<?php

namespace Mateus\ProtocolTracker\Controller;

use DateTimeImmutable;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;
use Mateus\ProtocolTracker\Service\UsuarioService;
use Exception;
use Mateus\ProtocolTracker\Interface\ProtocoloRepositorySqlInterface;
use Mateus\ProtocolTracker\Interface\UsuarioRepositoryInterface;
use Mateus\ProtocolTracker\Model\Usuario;
use Mateus\ProtocolTracker\Repository\ProtocoloRepositorySql;
use Mateus\ProtocolTracker\Service\AuditService;
use Mateus\ProtocolTracker\Service\DashboardService;

class AdminController {

    private ?Usuario $usuarioLogado = null;
    private bool $isAdmin = false;


    public function __construct(
        private UsuarioRepositoryInterface $usuarioRepositorio,
        private UsuarioService $usuarioService,
        private ProtocoloRepositorySqlInterface $protocoloRepositorio,
        private DashboardService $dashboardService,
        private AuditService $auditService
    ) {
        $idUsuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($idUsuario) {
            $this->usuarioLogado = $this->usuarioRepositorio->buscaPorId($idUsuario);
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
            $numero = $_GET['numero'] ?? null;
            $dataInicio = !empty($_GET['data_inicio']) ? new DateTimeImmutable($_GET['data_inicio'] . ' 00:00:00') : null;
            $dataFim = !empty($_GET['data_fim']) ? new DateTimeImmutable($_GET['data_fim'] . ' 23:59:59') : null;
            
            $listaDeProtocolos = $this->protocoloRepositorio->all();

            $tituloDaPagina = "Painel do Administrador - Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            
            require __DIR__ . '/../../templates/admin/protocolos.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/home.php';
        }
    }

    public function listaUsuarios()
    {
        try {
            $listaDeUsuarios = $this->usuarioRepositorio->all();

            $tituloDaPagina = "Painel do Administrador - Usuários";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/admin/usuarios.php';
        } catch (\Throwable $th) {
            $erro = $e->getMessage();
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/home.php';
        }
    }

    public function exibirFormularioEdicaoCadastro()
    {
        try {
            $id_usuario = isset($_GET['id']) ? (int)$_GET['id'] : null; 
            $usuario = $this->usuarioRepositorio->buscaPorId($id_usuario);
            if ($usuario === null) {
                header('Location: /admin/usuarios');
                exit();
            }

            $tituloDaPagina = "Editar cadastro";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            $dadosDoFormulario = $_POST;

            require __DIR__ . '/../../templates/admin/editarCadastro.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $tituloDaPagina = "Controle de Protocolos";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;
            
            require __DIR__ . '/../../templates/admin/editarCadastro.php';
        }
    }

    public function atualizarDadosUsuario()
    {
        try {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $cpf = $_POST['cpf'] ?? '';
            $id_usuario = $this->usuarioRepositorio->buscaPorCpf($cpf)->id();

            $this->usuarioService->atualizarDadosCadastrais($id_usuario, $nome, $email, $cpf);

            $_SESSION['mensagem_sucesso'] = "Cadastro atualizado com sucesso!";

            header('Location: /admin/usuarios');
            exit();

        } catch (Exception $e) {
            //Está com BUG aqui
            $erro = $e->getMessage();
            $listaDeUsuarios = $this->usuarioRepositorio->all();

            $tituloDaPagina = "Painel do Administrador - Usuários";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/admin/usuarios.php';
        }
    }

    public function listaAuditoria()
    {
        try {
            $tituloDaPagina = "Painel do Administrador - Usuários";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            $listaAuditoria = $this->auditService->listaAuditoria();

            require __DIR__ . '/../../templates/admin/auditoria.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/home.php';
        }
    }

    public function dashboard()
    {
        try {
            $idUsuario = $this->usuarioLogado?->id();
            $diaSelecionado = !empty($_GET['dia']) ? new DateTimeImmutable($_GET['dia']) : new DateTimeImmutable('now');
            $mesSelecionado = !empty($_GET['mes']) ? new DateTimeImmutable($_GET['mes']) : new DateTimeImmutable('now');

            $totalPorDiaUsuario = $this->dashboardService->metricarPorUsuarioDia(null, $diaSelecionado);
            $totalPorMesUsuario = $this->dashboardService->metricarPorUsuarioMes(null, $mesSelecionado);
            $totalUsuario = $this->dashboardService->metricarPorUsuarioTotal(null);

            $tituloDaPagina = "Dashboard de Produtividade Geral";
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            $metricas = $this->dashboardService->getTodasAsMetricas();

            require __DIR__ . '/../../templates/admin/dashboard.php';

        } catch (Exception $e) {
            $erro = $e->getMessage();
            $usuarioLogado = $this->usuarioLogado;
            $isAdmin = $this->isAdmin;

            require __DIR__ . '/../../templates/home.php';
        }
    }
}