<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Login | ระบบจองโต๊ะ</title>

    <!-- Bootstrap 5.3 Offline -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
     <link rel="icon" href="../img/logo .png">
</head>
<body>

<div class="container-fluid bg-gradient-custom min-vh-100 d-flex justify-content-center align-items-center">

    <div class="card login-card shadow border-0">
        <div class="card-body px-4 py-5">

            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="../img/logo .png" alt="logo" class="login-logo mb-3">
                <h4 class="fw-bold text-orange mb-1">
                    ระบบจัดการจองโต๊ะร้านอาหาร
                </h4>
                <p class="text-muted ">
                    กรุณาเข้าสู่ระบบ
                </p>
            </div>

            <!-- Login Form -->
            <form action="login_db.php" method="post">

                <div class="mb-4">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username"
                           class="form-control form-control-lg"
                           placeholder="กรอกชื่อผู้ใช้" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password"
                           class="form-control form-control-lg"
                           placeholder="กรอกรหัสผ่าน" required>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-orange btn-lg fw-bold">
                        เข้าสู่ระบบ
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
<style >
.bg-gradient-custom {
    background: 135deg, #fff3e6;
}

.login-card {
    width: 200%;
    max-width: 420px;
    border-radius: 24px;
}

.login-logo {
    width: 180px;
    margin-bottom: 32px !important;
}

.login-card .card-body > * {
    margin-bottom: 22px;
}

.form-control {
    border-radius: 12px;
    padding: 14px 18px;
    margin-top: 6px;
    border: 1px solid #ccc;
    margin-bottom: 16px;
}

.btn-orange {
    padding: 14px;
}
.btn-orange {
    background: linear-gradient(135deg, #ff7a18, #ff9f43);
    color: #fff;
    border-radius: 14px;
    border: none;
    font-size: 18px;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-orange:hover {
    background: linear-gradient(135deg, #ff6a00, #e25605ff);
    transform: translateY(-2px);
   
}

.btn-orange:active {
    transform: scale(0.97);
}


</style>
</body>
</html>
