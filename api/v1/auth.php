<?php

$apikey = (require '../../config.php')['apikey'];

$headers = apache_request_headers();
$auth_header = $headers['Authorization'] ?? '';

if (!preg_match('/ApiKey\s(\S+)/', $auth_header, $matches))
{
    http_response_code(401);
    echo json_encode(['erro' => 'API Key não fornecida'], JSON_UNESCAPED_UNICODE);
    exit;
}

$auth_header_apikey = $matches[1];

if ($apikey !== $auth_header_apikey)
{
    http_response_code(403);
    echo json_encode(['erro' => 'API Key inválida'], JSON_UNESCAPED_UNICODE);
    exit;
}