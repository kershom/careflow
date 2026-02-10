<?php
include("../config/db.php");

// Check if someone is already serving
$check = mysqli_query($conn, "SELECT * FROM tokens WHERE status='NowServing'");

if (mysqli_num_rows($check) > 0) {
    header("Location: doctor_dashboard.php");
    exit();
}

// Fetch next waiting token
$next = mysqli_query($conn, "SELECT * FROM tokens WHERE status='Waiting' ORDER BY created_at ASC LIMIT 1");

if (mysqli_num_rows($next) == 0) {
    header("Location: doctor_dashboard.php");
    exit();
}

$row = mysqli_fetch_assoc($next);
$token_id = $row['token_id'];

// Update token to NowServing
mysqli_query($conn, "UPDATE tokens SET status='NowServing' WHERE token_id='$token_id'");

header("Location: doctor_dashboard.php");
?>