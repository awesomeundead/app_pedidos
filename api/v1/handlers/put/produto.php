<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/pdo.php';

$id = $vars['id'];

$content = trim(file_get_contents('php://input'));
$_PUT = json_decode($content, true);

$categoria = trim($_PUT['categoria'] ?? '');
$nome = trim($_PUT['nome'] ?? '');
$descricao = trim($_PUT['descricao'] ?? '');
$imagem = trim($_PUT['imagem'] ?? '');
$disponibilidade = $_PUT['disponibilidade'] ?? '0';
$preco = $_PUT['preco'] ?? '';
$preco_desconto = $_PUT['preco_desconto'] ?? '';

if (empty($preco_desconto))
{
    $preco_desconto = null;
}

$query = 'UPDATE produtos SET
          categoria = :categoria,
          nome = :nome,
          descricao = :descricao,
          preco = :preco,
          preco_desconto = :preco_desconto,
          disponibilidade = :disponibilidade,
          imagem = :imagem
          WHERE id = :id';

$params = [
    'id' => $id,
    'categoria' => $categoria,
    'nome' => $nome,
    'descricao' => $descricao,
    'preco' => $preco,
    'preco_desconto' => $preco_desconto,
    'disponibilidade' => $disponibilidade,
    'imagem' => $imagem
];

$stmt = $pdo->prepare($query);
$result = $stmt->execute($params);

if ($stmt->rowCount() == 0)
{
    $result = false;
}

$json['status'] = $result ? 'success' : 'failure';

echo json_encode($json);