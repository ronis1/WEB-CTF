<?php
require_once __DIR__ . '/jwt.php';
require_role('admin');

define('RENDER_SECRET', trim(file_get_contents('/app/config/render_secret.txt')));

$secret   = $_POST['secret']   ?? '';
$template = $_POST['template'] ?? '';
$result   = null;
$error    = null;
$blocked  = null;
$locked   = ($secret !== RENDER_SECRET);

if (!$locked && $template) {

    // ─────────────────────────────────────────────────────────────────────────
    // VULNERABILITY: PHP eval() with a naive substring blacklist.
    //
    // The blacklist blocks common dangerous LITERALS, but PHP allows
    // function names to be built via string concatenation at runtime:
    //
    //   ('sys'.'tem')('cat /flag.txt')
    //   ('she'.'ll_exec')('cat /flag.txt')
    //
    // These bypass the check because the substrings are never literally
    // present in $template before execution.
    // ─────────────────────────────────────────────────────────────────────────
    $blacklist = [
        'system', 'exec', 'shell_exec', 'passthru', 'popen',
        'proc_open', 'file_get_contents', 'file_put_contents',
        'readfile', 'fopen', 'fread', 'include', 'require',
        'base64_decode', 'hex2bin', 'eval', 'assert',
        'preg_replace', 'call_user_func', 'create_function',
    ];

    foreach ($blacklist as $b) {
        if (stripos($template, $b) !== false) {
            $blocked = $b;
            break;
        }
    }

    if (!$blocked) {
        ob_start();
        try {
            eval($template);
            $result = ob_get_clean();
        } catch (ParseError $e) {
            ob_end_clean();
            $error = 'PHP Parse Error: ' . $e->getMessage();
        } catch (Throwable $t) {
            ob_end_clean();
            $error = 'Error: ' . $t->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NovaTech — Template Renderer</title>
<style>
  :root{--bg:#0a0e1a;--card:#111827;--border:#1f2d45;--accent:#ff6b35;--blue:#00d4ff;--text:#c9d1e0;--muted:#4a5568;--red:#ff4757;--green:#00ff88}
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);color:var(--text);font-family:'Segoe UI',monospace;min-height:100vh}
  nav{background:#0d1526;border-bottom:1px solid var(--border);padding:.9rem 2rem;display:flex;justify-content:space-between;align-items:center}
  .brand{color:#ff6b35;font-weight:700;letter-spacing:.15rem;font-family:'Courier New',monospace}
  nav a{color:var(--muted);text-decoration:none;font-size:.84rem;margin-left:1.5rem}
  .main{padding:2.5rem 2rem;max-width:900px;margin:0 auto}
  h2{font-size:1.1rem;color:#ff6b35;margin-bottom:1.5rem}
  .form-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1.8rem;margin-bottom:1.5rem}
  label{display:block;font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05rem;margin-bottom:.35rem}
  input,textarea{width:100%;background:#0d1526;border:1px solid var(--border);border-radius:8px;padding:.65rem 1rem;color:var(--text);font-family:'Courier New',monospace;font-size:.83rem;margin-bottom:1.1rem;outline:none;resize:vertical}
  input:focus,textarea:focus{border-color:var(--blue)}
  button{background:#ff6b35;color:#000;border:none;border-radius:8px;padding:.7rem 1.5rem;font-weight:700;cursor:pointer}
  .locked-box{background:#1a100a;border:1px solid var(--accent);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem}
  .locked-box p{font-size:.83rem;color:var(--accent);margin-bottom:.5rem}
  .locked-box .hint{font-size:.76rem;color:var(--muted);line-height:1.7}
  .blacklist{margin-top:.8rem;padding:.7rem 1rem;background:#0d1526;border-radius:8px;font-size:.72rem;color:var(--muted);line-height:1.9}
  .blacklist code{color:#ff9966;margin-right:.4rem}
  .result-box{background:var(--card);border:1px solid var(--green);border-radius:12px;padding:1.5rem;margin-top:1rem}
  .result-box h3{font-size:.8rem;color:var(--green);text-transform:uppercase;margin-bottom:.8rem}
  pre{background:#0d1526;border-radius:8px;padding:1rem;font-size:.78rem;overflow-x:auto;white-space:pre-wrap;word-break:break-all;max-height:400px;overflow-y:auto}
  .err{color:var(--red);font-size:.82rem;padding:.7rem 1rem;background:#1a0a0a;border:1px solid var(--red);border-radius:8px;margin-top:.5rem}
  .blocked-msg{color:#ff9966;font-size:.82rem;padding:.7rem 1rem;background:#1a1000;border:1px solid #ff9966;border-radius:8px;margin-top:.5rem}
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
  <h2>📝 PHP Template Renderer</h2>

  <?php if ($locked && $secret): ?>
  <div class="locked-box">
    <p>🔐 Wrong render secret.</p>
    <p class="hint">The render secret is stored in the internal configuration service.<br>Find a way to reach it.</p>
  </div>
  <?php elseif ($locked): ?>
  <div class="locked-box">
    <p>🔐 Renderer is locked.</p>
    <p class="hint">You need the internal render secret to unlock this feature.<br>
    Hint: the admin webhook tester can reach internal services…</p>
  </div>
  <?php endif; ?>

  <div class="form-card">
    <form method="POST">
      <label>Render Secret</label>
      <input type="text" name="secret" placeholder="Internal secret key required"
             value="<?= htmlspecialchars($secret) ?>">

      <label>PHP Template Code</label>
      <textarea name="template" rows="10"
        placeholder="Enter PHP code (without opening tag)&#10;e.g. echo 'Hello ' . strtoupper('world');"><?= htmlspecialchars($template) ?></textarea>

      <button type="submit">Render →</button>
    </form>

    <div class="blacklist">
      🚫 Blocked keywords:
      <code>system</code><code>exec</code><code>shell_exec</code><code>passthru</code>
      <code>popen</code><code>proc_open</code><code>file_get_contents</code>
      <code>readfile</code><code>fopen</code><code>include</code><code>require</code>
      <code>base64_decode</code><code>hex2bin</code><code>eval</code><code>assert</code>
      <code>call_user_func</code><code>create_function</code>
    </div>
  </div>

  <?php if ($blocked): ?>
  <p class="blocked-msg">⛔ Blocked keyword detected: <strong><?= htmlspecialchars($blocked) ?></strong></p>
  <?php endif; ?>

  <?php if ($result !== null): ?>
  <div class="result-box">
    <h3>Rendered Output</h3>
    <pre><?= htmlspecialchars($result) ?></pre>
  </div>
  <?php endif; ?>

  <?php if ($error): ?>
  <p class="err">⚠ <?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
</div>
</body>
</html>
