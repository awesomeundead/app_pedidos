<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/pdo.php';

$data = $_GET['data'] ?? '';

if (preg_match('/^\d{4}-(0[1-9]|1[0-2])-([0][1-9]|[12]\d|3[01])$/', $data))
{
    $pattern = "%Y-%m-%d";
}
elseif (preg_match('/^\d{4}\-(0[1-9]|1[0-2])$/', $data))
{
    $pattern = "%Y-%m";
}
else
{
    http_response_code(400);

    echo json_encode(['erro' => 'Data não informada ou inválida'], JSON_UNESCAPED_UNICODE);
    exit;
}

$params = [
    'data' => $data,
    'pattern' => $pattern
];
$query = 'SELECT * FROM pedidos WHERE DATE_FORMAT(data, :pattern) = :data';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result['pedidos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);