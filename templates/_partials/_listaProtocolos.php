<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Número</th>
            <th>Páginas</th>
            <th>Data de Registro</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listaDeProtocolos as $protocolo): ?>
            <tr>
                <td><?= htmlspecialchars($protocolo->id()) ?></td>
                <td><?= htmlspecialchars($protocolo->numero()) ?></td>
                <td><?= htmlspecialchars($protocolo->paginas()) ?></td>
                <td><?= $protocolo->data()->format('d/m/Y H:i:s') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>