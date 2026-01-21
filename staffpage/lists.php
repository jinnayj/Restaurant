<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

/* รับวันที่จากฟอร์ม */
$selected_date = $_GET['date'] ?? date('Y-m-d');

/* ดึงรายการจองที่ยังยืนยัน */
$sql = "
SELECT 
    r.id_booking,
    r.customer_name,
    r.phone,
    r.reservation_time,
    r.status,
    t.table_number,
    t.seat
FROM reservations r
JOIN tables t ON r.table_id = t.id_show
WHERE r.reservation_date = ?
AND r.status = 'confirmed'
ORDER BY r.reservation_time ASC
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายการจอง</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body class="bg-light">
<div class="container my-4">

<h4 class="mb-3 text-dark">
<i class="bi bi-calendar2-check-fill me-2 "></i></i></i>รายการจองวันที่ <?= date('d/m/Y', strtotime($selected_date)); ?>
</h4>

<form method="get" class="row g-2 mb-3">
    <input type="hidden" name="link" value="list">

    <div class="col-md-4">
        <input type="date"
               name="date"
               value="<?= $selected_date; ?>"
               class="form-control">
    </div>

    <div class="col-md-2">
        <button class="btn btn-warning w-100">
            ดูรายการ
        </button>
    </div>
</form>


<div class="card shadow-sm">
<div class="card-body">

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-info text-center">
        ไม่มีรายการจองในวันที่เลือก
    </div>
<?php else: ?>

<table class="table table-bordered table-hover align-middle">
<thead class="table-warning text-center">
<tr>
    <th>ชื่อลูกค้า</th>
    <th>เบอร์โทร</th>
    <th>เวลา</th>
    <th>โต๊ะ</th>
    <th>ที่นั่ง</th>
    
</tr>

</thead>

<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr class="text-center">
    <td><?= htmlspecialchars($row['customer_name']); ?></td>
    <td><?= htmlspecialchars($row['phone']); ?></td>
    <td><?= substr($row['reservation_time'], 0, 5); ?></td>
    <td>โต๊ะ <?= $row['table_number']; ?></td>
    <td><?= $row['seat']; ?> ที่นั่ง</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

<?php endif; ?>

</div>
</div>

</div>
</body>
</html>
