<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

/* ===== รับค่าจากฟอร์ม ===== */
$customer_name    = trim($_POST['customer_name'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$reservation_date = $_POST['reservation_date'] ?? '';
$reservation_time = $_POST['reservation_time'] ?? '';
$table_id         = (int)($_POST['table_id'] ?? 0);
$created_by       = $_SESSION['user_id'] ?? null;

/* ===== ตรวจค่าว่าง ===== */
if (
    $customer_name === '' ||
    $phone === '' ||
    $reservation_date === '' ||
    $reservation_time === '' ||
    $table_id === 0
) {
    echo "<script>alert('กรุณากรอกข้อมูลให้ครบ');history.back();</script>";
    exit;
}

/* ===== บันทึกการจอง ===== */
$stmt = $conn->prepare("
    INSERT INTO reservations
    (customer_name, phone, table_id, reservation_date, reservation_time, status, created_by)
    VALUES (?, ?, ?, ?, ?, 'confirmed', ?)
");

$stmt->bind_param(
    "ssissi",
    $customer_name,
    $phone,
    $table_id,
    $reservation_date,
    $reservation_time,
    $created_by
);

if ($stmt->execute()) {
    header("Location: staff.php?link=table");
    exit;
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}
