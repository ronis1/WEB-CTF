<?php
require_once __DIR__ . '/../jwt.php';
header('Content-Type: application/json');

$payload = get_payload();
if (!$payload) {
    http_response_code(401);
    echo json_encode(['error' => 'not authenticated']);
    exit;
}
echo json_encode($payload);
