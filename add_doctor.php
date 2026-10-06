<?php

session_start();

include("config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['add_doctor'])) {

    $doctor_name = trim($_POST['doctor_name']);
    $specialization_id = intval($_POST['specialization_id']);
    $hospital_name = trim($_POST['hospital_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $availability = trim($_POST['availability']);

    $sql = "INSERT INTO doctors
            (doctor_name, specialization_id, hospital_name, address,
             city, mobile, email, availability)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sissssss",
        $doctor_name,
        $specialization_id,
        $hospital_name,
        $address,
        $city,
        $mobile,
        $email,
        $availability
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: manage_doctors.php?added=1");
        exit();

    } else {

        $error = "Doctor could not be added.";

    }
}


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

    <title>Add Doctor - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .form-container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
        }

        .form-card {
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
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
            font-size: 18px;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        .add-btn {
            width: 100%;
            padding: 13px;
            background: #1769e0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .add-btn:hover {
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
            text-decoration: none;
        }

        .back-btn:hover {
            background: #1769e0;
            color: white;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

    </style>

</head>

<body>

<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="form-container">

    <h1 class="page-title">
        👨‍⚕️ Add Doctor
    </h1>

    <p class="page-subtitle">
        Add a new doctor to the healthcare system
    </p>


    <div class="form-card">

        <?php if (isset($error)) { ?>

            <div class="error">
                <?php echo $error; ?>
            </div>

        <?php } ?>


        <form method="POST">


            <div class="form-group">

                <label>Doctor Name</label>

                <input
                    type="text"
                    name="doctor_name"
                    placeholder="Enter doctor name"
                    required
                >

            </div>


            <div class="form-group">

                <label>Specialization</label>

                <select name="specialization_id" required>

                    <option value="">
                        Select Specialization
                    </option>

                    <?php

                    while ($row = mysqli_fetch_assoc($specialization_result)) {

                    ?>

                        <option value="<?php echo $row['specialization_id']; ?>">

                            <?php echo htmlspecialchars($row['specialization_name']); ?>

                        </option>

                    <?php

                    }

                    ?>

                </select>

            </div>


            <div class="form-group">

                <label>Hospital Name</label>

                <input
                    type="text"
                    name="hospital_name"
                    placeholder="Enter hospital name"
                    required
                >

            </div>


            <div class="form-group">

                <label>Address</label>

                <textarea
                    name="address"
                    rows="3"
                    placeholder="Enter address"
                    required
                ></textarea>

            </div>


            <div class="form-group">

                <label>City</label>

                <input
                    type="text"
                    name="city"
                    placeholder="Enter city"
                    required
                >

            </div>


            <div class="form-group">

                <label>Mobile</label>

                <input
                    type="text"
                    name="mobile"
                    placeholder="Enter mobile number"
                    required
                >

            </div>


            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter email"
                    required
                >

            </div>


            <div class="form-group">

                <label>Availability</label>

                <input
                    type="text"
                    name="availability"
                    placeholder="Example: Mon - Sat, 10 AM - 5 PM"
                    required
                >

            </div>


            <button
                type="submit"
                name="add_doctor"
                class="add-btn"
            >
                ➕ Add Doctor
            </button>


        </form>

    </div>


    <a href="manage_doctors.php" class="back-btn">
        ← Back to Manage Doctors
    </a>

</div>


<footer>

    <p>© 2026 Smart Healthcare System</p>

</footer>

</body>

</html>