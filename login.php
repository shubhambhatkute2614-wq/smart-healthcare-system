<?php

session_start();

include("config/db.php");

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: dashboard.php");
            exit();

        } else {

            $error = "Invalid email or password.";

        }

    } else {

        $error = "Invalid email or password.";

    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .auth-container {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
        }

        .login-title {
            text-align: center;
            color: #1769e0;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .login-subtitle {
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

        .login-button {
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

        .register-link {
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

    <div class="auth-container">

        <div class="login-card">

            <h1 class="login-title">
                🔐 Login
            </h1>

            <p class="login-subtitle">
                Login to access your healthcare dashboard
            </p>


            <?php if ($error != "") { ?>

                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php } ?>


            <form method="POST">

                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="login"
                    class="login-button"
                >
                    🔐 Login
                </button>

            </form>


            <div class="register-link">

                <p>
                    Don't have an account?
                    <a href="register.php">
                        Create Account
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