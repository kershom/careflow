<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != "patient") {
    die("Access Denied");
}

include("../config/db.php");

$pid = $_SESSION['user'];


// ================= FETCH PATIENT =================
$res = mysqli_query(
    $conn,
    "SELECT * FROM patients WHERE patient_id='$pid'"
);

$patient = mysqli_fetch_assoc($res);

// 🔒 Safety check to stop warnings
if (!$patient) {
    die("Patient record not found. Please login again.");
}


// ================= CURRENT TOKEN =================
$token_query = mysqli_query(
    $conn,
    "SELECT * FROM tokens
 WHERE patient_id='$pid'
 AND status IN ('Waiting','NowServing')
 ORDER BY created_at ASC
 LIMIT 1"
);

$token_data = mysqli_fetch_assoc($token_query);


// ================= NOW SERVING =================
$current = mysqli_query(
    $conn,
    "SELECT * FROM tokens WHERE status='NowServing' LIMIT 1"
);

$nowServing = mysqli_fetch_assoc($current);


// ================= QUEUE POSITION =================
$position = 0;
$estimated_time = 0;
$avg_time = 7;

if ($token_data) {

    $my_time = $token_data['created_at'];

    $count_query = mysqli_query(
        $conn,
        "SELECT COUNT(*) as total
     FROM tokens
     WHERE status='Waiting'
     AND created_at < '$my_time'"
    );

    $row = mysqli_fetch_assoc($count_query);

    $position = $row['total'];
    $estimated_time = $position * $avg_time;
}


// ================= MEDICAL HISTORY =================
$history = mysqli_query(
    $conn,
    "SELECT * FROM medical_history WHERE patient_id='$pid'"
);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Patient Dashboard</title>

    <script>
        setInterval(() => location.reload(), 5000);
    </script>
</head>

<body>

    <h2>Patient Dashboard</h2>

    <h3>My Profile</h3>

    <table border="1" cellpadding="8">
        <tr>
            <td rowspan="6">
                <?php
                if (!empty($patient['profile_image'])) {
                    echo "<img src='../uploads/" . $patient['profile_image'] . "' width='140'>";
                } else {
                    echo "No Image";
                }
                ?>
            </td>
            <td><b>Patient ID</b></td>
            <td><?php echo $patient['patient_id']; ?></td>
        </tr>

        <tr>
            <td>Name</td>
            <td><?php echo $patient['name']; ?></td>
        </tr>
        <tr>
            <td>DOB</td>
            <td><?php echo $patient['dob']; ?></td>
        </tr>
        <tr>
            <td>Gender</td>
            <td><?php echo $patient['gender']; ?></td>
        </tr>
        <tr>
            <td>Blood Group</td>
            <td><?php echo $patient['blood_group']; ?></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><?php echo $patient['phone']; ?></td>
        </tr>
    </table>

    <hr>

    <h3>Consultation Status</h3>

    <?php if ($token_data) { ?>

        <p>
            <b>Your Token:</b> <?php echo $token_data['token_id']; ?><br><br>

            <b>Now Serving:</b>
            <?php echo $nowServing ? $nowServing['token_id'] : "Not Started"; ?>
            <br><br>

            <b>Patients Ahead:</b> <?php echo $position; ?><br><br>

            <b>Estimated Waiting Time:</b> <?php echo $estimated_time; ?> minutes
        </p>

    <?php } else { ?>

        <p>No active consultation token</p>

        <form action="generate_token.php" method="POST">
            <button type="submit">Generate Consultation Token</button>
        </form>

    <?php } ?>

    <hr>

    <h3>Medical History</h3>

    <table border="1" cellpadding="8">
        <tr>
            <th>Date</th>
            <th>Doctor</th>
            <th>Diagnosis</th>
            <th>Medicines</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($history)) { ?>
            <tr>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo $row['doctor_id']; ?></td>
                <td><?php echo $row['diagnosis']; ?></td>
                <td><?php echo $row['medicines']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="../auth/logout.php">Logout</a>

</body>

</html>