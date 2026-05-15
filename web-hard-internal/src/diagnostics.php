<?php
header('Content-Type: application/json');
echo json_encode([
    'hostname' => gethostname(),
    'php'      => phpversion(),
    'cwd'      => getcwd(),
    'pid'      => getmypid(),
], JSON_PRETTY_PRINT);
