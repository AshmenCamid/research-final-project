function showModal(icon, message) {
    // Create modal container
    let modal = document.createElement("div");
    modal.style.position = "fixed";
    modal.style.top = "0";
    modal.style.left = "0";
    modal.style.width = "100%";
    modal.style.height = "100%";
    modal.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
    modal.style.display = "flex";
    modal.style.justifyContent = "center";
    modal.style.alignItems = "center";
    modal.style.zIndex = "1000";

    // Create modal content (BIGGER SIZE)
    let modalContent = document.createElement("div");
    modalContent.style.backgroundColor = "white";
    modalContent.style.padding = "30px";
    modalContent.style.borderRadius = "15px";
    modalContent.style.textAlign = "center";
    modalContent.style.boxShadow = "0px 6px 15px rgba(0, 0, 0, 0.3)";
    modalContent.style.width = "400px";
    modalContent.style.height = "250px";

    // Create icon (BIGGER)
    let modalIcon = document.createElement("img");
    modalIcon.src = icon;
    modalIcon.style.width = "80px";
    modalIcon.style.height = "80px";
    modalIcon.style.marginBottom = "15px";

    // Create message text (BIGGER FONT)
    let modalMessage = document.createElement("p");
    modalMessage.textContent = message;
    modalMessage.style.fontSize = "20px";
    modalMessage.style.color = "#333";
    modalMessage.style.fontWeight = "bold";
    modalMessage.style.marginBottom = "20px";

    // Create close button (BIGGER & CENTERED)
    let closeButton = document.createElement("button");
    closeButton.textContent = "Close";
    closeButton.style.backgroundColor = "red";
    closeButton.style.color = "white";
    closeButton.style.border = "none";
    closeButton.style.padding = "10px 20px";
    closeButton.style.borderRadius = "8px";
    closeButton.style.padding = "5px 10px";
    closeButton.style.cursor = "pointer";
    closeButton.style.fontSize = "18px";
    closeButton.style.marginTop = "15px";

    closeButton.onclick = function () {
        document.body.removeChild(modal);
    };

    // Append elements
    modalContent.appendChild(modalIcon);
    modalContent.appendChild(modalMessage);
    modalContent.appendChild(closeButton);
    modal.appendChild(modalContent);
    document.body.appendChild(modal);
}

// Example usage when the user is disabled
function searchUser() {
    let workerId = document.getElementById("user-id").value;

    if (workerId.trim() === "") {
        showModal("https://cdn-icons-png.flaticon.com/512/753/753345.png", "Please enter a valid Worker ID.");
        return;
    }

    // Send AJAX request
    fetch("controllers/search_worker.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `worker_id=${workerId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            showModal("https://cdn-icons-png.flaticon.com/512/753/753345.png", data.error);
        } else {
            document.getElementById("user-name").textContent = data.fullname;
            document.getElementById("current-date").textContent = data.date;
            document.getElementById("user-info").style.display = "block";

            let timeInButton = document.querySelector(".time-in");
            let timeOutButton = document.querySelector(".time-out");

            if (data.status === "disabled") {
                timeInButton.disabled = true;
                timeOutButton.disabled = true;
                timeInButton.style.backgroundColor = "gray";
                timeOutButton.style.backgroundColor = "gray";

                showModal("https://cdn-icons-png.flaticon.com/512/1828/1828843.png", "Your account is disabled. You cannot Time In or Time Out.");
            } else {
                timeInButton.disabled = false;
                timeOutButton.disabled = false;
                timeInButton.style.backgroundColor = "#28a745";
                timeOutButton.style.backgroundColor = "#dc3545";
            }
        }
    })
    .catch(error => console.error("Error:", error));
}
