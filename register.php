<?php
// Koneksi ke database menggunakan $conn yang sudah ada
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

// Proses register pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan input dari form dan sanitasi input
    $nama_pelanggan = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $NIK = mysqli_real_escape_string($conn, $_POST['NIK']);
    $email = mysqli_real_escape_string($conn, $_POST['email']); 

    // Validasi input
    if (empty($nama_pelanggan) || empty($username) || empty($password) || empty($email)) {
        echo "Semua field harus diisi!";
    } else {
        // Cek apakah username sudah terdaftar
        $checkUsernameQuery = "SELECT * FROM pelanggan WHERE username=?";
        $stmt = mysqli_prepare($conn, $checkUsernameQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            // Menyimpan data pengguna baru ke dalam database
            $insertQuery = "INSERT INTO pelanggan (nama_pelanggan, username, password, NIK, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmt, "sssss", $nama_pelanggan, $username, $password, $NIK, $email);
            if (mysqli_stmt_execute($stmt)) {
                // Redirect ke halaman login setelah sukses
                header("Location: login.php"); // Mengarahkan langsung ke halaman login
                exit(); // Menghentikan script lebih lanjut
            } else {
                echo "Terjadi kesalahan: " . mysqli_error($conn);
            }
        } else {
            // Menampilkan pesan jika username sudah terdaftar
            echo "Username sudah terdaftar, silakan pilih username lain.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    
    <style>
        /* Importing Google font - Open Sans */
        @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Open Sans", sans-serif;
        }

        body {
            height: 100vh;
            width: 100%;
            background: url("https://www.dreamers.id/img_artikel/17maskapai-garudaindonesia.jpg") center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px;
        }

        .form-box {
            width: 50%;
            padding: 20px;
        }

        .form-box img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .form-box .form-container {
            width: 100%;
        }

        .input-field {
            position: relative;
            height: 50px;
            width: 100%;
            margin-top: 20px;
        }

        .input-field input {
            height: 100%;
            width: 100%;
            background: none;
            outline: none;
            font-size: 0.95rem;
            padding: 0 15px;
            border: 1px solid #717171;
            border-radius: 3px;
        }

        .input-field input:focus {
            border: 1px solid #00bcd4;
        }

        .input-field label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #4a4646;
            pointer-events: none;
            transition: 0.2s ease;
        }

        .input-field input:is(:focus, :valid) {
            padding: 16px 15px 0;
        }

        .input-field input:is(:focus, :valid)~label {
            transform: translateY(-120%);
            color: #00bcd4;
            font-size: 0.75rem;
        }

        form button {
            width: 100%;
            color: #fff;
            border: none;
            outline: none;
            padding: 14px 0;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 3px;
            cursor: pointer;
            margin: 25px 0;
            background: #00bcd4;
            transition: 0.2s ease;
        }

        form button:hover {
            background: #0097a7;
        }

        form a {
            color: #00bcd4;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <!-- Left side with the form -->
        <div class="form-box">
            <h2>Register</h2>
            <form method="POST" action="">
                <div class="input-field">
                    <input type="text" id="nama_pelanggan" name="nama_pelanggan" required>
                    <label for="nama_pelanggan">Nama</label>
                </div>

                <div class="input-field">
                    <input type="text" id="username" name="username" required>
                    <label for="username">Username</label>
                </div>

                <div class="input-field">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                </div>

                <div class="input-field">
                    <input type="text" id="NIK" name="NIK">
                    <label for="NIK">NIK</label>
                </div>

                <!-- Menambahkan input email -->
                <div class="input-field">
                    <input type="email" id="email" name="email" required>
                    <label for="email">Email</label>
                </div>

                <button type="submit">Register</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">login disini</a></p>
        </div>

        <!-- Right side with the image -->
        <div class="form-box">
            <img src="https://cdn.medcom.id/dynamic/content/2017/07/19/731606/cover_maskapai_indonesia.jpg?w=1024" alt="Signup Image">
        </div>
    </div>

</body>
</html>
