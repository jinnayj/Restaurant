<?php
require_once __DIR__."/../config/db.php";

$id = (int)($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';

$allow = ['using','finished'];
if(!in_array($status,$allow)){
    die('สถานะไม่ถูกต้อง');
}

$stmt = $conn->prepare("
UPDATE reservations
SET status=?
WHERE id_booking=?
");
$stmt->bind_param("si",$status,$id);
$stmt->execute();

header("Location:store.php?link=list");
exit;
