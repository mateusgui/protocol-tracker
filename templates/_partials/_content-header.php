<div class="content-header">
    <div class="header-title">
        <h2><?= isset($tituloDaPagina) ? htmlspecialchars($tituloDaPagina) : 'Página sem Título' ?></h2>
    </div>
    <div class="header-actions">
        <a href="#" id="theme-toggle" class="action-icon" title="Mudar Tema">
            <span class="material-icons-outlined">dark_mode</span>
        </a>

        <div class="dropdown">
            <a href="#" id="user-menu-toggle" class="action-icon" title="Perfil do Usuário">
                <span class="material-icons-outlined">account_circle</span>
            </a>
            <div id="user-menu" class="dropdown-menu">
                <a href="/editar-cadastro">Editar Cadastro</a>
                <a href="/editar-senha">Alterar Senha</a>
                <a href="/logout">Sair</a>
            </div>
        </div>
        </div>
</div>