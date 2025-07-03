<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/pdo.php';

$telefone = $_GET['tel'] ?? null;

if (!preg_match('/^\(([1-9]{2})\)\s(\d{4,5})\-(\d{4})$/', $telefone))
{
    http_response_code(400);

    echo json_encode(['erro' => 'Telefone não informado ou inválido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$params = [
    'telefone' => $telefone
];

$query = 'SELECT COUNT(*) FROM pedidos WHERE telefone = :telefone';
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result = $stmt->fetchColumn();

if (!$result)
{
    echo json_encode(['mensagem' => 'Nenhum pedido encontrado para este telefone'], JSON_UNESCAPED_UNICODE);
    exit;
}

$query = 'SELECT SUM(subtotal) AS subtotal, SUM(desconto) AS desconto, SUM(frete) AS frete, SUM(total) AS total FROM pedidos
          WHERE telefone = :telefone';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$query = 'SELECT produtos.nome, produtos.imagem, SUM(pedidos_itens.quantidade) AS quantidade FROM pedidos_itens
          INNER JOIN produtos ON produtos.id = pedidos_itens.id_produto
          INNER JOIN pedidos ON pedidos.id = pedidos_itens.id_pedido
          WHERE telefone = :telefone
          GROUP BY produtos.id';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result['produtos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);