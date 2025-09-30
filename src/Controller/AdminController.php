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
        
        // A diferença crucial: o primeiro parâmetro (id_usuario) é sempre NULL
        // para garantir que a busca seja em TODOS os usuários.
        $listaDeProtocolos = $this->protocoloRepositorio->search(null, $numero, $dataInicio, $dataFim);

        // Prepara as variáveis para a view
        $tituloDaPagina = "Painel do Administrador - Protocolos";
        $usuarioLogado = $this->usuarioLogado;
        $isAdmin = $this->isAdmin;
        $erro = null;
        
        // Chama o novo template de admin
        require __DIR__ . '/../../templates/admin/protocolos.php';

    } catch (Exception $e) {
        // Tratamento de erro
        $erro = $e->getMessage();
        $usuarioLogado = $this->usuarioLogado;
        $isAdmin = $this->isAdmin;
        require __DIR__ . '/../../templates/admin/protocolos.php';
    }
}
}