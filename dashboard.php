<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION["user_name"];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Smart Healthcare - Dashboard</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f8fc;
            color: #333;
        }

        /* Navbar */

        .navbar {
            background: linear-gradient(90deg, #1677ff, #13c4e9);
            color: white;
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
        }

        .logout {
            background: white;
            color: #1677ff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .logout:hover {
            background: #eaf3ff;
        }

        /* Main */

        .container {
            width: 85%;
            max-width: 1100px;
            margin: 50px auto;
        }

        .welcome {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome h1 {
            color: #1677ff;
            font-size: 38px;
            margin-bottom: 12px;
        }

        .welcome p {
            font-size: 18px;
            color: #666;
        }

        /* Cards */

        .cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .card h2 {
            color: #1677ff;
            margin-bottom: 12px;
        }

        .card p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            background: #1677ff;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background: #0d5dcc;
        }

        /* Footer */

        footer {
            text-align: center;
            margin-top: 70px;
            padding: 20px;
            background: #17202a;
            color: white;
        }

        /* Mobile */

        @media (max-width: 700px) {

            .navbar {
                padding: 18px;
            }

            .logo {
                font-size: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .container {
                width: 90%;
            }

            .welcome h1 {
                font-size: 30px;
            }
        }

    </style>

</head>

<body>

<header class="dashboard-header">

    <h1>🏥 Smart Healthcare System</h1>

    <a href="logout.php" class="logout-btn">
        🚪 Logout
    </a>

</header>

<div class="container">

    <h2 style="text-align:center; font-size:40px;">
        Welcome, <?php echo $user_name; ?>! 👋
    </h2>

    <p style="text-align:center; font-size:20px; color:#555;">
        Your personal healthcare assistance dashboard
    </p>

    <div class="cards">

        <!-- Disease Finder -->

        <div class="card">

            <div style="font-size:60px;">
                🩺
            </div>

            <h2>Disease Finder</h2>

            <p>
                Select your symptoms and find possible diseases
                with recommended medical specialists.
            </p>

            <br>

            <a href="disease_finder.php">
                <button>Find Disease</button>
            </a>

        </div>


        <!-- Doctor Locator -->

        <div class="card">

            <div style="font-size:60px;">
                👨‍⚕️
            </div>

            <h2>Doctor Locator</h2>

            <p>
                Find doctors and specialists according to your
                city and medical specialization.
            </p>

            <br>

            <a href="doctor_locator.php">
                <button>Find Doctor</button>
            </a>

        </div>

    </div>

</div>


<footer>

    <p>
        © 2026 Smart Healthcare System
    </p>

</footer>

</body>

</html>