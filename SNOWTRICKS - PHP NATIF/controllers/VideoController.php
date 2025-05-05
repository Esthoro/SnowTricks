<?php

require_once 'C:\xampp\htdocs\Snowtricks\assets\vendor\autoload.php';
require_once  __DIR__ . '/functions.php';

use App\Video;

$Video = new Video();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_POST = cleanRequest($_POST);
    $_SESSION = cleanRequest($_SESSION);

    //Supprimer vidéo
    if (!empty($_POST['action']) && $_POST['action'] === "DELETE_VIDEO" && !empty($_POST['idVideo'])) {

        if (isConnected()) {

            $Video->setId($_POST['idVideo']);

            if ($Video->delete()) {
                jsonResponse(true, 'Vidéo supprimée avec succès !');
            }
            jsonResponse(false, 'Erreur lors de la suppression de la vidéo !');
        }
        jsonResponse(false, 'Vous devez être connecté pour modifier un trick.');
    }

    //Modifier vidéo
    if (!empty($_POST['action']) && $_POST['action'] === 'UPDATE_VIDEO' && !empty($_POST['idVideo']) && !empty($_POST['embedCodeVideo']) && !empty($_POST['idTrick'])) {
        if (isConnected()) {

            $Video->setId($_POST['idVideo']);

            if (!$Video->delete()) {
                jsonResponse(false, 'Erreur lors de la modification de la vidéo.');
            }

            if (!$Video->createVideo($_POST['idTrick'], $_POST['embedCodeVideo'])) {
                jsonResponse(false, 'Erreur lors de l\'upload de la vidéo.');
            }
            jsonResponse(true, 'Modification réussie !');
        }
        jsonResponse(false, 'Vous devez être connecté pour modifier un trick.');
    }

    jsonResponse(false, 'Erreur');

}