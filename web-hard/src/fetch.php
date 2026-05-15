<?php
require_once __DIR__ . '/jwt.php';
require_role('admin');

$url    = '';
$method = 'GET';
$body   = '';
$ctype  = 'application/xml';
$result = null;
$error  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url    = trim($_POST['url']          ?? '');
    $method = strtoupper($_POST['method'] ?? 'GET');
    $body   = $_POST['body']              ?? '';
    $ctype  = $_POST['content_type']      ?? 'application/xml';

    if ($url) {
        // ─────────────────────────────────────────────────────────────────
        // VULNERABILITY: SSRF — no allowlist or blocklist on $url
        // An attacker can target http://web-hard-internal:8080/
        // which is only reachable from within the Docker backend network.
        // ─────────────────────────────────────────────────────────────────
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 7);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: $ctype"]);
        }

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
        }
        curl_close($ch);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NovaTech — Webhook Tester</title>
<style>
  :root{--bg:#0a0e1a;--card:#111827;--border:#1f2d45;--accent:#ff6b35;--blue:#00d4ff;--text:#c9d1e0;--muted:#4a5568;--red:#ff4757}
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);color:var(--text);font-family:'Segoe UI',monospace;min-height:100vh}
  nav{background:#0d1526;border-bottom:1px solid var(--border);padding:.9rem 2rem;display:flex;justify-content:space-between;align-items:center}
  .brand{color:#ff6b35;font-weight:700;letter-spacing:.15rem;font-family:'Courier New',monospace}
  nav a{color:var(--muted);text-decoration:none;font-size:.84rem;margin-left:1.5rem}
  .main{padding:2.5rem 2rem;max-width:900px;margin:0 auto}
  h2{font-size:1.1rem;color:#ff6b35;margin-bottom:1.5rem}
  .form-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.8rem;margin-bottom:1.5rem}
  label{display:block;font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05rem;margin-bottom:.35rem}
  input,select,textarea{width:100%;background:#0d1526;border:1px solid var(--border);border-radius:8px;padding:.65rem 1rem;color:var(--text);font-family:'Courier New',monospace;font-size:.83rem;margin-bottom:1.1rem;outline:none;resize:vertical}
  input:focus,select:focus,textarea:focus{border-color:var(--blue)}
  select option{background:#0d1526}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
  button{background:#ff6b35;color:#000;border:none;border-radius:8px;padding:.7rem 1.5rem;font-weight:700;cursor:pointer}
  .result-box{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.5rem}
  .result-box h3{font-size:.8rem;color:var(--muted);text-transform:uppercase;margin-bottom:.8rem}
  pre{background:#0d1526;border-radius:8px;padding:1rem;font-size:.78rem;overflow-x:auto;white-space:pre-wrap;word-break:break-all;max-height:450px;overflow-y:auto}
  .err{color:var(--red);font-size:.82rem;padding:.7rem 1rem;background:#1a0a0a;border:1px solid var(--red);border-radius:8px}
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
  <h2>🌐 Webhook / URL Tester</h2>
  <div class="form-card">
    <form method="POST">
      <label>Target URL</label>
      <input type="text" name="url" value="<?= htmlspecialchars($url) ?>"
             placeholder="https://example.com/webhook  —or—  http://internal-host:port/path">
      <div class="row">
        <div>
          <label>HTTP Method</label>
          <select name="method">
            <option value="GET"  <?= $method==='GET'  ? 'selected':'' ?>>GET</option>
            <option value="POST" <?= $method==='POST' ? 'selected':'' ?>>POST</option>
          </select>
        </div>
        <div>
          <label>Content-Type (POST only)</label>
          <select name="content_type">
            <option value="application/xml">application/xml</option>
            <option value="application/json">application/json</option>
            <option value="application/x-www-form-urlencoded">form-urlencoded</option>
          </select>
        </div>
      </div>
      <label>Request Body (POST only)</label>
      <textarea name="body" rows="6" placeholder="XML / JSON body…"><?= htmlspecialchars($body) ?></textarea>
      <button type="submit">Send Request →</button>
    </form>
  </div>

  <?php if ($result !== null): ?>
  <div class="result-box">
    <h3>Response from <?= htmlspecialchars($url) ?></h3>
    <pre><?= htmlspecialchars($result) ?></pre>
  </div>
  <?php endif; ?>

  <?php if ($error): ?>
  <p class="err">⚠ <?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
</div>
</body>
</html>
