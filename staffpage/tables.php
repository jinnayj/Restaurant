<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

/* ===== Summary ===== */
$total = $conn->query("SELECT COUNT(*) c FROM tables")->fetch_assoc()['c'];
$available = $conn->query("SELECT COUNT(*) c FROM tables WHERE status='available'")->fetch_assoc()['c'];
$reserved = $conn->query("SELECT COUNT(*) c FROM tables WHERE status='reserved'")->fetch_assoc()['c'];
$using = $conn->query("SELECT COUNT(*) c FROM tables WHERE status='using'")->fetch_assoc()['c'];

/* ===== Tables ===== */
$tables = $conn->query("SELECT * FROM tables ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ระบบจองโต๊ะ</title>

<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<style>
.table-area{
    display:grid;
    grid-template-columns: repeat(5, 1fr);
    gap:20px;
}
.table-box{
    padding:15px;
    border-radius:12px;
    text-align:center;
    font-weight:bold;
    cursor:pointer;
}
.available{ background:#00c853; color:#fff; }
.reserved{ background:#ffc107; color:#000; }
.using{ background:#dc3545; color:#fff; }

.summary-card{
    padding:15px;
    border-radius:12px;
    background:#fff;
}
.border-orange{ border-left:6px solid #ff7a00; }
.border-green{ border-left:6px solid #00c853; }
.border-yellow{ border-left:6px solid #ffc107; }
.border-red{ border-left:6px solid #dc3545; }

.text-orange{ color:#ff7a00; }
.text-green{ color:#00c853; }
.text-yellow{ color:#ffc107; }
.text-red{ color:#dc3545; }

.btn-orange{
    background:#ff7a00;
    color:#fff;
}

</style>
</head>

<body class="bg-light">

<div class="container my-4">

    <!-- ===== Summary ===== -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="summary-card border-orange">
                <p>โต๊ะทั้งหมด</p>
                <h6 class="text-orange"><?= $total ?> โต๊ะ</h6>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card border-green">
                <p>โต๊ะว่าง</p>
                <h6 class="text-green"><?= $available ?> โต๊ะ</h6>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card border-yellow">
                <p>จองแล้ว</p>
                <h6 class="text-yellow"><?= $reserved ?> โต๊ะ</h6>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card border-red">
                <p>กำลังใช้</p>
                <h6 class="text-red"><?= $using ?> โต๊ะ</h6>
            </div>
        </div>
    </div>

    <!-- ===== Add booking ===== -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <button class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#bookingModal">
                ➕ เพิ่มการจองใหม่
            </button>
        </div>
    </div>

    <!-- ===== Table layout ===== -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="fw-bold text-orange mb-3">แผนผังโต๊ะภายในร้าน</h6>

           <div class="table-area">
<?php while($t = $tables->fetch_assoc()): ?>
    <div class="table-box <?= $t['status']; ?>"
         data-id="<?= $t['id']; ?>"
         data-status="<?= $t['status']; ?>"
         onclick="openStatusModal(this)">
        โต๊ะ <?= $t['table_number']; ?><br>
        👥 <?= $t['seat']; ?> ที่
    </div>
<?php endwhile; ?>
</div>


            <div class="mt-3">
                <span class="badge bg-success">ว่าง</span>
                <span class="badge bg-danger ms-2">กำลังใช้</span>
            </div>
        </div>
    </div>

</div>

<!-- ===== Modal เพิ่มการจอง ===== -->
<div class="modal fade" id="bookingModal">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form action="save_reservation.php" method="post">

<div class="modal-header bg-warning">
    <h5 class="modal-title">เพิ่มการจองใหม่</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="row g-3">

<div class="col-md-6">
    <label>ชื่อลูกค้า *</label>
    <input type="text" name="customer_name" class="form-control" required>
</div>

<div class="col-md-6">
    <label>เบอร์โทรศัพท์ *</label>
    <input type="text" name="phone" class="form-control" required>
</div>

<div class="col-md-6">
    <label>วันที่ *</label>
    <input type="date" name="reservation_date" class="form-control" required>
</div>

<div class="col-md-6">
    <label>เวลา *</label>
    <input type="time" name="reservation_time" class="form-control" required>
</div>

<div class="col-md-6">
    <label>โต๊ะ *</label>
    <select name="table_id" class="form-select" required>
        <option value="">เลือกโต๊ะ</option>
        <?php
        $ts = $conn->query("SELECT * FROM tables WHERE status='available'");
        while($tb = $ts->fetch_assoc()):
        ?>
        <option value="<?= $tb['id']; ?>">
            โต๊ะ <?= $tb['table_number']; ?> (<?= $tb['seat']; ?> ที่)
        </option>
        <?php endwhile; ?>
    </select>
</div>

</div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-success">บันทึกการจอง</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
</div>

</form>

</div>
</div>
</div>


<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function openStatusModal(el){
    const tableId = el.getAttribute("data-id");
    document.getElementById("modal_table_id").value = tableId;

    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}
</script>

<!-- Change Table Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="change_table_status.php">

      <div class="modal-header">
        <h5 class="modal-title">เปลี่ยนสถานะโต๊ะ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="table_id" id="modal_table_id">

        <label class="mb-2">สถานะโต๊ะ</label>
        <select name="status" class="form-select" required>
          <option value="available">ว่าง</option>
          <option value="using">กำลังใช้</option>
        </select>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">บันทึก</button>
      </div>

    </form>
  </div>
</div>

</body>
</html>
