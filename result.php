<?php

session_start();

include("config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['symptoms']) || empty($_POST['symptoms'])) {
    die("Please select at least one symptom.");
}

$symptoms = $_POST['symptoms'];

$ids = implode(",", array_map('intval', $symptoms));

$number_of_symptoms = count($symptoms);

$sql = "
SELECT 
    d.disease_id,
    d.disease_name,
    d.description,
    s.specialization_name,
    COUNT(DISTINCT ds.symptom_id) AS matched_symptoms

FROM diseases d

JOIN disease_symptoms ds
    ON d.disease_id = ds.disease_id

JOIN specializations s
    ON d.specialization_id = s.specialization_id

WHERE ds.symptom_id IN ($ids)

GROUP BY
    d.disease_id,
    d.disease_name,
    d.description,
    s.specialization_name

HAVING COUNT(DISTINCT ds.symptom_id) = $number_of_symptoms

ORDER BY matched_symptoms DESC

LIMIT 1
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Disease Result - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .result-heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .result-heading h1 {
            color: #1769e0;
            font-size: 38px;
        }

        .result-heading p {
            color: #666;
            font-size: 18px;
        }

        .disease-result {
            background: white;
            max-width: 850px;
            margin: 0 auto;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .disease-name {
            color: #1769e0;
            font-size: 32px;
            margin-top: 0;
        }

        .result-info {
            font-size: 18px;
            line-height: 1.8;
            color: #444;
        }

        .specialist-box {
            background: #e8f3ff;
            padding: 18px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .specialist-box strong {
            color: #1769e0;
        }

        .doctor-button {
            display: inline-block;
            margin-top: 25px;
            background: #1769e0;
            color: white;
            padding: 13px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 17px;
        }

        .doctor-button:hover {
            background: #0d55bd;
            color: white;
            text-decoration: none;
        }

        .no-result {
            background: #fff3cd;
            max-width: 850px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
        }

        .no-result h2 {
            color: #856404;
        }

        .navigation {
            text-align: center;
            margin-top: 30px;
        }

        .navigation a {
            margin: 0 15px;
        }

    </style>

</head>

<body>

<header>

    <h1>🏥 Smart Healthcare System</h1>

</header>


<div class="container">


    <div class="result-heading">

        <h1>🩺 Disease Result</h1>

        <p>
            Based on the symptoms you selected
        </p>

    </div>


    <?php

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

    ?>

        <div class="disease-result">

            <h2 class="disease-name">
                🩺 <?php echo htmlspecialchars($row['disease_name']); ?>
            </h2>


            <hr>


            <p class="result-info">

                <strong>Description:</strong>

                <?php echo htmlspecialchars($row['description']); ?>

            </p>


            <p class="result-info">

                <strong>Matched Symptoms:</strong>

                <?php echo $row['matched_symptoms']; ?>

            </p>


            <div class="specialist-box">

                <p class="result-info">

                    <strong>👨‍⚕️ Recommended Specialist:</strong>

                    <?php echo htmlspecialchars($row['specialization_name']); ?>

                </p>

            </div>


            <div style="text-align:center;">

                <a
                    class="doctor-button"
                    href="doctor_locator.php?city=Mumbai&specialization=<?php echo urlencode($row['specialization_name']); ?>"
                >

                    🔎 Find <?php echo htmlspecialchars($row['specialization_name']); ?>

                </a>

            </div>

        </div>


    <?php

    } else {

    ?>


        <div class="no-result">

            <h2>😔 No Disease Found</h2>

            <p>
                No disease was found for all the selected symptoms.
            </p>

            <p>
                Please select symptoms carefully and try again.
            </p>

        </div>


    <?php

    }

    ?>


    <div class="navigation">

        <a href="disease_finder.php">
            ← Back to Disease Finder
        </a>

        <a href="dashboard.php">
            🏠 Back to Dashboard
        </a>

    </div>


</div>


<footer>

    <p>© 2026 Smart Healthcare System</p>

</footer>


</body>

</html>