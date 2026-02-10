<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    die("Access Denied");
}

include("../config/db.php");


// ================= COUNTS =================

// Patients
$q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM patients");
$patients = mysqli_fetch_assoc($q)['total'];

// Doctors
$q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff WHERE role='doctor'");
$doctors = mysqli_fetch_assoc($q)['total'];

// Pharmacists
$q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff WHERE role='pharmacist'");
$pharmacists = mysqli_fetch_assoc($q)['total'];

// Receptionists
$q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff WHERE role='reception'");
$receptionists = mysqli_fetch_assoc($q)['total'];

// Appointments Today (tokens created today)
$q = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM tokens WHERE DATE(created_at)=CURDATE()"
);
$consultations_today = mysqli_fetch_assoc($q)['total'];


// ================= RECENT APPOINTMENTS =================

$recent = mysqli_query(
    $conn,
    "SELECT p.name AS patient, s.name AS doctor, t.created_at, t.status
 FROM tokens t
 JOIN patients p ON t.patient_id = p.patient_id
 LEFT JOIN staff s ON t.doctor_id = s.staff_id
 ORDER BY t.created_at DESC
 LIMIT 5"
);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        /* Layout */
        .container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #0f172a;
            color: white;
            height: 100vh;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            margin: 10px 0;
        }

        .sidebar a:hover {
            color: white;
        }

        /* Main */
        .main {
            flex: 1;
            padding: 30px;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h3 {
            margin: 0;
            color: #555;
        }

        .card p {
            font-size: 26px;
            margin: 10px 0 0;
            reminder: bold;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f5f9;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <h2>CareFlow</h2>

            <a href="admin_dashboard.php">Dashboard</a>
            <a href="patients.php">Patients</a>
            <a href="doctors.php">Doctors</a>
            <a href="pharmacists.php">Pharmacists</a>
            <a href="receptionists.php">Receptionists</a>

            <br>
            <a href="../auth/logout.php">Logout</a>
        </div>


        <!-- MAIN -->
        <div class="main">

            <h1>Admin Dashboard</h1>

            <!-- CARDS -->
            <div class="cards">
                <div class="card">
                    <h3>Patients</h3>
                    <p><?php echo $patients; ?></p>
                </div>

                <div class="card">
                    <h3>Doctors</h3>
                    <p><?php echo $doctors; ?></p>
                </div>

                <div class="card">
                    <h3>Pharmacists</h3>
                    <p><?php echo $pharmacists; ?></p>
                </div>

                <div class="card">
                    <h3>Receptionists</h3>
                    <p><?php echo $receptionists; ?></p>
                </div>

                <div class="card">
                    <h3>Appointments Today</h3>
                    <p><?php echo $appointments_today; ?></p>
                </div>
            </div>


            <!-- RECENT APPOINTMENTS -->
            <h2>Recent Consultations</h2>


            <table>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($recent)) { ?>
                    <tr>
                        <td><?php echo $row['patient']; ?></td>
                        <td><?php echo $row['doctor'] ?? "Not Assigned"; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php } ?>
            </table>

        </div>
    </div>

</body>

</html>