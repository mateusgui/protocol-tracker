<!-- (Vazio)
Qual sua função?
Centralizar todas as regras de validação de um protocolo em um único lugar. Em vez da lógica if (strlen($numero) !== 6 || !ctype_digit($numero)) estar dentro da ProtocoloService, ela estaria aqui, em um método como public function validarNumero(string $numero).

Como se comunica?

É chamado pela ProtocoloService. A Service receberia os dados crus, criaria uma instância do ValidadorProtocolo e o usaria para verificar se os dados são válidos antes de continuar.

Próximo Passo: Para este projeto, como as regras de validação são poucas e simples, manter a validação dentro da ProtocoloService é totalmente aceitável. Este arquivo representa uma boa prática para projetos maiores, mas você não precisa implementá-lo agora.-->