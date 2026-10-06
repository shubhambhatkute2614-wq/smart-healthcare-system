<?php

session_start();

include("config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$query = "SELECT user_id, name, email, mobile, created_at
          FROM users
          ORDER BY user_id DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Users - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .page-title {
            text-align: center;
            margin: 45px 0 30px;
        }

        .page-title h2 {
            font-size: 38px;
            color: #1769e0;
        }

        .users-card {
            background: white;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th {
            background: #1769e0;
            color: white;
            padding: 15px;
            text-align: left;
        }

        .users-table td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }

        .users-table tr:hover {
            background: #f5f9ff;
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 22px;
            border: 2px solid #1769e0;
            border-radius: 8px;
            color: #1769e0;
            text-decoration: none;
            font-weight: bold;
        }

        .back-btn:hover {
            background: #1769e0;
            color: white;
            text-decoration: none;
        }

    </style>

</head>

<body>

<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="container">

    <div class="page-title">

        <h2>👥 Manage Users</h2>

        <p>
            View all registered users
        </p>

    </div>


    <div class="users-card">

        <table class="users-table">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Mobile</th>

                    <th>Registered Date</th>

                </tr>

            </thead>


            <tbody>

                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($user = mysqli_fetch_assoc($result)) {

                ?>

                    <tr>

                        <td>
                            <?php echo $user['user_id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user['mobile']); ?>
                        </td>

                        <td>
                            <?php echo $user['created_at']; ?>
                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="5" style="text-align:center;">

                            No registered users found.

                        </td>

                    </tr>

                <?php

                }

                ?>

            </tbody>

        </table>

    </div>


    <div style="text-align:center;">

        <a href="admin_dashboard.php" class="back-btn">
            ← Back to Admin Dashboard
        </a>

    </div>


</div>


<footer>

    <p>
        © 2026 Smart Healthcare System
    </p>

</footer>


</body>

</html>