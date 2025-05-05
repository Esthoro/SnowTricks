<?php

namespace App;

use App\DB;
use PDO;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $photoPath;

    public bool $status;

    const CRYPTING_KEY = 'snowtricks!2905';  // La clé secrète
    const CRYPTING_METHOD = 'aes-256-cbc'; // L'algorithme de chiffrement (AES 256 bits en mode CBC)

    const ALLOWED_PICTURE_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
    const DEFAULT_PROFILE_PICTURE = '/Snowtricks/assets/img/defaultProfilePicture.png';

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPhotoPath(): string
    {
        return $this->photoPath;
    }

    /**
     * @param string $photoPath
     */
    public function setPhotoPath(string $photoPath): void
    {
        $this->photoPath = $photoPath;
    }

    public function register(): bool
    {
        $sql = 'INSERT INTO users (name, email, password, photoPath, status)
                VALUES (:name, :email, :password, :photoPath, :status)';
        $params = array(
            ':name' => $this->name,
            ':email' => $this->email,
            ':password' => $this->password,
            ':photoPath' => $this->photoPath,
            ':status' => 0
        );

        if (!DB::exec($sql, $params)) {
            return false;
        }
        return true;
    }

    public function login ($email, $password) {
        $User = new User();

        $User->setEmail($email);
        $User->setPassword($password);

        if ($userId = $User->verifyUser()) {
            $_SESSION['logged'] = 'true';
            $_SESSION['email'] = $email;
            $_SESSION['userId'] = $userId;
            return $userId;
        }
        return false;
    }

    public function showByEmail($email) {
        $sql = 'SELECT * FROM users
        WHERE email = :email
        AND status = 1';

        $params = array(':email' => $email);

        if ($result = DB::exec($sql, $params)) {

            if ($result->rowCount() == 1) {

                return $result->fetch(\PDO::FETCH_OBJ);
            }
        }
        return false;
    }
    public function updatePassword($token, $newPassword): bool
    {
        $email = $this->checkToken($token);
        if ($email) {
            $user = $this->showByEmail($email);
            if ($user) {
                $sql = 'UPDATE users
                SET password = :password
                WHERE id = :id';
                $params = array(
                    ':password' => $newPassword,
                    ':id' => $user->id
                );

                if (DB::exec($sql, $params)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function forgotPwdProcess(string $email): array
    {
        if ($user = $this->showByEmail($email)) {
            $name = $user->name;
            $email = $user->email;
            $token = generateToken($user->email);
            if ($this->sendForgotPwdEmail($name, $email, $token)) {
                return ['success' => true, 'message' => 'Utilisateur reconnu - un email contenant le lien de réinitialisation vous a été envoyé.'];
            }
            return ['success' => false, 'message' => 'Erreur'];
        }
        return ['success' => false, 'message' => 'Utilisateur non reconnu.'];
    }

    public function verifyUser() {
        $sql = 'SELECT * FROM users
        WHERE email = :email
        AND status = 1';

        $params = array(':email' => $this->email);

        if ($result = DB::exec($sql, $params)) {

            if ($result->rowCount() == 1) {

                $user = $result->fetch(\PDO::FETCH_OBJ);

                if (password_verify($this->password, $user->password)) {
                    return $user->id;
                }
            }
        }
        return false;
    }
    public function logout() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            header('Location: /Snowtricks/');
        }
    }

    public function checkToken($token) {
        list($encryptedData, $iv) = explode('::', base64_decode($token), 2);
        return openssl_decrypt($encryptedData, self::CRYPTING_METHOD, self::CRYPTING_KEY, 0, $iv);
    }

    public function confirmUser($token) {

        $email = $this->checkToken($token);

        if ($email) {

            $sql = 'SELECT * FROM users
        WHERE email = :email';

            $params = array(':email' => $email);

            if ($result = DB::exec($sql, $params)) {

                if ($result->rowCount() === 1) {

                    $user = $result->fetch(\PDO::FETCH_OBJ);

                    if ($user->status == 0) {
                        $sql = 'UPDATE users
                        SET status = 1
                        WHERE email = :email';
                        $params2 = array(':email' => $email);
                        if (DB::exec($sql, $params2)) {
                            return true;
                        }
                    } else if ($user->status == 1) {
                        return 'AC';
                    }
                }
            }
        }
        return false;
    }

    public function registrationProcess ($name, $email, $password, $passwordConfirmation, $profilePicture): array
    {
        $picturePath = '';

        if ($this->showByEmail($email)) {
            return ['success' => false, 'message' => 'Utilisateur déjà inscrit.'];
        }

        if (empty($name) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Tous les champs sont requis.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email invalide.'];
        }
        if ($password != $passwordConfirmation) {
            return ['success' => false, 'message' => 'Les deux mots de passe ne correspondent pas.'];
        }
        if ($profilePicture && !$picturePath = $this->importProfilePicture($profilePicture)) {
            return ['success' => false, 'message' => 'Erreur lors du téléchargement de l\'image.'];
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($hashedPassword);
        $this->setPhotoPath($picturePath);

        if ($this->register()) {

            //Génération du token de sécurité
            $token = generateToken($email);

            if ($this->sendInscriptionEmail($name, $email, $token)) {
                return ['success' => true, 'message' => 'Inscription réussie ! Un email de confirmation vous a été envoyé.'];
            }
        }
        return ['success' => false, 'message' => 'Une erreur est survenue.'];
    }

    public function sendInscriptionEmail(string $name, string $email, string $token): bool
    {
        $message = "Bienvenue chez <b>Snowtricks</b> !<br><br>";
        $message .= "Pour confirmer votre inscription, cliquez ici :<br><br>";
        $message .= '<a href="http://localhost/Snowtricks/controllers/AuthController.php?confIns=OK&token=' . $token . '">Confirmer mon inscription</a>';

        return sendMail($name, $email, 'Demande d\'inscription', $message);
    }

    public function importProfilePicture ($picture) {
        if ($picture && $picture['error'] === UPLOAD_ERR_OK) {

            if ($picture['size'] > 1048576 || //1Mo
                !in_array($picture['type'], self::ALLOWED_PICTURE_TYPES)) {
                return false;
            }

            $uploadDir = __DIR__ . '/../assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '-' . basename($picture['name']);
            $uploadPath = $uploadDir . $fileName;

            if (!move_uploaded_file($picture['tmp_name'], $uploadPath)) {
                return false;
            }
            $picture = 'uploads/' . $fileName;
        } else {
            $picture = self::DEFAULT_PROFILE_PICTURE;
        }
        return $picture;
    }

    public function sendForgotPwdEmail(string $name, string $email, string $token): bool
    {
        $message = "Pour réinitialiser votre mot de passe, cliquez ici :<br><br>";
        $message .= '<a href="http://localhost/Snowtricks/controllers/AuthController.php?confPwd=OK&token=' . $token . '">Réinitialiser mon mot de passe</a>';

        return sendMail($name, $email, 'Réinitialisation de votre mot de passe Snowtricks', $message);
    }
}