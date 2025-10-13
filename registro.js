document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formularioRegistro');
    const alerta = document.getElementById('alerta-js');

    formulario.addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value;
        const contrasena = document.getElementById('contrasena').value;

        // Validación simple: verificar que la contraseña tenga al menos 6 caracteres
        if (contrasena.length < 6) {
            e.preventDefault(); // Detiene el envío del formulario al PHP
            alerta.style.color = 'red';
            alerta.textContent = 'La contraseña debe tener al menos 6 caracteres.';
            return false;
        }

        // Si todo va bien, no hace e.preventDefault(), y el formulario se envía al PHP.
        // Después de enviar, el PHP se encarga de mostrar el mensaje de éxito/error.
    });
});