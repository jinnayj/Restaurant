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
$users = $conn->query("SELECT id, username, role FROM users ORDER BY role, username");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการพนักงาน</title>
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container my-4">

    <h3 class="mb-3">👥 จัดการพนักงาน</h3>

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
                        ➕ เพิ่มพนักงาน
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
                                <a href="store.php?link=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">
                                    ✏️ แก้ไข
                                </a>
                                <a href="add_stff/delete_staff.php?id=<?= $u['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('ยืนยันลบพนักงาน?')">
                                    🗑️ ลบ
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
