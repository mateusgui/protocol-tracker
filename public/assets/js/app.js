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
            event.preventDefault();
            userMenu.parentElement.classList.toggle('show');
        });

        window.addEventListener('click', (event) => {
            if (!userMenu.parentElement.contains(event.target)) {
                userMenu.parentElement.classList.remove('show');
            }
        });
    }


    // --- LÓGICA PARA INICIALIZAR O DATATABLES ---
    $('#tabela-protocolos').DataTable({
        searching: false,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json',
        },
        order: [], 
        pageLength: 5, 
        lengthMenu: [5, 10, 25, 50, 100],
    });

});