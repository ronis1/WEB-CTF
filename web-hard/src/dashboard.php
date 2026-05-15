<?php
require_once __DIR__ . '/jwt.php';

$payload = get_payload();
if (!$payload) { header('Location: login.php'); exit; }

$username = htmlspecialchars($payload->username ?? 'unknown');
$role     = htmlspecialchars($payload->role     ?? 'employee');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NovaTech — Dashboard</title>
<style>
  :root{--bg:#0a0e1a;--card:#111827;--border:#1f2d45;--accent:#00d4ff;--text:#c9d1e0;--muted:#4a5568;--green:#00ff88}
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);color:var(--text);font-family:'Segoe UI',sans-serif;min-height:100vh}
  nav{background:#0d1526;border-bottom:1px solid var(--border);padding:.9rem 2rem;display:flex;justify-content:space-between;align-items:center}
  .brand{color:var(--accent);font-weight:700;letter-spacing:.15rem;font-size:1rem;font-family:'Courier New',monospace}
  nav a{color:var(--muted);text-decoration:none;font-size:.84rem;margin-left:1.5rem}
  nav a:hover{color:var(--text)}
  .main{padding:3rem 2rem;max-width:900px;margin:0 auto}
  h2{font-size:1.3rem;margin-bottom:.3rem}
  .badge{display:inline-block;background:#1a2744;border:1px solid var(--accent);color:var(--accent);padding:.2rem .7rem;border-radius:999px;font-size:.7rem;font-weight:700;letter-spacing:.05rem;margin-left:.5rem}
  .desc{color:var(--muted);font-size:.84rem;margin-top:.4rem;margin-bottom:2rem}
  .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem}
  .card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.5rem}
  .card h3{font-size:.9rem;margin-bottom:.5rem}
  .card p{font-size:.78rem;color:var(--muted);line-height:1.6}
  .restricted{color:#333!important}
  .notice{margin-top:2rem;background:#0d1a0d;border:1px solid var(--green);border-radius:8px;padding:1rem 1.5rem;font-size:.78rem;color:var(--green);line-height:1.7}
  .notice code{font-size:.72rem}
</style>
</head>
<body>
<nav>
  <span class="brand">NOVATECH</span>
  <div>
    <a href="dashboard.php">Dashboard</a>
    <a href="api/whoami.php">Whoami</a>
    <a href="logout.php">Logout</a>
  </div>
</nav>
<div class="main">
  <p style="font-size:.76rem;color:var(--muted);margin-bottom:.4rem">Welcome back,</p>
  <h2><?= $username ?> <span class="badge"><?= $role ?></span></h2>
  <p class="desc">You are signed in as an <strong>employee</strong>. Some areas require elevated privileges.</p>

  <div class="grid">
    <div class="card"><h3>📄 My Documents</h3><p>Access HR documents and payslips.</p></div>
    <div class="card"><h3>📅 Time Tracking</h3><p>Log hours and submit timesheets.</p></div>
    <div class="card"><h3>🔧 IT Requests</h3><p>Submit helpdesk tickets.</p></div>
    <div class="card"><h3>🛡 Admin Panel</h3><p class="restricted">⛔ Requires admin role.</p></div>
  </div>

  <div class="notice">
    ℹ️ Security notice: NovaTech uses JWT-based authentication signed with <strong>RS256</strong>.<br>
    The public key is available at <code>/api/public-key.php</code> for API integrations.
  </div>
</div>
</body>
</html>
