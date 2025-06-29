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

$query = 'SELECT SUM(subtotal) AS subtotal, SUM(desconto) AS desconto, SUM(frete) AS frete, SUM(total) AS total FROM pedidos
          WHERE DATE_FORMAT(data, :pattern) = :data';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$query = 'SELECT COUNT(DISTINCT DATE(data)) FROM pedidos WHERE DATE_FORMAT(data, :pattern) = :data';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result['dias'] = $stmt->fetchColumn();

$query = 'SELECT pagamento AS tipo, SUM(total) AS valor FROM pedidos WHERE DATE_FORMAT(data, :pattern) = :data GROUP BY pagamento';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result['pagamento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = 'SELECT produtos.nome, produtos.imagem, SUM(pedidos_itens.quantidade) AS quantidade FROM pedidos_itens
          INNER JOIN produtos ON produtos.id = pedidos_itens.id_produto
          INNER JOIN pedidos ON pedidos.id = pedidos_itens.id_pedido
          WHERE DATE_FORMAT(pedidos.data, :pattern) = :data
          GROUP BY produtos.id';

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result['produtos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);