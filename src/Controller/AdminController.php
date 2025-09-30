<?php

namespace Mateus\ProtocolTracker\Controller;

use Mateus\ProtocolTracker\Repository\UsuarioRepository;
use Mateus\ProtocolTracker\Service\LoginService;
use Mateus\ProtocolTracker\Service\UsuarioService;
use Exception;

class AdminController {

    public function __construct(
        private UsuarioRepository $repositorio,
        private UsuarioService $usuarioService
    ) {}

    /**
     * URL_PATH = /login
     * REQUEST_METHOD = GET
     */
    public function exibirPainelAdmin()
    {
        
    }
}