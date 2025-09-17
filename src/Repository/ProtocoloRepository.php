<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;
use DateTimeImmutable; // Importa a classe para usar nos type hints

/**
 * Classe responsável pela persistência (leitura e escrita) dos dados de protocolos.
 * É a única classe que interage diretamente com o arquivo JSON.
 */
final class ProtocoloRepository
{
    public function __construct(
        private readonly string $caminhoArquivoJson
    ) {
        //Recebe caminho da pasta data, que contém o Arquivo JSON
        $diretorioDeDados = dirname($this->caminhoArquivoJson);

        //Verifica se a pasta data existe, se não existir cria a pasta
        if (!is_dir($diretorioDeDados)) {
            mkdir($diretorioDeDados, 0755, true);
        }

        //Verifica se o arquivo protocolos.json existe, se não existir cria o arquivo e incializa ele vazio
        if (!file_exists($this->caminhoArquivoJson)) {
            file_put_contents($this->caminhoArquivoJson, '[]');
        }
    }

    // --- MÉTODOS PÚBLICOS DE LEITURA ---

    /**
     * Retorna TODOS os protocolos do arquivo.
     * @return Protocolo[]
     */
    public function all(): array
    {
        // Lógica para ler o arquivo, decodificar o JSON e transformar em um array de objetos Protocolo.
        $conteudoJsonBruto = json_decode(file_get_contents($this->caminhoArquivoJson), true);
        /*[
            0 => ['numero' => '111111', 'quantidadeDePaginas' => 10, 'data' => '...'],
            1 => ['numero' => '222222', 'quantidadeDePaginas' => 25, 'data' => '...'],
            ...
        ]*/

        // Se a decodificação falhou ou o arquivo estava vazio, retorna um array vazio.
        if (!is_array($conteudoJsonBruto)) {
            return [];
        }

        // Aplica a função fromArray em cada array associativo contido no array numérico $conteudoJsonBruto e recebe de volta um array de objetos do tipo Protocolo
        $listaDeProtocolos = array_map([Protocolo::class, 'fromArray'], $conteudoJsonBruto);
        
        // Ordenando os Protocolos por data, usando o getter data()
        usort($listaDeProtocolos, function (Protocolo $a, Protocolo $b)
        {
            return $b->data() <=> $a->data();
        });

        return $listaDeProtocolos;
    }

    /**
     * Busca protocolos dentro de um intervalo de datas específico.
     * Este método será a base para os cálculos de "DIA CORRENTE" e "MÊS CORRENTE".
     * @return Protocolo[]
     */
    public function buscaPorPeriodo(?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array
    {
        //Chama a função all() desta classe para pegar a lista completa de Protocolos ordenada
        $listaDeProtocolos = $this->all();

        $protocolosFiltrados = array_filter($listaDeProtocolos, function(Protocolo $protocolo) use ($dataInicio, $dataFim)
        {
            $dataProtocolo = $protocolo->data();

            if($dataInicio !== null && $dataProtocolo < $dataInicio){
                return false; //Não é inserido no array $protocolosFiltrados
            }

            if($dataFim !==null && $dataProtocolo > $dataFim){
                return false; //Não é inserido no array $protocolosFiltrados
            }

            //Passou nas verificações e deve ser inserido no array $protocolosFiltrados
            return true;
        });

        //reindexando o novo array com os protocolos filtrados para garantir que os índices sejam sequenciais
        return array_values($protocolosFiltrados);
    }

    /**
     * Busca um único protocolo pelo seu número.
     * @return Protocolo|null
     */
    public function buscaPorNumero(string $numero): ?Protocolo
    {
        $listaDeProtocolos = $this->all();

        //Iterando a lista de protocolos e se achar um protocolo com número igual ao que está sendo procurado retorna aquele protocolo
        foreach($listaDeProtocolos as $protocolo){
            if($protocolo->numero() === $numero){
                return $protocolo;
            }
        }

        //Se na Iteração acima não for encontrado nenhum número que corresponda ao protocolo que está sendo procurado retorna null
        return null;
    }

    // --- MÉTODOS PÚBLICOS DE ESCRITA ---

    /**
     * Adiciona um novo objeto Protocolo ao arquivo de dados.
     */
    public function add(Protocolo $novoProtocolo): void
    {
        // Lógica do ciclo "Ler > Modificar > Escrever" que já discutimos.
        $listaDeProtocolos = $this->all();

        array_unshift($listaDeProtocolos, $novoProtocolo); //Adiciona o $novoProtocolo à $listaDeProtocolos

        //Mapea a $listaDeProtocolos e salva ela como um array associativo usando a função toArray para cada Protocolo iterado
        $listaParaSalvar = array_map(function(Protocolo $protocolo){
            return $protocolo->toArray();
        },$listaDeProtocolos);

        //Converte $listaParaSalvar de um array associativo para um JSON contendo os dados atualizados e formatados com JSON_PRETTY_PRINT
        $novoConteudoJson = json_encode($listaParaSalvar,JSON_PRETTY_PRINT);
        
        //Salva a string JSON atualizada, sobrescrevendo o conteúdo do arquivo
        file_put_contents($this->caminhoArquivoJson, $novoConteudoJson, LOCK_EX);
    }

    /**
     * Atualiza um protocolo existente. Para a funcionalidade de edição.
     * @return bool Retorna true se a atualização foi bem-sucedida, false se o protocolo não foi encontrado.
     */
    public function update(Protocolo $protocoloParaAtualizar): bool
    {
        // Lógica para ler tudo, encontrar o protocolo pelo número, substituí-lo no array
        // e salvar o array completo de volta.
        return false; // Retorno de exemplo
    }

    /**
     * Deleta um protocolo pelo seu número. Para a funcionalidade de exclusão.
     * @return bool Retorna true se a exclusão foi bem-sucedida, false se o protocolo não foi encontrado.
     */
    public function delete(string $numero): bool
    {
        // Lógica para ler tudo, criar um novo array sem o protocolo a ser deletado (array_filter)
        // e salvar o novo array de volta.
        return false; // Retorno de exemplo
    }
}