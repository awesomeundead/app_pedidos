<?php

require ROOT_DIR . '/pdo.php';

$query = "SELECT endereco FROM pedidos GROUP BY endereco";

$stmt = $pdo->prepare($query);
$stmt->execute();
$count = $stmt->rowCount();
$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($listing as $item)
{
    if (preg_match('/([\w\s\.]+),*/u', $item['endereco'], $matches))
    {
        $endereco[] = $matches[1];
    }
}

$result['logradouros'] = $endereco;

header('Content-Type: application/json; charset=utf-8');

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);