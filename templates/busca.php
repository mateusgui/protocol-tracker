<?php require __DIR__ . '/_partials/_header.php'; ?>

<div class="container-principal-busca">

    <section class="busca-form-container">
        <h2>Buscar Protocolos</h2>
        <form action="/busca" method="get" class="busca-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="numero">Número do Protocolo:</label>
                    <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($_GET['numero'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="data_inicio">De:</label>
                    <input type="date" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($_GET['data_inicio'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="data_fim">Até:</label>
                    <input type="date" id="data_fim" name="data_fim" value="<?= htmlspecialchars($_GET['data_fim'] ?? '') ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-buscar">
                    <span class="material-icons-outlined">search</span> Buscar
                </button>
                <a href="/busca" class="btn-limpar">Limpar Filtros</a>
            </div>
        </form>
    </section>

    <section class="listagem-container">
        <h3>Protocolos Registrados</h3>
        <?php if (isset($erro) && $erro): ?>
            <div class="error-message">
                <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>
        <table class="protocolos-table">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Páginas</th>
                    <th>Data da Digitalização</th>
                    <th class="acoes-header">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($listaDeProtocolos)): ?>
                    <tr>
                        <td colspan="4" class="nenhum-resultado">Nenhum protocolo encontrado.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($listaDeProtocolos as $protocolo): ?>
                    <tr>
                        <td><?= htmlspecialchars($protocolo->numero()) ?></td>
                        <td><?= htmlspecialchars($protocolo->paginas()) ?></td>
                        <td><?= $protocolo->data()->format('d/m/Y H:i:s') ?></td>
                        <td class="acoes-cell">
                            <a href="/editar?id=<?= htmlspecialchars($protocolo->id()) ?>" class="btn-acao btn-editar" title="Editar">
                                <span class="material-icons-outlined">edit</span>
                            </a>
                            <form action="/excluir" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este protocolo?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($protocolo->id()) ?>">
                                <button type="submit" class="btn-acao btn-excluir" title="Excluir">
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

</div>

<?php require __DIR__ . '/_partials/_footer.php'; ?>