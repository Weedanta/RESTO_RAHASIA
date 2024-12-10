<?php
require 'database.php';

if (!isset($_GET['id'])) {
    echo "No reservation ID specified.";
    exit();
}

$id = intval($_GET['id']);

// Ambil data dari tabel `reservations`
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    echo "Reservation not found.";
    exit();
}

// Pindahkan data ke tabel `approved_reservations`
$insertStmt = $pdo->prepare("
    INSERT INTO approved_reservations (name, email, phone, reservation_datetime, people, room_type, payment_method, payment_proof, additional_request)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$insertStmt->execute([
    $reservation['name'],
    $reservation['email'],
    $reservation['phone'],
    $reservation['reservation_datetime'],
    $reservation['people'],
    $reservation['room_type'],
    $reservation['payment_method'],
    $reservation['payment_proof'],
    $reservation['additional_request']
]);

// Hapus data dari tabel `reservations`
$deleteStmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
$deleteStmt->execute([$id]);

// Redirect kembali ke halaman daftar reservasi
header("Location: view_reservations.php");
exit();