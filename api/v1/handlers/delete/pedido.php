<?php

require ROOT_DIR . '/pdo.php';

$id = $vars['id'];
$params = [
    'id' => $id
];

$query = 'DELETE FROM pedidos_itens WHERE id_pedido = :id';
$stmt = $pdo->prepare($query);
$result = $stmt->execute($params);

if ($result)
{
    $query = 'DELETE FROM pedidos WHERE id = :id';
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute($params);
}

header('Content-Type: application/json; charset=utf-8');

$json['status'] = $result ? 'success' : 'failure';

echo json_encode($json);