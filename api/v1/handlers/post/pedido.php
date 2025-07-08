<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/pdo.php';

$content = trim(file_get_contents('php://input'));
$dados = json_decode($content, true);

$nome = trim($dados['nome']);
$telefone = $dados['telefone'];
$endereco = trim($dados['endereco']);
$subtotal = $dados['subtotal'];
$desconto = $dados['desconto'];
$frete = $dados['frete'];
$total = $dados['total'];
$pagamento = $dados['pagamento'];

foreach ($dados['itens'] as $item)
{
    if (is_numeric($item['quantidade']) && $item['quantidade'] > 0)
    {
        $pedidos_itens[] = [
            'id' => $item['id'],
            'preco' => $item['preco'],
            'preco_desconto' => $item['preco_desconto'],
            'quantidade' => $item['quantidade']
        ];
    }
}

if (!empty($pedidos_itens))
{
    $query = 'INSERT INTO pedidos (nome, telefone, endereco, data, subtotal, desconto, frete, total, pagamento, status)
              VALUES (:nome, :telefone, :endereco, :data, :subtotal, :desconto, :frete, :total, :pagamento, :status)';

    $params = [
        'nome' => $nome,
        'telefone' => $telefone,
        'endereco' => $endereco,
        'data' => date('Y-m-d H:i:s'),
        'subtotal' => $subtotal,
        'desconto' => $desconto,
        'frete' => $frete,
        'total' => $total,
        'pagamento' => $pagamento,
        'status' => 'Preparando'
    ];

    $stmt = $pdo->prepare($query);
    $result = $stmt->execute($params);

    if ($result)
    {
        $id_pedido = $pdo->lastInsertId();

        foreach ($pedidos_itens as $itens)
        {
            $query = 'INSERT INTO pedidos_itens (id_pedido, id_produto, preco, preco_desconto, quantidade)
                    VALUES (:id_pedido, :id_produto, :preco, :preco_desconto, :quantidade)';

            $params = [
                'id_pedido' => $id_pedido,
                'id_produto' => $itens['id'],
                'preco' => $itens['preco'],
                'preco_desconto' => $itens['preco_desconto'],
                'quantidade' => $itens['quantidade']
            ];

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
        }
    }
}

$json['status'] = ($result ?? false) ? 'success' : 'failure';

echo json_encode($json);