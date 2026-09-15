const formRegistro = document.getElementById('form-registro');
const alertaGato = document.getElementById('alerta-gato');
const gatoMensaje = document.getElementById('gato-mensaje');
const gatoImg = document.getElementById('gato-img');
const rolOptions = document.querySelectorAll('.rol-option');

// Selector de roles interactivo
rolOptions.forEach(option => {
    option.addEventListener('click', () => {
        rolOptions.forEach(opt => opt.classList.remove('active'));
        option.classList.add('active');
        option.querySelector('input').checked = true;
    });
});

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

// Función para controlar la alerta del gato
function mostrarAlerta(mensaje, esExito = false) {
    gatoMensaje.textContent = mensaje;
    
    if (esExito) {
        gatoImg.src = "../img/gato_x2.jpg"; 
        alertaGato.classList.remove('alerta-error');
        alertaGato.classList.add('alerta-exito');
    } else {
        gatoImg.src = "../img/gato_x3.jpg"; 
        alertaGato.classList.remove('alerta-exito');
        alertaGato.classList.add('alerta-error');
    }

    alertaGato.classList.remove('oculto');
    alertaGato.classList.add('mostrar_alerta');
}

formRegistro.addEventListener('submit', async function(e) {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();
    const rol = document.querySelector('input[name="rol"]:checked').value;

    if (password !== confirmPassword) {
        mostrarAlerta("Las contraseñas no coinciden 😿", false);
        return;
    }

    if (password.length < 6) {
        mostrarAlerta("La contraseña debe tener al menos 6 caracteres 😿", false);
        return;
    }

    try {
        const response = await fetch('../backend/registro_usuario.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, correo, password, rol })
        });

        const result = await response.json();

        if (result.status === 'success') {
            mostrarAlerta(result.mensaje + " 😸", true);
            formRegistro.reset();
            setTimeout(() => {
                window.location.href = '../index.html';
            }, 2000);
        } else {
            mostrarAlerta(result.mensaje + " 🙀", false);
        }

    } catch (error) {
        mostrarAlerta("Error al conectar con el servidor 😿", false);
    }
});