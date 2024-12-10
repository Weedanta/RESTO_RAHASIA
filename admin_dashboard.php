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

// Query Statistik
$waitingListQuery = $pdo->query("SELECT COUNT(*) AS total FROM reservations")->fetch();
$approvedReservationsQuery = $pdo->query("SELECT COUNT(*) AS total FROM approved_reservations")->fetch();
$rejectedReservationsQuery = $pdo->query("SELECT COUNT(*) AS total FROM rejected_reservations")->fetch();
$totalUsersQuery = $pdo->query("SELECT COUNT(*) AS total FROM users")->fetch();

// Hitung jumlah total reservasi
$waitingList = $waitingListQuery['total'];
$totalApproved = $approvedReservationsQuery['total'];
$totalRejected = $rejectedReservationsQuery['total'];
$totalReservations = $totalApproved + $totalRejected;

// Jumlah pengguna
$totalUsers = $totalUsersQuery['total'];

// Validasi Data Kosong

$totalApproved = $totalApproved ?? 0;
$totalRejected = $totalRejected ?? 0;
$waitingList = $waitingList ?? 0;
$totalReservations = $totalReservations ?? 0;
$totalUsers = $totalUsers ?? 0;

$trendQuery = $pdo->query("
    SELECT 
        MONTH(created_at) AS month, 
        MONTHNAME(created_at) AS month_name, 
        COUNT(*) AS total 
    FROM reservations 
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at)
")->fetchAll(PDO::FETCH_ASSOC);

$paymentMethodsQuery = $pdo->query("
    SELECT payment_method, COUNT(*) AS total
    FROM (
        SELECT payment_method FROM approved_reservations
        UNION ALL
        SELECT payment_method FROM rejected_reservations
    ) AS combined
    GROUP BY payment_method
")->fetchAll(PDO::FETCH_ASSOC);

$paymentLabels = json_encode(array_column($paymentMethodsQuery, 'payment_method'));
$paymentData = json_encode(array_column($paymentMethodsQuery, 'total'));

// Query Statistik Menu
$menuCountQuery = $pdo->query("SELECT COUNT(*) AS total_menus FROM menu")->fetch();
$categoryCountQuery = $pdo->query("
    SELECT kategori, COUNT(*) AS total
    FROM menu
    GROUP BY kategori
")->fetchAll(PDO::FETCH_ASSOC);

// Data Statistik Menu
$totalMenus = $menuCountQuery['total_menus'];
$categories = json_encode(array_column($categoryCountQuery, 'kategori'));
$categoryCounts = json_encode(array_column($categoryCountQuery, 'total'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Tombol Sidebar Toggle -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="active"><i class="fa fa-home"></i> Dashboard</a>
        <a href="edit_menu.php"><i class="fa fa-edit"></i> Edit Menu</a>
        <a href="view_reservations.php"><i class="fa fa-list"></i> Reservation List</a>
        <a href="approved_reservations.php"><i class="fa fa-check"></i> Approved Reservations</a>
        <a href="rejected_reservations.php"><i class="fa fa-times"></i> Rejected Reservations</a>
        <a href="logout_admin.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <div class="topbar">
            <h1>Statistics</h1>
        </div>

        <div class="dashboard-stats">
    <!-- Statistik Header -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>Waiting List</h3>
            <p><?= $waitingList ?></p>
        </div>
        <canvas id="waitingListChart"></canvas>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3>Approved</h3>
            <p><?= $totalApproved ?></p>
        </div>
        <canvas id="approvedChart"></canvas>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3>Rejected</h3>
            <p><?= $totalRejected ?></p>
        </div>
        <canvas id="rejectedChart"></canvas>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Reservations</h3>
            <p><?= $totalReservations ?></p>
        </div>
        <canvas id="totalReservationsChart"></canvas>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3>Registered Users</h3>
            <p><?= $totalUsers ?></p>
        </div>
        <canvas id="usersChart"></canvas>
    </div>
    <div class="stat-card">
        <h4>Reservation Distribution</h4>
        <canvas id="reservationPieChart"></canvas>
    </div>
    <div class="stat-card">
        <h4>Payment Methods Distribution</h4>
        <canvas id="paymentBarChart"></canvas>
    </div>
    <div class="stat-card">
        <h4>Menu Categories</h4>
        <canvas id="categoryChart"></canvas>
    </div>
    <div class="stat-card">
        <h4>Total Menu Listed</h4>
        <canvas id="menuChart"></canvas>
    </div>
</div>

<a href="export_statistics.php" class="download-btn"><i class="fa fa-download"></i> Download Statistics</a>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk Mini Charts
const miniChartConfig = (data, color) => ({
    type: 'doughnut',
    data: {
        labels: ['Count'],
        datasets: [{
            data: [data, 100 - data],
            backgroundColor: [color, 'rgba(200, 200, 200, 0.3)'],
            borderWidth: 0,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        cutout: '75%',
    }
});

// Mini Charts
new Chart(document.getElementById('waitingListChart'), miniChartConfig(<?= $waitingList ?>, 'rgba(255, 99, 132, 0.8)'));
new Chart(document.getElementById('approvedChart'), miniChartConfig(<?= $totalApproved ?>, 'rgba(54, 162, 235, 0.8)'));
new Chart(document.getElementById('rejectedChart'), miniChartConfig(<?= $totalRejected ?>, 'rgba(255, 206, 86, 0.8)'));
new Chart(document.getElementById('totalReservationsChart'), miniChartConfig(<?= $totalReservations ?>, 'rgba(75, 192, 192, 0.8)'));
new Chart(document.getElementById('usersChart'), miniChartConfig(<?= $totalUsers ?>, 'rgba(153, 102, 255, 0.8)'));

// Pie Chart
new Chart(document.getElementById('reservationPieChart'), {
    type: 'pie',
    data: {
        labels: ['Waiting List', 'Approved', 'Rejected'],
        datasets: [{
            data: [<?= $waitingList ?>, <?= $totalApproved ?>, <?= $totalRejected ?>],
            backgroundColor: ['rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 'rgba(255, 206, 86, 0.8)'],
        }]
    }
});

// Bar Chart for Payment Methods
new Chart(document.getElementById('paymentBarChart'), {
        type: 'bar',
        data: {
            labels: <?= $paymentLabels ?>,
            datasets: [{
                label: 'Payment Methods',
                data: <?= $paymentData ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { title: { display: true, text: 'Payment Methods' } },
                y: { title: { display: true, text: 'Count' }, beginAtZero: true }
            }
        }
    });

   // Grafik Kategori Menu dalam bentuk Pie Chart
new Chart(document.getElementById('menuChart'), {
    type: 'pie',
    data: {
        labels: <?= $categories ?>, // Kategori menu dari PHP
        datasets: [{
            data: <?= $categoryCounts ?>, // Jumlah menu per kategori
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ],
            hoverBackgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ]
        }]
    },
    options: {
        plugins: {
            legend: { display: true }, // Tampilkan legenda
        },
        responsive: true,
    }
});

// Grafik Kategori Menu
new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: <?= $categories ?>,
        datasets: [{
            label: 'Menu Categories',
            data: <?= $categoryCounts ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            x: { title: { display: true, text: 'Categories' } },
            y: { title: { display: true, text: 'Count' }, beginAtZero: true }
        }
    }
});
    </script>
</body>
</html>