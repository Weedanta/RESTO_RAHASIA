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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - CW Coffee & Eatery</title>
    <link rel="stylesheet" href="reservation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="reservation.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Book a Table</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
    <div class="error-message">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
        
        <form action="reservation_process.php" method="POST" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['nama']) ?>" readonly>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

            <label>WhatsApp or Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['nomor_telepon']) ?>" readonly>

            <label>Booking Date & Time:</label>
            <input type="datetime-local" name="reservation_datetime" id="reservation-datetime" required min="<?= (new DateTime('+1 day'))->format('Y-m-d\TH:i') ?>">

            <label>Number of People:</label>
            <input type="number" id="people" name="people" min="1" required onchange="calculateBookingPrice()">

            <label>Room Type:</label>
            <select id="room-type" name="room_type" required onchange="calculateBookingPrice()">
                <option value="Indoor">Regular Indoor - Fee IDR 7K/Guest</option>
                <option value="Outdoor">Regular Outdoor - Fee IDR 5K/Guest</option>
                <option value="VIP">VIP Room - Fee IDR 10K/Guest</option>
            </select>

            <label>Payment Methods:</label>
            <select name="payment_method" id="payment-method" onchange="updateAccountNumber()" required>
                <option value="BRI">BRI</option>
                <option value="BNI">BNI</option>
                <option value="Mandiri">Mandiri</option>
                <option value="BCA">BCA</option>
                <option value="OVO/Gopay/ShopeePay">OVO/Gopay/ShopeePay</option>
            </select>

            <label>Peyment Proof Upload:</label>
            <input type="file" name="payment_proof" accept="image/*,.pdf,.doc,.docx" required>

            <label>Account Number:</label>
            <input type="text" id="account-number" value="057901009325531" readonly>

            <label>Additional Request:</label>
            <textarea name="additional_request" rows="4"></textarea>

            <button type="submit">Submit</button>
        </form>
        <div class="button-container-reservation">
        <a href="view_menu.php" class="view-menu-button" target="_blank">View Menu</a>
            <a href="dashboard.php" class="back-button-reservation">Back</a>
        </div>
    </div>
</body>
</html>