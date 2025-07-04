<?php

header('Content-Type: application/json; charset=utf-8');

require ROOT_DIR . '/pdo.php';

$params = [];
$query = 'SELECT * FROM produtos';

if (isset($_GET['disponibilidade']) && is_numeric($_GET['disponibilidade']))
{
    $params['disponibilidade'] = $_GET['disponibilidade'];
    $query = 'SELECT * FROM produtos WHERE disponibilidade = :disponibilidade';
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$result['produtos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);