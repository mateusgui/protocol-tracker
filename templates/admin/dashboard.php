<?php require __DIR__ . '/../_partials/_header.php'; ?>

<div class="form-container dashboard-filters">
    <form action="/admin/dashboard" method="get">
        <div class="form-row">
            <div class="form-group">
                <label for="dia">Produtividade do Dia:</label>
                <input type="date" id="dia" name="dia" value="<?= $diaSelecionado->format('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label for="mes">Produtividade do Mês:</label>
                <input type="month" id="mes" name="mes" value="<?= $mesSelecionado->format('Y-m') ?>">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-buscar">
                    <span class="material-icons-outlined">visibility</span> Visualizar
                </button>
            </div>
        </div>
    </form>
</div>

<!--         return [
            'quantidade_paginas_dia'   => $this->quantidadeDePaginasDia($todosOsProtocolos, $agora),
            'media_paginas_dia'        => $this->mediaDePaginasDia($todosOsProtocolos),
            'quantidade_protocolos_dia'  => $this->quantidadeDeProtocolosDia($todosOsProtocolos, $agora),
            'media_protocolos_dia'     => $this->mediaDeProtocolosDia($todosOsProtocolos),
            
            'quantidade_paginas_mes'   => $this->quantidadeDePaginasMes($todosOsProtocolos, $agora),
            'media_paginas_mes'        => $this->mediaDePaginasMes($todosOsProtocolos, $agora),
            'quantidade_protocolos_mes'  => $this->quantidadeDeProtocolosMes($todosOsProtocolos, $agora),
            'media_protocolos_mes'     => $this->mediaDeProtocolosMes($todosOsProtocolos, $agora),
            
            'quantidade_paginas_total'   => $this->quantidadeDePaginasTotal($todosOsProtocolos),
            'quantidade_protocolos_total'  => $this->quantidadeDeProtocolosTotal($todosOsProtocolos),
        ]; -->

<div class="dashboard-container">
    <div class="dashboard-row">
        
        <div class="metric-card">
            <div class="label">Páginas no Dia (<?= $diaSelecionado->format('d/m/Y') ?>)</div>
            <div class="value dia"><?= htmlspecialchars($totalPorDiaUsuario) ?></div>
        </div>

        <div class="metric-card">
            <div class="label">Páginas no Mês (<?= $mesSelecionado->format('m/Y') ?>)</div>
            <div class="value mes"><?= htmlspecialchars($totalPorMesUsuario) ?></div>
        </div>

        <div class="metric-card total">
            <div class="label">Total de Páginas (Geral)</div>
            <div class="value total"><?= htmlspecialchars($totalUsuario) ?></div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../_partials/_footer.php'; ?>