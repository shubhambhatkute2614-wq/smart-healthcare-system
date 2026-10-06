<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM symptoms ORDER BY symptom_name";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Disease Finder - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<!-- Header -->

<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<!-- Main Container -->

<div class="container">

    <!-- Page Heading -->

    <div class="heading">

        <h1>🩺 Disease Finder</h1>

        <p>
            Select your symptoms to find possible diseases
        </p>

    </div>


    <!-- Symptom Box -->

    <div class="card">

        <h2>🔍 Select Your Symptoms</h2>

        <p class="instruction">
            Please select all the symptoms you are experiencing.
        </p>


        <form method="POST" action="result.php">

            <div class="symptom-grid">

                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>

                    <label class="symptom">

                        <input
                            type="checkbox"
                            name="symptoms[]"
                            value="<?php echo $row['symptom_id']; ?>"
                        >

                        <?php echo htmlspecialchars($row['symptom_name']); ?>

                    </label>

                <?php
                }
                ?>

            </div>


            <br>

            <div style="text-align:center;">

                <button type="submit">
                    🔎 Find Disease
                </button>

            </div>

        </form>

    </div>


    <!-- Back Button -->

    <div style="text-align:center; margin-top:30px;">

        <a href="dashboard.php">
            ← Back to Dashboard
        </a>

    </div>

</div>


<!-- Footer -->

<footer>

    <p>© 2026 Smart Healthcare System</p>

</footer>

</body>

</html>