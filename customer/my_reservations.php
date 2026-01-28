<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__."/../config/db.php";

$name=$_SESSION['customer_name'] ?? '';
$stmt=$conn->prepare("
SELECT r.*,t.table_number,t.seat
FROM reservations r
JOIN tables t ON r.table_id=t.id_show
WHERE r.customer_name=?
ORDER BY r.id_booking DESC
");
$stmt->bind_param("s",$name);
$stmt->execute();
$res=$stmt->get_result();
?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<style>
.orange{background:linear-gradient(135deg,#ff9800,#ffb74d);color:#fff}
</style>
</head>
<body class="bg-light">
<div class="container my-4">
<h4>รายการจองของฉัน</h4>
<table class="table bg-white">
<tr><th>โต๊ะ</th><th>วัน</th><th>เวลา</th><th>สถานะ</th><th></th></tr>
<?php while($r=$res->fetch_assoc()): ?>
<tr>
<td><?= $r['table_number'] ?></td>
<td><?= $r['reservation_date'] ?></td>
<td><?= substr($r['reservation_time'],0,5) ?></td>
<td>
<?php
if($r['status']=='pending_payment') echo '<span class="badge orange">รอชำระ</span>';
elseif($r['status']=='waiting_confirm') echo '<span class="badge orange">รอยืนยัน</span>';
elseif($r['status']=='confirmed') echo '<span class="badge bg-warning">จองแล้ว</span>';
else echo '<span class="badge bg-danger">ใช้งาน</span>';
?>
</td>
<td>
<?php if($r['status']=='pending_payment'): ?>
<a href="payment.php?id=<?= $r['id_booking'] ?>" class="btn btn-sm btn-warning">ชำระ</a>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
</body>
</html>
