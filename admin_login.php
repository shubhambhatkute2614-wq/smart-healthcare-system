<?php

session_start();

include("config/db.php");

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $username
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];

            header("Location: admin_dashboard.php");
            exit();

        } else {

            $error = "Invalid username or password.";

        }

    } else {

        $error = "Invalid username or password.";

    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .admin-login-container {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .admin-login-card {
            background: white;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
        }

        .admin-title {
            text-align: center;
            color: #1769e0;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .admin-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .admin-login-button {
            width: 100%;
            margin-top: 10px;
        }

        .error-message {
            background: #ffe5e5;
            color: #b00020;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .user-login-link {
            text-align: center;
            margin-top: 25px;
        }

    </style>

</head>

<body>

<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="container">

    <div class="admin-login-container">

        <div class="admin-login-card">

            <h1 class="admin-title">
                🔐 Admin Login
            </h1>

            <p class="admin-subtitle">
                Login to manage Smart Healthcare System
            </p>


            <?php if ($error != "") { ?>

                <div class="error-message">

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <form method="POST">

                <div class="form-group">

                    <label>Admin Username</label>

                    <input
                        type="text"
                        name="username"
                        placeholder="Enter admin username"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter admin password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="login"
                    class="admin-login-button"
                >
                    🔐 Admin Login
                </button>

            </form>


            <div class="user-login-link">

                <p>
                    Are you a user?

                    <a href="login.php">
                        User Login
                    </a>
                </p>

            </div>

        </div>

    </div>

</div>


<footer>

    <p>© 2026 Smart Healthcare System</p>

</footer>


</body>

</html>