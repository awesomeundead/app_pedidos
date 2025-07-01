<?php

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? '';

if (!preg_match('/ApiKey\s(\S+)/', $authHeader, $matches))
{
    http_response_code(401);
    echo json_encode(['erro' => 'API Key não fornecida'], JSON_UNESCAPED_UNICODE);
    exit;
}

$apiKey = $matches[1];

if ($apiKey !== '0123456789')
{
    http_response_code(403);
    echo json_encode(['erro' => 'API Key inválida'], JSON_UNESCAPED_UNICODE);
    exit;
}