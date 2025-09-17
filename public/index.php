<?php

$uri = $_SERVER['REQUEST_URI'];
//BARRANDO REQUISIÇÕES QUE NÃO SEJAM PARA A RAIZ
if ($uri !== '/' && pathinfo($uri, PATHINFO_EXTENSION) !== '') {
    return;
}

require __DIR__ . '/../vendor/autoload.php';

use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;

$protocolo_id = uniqid('protocolo_');

$protocolo = new Protocolo($protocolo_id, '123456', 30, new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande')));

echo "ID: " . $protocolo->id() . " | Número do protocolo: " . $protocolo->numero() . " | Quantidade de páginas: " . $protocolo->paginas() . " | Data de criação: " . $protocolo->data()->format('d/m/Y H:i');

$protocoloRepository = new ProtocoloRepository(__DIR__ . '/../data/protocolos.json');

$protocoloRepository->add($protocolo);

/* $protocolo = Protocolo::fromArray(
    [
        'id' => $protocolo_id,
        'numero' => '123456',
        'quantidadeDePaginas' => 15,
        'data' => (new DateTime('now', new DateTimeZone('America/Campo_Grande')))->format('Y-m-d H:i:s'),
    ]);
var_dump($protocolo);

$array = $protocolo->toArray();
echo "Dados do Array: " . $array['numero'] . " " . $array['quantidadeDePaginas'] . " " . $array['data']; */