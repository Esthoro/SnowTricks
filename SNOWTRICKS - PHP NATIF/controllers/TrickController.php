<?php

require_once 'C:\xampp\htdocs\Snowtricks\assets\vendor\autoload.php';
require_once  __DIR__ . '/functions.php';

use App\Trick;
use App\Illustration;
use App\Video;

$Trick = new Trick();
$Illustration = new Illustration();
$Video = new Video();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_POST = cleanRequest($_POST);

    //Création d'un trick
    if (!empty($_POST['createTrick']) && $_POST['createTrick'] === 'OK') {
        if (!empty($_POST['trickName']) && !empty($_POST['trickDescription']) && !empty($_POST['groupeTrickSelect'])) {

            $userId = isConnected();

            if ($userId) {

                if (!$Trick->createTrickProcess($_POST['trickName'], $_POST['trickDescription'], $_POST['groupeTrickSelect'], $userId)) {
                    jsonResponse(false, 'Erreur lors de la création du trick.');
                }

                //Gestion des images
                if (!$Illustration->uploadTrickIllustrations($_FILES['trickPictures'], $Trick->getId())) {
                    jsonResponse(false, 'Erreur lors de l\'upload des images.');
                }

                //Gestion des vidéos
                if (!empty($_POST['video_embed']) && !$Video->setTrickVideos($_POST['video_embed'], $Trick->getId())) {
                    jsonResponse(false, 'Erreur lors de l\'upload des vidéos.');
                }
                jsonResponse(true, 'Trick créé avec succès !');
            }
            jsonResponse(false, 'Vous devez être connecté pour créer un trick.');
        }
        jsonResponse(false, 'Veuillez remplit tous les champs obligatoires.');
    }

    //Mise à jour d'un trick
    if (!empty($_POST['trickName']) && !empty($_POST['trickDescription']) && !empty($_POST['trickGroupeName']) && !empty($_POST['trickId']) && !empty($_POST['updateTrick']) && $_POST['updateTrick'] === 'OK') {

        if (isConnected()) {

            if (!$Trick->updateTrick($_POST['trickName'], $_POST['trickDescription'], $_POST['trickGroupeName'], $_POST['trickId'])) {
                jsonResponse(false, 'Erreur lors de la création du trick.');
            }

            jsonResponse(true, 'Trick mis à jour avec succès !');
        }
        jsonResponse(false, 'Vous devez être connecté pour modifier un trick.');
    }

    //Suppression d'un trick
    if (!empty($_POST['action']) && $_POST['action'] === 'DELETE_TRICK' && !empty($_POST['idTrick'])) {
        if (isConnected()) {
            $Trick->setId($_POST['idTrick']);
            if ($Trick->deleteTrick()) {
                jsonResponse(true, 'Trick supprimé avec succès !');
            }
            jsonResponse(false, 'Erreur lors de la suppression du trick.');
        }
        jsonResponse(false, 'Vous devez être connecté pour créer un trick.');
    }

}