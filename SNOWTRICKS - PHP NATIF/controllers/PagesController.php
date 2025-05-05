<?php

require_once 'C:\xampp\htdocs\Snowtricks\assets\vendor\autoload.php';
require_once  __DIR__ . '/functions.php';

use App\Illustration;
use App\Video;
use App\User;
use App\Trick;
use App\Message;

function singleTrickPage($slugTrick) {

    $Trick = new Trick();
    $User = new User();
    $Message = new Message();
    $Illustration = new Illustration();
    $Video = new Video();

    $Trick->setSlug($slugTrick);
	
    $trick = $Trick->showBySlug();
    $firstIllustration = $Trick->getFirstIllustration($trick->id);
    $allIllustrations = $Illustration->showByTrickId($trick->id);
    $author = $Trick->getTrickAuthor($trick->id);
    $allVideos = $Video->showByTrickId($trick->id);
    $messages = $Message->showByTrickSlug($slugTrick);
    require ('views/trick.php');
}

function updateTrickPage($trickId) {
    $Illustration = new Illustration();
    $Video = new Video();
    $Trick = new Trick();
    $Trick->setId($trickId);
    $trick = $Trick->showById();
    $illustration = $Trick->getFirstIllustration($trick->id);
    $allIllustrations = $Illustration->showByTrickId($trick->id);
    $allVideos = $Video->showByTrickId($trick->id);
    require ('views/updateTrick.php');
}

function homePage () {

    $Trick = new Trick();
    $allTricks = $Trick->showAllTricks();

    $allIllustrations = $Trick->showFirstIllustrations($allTricks);

    $isConnected = false;

    if (isConnected()) {
        $isConnected = true;
    }

	require ('views/home.php');
}

function errorPage () {
	require ('views/404.php');
}

function registerPage () {
	require ('views/registration.php');
}

function loginPage () {
	require ('views/login.php');
}

function createTrickPage() {
	require('views/createTrick.php');
}
function resetPasswordPage () {
    require ('views/resetPassword.php');
}

function forgotPasswordPage () {
    require ('views/forgotPassword.php');
}


