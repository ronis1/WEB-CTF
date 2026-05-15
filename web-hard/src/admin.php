<?php
require_once __DIR__ . '/jwt.php';
require_role('admin');
$username = htmlspecialchars(get_payload()->username ?? 'admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NovaTech — Admin</title>
<style>
  :root{--bg:#0a0e1a;--card:#111827;--border:#1f2d45;--accent:#ff6b35;--text:#c9d1e0;--muted:#4a5568}
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);color:var(--text);font-family:'Segoe UI',sans-serif;min-height:100vh}
  nav{background:#0d1526;border-bottom:1px solid var(--border);padding:.9rem 2rem;display:flex;justify-content:space-between;align-items:center}
  .brand{color:var(--accent);font-weight:700;letter-spacing:.15rem;font-family:'Courier New',monospace}
  nav a{color:var(--muted);text-decoration:none;font-size:.84rem;margin-left:1.5rem}
  nav a:hover{color:var(--text)}
  .main{padding:3rem 2rem;max-width:900px;margin:0 auto}
  h2{font-size:1.2rem;color:var(--accent);margin-bottom:1.5rem}
  .warn{background:#1a100a;border:1px solid var(--accent);border-radius:8px;padding:.9rem 1.2rem;margin-bottom:1.5rem;font-size:.8rem;color:var(--accent)}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
  .card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.5rem;text-decoration:none;color:inherit;display:block;transition:border .2s}
  .card:hover{border-color:var(--accent)}
  .card .icon{font-size:1.3rem;margin-bottom:.5rem}
  .card h3{font-size:.92rem;margin-bottom:.35rem}
  .card p{font-size:.77rem;color:var(--muted);line-height:1.6}
  .card .dim{color:#333!important}
</style>
</head>
<body>
<nav>
  <span class="brand">NOVATECH ADMIN</span>
  <div>
    <a href="admin.php">Panel</a>
    <a href="fetch.php">Webhook Tester</a>
    <a href="render.php">Template Render</a>
    <a href="logout.php">Logout</a>
  </div>
</nav>
<div class="main">
  <div class="warn">⚠ ADMIN AREA — Authorised personnel only. All actions are logged.</div>
  <h2>Admin Panel — <?= $username ?></h2>
  <div class="grid">
    <a class="card" href="fetch.php">
      <div class="icon">🌐</div>
      <h3>Webhook / URL Tester</h3>
      <p>Fetch remote URLs to test webhook endpoints and internal service connectivity. Supports GET and POST with custom body.</p>
    </a>
    <a class="card" href="render.php">
      <div class="icon">📝</div>
      <h3>PHP Template Renderer</h3>
      <p>Render dynamic PHP templates for email and report generation. Requires the internal render secret key.</p>
    </a>
    <div class="card">
      <div class="icon">📊</div>
      <h3>Analytics</h3>
      <p class="dim">Under construction.</p>
    </div>
    <div class="card">
      <div class="icon">👥</div>
      <h3>User Management</h3>
      <p class="dim">Under construction.</p>
    </div>
  </div>
</div>
</body>
</html>
