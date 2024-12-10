<?php 
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = ($_POST['password']);

    $query = "SELECT * FROM admin WHERE admin_email = :email AND admin_password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        if ($admin['role'] === 'Admin') {
            $_SESSION['admin_email'] = $admin['admin_email'];
            $_SESSION['role'] = $admin['role'];
            echo json_encode(["status" => "success", "message" => "Login berhasil"]);
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Anda bukan Admin"]);
            header("Location: login_admin.html?error=Anda bukan Admin");
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email atau Password salah"]);
        header("Location: login_admin.html?error=Email atau Password salah");
        exit();
    }
}