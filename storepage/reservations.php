<?php
require_once __DIR__ . "/../config/db.php";

$result = $conn->query("
    SELECT r.*, t.table_number, t.seat
    FROM reservations r
    JOIN tables t ON r.table_id = t.id_show
    ORDER BY r.created_at DESC
");
?>

<table class="table table-bordered">
<thead>
<tr>
  <th>โต๊ะ</th>
  <th>วันที่</th>
  <th>เวลา</th>
  <th>ที่นั่ง</th>
  <th>สถานะ</th>
  <th>จัดการ</th>
</tr>
</thead>

<tbody>

<?php while($r = $result->fetch_assoc()): ?>
<tr>
  <td>โต๊ะ <?= $r['table_number'] ?></td>
  <td><?= $r['reservation_date'] ?></td>
  <td><?= substr($r['reservation_time'],0,5) ?></td>
  <td><?= $r['seat'] ?> คน</td>

  <td>
    <?= $r['status'] ?>
  </td>

  <td>
    <?php if($r['status'] === 'confirmed'): ?>
      <a href="start_using.php?id=<?= $r['id_booking'] ?>"
         class="btn btn-sm btn-danger"
         onclick="return confirm('เริ่มใช้งานโต๊ะนี้ใช่หรือไม่?')">
         ▶ เริ่มใช้งาน
      </a>
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
