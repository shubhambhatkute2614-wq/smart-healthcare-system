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


/* Delete Doctor */
$sql = "DELETE FROM doctors WHERE doctor_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $doctor_id
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_doctors.php?deleted=1");
    exit();

} else {

    die("Doctor deletion failed: " . mysqli_error($conn));

}

?>