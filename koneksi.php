<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pochina";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

// Jangan lupa untuk menutup koneksi setelah selesai
// mysqli_close($conn);
?>
