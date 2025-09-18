<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloDaPagina) ?></title>
    </head>
<body>

    <h1><?= htmlspecialchars($tituloDaPagina) ?></h1>

    <form action="/" method="post">
        </form>

    <hr>

    <h2>Protocolos Registrados</h2>
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

            <h2>Dados Brutos do Array de Métricas (para Debug)</h2>
            
        </tbody>
    </table>

    <pre>
       <?php print_r($metricas); ?>
    </pre>
</body>
</html>