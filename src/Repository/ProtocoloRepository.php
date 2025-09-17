<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;

class ProtocoloRepository {

    public readonly string $caminhoArquivoJson;

    public function __construct(string $caminhoArquivoJson)
    {
        $this->caminhoArquivoJson = $caminhoArquivoJson;

        //Recebe caminho da pasta data, que contém o Arquivo JSON
        $diretorioDeDados = dirname($this->caminhoArquivoJson);

        //Verifica se a pasta data existe, se não existir cria a pasta
        if(!is_dir($diretorioDeDados)){
            mkdir($diretorioDeDados, 0755, true);
        }

        //Verifica se o arquivo protocolos.json existe, se não existir cria o arquivo e incializa ele vazio 
        if(!file_exists($this->caminhoArquivoJson)){
            file_put_contents($this->caminhoArquivoJson, '[]');
        }
    }

    public function addProtocolo(Protocolo $novoProtocolo): void
    {
        $conteudoJson = json_decode(file_get_contents($this->caminhoArquivoJson), true);
    }

}