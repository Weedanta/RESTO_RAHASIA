<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ?");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation List - CW Coffee & Eatery</title>
    <link rel="stylesheet" href="reservation_status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Reservation List</h1>
        <?php if (count($reservations) > 0): ?>
            <table class="reservation-table">
    <thead>
        <tr>
            <th>Date & Time</th>
            <th>People</th>
            <th>Room Type</th>
            <th>Payment Method</th>
            <th>Additional Request</th>
            <th>Payment Proof</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?= htmlspecialchars($reservation['reservation_datetime']) ?></td>
                <td><?= htmlspecialchars($reservation['people']) ?></td>
                <td><?= htmlspecialchars($reservation['room_type']) ?></td>
                <td><?= htmlspecialchars($reservation['payment_method']) ?></td>
                <td><?= htmlspecialchars($reservation['additional_request']) ?></td>
                <td>
                    <?php if ($reservation['payment_proof']): ?>
                        <a href="uploads/<?= htmlspecialchars($reservation['payment_proof']) ?>" target="_blank">View</a>
                    <?php else: ?>
                        Not Uploaded
                    <?php endif; ?>
                </td>
                <td>
                    <button class="delete-button" data-id="<?= $reservation['id'] ?>">Cancel</button>
                    <button class="reschedule-button" onclick="window.location.href='reschedule_reservation.php?id=<?= $reservation['id'] ?>'">Reschedule</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    // Fungsi untuk menghapus reservasi
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-button').forEach(function(button) {
            button.addEventListener('click', function() {
                const reservationId = this.dataset.id;

                // Konfirmasi penghapusan
                if (confirm('Are you sure want to delete this reservation?')) {
                    fetch('delete_reservation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: reservationId }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Reservation successfully deleted.');
                            location.reload(); // Reload halaman setelah penghapusan
                        } else {
                            alert('Failed to delete reservation: ' + (data.error || 'Unknown error.'));
                        }
                    })
                    .catch(error => {
                        alert('There is an error occured: ' + error.message);
                    });
                }
            });
        });
    });
</script>
        <?php else: ?>
            <p>No reservations found.</p>
        <?php endif; ?>
        <div class="button-container">
            <a href="dashboard.php" class="back-button-status">Back</a>
        </div>
    </div>
</body>
</html>