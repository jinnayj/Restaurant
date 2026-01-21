<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* จำกัดสิทธิ์เฉพาะเจ้าของร้าน */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'store_owner') {
    die("ไม่อนุญาตให้เข้าถึงหน้านี้");
}

/* เชื่อมต่อฐานข้อมูล */
require_once __DIR__ . '/../../connect.php';

/* ดึงรายชื่อผู้ใช้ */
$users = $conn->query("SELECT id_ser , username, role FROM users ORDER BY role, username");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการพนักงาน</title>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container my-4">

    <h4 class="mb-3">
    <i class="bi bi-person-gear me-2 fs-2"></i>จัดการพนักงาน
</h4>

    <!-- ===== เพิ่มพนักงาน ===== -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="add_stff/save_user.php" class="row g-3">

                <div class="col-md-4">
                    <input type="text" name="username"
                           class="form-control"
                           placeholder="ชื่อผู้ใช้" required>
                </div>

                <div class="col-md-4">
                    <input type="password" name="password"
                           class="form-control"
                           placeholder="รหัสผ่าน" required>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">
                       <i class="bi bi-plus-square me-2"></i>เพิ่มพนักงาน
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- ===== รายชื่อพนักงาน ===== -->
    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-warning">
                    <tr>
                        <th>ชื่อผู้ใช้</th>
                        <th>ตำแหน่ง</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($u = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= $u['role'] ?></td>
                        <td>
                            <?php if($u['role'] !== 'store_owner'): ?>
                                <a href="store.php?link=edit&id=<?= $u['id_ser'] ?>" class="btn btn-sm btn-warning">
                                  <i class="bi bi-pencil-square me-2"></i>แก้ไข
                                </a>
                                <a href="add_stff/delete_staff.php?id_ser=<?= $u['id_ser'] ?>"
                                      class="btn btn-sm btn-danger"
                                        onclick="return confirm('ยืนยันลบพนักงาน?')">
                                         <i class="bi bi-trash3 me-2"></i>ลบ
                                              </a>

                            <?php else: ?>
                                <span class="text-muted">เจ้าของร้าน</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
