<?php

session_start();

include("config/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$query = "SELECT 
            doctors.doctor_id,
            doctors.doctor_name,
            specializations.specialization_name,
            doctors.hospital_name,
            doctors.address,
            doctors.city,
            doctors.mobile,
            doctors.email,
            doctors.availability
          FROM doctors
          LEFT JOIN specializations
          ON doctors.specialization_id = specializations.specialization_id
          ORDER BY doctors.doctor_id DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Doctors - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .manage-container {
            width: 95%;
            max-width: 1400px;
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

    </style>

</head>

<body>

<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="manage-container">

    <h1 class="page-title">
        👨‍⚕️ Manage Doctors
    </h1>

    <p class="page-subtitle">
        View all doctors available in the system
    </p>


    <div class="table-card">

        <table>

            <tr>

                <th>ID</th>

                <th>Doctor Name</th>

                <th>Specialization</th>

                <th>Hospital</th>

                <th>Address</th>

                <th>City</th>

                <th>Mobile</th>

                <th>Email</th>

                <th>Availability</th>

                <th>Action</th>

            </tr>


            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {

            ?>

                <tr>
                    

                    <td>
                        <?php echo $row['doctor_id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['doctor_name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['specialization_name'] ?? 'Not Assigned'); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['hospital_name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['address']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['city']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['mobile']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['email']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['availability']); ?>
                    </td>

                    <td>

    <div class="action-buttons">

        <a
            href="edit_doctor.php?id=<?php echo $row['doctor_id']; ?>"
            class="edit-btn"
        >
            ✏️ Edit
        </a>

        <a
    href="delete_doctor.php?id=<?php echo $row['doctor_id']; ?>"
    class="delete-btn"
    onclick="return confirm('Are you sure you want to delete this doctor?');"
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

                    <td colspan="10" style="text-align:center;">
    No doctors found.
</td>

                </tr>

            <?php

            }

            ?>

        </table>

    </div>


    <a href="admin_dashboard.php" class="back-btn">
        ← Back to Admin Dashboard
    </a>

</div>


<footer>

    <p>© 2026 Smart Healthcare System</p>

</footer>

</body>

</html>