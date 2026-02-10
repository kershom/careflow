<?php
include("../config/db.php");

// Fetch Now Serving Token
$current = mysqli_query($conn, "SELECT * FROM tokens WHERE status='NowServing' ORDER BY created_at ASC LIMIT 1");
$nowServing = mysqli_fetch_assoc($current);

// Fetch Waiting Queue
$queue = mysqli_query($conn, "SELECT * FROM tokens WHERE status='Waiting' ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>CareFlow | Live Queue Display</title>

    <!-- Auto Refresh Every 5 Seconds -->
    <script>
        setInterval(function () {
            location.reload();
        }, 5000);
    </script>

    <style>
        body {
            background-color: #0b1c2d;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            color: #00ffcc;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid white;
        }

        th {
            background-color: #003366;
        }

        .now-serving {
            background-color: green;
            font-size: 20px;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <h1>🏥 CAREFLOW CONSULTATION QUEUE</h1>

    <!-- ================= NOW SERVING SECTION ================= -->

    <h2>NOW SERVING</h2>

    <?php
    if ($nowServing) {
        echo "<table>
<tr class='now-serving'>
<td>" . $nowServing['token_id'] . "</td>
<td>" . $nowServing['patient_id'] . "</td>
</tr>
</table>";
    } else {
        echo "<p>No Patient Currently Being Served</p>";
    }
    ?>

    <br><br>

    <!-- ================= WAITING QUEUE ================= -->

    <h2>WAITING QUEUE</h2>

    <table>
        <tr>
            <th>Token Number</th>
            <th>Patient ID</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($queue)) {
            echo "<tr>
<td>" . $row['token_id'] . "</td>
<td>" . $row['patient_id'] . "</td>
</tr>";
        }
        ?>

    </table>

    <br>

    <p style="color:lightgray;">Auto updating every 5 seconds</p>

</body>

</html>