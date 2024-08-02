<?php
// invoice_history.php

include '../components/connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

// Fetch all invoices from the database with service details
$select_invoices = $conn->prepare("SELECT * FROM invoice");
$select_invoices->execute();
$invoices = $select_invoices->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice History</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="show-products">

    <h1 class="heading">Riwayat Invoice</h1>

    <div class="box-container">

    <?php
    if ($select_invoices->rowCount() > 0) {
        foreach ($invoices as $invoice) {
            // Decode JSON arrays of services and prices
            
            echo '<div class="box">';
            echo '<div class="name">Nama: ' . $invoice['name'] . '</div>';
            echo '<div class="motor">Motor: ' . $invoice['motor'] . '</div>';
            echo '<div class="plat">Plat: ' . $invoice['plat'] . '</div>';
            echo '<div class="tanggal">Tanggal: ' . $invoice['tanggal'] . '</div>';
            // Display each service with its price in a list

            echo '<div class="total-invoice">Total Harga : Rp.' . number_format($invoice['total_harga'], 2) . '</div>';

            // Add print button
            echo '<div class="print-btn"><a href="invoice_php/invoice'.$invoice['id'].'.php" class="btn btn-print">Cetak Invoice</a></div>';

            echo '</div>';
        }
    } else {
        echo '<p class="empty">Belum ada invoice yang ditambahkan!</p>';
    }
    ?>

    </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
