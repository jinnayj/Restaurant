<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

/* รับวันที่จากฟอร์ม */
$selected_date = $_GET['date'] ?? date('Y-m-d');

/* ดึงรายการจองที่ยังยืนยัน */
$sql = "
SELECT r.*, t.table_number, t.seat
FROM reservations r
JOIN tables t ON r.table_id = t.id
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
    <th>จัดการ</th>
</tr>

</thead>

<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
   <thead class="text-center">
    <td><?= htmlspecialchars($row['customer_name']); ?></td>
    <td><?= htmlspecialchars($row['phone']); ?></td>
    <td><?= substr($row['reservation_time'], 0, 5); ?></td>
     <td>โต๊ะ <?= $row['table_number']; ?></td>
     <td><?= $row['seat']; ?> ที่นั่ง</td>

    <td class="text-center">
<a href="store.php?link=edit_lists&id=<?= $row['id']; ?>"
   class="btn btn-sm btn-warning"><i class="bi bi-pencil-square me-2"></i>แก้ไข</a>

<a href="cancel_reservation.php?id=<?= $row['id']; ?>"
   class="btn btn-sm btn-danger"
   onclick="return confirm('ยืนยันยกเลิกการจองนี้?');">
   <i class="bi bi-trash3 me-2"></i> ยกเลิก
</a>
<?php if ($row['status'] == 'confirmed'): ?>
    <a href="complete_reservation.php?id=<?= $row['id']; ?>"
       class="btn btn-sm btn-success"
       onclick="return confirm('ยืนยันว่าใช้งานเสร็จแล้ว?');">
       <i class="bi bi-check2-circle me-2"></i>เสร็จสิ้น
    </a>
<?php endif; ?>
</td>
       
    </td>
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
