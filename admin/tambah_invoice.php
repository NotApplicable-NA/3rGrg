<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        require_once '../components/connect.php';
        require_once 'send_invoice.php';
        $pelanggan = $_POST['pelanggan'];
        $motor = $_POST['motor'];
        $plat = $_POST['plat'];
        $tanggal = $_POST['tanggal'];
        $id_reservasi = $_POST['id_reservasi'];
        $no_wa = $_POST['no_wa'];
        $layanan = [];
        $harga = [];
        $index = 0;
        $totalPrice = 0;
        $isiTable = '';
        while(isset($_POST['nama'.$index])){
            $isiTable .= '<tr>
                            <td>' . $_POST["nama".$index] . '</td>
                            <td>' . $_POST["harga".$index] . '</td>
                        </tr>';
            $totalPrice += $_POST['harga'.$index];
            $index++;
        }

        $select_message = $conn->prepare("SELECT * FROM `invoice` WHERE `name` = ? AND `motor` = ? AND `plat` = ? AND `tanggal` = ?");
        $select_message->execute([$pelanggan, $motor, $plat, $tanggal]);

        if ($select_message->rowCount() > 0) {
            echo 'Invoice telah ditambahkan';
        }else{
            $insert_message = $conn->prepare("INSERT INTO `invoice`(name, motor, plat, tanggal, total_harga) VALUES(?,?,?,?,?)");
            $insert_message->execute([$pelanggan, $motor, $plat, $tanggal, $totalPrice]);
        }
        $select_message = $conn->prepare("SELECT * FROM `invoice` WHERE `name` = ? AND `motor` = ? AND `plat` = ? AND `tanggal` = ?");
        $select_message->execute([$pelanggan, $motor, $plat, $tanggal]);
        $id_invoice = '';
            $result = $select_message->fetchAll(PDO::FETCH_ASSOC);
            foreach($result as $row){
                $id_invoice = $row['id'];
            }

        $txt = '<!DOCTYPE html>
        <html>
        <head>
            <title>Invoice Service Motor</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: #f4f4f4;
                }
                .invoice-box {
                    max-width: 800px;
                    margin: auto;
                    padding: 30px;
                    border: 1px solid #eee;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                    background-color: #fff;
                }
                .invoice-box h1 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .invoice-box h2 {
                    margin-top: 0;
                }
                .invoice-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .invoice-header .info {
                    text-align: right;
                }
                .invoice-header img {
                    width: 100px;
                    height: auto;
                }
                table {
                    width: 100%;
                    line-height: 1.6;
                    text-align: left;
                    border-collapse: collapse;
                }
                table th, table td {
                    padding: 8px;
                    border: 1px solid #ddd;
                }
                table th {
                    background-color: #f2f2f2;
                }
                table tfoot td {
                    font-weight: bold;
                }
                .print-button {
                    display: block;
                    width: 100%;
                    max-width: 200px;
                    margin: 20px auto;
                    padding: 10px;
                    text-align: center;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    cursor: pointer;
                    font-size: 16px;
                }
                .print-button:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
                <div class="invoice-box">
                    <h1>Invoice Service Motor</h1>
                    <h1>3R GARAGE</h1>
                    <div class="invoice-header">
                        <div class="info">
                            <p>Jl. Pugeran, Suryodiningratan, Kec. Mantrijeron, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55141</p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Nama Pelanggan:</strong>' . $pelanggan . '</p>
                    <p><strong>Jenis Motor:</strong>' . $motor . '</p>
                    <p><strong>Nomor Plat Motor:</strong>' . $plat . '</p>
                    <h3>Jenis Service</h3>
                    <table>
                        <tr>
                            <th>Nama Service</th>
                            <th>Harga Service</th>
                        </tr>
                        '. $isiTable .'
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>' . $totalPrice . '</td>
                            </tr>
                        </tfoot>
                    </table>
                    <button class="print-button" onclick="window.print()">Cetak</button>
                </div>
        </body>
        </html>
        ';
        $txtphp = '
        
        <?php
        include "../../components/connect.php";

        $admin_id = $_SESSION["admin_id"];
        $admin_name = $_SESSION["nama_admin"];
        $select_admin = $conn->prepare("SELECT * FROM admins WHERE id =? AND name = ?; ");
        $select_admin->execute([$admin_id,$admin_name]);
            if($select_admin->rowCount()<=0){
                header("Location: ../invoice/invoice'.$id_invoice.'.html");
            }
        ?>
        
        <!DOCTYPE html>
        <html>
        <head>
            <title>Invoice Service Motor</title>
            
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

            <link rel="stylesheet" href="../../css/admin_style.css">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                    font-size:16px;
                }
                .invoice-box {
                    max-width: 800px;
                    margin: auto;
                    padding: 30px;
                    border: 1px solid #eee;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                    background-color: #fff;
                }
                .invoice-box h1 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .invoice-box h2 {
                    margin-top: 0;
                }
                .invoice-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .invoice-header .info {
                    text-align: right;
                }
                .invoice-header img {
                    width: 100px;
                    height: auto;
                }
                table {
                    width: 100%;
                    line-height: 1.6;
                    text-align: left;
                    border-collapse: collapse;
                }
                table th, table td {
                    padding: 8px;
                    border: 1px solid #ddd;
                }
                table th {
                    background-color: #f2f2f2;
                }
                table tfoot td {
                    font-weight: bold;
                }
                .print-button {
                    display: block;
                    width: 100%;
                    max-width: 200px;
                    margin: 20px auto;
                    padding: 10px;
                    text-align: center;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    cursor: pointer;
                    font-size: 16px;
                }
                .print-button:hover {
                    background-color: #45a049;
                }
            </style>

        </head>
        <body>
            <header class="header">

                <section class="flex">

                    <a href="../admin/dashboard.php" class="logo">Admin<span> 3R Garage</span></a>

                    <nav class="navbar">
                        <a href="../dashboard.php">Home</a>
                        <a href="../products.php">Produk</a>
                        <a href="../placed_orders.php">Pesanan</a>
                        <a href="../admin_accounts.php">Admin</a>
                        <a href="../users_accounts.php">User</a>
                        <a href="../messages.php">Pesan</a>
                    </nav>

                    <div class="icons">
                        <div id="menu-btn" class="fas fa-bars"></div>
                        <div id="user-btn" class="fas fa-user"></div>
                    </div>

                    <div class="profile">
                        <?php
                            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                            $select_profile->execute([$admin_id]);
                            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <p><?= $fetch_profile["name"]; ?></p>
                        <a href="../admin/update_profile.php" class="btn">Update Profile</a>
                        <div class="flex-btn">
                            <a href="../admin/register_admin.php" class="option-btn">Register</a>
                            <a href="../admin/admin_login.php" class="option-btn">Login</a>
                        </div>
                        <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm("logout from the website?");">logout</a> 
                    </div>

                </section>

    </header>
                <div class="invoice-box">
                    <h1>Invoice Service Motor</h1>
                    <h1>3R GARAGE</h1>
                    <div class="invoice-header">
                        <div class="info">
                            <p>Jl. Pugeran, Suryodiningratan, Kec. Mantrijeron, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55141</p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Nama Pelanggan:</strong>' . $pelanggan . '</p>
                    <p><strong>Jenis Motor:</strong>' . $motor . '</p>
                    <p><strong>Nomor Plat Motor:</strong>' . $plat . '</p>
                    <h3>Jenis Service</h3>
                    <table>
                        <tr>
                            <th>Nama Service</th>
                            <th>Harga Service</th>
                        </tr>
                        '. $isiTable .'
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>' . $totalPrice . '</td>
                            </tr>
                        </tfoot>
                    </table>
                    <button class="print-button" onclick="window.print()">Cetak</button>
                </div>
        </body>
        </html>
        ';
        $invoice = fopen("invoice/invoice" . $id_invoice . ".html", "w") or die("Unable to open file!");
        fwrite($invoice, $txt);
        fclose($invoice);

        $invoice = fopen("invoice_php/invoice" . $id_invoice . ".php", "w") or die("Unable to open file!");
        fwrite($invoice, $txtphp);
        fclose($invoice);

            $status = "dikonfirmasi";
          //  $reservasi_status = $_POST['reservasi_status'];
          //  $reservasi_status = filter_var($reservasi_status, FILTER_SANITIZE_STRING);
              $update_reservasi = $conn->prepare("UPDATE `reservasi` SET reservasi_status = ? WHERE id = ?");
              $update_reservasi->execute([$status, $id_reservasi]);
           // $update_reservasi->execute([$reservasi_status, $reservasi_id]);
            $message[] = 'Reservasi berhasil dikonfirmasi';

         send_invoice($no_wa, 'invoice'.$id_invoice.'.html');
    }
    header("Location: invoice_history.php")
?>