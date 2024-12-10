<?php 
require 'database.php';

if (!isset($_GET['id'])) {
    echo "Invalid request. Reservation ID is missing.";
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    echo "Reservation not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule - CW Coffee & Eatery</title>
    <link rel="stylesheet" href="reservation.css">
    <link rel="stylesheet" href="reservation_status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Reschedule Reservation</h1>
        <form id="reschedule-form">
            <input type="hidden" name="id" value="<?= htmlspecialchars($reservation['id']) ?>">

            <label for="new_date">Date & Time:</label>
            <input 
                type="datetime-local" 
                id="new_date" 
                name="new_date" 
                value="<?= htmlspecialchars($reservation['reservation_datetime']) ?>" 
                required 
            >

            <button type="button" id="submit-btn">Save New Schedule</button>
        </form>
        <a href="reservation_status.php" class="back-button-reservation">Cancel</a>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const submitBtn = document.getElementById("submit-btn");
            const form = document.getElementById("reschedule-form");
            
            submitBtn.addEventListener("click", function () {
                const newDateInput = document.getElementById('new_date').value;
                const newDate = new Date(newDateInput);
                const currentTime = new Date();

                if (!newDateInput || newDate <= currentTime || (newDate - currentTime) < 24 * 60 * 60 * 1000) {
                    alert("Reschedule harus dilakukan minimal H-1 atau 24 jam sebelum waktu reservasi baru.");
                    return;
                }

                const formData = new FormData(form);
                fetch('update_reservation.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Reservation rescheduled successfully!");
                        window.location.href = "reservation_status.php";
                    } else {
                        alert("Failed to reschedule reservation: " + data.error);
                    }
                })
                .catch(error => {
                    alert("An error occurred: " + error);
                });
            });
        });
    </script>
</body>
</html>