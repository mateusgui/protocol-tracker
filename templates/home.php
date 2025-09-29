<?php require __DIR__ . '/_partials/_header.php'; ?>

<h2>Adicionar Novo Protocolo</h2>
<p>Preencha os campos abaixo para registrar um novo protocolo digitalizado.</p>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<form action="/home" method="post" class="protocolo-form">
    
    <div class="form-row">
        <div class="form-group">
            <label for="numero">Número do Protocolo (6 dígitos):</label>
            <input type="text" id="numero" name="numero" required maxlength="6" placeholder="Ex: 123456">
        </div>

        <div class="form-group">
            <label for="paginas">Quantidade de Páginas:</label>
            <input type="number" id="paginas" name="paginas" required placeholder="Ex: 2000">
        </div>
    </div>

    <div class="form-group">
        <label for="observacoes">Observações (Opcional):</label>
        <textarea id="observacoes" name="observacoes" rows="3" placeholder="Ex: Prontuário Nº9999 possui várias páginas com impressões falhadas"></textarea>
    </div>

    <div class="form-actions">
        <a href="/home" class="btn-limpar"><span class="material-icons-outlined">cancel</span> Limpar</a>
        <button type="submit"><span class="material-icons-outlined">add</span> Adicionar Protocolo</button>
    </div>
</form>

<?php require __DIR__ . '/_partials/_footer.php'; ?>