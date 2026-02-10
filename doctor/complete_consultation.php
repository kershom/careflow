<?php
include("../config/db.php");

// Find current serving token
$current = mysqli_query($conn, "SELECT * FROM tokens WHERE status='NowServing' LIMIT 1");

if (mysqli_num_rows($current) == 0) {
    header("Location: doctor_dashboard.php");
    exit();
}

$row = mysqli_fetch_assoc($current);
$token_id = $row['token_id'];

// Mark as completed
mysqli_query($conn, "UPDATE tokens SET status='Completed' WHERE token_id='$token_id'");

header("Location: doctor_dashboard.php");
?>