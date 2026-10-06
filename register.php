<?php

session_start();

include("config/db.php");

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $mobile = trim($_POST['mobile']);

    $check_sql = "SELECT * FROM users WHERE email = ?";

    $check_stmt = mysqli_prepare($conn, $check_sql);

    mysqli_stmt_bind_param(
        $check_stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($check_stmt);

    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {

        $error = "Email already registered.";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO users
                (name, email, password, mobile)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $name,
            $email,
            $hashed_password,
            $mobile
        );

        if (mysqli_stmt_execute($stmt)) {

            $success = "Registration successful! Please login.";

        } else {

            $error = "Registration failed. Please try again.";

        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .auth-container {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
        }

        .register-title {
            text-align: center;
            color: #1769e0;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .register-subtitle {
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

        .register-button {
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

        .success-message {
            background: #e3f7e9;
            color: #198754;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-link {
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

        <div class="register-card">

            <h1 class="register-title">
                📝 Create Account
            </h1>

            <p class="register-subtitle">
                Register to use Smart Healthcare System
            </p>


            <?php if ($error != "") { ?>

                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php } ?>


            <?php if ($success != "") { ?>

                <div class="success-message">

                    <?php echo htmlspecialchars($success); ?>

                    <br><br>

                    <a href="login.php">
                        Go to Login
                    </a>

                </div>

            <?php } ?>


            <form method="POST">

                <div class="form-group">

                    <label>Name</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter your full name"
                        required
                    >

                </div>


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
                        placeholder="Create a password"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Mobile</label>

                    <input
                        type="text"
                        name="mobile"
                        placeholder="Enter your mobile number"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="register"
                    class="register-button"
                >
                    📝 Register
                </button>

            </form>


            <div class="login-link">

                <p>
                    Already have an account?

                    <a href="login.php">
                        Login
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