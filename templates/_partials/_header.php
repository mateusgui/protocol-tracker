<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloDaPagina) ? htmlspecialchars($tituloDaPagina) : 'Controle de Protocolos' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" href="/favicon.ico">
</head>
<body>
    <header>
        <h1>Controle de Protocolos</h1>
        <nav>
            <a href="/">Página Inicial</a> | 
            <a href="/busca">Buscar Protocolos</a>
        </nav>
    </header>
    <main>