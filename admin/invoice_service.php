<?php
include '../components/connect.php';
require_once 'send_invoice.php';
//==========================================================================================
// invoice_service.php

// Include database connection

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if admin_id is set in session, otherwise redirect to login page
$admin_id = $_SESSION['admin_id'] ?? null; // Using null coalescing operator to avoid notice
if (!$admin_id) {
    header('Location: admin_login.php');
    exit;
}

// Handle form submission
$message = [];
if (isset($_POST['send'])) {
    // Sanitize and retrieve form data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $motor = filter_var($_POST['motor'], FILTER_SANITIZE_STRING);
    $plat = filter_var($_POST['plat'], FILTER_SANITIZE_STRING);
    $tanggal = filter_var($_POST['tanggal'], FILTER_SANITIZE_STRING);
    $layanan = $_POST['layanan']; // array of selected service names

    // Generate a unique order number
    $no_order = uniqid();

    // Check if the same invoice already exists
    $select_message = $conn->prepare("SELECT * FROM `invoice` WHERE `name` = ? AND `motor` = ? AND `plat` = ? AND `tanggal` = ?");
    $select_message->execute([$name, $motor, $plat, $tanggal]);

    if ($select_message->rowCount() > 0) {
        $message[] = 'Invoice telah ditambahkan';
    } elseif (empty($layanan)) {
        $message[] = 'Pilih minimal satu layanan!';
    } else {
        // Prepare an array of service names and calculate total price
        $services = [];
        $totalPrice = 0;

        // Insert invoice into database
        $insert_message = $conn->prepare("INSERT INTO `invoice`(name, motor, plat, tanggal, layanan, total_harga) VALUES(?,?,?,?,?,?)");
        $insert_message->execute([$name, $motor, $plat, $tanggal, $services_json, $totalPrice]);

        // Redirect to invoice history page after successful insertion
        header('Location: invoice_history.php');
        exit();
    }
}

// Fetch services from the database
$select_layanan = $conn->prepare("SELECT * FROM `layanan`");
$select_layanan->execute();
$layanan_list = $select_layanan->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Add your custom styles here */
        .service-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .service-item label {
            font-size: 1.5em;
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            margin-right: 10px;
        }
        .service-item input {
            margin-right: 5px;
        }
        .selected-services, .total-price {
            font-size: 1.5em;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">
    <h1 class="heading">Tambahkan Invoice</h1>
    <?php
        $id_reservasi = $_POST['reservasi_id'];
        $select_reservasi = $conn->prepare("SELECT * FROM reservasi WHERE id = ?");
        $select_reservasi->execute([$id_reservasi]);
        $reservasi = $select_reservasi->fetch(PDO::FETCH_ASSOC);
    ?>

    <form action="tambah_invoice.php" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
                <span><strong>Nama Pelanggan</strong></span><br>
                <?php echo '<span>' . $reservasi["name"] . '</span>'; ?>
                <?php echo '<input type="hidden" name="pelanggan" value="' . $reservasi["name"] . '">'; ?>
                <!--<input type="text" class="box" required maxlength="100" placeholder="Masukkan nama pelanggan" name="name" id="name">-->
            </div>
            <div class="inputBox">
                <span><strong>Jenis Motor</strong></span><br>
                <?php echo '<span>' . $reservasi["motor"] . '</span>'; ?>
                <?php echo '<input type="hidden" name="motor" value="' . $reservasi["motor"] . '">'; ?>
                
                <!--<input type="text" class="box" required maxlength="100" placeholder="Masukkan jenis motor" name="motor" id="motor">-->
            </div>
            <div class="inputBox">
                <span><strong>Plat Motor</strong></span><br>
                <?php echo '<span>' . $reservasi["plat"] . '</span>'; ?>
                <?php echo '<input type="hidden" name="plat" value="' . $reservasi["plat"] . '">'; ?>
                <!--<input type="text" class="box" required maxlength="100" placeholder="Masukkan plat motor" name="plat" id="plat">-->
            </div>
            <div class="inputBox">
                <span><strong>Tanggal untuk reservasi</strong></span><br>
                <?php echo '<span>' . $reservasi["tanggal"] . '</span>'; ?>
                <?php echo '<input type="hidden" name="tanggal" value="' . $reservasi["tanggal"] . '">'; ?>
                <!--<input type="date" class="box" required name="tanggal" id="tanggal">-->
            </div>
            <div class="inputBox">
                <span><strong>No Reservasi</strong></span><br>
                <?php echo '<span>' . $reservasi["id"] . '</span>'; ?>
                <?php echo '<input type="hidden" name="id_reservasi" value="' . $reservasi["id"] . '">'; ?>
                <!--<input type="date" class="box" required name="tanggal" id="tanggal">-->
            </div>
            <div class="inputBox">
                <span><strong>No Whatsapp</strong></span><br>
                <?php echo '<span>' . $reservasi["no_wa"] . '</span>'; ?>
                <?php echo '<input type="hidden" name="no_wa" value="' . $reservasi["no_wa"] . '">'; ?>
                <!--<input type="date" class="box" required name="tanggal" id="tanggal">-->
            </div>
            <hr style = "margin-top : 10px;">

            <table id="tbl" class="table" border="1">
                <thead>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th> Delete</th>
                </thead>
                        
                <tbody id = "tbod">
                    <tr>
                        <td><input type="text" name="nama0" placeholder = "Nama"></td>
                        <td><input type="number" name="harga0" placeholder = "Harga"></td>
                        <td><input type="button" name="del" value="Delete" onclick="delStudent(this)" class="btn btn-danger"></td>
                    </tr>
                </tbody>
                <input type="button" name="add" value="Tambah Layanan" onclick="addStudent();" class="btn btn-success" style = "margin: 20px; margin-left: 0px; padding: 10px">
            </table>
        </div>
        <input type="submit" value="Simpan" class="btn" name="send">
    </form>

    <?php if (!empty($message)): ?>
        <?php foreach ($message as $msg): ?>
            <p class="message"><?= $msg; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

</section>

<!-- JavaScript to handle service selection -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceCheckboxes = document.querySelectorAll('#service-checkboxes input[type="checkbox"]');

        serviceCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                updateSelectedServices();
            });
        });

        function updateSelectedServices() {
            const selectedServices = [];

            serviceCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedServices.push(checkbox.value);
                }
            });

            // Set the hidden input value with selected services JSON string
            document.getElementById('layanan').value = JSON.stringify(selectedServices);
        }
    });
</script>

<!-- Buat nambah baris layanan -->
<script type="text/javascript">

var baris = 1;
   
function addStudent()
{   
    var tr = document.createElement('tr');
    
    var td1 = tr.appendChild(document.createElement('td'));
    var td2 = tr.appendChild(document.createElement('td'));
    var td3 = tr.appendChild(document.createElement('td'));
    
    
    td1.innerHTML='<input type="text" name="nama'+ baris +'" placeholder = "Nama">';
    td2.innerHTML='<input type="number" name="harga' + baris + '" placeholder = "Harga">';
    td3.innerHTML='<input type="button" name="del" value="Delete" onclick="delStudent(this)" class="btn btn-danger">';

    document.getElementById("tbod").appendChild(tr);
    baris++;
}

function delStudent(Stud){
    var s=Stud.parentNode.parentNode;
    s.parentNode.removeChild(s);
}


</script>

</body>
</html>
