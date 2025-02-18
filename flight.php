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

// Fetch flight data from database
$sql = "SELECT airline, departure_city, destination_city, flight_time, price_reguler, price_vip, img_url FROM flights";
$result = $conn->query($sql);

$flights = [];
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
    <title>Pochina Airplane Dashboard</title>
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

        .card {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card .col {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 23%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card .col:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card .col i {
            font-size: 30px;
            color: #1c2e5a;
            margin-bottom: 15px;
        }

        .card .col h4 {
            font-size: 18px;
            color: #2c3e50;
            font-weight: bold;
        }

        .flight-schedule {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            padding: 30px 20px;
        }

        .flight-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            width: 30%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .flight-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .flight-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
        }

        .flight-info {
            padding: 15px;
        }

        .flight-airline {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .flight-details,
        .flight-price {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
        }

        .buttons-container {
            display: flex;
            gap: 10px;
        }

        .book-button {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            font-weight: bold;
            color: black;
            background-color: #4635B1;
            transition: background-color 0.3s;
        }

        .book-button.reguler {
            background-color: #FB9EC6;
        }

        .book-button.vip {
            background-color: #FCFFC1;
        }

        .book-button:hover {
            background-color: #B771E5;
        }

        footer {
            background-color: #FFE893;
            color: black;
            padding: 20px 0;
            text-align: center;
        }

        footer small {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
                align-items: flex-start;
            }

            header ul {
                flex-direction: column;
                margin-top: 20px;
            }

            .card {
                flex-direction: column;
                align-items: center;
            }

            .card .col {
                width: 100%;
                margin-bottom: 30px;
            }

            .flight-schedule {
                justify-content: center;
                gap: 20px;
            }

            .flight-card {
                width: 90%;
                margin-bottom: 20px;
            }
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
                <li class="active"><a href="flight.php">Flight</a></li>
                <li><a href="transaksi.php">Transaksi</a></li>
                <li><a href="riwayat.php">Riwayat</a></li>
                <li><a href="profile.php" class="profile-logo"><i class="fa fa-user" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </header>

    <div class="flight-schedule">
    <?php foreach ($flights as $flight): ?>
        <div class="flight-card">
            <div class="flight-image" style="background-image: url('<?php echo $flight['img_url']; ?>');"></div>
            <div class="flight-info">
                <h3 class="flight-airline"><?php echo $flight['airline']; ?></h3>
                <p class="flight-details">Tujuan: <?php echo $flight['departure_city']; ?> - <?php echo $flight['destination_city']; ?></p>
                <p class="flight-price">Reguler: <strong>IDR <?php echo number_format($flight['price_reguler'], 0, ',', '.'); ?></strong></p>
                <p class="flight-price">VIP: <strong>IDR <?php echo number_format($flight['price_vip'], 0, ',', '.'); ?></strong></p>
                <p class="flight-details">Flight Time: <?php echo $flight['flight_time']; ?></p>

                <!-- Start Form for Order -->
                <form action="booking.php" method="get">
                    <!-- Hidden Fields with Flight Details -->
                    <input type="hidden" name="airline" value="<?php echo urlencode($flight['airline']); ?>">
                    <input type="hidden" name="departure_city" value="<?php echo urlencode($flight['departure_city']); ?>">
                    <input type="hidden" name="destination_city" value="<?php echo urlencode($flight['destination_city']); ?>">
                    <input type="hidden" name="flight_time" value="<?php echo urlencode($flight['flight_time']); ?>">
                    <input type="hidden" name="price_reguler" value="<?php echo $flight['price_reguler']; ?>">
                    <input type="hidden" name="price_vip" value="<?php echo $flight['price_vip']; ?>">

                    <!-- Order Button -->
                    <button type="submit" class="book-button">Order</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

   <!-- Footer -->
   <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - Pochina Airplane, All Right Reserved.</small>
        </div>
    </footer>

</html>
