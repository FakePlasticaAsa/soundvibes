document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="estado_animo"]');

    radios.forEach(radio => {
        radio.addEventListener('click', function() {

                    // cambiar el background colour
            switch (this.value) {
                case 'Feliz':
                    document.body.style.backgroundColor = '#FFD700'; // Amarillo
                    break;
                case 'Triste':
                    document.body.style.backgroundColor = '#2196F3'; // Azul
                    break;
                case 'Energético':
                    document.body.style.backgroundColor = '#FF5722'; // Naranja
                    break;
                case 'Relajado':
                    document.body.style.backgroundColor = '#4CAF50'; // Verde
                    break;
                case 'Inspirado':
                    document.body.style.backgroundColor = '#9C27B0'; // Morado
                    break;
                case 'Estresado':
                    document.body.style.backgroundColor = '#F44336'; // Rojo
                    break;
                default:
                    document.body.style.backgroundColor = 'black'; // Negro (default)
            }
        });
    });
});