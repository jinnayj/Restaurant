<?php
require_once __DIR__."/../config/db.php";
$id=(int)$_GET['id'];

$q=$conn->query("
SELECT r.*,t.table_number,t.seat
FROM reservations r
JOIN tables t ON r.table_id=t.id_show
WHERE r.id_booking=$id
")->fetch_assoc();

$price=$q['seat']*100;
?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container my-5" style="max-width:480px">
<h4>ชำระเงิน</h4>
<h1 class="text-warning"><?= number_format($price) ?> บาท</h1>

<img src="../static/qr.jpg" width="260">

<form method="post" action="upload_slip.php" enctype="multipart/form-data">
<input type="hidden" name="id_booking" value="<?= $id ?>">
<input type="file" name="slip" class="form-control my-3" required>
<button class="btn btn-warning w-100">ยืนยัน</button>
</form>
</div>
</body>
</html>
