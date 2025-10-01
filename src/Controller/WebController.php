<?php

namespace Mateus\ProtocolTracker\Controller;

use DateTimeImmutable;
use DateTimeZone;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositoryInterface;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;
use Exception;
use Mateus\ProtocolTracker\Interface\ProtocoloRepositorySqlInterface;
use Mateus\ProtocolTracker\Interface\UsuarioRepositoryInterface;
use Mateus\ProtocolTracker\Model\Usuario;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;

class WebController
{
    private ?Usuario $usuarioLogado = null;
    private bool $isAdmin = false;

    public function __construct(
        private ProtocoloRepositorySqlInterface $repositorio,
        private DashboardService $dashboardService,
        private ProtocoloService $protocoloService,
        private UsuarioRepositoryInterface $usuarioRepository
    ) {
        $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($id_usuario) {
            $this->usuarioLogado = $this->usuarioRepository->buscaPorId($id_usuario);
        }
        
        if ($this->usuarioLogado && $this->usuarioLogado->permissao() === 'administrador') {
            $this->isAdmin = true;
        }
    }

    /**
     * URL_PATH = /home
     * REQUEST_METHOD = GET
     */
    public function home(?string $erro = null)
    {
        $tituloDaPagina = "Adicionar Novo Protocolo";
        $usuarioLogado = $this->usuarioLogado; // _content-header.php usa essa informação
        $isAdmin = $this->isAdmin; // _header.php usa essa informação

        require __DIR__ . '/../../templates/home.php';
    }

    /**
     * URL_PATH = /home
     * REQUEST_METHOD = POST
     */
    public function salvarNovoProtocolo()
    {
        try {
            $id_usuario = $this->usuarioLogado?->id();
            
            $numero = $_POST['numero'] ?? '';
            $quantidade_paginas = (int)($_POST['paginas'] ?? 0);
            $observacoes = $_POST['observacoes'] ?? '';
            
            $this->protocoloService->registrarNovoProtocolo($id_usuario, $numero, $quantidade_paginas, $observacoes);

            $_SESSION['mensagem_sucesso'] = "Protocolo adicionado com sucesso!";

            header('Location: /home');
            exit();

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Adicionar Novo Protocolo";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            
            require __DIR__ . '/../../templates/home.php';
        }
    }

    /**
     * URL_PATH = /busca
     * REQUEST_METHOD = GET
     */
    public function buscaProtocolo()
    {
        try {
            $numero = $_GET['numero'] ?? null;
            $dataInicio = !empty($_GET['data_inicio']) ? new DateTimeImmutable($_GET['data_inicio'] . ' 00:00:00') : null;
            $dataFim = !empty($_GET['data_fim']) ? new DateTimeImmutable($_GET['data_fim'] . ' 23:59:59') : null;

            $id_usuario = $this->usuarioLogado?->id();
            if ($this->isAdmin) {
                $id_usuario = null;
            }

            $listaDeProtocolos = $this->repositorio->search($id_usuario, $numero, $dataInicio, $dataFim);

            $tituloDaPagina = "Buscar Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            
            require __DIR__ . '/../../templates/busca.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Buscar Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            
            require __DIR__ . '/../../templates/busca.php';
        }
    }

    /**
     * URL_PATH = /editar
     * REQUEST_METHOD = POST
     */
    public function editarProtocolo()
    {
        try {
            $id_usuario = $this->usuarioLogado?->id();
            $id = $_POST['id'] ?? '';
            $numero = $_POST['numero'] ?? '';
            $quantidade_paginas = (int)($_POST['paginas'] ?? 0);
            $observacoes = $_POST['observacoes'] ?? '';

            $this->protocoloService->editarProtocolo($id_usuario, $id, $numero, $quantidade_paginas, $observacoes);
            $_SESSION['mensagem_sucesso'] = "Protocolo atualizado com sucesso!";

            header('Location: /busca');
            exit();

        } catch (Exception $e) {
            $this->exibirFormularioEdicao($e->getMessage());
        }
    }

    /**
     * URL_PATH = /editar
     * REQUEST_METHOD = GET
     */
    public function exibirFormularioEdicao(?string $erro = null)
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->notFound();
            return;
        }
        $protocolo = $this->repositorio->buscaPorId($id);

        if ($protocolo === null) {
            $this->notFound();
            return;
        }

        $tituloDaPagina = "Editando Protocolo";
        $usuarioLogado = $this->usuarioLogado;
        $isAdmin = $this->isAdmin;
        
        require __DIR__ . '/../../templates/editar.php';
    }

    /**
     * URL_PATH = /excluir
     * REQUEST_METHOD = POST
     */
    public function alteraStatusProtocolo()
    {
        try {
            $id_usuario = $this->usuarioLogado?->id();
            $id = $_POST['id'] ?? '';
            
            $this->protocoloService->alteraStatusProtocolo($id_usuario, $id);

            $_SESSION['mensagem_sucesso'] = "Status alterado com sucesso!";

            header('Location: /busca');
            exit();

        } catch (Exception $e) {

            $erro = $e->getMessage();
            $listaDeProtocolos = $this->repositorio->allByUser($id_usuario);
            $tituloDaPagina = "Busca de Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/busca.php';
        }
    }

    /**
     * URL_PATH = /dashboard
     * REQUEST_METHOD = GET
     */
    public function exibirDashboard()
    {
        try {
            $idUsuario = $this->usuarioLogado?->id();
            $diaSelecionado = !empty($_GET['dia']) ? new DateTimeImmutable($_GET['dia']) : new DateTimeImmutable('now');
            $mesSelecionado = !empty($_GET['mes']) ? new DateTimeImmutable($_GET['mes']) : new DateTimeImmutable('now');

            $totalPorDiaUsuario = $this->dashboardService->metricarPorUsuarioDia($idUsuario, $diaSelecionado);
            $totalPorMesUsuario = $this->dashboardService->metricarPorUsuarioMes($idUsuario, $mesSelecionado);
            $totalUsuario = $this->dashboardService->metricarPorUsuarioTotal($idUsuario);
            
            $tituloDaPagina = "Dashboard de Produtividade";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/dashboard.php';
            
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    public function notFound()
    {
        http_response_code(404);
        $tituloDaPagina = "Página Não Encontrada";
        $usuarioLogado = $this->usuarioLogado;
        $isAdmin = $this->isAdmin;

        require __DIR__ . '/../../templates/404.php';
    }
}