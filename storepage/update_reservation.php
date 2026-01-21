<?php
require_once __DIR__ . "/../config/db.php";

/* รับค่าจากฟอร์ม */
$id_booking       = $_POST['id_booking'];
$customer_name    = $_POST['customer_name'];
$phone            = $_POST['phone'];
$reservation_date = $_POST['reservation_date'];
$reservation_time = $_POST['reservation_time'];
$table_id         = $_POST['table_id'];

/* UPDATE การจอง */
$sql = "
UPDATE reservations SET
    customer_name = ?,
    phone = ?,
    reservation_date = ?,
    reservation_time = ?,
    table_id = ?
WHERE id_booking = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssii",
    $customer_name,
    $phone,
    $reservation_date,
    $reservation_time,
    $table_id,
    $id_booking
);
$stmt->execute();

header("Location: store.php?link=list");
exit;
