<?php
session_start();
include("../config/db.php");

// Prevent direct access
if (!isset($_POST['userid'])) {
    header("Location: ../public/login.html");
    exit();
}

$id = $_POST['userid'];
$pass = $_POST['password'];
$role = $_POST['role'];


// ================= PATIENT LOGIN =================
if ($role == "patient") {

    $q = "SELECT * FROM patients
          WHERE (patient_id='$id' OR email='$id')
          AND password='$pass'
          AND is_active=1";

    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) == 1) {

        $row = mysqli_fetch_assoc($res);

        // 🔥 IMPORTANT FIX → store REAL patient_id in session
        $_SESSION['user'] = $row['patient_id'];
        $_SESSION['role'] = "patient";

        header("Location: ../patient/patient_dashboard.php");
        exit();
    }
}


// ================= STAFF LOGIN =================
else {

    $q = "SELECT * FROM staff
          WHERE (staff_id='$id' OR email='$id')
          AND password='$pass'
          AND role='$role'
          AND is_active=1";

    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) == 1) {

        $row = mysqli_fetch_assoc($res);

        $_SESSION['user'] = $row['staff_id'];
        $_SESSION['role'] = $row['role'];

        // ===== STAFF REDIRECT FIX =====

        if ($row['role'] == "doctor") {
            header("Location: ../doctor/doctor_dashboard.php");
        } elseif ($row['role'] == "pharmacist") {
            header("Location: ../pharmacy/pharmacy_dashboard.php");
        } elseif ($row['role'] == "reception") {
            header("Location: ../reception/reception_dashboard.php");
        } else {
            echo "Invalid staff role";
        }

        exit();

        exit();
    }
}


// ================= INVALID LOGIN =================
echo "<h3>Invalid Login Credentials</h3>";
echo "<a href='../public/login.html'>Try Again</a>";
?>