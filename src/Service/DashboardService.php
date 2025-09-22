<?php

namespace Mateus\ProtocolTracker\Service;

use Mateus\ProtocolTracker\Repository\ProtocoloRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;

class DashboardService{

    // Construtor padrão da classe, recebe um objeto do tipo ProtocoloRepository para inicializar a classe DashboardService
    public function __construct(
        private ProtocoloRepositoryInterface $repositorio
    ) {}

    // -- MÉTODO PÚBLICO --

    //Responsável por chamar os métodos private para entregar o relatório completo com todas as métricas
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


    // -- MÉTODOS PRIVADOS --
    //São chamados para auxiliar o método getTodasAsMetricas() a calcular todos os dados que serão apresentados no dashboard

    //RF09: O dashboard deve exibir a "Quantidade de páginas do dia" (soma das quantidades de páginas de todos os protocolos registrados no dia corrente).
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

    //RF10: O dashboard deve exibir a "Média de páginas por dia" (Quantidade de páginas total / Quantidade total de dias [Soma da quantidade de dias diferentes existentes nos registros, será desconsiderado finais de semana]).
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

    //RF11: O dashboard deve exibir a "Quantidade de lotes do dia" (Contagem de protocolos registrados no dia corrente).
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

    //RF12: O dashboard deve exibir a "Média de lotes por dia" (Quantidade de lotes total / Quantidade total de dias [Soma da quantidade de dias diferentes existentes nos registros, será desconsiderado finais de semana]).
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

    //RF13: O dashboard deve exibir a "Quantidade de páginas do mês" (soma das quantidades de páginas de todos os protocolos registrados no mês corrente).
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

    //RF14: O dashboard deve exibir a "Média de páginas por mês " (Quantidade de páginas total / Quantidade total de meses [Nessa soma, considerar somente meses que já passaram por completo]).
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

    //RF15: O dashboard deve exibir a "Quantidade de lotes do mês " (Contagem de protocolos registrados no mês corrente).
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

    //RF16: O dashboard deve exibir a "Média de lotes por mês " (Quantidade de lotes total / Quantidade total de meses [Nessa soma, considerar somente meses que já passaram por completo]).
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

    //RF17: O dashboard deve exibir a "Quantidade de páginas total" (soma das páginas de todos os protocolos registrados).
    private function quantidadeDePaginasTotal(array $todosOsProtocolos): int
    {
        $somaQuantidadeDePaginas = 0;

        foreach ($todosOsProtocolos as $protocolo) {
            $somaQuantidadeDePaginas += $protocolo->paginas();
        }

        return $somaQuantidadeDePaginas;
    }

    //RF18: O dashboard deve exibir a "Quantidade de lotes total" (soma da quantidade de protocolos registrados).
    private function quantidadeDeProtocolosTotal(array $todosOsProtocolos): int
    {
        return count($todosOsProtocolos);
    }

    // -- MÉTODOS AUXILIARES --
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

    private function quantidadeTotalDeMeses(array $todosOsProtocolos, DateTimeImmutable $agora): int
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