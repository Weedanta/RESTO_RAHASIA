<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "<script>
        alert('Anda bukan Admin, silahkan kembali ke halaman awal');
        window.location.href = 'index.html';
    </script>";
    exit();
}

require 'database.php';
$menus = $pdo->query("SELECT * FROM menu")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="edit_menu.css">
    <link rel="stylesheet" href="reservation_status.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-container">
        <h1>Edit Menu</h1>
        <button class="add-menu-btn" onclick="showAddForm()">Add New Menu</button>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menus as $menu): ?>
                    <tr data-id="<?= $menu['id'] ?>">
                        <td><?= $menu['id'] ?></td>
                        <td class="category"><?= htmlspecialchars($menu['kategori']) ?></td>
                        <td class="name"><?= htmlspecialchars($menu['nama_menu']) ?></td>
                        <td class="price"><?= number_format($menu['harga'], 0, ',', '.') ?></td>
                        <td class="description"><?= htmlspecialchars($menu['deskripsi']) ?></td>
                        <td>
                            <button class="edit-btn" onclick="showEditForm(<?= $menu['id'] ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteMenu(<?= $menu['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="button-container-edit-menu">
        <a href="admin_dashboard.php" class="back-edit-menu">Back</a>
        </div>
    </div>

    <div id="form-modal" class="modal hidden">
    <form id="menu-form" method="POST" action="manage_menu.php">
        <input type="hidden" name="action" id="menu-action">
        <input type="hidden" name="id" id="menu-id">
        <label for="menu-category">Category</label>
        <input type="text" name="kategori" id="menu-category" required>
        <label for="menu-name">Name</label>
        <input type="text" name="nama_menu" id="menu-name" required>
        <label for="menu-price">Price</label>
        <input type="number" name="harga" id="menu-price" required>
        <label for="menu-description">Description</label>
        <textarea name="deskripsi" id="menu-description" required></textarea>
        <button type="submit">Save</button>
        <button type="button" onclick="hideForm()">Cancel</button>
    </form>
</div>
    <script src="edit_menu.js"></script>
</body>
</html>