<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

/* ===== วันที่ที่เลือก ===== */
$selected_date = $_GET['date'] ?? date('Y-m-d');

/* ===== Summary ===== */
$total = $conn->query("SELECT COUNT(*) c FROM tables")->fetch_assoc()['c'];
$available = $conn->query("SELECT COUNT(*) c FROM tables WHERE status='available'")->fetch_assoc()['c'];
$reserved = $conn->query("SELECT COUNT(*) c FROM tables WHERE status='reserved'")->fetch_assoc()['c'];
$using = $conn->query("SELECT COUNT(*) c FROM tables WHERE status='using'")->fetch_assoc()['c'];

/* ===== ดึงโต๊ะ + สถานะจากการจองจริง ===== */
$sql = "
SELECT 
    t.*,
    r.customer_name,
    r.phone,
    r.reservation_time,
    CASE
        WHEN t.status = 'using' THEN 'using'
        WHEN r.id IS NOT NULL THEN 'reserved'
        ELSE 'available'
    END AS booking_status
FROM tables t
LEFT JOIN reservations r 
    ON r.table_id = t.id
    AND r.reservation_date = ?
    AND r.status = 'confirmed'
ORDER BY t.id ASC
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$tables = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แผนผังโต๊ะ</title>

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
.available{
    background:#00c853;
    color:#fff;
}
.reserved{
    background:#ffc107;
    color:#000;
}
.using{
    background:#dc3545;
    color:#fff;
}
</style>
</head>

<body class="bg-light">

<div class="container my-4">

<!-- ===== เลือกวันที่ ===== -->
<form method="get" class="mb-4">
    <!-- สำคัญมาก ถ้าใช้ระบบ link -->
    <input type="hidden" name="link" value="table">

    <label class="fw-bold mb-1">📅 เลือกวันที่ดูการจอง</label>
    <input type="date"
           name="date"
           value="<?= $selected_date ?>"
           class="form-control w-25"
           onchange="this.form.submit()">
</form>
<!-- ===== Add booking ===== -->
<div class="card mb-4 shadow-sm">
    <div class="card-body text-end">
        <button class="btn btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#bookingModal">
            ➕ เพิ่มการจองใหม่
        </button>
    </div>
</div>
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
    <label>ชื่อลูกค้า</label>
    <input type="text" name="customer_name" class="form-control" required>
</div>

<div class="col-md-6">
    <label>เบอร์โทร</label>
    <input type="text" name="phone" class="form-control" required>
</div>

<div class="col-md-6">
    <label>วันที่</label>
    <input type="date"
           name="reservation_date"
           class="form-control"
           value="<?= $selected_date ?>"
           required>
</div>

<div class="col-md-6">
    <label>เวลา</label>
    <input type="time" name="reservation_time" class="form-control" required>
</div>

<div class="col-md-12">
    <label>โต๊ะ</label>
    <select name="table_id" class="form-select" required>
        <option value="">เลือกโต๊ะ</option>
        <?php
        $tb = $conn->query("SELECT * FROM tables ORDER BY table_number");
        while($t = $tb->fetch_assoc()):
        ?>
        <option value="<?= $t['id'] ?>">
            โต๊ะ <?= $t['table_number'] ?> (<?= $t['seat'] ?> ที่)
        </option>
        <?php endwhile; ?>
    </select>
</div>

</div>
</div>

<div class="modal-footer">
    <button class="btn btn-success">บันทึกการจอง</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        ยกเลิก
    </button>
</div>

</form>

</div>
</div>
</div>

<!-- ===== Summary ===== -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="p-3 bg-white rounded shadow-sm">
            โต๊ะทั้งหมด<br>
            <b><?= $total ?></b>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-white rounded shadow-sm text-success">
            ว่าง<br>
            <b><?= $available ?></b>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-white rounded shadow-sm text-warning">
            จองแล้ว<br>
            <b><?= $reserved ?></b>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-white rounded shadow-sm text-danger">
            กำลังใช้<br>
            <b><?= $using ?></b>
        </div>
    </div>
</div>

<!-- ===== แผนผังโต๊ะ ===== -->
 
<div class="card shadow-sm">
<div class="card-body">

    <div class="mt-3">
    <span class="badge bg-success">ว่าง</span>
    <span class="badge bg-warning text-dark ms-2">จองแล้ว</span>
    <span class="badge bg-danger ms-2">กำลังใช้</span>
</div>
<div class="mt-3">
<h6 class="fw-bold mb-3">แผนผังโต๊ะ (<?= $selected_date ?>)</h6>
</div>
<div class="table-area">
    
<?php while($t = $tables->fetch_assoc()): ?>
    <div class="table-box <?= $t['booking_status']; ?>"
         data-id="<?= $t['id']; ?>"
         data-table="<?= $t['table_number']; ?>"
         data-seat="<?= $t['seat']; ?>"
         data-customer="<?= $t['customer_name'] ?? ''; ?>"
         data-phone="<?= $t['phone'] ?? ''; ?>"
         data-time="<?= isset($t['reservation_time']) ? substr($t['reservation_time'],0,5) : ''; ?>"
         onclick="handleTableClick(this)">
         
        โต๊ะ <?= $t['table_number']; ?><br>
        👥 <?= $t['seat']; ?> ที่
    </div>
<?php endwhile; ?>
</div>


    




</div>
</div>

</div>

<!-- ===== Modal เปลี่ยนสถานะ ===== -->
<div class="modal fade" id="statusModal">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="change_table_status.php">
      <div class="modal-header">
        <h5 class="modal-title">เปลี่ยนสถานะโต๊ะ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="table_id" id="modal_table_id">
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

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function openStatusModal(el){
    document.getElementById("modal_table_id").value = el.dataset.id;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}
</script>


<div class="modal fade" id="detailModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">📋 รายละเอียดการจอง</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="detailBody"></div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
      </div>

    </div>
  </div>
</div>

<script>
function handleTableClick(el) {
    const customer = el.dataset.customer;

    // 🟢 โต๊ะว่าง → เปลี่ยนสถานะ
    if (!customer) {
        document.getElementById("modal_table_id").value = el.dataset.id;
        new bootstrap.Modal(document.getElementById('statusModal')).show();
        return;
    }

    // 🟡 โต๊ะที่มีการจอง → แสดงรายละเอียด
    let html = `
        <p><strong>🍽 โต๊ะ:</strong> ${el.dataset.table}</p>
        <p><strong>⏰ เวลา:</strong> ${el.dataset.time}</p>
        <p><strong>👤 ลูกค้า:</strong> ${el.dataset.customer}</p>
        <p><strong>📞 เบอร์โทร:</strong> ${el.dataset.phone}</p>
        <p><strong>👥 ที่นั่ง:</strong> ${el.dataset.seat} ที่</p>
    `;

    document.getElementById('detailBody').innerHTML = html;
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}
</script>


</body>
</html>
