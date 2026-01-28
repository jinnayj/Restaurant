<?php
require_once __DIR__ . "/../config/db.php";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ไม่พบรายการ");
}

/* เปลี่ยนสถานะเป็น using */
$stmt = $conn->prepare("
    UPDATE reservations
    SET status = 'using'
    WHERE id_booking = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

/* กลับหน้าเจ้าของร้าน */
header("Location: reservations.php?success=using");
exit;
