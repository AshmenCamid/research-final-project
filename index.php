<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Purpose: This is the login page for the admin. It includes a form for username and password and uses AJAX for login validation. -->
    <meta charset="UTF-8">
    <link rel="icon" href="assets/image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Program for Employment of Students</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/fonts.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        /* Centered modal styling */
        .modal-content {
            border-radius: 15px;
            text-align: center;
            padding: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Centering modal */
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Icon styling */
        .modal-icon {
            font-size: 50px;
        }
    </style>
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
        <form id="loginForm">
            <div class="form-section">
                <img src="assets/image/admin.webp" alt="Admin Icon" class="admin-image">
                <h2>ADMIN</h2>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control kanit" name="username" id="username" placeholder="Username"
                        required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control kanit" name="password" id="password"
                        placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-login kanit">
                    <span class="login-text">Log in</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- ✅ Success Modal (Centered) -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-success">
                <div class="modal-body">
                    <i class="bi bi-check-circle-fill text-success modal-icon"></i>
                    <h4 class="mt-2">Login Successful!</h4>
                    <p>Redirecting to the dashboard...</p>
                    <div class="spinner-border text-success mt-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ❌ Error Modal (Centered) -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-danger">
                <div class="modal-body">
                    <i class="bi bi-x-circle-fill text-danger modal-icon"></i>
                    <h4 class="mt-2">Login Failed!</h4>
                    <p id="errorMessage"></p>
                    <button type="button" class="btn btn-danger mt-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle login form submission
            // Explanation: The form data is sent via AJAX to the server for validation. Success or error modals are displayed based on the response.
            $("#loginForm").on("submit", function (e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "pages/login.php",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            $("#successModal").modal("show"); // Show success modal
                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 2000); // Redirect after 2 seconds
                        } else {
                            $("#errorMessage").text(response.message);
                            $("#errorModal").modal("show"); // Show error modal
                        }
                    },
                    error: function () {
                        $("#errorMessage").text("An error occurred. Please try again.");
                        $("#errorModal").modal("show");
                    }
                });
            });
        });
    </script>
</body>

</html>
