<?php
include 'components/connect.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Panggilan Emergency</title>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .emergency-button {
         background-color: red;
         color: white;
         font-size: 24px;
         padding: 10px 20px;
         border: none;
         cursor: pointer;
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<section class="contact">

   <form action="" method="post">
      <h1>Tekan tombol "Emergency" untuk melakukan kontak</h1>
      <h1>Aramada 3R Garage akan segera merapat ke lokasi Anda</h1>
      <button class="emergency-button" onclick="shareWhatsAppMessage()">Emergency</button>
   </form>

   <script>
      function shareWhatsAppMessage() {
         if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
               var latitude = position.coords.latitude;
               var longitude = position.coords.longitude;
               var phoneNumber = '6282220952025'; // Ganti dengan nomor WhatsApp bengkel
               var message = '3R Garage Emergency: Saya memerlukan bantuan segera! Lokasi saya: https://www.google.com/maps?q=' + latitude + ',' + longitude;
               var shareURL = 'https://wa.me/' + phoneNumber + '?text=' + encodeURIComponent(message);
               window.open(shareURL, '_blank');
            });
         } else {
            alert('Geolokasi tidak didukung oleh browser Anda.');
         }
      }
   </script>

   <?php include 'components/footer.php'; ?>
</section>

</body>
</html>
