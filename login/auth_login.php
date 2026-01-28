<?php
session_start();
require_once __DIR__ . "/../config/db.php";

/* =====================
   สมัครสมาชิก
===================== */
if (isset($_POST['register'])) {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // เช็คอีเมลซ้ำ
    $chk = $conn->prepare("SELECT id_ct FROM customer WHERE email = ?");
    $chk->bind_param("s", $email);
    $chk->execute();
    $chk->store_result();

    if ($chk->num_rows > 0) {
        die("❌ อีเมลนี้ถูกใช้แล้ว");
    }

    // บันทึกข้อมูล
    $stmt = $conn->prepare("
        INSERT INTO customer (name, email, password)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();

    // login อัตโนมัติ
    $_SESSION['customer_id'] = $stmt->insert_id;
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;

    header("Location: login_ct.php");
    exit;
}

/* =====================
   Login ลูกค้า
===================== */
$email    = $_POST['email']; 
$password = $_POST['password'];

$stmt = $conn->prepare("
    SELECT id_ct, name, password
    FROM customer
    WHERE email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ ไม่พบผู้ใช้งาน");
}

$user = $result->fetch_assoc();

// เทียบรหัสผ่านตรง ๆ
if ($password !== $user['password']) {
    die("❌ รหัสผ่านไม่ถูกต้อง");
}

// login สำเร็จ
$_SESSION['customer_id'] = $user['id_ct'];
$_SESSION['customer_name'] = $user['name'];
$_SESSION['customer_email'] = $email;

header("Location: ../customer/customer.php?link=table");
exit;
