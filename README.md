<?php
// Function to fetch live exchange rate (optional)
function getExchangeRate($from_currency, $to_currency) {
    $apiKey = 'YOUR_API_KEY_HERE'; // Replace with a real API key
    $url = "https://api.exchangerate-api.com/v4/latest/$from_currency";
    
    $response = file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        return $data['rates'][$to_currency] ?? false;
    }
    return false;
}

$exchangeRate = 55; // Default exchange rate (e.g., USD to PHP)

if (isset($_POST['convert'])) {
    $amount = floatval($_POST['amount']);
    $type = $_POST['type']; // 'buy' or 'sell'

    // Optionally fetch live exchange rate
    $liveRate = getExchangeRate('USD', 'PHP');
    if ($liveRate) {
        $exchangeRate = $liveRate;
    }

    if ($type === 'buy') {
        $total = $amount * $exchangeRate;
    } else {
        $total = $amount * ($exchangeRate - 0.5); // Slightly lower for selling
    }

    $message = "Converted Amount: " . number_format($total, 2) . " PHP";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dollar Buy/Sell</title>
</head>
<body>
    <h2>Dollar Exchange System</h2>
    <form method="POST">
        <label>Amount (USD):</label>
        <input type="number" step="0.01" name="amount" required>
        <label>Type:</label>
        <select name="type">
            <option value="buy">Buy</option>
            <option value="sell">Sell</option>
        </select>
        <button type="submit" name="convert">Convert</button>
    </form>

    <?php if (isset($message)) : ?>
        <h3><?php echo $message; ?></h3>
    <?php endif; ?>
</body>
</html>
