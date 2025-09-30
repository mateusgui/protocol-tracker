<?php

namespace Mateus\ProtocolTracker\Controller;

use DateTimeImmutable;
use DateTimeZone;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositoryInterface;
use Mateus\ProtocolTracker\Service\DashboardService;
use Mateus\ProtocolTracker\Service\ProtocoloService;
use Exception;
use Mateus\ProtocolTracker\Model\Usuario;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;

class WebController
{
    private ?Usuario $usuarioLogado = null;
    private bool $isAdmin = false;

    public function __construct(
        private ProtocoloRepositoryInterface $repositorio,
        private DashboardService $dashboardService,
        private ProtocoloService $protocoloService,
        private UsuarioRepository $usuarioRepository
    ) {
        $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($idUsuario) {
            $this->usuarioLogado = $this->usuarioRepository->buscaPorId($idUsuario);
        }
        
        if ($this->usuarioLogado && $this->usuarioLogado->permissao() === 'administrador') {
            $this->isAdmin = true;
        }
    }

    public function home()
    {
        $tituloDaPagina = "Adicionar Novo Protocolo";
        $usuarioLogado = $this->usuarioLogado;
        $isAdmin = $this->isAdmin;

        require __DIR__ . '/../../templates/home.php';
    }

    public function salvarNovoProtocolo()
    {
        try {
            $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
            if ($id_usuario === null) {
                throw new Exception("Usuário não autenticado para realizar esta ação.");
            }
            
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

    public function buscaProtocolo()
    {
        try {
            $numero = $_GET['numero'] ?? null;
            $dataInicio = !empty($_GET['data_inicio']) ? new DateTimeImmutable($_GET['data_inicio'] . ' 00:00:00') : null;
            $dataFim = !empty($_GET['data_fim']) ? new DateTimeImmutable($_GET['data_fim'] . ' 23:59:59') : null;

            $idUsuarioParaBusca = $this->usuarioLogado?->id();
            if ($this->isAdmin) {
                $idUsuarioParaBusca = null;
            }

            $listaDeProtocolos = $this->repositorio->search($idUsuarioParaBusca, $numero, $dataInicio, $dataFim);

            $tituloDaPagina = "Buscar Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            $erro = null;
            
            require __DIR__ . '/../../templates/busca.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $listaDeProtocolos = []; // Em caso de erro na busca, retorna uma lista vazia
            $tituloDaPagina = "Buscar Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            
            require __DIR__ . '/../../templates/busca.php';
        }
    }

    public function editarProtocolo()
    {
        try {
            $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
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

    public function exibirFormularioEdicao(?string $erro = null)
    {
        $id = $_GET['id'] ?? $_POST['id'] ?? null; // Pega o ID do GET ou do POST (em caso de erro)

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

    public function alteraStatusProtocolo()
    {
        try {
            $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
            $id = $_POST['id'] ?? '';
            
            $this->protocoloService->alteraStatusProtocolo($id_usuario, $id);
            $_SESSION['mensagem_sucesso'] = "Status alterado com sucesso!";

            header('Location: /busca');
            exit();

        } catch (Exception $e) {
            // Em caso de erro, recarrega a página de busca com a mensagem de erro
            $erro = $e->getMessage();
            $listaDeProtocolos = $this->repositorio->all();
            $tituloDaPagina = "Busca de Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/busca.php';
        }
    }

    public function exibirDashboard()
    {
        try {
            $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
            $diaSelecionado = !empty($_GET['dia']) ? new DateTimeImmutable($_GET['dia']) : new DateTimeImmutable('now');
            $mesSelecionado = !empty($_GET['mes']) ? new DateTimeImmutable($_GET['mes']) : new DateTimeImmutable('now');

            $totalPorDiaUsuario = $this->dashboardService->metricarPorUsuarioDia($idUsuario, $diaSelecionado);
            $totalPorMesUsuario = $this->dashboardService->metricarPorUsuarioMes($idUsuario, $mesSelecionado);
            $totalUsuario = $this->dashboardService->metricarPorUsuarioTotal($idUsuario);
            
            $metricas = $this->dashboardService->getTodasAsMetricas(); // Você pode querer um dashboard só de admin
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

    /**
     * Método auxiliar privado para renderizar views, passando dados comuns.
     */
    private function view(string $templateNome, array $dados = []): void
    {
        // Adiciona dados globais a todas as views
        $dados['usuarioLogado'] = $this->usuarioLogado;
        $dados['isAdmin'] = $this->isAdmin;
        
        extract($dados);
        require __DIR__ . "/../../templates/{$templateNome}";
    }
}