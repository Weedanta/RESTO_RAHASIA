<?php
// login_process.php
require 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Cek apakah email ditemukan
    if ($user) {
        // Cek password
        if (password_verify($password, $user['password'])) {
            // Jika password benar, simpan ID pengguna dalam sesi dan arahkan ke dashboard
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Jika password salah
            header("Location: login_form.html?error=wrong_password");
            exit();
        }
    } else {
        // Jika email tidak ditemukan
        header("Location: login_form.html?error=user_not_found");
        exit();
    }
}