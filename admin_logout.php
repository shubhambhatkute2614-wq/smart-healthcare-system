<?php

session_start();

include("config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_username = $_SESSION['admin_username'];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .admin-header {
            background: linear-gradient(90deg, #1769e0, #11c5e8);
            color: white;
            padding: 25px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            margin: 0;
            color: white;
        }

        .logout-btn {
            background: white;
            color: #1769e0;
            padding: 12px 22px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #f0f0f0;
            text-decoration: none;
        }

        .admin-welcome {
            text-align: center;
            margin: 50px 0 35px;
        }

        .admin-welcome h2 {
            font-size: 38px;
            color: #1769e0;
        }

        .admin-welcome p {
            font-size: 18px;
            color: #666;
        }

        .admin-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-top: 30px;
        }

        .admin-card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .admin-card-icon {
            font-size: 55px;
            margin-bottom: 15px;
        }

        .admin-card h3 {
            color: #1769e0;
            font-size: 24px;
        }

        .admin-card p {
            color: #666;
            line-height: 1.6;
        }

        .admin-btn {
            display: inline-block;
            background: #1769e0;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 10px;
        }

        .admin-btn:hover {
            background: #0d55bd;
            color: white;
            text-decoration: none;
        }

        @media (max-width: 900px) {

            .admin-cards {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>


<header class="admin-header">

    <h1>🏥 Smart Healthcare System</h1>

    <a href="admin_logout.php" class="logout-btn">
        🚪 Logout
    </a>

</header>


<div class="container">


    <div class="admin-welcome">

        <h2>
            Welcome, Admin! 👋
        </h2>

        <p>
            Admin Dashboard - Manage Smart Healthcare System
        </p>

    </div>


    <div class="admin-cards">


        <!-- Users -->

        <div class="admin-card">

            <div class="admin-card-icon">
                👥
            </div>

            <h3>Manage Users</h3>

            <p>
                View and manage registered users
                of the healthcare system.
            </p>

            <a href="manage_users.php" class="admin-btn">
    View Users
</a>

        </div>


        <!-- Diseases -->

        <div class="admin-card">

            <div class="admin-card-icon">
                🩺
            </div>

            <h3>Manage Diseases</h3>

            <p>
                Add, update and manage diseases
                available in the system.
            </p>

            <a href="manage_diseases.php" class="admin-btn">
    Manage Diseases
</a>

        </div>


        <!-- Doctors -->

        <div class="admin-card">

            <div class="admin-card-icon">
                👨‍⚕️
            </div>

            <h3>Manage Doctors</h3>

            <p>
                Add and manage doctors and
                their specializations.
            </p>

            <a href="manage_doctors.php" class="admin-btn">
    Manage Doctors
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