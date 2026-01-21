<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

$id = $_GET['id'] ?? 0;

/* ===== หาโต๊ะที่ถูกจอง ===== */
$stmt = $conn->prepare("
    SELECT table_id 
    FROM reservations 
    WHERE id_booking = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if ($res) {
    $table_id = $res['table_id'];

    /* ===== ลบการจอง ===== */
    $del = $conn->prepare("
        DELETE FROM reservations 
        WHERE id_booking = ?
    ");
    $del->bind_param("i", $id);
    $del->execute();

    /* ===== คืนสถานะโต๊ะ ===== */
    $upd = $conn->prepare("
        UPDATE tables 
        SET status = 'available' 
        WHERE id_show = ?
    ");
    $upd->bind_param("i", $table_id);
    $upd->execute();
}

header("Location: store.php?link=list");
exit;
