<?php

require_once 'C:\xampp\htdocs\Snowtricks\assets\vendor\autoload.php';

use App\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

const CRYPTING_KEY = 'snowtricks!2905';  // Clé secrète chiffrement token email d'inscription
const CRYPTING_METHOD = 'aes-256-cbc'; // L'algorithme de chiffrement (AES 256 bits en mode CBC)

/**
 * Function to clean requests
 *
 * @param mixed $data
 * @param array|null $exclude
 *
 * @return array|string
 */
function cleanRequest($data)
{
    if (is_array($data)) {

        foreach ($data as $key => $value) {

            $data[$key] = cleanRequest($value);
        }

    } else {
        $data = cleanData($data);
    }

    return $data;
}

function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendMail($name, $email, $subject, $message) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    /*$mail->SMTPDebug = SMTP::DEBUG_SERVER;*/
    $mail->SMTPDebug = 0;
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'mail.gandi.net';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    $mail->Username = '****@*****.fr';
    $mail->Password = '*****';
    $mail->setFrom('*****@*****.fr', 'Snowtricks');

    $mail->addAddress($email, $name);

    $mail->Subject = $subject;

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
    $mail->msgHTML($message);

//Replace the plain text body with one created manually
    $mail->AltBody = $message;

    //Desactivate certificate - ONLY FOR LOCALHOST !!! SECURITY RISK
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

//send the message, check for errors
    if (!$mail->send()) {
        return 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        return true;
    }
}

function isConnected()
{
    if (isset($_SESSION['logged']) && $_SESSION['logged'] === 'true') {
        if (isset($_SESSION['userId'])) {
            return $_SESSION['userId'];
        }
    }
    return false;
}

function logout() : bool
{
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
        return true;
    }
    return false;
}

function generateToken($string) : string {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(CRYPTING_METHOD));
    $encryptedData = openssl_encrypt($string, CRYPTING_METHOD, CRYPTING_KEY, 0, $iv);
    return base64_encode($encryptedData . '::' . $iv);
}

function jsonResponse ($succes, $response) {
    echo json_encode(['success' => $succes, 'message' => $response]);
    exit();
}

