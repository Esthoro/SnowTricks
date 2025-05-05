<?php

session_start();

require_once __DIR__ . '/assets/vendor/autoload.php';

require_once 'controllers/TrickController.php'; //CRUD des tricks
require_once 'controllers/PagesController.php'; //Affichage des pages
require_once 'controllers/AuthController.php'; //Inscription, Login, logout
require_once 'controllers/PasswordController.php'; //CRUD mdp

$_GET = cleanRequest($_GET);

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'trick':
        $slugTrick = $_GET['slug'] ?? null;
        if ($slugTrick) {
            singleTrickPage($slugTrick);
        } else {
            errorPage();
        }
        break;
		
	case 'createTrick':
		isConnected() ? createTrickPage() : errorPage();
        break;

    case 'updateTrick':
        if (isConnected()) {
            $trickId = $_GET['id'] ?? null;
            if (is_numeric($trickId) && $trickId > 0) {
                updateTrickPage($trickId);
            }
		} else {
            errorPage();
        }
        break;

    case 'login':
        isConnected() ? homePage() : loginPage();
        break;
		
	case 'registration':
        isConnected() ? homePage() : registerPage();
        break;
		
	case 'forgotPassword':
		forgotPasswordPage();
		break;
		
	case 'resetPassword':
		resetPasswordPage();
		break;

    case '404':
        errorPage();
        break;

    default:
        homePage();
        break;
}
