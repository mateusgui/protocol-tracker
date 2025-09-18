<?php

namespace Mateus\ProtocolTracker\Service;

use Mateus\ProtocolTracker\Model\Protocolo;
use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use DateTimeImmutable;
use DateTimeZone;
use Exception; // Usaremos para reportar erros de validação

final class ProtocoloService
{
    // Construtor padrão da classe, recebe um objeto do tipo ProtocoloRepository para inicializar a classe ProtocoloService
    public function __construct(
        private ProtocoloRepository $repositorio
    ) {}

    /**
     * Valida os dados e registra um novo protocolo.
     * @throws Exception Se os dados forem inválidos.
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

        // 2. Lógica de Criação do Objeto
        //Instanciação de um novo protocolo para ser adicionado
        $protocolo = new Protocolo(
            uniqid('protocolo_'), //Geração automática do id no formato 'protocolo_uniqid'
            $numero, //Número que recebeu por parâmetro
            $quantidadeDePaginas, //Quantidade de páginas que recebeu por parâmetro
            new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande')) //Pegando a data e hora atual
        );

        //chama a função add para adicionar a instancia de protocolo que foi criada acima
        $this->repositorio->add($protocolo);

        //NENHUMA EXCEÇÃO, RETORNA O PROTOCOLO CRIADO
        return $protocolo;
    }
    
    // No futuro, métodos como editarProtocolo() e deletarProtocolo() poderiam vir aqui.
}