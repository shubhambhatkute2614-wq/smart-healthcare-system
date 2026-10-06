<?php

session_start();

include("config/db.php");

/* Admin Login Check */
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


/* Get Doctor ID */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_doctors.php");
    exit();
}

$doctor_id = intval($_GET['id']);


/* Update Doctor */
if (isset($_POST['update_doctor'])) {

    $doctor_name = trim($_POST['doctor_name']);
    $specialization_id = intval($_POST['specialization_id']);
    $hospital_name = trim($_POST['hospital_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $availability = trim($_POST['availability']);

    $sql = "UPDATE doctors SET
                doctor_name = ?,
                specialization_id = ?,
                hospital_name = ?,
                address = ?,
                city = ?,
                mobile = ?,
                email = ?,
                availability = ?
            WHERE doctor_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sissssssi",
        $doctor_name,
        $specialization_id,
        $hospital_name,
        $address,
        $city,
        $mobile,
        $email,
        $availability,
        $doctor_id
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: manage_doctors.php?updated=1");
        exit();

    } else {

        $error = "Doctor update failed.";

    }
}


/* Get Doctor Details */
$sql = "SELECT * FROM doctors WHERE doctor_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $doctor_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    die("Doctor not found.");

}

$doctor = mysqli_fetch_assoc($result);


/* Get Specializations */
$specialization_query =
    "SELECT specialization_id, specialization_name
     FROM specializations
     ORDER BY specialization_name";

$specialization_result =
    mysqli_query($conn, $specialization_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Doctor - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .edit-container {

            width: 90%;
            max-width: 1000px;
            margin: 40px auto;

        }

        .page-title {

            text-align: center;
            color: #1769e0;
            font-size: 40px;
            margin-bottom: 10px;

        }

        .page-subtitle {

            text-align: center;
            color: #555;
            font-size: 20px;
            margin-bottom: 35px;

        }

        .form-card {

            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);

        }

        .form-group {

            margin-bottom: 20px;

        }

        .form-group label {

            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 17px;

        }

        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;
            padding: 13px;
            border: 1px solid #d5dce5;
            border-radius: 8px;
            font-size: 16px;
            font-family: Arial, sans-serif;

        }

        .form-group textarea {

            min-height: 100px;
            resize: vertical;

        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            outline: none;
            border-color: #1769e0;

        }

        .button-area {

            text-align: center;
            margin-top: 30px;

        }

        .update-btn {

            background: #1769e0;
            color: white;
            border: none;
            padding: 13px 30px;
            border-radius: 8px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;

        }

        .update-btn:hover {

            background: #0d55bd;

        }

        .back-btn {

            display: block;
            width: fit-content;
            margin: 25px auto;
            padding: 12px 25px;
            border: 2px solid #1769e0;
            border-radius: 8px;
            color: #1769e0;
            font-weight: bold;

        }

        .back-btn:hover {

            background: #1769e0;
            color: white;
            text-decoration: none;

        }

        .error-message {

            background: #f8d7da;
            color: #842029;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;

        }

    </style>

</head>


<body>


<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="edit-container">


    <h1 class="page-title">
        ✏️ Edit Doctor
    </h1>


    <p class="page-subtitle">
        Update doctor information
    </p>


    <?php if (isset($error)) { ?>

        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php } ?>


    <div class="form-card">


        <form method="POST">


            <!-- Doctor Name -->

            <div class="form-group">

                <label>
                    Doctor Name
                </label>

                <input
                    type="text"
                    name="doctor_name"
                    value="<?php echo htmlspecialchars($doctor['doctor_name']); ?>"
                    required
                >

            </div>


            <!-- Specialization -->

            <div class="form-group">

                <label>
                    Specialization
                </label>

                <select
                    name="specialization_id"
                    required
                >

                    <option value="">
                        -- Select Specialization --
                    </option>


                    <?php

                    while (
                        $specialization =
                        mysqli_fetch_assoc($specialization_result)
                    ) {

                    ?>

                        <option
                            value="<?php echo $specialization['specialization_id']; ?>"
                            <?php

                            if (
                                $specialization['specialization_id']
                                == $doctor['specialization_id']
                            ) {

                                echo "selected";

                            }

                            ?>
                        >

                            <?php
                            echo htmlspecialchars(
                                $specialization['specialization_name']
                            );
                            ?>

                        </option>

                    <?php

                    }

                    ?>

                </select>

            </div>


            <!-- Hospital -->

            <div class="form-group">

                <label>
                    Hospital Name
                </label>

                <input
                    type="text"
                    name="hospital_name"
                    value="<?php echo htmlspecialchars($doctor['hospital_name']); ?>"
                    required
                >

            </div>


            <!-- Address -->

            <div class="form-group">

                <label>
                    Address
                </label>

                <textarea
                    name="address"
                    required
                ><?php echo htmlspecialchars($doctor['address']); ?></textarea>

            </div>


            <!-- City -->

            <div class="form-group">

                <label>
                    City
                </label>

                <input
                    type="text"
                    name="city"
                    value="<?php echo htmlspecialchars($doctor['city']); ?>"
                    required
                >

            </div>


            <!-- Mobile -->

            <div class="form-group">

                <label>
                    Mobile
                </label>

                <input
                    type="text"
                    name="mobile"
                    value="<?php echo htmlspecialchars($doctor['mobile']); ?>"
                    required
                >

            </div>


            <!-- Email -->

            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($doctor['email']); ?>"
                    required
                >

            </div>


            <!-- Availability -->

            <div class="form-group">

                <label>
                    Availability
                </label>

                <input
                    type="text"
                    name="availability"
                    value="<?php echo htmlspecialchars($doctor['availability']); ?>"
                    placeholder="Example: Mon - Sat, 10 AM - 5 PM"
                    required
                >

            </div>


            <!-- Update Button -->

            <div class="button-area">

                <button
                    type="submit"
                    name="update_doctor"
                    class="update-btn"
                >
                    💾 Update Doctor
                </button>

            </div>


        </form>


    </div>


    <a
        href="manage_doctors.php"
        class="back-btn"
    >
        ← Back to Manage Doctors
    </a>


</div>


<footer>

    <p>
        © 2026 Smart Healthcare System
    </p>

</footer>


</body>

</html>