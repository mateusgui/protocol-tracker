<?php

namespace Mateus\ProtocolTracker\Service;

use DateTimeImmutable;
use DateTimeZone;
use Mateus\ProtocolTracker\Interface\ProtocoloRepositorySqlInterface;

class DashboardService{

    public function __construct(
        private ProtocoloRepositorySqlInterface $repositorio
    ) {}

    /**
     * Pega as métricas e produtividade geral dos Usuarios
     * @return array array com as métricas
     */
    public function getTodasAsMetricas(): array
    {
        $todosOsProtocolos = $this->repositorio->all();
        $agora = new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'));

        return [
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
        ];
    }

    /**
     * Busca a quantidade de páginas digitalizadas no $dia por Usuario
     * @param int|null $id_usuario
     * @param DateTimeImmutable $dia
     * @return int 
     */
    public function metricarPorUsuarioDia(?int $id_usuario, DateTimeImmutable $dia): int
    {
        $todosOsProtocolos = [];
        if ($id_usuario !== null) {
            $todosOsProtocolos = $this->repositorio->allByUser($id_usuario);
        } else {
            $todosOsProtocolos = $this->repositorio->all();
        }

        return $this->quantidadeDePaginasDia($todosOsProtocolos, $dia);
    }

    /**
     * Busca a quantidade de páginas digitalizadas no $mes por Usuario
     * @param int|null $id_usuario
     * @param DateTimeImmutable $mes
     * @return int 
     */
    public function metricarPorUsuarioMes(?int $id_usuario, DateTimeImmutable $mes): int
    {
        $todosOsProtocolos = [];
        if ($id_usuario !== null) {
            $todosOsProtocolos = $this->repositorio->allByUser($id_usuario);
        } else {
            $todosOsProtocolos = $this->repositorio->all();
        }

        return $this->quantidadeDePaginasMes($todosOsProtocolos, $mes);
    }

    /**
     * Busca a quantidade total de páginas digitalizadas por Usuario
     * @param int|null $id_usuario
     * @return int 
     */
    public function metricarPorUsuarioTotal(?int $id_usuario): int
    {
        $todosOsProtocolos = [];
        if ($id_usuario !== null) {
            $todosOsProtocolos = $this->repositorio->allByUser($id_usuario);
        } else {
            $todosOsProtocolos = $this->repositorio->all();
        }

        return $this->quantidadeDePaginasTotal($todosOsProtocolos);
    }

    /**
     * Busca a quantidade de páginas digitalizadas no dia atual
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @param DateTimeImmutable $agora
     * @return int 
     */
    private function quantidadeDePaginasDia(array $todosOsProtocolos, DateTimeImmutable $agora): int
    {
        $quantidadeDePaginasDia = 0;
        $diaAtualFormatado = $agora->format('d/m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data atual ex.: 17/09/2025

        foreach ($todosOsProtocolos as $protocolo) {
            $diaProtocolo = $protocolo->data(); //armazena um objeto do tipo DateTimeImmutable vindo do protocolo
            $diaProtocoloFormatado = $diaProtocolo->format('d/m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data de regsitro do protocolo ex.: 18/09/2025
            if($diaAtualFormatado === $diaProtocoloFormatado){
                //Se o dia de registro do protocolo for igual do dia atual, acumula os valores das quantidades de páginas
                $quantidadeDePaginasDia += $protocolo->paginas();
            }
        }

        return $quantidadeDePaginasDia;
    }

    /**
     * Busca a média total de páginas/dia
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @return int 
     */
    private function mediaDePaginasDia(array $todosOsProtocolos): int
    {
        $totalPaginas = $this->quantidadeDePaginasTotal($todosOsProtocolos);
        $totalDias = $this->quantidadeTotalDeDias($todosOsProtocolos);

        if($totalPaginas === 0 || $totalDias === 0){
            return 0;
        }

        $mediaDePaginasDia = $totalPaginas / $totalDias;

        return (int) round($mediaDePaginasDia);
    }

    /**
     * Busca a quantidade de Protocolos digitalizados no dia atual
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @param DateTimeImmutable $agora
     * @return int 
     */
    private function quantidadeDeProtocolosDia(array $todosOsProtocolos, DateTimeImmutable $agora): int
    {
        $quantidadeDeProtocolosDia = 0;
        $diaAtualFormatado = $agora->format('d/m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data atual ex.: 17/09/2025

        foreach ($todosOsProtocolos as $protocolo) {
            $diaProtocolo = $protocolo->data(); //armazena um objeto do tipo DateTimeImmutable vindo do protocolo
            $diaProtocoloFormatado = $diaProtocolo->format('d/m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data de regsitro do protocolo ex.: 18/09/2025
            if($diaAtualFormatado === $diaProtocoloFormatado){
                //Se o dia de registro do protocolo for igual do dia atual, incrementa o valor da quantidade de protocolos
                $quantidadeDeProtocolosDia++;
            }
        }

        return $quantidadeDeProtocolosDia;
    }

    /**
     * Busca a média total de protocolos/dia
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @return int 
     */
    private function mediaDeProtocolosDia(array $todosOsProtocolos): int
    {
        $totalProtocolos = $this->quantidadeDeProtocolosTotal($todosOsProtocolos);
        $totalDias = $this->quantidadeTotalDeDias($todosOsProtocolos);

        if($totalProtocolos === 0 || $totalDias === 0){
            return 0;
        }

        $mediaDeProtocolosDia = $totalProtocolos / $totalDias;

        return (int) round($mediaDeProtocolosDia);
    }

    /**
     * Busca a quantidade de páginas digitalizadas no mês atual
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @param DateTimeImmutable $agora
     * @return int 
     */
    private function quantidadeDePaginasMes(array $todosOsProtocolos, DateTimeImmutable $agora): int
    {
        $quantidadeDePaginasMes = 0;
        $mesAtualFormatado = $agora->format('m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data atual ex.: 09/2025

        foreach ($todosOsProtocolos as $protocolo) {
            $mesProtocolo = $protocolo->data(); //armazena um objeto do tipo DateTimeImmutable vindo do protocolo
            $mesProtocoloFormatado = $mesProtocolo->format('m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data de regsitro do protocolo ex.: 08/2025
            if($mesAtualFormatado === $mesProtocoloFormatado){
                //Se o mês de registro do protocolo for igual do mês atual, acumula os valores das quantidades de páginas
                $quantidadeDePaginasMes += $protocolo->paginas();
            }
        }

        return $quantidadeDePaginasMes;
    }

    /**
     * Busca a média total de páginas/mês
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @param DateTimeImmutable $agora
     * @return int 
     */
    private function mediaDePaginasMes(array $todosOsProtocolos, DateTimeImmutable $agora): int
    {
        $totalPaginas = $this->quantidadeDePaginasTotal($todosOsProtocolos);
        $totalMeses = $this->quantidadeTotalDeMeses($todosOsProtocolos, $agora);

        if($totalPaginas === 0 || $totalMeses === 0){
            return 0;
        }

        $mediaDePaginasMes = $totalPaginas / $totalMeses;

        return (int) round($mediaDePaginasMes);
    }

    /**
     * Busca a quantidade de protocolos digitalizados no mês atual
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @param DateTimeImmutable $agora
     * @return int 
     */
    private function quantidadeDeProtocolosMes(array $todosOsProtocolos, DateTimeImmutable $agora): int
    {
        $quantidadeDeProtocolosMes = 0;
        $mesAtualFormatado = $agora->format('m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data atual ex.: 09/2025

        foreach ($todosOsProtocolos as $protocolo) {
            $mesProtocolo = $protocolo->data(); //armazena um objeto do tipo DateTimeImmutable vindo do protocolo
            $mesProtocoloFormatado = $mesProtocolo->format('m/Y'); //Formata o objeto do tipo DateTimeImmutable com a data de regsitro do protocolo ex.: 08/2025
            if($mesAtualFormatado === $mesProtocoloFormatado){
                //Se o mês de registro do protocolo for igual do mês atual, incrementa a quantidade de protocolos
                $quantidadeDeProtocolosMes++;
            }
        }

        return $quantidadeDeProtocolosMes;
    }

    /**
     * Busca a média total de protocolos/mês
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @param DateTimeImmutable $agora
     * @return int 
     */
    private function mediaDeProtocolosMes(array $todosOsProtocolos, DateTimeImmutable $agora): int
    {
        $totalProtocolos = $this->quantidadeDeProtocolosTotal($todosOsProtocolos);
        $totalMeses = $this->quantidadeTotalDeMeses($todosOsProtocolos, $agora);

        if($totalProtocolos === 0 || $totalMeses === 0){
            return 0;
        }

        $mediaDeProtocolosMes = $totalProtocolos / $totalMeses;

        return (int) round($mediaDeProtocolosMes);
    }

    /**
     * Busca a quantidade total de páginas digitalizadas
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @return int 
     */
    private function quantidadeDePaginasTotal(array $todosOsProtocolos): int
    {
        $somaQuantidadeDePaginas = 0;

        foreach ($todosOsProtocolos as $protocolo) {
            $somaQuantidadeDePaginas += $protocolo->paginas();
        }

        return $somaQuantidadeDePaginas;
    }

    /**
     * Busca a quantidade total de protocolos digitalizados
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @return int 
     */
    private function quantidadeDeProtocolosTotal(array $todosOsProtocolos): int
    {
        return count($todosOsProtocolos);
    }

    /**
     * Busca a quantidade total de dias
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @return int 
     */
    private function quantidadeTotalDeDias(array $todosOsProtocolos): int
    {
        $diasDiferentes = [];

        foreach ($todosOsProtocolos as $protocolo) {
            $dataDoProtocolo = $protocolo->data();
            $chaveDoDia = $dataDoProtocolo->format('d/m/Y');

            if(!in_array($chaveDoDia, $diasDiferentes)){
                $diasDiferentes[] = $chaveDoDia;
            }
        }

        return count($diasDiferentes);
    }

    /**
     * Busca a quantidade total de meses
     * @param array $todosOsProtocolos Lista de todos os protocolos
     * @param DateTimeImmutable $agora
     * @return int 
     */
    private function quantidadeTotalDeMeses(array $todosOsProtocolos): int
    {
        $mesesDiferentes = [];

        foreach ($todosOsProtocolos as $protocolo) {
            $dataDoProtocolo = $protocolo->data();
            $chaveDoMes = $dataDoProtocolo->format('m/Y');

            if(!in_array($chaveDoMes, $mesesDiferentes)){
                $mesesDiferentes[] = $chaveDoMes;
            }
        }

        return count($mesesDiferentes);
    }
}