<?php
session_start();
if ($_SESSION['role'] != "reception") {
    die("Access Denied");
}

include("../config/db.php");

// Fetch Reception Profile
$staff_id = $_SESSION['user'];
$res = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id='$staff_id'");
$staff = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reception Dashboard</title>
</head>

<body>

    <h2>Reception Dashboard</h2>

    <!-- PROFILE SECTION -->
    <h3>My Profile</h3>
    <table border="1" cellpadding="8">
        <tr>
            <td><b>ID</b></td>
            <td><?php echo $staff['staff_id']; ?></td>
        </tr>
        <tr>
            <td><b>Name</b></td>
            <td><?php echo $staff['name']; ?></td>
        </tr>
        <tr>
            <td><b>Role</b></td>
            <td><?php echo ucfirst($staff['role']); ?></td>
        </tr>
        <tr>
            <td><b>Department</b></td>
            <td><?php echo $staff['department']; ?></td>
        </tr>
        <tr>
            <td><b>Email</b></td>
            <td><?php echo $staff['email']; ?></td>
        </tr>
        <tr>
            <td><b>Status</b></td>
            <td><?php echo $staff['is_active'] ? "Active" : "Inactive"; ?></td>
        </tr>
    </table>

    <hr>

    <!-- WALK-IN REGISTRATION -->
    <h3>Register Walk-in Patient</h3>

    <form action="register_patient.php" method="POST">
        <input name="name" placeholder="Name" required><br><br>
        <input type="date" name="dob" required><br><br>
        <input name="phone" placeholder="Phone" required><br><br>
        <input name="email" placeholder="Email"><br><br>
        <button type="submit">Register Patient</button>
    </form>

    <hr>

    <!-- TOKEN GENERATION -->
    <h3>Generate Consultation Token</h3>

    <form action="generate_token.php" method="POST">
        <input name="patient_id" placeholder="Enter PID" required><br><br>
        <button type="submit">Generate Token</button>
    </form>

    <br>
    <a href="../auth/logout.php">Logout</a>

</body>

</html>