<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Program for Employment of Students</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/fonts.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <img src="assets/image/logo.png" alt="Left Logo" class="left-logo">
        <div class="header-title-container">
            Special Program for Employment of Students
        </div>
        <img src="assets/image/DOLE.png" alt="Right Logo" class="right-logo">
    </div>
    <div class="container">
        <div class="logo-section">
            <img src="assets/image/peso.png" alt="Municipality of Buenavista Seal">
        </div>
        <form action="controllers/create.php" method="post">
            <div class="form-section" id="loginForm">
                <img src="assets/image/admin.webp" alt="Admin Icon" class="admin-image">
                <h2>ADMIN</h2>
                <div class="input-group mb-3" data-aos="slide-down" data-aos-duration="1000">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control kanit" name="username" id="username" placeholder="Username" required>
                </div>
                <div class="input-group mb-3" data-aos="slide-down" data-aos-duration="1000">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control kanit" name="fullname" id="fullname" placeholder="Full Name" required>
                </div>
                <div class="input-group mb-3" data-aos="slide-down" data-aos-duration="1000">
                    <span class="input-group-text">
                        <i class="bi bi-telephone"></i>
                    </span>
                    <input type="text" class="form-control kanit" name="contact_number" id="contact_number" placeholder="Contact Number" required>
                </div>
                <div class="input-group mb-3" data-aos="slide-down" data-aos-duration="1200">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control kanit" name="password" id="password" placeholder="Password" required>
                </div>
                <input type="submit" id="submit" name="submit" class="login-text" value="Signup">
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
