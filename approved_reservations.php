<?php
require 'database.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>
        alert('Access denied.');
        window.location.href = 'index.html';
    </script>";
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM approved_reservations ORDER BY reservation_datetime DESC");
$stmt->execute();
$approvedReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approved Reservations</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="reservation_status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<style>
        body {
            background-color: #0a225e;
        }
    </style>
    <div class="main-container-approve">
        <h1 class="main-header">Approved Reservations</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Reservation Date & Time</th>
                        <th>People</th>
                        <th>Room Type</th>
                        <th>Payment Method</th>
                        <th>Additional Request</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($approvedReservations as $reservation): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservation['name']) ?></td>
                            <td><?= htmlspecialchars($reservation['email']) ?></td>
                            <td><?= htmlspecialchars($reservation['phone']) ?></td>
                            <td><?= htmlspecialchars($reservation['reservation_datetime']) ?></td>
                            <td><?= htmlspecialchars($reservation['people']) ?></td>
                            <td><?= htmlspecialchars($reservation['room_type']) ?></td>
                            <td><?= htmlspecialchars($reservation['payment_method']) ?></td>
                            <td><?= htmlspecialchars($reservation['additional_request'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="button-container">
            <a href="admin_dashboard.php" class="back-approve">Back</a>
        </div>
    </div>
</body>
</html>