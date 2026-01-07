<?php
session_start();
require '../connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    
    if ($password == $user['password']) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // แยกตาม role
        if ($user['role'] == 'store_owner') {
            header("Location: ../storepage/store.php?link=homes");
        } else if ($user['role'] == 'staff') {
            header("Location: ../staffpage/staff.php?link=homes");
        }

    } else {
        echo "<script>alert('รหัสผ่านไม่ถูกต้อง');history.back();</script>";
    }

} else {
    echo "<script>alert('ไม่พบบัญชีผู้ใช้');history.back();</script>";
}
