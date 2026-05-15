<?php
session_start();
$required_flag = "TeXSS{Reflected_XSS_Chained_Successfully}";

if (isset($_POST['easy_flag'])) {
    if ($_POST['easy_flag'] === $required_flag) {
        $_SESSION['unlocked_medium'] = true;
        header("Location: challenge.php");
        exit();
    } else {
        $error = "Incorrect flag! You must solve the Easy level first.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medium Challenge - Phase 2</title>
    <style>
        body { background: #121212; color: #00ff00; font-family: 'Courier New', monospace; text-align: center; padding-top: 100px; }
        input { padding: 10px; width: 350px; background: #222; border: 1px solid #00ff00; color: #0f0; }
        button { padding: 10px 20px; background: #00ff00; color: #000; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <h1>SYSTEM LOCKED</h1>
    <p>Enter the decryption key (Easy Level Flag) to proceed:</p>
    <form method="POST">
        <input type="text" name="easy_flag" placeholder="TeXSS{...}" required>
        <button type="submit">DECRYPT</button>
    </form>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
