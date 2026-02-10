<?php
session_start();
if ($_SESSION['role'] != "admin")
    die("Access Denied");
include("../config/db.php");

$res = mysqli_query($conn, "SELECT * FROM patients");
?>

<h2>Patients</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($res)) { ?>
        <tr>
            <td>
                <?php echo $row['patient_id']; ?>
            </td>
            <td>
                <?php echo $row['name']; ?>
            </td>
            <td>
                <?php echo $row['email']; ?>
            </td>
            <td>
                <?php echo $row['phone']; ?>
            </td>
        </tr>
    <?php } ?>
</table>