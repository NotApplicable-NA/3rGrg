<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $update_layanan = $conn->prepare("UPDATE `layanan` SET name = ?, price = ?, details = ? WHERE id = ?");
   $update_layanan->execute([$name, $price, $details, $pid]);

   $message[] = 'layanan berhasil diperbarui!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Layanan</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">Update Layanan</h1>

   <?php
      $update_id = $_GET['update'];
      $select_layanan = $conn->prepare("SELECT * FROM `layanan` WHERE id = ?");
      $select_layanan->execute([$update_id]);
      if($select_layanan->rowCount() > 0){
         while($fetch_layanan = $select_layanan->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_layanan['id']; ?>">
      <span>Update Nama Layanan</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="masukkan nama layanan" value="<?= $fetch_layanan['name']; ?>">
      <span>Update Harga Layanan</span>
      <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="masukkan harga layanan" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_layanan['price']; ?>">
      <span>Update Detail Layanan</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_layanan['details']; ?></textarea>
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="update">
         <a href="layanan.php" class="option-btn">Kembali.</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">layanan tidak ditemukan!</p>';
      }
   ?>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>