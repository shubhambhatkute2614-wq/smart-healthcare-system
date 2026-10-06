<?php

session_start();

include("config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


/* =========================
   ADD DISEASE
========================= */

$message = "";

if (isset($_POST['add_disease'])) {

    $disease_name = trim($_POST['disease_name']);
    $description = trim($_POST['description']);
    $specialization_id = $_POST['specialization_id'];

    if ($disease_name != "" && $description != "" && $specialization_id != "") {

        $sql = "INSERT INTO diseases
                (disease_name, description, specialization_id)
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $disease_name,
            $description,
            $specialization_id
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: manage_diseases.php?success=1");
            exit();

        } else {

            $message = "Error while adding disease.";
        }

    } else {

        $message = "Please fill all fields.";
    }
}


/* =========================
   SUCCESS MESSAGE
========================= */

if (isset($_GET['success'])) {
    $message = "Disease added successfully!";
}

if (isset($_GET['deleted'])) {
    $message = "Disease deleted successfully!";
}


/* =========================
   GET SPECIALIZATIONS
========================= */

$specialization_query = "
    SELECT specialization_id, specialization_name
    FROM specializations
    ORDER BY specialization_name
";

$specialization_result = mysqli_query(
    $conn,
    $specialization_query
);


/* =========================
   GET DISEASES
========================= */

$query = "SELECT 
            diseases.disease_id,
            diseases.disease_name,
            diseases.description,
            specializations.specialization_name
          FROM diseases
          LEFT JOIN specializations
          ON diseases.specialization_id = specializations.specialization_id
          ORDER BY diseases.disease_id DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Diseases - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .manage-container {
            width: 90%;
            max-width: 1200px;
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

        /* ADD DISEASE FORM */

        .add-card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            margin-bottom: 35px;
        }

        .add-card h2 {
            color: #1769e0;
            margin-top: 0;
            margin-bottom: 25px;
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
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 13px;
            border: 1px solid #d5dce5;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1769e0;
        }

        .add-btn {
            background: #1769e0;
            color: white;
            border: none;
            padding: 13px 28px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .add-btn:hover {
            background: #0d55bd;
        }

        .success-message {
            background: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
        }

        .error-message {
            background: #f8d7da;
            color: #842029;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
        }

        /* TABLE */

        .table-card {
            background: white;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1769e0;
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f5f9ff;
        }

        .back-btn {
            display: block;
            width: fit-content;
            margin: 35px auto;
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

        .action-buttons {
    display: flex;
    align-items: center;
    gap: 10px;
}

.edit-btn {
    display: inline-block;
    background: #1769e0;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}

.edit-btn:hover {
    background: #0d55bd;
    color: white;
    text-decoration: none;
}

.delete-btn {
    display: inline-block;
    background: #dc3545;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}

.delete-btn:hover {
    background: #b02a37;
    color: white;
}

    </style>

</head>

<body>

<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="manage-container">

    <h1 class="page-title">
        🩺 Manage Diseases
    </h1>

    <p class="page-subtitle">
        Add and manage diseases available in the system
    </p>


    <!-- MESSAGE -->

    <?php if ($message != "") { ?>

        <?php if (isset($_GET['success'])) { ?>

            <div class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } else { ?>

            <div class="error-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

    <?php } ?>


    <!-- ADD DISEASE -->

    <div class="add-card">

        <h2>➕ Add New Disease</h2>

        <form method="POST" action="manage_diseases.php">

            <div class="form-group">

                <label>
                    Disease Name
                </label>

                <input
                    type="text"
                    name="disease_name"
                    placeholder="Enter disease name"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Description
                </label>

                <textarea
                    name="description"
                    placeholder="Enter disease description"
                    required
                ></textarea>

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

                    while (
                        $specialization =
                        mysqli_fetch_assoc($specialization_result)
                    ) {

                    ?>

                        <option
                            value="<?php echo $specialization['specialization_id']; ?>"
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


            <button
                type="submit"
                name="add_disease"
                class="add-btn"
            >
                ➕ Add Disease
            </button>

        </form>

    </div>


    <!-- DISEASE TABLE -->

    <div class="table-card">

        <table>

            <tr>

                <th>ID</th>

                <th>Disease Name</th>

                <th>Description</th>

                <th>Specialization</th>

                <th>Action</th>

            </tr>


            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {

            ?>

                <tr>

                    <td>
                        <?php echo $row['disease_id']; ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['disease_name']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['description']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['specialization_name']
                            ?? 'Not Assigned'
                        );
                        ?>
                    </td>
                    <td>

    <div class="action-buttons">

        <a
            href="edit_disease.php?id=<?php echo $row['disease_id']; ?>"
            class="edit-btn"
        >
            ✏️ Edit
        </a>

        <a
            href="manage_diseases.php?delete=<?php echo $row['disease_id']; ?>"
            class="delete-btn"
            onclick="return confirm('Are you sure you want to delete this disease?');"
        >
            🗑️ Delete
        </a>

    </div>

</td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td
                        colspan="4"
                        style="text-align:center;"
                    >
                        No diseases found.
                    </td>

                </tr>

            <?php

            }

            ?>

        </table>

    </div>


    <a
        href="admin_dashboard.php"
        class="back-btn"
    >
        ← Back to Admin Dashboard
    </a>

</div>


<footer>

    <p>© 2026 Smart Healthcare System</p>

</footer>

</body>

</html>