<?php

require ROOT_DIR . '/pdo.php';

$itens_id = $_POST['itens_id'];
$itens_preco = $_POST['itens_preco'];
$itens_preco_desconto = $_POST['itens_preco_desconto'];
$itens_quantidade = $_POST['itens_quantidade'];

$nome = trim($_POST['nome']);
$telefone = $_POST['telefone'];
$endereco = trim($_POST['endereco']);
$subtotal = $_POST['subtotal'];
$desconto = $_POST['desconto'];
$frete = $_POST['frete'];
$total = $_POST['total'];
$pagamento = $_POST['pagamento'];

foreach ($itens_quantidade as $index => $value)
{
    if (is_numeric($value) && $value > 0)
    {
        $pedidos_itens[] = [
            'id' => $itens_id[$index],
            'preco' => $itens_preco[$index],
            'preco_desconto' => $itens_preco_desconto[$index],
            'quantidade' => $itens_quantidade[$index]
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

header('Content-Type: application/json; charset=utf-8');

$json['status'] = ($result ?? false) ? 'success' : 'failure';

echo json_encode($json);