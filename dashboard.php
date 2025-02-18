<?php
// Start the session (if you still want to use the session for other purposes)
session_start();

// Assuming you're not checking for user login, the user session part is removed
// If you want to display a greeting, you can display a generic message or check if a user is logged in
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
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

/* Header */
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

/* Banner */
.banner {
    width: 100%;
    height: 60vh;
    background-image: url(https://static.promediateknologi.id/crop/0x0:0x0/0x0/webp/photo/krjogja/news/2023/03/15/497440/-indonesia-punya-32-bandara-internasional-cek-selengkapnya-disini-230315l.jpg);
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.banner::after {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.4);
}
.banner h2 {
    position: relative;
    color: white;
    font-size: 36px;
    font-weight: bold;
    letter-spacing: 2px;
    padding: 20px 30px;
    border: 3px solid white;
    background-color: rgba(0, 0, 0, 0.5);
}

/* Dashboard Content */
.dashboard {
    padding: 30px;
    background-color: #f5f5f5;
}
.dashboard h3 {
    font-size: 30px;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 20px;
}

.card {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
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

section {
    padding: 50px 15px;
    background-color: #f5f5f5;
}

section h3 {
    font-size: 30px;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 20px;
}

.about p {
    color: #555;
    font-size: 16px;
    line-height: 1.6;
    text-align: justify;
    margin: 0 auto;
    max-width: 800px;
}

/* Footer */
footer {
    background-color: #FFE893;
    color: black;
    padding: 20px 0;
    text-align: center;
}
footer small {
    font-size: 14px;
}

/* Responsive Design */
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
        width: 80%;
        margin-bottom: 30px;
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
                <li class="active"><a href="dashboard.php">Dashboard</a></li>
                <li><a href="flight.php">Flight</a></li>
                <li><a href="transaksi.php">Transaksi</a></li>
                <li><a href="riwayat.php">Riwayat</a></li>
                <li><a href="profile.php" class="profile-logo"><i class="fa fa-user" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </header>

    <!-- Banner Section -->
    <section class="banner">
        <h2>POCHINA AIRPLANE</h2> <!-- Default greeting for guests -->
    </section>

    <!-- about -->
    <section class="about">
        <div class="container">
            <h3>About</h3>
            <p><strong>Pochina Airplane</strong> is an maskapai who's provides best flight services for passengers. 
                They lease or own aircraft to provide such services and may form partnerships or alliances with other airlines for mutual benefit.</p>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="dashboard">
        <div class="container">
            <h3>Services</h3>
            <div class="card">
                <div class="col">
                    <div class="icon"><i class="fa-solid fa-plane"></i></div>
                    <h4>Save Flight</h4>
                </div>
                <div class="col">
                    <div class="icon"><i class="fa-solid fa-ticket"></i></div>
                    <h4>Cost Ticket</h4>
                </div>
                <div class="col">
                    <div class="icon"><i class="fa-solid fa-users"></i></div>
                    <h4>Customer Priority</h4>
                </div>
                <div class="col">
                    <div class="icon"><i class="fa fa-line-chart" aria-hidden="true"></i></div>
                    <h4>Quality Upgrade</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - Pochina Airplane, All Right Reserved.</small>
        </div>
    </footer>

</body>
</html>
