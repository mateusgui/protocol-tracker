<?php

namespace Mateus\ProtocolTracker\Service;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Mateus\ProtocolTracker\Model\Usuario;
use Mateus\ProtocolTracker\Repository\UsuarioRepository;

final class UsuarioService
{
    public function __construct(
        private UsuarioRepository $repositorio
    ) {}

    public function registrarNovoUsuario(string $nome, string $email, string $cpf, string $senha, string $confirmaSenha): Usuario
    {
        $this->validaCpf($cpf);
        $this->validaSenha($senha, $confirmaSenha);
        $this->validaEmail($email);

        //Validar se o cpf já está cadastrado
        if($this->repositorio->buscaPorCpf($cpf) !== null){
            throw new Exception("O CPF informado já está cadastrado");
        }

        //Validar se o email já está cadastrado
        if($this->repositorio->buscaPorEmail($email) !== null){
            throw new Exception("O email informado já está cadastrado");
        }

        $hashDaSenha = password_hash($senha, PASSWORD_ARGON2ID);

        $usuario = new Usuario(
            null,
            $nome,
            $email,
            $cpf,
            $hashDaSenha,
            'operador',
            new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande')),
            true
        );

        return $this->repositorio->add($usuario);
    }

    public function atualizarDadosCadastrais(int $id, string $nome, string $email, string $cpf): void
    {
        $dadosAtuaisUsuario = $this->repositorio->buscaPorId($id);
        if ($dadosAtuaisUsuario === null) {
            throw new Exception("O usuário informado não existe");
        }

        $this->validaCpf($cpf);
        $this->validaEmail($email);

        if($this->repositorio->buscaPorCpf($cpf) !== null && $dadosAtuaisUsuario->cpf() !== $cpf){
            throw new Exception("O CPF informado já está cadastrado");
        }

        if($this->repositorio->buscaPorEmail($email) !== null && $dadosAtuaisUsuario->email() !== $email){
            throw new Exception("O email informado já está cadastrado");
        }

        $usuario = new Usuario(
            $id,
            $nome,
            $email,
            $cpf,
            $dadosAtuaisUsuario->senha(),
            'operador',
            $dadosAtuaisUsuario->criadoEm(),
            $dadosAtuaisUsuario->isAtivo()
        );

        $this->repositorio->update($usuario);
    }

    public function alterarStatusUsuario(int $id)
    {
        $dadosAtuaisUsuario = $this->repositorio->buscaPorId($id);
        if ($dadosAtuaisUsuario === null) {
            throw new Exception("O usuário informado não existe");
        }

            if($dadosAtuaisUsuario->isAtivo()){
                $this->repositorio->desativarUsuario($id);
            } else{
                $this->repositorio->ativarUsuario($id);
            }
    }

    public function alterarSenhaUsuario(int $id, string $novaSenha, string $confirmaSenha): void
    {
        $dadosAtuaisUsuario = $this->repositorio->buscaPorId($id);

        if ($dadosAtuaisUsuario === null) {
            throw new Exception("O usuário informado não existe");
        }

        if(!$dadosAtuaisUsuario->isAtivo()){
            throw new Exception("Não é possível redefinir a senha de usuários inativos");
        }

        $this->validaSenha($novaSenha, $confirmaSenha);

        $hashDaNovaSenha = password_hash($novaSenha, PASSWORD_ARGON2ID);

        $this->repositorio->alterarSenha($id, $hashDaNovaSenha);
    }

    //Lógica para validação do CPF
    private function validaCpf(string $cpf): void
    {
        // Verifica se o CPF tem 11 dígitos numéricos
        if (!preg_match('/^\d{11}$/', $cpf)) {
            throw new Exception("O CPF precisa ter 11 dígitos numéricos");
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/^(\d)\1*$/', $cpf)) {
            throw new Exception("O CPF informado é inválido.");
        }

        //Calcula o primeiro dígito
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int)$cpf[$i] * (10 - $i);
        }
        $primeiroDigito = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

        //Calcula o segundo dígito
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int)$cpf[$i] * (11 - $i);
        }
        $segundoDigito = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

        $cpfValido = ($cpf[9] == $primeiroDigito && $cpf[10] == $segundoDigito);
        if (!$cpfValido) {
            throw new Exception("O CPF informado é inválido.");
        }
    }

    private function validaEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("O e-mail informado é inválido");
        }
    }

    private function validaSenha(string $senha, string $confirmaSenha): void
    {
        if($senha !== $confirmaSenha){
            throw new Exception("A confirmação da senha deve ser igual à senha");
        }

        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres.");
        }
    }
}