<?php
$warmup_flag = "TeXSS{Head_For_The_Easy_Level_Now}";
$start = false;

if (isset($_GET['flag']) && $_GET['flag'] === $warmup_flag) {
    $start = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>DevConnect - Easy Challenge</title>
    <style>
        body { font-family: Arial; background:#f4f6f8; text-align:center; padding:50px; }
        input { padding:10px; width:300px; }
        button { padding:10px 20px; cursor:pointer; background:#3498db; color:white; border:none; }
        button:hover { background:#2980b9; }
        .challenge { margin-top:30px; }
    </style>
</head>
<body>
<h1>DevConnect - Easy Level</h1>
<p>Enter the warmup flag to continue:</p>

<form method="GET">
    <input type="text" name="flag" placeholder="Warmup Flag" required>
    <button type="submit">Start</button>
</form>

<?php if($start): ?>
    <div class="challenge">
        <p>Warmup completed! <a href="challenge.php">Go to Challenge</a></p>
    </div>
<?php elseif(isset($_GET['flag'])): ?>
    <p style="color:red;">Incorrect flag!</p>
<?php endif; ?>

</body>
</html>
