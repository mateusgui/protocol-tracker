<?php

namespace Mateus\ProtocolTracker\Service;

use DateTimeImmutable;
use DateTimeZone;
use Exception; // Usaremos para reportar erros de validação
use Mateus\ProtocolTracker\Repository\UsuarioRepository;

final class ProtocoloService
{
    public function __construct(
        private UsuarioRepository $repositorio
    ) {}
}