<?php

require_once 'C:\xampp\htdocs\Snowtricks\assets\vendor\autoload.php';
require_once  __DIR__ . '/functions.php';

use App\Illustration;

$Illustration = new Illustration();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_POST = cleanRequest($_POST);
    $_SESSION = cleanRequest($_SESSION);

    //Supprimer illustration
    if (!empty($_POST['action']) && $_POST['action'] === "DELETE_ILLUSTRATION" && !empty($_POST['idIllustration'])) {

        if (isConnected()) {

            $Illustration->setId($_POST['idIllustration']);

            if ($Illustration->delete()) {
                jsonResponse(true, 'Image supprimée avec succès !');
            }
            jsonResponse(false, 'Erreur lors de la suppression de l\'image !');
        }
        jsonResponse(false, 'Vous devez être connecté pour modifier un trick.');
    }

    //Modifier illustration
    if (!empty($_POST['UPDATE_ILLUSTRATION']) && $_POST['UPDATE_ILLUSTRATION'] === 'OK' && !empty($_POST['idIllustrationToUpdate']) && !empty($_POST['idTrickToUpdate']) && isset($_FILES['illustration'])) {
        if (isConnected()) {

            $Illustration->setId($_POST['idIllustrationToUpdate']);

            if (!$Illustration->delete()) {
                jsonResponse(false, 'Erreur lors de la modification de l\'image.');
            }
            if (!$Illustration->uploadTrickIllustrations($_FILES['illustration'], $_POST['idTrickToUpdate'])) {
                jsonResponse(false, 'Erreur lors de l\'upload de l\'image.');
            }
            jsonResponse(true, 'Modification réussie !');
        }
        jsonResponse(false, 'Vous devez être connecté pour modifier un trick.');
    }

    jsonResponse(false, 'Erreur');

}