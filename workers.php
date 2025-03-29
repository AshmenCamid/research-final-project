<!DOCTYPE html>
<html lang="en">
<head>
    <!-- This file serves as the front-end interface for workers to Time In and Time Out -->
    <meta charset="UTF-8">
    <link rel="icon" href="assets/image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPES | WORKERS</title>
    <link rel="stylesheet" href="assets/css/workers.css">
</head>
<body>

    <!-- Header section with logos and title -->
    <div class="header">
        <img src="assets/image/logo.png" alt="Left Logo" class="left-logo">
        <div class="header-title-container">
            Special Program for Employment of Students
        </div>
        <img src="assets/image/DOLE.png" alt="Right Logo" class="right-logo">
    </div>

    <div class="container">
        <!-- Logo section -->
        <div class="logo-section">
            <img src="assets/image/peso.png" alt="Municipality of Buenavista Seal">
        </div>

        <!-- Form section for worker ID input and attendance actions -->
        <div class="form-section">
            <h2>Summer Job <br> <span style="font-size: 18px; font-weight: normal;">ATTENDANCE</span></h2>
            <label for="user-id">Enter Your ID:</label>
            <input type="text" id="user-id" name="user-id" placeholder="Please input your ID number">
            <button class="search-button" onclick="searchUser()">SEARCH</button>

            <!-- User information and Time In/Out buttons -->
            <div id="user-info" style="display: none; text-align: left; margin-top: 15px;">
                <p><strong>Name:</strong> <span id="user-name"></span></p>
                <p><strong>Date:</strong> <span id="current-date"></span></p>

                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 10px;">
                    <button class="time-in">TIME IN</button>
                    <button class="time-out">TIME OUT</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden input to track Time In status -->
    <input type="hidden" id="time-in-status" value="false">

    <!-- JavaScript files for search and attendance functionality -->
    <script src="assets/js/search.js"></script>
    <script src="assets/js/time_in-out.js"></script> 
    <script>
        // Function to display an alert message and reload the page
        function showAlert(message) {
            alert(message);
            location.reload();
        }
    </script>
</body>
</html>
