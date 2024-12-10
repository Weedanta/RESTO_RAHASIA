<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$reservation_datetime = $_POST['reservation_datetime'];
$people = $_POST['people'];
$room_type = $_POST['room_type'];
// $booking_price = $_POST['booking_price'];
$payment_method = $_POST['payment_method'];
$additional_request = $_POST['additional_request'];

// Validasi H-24
$current_time = new DateTime();
$reservation_time = new DateTime($reservation_datetime);
$time_diff = $current_time->diff($reservation_time);

if ($reservation_time <= $current_time || ($time_diff->days == 0 && $time_diff->h < 24)) {
    $_SESSION['error_message'] = "Reservasi harus dilakukan minimal H-1 atau 24 jam sebelum waktu reservasi.";
    header("Location: reservation.php");
    exit();
}

// Validasi dan pengunggahan file bukti pembayaran
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
$upload_dir = 'uploads/';
$payment_proof = null;

if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['payment_proof']['tmp_name'];
    $file_name = basename($_FILES['payment_proof']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($file_ext, $allowed_extensions)) {
        $payment_proof = uniqid() . '.' . $file_ext; // Generate nama file unik
        move_uploaded_file($file_tmp, $upload_dir . $payment_proof);
    } else {
        $_SESSION['error_message'] = "Format file tidak didukung. Unggah file gambar atau dokumen.";
        header("Location: reservation.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Harap unggah bukti pembayaran.";
    header("Location: reservation.php");
    exit();
}

// Simpan data ke database
$stmt = $pdo->prepare("INSERT INTO reservations (user_id, name, email, phone, reservation_datetime, people, room_type, payment_method, additional_request) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $name, $email, $phone, $reservation_datetime, $people, $room_type,    $payment_method, $additional_request]);

header("Location: reservation_status.php");
exit();