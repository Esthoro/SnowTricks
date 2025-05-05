<?php

require_once 'C:\xampp\htdocs\Snowtricks\assets\vendor\autoload.php';
require_once  __DIR__ . '/functions.php';

use App\User;


$User = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $_POST = cleanRequest($_POST);
    $_SESSION = cleanRequest($_SESSION);

    //Inscription
    if (!empty($_POST["nameRegistration"]) && !empty($_POST["emailRegistration"]) && !empty($_POST["passwordRegistration"]) && !empty($_POST["passwordConfirmation"])) {

        $profilePicture = $_FILES['profilePicture'] ?? null;

        $userRegistration = $User->registrationProcess($_POST['nameRegistration'], $_POST['emailRegistration'], $_POST['passwordRegistration'], $_POST['passwordConfirmation'], $profilePicture);
        echo json_encode($userRegistration);
        exit();
    }

    //Login
    if (!empty($_POST['emailLogin']) && !empty($_POST['passwordLogin'])) {
        if ($User->login($_POST['emailLogin'], $_POST['passwordLogin'])) {
            echo json_encode(['success' => true, 'message' => 'Connexion réussie !']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur']);
        }
        exit();
    }

    //Logout
    if (isset($_POST['Logout']) && $_POST['Logout'] === 'OK') {
        if (logout()) {
            echo json_encode(['success' => true]);
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Erreur']);
        exit();
    }

    //Mdp oublié
    if (!empty($_POST['emailforgotPwd']) && !empty($_POST['forgotPwd']) && $_POST['forgotPwd'] === 'OK') {
        $result = $User->forgotPwdProcess($_POST['emailforgotPwd']);
        echo json_encode($result);
        exit();
    }

    //Réinitialisation mdp
    if (isset($_POST['resetPwd']) && $_POST['resetPwd'] === 'OK' && !empty($_POST['newPwd']) && !empty($_POST['token'])) {
        $hashedPassword = password_hash($_POST['newPwd'], PASSWORD_DEFAULT);
        if ($User->updatePassword($_POST['token'], $hashedPassword)) {
            echo json_encode(['success' => true, 'message' => 'Réinitialisation du mot de passe réussie !']);
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Erreur']);
        exit();
    }

}

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $_GET = cleanRequest($_GET);

    //Confirmation inscription
    if (isset($_GET['confIns']) && $_GET['confIns'] === "OK" && isset($_GET['token'])) {
        $token = $_GET['token'];
        $userConfirmation = $User->confirmUser($token);
        header('Location: /Snowtricks/login/?Inscription=' . $userConfirmation);
    }

    //Confirmation réinitialisation mdp
    if (isset($_GET['confPwd']) && $_GET['confPwd'] === "OK" && isset($_GET['token'])) {
        $token = $_GET['token'];
        $email = $User->checkToken($token);
        if ($email && $User->showByEmail($email)) {
            header('Location: /Snowtricks/resetPassword/?token=' . $token);
        } else {
            header('Location: /Snowtricks/404/');
        }
    }
}

