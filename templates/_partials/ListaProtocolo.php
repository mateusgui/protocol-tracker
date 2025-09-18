<!-- Sua Dúvida: É como se fosse um componente, fazendo analogia com React ou Vue?

Sim, sua analogia com componentes do React/Vue está perfeita! É exatamente esse o conceito.

Qual sua função?
É um "componente" ou "bloco de HTML reutilizável". A única função dele é conter o código da <table> que desenha a lista de protocolos.

Como se comunica?
Ele não se comunica ativamente. Outros templates (como o busca.php e futuramente um relatorios.php) que precisarem exibir a mesma tabela irão simplesmente incluí-lo com require. O template "pai" será o responsável por preparar a variável $listaDeProtocolos que este "componente" irá usar.

Próximo Passo: Quando formos construir a templates/busca.php, vamos criar a tabela de listagem. Depois, podemos mover o código da tabela para dentro deste arquivo para reutilizá-lo. -->