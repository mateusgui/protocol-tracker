<?php require __DIR__ . '/_partials/_header_publico.php'; ?>

<div class="login-container">
    <div class="login-box">
        <div class="login-logo">
            <img src="/assets/imgs/ProtocolTrackerLogo.png" alt="Logo Protocol Tracker">
        </div>
        <h2>Criar Nova Conta</h2>
        <p>Preencha os campos abaixo para se cadastrar.</p>

        <?php if (isset($erro) && $erro): ?>
            <div class="error-message">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/cadastro-usuario" method="post" class="login-form">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($dadosDoFormulario['nome'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($dadosDoFormulario['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="cpf_formatado">CPF</label>
                <input type="text" id="cpf_formatado" placeholder="000.000.000-00" required maxlength="14" value="<?= htmlspecialchars($dadosDoFormulario['cpf_formatado'] ?? '') ?>">
                <input type="hidden" id="cpf_puro" name="cpf" value="<?= htmlspecialchars($dadosDoFormulario['cpf'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="senha">Senha (mínimo 6 caracteres)</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="confirmaSenha">Confirmar Senha</label>
                <input type="password" id="confirmaSenha" name="confirmaSenha" required>
            </div>
            <div class="form-actions">
                <button type="submit">Cadastrar</button>
            </div>
        </form>

        <div class="login-links links-cadastro" style="text-align: center; display: block;">
            <a href="/login">Já tem uma conta? Faça o login</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/_partials/_footer_publico.php'; ?>