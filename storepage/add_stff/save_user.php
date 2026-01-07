<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] !== 'store_owner') {
    die("ไม่อนุญาต");
}

require_once __DIR__ . '/../../connect.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$role = 'staff';

/* เช็กซ้ำ */
$check = $conn->prepare("SELECT id FROM users WHERE username=?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Username นี้มีผู้ใช้แล้ว');history.back();</script>";
    exit;
}

/* hash รหัสผ่าน */
$hash = password_hash($password, PASSWORD_DEFAULT);

/* insert */
$stmt = $conn->prepare(
    "INSERT INTO users (username,password,role) VALUES (?,?,?)"
);
$stmt->bind_param("sss", $username, $hash, $role);
$stmt->execute();

header("Location:../store.php?link=add");
exit;
