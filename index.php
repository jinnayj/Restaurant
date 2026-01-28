<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Moonlight</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
 <link rel="icon" href="img/logo .png">


</head>
<body>

<div class="container" href="style.css">
    <div class="login-card">
        <h2 class="title">Moonlight</h2>
        <p class="subtitle">เลือกบทบาทของคุณ</p>

        <a href="login/login.php" class="btn btn-staff">
            เข้าสู่ระบบในฐานะพนักงาน
        </a>

        <a href="login/login.php" class="btn btn-owner">
            เข้าสู่ระบบในฐานะเจ้าของร้าน
        </a>

        <a href="login/login_ct.php" class="btn btn-danger">
    เข้าสู่ระบบในฐานะลูกค้า
</a>


    </div>
</div>

<style>
.btn-danger {
     background: #ff5550 !important;
}
.btn-danger:hover {
    background: #c40404 !important;

}
</style>

</body>
</html>