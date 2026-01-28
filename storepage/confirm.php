<?php
require_once __DIR__."/../config/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("
UPDATE reservations
SET status='confirmed'
WHERE id_booking=?
");
$stmt->bind_param("i",$id);
$stmt->execute();

header("Location:store.php?link=list");
exit;
