<?php
session_start();
require_once __DIR__ . "/../../connect.php";


if ($_SESSION['role'] !== 'store_owner') {
    die("ไม่มีสิทธิ์เข้าถึง");
}

$staff = $conn->query("SELECT id, username FROM users WHERE role='staff'");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายชื่อพนักงาน</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">
<div class="container mt-4">

<h4 class="mb-3">👨‍🍳 รายชื่อพนักงาน</h4>

<a href="add_staff.php" class="btn btn-primary mb-3">➕ เพิ่มพนักงาน</a>

<table class="table table-bordered bg-white">
<tr>
    <th>#</th>
    <th>Username</th>
    <th width="180">จัดการ</th>
</tr>

<?php $i=1; while($row = $staff->fetch_assoc()): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td>
        <a href="edit_staff.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
            ✏️ แก้ไข
        </a>

        <a href="delete_staff.php?id=<?= $row['id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('ยืนยันลบพนักงานคนนี้?')">
           🗑️ ลบ
        </a>
    </td>
</tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>
