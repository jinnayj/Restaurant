<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'store_owner') {
    die("ไม่อนุญาต");
}

require_once __DIR__ . '/../../connect.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = 'staff';

/* ตรวจค่าว่าง */
if ($username === '' || $password === '') {
    echo "<script>alert('กรุณากรอก Username และ Password');history.back();</script>";
    exit;
}

/* เช็ก username ซ้ำ */
$check = $conn->prepare("SELECT id_ser FROM users WHERE username = ?");
if (!$check) {
    die("Prepare failed (check): " . $conn->error);
}
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Username นี้มีผู้ใช้แล้ว');history.back();</script>";
    exit;
}

/* insert staff */
$stmt = $conn->prepare(
    "INSERT INTO users (username, password, role)
     VALUES (?, ?, ?)"
);
if (!$stmt) {
    die("Prepare failed (insert): " . $conn->error);
}

$stmt->bind_param("sss", $username, $password, $role);
$stmt->execute();

header("Location: ../store.php?link=add");
exit;