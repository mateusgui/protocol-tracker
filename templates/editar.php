<?php require __DIR__ . '/_partials/_header.php'; ?>

<?php require __DIR__ . '/_partials/_content-header.php'; ?>

<div class="home-container"> 
        <h2><?= htmlspecialchars($tituloDaPagina) ?></h2>
        <p>Altere os dados necessários e clique em "Salvar Alterações".</p>

        <?php if (isset($erro) && $erro): ?>
            <div class="error-message">
                <strong>Erro ao salvar:</strong> <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/editar" method="post" class="protocolo-form">
            
            <input type="hidden" name="id" value="<?= htmlspecialchars($protocolo->id()) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="numero">Número do Protocolo (6 dígitos):</label>
                    <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($protocolo->numero()) ?>" required maxlength="6" pattern="\d{6}">
                </div>

                <div class="form-group">
                    <label for="paginas">Quantidade de Páginas:</label>
                    <input type="number" id="paginas" name="paginas" value="<?= htmlspecialchars($protocolo->paginas()) ?>" required min="1">
                </div>
            </div>

            <div class="form-group">
                <label for="observacoes">Observações (Opcional):</label>
                <textarea id="observacoes" name="observacoes" rows="3"></textarea>
            </div>

            <div class="form-actions-edit">
                <button type="submit" class="btn-salvar">Salvar Alterações</button>
                <a href="/busca" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>

<?php require __DIR__ . '/_partials/_footer.php'; ?>