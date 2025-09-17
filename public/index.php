<?php

require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Model\Protocolo;

$protocolo = Protocolo::fromArray(
    [
        'numero' => '123456',
        'quantidadeDePaginas' => 15,
        'data' => (new DateTime('now', new DateTimeZone('America/Campo_Grande')))->format('Y-m-d H:i:s')
    ]);
var_dump($protocolo);

$array = $protocolo->toArray();
echo "Dados do Array: " . $array['numero'] . " " . $array['quantidadeDePaginas'] . " " . $array['data'];

echo "Hellow World!";