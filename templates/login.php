<?php require __DIR__ . '/_partials/_header_publico.php'; ?>

<div class="login-container">
    <div class="login-box">
        <div class="login-logo">
            <img src="/assets/imgs/ProtocolTrackerLogo.png" alt="Logo Protocol Tracker">
        </div>
        <h2>Acessar o Sistema</h2>
        <p>Por favor, insira seu CPF e senha para continuar.</p>

        <?php if (isset($erro) && $erro): ?>
            <div class="error-message">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post" class="login-form">
            <div class="form-group">
                <label for="cpf_formatado">CPF</label>
                <input type="text" id="cpf_formatado" placeholder="000.000.000-00" maxlength="14">
                
                <input type="hidden" id="cpf_puro" name="cpf">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-actions">
                <button type="submit">Entrar</button>
            </div>
        </form>

        <div class="login-links">
            <a href="/cadastro-usuario">Cadastrar-se</a>
            <a href="/esqueci-senha">Esqueci minha senha</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/_partials/_footer_publico.php'; ?>