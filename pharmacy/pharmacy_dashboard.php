<?php
session_start();
if ($_SESSION['role'] != "pharmacist") {
    die("Access Denied");
}

include("../config/db.php");

// Fetch Pharmacist Profile
$staff_id = $_SESSION['user'];
$res = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id='$staff_id'");
$staff = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Pharmacy Dashboard</title>

    <script>
        // Load pharmacy orders without refreshing page
        function loadOrders() {
            fetch("live_orders.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("ordersArea").innerHTML = data;
                });
        }

        // Refresh every 5 seconds
        setInterval(loadOrders, 5000);

        // Load once on page open
        window.onload = loadOrders;
    </script>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

    <h2>Pharmacy Dashboard</h2>

    <!-- ================= PROFILE ================= -->

    <div class="card">
        <h3>My Profile</h3>

        <table cellpadding="8">
            <tr>
                <td><b>ID</b></td>
                <td><?php echo $staff['staff_id']; ?></td>
            </tr>
            <tr>
                <td><b>Name</b></td>
                <td><?php echo $staff['name']; ?></td>
            </tr>
            <tr>
                <td><b>Department</b></td>
                <td><?php echo $staff['department']; ?></td>
            </tr>
            <tr>
                <td><b>Email</b></td>
                <td><?php echo $staff['email']; ?></td>
            </tr>
        </table>
    </div>

    <!-- ================= LIVE ORDERS ================= -->

    <div class="card">
        <div id="ordersArea">
            Loading prescriptions...
        </div>
    </div>

    <a href="../auth/logout.php">Logout</a>

</body>

</html>