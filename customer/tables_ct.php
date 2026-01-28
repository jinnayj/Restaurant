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
.table-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}
.box{padding:16px;border-radius:14px;color:#fff;text-align:center;font-weight:600}
.available{background:#00c853;cursor:pointer}
.pending{background:linear-gradient(135deg,#ff9800,#ffb74d)}
.confirmed{background:#ffc107;color:#000}
.using{background:#dc3545}
</style>
</head>
<body class="bg-light">

<div class="container my-4">

<form>
<input type="date" name="reservation_date"
value="<?= $selected_date ?>"
min="<?= $today ?>" max="<?= $maxDate ?>"
onchange="this.form.submit()">
</form>

<div class="row g-3 my-3">
<div class="col">ว่าง <?= $available ?></div>
<div class="col">รอ <?= $pending ?></div>
<div class="col">จองแล้ว <?= $confirmed ?></div>
<div class="col">ใช้งาน <?= $using ?></div>
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
โต๊ะ <?= $t['table_number'] ?><br>
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
