<?php

session_start();

include("config/db.php");


// Check admin login

if (!isset($_SESSION['admin_id'])) {

    header("Location: admin_login.php");
    exit();

}


// Check disease ID

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: manage_diseases.php");
    exit();

}


$disease_id = intval($_GET['id']);


// Delete disease

$sql = "DELETE FROM diseases WHERE disease_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $disease_id
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: manage_diseases.php?deleted=1");
    exit();

} else {

    echo "Error deleting disease: " . mysqli_error($conn);

}

?>