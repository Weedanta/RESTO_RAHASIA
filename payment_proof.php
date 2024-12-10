<?php
require 'database.php';

if (!isset($_GET['id'])) {
    echo "No ID specified.";
    exit();
}

$id = intval($_GET['id']);

// Ambil data dari database
$stmt = $pdo->prepare("SELECT payment_proof FROM reservations WHERE id = ?");
$stmt->execute([$id]);
$reservation = $stmt->fetch();

if (!$reservation || !$reservation['payment_proof']) {
    echo "Payment proof not found.";
    exit();
}

// Decode jika file tersimpan sebagai data biner
$image_data = $reservation['payment_proof'];
$image_info = getimagesizefromstring($image_data);

// Validasi apakah file benar-benar gambar
if ($image_info === false) {
    echo "Invalid image data.";
    exit();
}

// Tampilkan gambar dengan header yang sesuai
header("Content-Type: " . $image_info['mime']);
echo $image_data;
exit();