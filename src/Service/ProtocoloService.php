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
    public function __construct(
        private ProtocoloRepositoryInterface $repositorio
    ) {}

    /* DESCOMENTAR NA MIGRAÇÃO
    public function __construct(
        private ProtocoloRepositoryInterface $repositorio
        private AuditService $auditoria
    ) {}*/

    /**
     * Valida os dados e registra um novo protocolo.
     * @throws Exception Se os dados forem inválidos.
     * @return Protocolo
     */
    public function registrarNovoProtocolo(string $numero, int $quantidadeDePaginas): Protocolo
    {
        /* DESCOMENTAR NA MIGRAÇÃO
        public function registrarNovoProtocolo(string $idUsuario, string $numero, int $quantidadeDePaginas, string $observacoes): Protocolo*/

        //validação se numero tem 6 dígitos e se foi digitado somente numerais
        if(strlen($numero) !== 6 || !ctype_digit($numero)){
            throw new Exception("O número do protocolo precisa ter exatamente 6 dígitos e possuir somente números");
        }

        /* DESCOMENTAR NA MIGRAÇÃO
        if(strlen($observacoes) < 5){
            throw new Exception("O campo de observações precisa ter pelo menos 5 caracteres");
        }*/

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

        /*  DESCOMENTAR NA MIGRAÇÃO E APAGAR A CRIAÇÃO DE OBJETO ACIMA
            DADOS DEVEM CHEGAR JÁ TRATADOS (O QUE NÃO FOI INFORMADO PELO USUARIO DEVE VIR COMO VAZIO OU NULL)

            $protocolo = new Protocolo(
            $uuid,
            $idUsuario,
            $numero,
            $quantidadeDePaginas,
            new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande')),
            $observacoes
        ); */

        //chama a função add para adicionar a instancia de protocolo que foi criada acima
        $this->repositorio->add($protocolo);

        //Registro para auditoria
        /* DESCOMENTAR NA MIGRAÇÃO
        $this->auditoria->registraAlteracao($protocolo->id(), $idUsuario, $protocolo->numero(), 'CRIAR');
        */

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
        /* DESCOMENTAR NA MIGRAÇÃO
        public function editarProtocolo(string $idUsuario, string $id, string $numero, int $quantidadeDePaginas, string $observacoes): Protocolo
        */

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

        /*  DESCOMENTAR NA MIGRAÇÃO E APAGAR A CRIAÇÃO DE OBJETO ACIMA
            DADOS DEVEM CHEGAR JÁ TRATADOS (O QUE NÃO FOI INFORMADO PELO USUARIO DEVE VIR COMO VAZIO OU NULL)

            $protocolo = new Protocolo(
            $uuid,
            $idUsuario,
            $numero,
            $quantidadeDePaginas,
            $protocoloOriginal->data(),
            $observacoes,
            new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'))
        ); */

        //chama a função update para atualizar um protocolo usando a instancia de protocolo que foi criada acima
        $this->repositorio->update($protocoloAtualizado);

        //Registro para auditoria
        /* DESCOMENTAR NA MIGRAÇÃO
        $this->auditoria->registraAlteracao($protocolo->id(), $idUsuario, $protocolo->numero(), 'EDIÇÃO');
        */

        //NENHUMA EXCEÇÃO, RETORNA O PROTOCOLO EDITADO
        return $protocoloAtualizado;
    }

    /**
     * Deleta um protocolo
     * @throws Exception Se os dados forem inválidos.
     * @return void
     */

/*  DESCOMENTAR NA MIGRAÇÃO
    public function alteraStatusProtocolo(string $idUsuario, string $id): void
    {
        $protocoloParaAlterar = $this->repositorio->buscaPorId($id);

        if ($protocoloParaAlterar === null) {
            throw new Exception("O protocolo com o ID informado não foi localizado.");
        }

        if(isset($protocoloParaAlterar->deletadoEm())){
            $this->reativar($idUsuario, $id);

            $this->auditoria->registraAlteracao($id, $idUsuario, $numero_protocolo, 'EXCLUSÃO');
        } else {
            $this->desativar($idUsuario, $id);
        }
    } */

    public function desativar(string $id): void
    {
        /* DESCOMENTAR NA MIGRAÇÃO
        private function deletarProtocolo(string $idUsuario, string $id): void
        */

        $protocoloParaDeletar = $this->repositorio->buscaPorId($id);

        if ($protocoloParaDeletar === null) {
            throw new Exception("O protocolo com o ID informado não foi localizado para exclusão.");
        }

        $numero_protocolo = $protocoloParaDeletar->numero();

        $this->repositorio->delete($id);

        //Registro para auditoria
        /* DESCOMENTAR NA MIGRAÇÃO
        $this->auditoria->registraAlteracao($id, $idUsuario, $numero_protocolo, 'EXCLUSÃO');
        */
    }

    /* DESCOMENTAR NA MIGRAÇÃO
    /* private function reativar(string $idUsuario, string $id): void
    {
        $protocoloParaReativar = $this->repositorio->buscaPorId($id);

        if($protocolo === null){
            throw new Exception("O protocolo não foi encontrado");
        }

        $numero_protocolo = $protocoloParaReativar->numero();

        $this->repositorio->reativar();

        $this->auditoria->registraAlteracao($id, $idUsuario, $numero_protocolo, 'REATIVAÇÃO');
    }
    
    */
}