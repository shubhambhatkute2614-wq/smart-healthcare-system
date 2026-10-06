<?php

include "config/db.php";

$city = "";
$specialization = "";
$doctors = [];

if (isset($_GET["city"])) {
    $city = trim($_GET["city"]);
}

if (isset($_GET["specialization"])) {
    $specialization = trim($_GET["specialization"]);
}

if ($city != "" && $specialization != "") {

    $sql = "SELECT doctors.*, specializations.specialization_name
            FROM doctors
            JOIN specializations
            ON doctors.specialization_id = specializations.specialization_id
            WHERE doctors.city = ?
            AND specializations.specialization_name = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $city,
        $specialization
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

} elseif ($city != "") {

    $sql = "SELECT doctors.*, specializations.specialization_name
            FROM doctors
            JOIN specializations
            ON doctors.specialization_id = specializations.specialization_id
            WHERE doctors.city = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $city
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

} else {

    $result = false;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Doctor Locator - Smart Healthcare System</title>

    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Leaflet Map CSS -->
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

    <style>

        body {
            background-color: #f4f8fb;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
        }

        .page-title {
            color: #0d6efd;
            font-weight: bold;
        }

        .search-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .doctor-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s;
            height: 100%;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .doctor-icon {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background-color: #e7f1ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .doctor-name {
            color: #0d6efd;
            font-weight: bold;
        }

        .specialization-badge {
            background-color: #e7f1ff;
            color: #0d6efd;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 14px;
        }

        .info-label {
            font-weight: bold;
            color: #444;
        }

        .availability {
            color: #198754;
            font-weight: bold;
        }

        .no-doctor {
            border-radius: 15px;
            border: none;
        }

        .map-container {
    height: 400px;
    width: 100%;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 40px;
}

        footer {
            margin-top: 50px;
            padding: 20px;
            background-color: #212529;
            color: white;
            text-align: center;
        }

    </style>

</head>

<body>


<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-dark">

    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            🏥 Smart Healthcare System
        </a>

    </div>

</nav>


<!-- ================= MAIN CONTENT ================= -->

<div class="container py-5">


    <!-- PAGE TITLE -->

    <div class="text-center mb-4">

        <h1 class="page-title">
            Doctor Locator
        </h1>

        <p class="text-muted">
            Find doctors and specialists near your city
        </p>

    </div>


    <!-- ================= SEARCH FORM ================= -->

    <div class="card search-card p-4 mb-5">

        <h4 class="mb-4">
            🔍 Find a Doctor
        </h4>

        <form method="GET">

            <div class="row g-3">


                <!-- CITY -->

                <div class="col-md-5">

                    <label class="form-label">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        class="form-control"
                        placeholder="Enter city e.g. Pune"
                        value="<?php echo htmlspecialchars($city); ?>"
                    >

                </div>


                <!-- SPECIALIZATION -->

                <div class="col-md-5">

                    <label class="form-label">
                        Specialization
                    </label>

                    <input
                        type="text"
                        name="specialization"
                        class="form-control"
                        placeholder="e.g. Cardiologist"
                        value="<?php echo htmlspecialchars($specialization); ?>"
                    >

                </div>


                <!-- BUTTON -->

                <div class="col-md-2 d-flex align-items-end">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Find Doctor
                    </button>

                </div>

            </div>

        </form>

    </div>


    <!-- ================= DOCTOR RESULTS ================= -->

    </div>

<!-- Doctor Map -->

<div class="card p-3 mb-5">
    <h4 class="mb-3">🗺️ Doctor Locations</h4>

    <div id="doctorMap" class="map-container"></div>
</div>

<?php
$mapDoctors = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($doctor = mysqli_fetch_assoc($result)) {
        $mapDoctors[] = $doctor;
    }
}
?>

<?php
if (!empty($mapDoctors)) {
?>

        <h3 class="mb-4">
            👨‍⚕️ Available Doctors
        </h3>


        <div class="row g-4">

        <?php

        foreach ($mapDoctors as $doctor) {
?>

            <div class="col-md-6 col-lg-4">

                <div class="card doctor-card p-4">


                    <!-- DOCTOR HEADER -->

                    <div class="d-flex align-items-center mb-3">

                        <div class="doctor-icon me-3">
                            👨‍⚕️
                        </div>

                        <div>

                            <h4 class="doctor-name mb-1">

                                <?php
                                echo htmlspecialchars(
                                    $doctor["doctor_name"]
                                );
                                ?>

                            </h4>

                            <span class="specialization-badge">

                                <?php
                                echo htmlspecialchars(
                                    $doctor["specialization_name"]
                                );
                                ?>

                            </span>

                        </div>

                    </div>


                    <!-- HOSPITAL -->

                    <p>

                        <span class="info-label">
                            🏥 Hospital:
                        </span>

                        <?php
                        echo htmlspecialchars(
                            $doctor["hospital_name"]
                        );
                        ?>

                    </p>


                    <!-- ADDRESS -->

                    <p>

                        <span class="info-label">
                            📍 Address:
                        </span>

                        <?php
                        echo htmlspecialchars(
                            $doctor["address"]
                        );
                        ?>

                    </p>


                    <!-- CITY -->

                    <p>

                        <span class="info-label">
                            🏙️ City:
                        </span>

                        <?php
                        echo htmlspecialchars(
                            $doctor["city"]
                        );
                        ?>

                    </p>


                    <!-- MOBILE -->

                    <p>

                        <span class="info-label">
                            📞 Mobile:
                        </span>

                        <?php
                        echo htmlspecialchars(
                            $doctor["mobile"]
                        );
                        ?>

                    </p>


                    <!-- EMAIL -->

                    <p>

                        <span class="info-label">
                            📧 Email:
                        </span>

                        <?php
                        echo htmlspecialchars(
                            $doctor["email"]
                        );
                        ?>

                    </p>


                    <!-- AVAILABILITY -->

                    <p class="availability">

                        🕐
                        <?php
                        echo htmlspecialchars(
                            $doctor["availability"]
                        );
                        ?>

                    </p>


                </div>

            </div>


        <?php

        }

        ?>

        </div>


    <?php

    } elseif ($city != "") {

    ?>

        <!-- NO DOCTOR FOUND -->

        <div class="alert alert-warning no-doctor text-center p-4">

            <h4>
                😔 No Doctor Found
            </h4>

            <p class="mb-0">

                No doctor was found for

                <strong>
                    <?php echo htmlspecialchars($city); ?>
                </strong>

                <?php

                if ($specialization != "") {

                    ?>

                    with specialization

                    <strong>
                        <?php
                        echo htmlspecialchars($specialization);
                        ?>
                    </strong>

                    <?php

                }

                ?>

            </p>

        </div>


    <?php

    }

    ?>


    <!-- BACK BUTTON -->

    <div class="text-center mt-5">

        <a
            href="dashboard.php"
            class="btn btn-outline-primary"
        >
            ← Back to Dashboard
        </a>

    </div>


</div>


<!-- ================= FOOTER ================= -->

<footer>

    <p class="mb-0">
        © 2026 Smart Healthcare System |
        Disease Finder & Doctor Locator
    </p>

</footer>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Create map
    var map = L.map('doctorMap').setView([19.0760, 72.8777], 7);

    // OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Doctor markers
var doctors = <?php echo json_encode($mapDoctors); ?>;

doctors.forEach(function(doctor) {

    if (doctor.latitude && doctor.longitude) {

        var marker = L.marker([
            parseFloat(doctor.latitude),
            parseFloat(doctor.longitude)
        ]).addTo(map);

        marker.bindPopup(
    "<b>🏥 " + doctor.hospital_name + "</b><br>" +
    "👨‍⚕️ " + doctor.doctor_name + "<br>" +
    "📍 " + doctor.address + ", " + doctor.city + "<br><br>" +
    "<a href='https://www.google.com/maps/dir/?api=1&destination=" +
    parseFloat(doctor.latitude) + "," +
    parseFloat(doctor.longitude) +
    "' target='_blank' " +
    "style='display:inline-block;padding:8px 12px;background:#0d6efd;color:white;text-decoration:none;border-radius:6px;'>" +
    "🧭 Get Directions" +
    "</a>"
);
    }
});
</script>
</body>

</html>