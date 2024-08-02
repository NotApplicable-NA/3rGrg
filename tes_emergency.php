<?php
include 'components/connect.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $pesan = $_POST['message'];
    $no_wa = $_POST['number'];

    $token = "2v9uposHjGciSz8N@4cY";
    $target = $no_wa;
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.fonnte.com/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
    'target' => $target,
    'message' => 'Saya sedang dalam keadaan darurat'
    ),
    CURLOPT_HTTPHEADER => array(
        "Authorization: $token"
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
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
   <button type="submit" class="emergency-button" onclick="shareWhatsAppMessage()">Emergency</button>
   <form><input type="hidden" id="pesan" name="message" value="5" /></form>
   <form><input type="hidden" id="no_wa" name="number" value="5" /></form>
   </form>

   <script>
      function shareWhatsAppMessage() {
         if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
               var latitude = position.coords.latitude;
               var longitude = position.coords.longitude;
               var phoneNumber = '6282220952025'; // Ganti dengan nomor WhatsApp bengkel
               var message = '3R Garage Emergency: Saya memerlukan bantuan segera! Lokasi saya: https://www.google.com/maps?q=' + latitude + ',' + longitude;
                process1(phoneNumber, message);
            });
         } else {
            alert('Geolokasi tidak didukung oleh browser Anda.');
         }
      }
        function process1(phone_number, message) {
            document.getElementById("no_wa").value = phone_number.value;
            document.getElementById("pesan").value = message.value;
        }

   </script>

   
</section>
<?php include 'components/footer.php'; ?>

</body>
</html>
