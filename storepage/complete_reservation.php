<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "
        UPDATE reservations
        SET status = 'completed',
            completed_at = NOW()
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location:store.php?link=list");
exit;
