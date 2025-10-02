<?php require __DIR__ . '/../_partials/_header.php'; ?>

<div class="home-container"> 
        <h2><?= htmlspecialchars($tituloDaPagina) ?></h2>
        <p>Altere os dados necessários e clique em "Salvar Alterações".</p>

        <?php if (isset($erro) && $erro): ?>
            <div class="error-message">
                <strong>Erro ao salvar:</strong> <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/admin/editarprotocolo" method="post" class="protocolo-form">
            
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
                <button type="submit" class="btn-salvar"><span class="material-icons-outlined"> save</span> Salvar Alterações</button>
                <a href="/admin/protocolos" class="btn-cancelar"><span class="material-icons-outlined">cancel</span> Cancelar</a>
            </div>
        </form>
    </div>

<?php require __DIR__ . '/../_partials/_footer.php'; ?>