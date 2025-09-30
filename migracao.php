<?php

// Carrega o autoloader para termos acesso a todas as nossas classes
require __DIR__ . '/vendor/autoload.php';

use Mateus\ProtocolTracker\Infrastructure\Persistence\ConnectionCreator;
use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositorySql;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use DateTimeZone;

// --- CONFIGURAÇÃO ---
// Altere estes valores de acordo com a sua necessidade
$caminhoArquivoJson = __DIR__ . '/data/protocolos.json'; // Caminho do JSON a ser importado
$idUsuarioPadrao = 2; // ID do usuário no banco SQL ao qual os protocolos serão associados
// --------------------


// --- INÍCIO DO SCRIPT ---

echo "Iniciando script de migração..." . PHP_EOL;

// 1. Validação dos Arquivos
if (!file_exists($caminhoArquivoJson)) {
    echo "Erro: Arquivo JSON não encontrado em '$caminhoArquivoJson'." . PHP_EOL;
    exit(1);
}

// 2. Conexão com o Banco de Dados
try {
    $pdo = ConnectionCreator::createConnection();
    $repositorioSql = new ProtocoloRepositorySql($pdo);
    echo "Conexão com o banco de dados SQLite estabelecida." . PHP_EOL;
} catch (\PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// 3. Leitura e Decodificação do JSON
$conteudoJson = file_get_contents($caminhoArquivoJson);
$dadosAntigos = json_decode($conteudoJson, true);

if (!is_array($dadosAntigos)) {
    echo "Erro: O arquivo JSON está vazio ou em um formato inválido." . PHP_EOL;
    exit(1);
}

echo count($dadosAntigos) . " registros encontrados no arquivo JSON. Iniciando a inserção..." . PHP_EOL;

// 4. Inserção dos Dados no Banco (Loop Principal)
$pdo->beginTransaction(); // Inicia uma transação para performance e segurança
$contadorSucesso = 0;
$contadorFalha = 0;

foreach ($dadosAntigos as $chave => $dadosProtocolo) {
    try {
        // Reutiliza a lógica do seu Model para criar o objeto
        $protocolo = new Protocolo(
            $dadosProtocolo['id'],
            $idUsuarioPadrao,
            $dadosProtocolo['numero'],
            (int)$dadosProtocolo['quantidadeDePaginas'],
            new DateTimeImmutable($dadosProtocolo['data']),
            null, // observacoes
            null, // alterado_em
            null  // deletado_em
        );
        
        // Reutiliza o método add do seu novo repositório
        $repositorioSql->add($protocolo);
        
        echo "Protocolo Nº " . $protocolo->numero() . " inserido com sucesso." . PHP_EOL;
        $contadorSucesso++;

    } catch (Exception $e) {
        // Se um registro falhar (ex: número duplicado), reporta o erro mas continua
        echo "ERRO ao inserir protocolo da chave '$chave': " . $e->getMessage() . PHP_EOL;
        $contadorFalha++;
    }
}

// 5. Finalização
if ($contadorFalha > 0) {
    // Se houve qualquer erro, desfaz todas as inserções para não deixar dados parciais.
    $pdo->rollBack();
    echo "\nATENÇÃO: A migração falhou para $contadorFalha registro(s). Nenhuma alteração foi salva no banco. Verifique os erros acima." . PHP_EOL;
} else {
    // Se tudo deu certo, confirma as inserções no banco.
    $pdo->commit();
    echo "\nMigração concluída com sucesso!" . PHP_EOL;
    echo "$contadorSucesso protocolos foram inseridos no banco de dados." . PHP_EOL;
}