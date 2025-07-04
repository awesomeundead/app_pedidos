<?php

use FastRoute\RouteCollector;

return function(RouteCollector $route)
{
    $route->get('/logradouros', 'handlers/get/logradouros.php');

    $route->delete('/pedido/{id:\d+}', 'handlers/delete/pedido.php');
    $route->get('/pedido/{id:\d+}', 'handlers/get/pedido.php');
    $route->post('/pedido', 'handlers/post/pedido.php');
    $route->put('/pedido/{id:\d+}', 'handlers/put/pedido.php');

    // Query String | data={YYYY-MM || YYYY-MM-DD}
    $route->get('/pedidos', 'handlers/get/pedidos.php');
    // Query String tel={telefone}
    $route->get('/pedidos/cliente', 'handlers/get/pedidos_cliente.php');

    // Query String | disponibilidade={0 || 1}
    $route->get('/produtos', 'handlers/get/produtos.php');

    $route->put('/produto/{id:\d+}', 'handlers/put/produto.php');

    // Query String | data={YYYY-MM}
    $route->get('/vendas', 'handlers/get/vendas.php');
    // query string tel={(\d{2}) \d{4,5}-\d{4}}
    $route->get('/vendas/cliente', 'handlers/get/vendas_cliente.php');
    // Query String | data={YYYY-MM}
    $route->get('/vendas/dia', 'handlers/get/vendas_dia.php');
};