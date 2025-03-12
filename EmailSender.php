<?php
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailSender
{
    private $mail;

    public function __construct()
    {
        // Crear instancia de PHPMailer
        $this->mail = new PHPMailer(true);

        // Configuración SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'glowbiteria.mx'; // Servidor SMTP
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'mensajes@glowbiteria.mx'; // Usuario SMTP
        $this->mail->Password = 'G0ub1t3r1a2022'; // Contraseña SMTP
        $this->mail->SMTPSecure = 'tls'; // Encriptación
        $this->mail->Port = 587; // Puerto SMTP
        $this->mail->CharSet = 'UTF-8'; // Codificación de caracteres


        // Configurar remitente predeterminado
        $this->mail->setFrom('mensajes@glowbiteria.mx', 'Glowbiteria');
    }

    // Método para enviar correos
    public function sendMail($asunto, $body,$imagen)
    {
        try {
            // Configurar destinatario
            $this->mail->addAddress("ahernandez@glowbiteria.mx", "Almendra");
            $this->mail->addCC('almendrahernandez86@gmail.com');  
            $this->mail->addCC('mc.munoz.rz@gmail.com');  
            // Configurar asunto y cuerpo del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $body;

            $this->mail->AddEmbeddedImage($imagen, 'imagen');
            // Enviar correo
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Manejar errores de envío
            return false;
        }
    }
}
