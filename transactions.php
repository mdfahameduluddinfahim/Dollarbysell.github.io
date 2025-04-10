<?php
session_start();
require 'db.php';
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM transactions WHERE user_id=$user_id ORDER BY created_at DESC");
echo "<h2>Your Transactions</h2>";
echo "<table border='1' cellpadding='10'><tr><th>Date</th><th>Type</th><th>Amount</th><th>Rate</th><th>Total</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['created_at']}</td>
        <td>{$row['type']}</td>
        <td>{$row['amount']}</td>
        <td>{$row['rate']}</td>
        <td>{$row['total']}</td>
    </tr>";
}
echo "</table>";
?>
<a href="dashboard.php">Back</a>