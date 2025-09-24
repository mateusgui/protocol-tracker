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

    // Garante que o botão existe antes de adicionar o evento de clique
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            // Verifica se o modo escuro já está ativo
            const isDarkMode = document.body.classList.contains('dark-mode');
            
            if (isDarkMode) {
                // Se estiver, muda para o claro e salva a preferência
                localStorage.setItem('theme', 'light');
                aplicarTema('light');
            } else {
                // Se não, muda para o escuro e salva a preferência
                localStorage.setItem('theme', 'dark');
                aplicarTema('dark');
            }
        });
    }

});