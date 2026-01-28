<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__."/../config/db.php";

$_SESSION['customer_name']=$_POST['customer_name'];

$stmt=$conn->prepare("
INSERT INTO reservations
(customer_name,phone,table_id,reservation_date,reservation_time,status)
VALUES(?,?,?,?,?,'pending_payment')
");
$stmt->bind_param("ssiss",
$_POST['customer_name'],
$_POST['phone'],
$_POST['table_id'],
$_POST['reservation_date'],
$_POST['reservation_time']
);
$stmt->execute();

header("Location:customer.php?link=mybooking");
