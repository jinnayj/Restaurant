<?php
require_once __DIR__ . "/../config/db.php";

/* ===== โต๊ะที่มีการจองวันนี้ ===== */
$todayTable = $conn->query("
    SELECT COUNT(DISTINCT table_id) c
    FROM reservations
    WHERE reservation_date = CURDATE()
")->fetch_assoc()['c'];

/* ===== จำนวนการจองวันนี้ ===== */
$todayBooking = $conn->query("
    SELECT COUNT(*) c
    FROM reservations
    WHERE reservation_date = CURDATE()
")->fetch_assoc()['c'];

/* ===== โต๊ะยอดนิยม ===== */
$popular = $conn->query("
    SELECT t.table_number, COUNT(r.id) total
    FROM reservations r
    JOIN tables t ON r.table_id = t.id
    WHERE r.reservation_date = CURDATE()
    GROUP BY r.table_id
    ORDER BY total DESC
    LIMIT 1
")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>สรุปยอดวันนี้</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">
<div class="container my-4">

<h4 class="mb-4">📊 สรุปการจองวันนี้</h4>

<div class="row g-3">

<!-- จำนวนการจอง -->
<div class="col-md-4">
<div class="card shadow-sm">
<div class="card-body text-center">
<h6>จำนวนการจองวันนี้</h6>
<h3 class="text-primary"><?= $todayBooking ?></h3>
</div>
</div>
</div>

<!-- โต๊ะที่ถูกใช้งาน -->
<div class="col-md-4">
<div class="card shadow-sm">
<div class="card-body text-center">
<h6>โต๊ะที่มีการใช้งาน</h6>
<h3 class="text-success"><?= $todayTable ?></h3>
</div>
</div>
</div>

<!-- โต๊ะยอดนิยม -->
<div class="col-md-4">
<div class="card shadow-sm">
<div class="card-body text-center">
<h6>โต๊ะยอดนิยมวันนี้</h6>
<?php if($popular): ?>
<h4 class="text-danger">
โต๊ะ <?= $popular['table_number']; ?>
</h4>
<p>ถูกจอง <?= $popular['total']; ?> ครั้ง</p>
<?php else: ?>
<p class="text-muted">ยังไม่มีการจอง</p>
<?php endif; ?>
</div>
</div>
</div>

</div>

</div>
</body>
</html>
