<?php
session_start();
require_once __DIR__ . "/../../connect.php";

$id_ser   = $_POST['id_ser'];
$username = $_POST['username'];
$password = $_POST['password'];

if ($password !== '') {
    // อัปเดตรหัสผ่านตรง ๆ
    $stmt = $conn->prepare(
        "UPDATE users SET username=?, password=? WHERE id_ser=?"
    );
    $stmt->bind_param("ssi", $username, $password, $id_ser);
} else {
    // ไม่เปลี่ยนรหัส
    $stmt = $conn->prepare(
        "UPDATE users SET username=? WHERE id_ser=?"
    );
    $stmt->bind_param("si", $username, $id_ser);
}

$stmt->execute();
header("Location: ../store.php?link=add");
exit;
