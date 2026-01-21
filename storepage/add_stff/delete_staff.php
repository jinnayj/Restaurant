<?php
session_start();
require_once __DIR__ . "/../../connect.php";

if ($_SESSION['role'] !== 'store_owner') {
    die("ไม่มีสิทธิ์");
}

$id = $_GET['id_ser'] ?? '';

if ($id == '') {
    header("Location: ../store.php?link=add");
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id_ser = ? AND role = 'staff'");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../store.php?link=add");
exit;
