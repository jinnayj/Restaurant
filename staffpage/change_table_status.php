<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$table_id = $_POST['table_id'] ?? '';
$status   = $_POST['status'] ?? '';

if($table_id == '' || $status == ''){
    header("Location: store.php?link=table");
    exit;
}

$stmt = $conn->prepare("UPDATE tables SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $table_id);
$stmt->execute();

header("Location:staff.php?link=table");
exit;
