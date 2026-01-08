<?php
require_once __DIR__ . "/../config/db.php";

/* วันที่ที่เลือก */
$selected_date = $_GET['date'] ?? date('Y-m-d');

/* จำนวนการจองทั้งหมด */
$sqlAll = "
SELECT COUNT(*) c
FROM reservations
WHERE reservation_date = ?
";
$stmt = $conn->prepare($sqlAll);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$totalBooking = $stmt->get_result()->fetch_assoc()['c'] ?? 0;

/* จำนวนที่เสร็จสิ้น */
$sqlDone = "
SELECT COUNT(*) c
FROM reservations
WHERE status = 'completed'
AND DATE(completed_at) = ?
";
$stmt = $conn->prepare($sqlDone);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$completed = $stmt->get_result()->fetch_assoc()['c'] ?? 0;

/* คำนวณเปอร์เซ็นต์ */
$percent = ($totalBooking > 0)
    ? round(($completed / $totalBooking) * 100)
    : 0;
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">

<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    

<div class="row g-4 mb-4">

<!-- 🟧 การจองทั้งหมด -->
<div class="col-md-6">
    <div class="summary-card orange">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="title">การจองทั้งหมด</div>
                <div class="value"><?= $totalBooking ?></div>
                <div class="sub">วันนี้</div>
            </div>
            <div class="icon"><i class="bi bi-calendar4"></i></div>
        </div>
    </div>
</div>

<!-- 🟩 เสร็จสิ้น -->
<div class="col-md-6">
    <div class="summary-card green">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="title">เสร็จสิ้น</div>
                <div class="value"><?= $completed ?></div>
                <div class="sub"><?= $percent ?>% ของทั้งหมด</div>
            </div>
            <div class="icon"><i class="bi bi-graph-up"></i></div>
        </div>
    </div>
</div>

</div>

<style>
.summary-card {
    border-radius: 16px;
    padding: 24px;
    color: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
}

.summary-card .title {
    font-size: 16px;
    opacity: .9;
}

.summary-card .value {
    font-size: 36px;
    font-weight: bold;
    line-height: 1.2;
}

.summary-card .sub {
    font-size: 14px;
    opacity: .85;
}

.summary-card .icon {
    font-size: 32px;
    opacity: .9;
}

/* สีการ์ด */
.summary-card.orange {
    background: linear-gradient(135deg, #ff9800, #ff6f00);
}

.summary-card.green {
    background: linear-gradient(135deg, #00c853, #00e676);
}
</style>
