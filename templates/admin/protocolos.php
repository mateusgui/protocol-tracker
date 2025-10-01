<?php require __DIR__ . '/../_partials/_header.php'; ?>
<div class="container-principal-busca">
    <div class="busca-form-container">
        <form action="/admin/protocolos" method="get" class="busca-form"> <div class="form-row">
                <div class="form-group">
                    <label for="numero">Número do Protocolo</label>
                    <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($_GET['numero'] ?? '') ?>" placeholder="Ex: 123456">
                </div>
                <div class="form-group">
                    <label for="data_inicio">Data inicial</label>
                    <input type="date" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($_GET['data_inicio'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="data_fim">Data final</label>
                    <input type="date" id="data_fim" name="data_fim" value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-buscar">
                    <span class="material-icons-outlined">search</span> Buscar
                </button>
                <a href="/admin/protocolos" class="btn-limpar">Limpar Filtros</a>
            </div>
        </form>
    </div>

    <div class="listagem-container">
        <h3>Todos os Protocolos Registrados</h3>
        <?php require __DIR__ . '/../_partials/_listaProtocolos.php'; ?> 
    </div>
</div>

<?php require __DIR__ . '/../_partials/_footer.php'; ?>