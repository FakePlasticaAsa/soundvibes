<?php
// Si se envia un formulario, sigue.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // selección de animo (si hay, se sigue el código)
    if (isset($_POST['estado_animo'])) {
        // obtener el estado de ánimo
        $estadoAnimo = htmlspecialchars($_POST['estado_animo']);
        
        // Dependiendo el estado de ánimo, el fondo cambia
        $backgroundColor = '';
        if ($estadoAnimo == "Inspirado") {
            $backgroundColor = 'background-color: #9C27B0;';
        } elseif ($estadoAnimo == "Feliz") {
            $backgroundColor = 'background-color: #FFD700;'; 
        } elseif ($estadoAnimo == "Triste") {
            $backgroundColor = 'background-color: #2196F3;'; 
        } elseif ($estadoAnimo == "Energético") {
            $backgroundColor = 'background-color: #FF5722;'; 
        } elseif ($estadoAnimo == "Estresado") {
            $backgroundColor = 'background-color: #F44336;'; 
        } elseif ($estadoAnimo == "Relajado") {
            $backgroundColor = 'background-color: #4CAF50;'; 
        } else { 
            $backgroundColor = 'background-color: black;';
        }
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Recomendador de Canciones</title>
            <link rel="stylesheet" href="style.css"> <!-- Asegúrate de que la ruta sea correcta -->
        </head>
        <body style="' . $backgroundColor . '">'; 
        
        echo "<h1>Tu estado de ánimo es: $estadoAnimo</h1>";

        echo '<div class="video-container">'; // div para centrar el video
        if ($estadoAnimo == "Relajado") {
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/9cBUrIw2sQk" frameborder="0" allowfullscreen></iframe>';
        } elseif ($estadoAnimo == 'Feliz') {
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/sU0FVs9Enq8" frameborder="0" allowfullscreen></iframe>';
        } elseif ($estadoAnimo == 'Triste') { 
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/Bfurc6KcMwk" frameborder="0" allowfullscreen></iframe>'; 
        } elseif ($estadoAnimo == 'Energético') {
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/fKyXvNkGQKc" frameborder="0" allowfullscreen></iframe>'; 
        } elseif ($estadoAnimo == 'Inspirado') {
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/27c3JaZq4_c" frameborder="0" allowfullscreen></iframe>'; 
        } elseif ($estadoAnimo == 'Estresado') {
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/6isz01iv8Ls" frameborder="0" allowfullscreen></iframe>';
        } else {
            echo "<h1>No hay un video asignado para el estado de ánimo seleccionado.</h1>";
        }
        echo '</div>'; // Cerrar el div de videos

        echo '<button type="button" onclick="window.history.back()" class="buttonBack">Volver al Menú</button>';
        echo '</body></html>';
    } 
    
} else {
    // Si no se ha enviado el formulario, mostrar el formulario principal
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recomendador de Canciones</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>¡Selecciona tu estado de ánimo!</h1>
        
        <form action="" method="POST"> // enviar estado de animo
            <div class="radio"> // div para contenedores
                <label>
                    <input type="radio" name="estado_animo" value="Feliz" class="hidden-radio">
                    <span class="clickable">Feliz</span>
                </label>
                <label>
                    <input type="radio" name="estado_animo" value="Triste" class="hidden-radio">
                    <span class="clickable">Triste</span>
                </label>
                <label>
                    <input type="radio" name="estado_animo" value="Energético" class="hidden-radio">
                    <span class="clickable">Energético</span>
                </label>
                <label>
                    <input type="radio" name="estado_animo" value="Relajado" class="hidden-radio">
                    <span class="clickable">Relajado</span>
                </label>
                <label>
                    <input type="radio" name="estado_animo" value="Inspirado" class="hidden-radio">
                    <span class="clickable">Inspirado</span>
                </label>
                <label>
                    <input type="radio" name="estado_animo" value="Estresado" class="hidden-radio">
                    <span class="clickable">Estresado</span>
                </label>
            </div>
            
            <button type="submit">Enviar</button>  // enviar boton
        </form>
        
        <script src="script.js" defer></script> 
    </body>
    </html>
    <?php
}
?>