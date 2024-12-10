<?php
session_start();
include 'database.php'; // File koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Password terenkripsi dengan MD5

    // Query untuk memeriksa kredensial
    $query = "SELECT * FROM admin";
$stmt = $pdo->prepare($query);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($admins); // Periksa output data dari database

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($admin['role'] === 'Admin') {
            $_SESSION['admin_email'] = $admin['admin_email'];
            $_SESSION['role'] = $admin['role'];
            echo json_encode(["status" => "success", "message" => "Login berhasil"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Anda bukan Admin"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email atau Password salah"]);
    }
}