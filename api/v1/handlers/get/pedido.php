<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/pdo.php';

$id = $vars['id'];

$params = [
    'id' => $id
];

$query = 'SELECT * FROM pedidos WHERE id = :id';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result)
{
    $query = 'SELECT *, pedidos_itens.preco * pedidos_itens.quantidade AS subtotal FROM pedidos_itens
              INNER JOIN produtos ON produtos.id = pedidos_itens.id_produto
              WHERE pedidos_itens.id_pedido = :id';

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $result['itens'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else
{
    http_response_code(404);
    
    exit;
}

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);