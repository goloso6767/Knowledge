const formLogin = document.getElementById('form-login');
const alertaGato = document.getElementById('alerta-gato');
const gatoMensaje = document.getElementById('gato-mensaje');

formLogin.addEventListener('submit', async function(e) {
    e.preventDefault();

    const correo = document.getElementById('correo').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch('backend/login_usuario.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo, password })
        });

        const result = await response.json();

        if (result.status === 'success') {
            alertaGato.classList.remove('mostrar_alerta');
            alertaGato.classList.add('oculto');
            
            // Redirección según el rol guardado en MySQL
            if (result.rol === 'alumno') window.location.href = 'dashboards/dashboard-alumno.html';
            else if (result.rol === 'docente') window.location.href = 'dashboards/dashboard-docente.html';
            else if (result.rol === 'padre') window.location.href = 'dashboards/dashboard-padre.html';
            else window.location.href = 'dashboards/dashboard-admin.html';

        } else {
            // Activa la alerta del gatito a un lado
            gatoMensaje.textContent = result.mensaje + " 🙀";
            alertaGato.classList.remove('oculto');
            alertaGato.classList.add('mostrar_alerta');
        }

    } catch (error) {
        gatoMensaje.textContent = "Error de conexión con la base de datos 😿";
        alertaGato.classList.remove('oculto');
        alertaGato.classList.add('mostrar_alerta');
    }
});