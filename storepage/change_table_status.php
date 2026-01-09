<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$table_id = $_POST['table_id'] ?? null;
$status   = $_POST['status'] ?? null;
$reservation_date = $_POST['reservation_date'] ?? null;

$today = date('Y-m-d');

if (!$table_id || !$status || !$reservation_date) {
    header("Location: store.php");
    exit;
}

/* ❌ ไม่ใช่วันนี้ ห้ามแก้ */
if ($reservation_date !== $today) {
    header("Location: store.php?link=table");
    exit;
}

/* ===== ถ้าเลือก "ว่าง" → ลบ reservation ===== */
if ($status === 'available') {

    $del = $conn->prepare("
        DELETE FROM reservations
        WHERE table_id = ?
        AND reservation_date = ?
        AND status IN ('confirmed','using')
    ");
    $del->bind_param("is", $table_id, $reservation_date);
    $del->execute();

    header("Location:store.php?link=table");
    exit;
}

/* ===== ตรวจว่ามี reservation อยู่ไหม ===== */
$check = $conn->prepare("
    SELECT id FROM reservations
    WHERE table_id = ?
    AND reservation_date = ?
    AND status IN ('confirmed','using')
");
$check->bind_param("is", $table_id, $reservation_date);
$check->execute();
$res = $check->get_result();

/* ===== มี → UPDATE ===== */
if ($res->num_rows > 0) {

    $row = $res->fetch_assoc();
    $rid = $row['id'];

    $upd = $conn->prepare("
        UPDATE reservations
        SET status = ?
        WHERE id = ?
    ");
    $upd->bind_param("si", $status, $rid);
    $upd->execute();

/* ===== ไม่มี → INSERT ===== */
} else {

    $ins = $conn->prepare("
        INSERT INTO reservations (table_id, reservation_date, status)
        VALUES (?, ?, ?)
    ");
    $ins->bind_param("iss", $table_id, $reservation_date, $status);
    $ins->execute();
}

header("Location:store.php?link=table");
exit;
