<?php

namespace Mateus\ProtocolTracker\Controller;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;
use Mateus\ProtocolTracker\Service\LoginService;
use Mateus\ProtocolTracker\Service\UsuarioService;
use Exception;
use Mateus\ProtocolTracker\Model\Usuario;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositorySql;

class AdminController {

    private ?Usuario $usuarioLogado = null;
    private bool $isAdmin = false;


    public function __construct(
        private UsuarioRepository $repositorio,
        private UsuarioService $usuarioService,
        private ProtocoloRepositorySql $protocoloRepositorio
    ) {
        $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($idUsuario) {
            $this->usuarioLogado = $this->repositorio->buscaPorId($idUsuario);
        }
        
        if ($this->usuarioLogado && $this->usuarioLogado->permissao() === 'administrador') {
            $this->isAdmin = true;
        }
    }

    /**
     * URL_PATH = /admin/protocolos
     * REQUEST_METHOD = GET
     */
    public function exibirPainelAdmin()
    {
        try {
            // Pega os mesmos filtros da página de busca
            $numero = $_GET['numero'] ?? null;
            $dataInicio = !empty($_GET['data_inicio']) ? new DateTimeImmutable($_GET['data_inicio'] . ' 00:00:00') : null;
            $dataFim = !empty($_GET['data_fim']) ? new DateTimeImmutable($_GET['data_fim'] . ' 23:59:59') : null;
            
            $listaDeProtocolos = $this->protocoloRepositorio->all();

            $tituloDaPagina = "Painel do Administrador - Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            $erro = null;
            
            require __DIR__ . '/../../templates/admin/protocolos.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/home.php';
        }
    }
}