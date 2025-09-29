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
        private ProtocoloRepositoryInterface $repositorio,
        private AuditService $auditoria
    ) {}

    /**
     * Valida os dados e registra um novo protocolo.
     * @throws Exception Se os dados forem inválidos.
     * @return Protocolo
     */
    public function registrarNovoProtocolo(string $id_usuario, string $numero, int $quantidade_paginas, string $observacoes): Protocolo
    {
        //validação se numero tem 6 dígitos e se foi digitado somente numerais
        if(strlen($numero) !== 6 || !ctype_digit($numero)){
            throw new Exception("O número do protocolo precisa ter exatamente 6 dígitos e possuir somente números");
        }

        if(strlen($observacoes) > 0 && strlen($observacoes) < 5){
            throw new Exception("O campo de observações precisa ter pelo menos 5 caracteres");
        }

        //validação da quantidade de páginas
        if($quantidade_paginas < 1){
            throw new Exception("A quantidade de páginas precisa ser maior que zero");
        }

        if(!is_null($this->repositorio->buscaPorNumero($numero))){
            throw new Exception("O número do protocolo informado já foi registrado");
        }

        //Instanciação de um novo objeto Protocolo para ser adicionado
        $uuid = Uuid::uuid4()->toString();
        $protocolo = new Protocolo(
            $uuid,
            $id_usuario,
            $numero,
            $quantidade_paginas,
            new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande')),
            $observacoes,
            null
        );

        //chama a função add para adicionar a instancia de protocolo que foi criada acima
        $this->repositorio->add($protocolo);

        $this->auditoria->registraAlteracao($protocolo->id(), $id_usuario, $protocolo->numero(), 'CRIAR');

        //NENHUMA EXCEÇÃO, RETORNA O PROTOCOLO CRIADO
        return $protocolo;
    }
    
    /**
     * Valida os dados e edita um protocolo.
     * @throws Exception Se os dados forem inválidos.
     * @return Protocolo
     */
    public function editarProtocolo(string $id_usuario, string $id, string $numero, int $quantidade_paginas, string $observacoes): void
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

        if($quantidade_paginas < 1){
            throw new Exception("A quantidade de páginas precisa ser maior que zero");
        }

        $protocolo = new Protocolo(
            $id,
            $id_usuario,
            $numero,
            $quantidade_paginas,
            $protocoloOriginal->data(),
            $observacoes,
            new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'))
        );

        //chama a função update para atualizar um protocolo usando a instancia de protocolo que foi criada acima
        $this->repositorio->update($protocolo);

        $this->auditoria->registraAlteracao($protocolo->id(), $id_usuario, $protocolo->numero(), 'EDIÇÃO');
    }

    /**
     * Deleta um protocolo
     * @throws Exception Se os dados forem inválidos.
     * @return void
     */
    
    public function alteraStatusProtocolo(string $id_usuario, string $id): void
    {
        $protocoloParaAlterar = $this->repositorio->buscaPorId($id);

        if ($protocoloParaAlterar === null) {
            throw new Exception("O protocolo com o ID informado não foi localizado.");
        }

        if($protocoloParaAlterar->deletadoEm() === null){
            $this->desativar($id_usuario, $id);
        } else {
            $this->reativar($id_usuario, $id);
        }
    }

    private function desativar(string $id_usuario, string $id): void
    {
        $protocoloParaDeletar = $this->repositorio->buscaPorId($id);

        if ($protocoloParaDeletar === null) {
            throw new Exception("O protocolo com o ID informado não foi localizado para exclusão.");
        }

        $numero_protocolo = $protocoloParaDeletar->numero();

        $this->repositorio->desativar($id);

        $this->auditoria->registraAlteracao($id, $id_usuario, $numero_protocolo, 'EXCLUSÃO');

    }

    private function reativar(string $id_usuario, string $id): void
    {
        $protocoloParaReativar = $this->repositorio->buscaPorId($id);

        if($protocoloParaReativar === null){
            throw new Exception("O protocolo não foi encontrado");
        }

        $numero_protocolo = $protocoloParaReativar->numero();

        $this->repositorio->reativar($id);

        $this->auditoria->registraAlteracao($id, $id_usuario, $numero_protocolo, 'REATIVAÇÃO');
    }
}