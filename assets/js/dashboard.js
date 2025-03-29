let workersChart;

function updateChart(data) {
    if (workersChart) {
        workersChart.destroy(); // Destroy previous chart before updating
    }
    let ctx = document.getElementById('workersChart').getContext('2d');
    workersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Workers', 'Currently IN', 'Off-Duty Workers', 'Absent Workers'],
            datasets: [{
                label: 'Workers Statistics',
                data: [data.totalWorkers, data.currentlyIn, data.offDutyWorkers, data.totalWorkers - data.currentlyIn - data.offDutyWorkers],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderColor: ['#0056b3', '#1d7a35', '#e0a800', '#b02a37'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

function updateStatistics(data) {
    $("#totalWorkers").text(data.totalWorkers);
    $("#currentlyIn").text(data.currentlyIn);
    $("#offDutyWorkers").text(data.offDutyWorkers);
    $("#absentWorkers").text(data.totalWorkers - data.currentlyIn - data.offDutyWorkers);
}

$(document).ready(function() {
    function fetchData() {
        let selectedDate = $("#filterDate").val();
        let selectedPeriod = $("#timePeriod").val();
        $.ajax({
            url: "dashboard.php",
            type: "POST",
            data: { date: selectedDate, period: selectedPeriod },
            dataType: "json",
            success: function(response) {
                updateChart(response);  // Update Chart
                updateStatistics(response); // Update Statistics Cards
                $("#periodLabel").text(selectedPeriod); // Update period label
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });
    }
    $("#filterButton").click(fetchData);
    fetchData(); // Load chart and stats on page load
});

$(document).ready(function() {
    $("#filterButton").click(function() {
        let selectedDate = $("#filterDate").val();
        let selectedPeriod = $("#timePeriod").val();
        $.ajax({
            url: "dashboard.php",
            type: "POST",
            data: { date: selectedDate, period: selectedPeriod },
            dataType: "json",
            success: function(response) {
                $("#total-workers").text(response.totalWorkers);
                $("#currently-in").text(response.currentlyIn);
                $("#off-duty-workers").text(response.offDutyWorkers);
                $("#absent-workers").text(response.totalWorkers - response.currentlyIn - response.offDutyWorkers);
                $("#periodLabel").text(selectedPeriod); // Update period label
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });
    });
    // Time Out Button Click Event
    $(".time-out-btn").click(function() {
        let workerId = $(this).data("worker-id");
        $.ajax({
            url: "dashboard.php",
            type: "POST",
            data: { worker_id: workerId, time_out: true },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert("Worker timed out successfully.");
                    $("#filterButton").click();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", error);
            }
        });
    });
});