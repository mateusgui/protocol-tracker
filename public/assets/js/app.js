document.addEventListener('DOMContentLoaded', () => {

    // --- LÓGICA PARA A FLASH MESSAGE ---
    const flashMessage = document.querySelector('.flash-message');

    if (flashMessage) {
        setTimeout(() => {
            flashMessage.classList.add('fade-out');
            setTimeout(() => {
                flashMessage.remove();
            }, 500);
        }, 4000);
    }


    // --- LÓGICA PARA O DARK MODE ---
    const themeToggle = document.getElementById('theme-toggle');

    // Função para aplicar e remover o tema
    function aplicarTema(tema) {
        if (tema === 'dark') {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }

    // Verifica se o usuário já tem uma preferência salva no navegador
    const temaSalvo = localStorage.getItem('theme');
    if (temaSalvo) {
        aplicarTema(temaSalvo);
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            // Verifica se o modo escuro já está ativo
            const isDarkMode = document.body.classList.contains('dark-mode');
            
            if (isDarkMode) {
                localStorage.setItem('theme', 'light');
                aplicarTema('light');
            } else {
                localStorage.setItem('theme', 'dark');
                aplicarTema('dark');
            }
        });
    }

    // --- LÓGICA PARA A MÁSCARA DE CPF ---
    const campoCpfVisivel = document.getElementById('cpf_formatado');
    const campoCpfPuro = document.getElementById('cpf_puro');

    if (campoCpfVisivel && campoCpfPuro) {
        
        campoCpfVisivel.addEventListener('input', () => {
            let valorLimpo = campoCpfVisivel.value.replace(/\D/g, '');

            valorLimpo = valorLimpo.substring(0, 11);

            campoCpfPuro.value = valorLimpo;

            let valorFormatado = valorLimpo;
            if (valorLimpo.length > 3) {
                valorFormatado = valorLimpo.replace(/(\d{3})(\d)/, '$1.$2');
            }
            if (valorLimpo.length > 6) {
                valorFormatado = valorFormatado.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            }
            if (valorLimpo.length > 9) {
                valorFormatado = valorFormatado.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
            }
            
            campoCpfVisivel.value = valorFormatado;
        });
    }

    // --- LÓGICA PARA O DROPDOWN DE USUÁRIO ---
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenu = document.getElementById('user-menu');

    if (userMenuToggle && userMenu) {
        userMenuToggle.addEventListener('click', (event) => {
            // Previne que o link '#' recarregue a página
            event.preventDefault();
            
            // Adiciona ou remove a classe 'show' no elemento PAI (.dropdown)
            userMenu.parentElement.classList.toggle('show');
        });

        // Fecha o dropdown se o usuário clicar fora dele
        window.addEventListener('click', (event) => {
            if (!userMenu.parentElement.contains(event.target)) {
                userMenu.parentElement.classList.remove('show');
            }
        });
    }

});