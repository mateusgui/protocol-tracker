const flashMessage = document.querySelector('.flash-message');

    // Se o elemento existir na página...
    if (flashMessage) {
        // ...espera 4 segundos (4000 milissegundos)...
        setTimeout(() => {
            // ...adiciona a classe 'fade-out' para iniciar a animação de desaparecer.
            flashMessage.classList.add('fade-out');
            
            // Espera a animação terminar (0.5s) para remover o elemento da página.
            setTimeout(() => {
                flashMessage.remove();
            }, 500);

        }, 4000);
    }