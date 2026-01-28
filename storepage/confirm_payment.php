<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../config/db.php";

/* เช็กสิทธิ์เจ้าของร้าน */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'store_owner') {
    die('⛔ ไม่มีสิทธิ์เข้าถึง');
}

/* รับ id */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('❌ ไม่พบรายการ');
}

/* อัปเดตสถานะ */
$stmt = $conn->prepare("
    UPDATE reservations
    SET status = 'confirmed'
    WHERE id_booking = ?
      AND status = 'waiting_confirm'
");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    die('⚠️ รายการนี้ถูกยืนยันไปแล้ว หรือสถานะไม่ถูกต้อง');
}

/* กลับหน้าเจ้าของร้าน */
header("Location:store.php?link=list");
exit;
