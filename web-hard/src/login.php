<?php
session_start();
if (!isset($_SESSION['hard_unlocked'])) { header('Location: index.php'); exit; }

require_once __DIR__ . '/jwt.php';

// Password is weak — brute forceable with rockyou.txt
define('USERS', ['jdoe' => 'dragon']);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if (isset(USERS[$u]) && USERS[$u] === $p) {
        $token = make_token(['username' => $u, 'role' => 'employee']);
        setcookie('token', $token, 0, '/', '', false, true);
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid credentials.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NovaTech — Login</title>
<style>
  :root{--bg:#0a0e1a;--card:#111827;--border:#1f2d45;--accent:#00d4ff;--red:#ff4757;--text:#c9d1e0;--muted:#4a5568}
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);color:var(--text);font-family:'Courier New',monospace;min-height:100vh;display:flex;align-items:center;justify-content:center}
  .wrap{max-width:400px;width:90%;padding:2rem}
  h1{color:var(--accent);font-size:1.5rem;letter-spacing:.2rem;text-align:center;margin-bottom:.3rem}
  .sub{color:var(--muted);font-size:.78rem;text-align:center;margin-bottom:2rem}
  .card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:2rem}
  label{display:block;font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05rem;margin-bottom:.35rem}
  input{width:100%;background:#0d1526;border:1px solid var(--border);border-radius:8px;padding:.7rem 1rem;color:var(--text);font-family:inherit;font-size:.88rem;margin-bottom:1.1rem;outline:none}
  input:focus{border-color:var(--accent)}
  button{width:100%;background:var(--accent);color:#000;border:none;border-radius:8px;padding:.75rem;font-weight:700;cursor:pointer}
  .error{background:#2a1020;border:1px solid var(--red);color:var(--red);padding:.65rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1rem}
  .hint{margin-top:1.5rem;padding:1rem;background:#0d1526;border-radius:8px;font-size:.72rem;color:var(--muted);line-height:1.7}
  .hint a{color:var(--accent);text-decoration:none}
</style>
</head>
<body>
<!-- Developer Note: employee account "jdoe" is still active on this portal. Password policy enforced since v1.3 - see IT wiki. -->
<div class="wrap">
  <h1>NOVATECH</h1>
  <p class="sub">Secure Employee Portal</p>
  <div class="card">
    <h2 style="font-size:1rem;margin-bottom:1.3rem">Sign In</h2>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
      <label>Username</label>
      <input type="text" name="username" placeholder="your.username" autocomplete="off" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
      <button type="submit">Login →</button>
    </form>
    <div class="hint">
      Lost access? Contact IT at <code>it-support@novatech.internal</code><br>
      API docs: <a href="/api/public-key.php">/api/public-key.php</a> · <a href="/api/whoami.php">/api/whoami.php</a>
    </div>
  </div>
</div>
</body>
</html>
