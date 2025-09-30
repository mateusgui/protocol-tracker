<section class="listagem-container">
    <h3>Protocolos Registrados</h3>
    
    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <table id="tabela-protocolos" class="protocolos-table">
        <thead>
            <tr>
                <th>Número</th>
                <th>Páginas</th>
                <th>Data da Digitalização</th>
                <th class="acoes-header">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // O bloco "if (empty(...))" foi REMOVIDO daqui.
            // O foreach simplesmente não executará se a lista estiver vazia,
            // resultando em um <tbody> vazio, que é o correto.
            ?>
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