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

    public function registrarNovouUsuario(string $nome, string $email, string $cpf, string $senha)
    {
        //Validar o CPF - private function validaCpf(): bool
        //Validar se o cpf já está cadastrado

        //Validar o email - private function validaEmail(): bool
        //Validar se o email já está cadastrado

        //Validar se a senha cumpre as regras de segurança
    }

    // public function update(Usuario $usuario): void
    // public function desativarUsuario(int $id): void
    // public function ativarUsuario(int $id): void
    //nome, email, cpf, senha, ativo
    public function salvarUsuario()
    {

    }

    //Lógica para validação do CPF
    private function validaCpf(): bool
    {
        return true;
    }

    private function validaEmail(): bool
    {
        return true;
    }

    private function validaSenha(): bool
    {
        return true;
    }
}