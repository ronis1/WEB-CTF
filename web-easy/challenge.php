<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>DevConnect - Community Wall</title>
    <style>
        body { font-family: Arial; background-color: #f4f6f8; margin:0; padding:0; }
        .header { background:#2c3e50; color:white; padding:15px; text-align:center; }
        .container { width:60%; margin:40px auto; background:white; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
        .post { margin-top:20px; padding:15px; background:#ecf0f1; border-radius:5px; }
        input[type="text"] { width:80%; padding:8px; }
        button { padding:8px 15px; background:#3498db; border:none; color:white; cursor:pointer; }
        button:hover { background:#2980b9; }
        .footer { margin-top:30px; font-size:12px; color:gray; text-align:center; }
    </style>
</head>
<body>

<div class="header">
    <h1>DevConnect</h1>
    <p>Share your thoughts with the community</p>
</div>

<div class="container">
    <form method="GET">
        <input type="text" name="message" placeholder="Write something..." required>
        <button type="submit">Post</button>
    </form>

    <?php
    // INTENTIONAL XSS VULNERABILITY
    if(isset($_GET['message'])){
        echo "<div class='post'>" . $_GET['message'] . "</div>";
    }
    ?>

    <div class="footer">
        DevConnect © 2026 | Internal Build v1.3
    </div>
</div>

</body>
</html>
