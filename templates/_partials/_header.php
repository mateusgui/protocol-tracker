<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloDaPagina) ? htmlspecialchars($tituloDaPagina) : 'Controle de Protocolos' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header style="background-color: #f0f0f0; padding: 10px; text-align: center;">
        <h1>Controle de Protocolos</h1>
        <nav>
            <a href="/">Página Inicial</a> | 
            <a href="/busca">Buscar Protocolos</a>
        </nav>
    </header>
    <main style="padding: 20px;">