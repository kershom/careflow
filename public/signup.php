<?php
include("../config/db.php");

$name = $_POST['name'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$pass = $_POST['password'];

// Generate Patient ID
$r = mysqli_query($conn, "SELECT COUNT(*) AS total FROM patients");
$row = mysqli_fetch_assoc($r);

$num = $row['total'] + 1;
$pid = "PID-" . str_pad($num, 4, "0", STR_PAD_LEFT);

// Insert Patient (ONLY required fields)
mysqli_query(
    $conn,
    "INSERT INTO patients
(patient_id, name, dob, email, phone, password, is_active, created_at)
VALUES
('$pid','$name','$dob','$email','$phone','$pass',1,NOW())"
);

echo "<h3>Registration Successful</h3>";
echo "<p>Your Patient ID: <b>$pid</b></p>";
echo "<a href='login.html'>Login Now</a>";
?>