<!-- VAZIO

Qual sua função?
Ser o "molde" ou "template mestre" de todas as suas páginas. Ele contém todo o HTML que se repete: <!DOCTYPE>, <html>, <head>, <meta>, os links para o style.css e app.js, e talvez um cabeçalho e rodapé fixos. Ele define a estrutura visual principal do site.

Como se comunica?
Os seus templates de página (home.php, busca.php) se tornarão mais simples. Em vez de terem a estrutura HTML completa, eles apenas incluirão o cabeçalho do layout no início e o rodapé no final. A forma mais simples de fazer isso é criando _partials/_header.php e _partials/_footer.php e incluindo-os em cada página.

Próximo Passo: Criar esses arquivos parciais de layout (_header.php, _footer.php) com o HTML base. -->