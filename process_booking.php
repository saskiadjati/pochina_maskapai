<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'pochina');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$nik = $_POST['nik'];
$airline = $_POST['airline'];
$departure_city = $_POST['asal'];
$destination_city = $_POST['tujuan'];
$flight_time = $_POST['waktu']; // In 'YYYY-MM-DD HH:MM' format
$visa = $_POST['visa'];
$ticket_type = $_POST['flight_type'];
$total_price = $_POST['total_price'];
$seat_number = $_POST['seat_number'];
$payment_method = $_POST['payment_method'];
$account_number = $_POST['account_number'];

// Insert data into the database
$sql = "INSERT INTO transaksi (name, username, email, airline, departure_city, destination_city, flight_time, visa, ticket_type, total_price, seat_number, payment_method, account_number)
        VALUES ('$name', '$username', '$email', '$airline', '$departure_city', '$destination_city', '$flight_time', '$visa', '$ticket_type', '$total_price', '$seat_number', '$payment_method', '$account_number')";

if (mysqli_query($conn, $sql)) {
    // Redirect to riwayat.php after successful insertion
    header("Location: transaksi.php");
    exit;
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
