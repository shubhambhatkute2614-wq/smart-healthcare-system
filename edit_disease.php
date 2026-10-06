<?php

session_start();

include("config/db.php");


// Admin login check
if (!isset($_SESSION['admin_id'])) {

    header("Location: admin_login.php");
    exit();

}


// Check disease ID
if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location: manage_diseases.php");
    exit();

}


$disease_id = intval($_GET['id']);


// Fetch disease details
$query = "SELECT * FROM diseases WHERE disease_id = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "i", $disease_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) == 0) {

    die("Disease not found.");

}


$disease = mysqli_fetch_assoc($result);


// Fetch specializations
$specialization_query =
    "SELECT * FROM specializations ORDER BY specialization_name";

$specialization_result =
    mysqli_query($conn, $specialization_query);


// Update disease
if (isset($_POST['update_disease'])) {

    $disease_name = trim($_POST['disease_name']);

    $description = trim($_POST['description']);

    $specialization_id = intval($_POST['specialization_id']);


    $update_query =
        "UPDATE diseases
         SET disease_name = ?,
             description = ?,
             specialization_id = ?
         WHERE disease_id = ?";


    $update_stmt =
        mysqli_prepare($conn, $update_query);


    mysqli_stmt_bind_param(
        $update_stmt,
        "ssii",
        $disease_name,
        $description,
        $specialization_id,
        $disease_id
    );


    if (mysqli_stmt_execute($update_stmt)) {

        header("Location: manage_diseases.php?updated=1");

        exit();

    } else {

        $error = "Failed to update disease.";

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Disease - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">


    <style>

        .edit-container {

            width: 90%;

            max-width: 900px;

            margin: 50px auto;

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


        .edit-card {

            background: white;

            padding: 35px;

            border-radius: 18px;

            box-shadow: 0 8px 25px rgba(0,0,0,0.08);

        }


        .form-group {

            margin-bottom: 22px;

        }


        .form-group label {

            display: block;

            font-weight: bold;

            margin-bottom: 8px;

            font-size: 17px;

        }


        .form-group input,

        .form-group textarea,

        .form-group select {

            width: 100%;

            padding: 13px;

            border: 1px solid #d5dce5;

            border-radius: 8px;

            font-size: 16px;

            box-sizing: border-box;

        }


        .form-group textarea {

            min-height: 130px;

            resize: vertical;

        }


        .form-group input:focus,

        .form-group textarea:focus,

        .form-group select:focus {

            outline: none;

            border-color: #1769e0;

        }


        .update-btn {

            background: #1769e0;

            color: white;

            border: none;

            padding: 13px 28px;

            border-radius: 8px;

            font-size: 17px;

            cursor: pointer;

        }


        .update-btn:hover {

            background: #0d55bd;

        }


        .back-btn {

            display: block;

            width: fit-content;

            margin: 30px auto;

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

        }

    </style>

</head>


<body>


<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="edit-container">


    <h1 class="page-title">

        ✏️ Edit Disease

    </h1>


    <p class="page-subtitle">

        Update disease information

    </p>


    <div class="edit-card">


        <?php if (isset($error)) { ?>

            <div class="error-message">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php } ?>


        <form method="POST">


            <div class="form-group">

                <label>

                    Disease Name

                </label>


                <input

                    type="text"

                    name="disease_name"

                    value="<?php echo htmlspecialchars($disease['disease_name']); ?>"

                    required

                >

            </div>



            <div class="form-group">

                <label>

                    Description

                </label>


                <textarea

                    name="description"

                    required

                ><?php echo htmlspecialchars($disease['description']); ?></textarea>

            </div>



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

                    while ($specialization = mysqli_fetch_assoc($specialization_result)) {

                    ?>

                        <option

                            value="<?php echo $specialization['specialization_id']; ?>"

                            <?php

                            if (

                                $specialization['specialization_id']

                                ==

                                $disease['specialization_id']

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



            <div style="text-align:center;">

                <button

                    type="submit"

                    name="update_disease"

                    class="update-btn"

                >

                    💾 Update Disease

                </button>

            </div>


        </form>


    </div>


    <a

        href="manage_diseases.php"

        class="back-btn"

    >

        ← Back to Manage Diseases

    </a>


</div>


<footer>

    <p>

        © 2026 Smart Healthcare System

    </p>

</footer>


</body>

</html>