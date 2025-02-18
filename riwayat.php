<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'pochina');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Assuming user role is saved in session
$user_role = $_SESSION['role'] ?? '';

// Handle soft delete of a booking (set delete_id to 1)
if (isset($_GET['delete_id'])) {
    // Ensure $delete_id is an integer to prevent SQL injection
    $delete_id = (int)$_GET['delete_id'];
    
    // Check if the ID is valid (greater than 0)
    if ($delete_id > 0) {
        $delete_sql = "UPDATE riwayat SET delete_id = 1 WHERE id = $delete_id";
        
        if (mysqli_query($conn, $delete_sql)) {
            echo "<script>alert('Data berhasil disembunyikan'); window.location.href='riwayat.php';</script>";
        } else {
            echo "<script>alert('Gagal menyembunyikan data'); window.location.href='riwayat.php';</script>";
        }
    } else {
        echo "<script>alert('ID tidak valid'); window.location.href='riwayat.php';</script>";
    }
}

// Delay order and change status to "Success"
if (isset($_GET['delay_id'])) {
    $delay_id = (int)$_GET['delay_id'];
    if ($delay_id > 0) {
        // Update order status to "success" after delay
        $update_sql = "UPDATE riwayat SET status = 'success' WHERE id = $delay_id";
        if (mysqli_query($conn, $update_sql)) {
            // Send email to user about status change
            $select_sql = "SELECT email, nama_pelanggan FROM riwayat WHERE id = $delay_id";
            $result = mysqli_query($conn, $select_sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $to = $row['email'];
                $subject = "Status Pemesanan Anda Diperbarui";
                $message = "Halo " . $row['nama_pelanggan'] . ",\n\nPesanan Anda yang sebelumnya ditunda telah berhasil diproses. Pesanan Anda sekarang berhasil!\n\nTerima kasih telah menggunakan layanan Pochina Airlines! Silakan periksa email Anda untuk detail lebih lanjut.";
                $headers = "From: no-reply@pochina-airlines.com";
                
                // Send the email
                mail($to, $subject, $message, $headers);
            }

            echo "<script>alert('Status berhasil diperbarui ke Success.'); window.location.href='riwayat.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status'); window.location.href='riwayat.php';</script>";
        }
    }
}

// Fetch booking history from the database (exclude rows marked as deleted)
$sql = "SELECT * FROM riwayat WHERE delete_id = 0 ORDER BY flight_time DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Pochina Airlines</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        .medsos {
            background-color: #FFE893;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            padding: 35px;
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
            padding: 30px 33px;
        }

        table td a {
            color: black;
            text-decoration: none;
            background-color: #FB9EC6;
            padding: 14px 14px;
            border-radius: 5px;
            transition: background-color 0.3s;
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
            margin-top: 550px;
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

    <header>
        <div class="container">
            <h1><a href="dashboard.php">Pochina Airplane</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="flight.php">Flight</a></li>
                <li><a href="transaksi.php">Transaksi</a></li>
                <li class="active"><a href="riwayat.php">Riwayat</a></li>
                <li><a href="profile.php" class="profile-logo"><i class="fa fa-user" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </header>

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
                        <th>Status</th>
                        <th>Tiket</th>
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
                            <td><?php echo isset($row['status']) ? htmlspecialchars($row['status']) : 'Pending'; ?></td>
                            <td>
                                <?php if (!empty($row['tiket_pesawat'])) { ?>
                                    <a href="<?php echo htmlspecialchars($row['tiket_pesawat']); ?>" target="_blank">Tiket</a>
                                <?php } else { ?>
                                    No tiket uploaded
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p>Tidak ada data pemesanan.</p>
    <?php } ?>
</div>

    <div class="back-button-container">
        <a href="dashboard.php" class="btn-back">Back</a>
    </div>

    <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - Pochina Airplane, All Right Reserved.</small>
        </div>
    </footer>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
