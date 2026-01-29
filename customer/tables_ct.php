<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__."/../config/db.php";

$selected_date = $_GET['reservation_date'] ?? date('Y-m-d');
$today = date('Y-m-d');
$maxDate = date('Y-m-d', strtotime('+7 days'));

/* summary */
$total = $conn->query("SELECT COUNT(*) c FROM tables")->fetch_assoc()['c'];

$using = $conn->query("
SELECT COUNT(DISTINCT table_id) c FROM reservations
WHERE reservation_date='$selected_date' AND status='using'
")->fetch_assoc()['c'];

$pending = $conn->query("
SELECT COUNT(DISTINCT table_id) c FROM reservations
WHERE reservation_date='$selected_date'
AND status IN('pending_payment','waiting_confirm')
")->fetch_assoc()['c'];

$confirmed = $conn->query("
SELECT COUNT(DISTINCT table_id) c FROM reservations
WHERE reservation_date='$selected_date' AND status='confirmed'
")->fetch_assoc()['c'];

$available = $total - ($using+$pending+$confirmed);

/* tables */
$stmt=$conn->prepare("
SELECT t.*,
(
 SELECT r.status FROM reservations r
 WHERE r.table_id=t.id_show
 AND r.reservation_date=?
 ORDER BY r.id_booking DESC LIMIT 1
) booking_status
FROM tables t
ORDER BY CAST(t.table_number AS UNSIGNED)
");
$stmt->bind_param("s",$selected_date);
$stmt->execute();
$tables=$stmt->get_result();
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<style>
    
/* รอการยืนยัน (ส้ม) */
.table-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:16px;
}

.box{
    padding:16px;
    border-radius:16px;
    text-align:center;
    font-weight:600;
    box-shadow:0 4px 10px rgba(0,0,0,.12);
    transition:transform .2s, box-shadow .2s;
}

/* รอการยืนยัน (ส้ม) */
.border-right{
    border-right:6px solid #ff8121 !important; /* ส้มเข้ม */
}

.text-orange{
    color:#ff6f00 !important;
    font-weight:700;
}

.border-right{
    border-right:6px solid #ff6f00 !important; /* ส้มเข้ม */
}

.text-orange{
    color:#ff6f00 !important;
    font-weight:700;
}

.pending{
    background:#ff6f00;   /* ส้มชัด */
    color:#fff;
}


/* จองแล้ว (เหลือง) */
.confirmed{
    background:linear-gradient(135deg,#ffc107,#ffe082);
    color:#000;
}

/* กำลังใช้งาน (แดง) */
.using{
    background:linear-gradient(135deg,#dc3545,#ff6f61);
    color:#fff;
}
</style>


</head>
<body class="bg-light">

<div class="container my-4">

<div class="card mb-4 shadow-sm">
<div class="card-body">
<form method="get" class="d-flex justify-content-between align-items-end">
<input type="hidden" name="link" value="table">

<form>
  <label class="form-label fw-bold text-muted"><i class="bi bi-calendar2 me-2"></i>เลือกวันที่</label>
  <input type="date"
         name="reservation_date"
         value="<?= $selected_date ?>"
         min="<?= $today ?>"
         max="<?= $maxDate ?>"
         class="form-control" style="width:200px"
         onchange="this.form.submit()">
</form>

</div>
</div>

<div class="row g-3 mb-4">
  <!-- ว่าง -->
  <div class="col-md-3">
    <div class="p-3 bg-white rounded shadow-sm text-success border-end border-4 border-success">
      ว่าง<br>
      <b><?= $available ?></b>
    </div>
  </div>

  <!-- รอการยืนยัน (ส้ม) -->
  <div class="col-md-3">
   <div class="p-3 bg-white rounded shadow-sm text-orange border-right border-4">
      รอการยืนยัน<br>
      <b><?= $pending ?></b>
    </div>
  </div>

  <!-- จองแล้ว (เหลือง) -->
  <div class="col-md-3">
    <div class="p-3 bg-white rounded shadow-sm text-warning border-end border-4 border-warning">
      จองแล้ว<br>
      <b><?= $confirmed ?></b>
    </div>
  </div>

  <!-- กำลังใช้ -->
  <div class="col-md-3">
    <div class="p-3 bg-white rounded shadow-sm text-danger border-end border-4 border-danger">
      กำลังใช้<br>
      <b><?= $using ?></b>
    </div>
  </div>
</div>



<div class="card shadow-sm">
<div class="card-body">

<div class="mb-3">
  <span class="badge bg-success">ว่าง</span>

  <span class="badge ms-2"
        style="background:#ff9800;color:#fff;">
    รอการยืนยัน
  </span>

  <span class="badge ms-2"
        style="background:#ffc107;color:#000;">
    จองแล้ว
  </span>

  <span class="badge bg-danger ms-2">
    กำลังใช้
  </span>
</div>

<div class="table-grid">
<?php while($t=$tables->fetch_assoc()):
$s=$t['booking_status'];
if(!$s){$c='available';$l='ว่าง';}
elseif(in_array($s,['pending_payment','waiting_confirm'])){$c='pending';$l='รอชำระ';}
elseif($s==='confirmed'){$c='confirmed';$l='จองแล้ว';}
else{$c='using';$l='ใช้งาน';}
?>
<div class="box <?= $c ?>"
<?= $c==='available'?"onclick=\"openModal({$t['id_show']})\"":"" ?>>
โต๊ะ <?= $t['table_number'] ?><br>👥
<?= $t['seat'] ?> คน<br>
<small><?= $l ?></small>
</div>
<?php endwhile;?>
</div>
</div>

<!-- modal -->
<div class="modal fade" id="m">
<div class="modal-dialog">
<form class="modal-content" method="post" action="save_reservation.php">
<div class="modal-body">
<input type="hidden" name="table_id" id="tid">
<input type="hidden" name="reservation_date" value="<?= $selected_date ?>">
<input class="form-control mb-2" name="customer_name" placeholder="ชื่อ" required>
<input class="form-control mb-2" name="phone" placeholder="เบอร์" required>
<input class="form-control mb-2" type="time" name="reservation_time" required>
<button class="btn btn-success w-100">จอง</button>
</div>
</form>
</div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function openModal(id){
document.getElementById('tid').value=id;
new bootstrap.Modal(document.getElementById('m')).show();
}
</script>
</body>
</html>
