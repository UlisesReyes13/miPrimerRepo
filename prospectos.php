<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Diciembre-2024*/
header('Content-Type: application/json');
require '../administrador/conexion/conexion.php';
require '../lib/EmailSender.php';

// Manejar advertencias como excepciones
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Función para generar el cuerpo del correo electrónico
function generarCuerpoCorreo($pro_nombre, $pro_email, $pro_telefono, $pro_comentarios)
{
    return '<!doctype html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <style>
                p { font-size: 14px; }
                .signature { font-style: italic; }
            </style>
        </head>
        <body>
            <p>Correo emitido desde página Glowbiteria.</p>
            <p>Nombre: ' . htmlspecialchars($pro_nombre) . '</p>
            <p>Correo: ' . htmlspecialchars($pro_email) . '</p>
            <p>Teléfono: ' . htmlspecialchars($pro_telefono) . '</p>
            <p>Comentarios: ' . htmlspecialchars($pro_comentarios) . '</p>
            <br>
            <p>Atentamente,</p>
            <p>Página Glowbiteria</p>
        </body>
        </html>';
}

// Conectar a la base de datos
$cnx = Conectarse();

try {
    // Validar datos
    if (empty($_POST["txt_nombre"]) || empty($_POST["txt_correo"]) || empty($_POST["txt_telefono"])) {
        throw new Exception("Todos los campos son requeridos");
    }

    // Escapar datos para mayor seguridad
    $pro_nombre = mysqli_real_escape_string($cnx, $_POST["txt_nombre"]);
    $pro_email = mysqli_real_escape_string($cnx, $_POST["txt_correo"]);
    $pro_telefono = mysqli_real_escape_string($cnx, $_POST["txt_telefono"]);
    $pro_comentarios = mysqli_real_escape_string($cnx, $_POST["txt_comentarios"]);

    $query = "INSERT INTO prospectos (pro_nombre, pro_email, pro_telefono, pro_comentarios) 
    VALUES ('$pro_nombre', '$pro_email', '$pro_telefono', '$pro_comentarios')";


    // Ejecutar consulta
    if (!mysqli_query($cnx, $query)) {
        throw new Exception("Error al insertar datos en la base de datos.");
    }

    // Si la inserción fue exitosa, enviar el correo
    $mailSender = new MailSender();
    $imagen = "../img/image/logo_globiteria.png";
    $asunto = 'Contacto en página Glowbiteria';
    $body = generarCuerpoCorreo($pro_nombre, $pro_email, $pro_telefono, $pro_comentarios);

    if (!$mailSender->sendMail($asunto, $body, $imagen)) {
        throw new Exception("Error al enviar el correo.");
    }

    // Respuesta exitosa
    $message = [
        'status' => 'success',
        'title' => 'Información enviada!',
        'message' => 'En breve nos pondremos en contacto con usted!'
    ];
} catch (Exception $e) {
    // Capturar error y enviar como respuesta JSON
    $message = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

// Cerrar la conexión
$cnx->close();

// Devolver respuesta JSON
echo json_encode($message);
