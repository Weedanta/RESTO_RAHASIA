<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CW Coffee & Eatery</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="dashboard.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <a href="update_profile.php" data-page="edit-profile">
        <i class="fas fa-user-edit"></i> Edit Profile
    </a>
    <a href="delete_account.php" onclick="return confirm('Are you sure you want to delete your account?')">
        <i class="fas fa-user-times"></i> Delete Account
    </a>
    <a href="reservation.php">
        <i class="fas fa-calendar-plus"></i> Book a Table
    </a>
    <a href="reservation_status.php">
        <i class="fas fa-list"></i> Booking List
    </a>
    <a href="approved_booking.php">
        <i class="fas fa-check-circle"></i> Approved Bookings
    </a>
    <a href="rejected_booking.php">
        <i class="fas fa-times-circle"></i> Rejected Bookings
    </a>
    <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

    <!-- Main Content -->
    <div class="main-container">
        <div class="main-header">Account Dashboard</div>
        <div id="profile-info">
            <label>Full Name:</label>
            <p><?= htmlspecialchars($user['nama']) ?></p>

            <label>Gender:</label>
            <p><?= htmlspecialchars($user['jenis_kelamin']) ?></p>

            <label>Address:</label>
            <p><?= nl2br(htmlspecialchars($user['alamat'])) ?></p>

            <label>Email:</label>
            <p><?= htmlspecialchars($user['email']) ?></p>

            <label>WhatsApp or Phone:</label>
            <p><?= htmlspecialchars($user['nomor_telepon']) ?></p>

            <label>Personal Describe:</label>
            <p><?= nl2br(htmlspecialchars($user['deskripsi'])) ?></p>
        </div>
    </div>
</body>
</html>