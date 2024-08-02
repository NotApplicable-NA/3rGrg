<?php

include '../components/connect.php';

if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_layanan'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   
   $select_layanan = $conn->prepare("SELECT * FROM `layanan` WHERE name = ?");
   $select_layanan->execute([$name]);


      $insert_layanan = $conn->prepare("INSERT INTO `layanan`(name, details, price) VALUES(?,?,?)");
      $insert_layanan->execute([$name, $details, $price]);
      $message[] = 'layanan berhasil ditambahkan!';
      

   };  

   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete']; // Assign the value of 'delete' parameter to $delete_id
   
      $delete_layanan = $conn->prepare("DELETE FROM `layanan` WHERE id = ?");
      $delete_layanan->execute([$delete_id]); // Execute deletion using $delete_id
   
      header('location:layanan.php');
   }
   


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Layanan</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

   <h1 class="heading">Tambahkan Layanan</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Jenis Layanan (wajib diisi)</span>
            <input type="text" class="box" required maxlength="500" placeholder="masukkan jenis layanan" name="name">
         </div>
         <div class="inputBox">
            <span>Harga Layanan (wajib diisi)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="masukkan harga layanan" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <span>Deskripsi Produk (wajib) diisi</span>
            <textarea name="details" placeholder="masukkan deskripsi layanan" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="Tambahkan Layanan" class="btn" name="add_layanan">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">Layanan Ditambahkan.</h1>

   <div class="box-container">

   <?php
      $select_layanan = $conn->prepare("SELECT * FROM `layanan`");
      $select_layanan->execute();
      if($select_layanan->rowCount() > 0){
         while($fetch_layanan = $select_layanan->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <div class="name"><?= $fetch_layanan['name']; ?></div>
      <div class="price">Rp.<span><?= $fetch_layanan['price']; ?></span>/-</div>
      <div class="details"><span><?= $fetch_layanan['details']; ?></span></div>
      <div class="flex-btn">
         <a href="update_layanan.php?update=<?= $fetch_layanan['id']; ?>" class="option-btn">update</a>
         <a href="layanan.php?delete=<?= $fetch_layanan['id']; ?>" class="delete-btn" onclick="return confirm('hapus layanan?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">belum ada layanan yang ditambahkan!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>