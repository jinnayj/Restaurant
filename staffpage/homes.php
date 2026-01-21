
<?php $link = $_GET['link'] ?? 'homes'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Moonlight</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
 <link rel="icon" href="../img/logo.png">

<div class="d-flex justify-content-center mb-4">
    <div class="d-flex gap-4">

        <!-- แผงผังโต๊ะ -->
        <a href="staff.php?link=table"
           class="switch-btn lg
           <?= $link=='table'
               ? 'switch-orange'
               : 'switch-orange outline' ?>">
            🪑 แผงผังโต๊ะ
        </a>

        <!-- รายการจอง -->
        <a href="staff.php?link=list"
           class="switch-btn lg
           <?= $link=='list'
               ? 'switch-green'
               : 'switch-green outline' ?>">
            📋 รายการจอง
        </a>

    </div>
</div>
<div class="d-flex justify-content-center mb-4">
  <img src="../img/tables.png" alt="...">
</div>



<style>
    a {
    text-decoration: none !important;
}

/* ปุ่มสลับหน้า (พื้นฐาน) */
.switch-btn {
    font-size: 1.2rem;
    padding: 16px 48px;
    border-radius: 16px;
    font-weight: 600;
    transition: all .2s ease;
}

/* ===== ขนาด ===== */
.switch-btn.sm {
    font-size: 1rem;
    padding: 50px 60px;
}

.switch-btn.lg {
    font-size: 1.4rem;
    padding: 40px 120px;
}

/* ===== สี (Custom Theme) ===== */


/* เขียว */
.switch-green {
    color: #fff;
    background: linear-gradient(135deg,#4caf50,#2e7d32);
    border: none;
}
.switch-green.outline {
    background: #fff9f3ff;
    color: #d7b000ff;
    border: 3px solid #debc00ff;
}

/* ส้ม (เหมาะร้านอาหาร) */
.switch-orange {
    color: #ffffffff;
    background: linear-gradient(135deg,#ff9800,#f57c00);
    border: none;
}
.switch-orange.outline {
    background: #fff9f3ff;
    color: #f57c00;
    border: 3px solid #f57c00;
}

/* Hover */
.switch-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,.2);
}
/*--------------------------------------*/
.full-screen-image {
    width: 50vw;
    height: 500vh;
    overflow: hidden;
}

.full-screen-image img {
    width: 50%;
    height: 50%;
    object-fit: cover;   /* เต็มจอ ไม่ยืด */
}
/*-------------------------------------------*/

</style>

