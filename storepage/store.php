<?php
session_start();
if ($_SESSION['role'] != 'store_owner') {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | ระบบจองโต๊ะ</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset.css">
</head>
<body>

<!-- ===== Navbar ===== -->
<nav class="navbar navbar-light bg-navbar px-4">
    <div class="d-flex align-items-center gap-3 ms-4">
        <img src="../img/logo .png" height="50">
        <div>
            <h6 class="mb-0 text-orange fw-bold">ระบบจัดการจองโต๊ะ</h6>
            <small class="text-muted">เจ้าของร้าน • สิทธิ์เต็ม</small>
        </div>
    </div>

    <a href="../login/logout.php" class="btn btn-danger btn-sm">
        ออกจากระบบ
    </a>
</nav>

<!-- ===== Menu ===== -->
<div class="menu-bar ps-5 ">
    <a href="store.php?link=homes">หน้าหลัก</a>
        <a href="store.php?link=table">แผนผังโต๊ะ</a>
        <a href="store.php?link=list">รายการจอง</a>
        <a href="store.php?link=reports">รายงาน</a>
        <a href="store.php?link=add">เพิ่มพนักงาน</a>

</div>

<div class="container my-4">
   <?php require_once 'body.php';  ?>
   

</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
