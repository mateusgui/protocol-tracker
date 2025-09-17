<?php

namespace Mateus\ProtocolTracker\Service;

use Mateus\ProtocolTracker\Repository\ProtocoloRepository;
use DateTimeImmutable;
use DateTimeZone;

class DashboardService{
    // Instância do repositório para poder usá-lo.
    private ProtocoloRepository $repositorio;

    // Construtor padrão da classe, recebe um objeto do tipo ProtocoloRepository para inicializar a classe DashboardService
    public function __construct(ProtocoloRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    //RF09: O dashboard deve exibir a "Quantidade de páginas do dia" (soma das quantidades de páginas de todos os protocolos registrados no dia corrente).
    public function quantidadeDePaginasDia(): int
    {
        return 0;
    }

    //RF10: O dashboard deve exibir a "Média de páginas por dia" (Quantidade de páginas total / Quantidade total de dias [Soma da quantidade de dias diferentes existentes nos registros, será desconsiderado finais de semana]).
    public function mediaDePaginasDia(): int
    {
        return 0;
    }

    //RF11: O dashboard deve exibir a "Quantidade de lotes do dia" (Contagem de protocolos registrados no dia corrente).
    public function quantidadeDeProtocolosDia(): int
    {
        return 0;
    }

    //RF12: O dashboard deve exibir a "Média de lotes por dia" (Quantidade de lotes total / Quantidade total de dias [Soma da quantidade de dias diferentes existentes nos registros, será desconsiderado finais de semana]).
    public function mediaDeProtocolosDia(): int
    {
        return 0;
    }

    //RF13: O dashboard deve exibir a "Quantidade de páginas do mês" (soma das quantidades de páginas de todos os protocolos registrados no mês corrente).
    public function quantidadeDePaginasMes(): int
    {
        return 0;
    }

    //RF14: O dashboard deve exibir a "Média de páginas por mês " (Quantidade de páginas total / Quantidade total de meses [Nessa soma, considerar somente meses que já passaram por completo]).
    public function mediaDePaginasMes(): int
    {
        return 0;
    }

    //RF15: O dashboard deve exibir a "Quantidade de lotes do mês " (Contagem de protocolos registrados no mês corrente).
    public function quantidadeDeProtocolosMes(): int
    {
        return 0;
    }

    //RF16: O dashboard deve exibir a "Média de lotes por mês " (Quantidade de lotes total / Quantidade total de meses [Nessa soma, considerar somente meses que já passaram por completo]).
    public function mediaDeProtocolosMes(): int
    {
        return 0;
    }

    //RF17: O dashboard deve exibir a "Quantidade de páginas total" (soma das páginas de todos os protocolos registrados no dia corrente).
    public function quantidadeDePaginasTotal(): int
    {
        return 0;
    }

    //RF18: O dashboard deve exibir a "Quantidade de lotes total" (soma das páginas de todos os protocolos registrados no dia corrente).
        public function quantidadeDeProtocolosTotal(): int
    {
        return 0;
    }
}