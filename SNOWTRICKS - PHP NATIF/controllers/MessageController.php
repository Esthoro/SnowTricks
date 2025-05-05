<?php

require_once 'C:\xampp\htdocs\Snowtricks\assets\vendor\autoload.php';
require_once  __DIR__ . '/functions.php';

use App\Message;

$Message = new Message();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_POST = cleanRequest($_POST);

    if (isset($_POST["action"]) && $_POST["action"] === "comment" && isset($_POST["content"]) && isset($_POST["trickId"])) {

        if (empty($_POST["content"]) || empty($_POST["trickId"])) {
            jsonResponse(false, 'Veuillez remplir tous les champs');
        }

        if ($userId = isConnected()) {

            if ($Message->createMessage($_POST["content"], $userId, $_POST["trickId"])) {
                jsonResponse(true, 'Message envoyé !');
            }
            jsonResponse(false, 'Erreur lors de l\'envoi du message.');
        }
        jsonResponse(false, 'Vous devez être connecté pour laisser un message.');
    }
}
