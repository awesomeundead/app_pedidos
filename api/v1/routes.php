<?php

use FastRoute\RouteCollector;

return function(RouteCollector $route)
{
    $route->get('/clientes', 'handlers/get/clientes.php');
    $route->get('/logradouros', 'handlers/get/logradouros.php');

    $route->delete('/pedido/{id:\d+}', 'handlers/delete/pedido.php');
    $route->get('/pedido/{id:\d+}', 'handlers/get/pedido.php');
    $route->post('/pedido', 'handlers/post/pedido.php');
    $route->put('/pedido/{id:\d+}', 'handlers/put/pedido.php');

    $route->get('/pedidos', 'handlers/get/pedidos.php');
    $route->get('/pedidos/cliente', 'handlers/get/pedidos_cliente.php');
    $route->get('/produtos', 'handlers/get/produtos.php');
    $route->get('/vendas', 'handlers/get/vendas.php');
    $route->get('/vendas/dia', 'handlers/get/vendas_dia.php');

    /*
    $route->addGroup('/pedido', function (RouteCollector $route)
    {
        $route->delete('/{id:\d+}', 'handlers/delete/pedido.php');
        $route->get('/{id:\d+}', 'handlers/get/pedido.php');
        $route->post('', 'handlers/post/pedido.php');
        $route->put('/{id:\d+}', 'handlers/put/pedido.php');
    });
    */
};