// Obtiene el formulario y el botón (mejor escuchar el evento 'submit' del formulario)
const formulario = document.getElementById('formulario'); 
const enviarBtn = document.getElementById('boton'); // Mantener para el clic

// 1. **RECOMENDADO**: Escuchar el evento 'submit' del formulario
formulario.addEventListener('submit', generarIngresoYDetenerEnvio);

// 2. Opcional: Mantener la escucha del 'click' si prefieres esa sintaxis
// enviarBtn.addEventListener('click', generarIngresoYDetenerEnvio); 

// Función que se ejecuta al hacer clic en el botón o al enviar el formulario
function generarIngresoYDetenerEnvio(event) {
    // ESTA ES LA CLAVE: Detiene el envío normal del formulario (la recarga)
    // Útil si quieres que PHP/BD lo maneje AJAX o si solo quieres mostrar los datos.
    event.preventDefault(); 
    
    // Si tu objetivo es enviar los datos a PHP/MSSQL, deberás añadir el código AJAX/Fetch aquí.
    
    // *****************************************************************
    // Tu código actual (solo para mostrar en pantalla) sigue igual:
    // *****************************************************************
    
    // Se obtienen los valores de los campos al momento del clic
    const jsnombre = document.getElementById('nombre').value;
    const jsedad = document.getElementById("edad").value;
    const jsgeneroSeleccionado = document.querySelector('input[name="genero"]:checked').value;
    const jspaisSeleccionado = document.querySelector('input[name="pais"]:checked').value;
    const jsprofesionSeleccionada = document.querySelector('input[name="profesion"]:checked').value;

    // Mostrar los valores en el div 'flowcontrol'
    document.getElementById('muestranombre').textContent = jsnombre;
    document.getElementById('muestraedad').textContent = jsedad;
    document.getElementById('muestragenero').textContent = jsgeneroSeleccionado;
    document.getElementById('muestrapais').textContent = jspaisSeleccionado;
    document.getElementById('muestraprofesion').textContent = jsprofesionSeleccionada;

    // Muestra el div oculto
    document.getElementById("flowcontrol").style.display = 'block';
    
    // *****************************************************************
}
















