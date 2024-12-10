<?php
session_start();
require 'database.php';

$stmt = $pdo->query("SELECT * FROM menu ORDER BY kategori, nama_menu");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - CW Coffee & Eatery</title>
    <link rel="stylesheet" href="view_menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="menu-container">
        <h1>Daftar Menu</h1>
        <div class="menu-list">
            <?php 
            $currentCategory = '';
            foreach ($menus as $menu): 
                if ($menu['kategori'] !== $currentCategory):
                    $currentCategory = $menu['kategori'];
            ?>
                <h2 class="menu-category"><?= htmlspecialchars($currentCategory) ?></h2>
            <?php endif; ?>
            <div class="menu-item">
                <h3><?= htmlspecialchars($menu['nama_menu']) ?></h3>
                <p><?= htmlspecialchars($menu['deskripsi']) ?></p>
                <span class="menu-price">IDR <?= number_format($menu['harga'], 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="back-to-reservation">
            <a href="reservation.php" class="back-button">Back to Reservation</a>
        </div>
    </div>
</body>
</html>