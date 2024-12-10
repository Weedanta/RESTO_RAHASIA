<?php
require 'database.php';

$action = $_POST['action'] ?? null;
$id = $_POST['id'] ?? null;
$kategori = $_POST['kategori'] ?? null;
$nama_menu = $_POST['nama_menu'] ?? null;
$harga = $_POST['harga'] ?? null;
$deskripsi = $_POST['deskripsi'] ?? null;

if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO menu (kategori, nama_menu, harga, deskripsi) VALUES (?, ?, ?, ?)");
    $stmt->execute([$kategori, $nama_menu, $harga, $deskripsi]);
} elseif ($action === 'edit') {
    $stmt = $pdo->prepare("UPDATE menu SET kategori = ?, nama_menu = ?, harga = ?, deskripsi = ? WHERE id = ?");
    $stmt->execute([$kategori, $nama_menu, $harga, $deskripsi, $id]);
} elseif ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: edit_menu.php');