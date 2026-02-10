<?php
include("../config/db.php");

// Current Serving
$current = mysqli_query(
    $conn,
    "SELECT * FROM tokens WHERE status='NowServing' LIMIT 1"
);

$now = mysqli_fetch_assoc($current);

// Waiting Queue
$queue = mysqli_query(
    $conn,
    "SELECT * FROM tokens WHERE status='Waiting' ORDER BY created_at ASC"
);
?>

<h3>Now Serving</h3>

<?php
if ($now) {
    echo "<b>" . $now['token_id'] . "</b> - " . $now['patient_id'];
} else {
    echo "No patient being served";
}
?>

<hr>

<h3>Waiting Queue</h3>

<table border="1" cellpadding="6">
    <tr>
        <th>Token</th>
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