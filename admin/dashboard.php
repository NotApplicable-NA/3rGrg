<?php

include '../components/connect.php';

if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin 3R Garage</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Admin 3R Garage</h1>

   <div class="box-container">

      <div class="box">
         <h3>Selamat Datang</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Update Profile</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `reservasi` WHERE reservasi_status = 'menunggu';");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>Reservasi Service</p>
         <a href="konfirm_reservasi.php" class="btn">Lihat Reservasi</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `reservasi` WHERE reservasi_status = 'Dirumah(menunggu)'");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>Reservasi Service di Rumah</p>
         <a href="konfirm_rumah.php" class="btn">Lihat Reservasi di Rumah</a>
      </div>
<!--
      <div class="box">
         <?php
            /*$select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()*/
         ?>
         <h3><?// $number_of_messages; ?></h3>
         <p>Riwayat Reservasi Service</p>
         <a href="messages.php" class="btn">Lihat Riwayat Reservasi di Rumah</a>
      </div>
-->
      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `reservasi` WHERE reservasi_status = 'Emergency(menunggu)';");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>Reservasi Service Emergency</p>
         <a href="konfirm_emergency.php" class="btn">Lihat Reservasi Emergency</a>
      </div>
<!--
      <div class="box">
         <?php
            //$select_messages = $conn->prepare("SELECT * FROM `reservasi` WHERE reservasi_status = 'Emergency(menunggu)';");
            //$select_messages->execute();
            //$number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?php //$number_of_messages; ?></h3>
         <p>Riwayat Reservasi Emergency</p>
         <a href="messages.php" class="btn">Lihat Riwayat Reservasi Emergency</a>
      </div>
-->
      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if($select_pendings->rowCount() > 0){
               while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
         ?>
         <h3><span>Rp.</span><?= $total_pendings; ?><span>/-</span></h3>
         <p>Pesanan Tertuda</p>
         <a href="placed_orders.php" class="btn">Lihat Pesanan.</a>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            if($select_completes->rowCount() > 0){
               while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                  $total_completes += $fetch_completes['total_price'];
               }
            }
         ?>
         <h3><span>Rp.</span><?= $total_completes; ?><span>/-</span></h3>
         <p>Pesanan Selesai</p>
         <a href="placed_orders.php" class="btn">Lihat Pesanan</a>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
         ?>
         <h3><?= $number_of_orders; ?></h3>
         <p>Pesanan Ditetapkan.</p>
         <a href="placed_orders.php" class="btn">Lihat Pesanan.</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p>Produk Ditambahkan</p>
         <a href="products.php" class="btn">Lihat Produk</a>
      </div>
<!--
      <div class="box">
         <?php
           /* $select_layanan = $conn->prepare("SELECT * FROM `layanan`");
            $select_layanan->execute();
            $number_of_layanan = $select_layanan->rowCount()*/
         ?>
         <h3><?php //$number_of_layanan; ?></h3>
         <p>Layanan Ditambahkan</p>
         <a href="layanan.php" class="btn">Lihat Layanan</a>
      </div>
         -->

      <div class="box">
         <?php
            $select_invoice = $conn->prepare("SELECT * FROM `invoice`");
            $select_invoice->execute();
            $number_of_invoice = $select_invoice->rowCount()
         ?>
         <h3><?= $number_of_invoice; ?></h3>
         <p>Invoice Ditambahkan</p>
         <a href="invoice_history.php" class="btn">Lihat Invoice</a>
      </div>
<!--
      <div class="box">
         <?php
            /*$select_invoice = $conn->prepare("SELECT * FROM `invoice`");
            $select_invoice->execute();
            $number_of_invoice = $select_invoice->rowCount()*/
         ?>
         <h3><?php //$number_of_invoice; ?></h3>
         <p>Riwayat Invoice</p>
         <a href="invoice_history.php" class="btn">Lihat RIwayat Invoice</a>
      </div>
         -->
      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>Normal users</p>
         <a href="users_accounts.php" class="btn">Lihat Users</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>Admin users</p>
         <a href="admin_accounts.php" class="btn">Lihat Admin</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>Pesan Baru</p>
         <a href="messages.php" class="btn">Lihat Pesan</a>
      </div>

      

   </div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>