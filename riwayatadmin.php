<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'pochina');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle ticket upload
if (isset($_POST['upload_ticket'])) {
    // Get booking ID and the uploaded file
    $booking_id = $_POST['booking_id'];
    $ticket_file = $_FILES['ticket_file'];

    // Ensure the file is valid and uploaded correctly
    if ($ticket_file['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $ticket_file['tmp_name'];
        $file_name = 'uploads/tikets/' . uniqid('ticket_') . '_' . basename($ticket_file['name']);
        
        // Move the file to the 'uploads' directory
        if (move_uploaded_file($file_tmp, $file_name)) {
            // Update the database with the ticket file path
            $update_sql = "UPDATE riwayat SET tiket_pesawat = '$file_name' WHERE id = $booking_id";
            
            if (mysqli_query($conn, $update_sql)) {
                echo "<script>alert('Tiket pesawat berhasil di-upload'); window.location.href='riwayatadmin.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan data tiket pesawat');</script>";
            }
        } else {
            echo "<script>alert('Gagal meng-upload tiket pesawat');</script>";
        }
    } else {
        echo "<script>alert('Error dalam pengunggahan file');</script>";
    }
}

// Handle confirm action
if (isset($_POST['confirm_booking'])) {
    $booking_id = $_POST['booking_id'];
    $update_sql = "UPDATE riwayat SET status = 'success' WHERE id = $booking_id";
    
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Pesanan berhasil dikonfirmasi'); window.location.href='riwayatadmin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengonfirmasi pesanan');</script>";
    }
}

// Handle delete action
if (isset($_POST['delete_booking'])) {
    $booking_id = $_POST['booking_id'];
    $delete_sql = "UPDATE riwayat SET delete_id = 1 WHERE id = $booking_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Pesanan berhasil dihapus'); window.location.href='riwayatadmin.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus pesanan');</script>";
    }
}

// Fetch all bookings from the database
$sql = "SELECT * FROM riwayat WHERE delete_id = 0 ORDER BY flight_time DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
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

        /* Styles for header */
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

        /* Table styles */
        .table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
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
            text-align: center;
            font-weight: bold;
        }

        table td {
            color: #555;
        }

        table td a {
            background-color: #FB9EC6;
            padding: 10px 15px;
            border-radius: 5px;
            color: black;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        table td a:hover {
            background-color: #FFE893;
        }

        /* File input and buttons styling */
        table td input[type="file"] {
            padding: 8px;
            margin-top: 10px;
        }

        table td button[type="submit"] {
            padding: 12px 24px;
            font-size: 15px;
            background-color: #FFB4A2;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }

        table td button[type="submit"]:hover {
            background-color: #FFB4A2;
        }

        /* Action button styling */
        table td form button {
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        table td form button[type="submit"]:hover {
            background-color: #FF9C9C;
        }

        /* Back button styling */
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
        }

        .btn-back:hover {
            background-color: #FFE893;
        }

        /* Footer styles */
        footer {
            padding: 20px 0;
            background-color: #FFE893;
            color: black;
            text-align: center;
            margin-top: 600px;
        }

        footer small {
            font-size: 14px;
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <h1><a href="dashadmin.php">Pochina Airplane</a></h1>
            <ul>
                <li><a href="dashadmin.php">Dashboard</a></li>
                <li><a href="flightadmin.php">Flight</a></li>
                <li class="active"><a href="riwayatadmin.php">Riwayat</a></li>
                <li><a href="profileadmin.php" class="profile-logo"><i class="fa fa-user" aria-hidden="true"></i></a></li>
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
                            <td><?php echo isset($row['status']) ? htmlspecialchars($row['status']) : 'Pending'; ?></td>
                            <td>
                                <?php if (!empty($row['tiket_pesawat'])) { ?>
                                    <a href="<?php echo htmlspecialchars($row['tiket_pesawat']); ?>" target="_blank">Download Tiket</a>
                                <?php } else { ?>
                                    No tiket uploaded
                                <?php } ?>
                                <form action="riwayatadmin.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                    <input type="file" name="ticket_file" required>
                                    <button type="submit" name="upload_ticket">Upload Tiket</button>
                                </form>
                            </td>
                            <td>
                                <form action="riwayatadmin.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="confirm_booking">Confirm</button>
                                </form>
                                <form action="riwayatadmin.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_booking" style="background-color: pink;">Delete</button>
                                </form>
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
        <a href="dashadmin.php" class="btn-back">Back</a>
    </div>

    <footer>
        <small>&copy; 2025 Pochina Airplane, All Rights Reserved.</small>
    </footer>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
