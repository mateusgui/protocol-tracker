<?php

namespace Mateus\ProtocolTracker\Service;

use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;
use Ramsey\Uuid\Uuid;
use Exception; // Usaremos para reportar erros de validação

final class ProtocoloService
{
    // Construtor padrão da classe, recebe um objeto do tipo ProtocoloRepository para inicializar a classe ProtocoloService
    public function __construct(
        private ProtocoloRepositoryInterface $repositorio
    ) {}

    /**
     * Valida os dados e registra um novo protocolo.
     * @throws Exception Se os dados forem inválidos.
     * @return Protocolo
     */
    public function registrarNovoProtocolo(string $numero, int $quantidadeDePaginas): Protocolo
    {
        //validação se numero tem 6 dígitos e se foi digitado somente numerais
        if(strlen($numero) !== 6 || !ctype_digit($numero)){
            throw new Exception("O número do protocolo precisa ter exatamente 6 dígitos e possuir somente números");
        }

        //validação da quantidade de páginas
        if($quantidadeDePaginas < 1){
            throw new Exception("A quantidade de páginas precisa ser maior que zero");
        }

        if(!is_null($this->repositorio->buscaPorNumero($numero))){
            throw new Exception("O número do protocolo informado já foi registrado");
        }

        //Instanciação de um novo objeto Protocolo para ser adicionado
        $uuid = Uuid::uuid4()->toString();
        $protocolo = new Protocolo(
            $uuid, //Geração automática do id no formato 'protocolo_uniqid'
            $numero, //Número que recebeu por parâmetro
            $quantidadeDePaginas, //Quantidade de páginas que recebeu por parâmetro
            new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande')) //Pegando a data e hora atual
        );

        //chama a função add para adicionar a instancia de protocolo que foi criada acima
        $this->repositorio->add($protocolo);

        //NENHUMA EXCEÇÃO, RETORNA O PROTOCOLO CRIADO
        return $protocolo;
    }
    
    /**
     * Valida os dados e edita um protocolo.
     * @throws Exception Se os dados forem inválidos.
     * @return Protocolo
     */
    public function editarProtocolo(string $id, string $numero, int $quantidadeDePaginas): Protocolo
    {
        //Busca o objeto Protocolo original que vai ser editado
        $protocoloOriginal = $this->repositorio->buscaPorId($id);

        if($protocoloOriginal === null){
            throw new Exception("O número do protocolo informado não foi localizado");
        }

        if($this->repositorio->buscaPorNumero($numero) && ($numero !== $protocoloOriginal->numero())){
            throw new Exception("Não é possível informar o número de um protocolo que já existe");
        }

        if(strlen($numero) !== 6 || !ctype_digit($numero)){
            throw new Exception("O número do protocolo precisa ter exatamente 6 dígitos e possuir somente números");
        }

        if($quantidadeDePaginas < 1){
            throw new Exception("A quantidade de páginas precisa ser maior que zero");
        }

        $protocoloAtualizado = new Protocolo(
            $id,
            $numero,
            $quantidadeDePaginas,
            $protocoloOriginal->data()
        );

        //chama a função update para atualizar um protocolo usando a instancia de protocolo que foi criada acima
        $this->repositorio->update($protocoloAtualizado);

        //NENHUMA EXCEÇÃO, RETORNA O PROTOCOLO EDITADO
        return $protocoloAtualizado;
    }

    /**
     * Deleta um protocolo
     * @throws Exception Se os dados forem inválidos.
     * @return void
     */
    public function deletarProtocolo(string $id): void
    {
        //Retornar true se a exclusão ocorrer e false caso não encontre o ID
        $sucesso = $this->repositorio->delete($id);

        //Se não houve exclusão lança uma exceção
        if (!$sucesso) {
            throw new Exception("O protocolo com o ID informado não foi localizado para exclusão.");
        }
    }
}