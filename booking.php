<?php
// Start the session
session_start();

// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'pochina';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Ambil data pengguna berdasarkan user_id
$sql = "SELECT nama_pelanggan, username, password, email, NIK FROM pelanggan WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_name = $row['nama_pelanggan'];
    $user_username = $row['username'];
    $user_password = $row['password']; // Password dalam bentuk hash
    $user_email = $row['email'];
    $user_nik = $row['NIK'];
} else {
    // Jika tidak ditemukan, kosongkan variabel
    $user_name = '';
    $user_username = '';
    $user_password = '';
    $user_email = '';
    $user_nik = '';
}

// Ambil data penerbangan berdasarkan parameter yang ada di URL
$airline = isset($_GET['airline']) ? urldecode($_GET['airline']) : ''; // Decoding the airline name
$departure_city = isset($_GET['departure_city']) ? $_GET['departure_city'] : "Jakarta";
$destination_city = isset($_GET['destination_city']) ? $_GET['destination_city'] : "Bali";
$ticket_type = isset($_GET['ticket_type']) ? $_GET['ticket_type'] : "reguler";

// Decode dan format waktu penerbangan
$flight_time = isset($_GET['flight_time']) ? urldecode($_GET['flight_time']) : '2025-02-21 14:00:00';
$flight_time = new DateTime($flight_time); // Convert to DateTime object
$formatted_flight_time = $flight_time->format('Y-m-d H:i:s'); // Format to desired format

// Ambil harga dan informasi maskapai berdasarkan penerbangan
$sql_flight = "SELECT * FROM flights WHERE airline = '$airline' AND departure_city = '$departure_city' AND destination_city = '$destination_city' AND flight_time = '$formatted_flight_time'";
$result_flight = mysqli_query($conn, $sql_flight);

if ($result_flight && mysqli_num_rows($result_flight) > 0) {
    $flight = mysqli_fetch_assoc($result_flight);
    $maskapai = $flight['airline'];
    $departure_city = $flight['departure_city'];
    $destination_city = $flight['destination_city'];
    $flight_time = $flight['flight_time'];
    $price_reguler = $flight['price_reguler'];
    $price_vip = $flight['price_vip'];

    // Menentukan harga berdasarkan jenis tiket
    $price = ($ticket_type == 'vip') ? $price_vip : $price_reguler;
} else {
    // Default harga jika data penerbangan tidak ditemukan
    $price = 1000000;
}

// Tutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Pochina Airplane</title>
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

        /* Sosial Media */
        .medsos {
            background-color: #FBB4A5;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .medsos ul {
            display: flex;
            justify-content: left;
            gap: 30px;
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

        body {
            background-color: #f5f5f5;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #FB9EC6;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #8B0000;
            text-align: left;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .save-button {
            background: linear-gradient(135deg, #FBB4A5, #FFE893);
            color: black;
            padding: 15px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            width: 100%;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .save-button:hover {
            background: linear-gradient(135deg, #FFE893, #FBB4A5);
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .save-button:active {
            transform: scale(1);
        }

        footer {
            background-color: #FBB4A5;
            color: black;
            padding: 20px 0;
            text-align: center;
            margin-top: 100px;
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
            <li><a href="flight.php" class="back-logo"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></li>
        </ul>
    </div>
</div>

<h1>Booking Form - Pochina Airplane</h1>

<div class="form-container">
    <form action="process_booking.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nama Lengkap:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user_name); ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user_username); ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="nik">NIK:</label>
            <input type="text" name="nik" value="<?php echo htmlspecialchars($user_nik); ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="airline">Maskapai:</label>
            <input type="text" name="airline" value="<?php echo htmlspecialchars($airline); ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="asal">Kota Asal:</label>
            <input type="text" name="asal" value="<?php echo $departure_city; ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="tujuan">Kota Tujuan:</label>
            <input type="text" name="tujuan" value="<?php echo $destination_city; ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="waktu">Jam Penerbangan:</label>
            <input type="text" name="waktu" value="<?php echo $formatted_flight_time; ?>" readonly required>
        </div>

        <div class="form-group">
            <label for="visa">Nomor Visa:</label>
            <input type="text" name="visa" required>
        </div>

        <div class="form-group">
            <label for="flight_type">Jenis Tiket:</label>
            <select name="flight_type" id="flight_type" required>
                <option value="reguler" <?php echo ($ticket_type == 'reguler') ? 'selected' : ''; ?>>Reguler</option>
                <option value="vip" <?php echo ($ticket_type == 'vip') ? 'selected' : ''; ?>>VIP</option>
            </select>
        </div>

        <div class="form-group">
            <label for="total-price">Total Harga:</label>
            <input id="total-price" type="text" value="IDR <?php echo number_format($price, 0, ',', '.'); ?>" readonly />
            <input type="hidden" name="total_price" id="total-price-hidden" value="<?php echo $price; ?>" />
        </div>

        <div class="form-group">
            <label for="seat_number">Pilih Kursi:</label>
            <select name="seat_number" required>
                <option value="A1">A1</option>
                <option value="A2">A2</option>
                <option value="B1">B1</option>
                <option value="B2">B2</option>
            </select>
        </div>

        <!-- Payment Method -->
        <div class="form-group">
            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="BCA">BCA</option>
                <option value="BNI">BNI</option>
                <option value="BRI">BRI</option>
            </select>
        </div>

        <div class="form-group" id="account_number_group" style="display: none;">
            <label for="account_number">Account Number:</label>
            <input type="text" name="account_number" id="account_number" readonly required>
        </div>

        <button type="submit" id="save-button" class="save-button">
                Order
        </button>
    </form>

    <!-- footer -->
    <footer>
        <div class="container">
            <small> Copyright &copy; 2025 - Pochina Airplane, All Right Reserved.</small>
        </div>
    </footer>
</div>

<script>
    
        // Get the flight type dropdown and price elements
        const flightTypeSelect = document.getElementById('flight_type');
    const totalPriceField = document.getElementById('total-price');
    const totalPriceHidden = document.getElementById('total-price-hidden');
    
    // Initial prices
    const priceReguler = <?php echo $price_reguler; ?>;
    const priceVip = <?php echo $price_vip; ?>;
    
    // Function to update the price based on the selected flight type
    function updatePrice() {
        const selectedType = flightTypeSelect.value;
        
        // Set price based on selected flight type
        let price = selectedType === 'vip' ? priceVip : priceReguler;
        
        // Update the displayed price
        totalPriceField.value = "IDR " + price.toLocaleString();
        
        // Update the hidden input value for form submission
        totalPriceHidden.value = price;
    }

    // Add event listener to update price when flight type changes
    flightTypeSelect.addEventListener('change', updatePrice);

    // Call updatePrice on page load to set the initial price based on the selected type
    updatePrice();

    // Get the payment method select and account number input elements
    const paymentMethodSelect = document.getElementById('payment_method');
    const accountNumberGroup = document.getElementById('account_number_group');
    const accountNumberInput = document.getElementById('account_number');

    // Function to update the account number field based on the payment method
    function updateAccountNumber() {
        const selectedMethod = paymentMethodSelect.value;

        // Show the account number field if a payment method is selected
        if (selectedMethod) {
            accountNumberGroup.style.display = 'block';
            
            // Set the account number based on the selected payment method
            switch (selectedMethod) {
                case 'BCA':
                    accountNumberInput.value = '5665056781'; // BCA account number
                    break;
                case 'BNI':
                    accountNumberInput.value = '1234567890'; // BNI account number
                    break;
                case 'BRI':
                    accountNumberInput.value = '0987654321'; // BRI account number
                    break;
                default:
                    accountNumberInput.value = ''; // Clear the account number if no method selected
            }
        } else {
            // Hide the account number field if no payment method is selected
            accountNumberGroup.style.display = 'none';
        }
    }

    // Add an event listener to the payment method select dropdown
    paymentMethodSelect.addEventListener('change', updateAccountNumber);
</script>

</body>
</html>
