<div class="content-header">
    <div class="header-title">
        <h2><?= isset($tituloDaPagina) ? htmlspecialchars($tituloDaPagina) : 'Página sem Título' ?></h2>
    </div>
    <div class="header-actions">
        
        <?php if (isset($usuarioLogado) && $usuarioLogado): ?>
            <div class="welcome-message">
                <span>Bem-vindo, <strong><?= htmlspecialchars($usuarioLogado->nome()) ?></strong>!</span>
            </div>
        <?php endif; ?>

        <a href="#" id="theme-toggle" class="action-icon" title="Mudar Tema">
            <span class="material-icons-outlined">dark_mode</span>
        </a>

        <div class="dropdown">
            <a href="#" id="user-menu-toggle" class="action-icon" title="Perfil do Usuário">
                <span class="material-icons-outlined">account_circle</span>
            </a>
            <div id="user-menu" class="dropdown-menu">
                <a href="/editar-cadastro"><span class="material-icons-outlined">edit</span> Editar Cadastro</a>
                <a href="/editar-senha"><span class="material-icons-outlined">key</span> Alterar Senha</a>
                <div class="dropdown-divider"></div>
                <a href="/logout"><span class="material-icons-outlined">logout</span> Sair</a>
            </div>
        </div>
        
    </div>
</div>