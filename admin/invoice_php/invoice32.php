
        <?php
        include '../../components/connect.php';

        $admin_id = $_SESSION["admin_id"];
        $admin_name = $_SESSION["nama_admin"];
        $select_admin = $conn->prepare("SELECT * FROM admins WHERE id =? AND name = ?; ");
        $select_admin->execute([$admin_id,$admin_name]);
            if($select_admin->rowCount()<=0){
                header("Location: ../invoice/invoice32.html");
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
                        <p><?= $fetch_profile['name']; ?></p>
                        <a href="../admin/update_profile.php" class="btn">Update Profile</a>
                        <div class="flex-btn">
                            <a href="../admin/register_admin.php" class="option-btn">Register</a>
                            <a href="../admin/admin_login.php" class="option-btn">Login</a>
                        </div>
                        <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
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
                    <p><strong>Nama Pelanggan:</strong>asep</p>
                    <p><strong>Jenis Motor:</strong>Vario</p>
                    <p><strong>Nomor Plat Motor:</strong>B 4 BI</p>
                    <h3>Jenis Service</h3>
                    <table>
                        <tr>
                            <th>Nama Service</th>
                            <th>Harga Service</th>
                        </tr>
                        <tr>
                            <td>rantai</td>
                            <td>43000</td>
                        </tr><tr>
                            <td>aki</td>
                            <td>300000</td>
                        </tr>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>343000</td>
                            </tr>
                        </tfoot>
                    </table>
                    <button class="print-button" onclick="window.print()">Cetak</button>
                </div>
        </body>
        </html>
        