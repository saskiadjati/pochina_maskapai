<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'pochina');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Assuming user role is saved in session
$user_role = $_SESSION['role'] ?? '';

// Handle proof of payment upload
if (isset($_POST['upload_payment_proof'])) {
    $booking_id = $_POST['booking_id'];
    $payment_proof_file = $_FILES['payment_proof_file'];

    if ($payment_proof_file['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $payment_proof_file['tmp_name'];
        $file_name = 'uploads/' . uniqid('payment_proof_') . '_' . basename($payment_proof_file['name']);
        
        if (move_uploaded_file($file_tmp, $file_name)) {
            // Update the transaksi table with the proof of payment
            $update_sql = "UPDATE riwayat SET payment_proof = '$file_name' WHERE id = $booking_id";
            
            if (mysqli_query($conn, $update_sql)) {
                // Fetch the data for the booking from the transaksi table
                $select_sql = "SELECT * FROM transaksi WHERE id = $booking_id";
                $select_result = mysqli_query($conn, $select_sql);
                $transaction = mysqli_fetch_assoc($select_result);
                
                // Insert into 'riwayat' table
                $history_sql = "INSERT INTO riwayat (name, username, email, airline, departure_city, destination_city, flight_time, visa, ticket_type, total_price, seat_number, payment_method, account_number, payment_proof)
                                VALUES (
                                    '" . mysqli_real_escape_string($conn, $transaction['name']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['username']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['email']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['airline']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['departure_city']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['destination_city']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['flight_time']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['visa']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['ticket_type']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['total_price']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['seat_number']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['payment_method']) . "',
                                    '" . mysqli_real_escape_string($conn, $transaction['account_number']) . "',
                                    '$file_name'
                                )";
                
                if (mysqli_query($conn, $history_sql)) {
                    echo "<script>alert('Bukti pembayaran berhasil di-upload'); window.location.href='riwayat.php';</script>";
                } else {
                    echo "<script>alert('Gagal menyimpan riwayat');</script>";
                }
            } else {
                echo "<script>alert('Gagal menyimpan data bukti pembayaran');</script>";
            }
        } else {
            echo "<script>alert('Gagal meng-upload bukti pembayaran');</script>";
        }
    } else {
        echo "<script>alert('Error dalam pengunggahan file');</script>";
    }
}

// Handle soft delete of a booking (set delete_id to 1)
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if ($delete_id > 0) {
        $delete_sql = "UPDATE transaksi SET delete_id = 1 WHERE id = $delete_id";
        if (mysqli_query($conn, $delete_sql)) {
            echo "<script>alert('Data berhasil disembunyikan'); window.location.href='transaksi.php';</script>";
        } else {
            echo "<script>alert('Gagal menyembunyikan data'); window.location.href='transaksi.php';</script>";
        }
    } else {
        echo "<script>alert('ID tidak valid'); window.location.href='transaksi.php';</script>";
    }
}

// Fetch booking history from the database (exclude rows marked as deleted)
$sql = "SELECT * FROM transaksi WHERE delete_id = 0 ORDER BY flight_time DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Pochina Airlines</title>
    <style>
        /* Add your styles here */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

       /* Sosial Media */
       .medsos {
        background-color: #FFE893;
        padding: 10px 0;
        display: flex;
        justify-content: space-between; /* Added space between the elements */
        align-items: center; /* Center items vertically */
        }
        .medsos ul {
        display: flex;
        justify-content: left;
        gap: 15px;
        }
        .medsos ul li {
        display: inline-block;
        }
        .medsos ul li a {
        font-size: 18px;
        color: black;
        transition: transform 0.3s ease;
        }
        .medsos ul li a:hover {
        transform: scale(1.2);
        }

        header {
        background-color: #FBB4A5;
        padding: 20px 0;
        }

        header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        }

        header h1 {
        color: black;
        font-size: 28px;
        font-weight: bold;
        }

        header ul {
        display: flex;
        gap: 30px;
        }

        header ul li {
        list-style: none;
        }

        header ul li a {
        color: black;
        text-transform: uppercase;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        }

        header ul li a:hover,
        .active {
        background-color: #FFE893;
        }

        .table-container {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 500px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #FBB4A5;
            color: black;
            font-weight: bold;
        }

        table td {
            color: #555;
            padding: 14px 14px;
        }

        table td a {
            color: black;
            text-decoration: none;
            background-color: #FB9EC6;
            padding: 14px 14px;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        table td a:hover {
            background-color: #FFE893;
        }

        table th:nth-child(15), 
        table th:nth-child(10) { 
            width: 200px; 
        }

        table td:nth-child(20), 
        table td:nth-child(10) {
            width: 200px;
            word-wrap: break-word;
        }

        .back-button-container {
            margin-top: 40px;
            text-align: right;
        }

        .btn-back {
            padding: 12px 24px;
            font-size: 18px;
            border-radius: 5px;
            font-weight: bold;
            color: black;
            background-color: #FBB4A5;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #FFE893;
        }

        .btn-delete {
            display: inline-block;
            padding: 0px 8px;
            font-size: 15px;
            align-items: center;
            color: white;
            background-color: #FB9EC6;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border: 1px solid #FB9EC6;
        }

        .btn-delete i {
            margin-right: 5px;
            align-items: center;
        }

        .btn-delete:hover {
            background-color: #FFB4A2;
            border-color: #FFB4A2;
            transform: scale(1.05);
        }

        footer {
            padding: 20px 0;
            background-color: #FFE893;
            color: black;
            text-align: center;
            margin-top: 650px;
        }

        footer small {
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Sosial Media Links -->
    <div class="medsos">
        <div class="container">
            <ul>
                <li><a href="https://www.bki.co.id/halamanstatis-63.html#"><i class="fa-solid fa-globe"></i></a></li>
                <li><a href="https://www.instagram.com/bki_untukindonesia?igsh=NjlzZWFlZmp5NHVp"><i class="fa-brands fa-instagram"></i></a></li>
                <li><a href="https://youtube.com/@bki1964?si=tsr_YWtY18qhGWgT"><i class="fa-brands fa-youtube"></i></a></li>
            </ul>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php">Pochina Airplane</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="flight.php">Flight</a></li>
                <li class="active"><a href="transaksi.php">Transaksi</a></li>
                <li><a href="riwayat.php">Riwayat</a></li>
                <li><a href="profile.php" class="profile-logo"><i class="fa fa-user" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </header>

    <!-- Transaction History Table -->
    <div class="container">
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Seat Number</th>
                        <th>Waktu Penerbangan</th>
                        <th>Total Harga</th>
                        <th>Email</th>
                        <th>Payment Method</th>
                        <th>Account Number</th>
                        <th>Proof of Payment</th> 
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['departure_city']); ?></td>
                            <td><?php echo htmlspecialchars($row['destination_city']); ?></td>
                            <td><?php echo htmlspecialchars($row['seat_number']); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['flight_time'])); ?></td>
                            <td>IDR <?php echo number_format($row['total_price'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td>
                                <?php 
                                    if ($row['payment_method'] == 'BCA') {
                                        echo 'BCA - 5665056781';
                                    } elseif ($row['payment_method'] == 'BNI') {
                                        echo 'BNI - 5348712000';
                                    } elseif ($row['payment_method'] == 'BRI') {
                                        echo 'BRI - 78235461098';
                                    }
                                ?>
                            </td>
                            <td style="text-align: center; padding: 10px;">
                            <?php if (!empty($row['proof_of_payment'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['proof_of_payment']); ?>" target="_blank" 
                            style="display: inline-block; padding: 8px 12px; background-color: #FFCDB2; color: white; text-decoration: none; border-radius: 5px;">
                            Download Bukti Pembayaran
                            </a>
     <?php } else { ?>
        <p style="color: red; font-weight: bold;">No proof uploaded</p>
        <form action="transaksi.php" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
            <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
            <input type="file" name="payment_proof_file" required 
                   style="display: block; background-color: #FFCDB2; margin: 5px auto; padding: 5px; border: 1px solid #ccc; border-radius: 5px;">
            <button type="submit" name="upload_payment_proof" 
                    style="background-color: #E5898B; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;">
                Upload Bukti Pembayaran
            </button>
        </form>
    <?php } ?>
</td>
                            <td>
                                <a href="transaksi.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete">Hapus Data</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    <?php } else { ?>
        <p>No transaction history available.</p>
    <?php } ?>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - Pochina Airplane, All Right Reserved.</small>
        </div>
    </footer>

</body>
</html>
