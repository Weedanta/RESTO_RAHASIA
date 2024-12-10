<?php
session_start();
require 'database.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $deskripsi = $_POST['deskripsi'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        // Validasi email agar unik
        $emailCheckStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $emailCheckStmt->execute([$email, $user_id]);
        if ($emailCheckStmt->fetch()) {
            throw new Exception("This email has already been used.");
        }

        // Jika ada password baru, pastikan cocok
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                throw new Exception("The password does not match.");
            }
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("
                UPDATE users SET 
                    nama = ?, jenis_kelamin = ?, alamat = ?, email = ?, nomor_telepon = ?, deskripsi = ?, password = ? 
                WHERE id = ?
            ");
            $updateStmt->execute([
                $nama, $jenis_kelamin, $alamat, $email, $nomor_telepon, $deskripsi, $hashed_password, $user_id
            ]);
        } else {
            // Update tanpa mengganti password
            $updateStmt = $pdo->prepare("
                UPDATE users SET 
                    nama = ?, jenis_kelamin = ?, alamat = ?, email = ?, nomor_telepon = ?, deskripsi = ? 
                WHERE id = ?
            ");
            $updateStmt->execute([
                $nama, $jenis_kelamin, $alamat, $email, $nomor_telepon, $deskripsi, $user_id
            ]);
        }

        // Redirect ke dashboard
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Ambil data pengguna
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
    <title>Edit Profile - CW Coffee & Eatery</title>
    <link rel="stylesheet" href="update_profile.css">
    <link rel="stylesheet" href="reservation_status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="update_profile.php" method="POST">
            <label>Full Name:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>

            <label>Gender:</label>
            <select name="jenis_kelamin">
                <option value="Laki-laki" <?= $user['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $user['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>

            <label>Address:</label>
            <textarea name="alamat"><?= htmlspecialchars($user['alamat']) ?></textarea>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>WhatsApp or Phone:</label>
            <input type="tel" name="nomor_telepon" value="<?= htmlspecialchars($user['nomor_telepon']) ?>">

            <label>Personal Describe:</label>
            <textarea name="deskripsi"><?= htmlspecialchars($user['deskripsi']) ?></textarea>

            <label>New Password (Optional):</label>
            <input type="password" id="new_password" name="new_password">

            <label>Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">

            <button type="submit" onclick="return updateProfile()">Save Changes</button>
            <a href="dashboard.php" class="logout-btn">Back</a>
        </form>
    </div>
</body>
</html>