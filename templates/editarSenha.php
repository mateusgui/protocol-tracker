<?php 
// Usando o layout público, pois é uma ação focada, fora do layout principal.
require __DIR__ . '/_partials/_header.php'; 
?>

<div class="login-container">
    <div class="login-box">
        <h2>Alterar Senha</h2>
        <p>Digite e confirme sua nova senha.</p>

        <?php if (isset($erro) && $erro): ?>
            <div class="error-message">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/editar-senha" method="post" class="login-form">
            <div class="form-group">
                <label for="novaSenha">Nova Senha (mínimo 6 caracteres)</label>
                <input type="password" id="novaSenha" name="novaSenha" required>
            </div>
            <div class="form-group">
                <label for="confirmaSenha">Confirmar Nova Senha</label>
                <input type="password" id="confirmaSenha" name="confirmaSenha" required>
            </div>
            
            <div class="form-actions-edit">
                <button type="submit" class="btn-salvar">Salvar Nova Senha</button>
                <a href="/editar-cadastro" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php 
require __DIR__ . '/_partials/_footer.php'; 
?>