<?php
require_once __DIR__."/../config/db.php";

$id=$_POST['id_booking'];

$path="../uploads/slips/";
if(!is_dir($path)) mkdir($path,0777,true);

$name=time()."_".$_FILES['slip']['name'];
move_uploaded_file($_FILES['slip']['tmp_name'],$path.$name);

$stmt=$conn->prepare("
UPDATE reservations
SET slip_image=?,status='waiting_confirm'
WHERE id_booking=?
");
$stmt->bind_param("si",$name,$id);
$stmt->execute();

header("Location:customer.php?link=mybooking");
