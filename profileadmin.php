<?php
// Memulai sesi untuk memeriksa login
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pochina";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Ambil data pengguna dari database
$username = $_SESSION['username'];
$query = "SELECT * FROM pelanggan WHERE username = '$username'";
$result = mysqli_query($conn, $query);

// Cek apakah data ditemukan
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Data pengguna tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Pochina Airplane</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+ua7Kw1TIq0f6QmH1rtxBzDZoD7zcwWfy7S98Ikl4FfwY0bTt5qU3oaP7y" crossorigin="anonymous">
    <style>


       /* Custom CSS */
$font-color: #404040;
$dp-size: 8rem;
$padding-size: 2rem;
$opener-size: 2.5rem;
$dot-size: 0.4rem;

html, body {
    height: 100%; /* Ensures full height of the page */
    margin: 0;
    padding: 0;
}

body {
    font-size: 16px;
    color: $font-color;
    font-family: Montserrat, sans-serif;
    background-image: linear-gradient(to bottom right, #ff9eaa 0% 65%, #e860ff 95% 100%);
    background-position: center;
    background-attachment: fixed;
    display: grid;
    place-items: center; /* Centers content vertically and horizontally */
    box-sizing: border-box;
}

.card {
    background-color: white;
    max-width: 360px;
    width: 100%; /* Makes the card responsive */
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border-radius: 2rem;
    box-shadow: 0px 1rem 1.5rem rgba(0, 0, 0, 0.5);
    margin: 2rem; /* Add space around the card */
    padding: 1.5rem; /* Add padding inside the card */
}

.banner {
    background-image: url(https://th.bing.com/th/id/R.56cf41ad23493c4e5206ed6d8c9935d6?rik=lSGLlrdp%2fAOr7Q&riu=http%3a%2f%2fbandara.id%2fwp-content%2fuploads%2f2015%2f04%2fmaskapai-garuda-indonesia.jpg&ehk=KK4icsbSeY0OXyarNgh8YSrRIWknyRf4cHU9YL6p%2fFw%3d&risl=&pid=ImgRaw&r=0);
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 11rem;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    box-sizing: border-box;
}

.menu {
    width: 100%;
    height: 5.5rem;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    position: relative;
    box-sizing: border-box;
    .opener {
        width: $opener-size;
        height: $opener-size;
        position: relative;
        border-radius: 50%;
        transition: background-color 100ms ease-in-out;
        &:hover {
            background-color: #f2f2f2;
        }
        span {
            background-color: $font-color;
            width: $dot-size;
            height: $dot-size;
            position: absolute;
            top: 0;
            left: calc(50% - #{$dot-size / 2});
            border-radius: 50%;
            &:nth-child(1) {
                top: 0.45rem;
            }
            &:nth-child(2) {
                top: 1.05rem;
            }
            &:nth-child(3) {
                top: 1.65rem;
            }
        }
    }
}

.profile-header h4 {
    color: #682773;
    text-align: center;
    padding: 0 $padding-size;
    margin-bottom: 1rem; 
    margin-top: 0rem
}

.labels {
    font-size: 16px;
    font-weight: bold;
    color: #8B0000;
    text-transform: uppercase;
    margin-bottom: 10px;
    display: block;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    margin-bottom: 1rem; 
}

.form-control:focus {
    border-color: rgb(99, 39, 120);
    box-shadow: 0 0 5px rgba(99, 39, 120, 0.5);
}

.profile-logout-button {
    background-color: rgb(99, 39, 120);
    color: white;
    border: none;
    padding: 12px 25px;
    font-size: 18px;
    border-radius: 25px;
    text-align: center;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(99, 39, 120, 0.2); 
    margin-left: 150px
}

.profile-logout-button:hover {
    background-color: #682773; 
    cursor: pointer;
    transform: translateY(-3px); 
    box-shadow: 0 6px 12px rgba(99, 39, 120, 0.3); 
}

.profile-logout-button:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(99, 39, 120, 0.5); 
}

.profile-button {
    background-color: rgb(99, 39, 120);
    color: white;
    border: none;
    padding: 12px 25px;
    font-size: 18px;
    border-radius: 25px;
    text-align: center;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(99, 39, 120, 0.2); 
    margin-top: 20px;
    margin-bottom: 10px; 
}

.profile-button:hover {
    background-color: #682773; 
    cursor: pointer;
    transform: translateY(-3px); 
    box-shadow: 0 6px 12px rgba(99, 39, 120, 0.3); 
}

.profile-button:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(99, 39, 120, 0.5); 
}

.profile-image-container {
    position: relative;
}

.profile-image-container img {
    border-radius: 50%;
    border: 3px solid #682773;
    transition: transform 0.3s ease;
}

.profile-image-container:hover img {
    transform: scale(1.1);
}

.profile-details {
    margin-left: 2rem;
}

.profile-row {
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

    </style>
</head>
<body>
    <div class="card">
        <div class="banner">
            <svg>...</svg> <!-- Icon placeholder or any content inside the banner -->
        </div>

        <div class="menu">
            <div class="opener">
                <span></span><span></span><span></span>
            </div>
        </div>

        <div class="profile-header">
            <h4>Profile Settings</h4>
        </div>

        <div class="profile-details">
            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="labels">Name</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['nama_pelanggan']); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="labels">NIK</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['NIK']); ?>" readonly>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="labels">Username</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                </div>
            </div>

            <div class="mt-5 text-center">
                <a href="dashboard.php" class="btn profile-button" type="button">Back</a>
            </div>
            <div class="mt-5 text-center">
                <a href="login.php" class="btn profile-logout-button" type="button">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
