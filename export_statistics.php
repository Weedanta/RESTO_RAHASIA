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

// Query data statistik
$statistics = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM reservations) AS Waiting_List,
        (SELECT COUNT(*) FROM approved_reservations) AS Approved,
        (SELECT COUNT(*) FROM rejected_reservations) AS Rejected,
        (SELECT COUNT(*) FROM users) AS Registered_Users
")->fetch(PDO::FETCH_ASSOC);

$reservationDistribution = $pdo->query("
    SELECT MONTHNAME(created_at) AS Month, COUNT(*) AS Total_Reservations
    FROM reservations
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at)
")->fetchAll(PDO::FETCH_ASSOC);

$paymentMethodsDistribution = $pdo->query("
    SELECT payment_method AS Payment_Method, COUNT(*) AS Total
    FROM (
        SELECT payment_method FROM approved_reservations
        UNION ALL
        SELECT payment_method FROM rejected_reservations
    ) AS combined
    GROUP BY payment_method
")->fetchAll(PDO::FETCH_ASSOC);

// Menggabungkan semua data ke array
$data = [
    'Statistics' => $statistics,
    'Reservation_Distribution' => $reservationDistribution,
    'Payment_Methods_Distribution' => $paymentMethodsDistribution,
];

// Header untuk file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Statistics_Export_' . date('Y-m-d') . '.xls"');

// Membuat tabel HTML
echo "<html><body>";
foreach ($data as $sheetName => $rows) {
    echo "<table border='1'><thead><tr><th colspan='2'>$sheetName</th></tr></thead><tbody>";
    foreach ($rows as $key => $row) {
        echo "<tr>";
        if (is_array($row)) {
            foreach ($row as $column) {
                echo "<td>$column</td>";
            }
        } else {
            echo "<td>$key</td><td>$row</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table><br>";
}
echo "</body></html>";
exit;
?>