# 🚧 Roadmap do Projeto (Próximos Passos)

Esta é a lista de tarefas restantes para finalizar a versão MVP (Produto Mínimo Viável) do sistema.

### Página de Busca (`/busca`)
- [ ] **Criar Template:** Desenvolver a estrutura HTML do arquivo `templates/busca.php`.
- [ ] **Formulário de Filtros:** Implementar o formulário HTML em `busca.php` com os campos:
    - [ ] Campo para `número do protocolo`.
    - [ ] Campo para `data de início`.
    - [ ] Campo para `data de fim`.
- [ ] **Lógica de Filtro:** Implementar a lógica no `index.php` (na rota `/busca`) para processar os filtros enviados pelo formulário e exibir apenas os resultados correspondentes.
- [ ] **Listagem Completa:** Exibir a tabela com todos os protocolos na página (`$repositorio->all()`) quando nenhum filtro for aplicado.

### Funcionalidades de Edição e Exclusão
- [ ] **Adicionar Botões de Ação:** Na tabela de listagem em `busca.php`, adicionar um botão/link de "Editar" e um de "Excluir" para cada protocolo.
- [ ] **Implementar Exclusão (Delete):**
    - [ ] Fazer o botão "Excluir" submeter um formulário `POST` para uma nova rota (ex: `/excluir`).
    - [ ] Adicionar um pop-up de confirmação em JavaScript ("Tem certeza?") antes de enviar o formulário de exclusão.
    - [ ] Implementar a lógica no `index.php` para a rota `/excluir`, chamando o método `$repositorio->delete($id)` e redirecionando de volta para a página de busca.
- [ ] **Implementar Edição (Update):**
    - [ ] Criar a rota (`/editar`) e o template (`templates/editar.php`).
    - [ ] Fazer a rota `/editar` buscar os dados de um protocolo específico pelo `id` e passá-los para o template `editar.php`.
    - [ ] Construir o formulário em `editar.php`, pré-preenchido com os dados do protocolo.
    - [ ] Fazer o formulário de edição submeter um `POST` para a rota `/editar`, que chamará o método `update` do repositório.

### Refinamento e Layout
- [ ] **Criar Layout Padrão:** Desenvolver arquivos `templates/_partials/_header.php` e `templates/_partials/_footer.php` com o HTML comum (cabeçalho, menu, rodapé) e incluí-los em `home.php` e `busca.php`.
- [ ] **Estilização (CSS):** Criar e aplicar estilos básicos no arquivo `public/assets/css/style.css` para dar uma aparência profissional e limpa à aplicação.