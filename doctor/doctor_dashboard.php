<?php
session_start();
if ($_SESSION['role'] != "doctor") {
    die("Access Denied");
}

include("../config/db.php");

// Fetch Doctor Profile
$staff_id = $_SESSION['user'];
$res = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id='$staff_id'");
$staff = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Doctor Dashboard</title>

    <script>
        // Load queue without refreshing page
        function loadQueue() {
            fetch("live_queue.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("queueArea").innerHTML = data;
                });
        }

        // Refresh every 5 seconds
        setInterval(loadQueue, 5000);

        // Load once on page load
        window.onload = loadQueue;
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

        button {
            padding: 8px 14px;
            background: #007bff;
            border: none;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        textarea {
            width: 100%;
            height: 80px;
        }
    </style>

</head>

<body>

    <h2>Doctor Dashboard</h2>

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

    <!-- ================= QUEUE AREA ================= -->

    <div class="card">
        <div id="queueArea">
            Loading queue...
        </div>

        <br>

        <form action="call_next.php" method="POST">
            <button type="submit">Call Next Patient</button>
        </form>

        <form action="complete_consultation.php" method="POST">
            <button type="submit">Complete Consultation</button>
        </form>
    </div>

    <!-- ================= PRESCRIPTION ================= -->

    <div class="card">

        <h3>Write Prescription</h3>

        <form action="submit_prescription.php" method="POST">

            <textarea name="diagnosis" placeholder="Diagnosis" required></textarea><br><br>

            <textarea name="medicines" placeholder="Medicines (comma separated)" required></textarea><br><br>

            <button type="submit">Submit Prescription</button>

        </form>

    </div>

    <a href="../auth/logout.php">Logout</a>

</body>

</html>