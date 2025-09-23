<?php require __DIR__ . '/_partials/_header.php'; ?>

<?php require __DIR__ . '/_partials/_content-header.php'; ?>

<h2><?= htmlspecialchars($tituloDaPagina) ?></h2>
<p>Aqui estão as métricas de produtividade baseadas nos registros do sistema.</p>

<div class="dashboard-container">

    <div class="dashboard-row">
        <div class="metric-card">
            <div class="label">Lotes do Dia</div>
            <div class="value"><?= htmlspecialchars($metricas['quantidade_protocolos_dia']) ?></div>
        </div>
        <div class="metric-card">
            <div class="label">Páginas do Dia</div>
            <div class="value"><?= htmlspecialchars($metricas['quantidade_paginas_dia']) ?></div>
        </div>
        <div class="metric-card">
            <div class="label">Média de Lotes por Dia</div>
            <div class="value"><?= htmlspecialchars($metricas['media_protocolos_dia']) ?></div>
        </div>
        <div class="metric-card">
            <div class="label">Média de Páginas por Dia</div>
            <div class="value"><?= htmlspecialchars($metricas['media_paginas_dia']) ?></div>
        </div>
    </div>

    <div class="dashboard-row">
        <div class="metric-card">
            <div class="label">Lotes do Mês</div>
            <div class="value"><?= htmlspecialchars($metricas['quantidade_protocolos_mes']) ?></div>
        </div>
        <div class="metric-card">
            <div class="label">Páginas do Mês</div>
            <div class="value"><?= htmlspecialchars($metricas['quantidade_paginas_mes']) ?></div>
        </div>
        <div class="metric-card">
            <div class="label">Média de Lotes por Mês</div>
            <div class="value"><?= htmlspecialchars($metricas['media_protocolos_mes']) ?></div>
        </div>
        <div class="metric-card">
            <div class="label">Média de Páginas por Mês</div>
            <div class="value"><?= htmlspecialchars($metricas['media_paginas_mes']) ?></div>
        </div>
    </div>

    <div class="dashboard-row">
        <div class="metric-card total">
            <div class="label">Total de Lotes</div>
            <div class="value"><?= htmlspecialchars($metricas['quantidade_protocolos_total']) ?></div>
        </div>
        <div class="metric-card total">
            <div class="label">Total de Páginas</div>
            <div class="value"><?= htmlspecialchars($metricas['quantidade_paginas_total']) ?></div>
        </div>
    </div>

</div>

<?php require __DIR__ . '/_partials/_footer.php'; ?>