<?php
session_start();
require_once __DIR__ . "/../../connect.php";

if ($_SESSION['role'] !== 'store_owner') {
    die("ไม่มีสิทธิ์");
}

$id = $_POST['id'];
$username = trim($_POST['username']);
$password = trim($_POST['password']);

if ($password != '') {
    $stmt = $conn->prepare("UPDATE users SET username=?, password=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $password, $id);
} else {
    $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
    $stmt->bind_param("si", $username, $id);
}

$stmt->execute();

header("Location: ../store.php?link=add");
exit;
