<?php
session_start();
require 'database.php'; // File koneksi database

// Pastikan pengguna adalah Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>
        alert('Anda bukan Admin, silahkan kembali ke halaman awal');
        window.location.href = 'index.html';
    </script>";
    exit();
}

// Query data reservasi
$query = "SELECT id, name, email, phone, reservation_datetime, people, room_type, payment_method,  additional_request FROM reservations ORDER BY reservation_datetime DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation List</title>
    <link rel="stylesheet" href="admin.css">
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
    <div class="main-container-list">
        <h1 class="main-header">Reservation List</h1>
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
                        <th>Payment Proof</th>
                        <th>Additional Request</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservations) > 0): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?= htmlspecialchars($reservation['name']) ?></td>
                                <td><?= htmlspecialchars($reservation['email']) ?></td>
                                <td><?= htmlspecialchars($reservation['phone']) ?></td>
                                <td><?= htmlspecialchars($reservation['reservation_datetime']) ?></td>
                                <td><?= htmlspecialchars($reservation['people']) ?></td>
                                <td><?= htmlspecialchars($reservation['room_type']) ?></td>
                                <td><?= htmlspecialchars($reservation['payment_method']) ?></td>
                                <td>
                                    <?php if (!empty($reservation['payment_proof'])): ?>
                                        <a href="payment_proof_admin.php?id=<?= htmlspecialchars($reservation['id']) ?>" target="_blank">View</a>
                                    <?php else: ?>
                                        Not Uploaded
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($reservation['additional_request'] ?? 'N/A') ?></td>
                                <td>
                                    <button class="reject-button" onclick="window.location.href='reject_reservation.php?id=<?= $reservation['id'] ?>'">Reject</button>
                                    <button class="approve-button" onclick="window.location.href='approve_reservation.php?id=<?= $reservation['id'] ?>'">Approve</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">No reservations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="button-container">
            <a href="admin_dashboard.php" class="back-viewlist">Back</a>
        </div>
    </div>
</body>
</html>