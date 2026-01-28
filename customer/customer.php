<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login_ct.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ลูกค้า | ระบบจองโต๊ะ</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset.css">
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/logo .png">

<style>
* {
    font-family: 'Sarabun', sans-serif !important;
}

.btn-logout {
    background: linear-gradient(135deg, #d32f2f, #f5530e);
    padding: 10px 26px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    text-decoration: none;
}
.btn-logout:hover {
    background: linear-gradient(135deg, #b71c1c, #7f0000);
}
.btn-logout,
.btn-logout:hover,
.btn-logout:focus {
    color: #fff !important;
}

.menu-bar a {
    display: inline-block;
    padding: 10px 18px;
    margin-right: 8px;
    text-decoration: none;
    color: #333;
    border-radius: 10px;
    transition: all 0.3s ease;
}
.menu-bar a.active {
    background: linear-gradient(135deg, #ff9800, #ff5722);
    color: #fff;
    font-weight: 600;
}
</style>
</head>

<body>

<!-- ===== Navbar ===== -->
<nav class="navbar navbar-light bg-navbar px-4">
    <div class="d-flex align-items-center gap-3 ms-4">
        <img src="../img/logo .png" height="50">
        <div>
            <h6 class="mb-0 text-orange fw-bold">ระบบจองโต๊ะ</h6>
            <small class="text-muted">
                ลูกค้า <i class="bi bi-person-check ms-1"></i> เต็มสิทธ์
            </small>
        </div>
    </div>

    <a href="../login/logout.php" class="btn btn-logout">
        <i class="bi bi-box-arrow-right me-2"></i>
        ออกจากระบบ
    </a>
</nav>

<!-- ===== Menu ===== -->
<?php $link = $_GET['link'] ?? 'homes'; ?>

<div class="menu-bar ">
    
    <a href="customer.php?link=table"
       class="<?= $link == 'table' ? 'active' : '' ?>">
        จองโต๊ะ
    </a>

    <a href="customer.php?link=mybooking"
       class="<?= $link == 'mybooking' ? 'active' : '' ?>">
        การจองของฉัน
    </a>
</div>

<!-- ===== Content ===== -->
<div class="container my-4">
    <?php require_once 'body.php'; ?>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
