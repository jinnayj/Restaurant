<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก | ระบบจองโต๊ะ</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../img/logo .png">

    <style>
        * {
            font-family: 'Sarabun', sans-serif;
        }

        .bg-gradient-custom {
           background: linear-gradient(135deg, #ffd194, #ffef8a);
            min-height: 100vh;
        }

        .login-card {
    width: 100%;          
    max-width: 450px;     
    border-radius: 24px;
}


        .register-logo {
            width: 160px;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px;
        }

        .btn-orange {
            background: linear-gradient(135deg, #ff7a18, #ff9f43);
            color: #fff;
            border-radius: 14px;
            font-weight: 600;
            border: none;
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, #ff6a00, #e25605);
        }
    </style>
</head>

<body>

<div class="container-fluid bg-gradient-custom d-flex justify-content-center align-items-center">
    <div class="card login-card shadow border-0">
        <div class="card-body px-4 py-5">

            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="../img/logo .png" class="register-logo mb-3">
                <h4 class="fw-bold text-warning mb-1">
                    สมัครสมาชิก
                </h4>
                <p class="text-muted">
                    ระบบจองโต๊ะร้านอาหาร Moonlight
                </p>
            </div>

            <!-- Register Form -->
            <form action="auth_login.php" method="post">

                <input type="hidden" name="register" value="1">

                <div class="mb-3">
                    <label class="fw-semibold">ชื่อ</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">อีเมล</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="fw-semibold">รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button class="btn btn-orange btn-lg">
                        สมัครสมาชิก
                    </button>
                    <div class="text-center mt-3">
                <a href="../index.php" class="text-decoration-none">
                    ← กลับไปหน้าเข้าสู่ระบบ
                </a>
                </div>

            </form>

           
            </div>

        </div>
    </div>
</div>

</body>
</html>
