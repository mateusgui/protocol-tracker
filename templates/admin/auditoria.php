<?php require __DIR__ . '/../_partials/_header.php'; ?>
<div class="container-principal-busca">
    <!-- <div class="busca-form-container">

        AJUSTAR BUSCA - CRIAR ROTA DA BUSCA DO ADMIN

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
    </div> -->

    <!-- CRIAR UMA _listaAuditoria REUTILIZAVEL -->

        <section class="listagem-container">
            <h3>Histórico</h3>
            
            <?php if (isset($erro) && $erro): ?>
                <div class="error-message">
                    <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <table id="tabela-protocolos" class="protocolos-table">
                <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Ação</th>
                        <th>Data da ação</th>
                        <th>Usuário responsável</th>
                        <th>Permissão</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaAuditoria as $registro): ?>
                        <tr>
                            <td> <?= htmlspecialchars($registro['numero_protocolo']) ?> </td>
                            <td> <?= htmlspecialchars($registro['acao']) ?> </td>
                            <td> <?= (new DateTimeImmutable($registro['data_acao']))->format('d/m/Y H:i:s') ?> </td>
                            <td> <?= htmlspecialchars($registro['nome']) ?> </td>
                            <td> <?= htmlspecialchars($registro['permissao']) ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
</div>

<?php require __DIR__ . '/../_partials/_footer.php'; ?>