<?php
// Start the session at the top of the script
session_start();

// Koneksi ke database
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

// Proses login pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password_input = $_POST['password'];

        // Validasi input
        if (empty($username) || empty($password_input)) {
            echo "Semua field harus diisi!";
        } else {
            // Cek apakah username ada di database
            $query = "SELECT id, nama_pelanggan, password, role FROM pelanggan WHERE username=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                echo "Username tidak ditemukan!";
            } else {
                $user = mysqli_fetch_assoc($result);

                // Verifikasi password menggunakan password_verify
                if (password_verify($password_input, $user['password'])) {
                    // Set session atau login sukses
                    $_SESSION['user_id'] = $user['id']; // Menyimpan user_id ke session
                    $_SESSION['username'] = $username;  // Menyimpan username ke session
                    $_SESSION['user_name'] = $user['nama_pelanggan']; // Menyimpan nama_pelanggan ke session
                    $_SESSION['role'] = $user['role']; // Menyimpan role ke session
                    
                    echo "Login berhasil! Selamat datang, " . $user['nama_pelanggan'];

                    // Redirect ke halaman yang sesuai berdasarkan role pengguna
                    if ($user['role'] == 'admin') {
                        header("Location: dashadmin.php"); // Redirect ke dashboard admin
                    } else {
                        header("Location: dashboard.php"); // Redirect ke dashboard pengguna biasa
                    }
                    exit(); // Jangan lupa panggil exit setelah redirect agar tidak ada kode lain yang dieksekusi
                } else {
                    echo "Password salah!";
                }
            }
        }
    } else {
        echo "Username atau password tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pochina Airplane</title>
    <style>
        /* Styling seperti sebelumnya */
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
            background: url("https://cdn.medcom.id/dynamic/content/2017/07/19/731606/cover_maskapai_indonesia.jpg?w=1024") center/cover no-repeat;
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
            max-width: 600px;
        }

        .form-box {
            width: 100%;
            padding: 20px;
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
        <div class="form-box">
            <h2>Login</h2>
            <form method="POST" action="">
                <div class="input-field">
                    <input type="text" id="username" name="username" required>
                    <label for="username">Username</label>
                </div>

                <div class="input-field">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                </div>

                <button type="submit">Login</button>
            </form>
            <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
        </div>
    </div>

</body>
</html>
