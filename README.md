# 🚧 Roadmap do Projeto

## ⏳ Próximos Passos (Foco na Interface)

Esta é a lista de tarefas restantes para finalizar a interface do usuário e a experiência visual do MVP.

### 1. Refinamento e Layout
- [ ] **Criar Layout Padrão:** Desenvolver os arquivos `templates/_partials/_header.php` e `templates/_partials/_footer.php` com o HTML comum (cabeçalho, menu, etc.) para garantir uma aparência consistente em todas as páginas.
- [ ] **Estilização (CSS):** Criar e aplicar estilos básicos no arquivo `public/assets/css/style.css` para dar uma aparência profissional e limpa à aplicação.

### 2. Página de Busca (`/busca`)
- [ ] **Criar Template (`templates/busca.php`):** Montar a estrutura HTML da página, que deve incluir:
    - [ ] O formulário de filtros (`numero`, `data_inicio`, `data_fim`).
    - [ ] A tabela para listar os protocolos.
- [ ] **Adicionar Botões de Ação:** Na tabela de listagem, adicionar um link "Editar" e um botão "Excluir" para cada protocolo.

### 3. Funcionalidade de Edição (Update)
- [ ] **Criar Template de Edição (`templates/editar.php`):** Construir o formulário HTML que será pré-preenchido com os dados do protocolo a ser editado.

### 4. Funcionalidade de Exclusão (Delete)
- [ ] **Implementar o Formulário de Exclusão:** Garantir que o botão "Excluir" na listagem esteja dentro de um `<form method="post">` que envie o ID do protocolo.
- [ ] **Adicionar Pop-up de Confirmação (JavaScript):** Implementar o alerta `confirm('Tem certeza?')` no arquivo `public/assets/js/app.js` para a ação de excluir.

### 5. Mensagens de Feedback para o Usuário
- [ ] **Implementar Mensagens de Sucesso:** Usar sessões PHP para exibir uma mensagem de sucesso após uma adição, edição ou exclusão bem-sucedida (ex: "Protocolo salvo com sucesso!").