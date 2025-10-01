<?php

// Carrega o autoloader para termos acesso às nossas classes
require __DIR__ . '/vendor/autoload.php';

use Mateus\ProtocolTracker\Infrastructure\Persistence\ConnectionCreator;

// --- CONFIGURAÇÃO ---
// Lista dos bancos de dados de ORIGEM que serão lidos
$bancosDeOrigem = [
    __DIR__ . '/db_bira.sqlite',
    __DIR__ . '/db_leandro.sqlite',
    __DIR__ . '/db_tania.sqlite',
];

// Caminho do banco de dados de DESTINO
$caminhoBancoDestino = __DIR__ . '/data/db.sqlite';
// --------------------


// --- INÍCIO DO SCRIPT ---

echo "========================================\n";
echo "Iniciando script de unificação de dados\n";
echo "========================================\n\n";

try {
    // Conecta-se ao banco de dados de DESTINO
    $pdoDestino = new PDO('sqlite:' . $caminhoBancoDestino);
    $pdoDestino->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão com o banco de dados principal estabelecida.\n";
} catch (\PDOException $e) {
    echo "[ERRO FATAL] Não foi possível conectar ao banco de dados de destino: " . $e->getMessage() . "\n";
    exit(1);
}

// Inicia a transação no banco de destino
$pdoDestino->beginTransaction();

$totalProtocolosMigrados = 0;

try {
    // Loop para processar cada banco de dados de origem
    foreach ($bancosDeOrigem as $caminhoBancoOrigem) {
        if (!file_exists($caminhoBancoOrigem)) {
            echo "[AVISO] Arquivo de banco de dados não encontrado: $caminhoBancoOrigem. Pulando...\n";
            continue;
        }

        echo "\n--- Processando banco: $caminhoBancoOrigem ---\n";

        // Conecta-se ao banco de dados de ORIGEM
        $pdoOrigem = new PDO('sqlite:' . $caminhoBancoOrigem);
        $pdoOrigem->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Busca todos os protocolos do banco de origem
        $stmtOrigem = $pdoOrigem->query('SELECT * FROM protocolos;');
        $protocolosOrigem = $stmtOrigem->fetchAll(PDO::FETCH_ASSOC);

        if (empty($protocolosOrigem)) {
            echo "Nenhum protocolo encontrado neste banco. Pulando...\n";
            continue;
        }

        // Prepara a query de inserção no banco de DESTINO uma única vez (para performance)
        $sqlInsert = "INSERT OR IGNORE INTO protocolos (id, id_usuario, numero, quantidade_paginas, observacoes, criado_em, alterado_em, deletado_em) 
                      VALUES (:id, :id_usuario, :numero, :quantidade_paginas, :observacoes, :criado_em, :alterado_em, :deletado_em);";
        $stmtDestino = $pdoDestino->prepare($sqlInsert);

        // Loop para inserir cada protocolo no banco de destino
        foreach ($protocolosOrigem as $protocolo) {
            $stmtDestino->execute([
                ':id' => $protocolo['id'],
                ':id_usuario' => $protocolo['id_usuario'],
                ':numero' => $protocolo['numero'],
                ':quantidade_paginas' => $protocolo['quantidade_paginas'],
                ':observacoes' => $protocolo['observacoes'],
                ':criado_em' => $protocolo['criado_em'],
                ':alterado_em' => $protocolo['alterado_em'],
                ':deletado_em' => $protocolo['deletado_em'],
            ]);

            // rowCount() retorna 1 se a inserção ocorreu, 0 se foi ignorada (por já existir)
            if ($stmtDestino->rowCount() > 0) {
                echo "Protocolo Nº " . $protocolo['numero'] . " inserido.\n";
                $totalProtocolosMigrados++;
            } else {
                echo "Protocolo Nº " . $protocolo['numero'] . " já existe no banco principal. Ignorando...\n";
            }
        }
    }

    // Se tudo correu bem, efetiva as alterações
    $pdoDestino->commit();
    echo "\n========================================\n";
    echo "Migração concluída com sucesso!\n";
    echo "$totalProtocolosMigrados novos protocolos foram adicionados ao banco de dados principal.\n";
    echo "========================================\n";

} catch (Exception $e) {
    // Se qualquer erro ocorrer, desfaz todas as alterações
    $pdoDestino->rollBack();
    echo "\n[ERRO FATAL] Ocorreu um erro durante a migração. Todas as alterações foram desfeitas.\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    exit(1);
}