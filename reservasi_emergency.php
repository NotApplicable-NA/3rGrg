<?php

include 'components/connect.php';
include 'sendwa.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
   $nama_user = $_SESSION['nama_user'];
 }else{
    $user_id = '';
 };

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $motor = $_POST['motor'];
   $motor = filter_var($motor, FILTER_SANITIZE_STRING);
   $plat = $_POST['plat'];
   $plat = filter_var($plat, FILTER_SANITIZE_STRING);
   $tanggal = $_POST['tanggal'];
   $tanggal = filter_var($tanggal, FILTER_SANITIZE_STRING);
   $no_wa = $_POST['no_wa'];
   $no_wa = filter_var($no_wa, FILTER_SANITIZE_STRING);

   $query = "SELECT * FROM reservasi WHERE tanggal = ?";
   $stmt = $conn->prepare($query);

   $stmt->execute([$tanggal]);
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   //$reservasi_harian->fetchAll(PDO::FETCH_ASSOC);
   $cek = 0;
   foreach($result as $row){
      $cek++;
   }

   $select_message = $conn->prepare("SELECT * FROM `reservasi` WHERE name = ? AND motor = ? AND plat = ? AND tanggal = ?");
   $select_message->execute([$name, $motor, $plat, $tanggal]);

   if($select_message->rowCount() > 0){
      $message[] = 'reservasi telah dilakukan';
   }elseif($cek <= 2){
      $no_order = $cek + 1;
      $status = 'Emergency(menunggu)';
      $insert_message = $conn->prepare("INSERT INTO `reservasi`(user_id, name, motor, plat, tanggal, no_order, reservasi_status, no_wa) VALUES(?,?,?,?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $motor, $plat, $tanggal, $no_order, $status, $no_wa]);

      send_wa($no_wa);
      $message[] = "Reservasi berhasil";

   }else{
      $message[] = "Reservasi Tanggal Ini Penuh";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reservasi Service Motor</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <form action="" method="post">
      <h3>Reservasi Service Motor</h3>
      <?php
        if(isset($nama_user)){
            echo '<span style = "font-size: 25px;"><strong>Nama : </strong></span><span style = "font-size: 25px;">'. $nama_user.'</span>'; 
            echo '<input type="hidden" name="name" value="' . $nama_user . '">';
        }else{
            echo '<input type="text" name="name" placeholder="masukkan nama" required maxlength="50" class="box">';
        }
      ?>
      <input type="text" name="motor" placeholder="masukkan jenis motor" required maxlength="50" class="box">
      <input type="text" name="plat" placeholder="masukkan plat nomor" required maxlength="20" class="box">
      <input type="date" name="tanggal" placeholder="masukkan tanggal reservasi" class="box">
      <input type="text" name="no_wa" placeholder="masukkan nomor whatsapp" required maxlength="20" class="box">
      <input type="submit" value="Kirim Reservasi" name="send" class="btn">
      <a href="riwayat_reservasi.php" class="btn">Lihat Reservasi</a>
   </form>

</section>












<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>