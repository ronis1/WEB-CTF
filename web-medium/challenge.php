<?php
session_start();
if (!isset($_SESSION['unlocked_medium'])) {
    header("Location: index.php");
    exit();
}

// DB connection using environment variables from docker-compose
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));

$message = "Waiting for input...";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // VULNERABLE: No sanitization. Time-based Blind SQLi target.
    // The code does NOT print results or errors, forcing the use of SLEEP()
    $sql = "SELECT username FROM users WHERE id = '$id'";
    $result = $conn->query($sql);

    $message = "Query executed. Database response: [REDACTED]";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Internal Member Portal</title>
    <style>
        body { background: #1a1a1a; color: #cfcfcf; font-family: sans-serif; text-align: center; }
        .container { margin-top: 50px; border: 1px solid #444; display: inline-block; padding: 20px; border-radius: 10px; }
        .hint { color: #555; font-size: 0.8em; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Member Verification System</h2>
        <form method="GET">
            User ID: <input type="text" name="id" placeholder="e.g. 1337">
            <button type="submit">Verify</button>
        </form>
        <p><strong>System Status:</strong> <?php echo $message; ?></p>
        <p class="hint">Note: High latency detected in DB response for certain queries.</p>
    </div>
</body>
</html>
