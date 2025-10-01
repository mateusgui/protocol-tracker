<section class="listagem-container">
    <h3>Usuários Cadastrados</h3>

    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <table class="protocolos-table datatable-js">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Permissão</th>
                <th>Status</th>
                <th class="acoes-header">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaDeUsuarios)): ?>
                <tr>
                    <td colspan="6" class="nenhum-resultado">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($listaDeUsuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario->nome()) ?></td>
                    <td><?= htmlspecialchars($usuario->email()) ?></td>
                    <td><?= htmlspecialchars($usuario->cpf()) ?></td>
                    <td><?= htmlspecialchars(ucfirst($usuario->permissao())) ?></td>
                    <td>
                        <?php if ($usuario->isAtivo()): ?>
                            <span class="badge badge-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="acoes-cell">
                        <form action="/admin/editar-cadastro" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->id()) ?>">
                            <button type="submit" class="btn-acao btn-editar" title="Editar Usuário">
                                <span class="material-icons-outlined">manage_accounts</span>
                            </button>
                        </form>
                        <form action="/editar-status" method="post" onsubmit="return confirm('Tem certeza que deseja alterar o status deste usuário?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->id()) ?>">
                            <button type="submit" class="btn-acao <?= $usuario->isAtivo() ? 'btn-desativar' : 'btn-ativar' ?>" title="<?= $usuario->isAtivo() ? 'Desativar' : 'Ativar' ?>">
                                <span class="material-icons-outlined">
                                    <?= $usuario->isAtivo() ? 'toggle_off' : 'toggle_on' ?>
                                </span>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>