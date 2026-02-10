<?php
session_start();
if ($_SESSION['role'] != "admin")
    die("Access Denied");
include("../config/db.php");

$res = mysqli_query($conn, "SELECT * FROM staff WHERE role='reception'");
?>

<h2>Receptionists</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($res)) { ?>
        <tr>
            <td>
                <?php echo $row['staff_id']; ?>
            </td>
            <td>
                <?php echo $row['name']; ?>
            </td>
            <td>
                <?php echo $row['email']; ?>
            </td>
        </tr>
    <?php } ?>
</table>