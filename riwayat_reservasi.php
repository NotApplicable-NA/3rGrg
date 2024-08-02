<?php

include 'components/connect.php';


if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = NULL;
   header('location:user_login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['no_order'];
   $delete_cart_item = $conn->prepare("DELETE FROM `reservasi` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `reservasi` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:riwayat_reservasi.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Riwayat Reservasi</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header_shop.php'; ?>

<section class="orders">

<h1 class="heading">Reservasi Service Motor.</h1>

<div class="box-container">

   <?php
   if(isset($user_id)){
      $select_reservasi = $conn->prepare("SELECT * FROM `reservasi` WHERE user_id = ? ORDER BY tanggal; ");
      $select_reservasi->execute([$user_id]);
      if($select_reservasi->rowCount() > 0){
         $no_order = 1;
         while($fetch_reservasi = $select_reservasi->fetch(PDO::FETCH_ASSOC)){
            if($fetch_reservasi['reservasi_status'] == "dikonfirmasi"){

               ?>
                  <div class="box">
                        <p> Nomor Order : <span><?= $fetch_reservasi['no_order']; ?></span> </p>
                        <p> Reservasi Masuk : <span><?= $fetch_reservasi['ditetapkan']; ?></span> </p>
                        <p> Nama : <span><?= $fetch_reservasi['name']; ?></span> </p>
                        <p> Jenis Motor : <span><?= $fetch_reservasi['motor']; ?></span> </p>
                        <p> Plat Nomor : <span><?= $fetch_reservasi['plat']; ?></span> </p>
                        <p> Tanggal Reservasi : <span><?= $fetch_reservasi['tanggal']; ?></span> </p>
                        <form action="" method="post">
                           <input type="hidden" name="reservasi_id" value="<?= $fetch_reservasi['id']; ?>">
                        <div class="flex-btn">
                           <input type="submit" value="Konfirmasi" class="option-btn" name="update_reservasi">
                           
                        </div>
                        </form>
                     </div>
               <?php

            }
            $no_order++;
         }
      }else{
         echo '<p class="empty">Riwayat Reservasi Kosong</p>';
      }
   }
   ?>

</div>

</section>












<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>