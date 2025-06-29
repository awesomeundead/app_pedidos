<?php

require ROOT_DIR . '/pdo.php';

$id = $vars['id'];

$content = trim(file_get_contents('php://input'));
$_PUT = json_decode($content, true);

$nome = trim($_PUT['nome'] ?? '');
$telefone = $_PUT['telefone'] ?? '';
$endereco = trim($_PUT['endereco'] ?? '');
$desconto = $_PUT['desconto'] ?? '';
$frete = $_PUT['frete'] ?? '';
$total = $_PUT['total'] ?? '';
$pagamento = $_PUT['pagamento'] ?? '';
$status = $_PUT['status'] ?? '';

$query = 'UPDATE pedidos SET
          nome = :nome,
          telefone = :telefone,
          endereco = :endereco,
          desconto = :desconto,
          frete = :frete,
          total = :total,
          pagamento = :pagamento,
          status = :status
          WHERE id = :id';

$params = [
    'id' => $id,
    'nome' => $nome,
    'telefone' => $telefone,
    'endereco' => $endereco,
    'desconto' => $desconto,
    'frete' => $frete,
    'total' => $total,
    'pagamento' => $pagamento,
    'status' => $status
];

$stmt = $pdo->prepare($query);
$result = $stmt->execute($params);

header('Content-Type: application/json; charset=utf-8');

$json['status'] = $result ? 'success' : 'failure';

echo json_encode($json);