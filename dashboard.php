<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';
function getExchangeRate($from, $to) {
    $url = "https://open.er-api.com/v6/latest/$from";
    $data = json_decode(file_get_contents($url), true);
    return $data['rates'][$to] ?? 110;
}
$user_id = $_SESSION['user_id'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = floatval($_POST['amount']);
    $type = $_POST['type'];
    $rate = getExchangeRate('USD', 'BDT');
    $final_rate = $type === 'sell' ? $rate - 1 : $rate;
    $total = $amount * $final_rate;

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, type, rate, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idssd", $user_id, $amount, $type, $final_rate, $total);
    $stmt->execute();
    $message = "Transaction saved! You " . ($type == 'buy' ? "pay" : "get") . " BDT " . number_format($total, 2);
}
?>
<h2>Dollar Buy/Sell</h2>
<form method="POST">
    Amount (USD): <input type="number" name="amount" step="0.01" required>
    Type: 
    <select name="type">
        <option value="buy">Buy</option>
        <option value="sell">Sell</option>
    </select>
    <button type="submit">Submit</button>
</form>
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>
<a href="transactions.php">View Transactions</a> |
<a href="logout.php">Logout</a>