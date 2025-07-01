<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/auth.php';
require ROOT_DIR . '/pdo.php';

$data = $_GET['data'] ?? '';

if (preg_match('/^\d{4}\-(0[1-9]|1[0-2])$/', $data))
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
    'data' => $data
];

$query = 'SELECT SUM(subtotal) AS subtotal, SUM(desconto) AS desconto, SUM(frete) AS frete, SUM(total) AS total, DATE_FORMAT(data, "%Y-%m-%d") AS data FROM pedidos
          WHERE DATE_FORMAT(data, "%Y-%m") = :data GROUP BY DATE_FORMAT(data, "%Y-%m-%d")';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);