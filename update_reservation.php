<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit();
}

$id = $_POST['id'];
$new_date = $_POST['new_date'];

if (!$id || !$new_date) {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
    exit();
}

$current_time = new DateTime();
$new_reservation_time = new DateTime($new_date);

// Validasi H-24
if ($new_reservation_time <= $current_time || ($new_reservation_time->getTimestamp() - $current_time->getTimestamp()) < 24 * 60 * 60) {
    echo json_encode(['success' => false, 'error' => 'Reschedule harus dilakukan minimal H-1 atau 24 jam sebelum waktu reservasi baru.']);
    exit();
}

$stmt = $pdo->prepare("UPDATE reservations SET reservation_datetime = ? WHERE id = ?");
if ($stmt->execute([$new_date, $id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update reservation.']);
}
exit();