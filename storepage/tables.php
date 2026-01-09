<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

/* ===== วันที่ที่เลือก ===== */
$selected_date = $_GET['date'] ?? date('Y-m-d');
$today = date('Y-m-d');
$isToday = ($selected_date === $today);

/* ===== Summary ===== */
$total = $conn->query("SELECT COUNT(*) c FROM tables")
              ->fetch_assoc()['c'];

$using = $conn->query("
    SELECT COUNT(DISTINCT table_id) c
    FROM reservations
    WHERE reservation_date = '$selected_date'
    AND status = 'using'
")->fetch_assoc()['c'];

$reserved = $conn->query("
    SELECT COUNT(DISTINCT table_id) c
    FROM reservations
    WHERE reservation_date = '$selected_date'
    AND status = 'confirmed'
")->fetch_assoc()['c'];

$available = $total - ($using + $reserved);

/* ===== ดึงโต๊ะ + สถานะ ===== */
$sql = "
SELECT 
    t.*,
    r.customer_name,
    r.phone,
    r.reservation_time,
    r.status AS reserve_status,
    CASE
        WHEN r.status = 'using' THEN 'using'
        WHEN r.status = 'confirmed' THEN 'reserved'
        ELSE 'available'
    END AS booking_status
FROM tables t
LEFT JOIN reservations r 
    ON r.table_id = t.id
    AND r.reservation_date = ?
    AND r.status IN ('confirmed','using')
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
.table-area{display:grid;grid-template-columns:repeat(5,1fr);gap:20px}
.table-box{padding:15px;border-radius:12px;text-align:center;font-weight:bold;cursor:pointer}
.available{background:#00c853;color:#fff}
.reserved{background:#ffc107;color:#000}
.using{background:#dc3545;color:#fff}
.opacity-50{opacity:.5;cursor:not-allowed}
.btn-orange-gradient{background:linear-gradient(135deg,#ff9800,#ff5722);color:#fff;border:none;font-weight:600;border-radius:10px}
.btn-orange-gradient:hover{background:linear-gradient(135deg,#ff5722,#e65100)}
.border-orange-right{border-right:4px solid #ff9800;color:#e65100}
</style>
</head>
<body>

<div class="card mb-4 shadow-sm">
<div class="card-body">
<form method="get" class="d-flex justify-content-between align-items-end">
<input type="hidden" name="link" value="table">

<div>
<label class="fw-bold mb-1"><i class="bi bi-calendar2 me-2"></i> เลือกวันที่</label>
<input type="date" name="date" value="<?= $selected_date ?>" class="form-control" style="width:200px" onchange="this.form.submit()">
</div>

<button type="button" class="btn btn-orange-gradient h-50" data-bs-toggle="modal" data-bs-target="#bookingModal">
<i class="bi bi-calendar2-plus me-2"></i> เพิ่มการจองใหม่
</button>
</form>
</div>
</div>

<!-- ===== Summary ===== -->
<div class="row g-3 mb-4">
<div class="col-md-3"><div class="p-3 bg-white rounded shadow-sm border-orange-right">โต๊ะทั้งหมด<br><b><?= $total ?></b></div></div>
<div class="col-md-3"><div class="p-3 bg-white rounded shadow-sm text-success border-end border-4 border-success">ว่าง<br><b><?= $available ?></b></div></div>
<div class="col-md-3"><div class="p-3 bg-white rounded shadow-sm text-danger border-end border-4 border-danger">กำลังใช้<br><b><?= $using ?></b></div></div>
</div>

<!-- ===== แผนผังโต๊ะ ===== -->
<div class="card shadow-sm">
<div class="card-body">

<div class="mb-3">
<span class="badge bg-success">ว่าง</span>
<span class="badge bg-warning text-dark ms-2">จองแล้ว</span>
<span class="badge bg-danger ms-2">กำลังใช้</span>
</div>

<div class="table-area">
<?php while($t = $tables->fetch_assoc()): ?>
<div class="table-box <?= $t['booking_status'] ?> <?= !$isToday ? 'opacity-50' : '' ?>"
     data-id="<?= $t['id'] ?>"
     data-table="<?= $t['table_number'] ?>"
     data-seat="<?= $t['seat'] ?>"
     data-customer="<?= $t['customer_name'] ?? '' ?>"
     data-phone="<?= $t['phone'] ?? '' ?>"
     data-time="<?= isset($t['reservation_time']) ? substr($t['reservation_time'],0,5) : '' ?>"
     onclick="handleTableClick(this)">
    โต๊ะ <?= $t['table_number'] ?><br>
    <small>
        <?= $t['booking_status']=='using'?'กำลังใช้':($t['booking_status']=='reserved'?'จองแล้ว':'ว่าง') ?>
    </small>
</div>
<?php endwhile; ?>
</div>
</div>
</div>

<!-- ===== Modal เปลี่ยนสถานะ ===== -->
<div class="modal fade" id="statusModal">
<div class="modal-dialog">
<form class="modal-content" method="post" action="change_table_status.php">
<input type="hidden" name="reservation_date" value="<?= $selected_date ?>">
<input type="hidden" name="table_id" id="modal_table_id">

<div class="modal-header"><h5 class="modal-title">เปลี่ยนสถานะโต๊ะ</h5></div>
<div class="modal-body">
<select name="status" class="form-select" required>
    <option value="available">ว่าง</option>
    <option value="confirmed">จองแล้ว</option>
    <option value="using">กำลังใช้</option>
</select>

</div>
<div class="modal-footer"><button class="btn btn-success">บันทึก</button></div>
</form>
</div>
</div>

<!-- ===== Modal รายละเอียด ===== -->
<div class="modal fade" id="detailModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header bg-warning"><h5 class="modal-title">📋 รายละเอียดการจอง</h5></div>
<div class="modal-body" id="detailBody"></div>
<div class="modal-footer"><button class="btn btn-danger" data-bs-dismiss="modal">ปิด</button></div>
</div>
</div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
const isToday = <?= $isToday ? 'true' : 'false' ?>;

function handleTableClick(el){
    const hasReservation = el.dataset.customer !== "";
    const status = el.classList.contains('using') ? 'using' :
                   el.classList.contains('reserved') ? 'reserved' : 'available';

    if (!isToday){
        if (hasReservation) showDetail(el);
        return;
    }

    if (status === 'available' || status === 'using'){
        document.getElementById("modal_table_id").value = el.dataset.id;
        new bootstrap.Modal(document.getElementById('statusModal')).show();
        return;
    }

    if (hasReservation) showDetail(el);
}

function showDetail(el){
    document.getElementById('detailBody').innerHTML = `
        <p><b>ลูกค้า:</b> ${el.dataset.customer}</p>
        <p><b>เบอร์:</b> ${el.dataset.phone}</p>
        <p><b>เวลา:</b> ${el.dataset.time}</p>
        <p><b>โต๊ะ:</b> ${el.dataset.table}</p>
        <p><b>ที่นั่ง:</b> ${el.dataset.seat}</p>`;
    new bootstrap.Modal(document.getElementById('detailModal')).show();
}
</script>

</body>
</html>
