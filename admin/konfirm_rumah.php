<?php

include '../components/connect.php';

//session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_reservasi'])){
   $reservasi_id = $_POST['reservasi_id'];
   $status = "dikonfirmasi";
 //  $reservasi_status = $_POST['reservasi_status'];
 //  $reservasi_status = filter_var($reservasi_status, FILTER_SANITIZE_STRING);
     $update_reservasi = $conn->prepare("UPDATE `reservasi` SET reservasi_status = ? WHERE id = ?");
     $update_reservasi->execute([$status, $reservasi_id]);
  // $update_reservasi->execute([$reservasi_status, $reservasi_id]);
   $message[] = 'Reservasi berhasil dikonfirmasi';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_reservasi = $conn->prepare("DELETE FROM `reservasi` WHERE id = ?");
   $delete_reservasi->execute([$delete_id]);
   header('location:reservasi_reservasi.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reservasi Service Motor</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">

<h1 class="heading">Reservasi Service Motor.</h1>
<div class="box-container">

   <?php
      $temp_date = '0';
      $select_reservasi = $conn->prepare("SELECT * FROM `reservasi` WHERE reservasi_status = 'Dirumah(menunggu)' ORDER BY tanggal");
      $select_reservasi->execute();
      if($select_reservasi->rowCount() > 0){
         while($fetch_reservasi = $select_reservasi->fetch(PDO::FETCH_ASSOC)){
            if($temp_date != $fetch_reservasi['tanggal']){
               echo '</div>';
               echo '<h1>Tanggal : '. $fetch_reservasi["tanggal"] .'</h1>';
               echo '<div class="box-container">';
               ?>
                  <div class="box">
                     <p> Nomor Order : <span><?= $fetch_reservasi['no_order']; ?></span> </p>
                     <p> Reservasi Masuk : <span><?= $fetch_reservasi['ditetapkan']; ?></span> </p>
                     <p> Nama : <span><?= $fetch_reservasi['name']; ?></span> </p>
                     <p> Jenis Motor : <span><?= $fetch_reservasi['motor']; ?></span> </p>
                     <p> Plat Nomor : <span><?= $fetch_reservasi['plat']; ?></span> </p>
                     <p> Tanggal Reservasi : <span><?= $fetch_reservasi['tanggal']; ?></span> </p>
                     <form action="invoice_service.php" method="post">
                        <input type="hidden" name="reservasi_id" value="<?= $fetch_reservasi['id']; ?>">

                        <?php
                           if($fetch_reservasi['reservasi_status'] == "dikonfirmasi"){
                              echo '<h1>Dikonfirmasi</h1>';
                           }else{
                        ?>
                     <div class="flex-btn">
                        <input type="submit" value="Konfirmasi" class="option-btn" name="update_reservasi">
                        
                     </div>
                     <?php
                           }
                     ?>
                     </form>
                  </div>
               <?php
            }else{
               ?>
                  <div class="box">
                     <p> Nomor Order : <span><?= $fetch_reservasi['no_order']; ?></span> </p>
                     <p> Reservasi Masuk : <span><?= $fetch_reservasi['ditetapkan']; ?></span> </p>
                     <p> Nama : <span><?= $fetch_reservasi['name']; ?></span> </p>
                     <p> Jenis Motor : <span><?= $fetch_reservasi['motor']; ?></span> </p>
                     <p> Plat Nomor : <span><?= $fetch_reservasi['plat']; ?></span> </p>
                     <p> Tanggal Reservasi : <span><?= $fetch_reservasi['tanggal']; ?></span> </p>
                     <form action="invoice_service.php" method="post">
                        <input type="hidden" name="reservasi_id" value="<?= $fetch_reservasi['id']; ?>">
                     <div class="flex-btn">
                        <input type="submit" value="Konfirmasi" class="option-btn" name="update_reservasi">
                        
                     </div>
                     </form>
                  </div>
               <?php
            }
      $temp_date = $fetch_reservasi['tanggal'];
         }
      }else{
         echo '<p class="empty">tidak ada reservasi masuk</p>';
      }
   ?>

</div>

</section>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>