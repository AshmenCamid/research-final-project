$(document).ready(function() {
    // Payout button functionality
    $(".payout-btn").click(function() {
        var workerId = $(this).data("worker-id");
    
        if (confirm("Are you sure you want to mark this worker as RECEIVED?")) {
            $.ajax({
                url: "../controllers/payout.php",
                type: "POST",
                data: { worker_id: workerId },
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function() {
                    alert("Error processing payout.");
                }
            });
        }
    });

    // Toggle total hours visibility
    $("#toggleTotalHours").click(function() {
        $(".total-hours").toggle();
        $(this).text(function(i, text) {
            return text === "Show Total Hours" ? "Hide Total Hours" : "Show Total Hours";
        });
    });
});