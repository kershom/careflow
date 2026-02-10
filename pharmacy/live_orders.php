<?php
include("../config/db.php");

$orders = mysqli_query(
    $conn,
    "SELECT * FROM pharmacy_orders ORDER BY created_at DESC"
);
?>

<h3>Prescription Orders</h3>

<table border="1" cellpadding="8" width="100%">
    <tr>
        <th>Order ID</th>
        <th>Token</th>
        <th>Patient ID</th>
        <th>Medicines</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($orders)) { ?>
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td><?php echo $row['token_id']; ?></td>
            <td><?php echo $row['patient_id']; ?></td>
            <td><?php echo $row['medicines']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>

                <?php if ($row['status'] == "Pending") { ?>
                    <button onclick="updateStatus(<?php echo $row['order_id']; ?>, 'Ready')">
                        Mark Ready
                    </button>

                <?php } elseif ($row['status'] == "Ready") { ?>
                    <button onclick="updateStatus(<?php echo $row['order_id']; ?>, 'Collected')">
                        Mark Collected
                    </button>

                <?php } else { ?>
                    Completed
                <?php } ?>

            </td>
        </tr>
    <?php } ?>

</table>

<script>
    function updateStatus(id, status) {

        console.log("Sending:", id, status);

        fetch("update_status.php?id=" + id + "&status=" + status)
            .then(res => res.text())
            .then(data => {

                console.log("Server says:", data);

                // Reload orders after update
                fetch("live_orders.php")
                    .then(r => r.text())
                    .then(html => {
                        document.getElementById("ordersArea").innerHTML = html;
                    });

            });
    }
</script>