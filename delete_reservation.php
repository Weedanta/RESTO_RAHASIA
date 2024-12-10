<?php 
require 'database.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    if ($stmt->execute([$data['id']])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete reservation.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}