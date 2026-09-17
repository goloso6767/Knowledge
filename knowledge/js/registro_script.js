document.addEventListener('DOMContentLoaded', () => {
    const formRegistro = document.getElementById('form-registro');
    const alertaGato = document.getElementById('alerta-gato');
    const gatoMensaje = document.getElementById('gato-mensaje');
    const gatoImg = document.getElementById('gato-img');
    const rolOptions = document.querySelectorAll('.rol-option');

    // Selector de roles interactivo
    if (rolOptions) {
        rolOptions.forEach(option => {
            option.addEventListener('click', () => {
                rolOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                option.querySelector('input').checked = true;
            });
        });
    }

    // Función para controlar la alerta del gato en el cliente
    function mostrarAlerta(mensaje, esExito = false) {
        if (!alertaGato || !gatoMensaje || !gatoImg) return;

        gatoMensaje.textContent = mensaje;
        
        if (esExito) {
            gatoImg.src = "img/gato_x2.jpg"; 
            alertaGato.classList.remove('alerta-error');
            alertaGato.classList.add('alerta-exito');
        } else {
            gatoImg.src = "img/gato_x3.jpg"; 
            alertaGato.classList.remove('alerta-exito');
            alertaGato.classList.add('alerta-error');
        }

        alertaGato.classList.remove('oculto');
        alertaGato.classList.add('mostrar_alerta');
    }

    // Validaciones preventivas antes del envío por POST a PHP
    if (formRegistro) {
        formRegistro.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();

            if (password !== confirmPassword) {
                e.preventDefault();
                mostrarAlerta("Las contraseñas no coinciden 😿", false);
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                mostrarAlerta("La contraseña debe tener al menos 6 caracteres 😿", false);
                return;
            }
        });
    }
});

// Función global para alternar ver/ocultar contraseña (fuera del DOMContentLoaded)
function verPassword(inputId, eyeIcon) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.textContent = '🙈';
    } else {
        input.type = 'password';
        eyeIcon.textContent = '👁️';
    }
}