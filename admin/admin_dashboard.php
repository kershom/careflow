<?php session_start();
if ($_SESSION['role'] != "admin")
    die("Access Denied");
?>

<h1>Admin Dashboard</h1>

<ul>
    <li>Staff Management</li>
    <li>Patient Overview</li>
    <li>Analytics</li>
    <li>Revenue Summary</li>
</ul>

<a href="../auth/logout.php">Logout</a>