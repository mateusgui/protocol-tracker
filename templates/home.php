<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloDaPagina) ?></title>
    </head>
<body>

    <h1><?= htmlspecialchars($tituloDaPagina) ?></h1>

    <a href="/busca">
        <button>
            BUSCAR
        </button>
    </a>

    <h2>Dashboard</h2>
    <pre>
       <?php print_r($metricas); ?>
    </pre>
</body>
</html>