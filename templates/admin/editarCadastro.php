<?php require __DIR__ . '/_partials/_header.php'; // Usando o layout público sem sidebar ?>

<div class="login-container">
    <div class="login-box">
        <h2>Editar Meu Cadastro</h2>
        <p>Altere seus dados e clique em "Salvar Alterações".</p>

        <?php if (isset($erro) && $erro): ?>
            <div class="error-message">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/admin/editar-cadastro" method="post" class="login-form">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($usuario->nome() ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($usuario->email() ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="cpf_formatado">CPF</label>
                <input type="text" id="cpf_formatado" placeholder="000.000.000-00" required maxlength="14" value="<?= htmlspecialchars($usuario->cpf() ?? '') ?>">
                <input type="hidden" id="cpf_puro" name="cpf" value="<?= htmlspecialchars($usuario->cpf() ?? '') ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/_partials/_footer.php'; ?>