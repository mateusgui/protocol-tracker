<?php

namespace Mateus\ProtocolTracker\Repository;

use Mateus\ProtocolTracker\Model\Usuario;

interface UsuarioRepositoryInterface
{
    public function all(): array;
    public function buscaPorId(int $id): ?Usuario;
    public function buscaPorEmail(string $email): ?Usuario;
    public function buscaPorCpf(string $cpf): ?Usuario;
    public function add(Usuario $usuario): Usuario;
    public function update(Usuario $usuario): void;
    public function alterarSenha(int $id, string $hashDaNovaSenha): void;
    public function desativarUsuario(int $id): void;
    public function ativarUsuario(int $id): void;
}