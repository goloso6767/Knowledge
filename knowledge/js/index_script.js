// Función para alternar ver/ocultar contraseña
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

// Control de eventos del formulario si prefieres validación dinámica
document.addEventListener('DOMContentLoaded', () => {
    const formIndex = document.getElementById('form-index');
    const aleirtaGato = document.getElementById('alerta-gato');
    const gatoMensaje = document.getElementById('gato-mensaje');

    if (formIndex) {
        formIndex.addEventListener('submit', function(e) {
            const correo = document.getElementById('correo').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!correo || !password) {
                e.preventDefault();
                if (gatoMensaje && alertaGato) {
                    gatoMensaje.textContent = "Por favor completa todos los campos 😿";
                    alertaGato.classList.remove('oculto');
                    alertaGato.classList.add('mostrar_alerta');
                }
            }
        });
    }
});