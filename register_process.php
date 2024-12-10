<?php
// register_process.php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $deskripsi = $_POST['deskripsi'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi konfirmasi password
if ($password !== $confirm_password) {
    header("Location: register.html?error=password_mismatch");
    exit();
}

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Masukkan data pengguna ke dalam database
    $stmt = $pdo->prepare("INSERT INTO users (nama, jenis_kelamin, alamat, deskripsi, email, nomor_telepon, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nama, $jenis_kelamin, $alamat, $deskripsi, $email, $nomor_telepon, $hashed_password]);

    // Redirect ke halaman login setelah berhasil mendaftar
    header("Location: login_form.html");
    exit();
}