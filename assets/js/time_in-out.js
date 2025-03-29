// This script handles the Time In and Time Out functionality for workers.
// It sends AJAX requests to the server and displays success or error messages.

// Add event listener for the Time In button
document.querySelector(".time-in").addEventListener("click", function () {
    // Get worker ID and full name from the input fields
    let workerId = document.getElementById("user-id").value;
    let fullname = document.getElementById("user-name").textContent; // Assume this is displayed

    // Validate inputs
    if (!workerId || !fullname) {
        alert("❌ Please enter a valid Worker ID and Name.");
        return;
    }

    // Send a POST request to the server to record Time In
    fetch("time_in.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `worker_id=${encodeURIComponent(workerId)}&fullname=${encodeURIComponent(fullname)}`
    })
    .then(response => response.json())
    .then(data => {
        // Show success or error message based on server response
        showAlert(data.success ? "✅ " + data.success : "❌ " + data.error);
    })
    .catch(error => console.error("Error:", error));
});

// Add event listener for the Time Out button
document.querySelector(".time-out").addEventListener("click", function () {
    // Get worker ID from the input field
    let workerId = document.getElementById("user-id").value;

    // Validate input
    if (!workerId) {
        alert("❌ Please enter a valid Worker ID.");
        return;
    }

    // Send a POST request to the server to record Time Out
    fetch("time_out.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `worker_id=${encodeURIComponent(workerId)}`
    })
    .then(response => response.json())
    .then(data => {
        // Show success or error message based on server response
        showAlert(data.success ? "✅ " + data.success : "❌ " + data.error);
    })
    .catch(error => console.error("Error:", error));
});

// Function to display an alert message and reload the page
function showAlert(message) {
    alert(message);
    location.reload();
}
