<?php
require_once __DIR__."/../config/db.php";

/* ===== รับวันที่ ===== */
$selected_date = $_GET['date'] ?? null;

/* ===== SQL ===== */
$sql = "
SELECT 
    r.*,
    t.table_number,
    t.seat
FROM reservations r
JOIN tables t ON r.table_id = t.id_show
";

$params = [];
$types  = "";

$where = [];

if (!empty($selected_date)) {
    $where[] = "r.reservation_date = ?";
    $params[] = $selected_date;
    $types .= "s";
}

if (!empty($booking_by)) {
    $where[] = "r.booking_by = ?";
    $params[] = $booking_by;
    $types .= "s";
}

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}


$sql .= " ORDER BY r.reservation_date DESC, r.id_booking DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการการจอง | เจ้าของร้าน</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<style>
/* ===== พื้นหลัง ===== */
body{
    min-height:100vh;
    background: linear-gradient(135deg, #ffd194, #ffef8a);
    font-family: 'Segoe UI', sans-serif;
}

/* ===== Card ===== */
.card{
    border-radius:20px;
    border:none;
    background:#fff;
}

/* ===== หัวข้อ ===== */
h4{
    color:#000000;
    font-weight:800;
}

/* ===== Filter box ===== */
.filter-box{
    background:#fff7e6;
    padding:18px;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}

/* ===== ตาราง ===== */
.table{
    border-radius:14px;
    overflow:hidden;
}
.table thead{
    background:#ff9800;
    color:#fff;
}
.table tbody tr:hover{
    background:#fff3cd;
}

/* ===== Badge ===== */
.badge-orange{
    background:linear-gradient(135deg,#ff9800,#ffb74d);
    color:#fff;
    font-weight:600;
    border-radius:10px;
    padding:6px 12px;
}

/* ===== ปุ่ม ===== */
.btn-warning{
    background:#ff9800;
    border:none;
    color:#fff;
}
.btn-warning:hover{
    background:#fb8c00;
}
.btn-success{background:#43a047;border:none}
.btn-danger{background:#e53935;border:none}
.btn-secondary{background:#8d6e63;border:none}

/* ===== ลิงก์ดูสลิป ===== */
.btn-outline-secondary{
    border-radius:8px;
}
</style>
</head>

<body>
<div class="container my-4">

<div class="card shadow-lg">
<div class="card-body p-4">

<h4 class="mb-4">🍽️ จัดการการจอง (เจ้าของร้าน)</h4>

<!-- ===== เลือกวัน ===== -->
<form method="get" class="filter-box mb-4 d-flex align-items-end">
    <input type="hidden" name="link" value="list">

    <div class="me-3">
        <label class="fw-bold text-muted mb-2">เลือกวันที่</label>
        <input type="date"
               name="date"
               value="<?= htmlspecialchars($selected_date ?? '') ?>"
               class="form-control">
    </div>

    <button type="submit" class="btn btn-warning px-4 me-3">
        🔍 ดูรายการ
    </button>

    <?php if($selected_date): ?>
        <a href="store.php?link=list" class="btn btn-outline-secondary">
            ดูทั้งหมด
        </a>
    <?php endif; ?>
</form>


<!-- ===== ตาราง ===== -->
<table class="table table-bordered align-middle bg-white">
<thead>
<tr>
  <th>ลูกค้า</th>
  <th>โต๊ะ</th>
  <th>วันที่</th>
  <th>เวลา</th>
  <th>ที่นั่ง</th>
  <th>สถานะ</th>
  <th>สลิป</th>
  <th>จัดการ</th>
</tr>
</thead>
<tbody>

<?php if($result->num_rows === 0): ?>
<tr>
<td colspan="8" class="text-center text-muted py-4">
ไม่มีรายการจอง
</td>
</tr>
<?php endif; ?>

<?php while($r = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($r['customer_name']) ?></td>
<td>โต๊ะ <?= $r['table_number'] ?></td>
<td><?= $r['reservation_date'] ?></td>
<td><?= substr($r['reservation_time'],0,5) ?></td>
<td><?= $r['seat'] ?> คน</td>

<td>
<?php
switch($r['status']){
    case 'pending_payment':
        echo '<span class="badge badge-orange">รอชำระเงิน</span>'; break;
    case 'waiting_confirm':
        echo '<span class="badge badge-orange">รอยืนยัน</span>'; break;
    case 'confirmed':
        echo '<span class="badge bg-warning text-dark">จองแล้ว</span>'; break;
    case 'using':
        echo '<span class="badge bg-danger">กำลังใช้</span>'; break;
    case 'finished':
        echo '<span class="badge bg-secondary">เสร็จสิ้น</span>'; break;
}
?>
</td>

<td class="text-center">
<?php if($r['slip_image']): ?>
<a href="../uploads/slips/<?= $r['slip_image'] ?>" target="_blank"
   class="btn btn-sm btn-outline-secondary">
ดูสลิป
</a>
<?php else: ?>-<?php endif; ?>
</td>

<td>
<?php if($r['status'] === 'waiting_confirm'): ?>
<a href="confirm_payment.php?id=<?= $r['id_booking'] ?>"
   class="btn btn-sm btn-success mb-1">
ยืนยันสลิป
</a>
<?php endif; ?>

<?php if($r['status'] === 'confirmed'): ?>
<a href="update_status.php?id=<?= $r['id_booking'] ?>&status=using"
   class="btn btn-sm btn-danger mb-1"
   onclick="return confirm('เริ่มใช้งานโต๊ะนี้?')">
เริ่มใช้งาน
</a>
<?php endif; ?>

<?php if($r['status'] === 'using'): ?>
<a href="update_status.php?id=<?= $r['id_booking'] ?>&status=finished"
   class="btn btn-sm btn-secondary mb-1"
   onclick="return confirm('จบการใช้งานโต๊ะนี้?')">
เสร็จสิ้น
</a>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>
</div>
</div>
</body>
</html>
