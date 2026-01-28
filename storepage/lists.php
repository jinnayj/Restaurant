<?php
require_once __DIR__."/../config/db.php";

$stmt = $conn->query("
SELECT 
    r.*,
    t.table_number,
    t.seat
FROM reservations r
JOIN tables t ON r.table_id = t.id_show
ORDER BY r.reservation_date DESC, r.id_booking DESC
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการการจอง</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<style>
.badge-orange{
    background:linear-gradient(135deg,#ff9800,#ffb74d);
    color:#fff;
    font-weight:600;
    border-radius:8px;
    padding:4px 10px;
}
</style>
</head>

<body class="bg-light">
<div class="container my-4">

<h4 class="mb-4">📋 รายการจองทั้งหมด (เจ้าของร้าน)</h4>

<table class="table table-bordered bg-white">
<thead class="table-dark">
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

<?php while($r = $stmt->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($r['customer_name']) ?></td>
<td>โต๊ะ <?= $r['table_number'] ?></td>
<td><?= $r['reservation_date'] ?></td>
<td><?= substr($r['reservation_time'],0,5) ?></td>
<td><?= $r['seat'] ?> คน</td>

<td>
<?php
if ($r['status'] === 'pending_payment') {
    echo '<span class="badge badge-orange">รอชำระเงิน</span>';
} elseif ($r['status'] === 'waiting_confirm') {
    echo '<span class="badge badge-orange">รอยืนยัน</span>';
} elseif ($r['status'] === 'confirmed') {
    echo '<span class="badge bg-warning text-dark">จองแล้ว</span>';
} elseif ($r['status'] === 'using') {
    echo '<span class="badge bg-danger">กำลังใช้</span>';
}
?>
</td>

<td class="text-center">
<?php if(!empty($r['slip_image'])): ?>
<a href="../uploads/slips/<?= $r['slip_image'] ?>" target="_blank"
   class="btn btn-sm btn-outline-secondary">
ดูสลิป
</a>
<?php else: ?>
-
<?php endif; ?>
</td>

<td>
<?php if($r['status'] === 'waiting_confirm'): ?>
<a href="confirm_payment.php?id=<?= $r['id_booking'] ?>"
   class="btn btn-sm btn-success">
ยืนยันสลิป
</a>
<?php endif; ?>

<?php if($r['status'] === 'confirmed'): ?>
<a href="update_status.php?id=<?= $r['id_booking'] ?>&status=using"
   class="btn btn-sm btn-danger">
เริ่มใช้งาน
</a>
<?php endif; ?>

<?php if($r['status'] === 'using'): ?>
<a href="update_status.php?id=<?= $r['id_booking'] ?>&status=finished"
   class="btn btn-sm btn-secondary">
เสร็จสิ้น
</a>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>
</body>
</html>
