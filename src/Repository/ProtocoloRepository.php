<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Protocolo;
use DateTimeImmutable; // Importa a classe para usar nos type hints

/**
 * Classe responsável pela persistência (leitura e escrita) dos dados de protocolos.
 * É a única classe que interage diretamente com o arquivo JSON.
 */
final class ProtocoloRepository implements ProtocoloRepositoryInterface
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

    //MÉTODOS PÚBLICOS BUSCA
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
     * Realiza uma busca combinada com múltiplos filtros.
     */
    public function search(?string $numero = null, ?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array
    {
        // Começa com a lista completa como base.
        $protocolos = $this->all();

        // Aplica o filtro de NÚMERO, se foi fornecido.
        if (!empty($numero)) {
            $protocolos = array_filter(
                $protocolos,
                fn(Protocolo $p) => $p->numero() === $numero
            );
        }

        // Aplica o filtro de DATA na lista JÁ FILTRADA.
        if ($dataInicio || $dataFim) {
            $protocolos = array_filter(
                $protocolos,
                function (Protocolo $p) use ($dataInicio, $dataFim) {
                    $dataProtocolo = $p->data();
                    if ($dataInicio && $dataProtocolo < $dataInicio) return false;
                    if ($dataFim && $dataProtocolo > $dataFim) return false;
                    return true;
                }
            );
        }

        // Retorna o resultado final e reindexado.
        return array_values($protocolos);
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
        //Chama a função all() desta classe para pegar a lista completa de Protocolos ordenada
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

    public function buscaPorId(string $id): ?Protocolo
    {
        //Chama a função all() desta classe para pegar a lista completa de Protocolos ordenada
        $listaDeProtocolos = $this->all();

        //Iterando a lista de protocolos e se achar um protocolo com número igual ao que está sendo procurado retorna aquele protocolo
        foreach($listaDeProtocolos as $protocolo){
            if($protocolo->id() === $id){
                return $protocolo;
            }
        }

        //Se na Iteração acima não for encontrado nenhum número que corresponda ao protocolo que está sendo procurado retorna null
        return null;
    }

    //MÉTODO PÚBLICO ESCRITA
    /**
     * Adiciona um novo objeto Protocolo ao arquivo de dados.
     */
    public function add(Protocolo $novoProtocolo): void
    {
        //Chama a função all() desta classe para pegar a lista completa de Protocolos ordenada
        $listaDeProtocolos = $this->all();

        array_unshift($listaDeProtocolos, $novoProtocolo); //Adiciona o $novoProtocolo à $listaDeProtocolos

        $this->salvarLista($listaDeProtocolos);
    }

    /**
     * Atualiza um protocolo existente no arquivo de dados.
     * @param Protocolo $protocoloParaAtualizar O objeto com o ID do alvo e os dados novos.
     * @return bool Retorna true se a atualização foi bem-sucedida, false se o protocolo não foi encontrado.
     */
    public function update(Protocolo $protocoloParaAtualizar): bool
    {
        //Chama a função all() desta classe para pegar a lista completa de Protocolos ordenada
        $listaDeProtocolos = $this->all();

        //Índice sendo criado com número inválido
        $indiceParaSubstituir = -1;

        //Procura pelo Protocolo com o mesmo ID do protocolo a ser atualizado.
        foreach($listaDeProtocolos as $indice => $protocolo){
            if($protocolo->id() === $protocoloParaAtualizar->id()){
                //Salva o índice encontrado para substituir
                 $indiceParaSubstituir = $indice;
                 break;
            }
        }

        //Se não encontrar nenhuma correspondência para atualizar chega aqui e retorna false para indicar que o update não ocorreu
        if($indiceParaSubstituir === -1){
            return false;
        }

        //Substitui o objeto $protocoloParaAtualizar no índice que foi encontrada a correpondência de ID
        $listaDeProtocolos[$indiceParaSubstituir] = $protocoloParaAtualizar;

        $this->salvarLista($listaDeProtocolos);

        return true;
    }

    /**
     * Deleta um protocolo pelo seu número. Para a funcionalidade de exclusão.
     * @return bool Retorna true se a exclusão foi bem-sucedida, false se o protocolo não foi encontrado.
     */
    public function delete(string $id): bool
    {
        //Chama a função all() desta classe para pegar a lista completa de Protocolos ordenada
        $listaDeProtocolos = $this->all();

        //Variável de controle para saber se a exclusão ocorreu ou não
        $encontrou = false;

        //Filtra o array $listaDeProtocolos e só adiciona ao array $listaAtualizada o que não tiver mesmo ID do que será excluído
        $listaAtualizada = array_filter($listaDeProtocolos, function(Protocolo $protocolo) use ($id, &$encontrou)
        {
            if($protocolo->id() !== $id){
                return true; //ID não corresponde, então é mantido em $listaAtualizada
            } else {
                $encontrou = true; //ID corresponde, então é excluído de $listaAtualizada e $encontrou é sinalizada como true
                return false;
            }
        });

        if ($encontrou) {
            // Só vai salvar se encontrou
            $this->salvarLista($listaAtualizada);
        }

        return $encontrou;
    }

    //MÉTODOS PRIVADOS
    /**
     * Pega um array de objetos Protocolo, converte e salva no arquivo JSON.
     * @param Protocolo[] $protocolos A lista completa de protocolos a ser salva.
     */
    private function salvarLista(array $protocolos): void
    {
        // Mapeia a lista de objetos para uma lista de arrays associativos
        $listaParaSalvar = array_map(
            fn(Protocolo $protocolo) => $protocolo->toArray(),
            $protocolos
        );

        // Converte para JSON e salva no arquivo
        $novoConteudoJson = json_encode($listaParaSalvar, JSON_PRETTY_PRINT);
        file_put_contents($this->caminhoArquivoJson, $novoConteudoJson, LOCK_EX);
    }
}