<?php
// Mulai session
session_start();

// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'pochina';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses tambah penerbangan baru
if (isset($_POST['add_flight'])) {
    $airline = $_POST['airline'];
    $departure_city = $_POST['departure_city'];
    $destination_city = $_POST['destination_city'];
    $flight_time = $_POST['flight_time'];
    $price_reguler = $_POST['price_reguler'];
    $price_vip = $_POST['price_vip'];
    $img_url = $_POST['img_url'];

    // Konversi format datetime-local ke MySQL DATETIME
    $flight_time = date('Y-m-d H:i:s', strtotime($flight_time));

    // Simpan penerbangan ke database
    $sql = "INSERT INTO flights (airline, departure_city, destination_city, flight_time, price_reguler, price_vip, img_url) 
            VALUES ('$airline', '$departure_city', '$destination_city', '$flight_time', '$price_reguler', '$price_vip', '$img_url')";

    if ($conn->query($sql) === TRUE) {
        echo "Penerbangan berhasil ditambahkan!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Proses edit penerbangan
if (isset($_POST['edit_flight'])) {
    $flight_id = $_POST['flight_id'];
    $airline = $_POST['airline'];
    $departure_city = $_POST['departure_city'];
    $destination_city = $_POST['destination_city'];
    $flight_time = $_POST['flight_time'];
    $price_reguler = $_POST['price_reguler'];
    $price_vip = $_POST['price_vip'];
    $img_url = $_POST['img_url'];

    // Konversi format datetime-local ke MySQL DATETIME
    $flight_time = date('Y-m-d H:i:s', strtotime($flight_time));

    // Update database
    $sql = "UPDATE flights SET airline='$airline', departure_city='$departure_city', destination_city='$destination_city', 
            flight_time='$flight_time', price_reguler='$price_reguler', price_vip='$price_vip', img_url='$img_url' 
            WHERE id='$flight_id'";
}

// Proses hapus penerbangan
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM flights WHERE id = '$delete_id'";
}

// Ambil data penerbangan untuk form edit
if (isset($_GET['edit'])) {
    $flight_id = $_GET['edit'];
    $sql = "SELECT * FROM flights WHERE id = '$flight_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $flight = $result->fetch_assoc();
    }
}

// Ambil daftar penerbangan
$flights = [];
$sql = "SELECT * FROM flights";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pochina Airplane Admin - Flight Management</title>
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

header {
    background-color: #FBB4A5;
    padding: 20px 0;
    position: fixed;  /* Membuat header tetap di atas saat scroll */
    top: 0;  /* Pastikan menempel di atas */
    left: 0;
    width: 100%;  /* Lebar penuh */
    z-index: 1000;  /* Pastikan di atas elemen lain */
}

body {
    margin: 0; /* Hilangkan margin agar tidak ada celah di atas */
    padding-top: 70px; /* Beri ruang agar konten tidak tertutup header */
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
    overflow-x: auto; /* Horizontal scrollbar */
    overflow-y: auto; /* Vertical scrollbar */
    max-height: 500px; /* Set a height limit for vertical scrolling */
    margin-top: 20px;
}

/* Table Styles */
table {
    width: 100%;
    min-width: 800px; /* Ensures table has a minimum width to trigger horizontal scrolling */
    border-collapse: collapse;
}

/* Table Headers and Data Cells */
table th,
table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

/* Table Headers Styling */
table th {
    background-color: #FBB4A5;
    color: black;
    font-weight: bold;
}

/* Table Data Cells Styling */
table td {
    color: #555;
}

/* Optional: You can add some padding and hover effect for cells */
table td a {
    color: black;
    text-decoration: none;
    background-color: #FB9EC6;
    padding: 8px 14px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

table td a:hover {
    background-color: #FFE893;
}

/* Input Fields Styling */
input[type="text"], input[type="number"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

/* Focus effect for input fields */
input[type="text"]:focus, input[type="number"]:focus {
    border-color: #FBB4A5;
    outline: none;
}

/* Button Styling */
button {
    padding: 12px 24px;
    font-size: 18px;
    border-radius: 5px;
    font-weight: bold;
    color: black;
    background-color: #FBB4A5;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Button Hover Effect */
button:hover {
    background-color: #FFE893;
}

/* Styling for Add and Edit Form Titles */
h1 {
    margin-top: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    text-align: center;
}

h2 {
    margin-top: 20px;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    text-align: center;
}

/* Styling the form container */
form {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
}

/* Optional: Add spacing between form fields */
form input {
    margin-bottom: 15px;
}

/* Optional: Add text for the form's placeholder to match the theme */
input::placeholder {
    color: #999;
    font-style: italic;
}

.no-records {
    text-align: center;
    font-size: 18px;
    color: #777;
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

footer {
    padding: 20px 0;
    background-color: #FFE893;
    color: black;
    text-align: center;
    margin-top: 200px; 
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
            <h1><a href="dashadmin.php">Pochina Airplane</a></h1>
            <ul>
                <li><a href="dashadmin.php">Dashboard</a></li>
                <li class="active"><a href="flightadmin.php">Flight</a></li>
                <li><a href="riwayatadmin.php">Riwayat</a></li>
                <li><a href="profileadmin.php" class="profile-logo"><i class="fa fa-user" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </header>

    <h1>Flight Management</h1>

    <!-- Form Tambah/Edit Penerbangan -->
    <h2><?php echo isset($flight) ? 'Edit Penerbangan' : 'Tambah Penerbangan Baru'; ?></h2>
    <form method="POST">
        <?php if (isset($flight)): ?>
            <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
        <?php endif; ?>
        <input type="text" name="airline" placeholder="Airline" value="<?php echo isset($flight) ? $flight['airline'] : ''; ?>" required><br>
        <input type="text" name="departure_city" placeholder="Departure City" value="<?php echo isset($flight) ? $flight['departure_city'] : ''; ?>" required><br>
        <input type="text" name="destination_city" placeholder="Destination City" value="<?php echo isset($flight) ? $flight['destination_city'] : ''; ?>" required><br>
        
        <!-- Input DATETIME -->
        <input type="datetime-local" name="flight_time" value="<?php echo isset($flight) ? date('Y-m-d\TH:i', strtotime($flight['flight_time'])) : ''; ?>" required><br>

        <input type="text" name="img_url" placeholder="Image URL" value="<?php echo isset($flight) ? $flight['img_url'] : ''; ?>" required><br>
        <input type="number" name="price_reguler" placeholder="Price Reguler (IDR)" value="<?php echo isset($flight) ? $flight['price_reguler'] : ''; ?>" required><br>
        <input type="number" name="price_vip" placeholder="Price VIP (IDR)" value="<?php echo isset($flight) ? $flight['price_vip'] : ''; ?>" required><br>
        <button type="submit" name="<?php echo isset($flight) ? 'edit_flight' : 'add_flight'; ?>">
            <?php echo isset($flight) ? 'Update Flight' : 'Add Flight'; ?>
        </button>
    </form>

    <!-- Daftar Penerbangan -->
    <h2>Daftar Penerbangan</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Airline</th>
                <th>Departure City</th>
                <th>Destination City</th>
                <th>Flight Time</th>
                <th>Image</th>
                <th>Price Reguler</th>
                <th>Price VIP</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($flights as $flight): ?>
                <tr>
                    <td><?php echo $flight['airline']; ?></td>
                    <td><?php echo $flight['departure_city']; ?></td>
                    <td><?php echo $flight['destination_city']; ?></td>
                    <td><?php echo $flight['flight_time']; ?></td>
                    <td><img src="<?php echo $flight['img_url']; ?>" width="100"></td>
                    <td><?php echo $flight['price_reguler']; ?></td>
                    <td><?php echo $flight['price_vip']; ?></td>
                    <td>
                        <a href="flightadmin.php?edit=<?php echo $flight['id']; ?>">Edit</a> |
                        <a href="flightadmin.php?delete_id=<?php echo $flight['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

     <!-- Footer -->
     <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - Pochina Airplane, All Right Reserved.</small>
        </div>
    </footer>

</body>
</html>
