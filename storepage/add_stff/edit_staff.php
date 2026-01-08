<?php

require_once __DIR__ . "/../../connect.php";

if ($_SESSION['role'] !== 'store_owner') {
    die("ไม่มีสิทธิ์");
}

$id = $_GET['id'] ?? '';

$stmt = $conn->prepare("SELECT username FROM users WHERE id=? AND role='staff'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("ไม่พบพนักงาน");
}

$staff = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขพนักงาน</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">
<div class="container mt-4">

<h4> <i class="bi bi-pencil-square me-2"></i>แก้ไขพนักงาน</h4>

<form method="post" action="add_stff/update_staff.php">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control"
               value="<?= htmlspecialchars($staff['username']) ?>" required>
    </div>

    <div class="mb-3">
        <label>รหัสผ่านใหม่</label>
        <input type="password" name="password" class="form-control">
    </div>

    <button class="btn btn-success">บันทึก</button>
    <a href="store.php?link=add" class="btn btn-danger">กลับ</a>
</form>

</div>
</body>
</html>
