<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

$id = $_GET['id'];

$sql = "
SELECT r.*, t.table_number
FROM reservations r
JOIN tables t ON r.table_id = t.id
WHERE r.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

$tables = $conn->query("SELECT * FROM tables");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขการจอง</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">
<div class="container my-4">

<h4 class="mb-3">
    <i class="bi bi-pencil-square me-2"></i>
    แก้ไขการจอง
</h4>


<form method="post" action="update_reservation.php" class="card p-3 shadow-sm">
<input type="hidden" name="id" value="<?= $data['id']; ?>">

<label>ชื่อลูกค้า</label>
<input type="text" name="customer_name" class="form-control mb-2"
       value="<?= $data['customer_name']; ?>" required>

<label>เบอร์โทร</label>
<input type="text" name="phone" class="form-control mb-2"
       value="<?= $data['phone']; ?>" required>

<label>วันที่</label>
<input type="date" name="reservation_date" class="form-control mb-2"
       value="<?= $data['reservation_date']; ?>" required>

<label>เวลา</label>
<input type="time" name="reservation_time" class="form-control mb-2"
       value="<?= $data['reservation_time']; ?>" required>

<label>โต๊ะ</label>
<select name="table_id" class="form-select mb-3">
<?php while($t = $tables->fetch_assoc()): ?>
<option value="<?= $t['id']; ?>"
<?= $t['id']==$data['table_id']?'selected':''; ?>>
โต๊ะ <?= $t['table_number']; ?> (<?= $t['seat']; ?> ที่นั่ง)
</option>
<?php endwhile; ?>
</select>

<div class="d-flex gap-2">
    <button class="btn btn-success px-5">บันทึก</button>
    <a href="store.php?link=list" class="btn btn-danger px-5">ยกเลิก</a>
</div>

</form>

</div>
</body>
</html>
