<?php
header('Content-Type: application/json');
echo json_encode([
    'service'   => 'NovaTech Internal Config API',
    'version'   => '3.0.1',
    'endpoints' => [
        '/'                  => 'GET  — this index',
        '/health.php'        => 'GET  — liveness probe',
        '/config-check.php'  => 'POST — validate XML configuration blob',
        '/diagnostics.php'   => 'GET  — system diagnostics',
    ],
    'note' => 'INTERNAL SERVICE — external access is not intended.',
], JSON_PRETTY_PRINT);
