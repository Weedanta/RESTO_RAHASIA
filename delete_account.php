<?php
session_start();
require 'database.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Hapus data pengguna dari tabel users
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    // Hapus sesi dan redirect ke halaman pendaftaran
    session_destroy();
    header("Location: login_form.html");
    exit();
} else {
    header("Location: login_form.php");
    exit();
}