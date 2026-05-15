<?php
session_start();

define('MEDIUM_FLAG_HASH', 'eba5d1f9db8ecbf2f516ae01787f17c5e90f154f919b4af4584cebea50849e78');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = trim($_POST['flag'] ?? '');
    if (hash('sha256', strtolower($submitted)) === hash('sha256', strtolower('TeXSS{T1m3_Is_Th3_Ult1m4t3_K3y}'))) {
        $_SESSION['hard_unlocked'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Wrong flag. Solve the medium challenge first.';
}

$unlocked = isset($_SESSION['hard_unlocked']) && $_SESSION['hard_unlocked'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NovaTech — Hard Challenge</title>
<style>
  :root{--bg:#0a0e1a;--card:#111827;--border:#1f2d45;--accent:#00d4ff;--red:#ff4757;--text:#c9d1e0;--muted:#4a5568}
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);color:var(--text);font-family:'Courier New',monospace;min-height:100vh;display:flex;align-items:center;justify-content:center}
  .wrap{max-width:500px;width:90%;padding:2rem}
  h1{color:var(--accent);font-size:1.8rem;letter-spacing:.2rem;text-align:center;margin-bottom:.3rem}
  .sub{color:var(--muted);font-size:.8rem;text-align:center;margin-bottom:2rem}
  .badge{display:block;text-align:center;margin-bottom:1.5rem}
  .badge span{background:#1a2744;border:1px solid var(--accent);color:var(--accent);padding:.25rem .8rem;border-radius:999px;font-size:.72rem;letter-spacing:.1rem}
  .card{background:#111827;border:1px solid #1f2d45;border-radius:12px;padding:2rem}
  .card h2{font-size:1rem;margin-bottom:1rem}
  .card p{font-size:.8rem;color:var(--muted);margin-bottom:1.2rem;line-height:1.6}
  input{width:100%;background:#0d1526;border:1px solid #1f2d45;border-radius:8px;padding:.75rem 1rem;color:var(--text);font-family:'Courier New',monospace;font-size:.9rem;margin-bottom:1rem;outline:none}
  input:focus{border-color:var(--accent)}
  button{width:100%;background:var(--accent);color:#000;border:none;border-radius:8px;padding:.8rem;font-weight:700;cursor:pointer;font-size:.9rem;letter-spacing:.05rem}
  .error{background:#2a1020;border:1px solid var(--red);color:var(--red);padding:.7rem 1rem;border-radius:8px;font-size:.83rem;margin-bottom:1rem}
  .success{text-align:center}
  .success p{margin-bottom:1rem;font-size:.9rem;line-height:1.8}
  .success a{display:inline-block;background:var(--accent);color:#000;padding:.8rem 2rem;border-radius:8px;text-decoration:none;font-weight:700}
</style>
</head>
<body>
<div class="wrap">
  <h1>NOVATECH</h1>
  <p class="sub">Employee Secure Portal — CTF Hard Challenge</p>
  <div class="badge"><span>⚠ DIFFICULTY: HARD</span></div>

  <?php if ($unlocked): ?>
  <div class="card">
    <div class="success">
      <p>🔓 <strong>Challenge Unlocked!</strong><br>
         Good job on the medium level.<br>Now break this one.</p>
      <a href="login.php">→ Begin the Challenge</a>
    </div>
  </div>
  <?php else: ?>
  <div class="card">
    <h2>🔒 Enter Medium Flag to Unlock</h2>
    <p>This challenge is gated. Solve the medium level and paste its flag below.</p>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
      <input type="text" name="flag" placeholder="TeXSS{...}" autocomplete="off" spellcheck="false" required>
      <button type="submit">Unlock Hard Challenge</button>
    </form>
  </div>
  <?php endif; ?>
</div>
</body>
</html>
