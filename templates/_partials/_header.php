<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloDaPagina) ? htmlspecialchars($tituloDaPagina) : 'Controle de Protocolos' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" href="/favicon.ico">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

    <div class="container-principal">

        <aside class="sidebar">
            <nav>
                <ul>
                    <?php $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
                    <li><a href="/" class="<?= ($uri === '/') ? 'active' : '' ?>">Novo Protocolo</a></li>
                    <li><a href="/busca" class="<?= ($uri === '/busca') ? 'active' : '' ?>">Buscar Protocolos</a></li>
                </ul>
            </nav>
        </aside>

        <main class="conteudo-principal">