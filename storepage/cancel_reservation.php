<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

$id = $_GET['id'] ?? 0;

// หาโต๊ะที่ถูกจอง
$stmt = $conn->prepare("SELECT table_id FROM reservations WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if ($res) {
    $table_id = $res['table_id'];

    // ลบการจอง
    $conn->query("DELETE FROM reservations WHERE id='$id'");

    // คืนสถานะโต๊ะ
    $conn->query("UPDATE tables SET status='available' WHERE id='$table_id'");
}

header("Location: store.php?link=list");
exit;
