<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/pdo.php';

$telefone = $_GET['tel'] ?? '';

if (!preg_match('/^\(([1-9]{2})\)\s(\d{4,5})\-(\d{4})$/', $telefone))
{
    http_response_code(400);

    echo json_encode(['erro' => 'Telefone não informado ou inválido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$params = [
    'telefone' => $_GET['tel']
];
$query = 'SELECT * FROM pedidos WHERE telefone = :telefone';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result['pedidos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);