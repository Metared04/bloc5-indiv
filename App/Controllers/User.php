<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;

/**
 * User controller
 */
class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {
        if(isset($_POST['submit'])){

            $userFormData = $_POST;
            
            if($this->login($userFormData)){
                header('Location: /account');
                exit;
            }
        }

        View::renderTemplate('User/login.html');
    }

    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if(isset($_POST['submit'])){
            $userFormData = $_POST;

            if (empty($userFormData['username']) || empty($userFormData['email']) || empty($userFormData['password']) || empty($userFormData['password-check'])){
                View::renderTemplate('User/register.html');
                return;
            }

            if($userFormData['password'] !== $userFormData['password-check']){
                // TODO: Gestion d'erreur côté utilisateur
                View::renderTemplate('User/register.html');
                return;
            }

            $userId = $this->register($userFormData);

            if (!$userId) {
                // TODO: flash error — email déjà utilisé ou erreur serveur
                View::renderTemplate('User/register.html');
                return;
            }

            if ($this->login($userFormData)) {
                header('Location: /account');
                exit;
            }

            // validation

            //$this->register($userFormData);
            //$this->login($userFormData);
            //header('Location: /account');
            //return;
            header('Location: /login');
            exit;
        }

        View::renderTemplate('User/register.html');
    }

    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }

    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);

            return $userID;

        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            /* Utility\Flash::danger($ex->getMessage());*/
            error_log('[User::register] ' . $ex->getMessage());
            return false;
        }
    }

    private function login($data){
        try {
            if(!isset($data['email'])){
                throw new Exception('TODO');
            }

            $user = \App\Models\User::getByLogin($data['email']);

            if (Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                return false;
            }

            if(isset($data['remember_me']) && $data['remember_me'] === '1'){
                $expire = time() + (30 * 24 * 60 * 60); // 30 jours
                setcookie('remember_email', $data['email'], $expire, '/');
                setcookie('remember_token', Hash::generate($data['email'], $user['salt']), $expire, '/');
            }

            $_SESSION['user'] = array(
                'id' => $user['id'],
                'username' => $user['username'],
            );

            return true;

        } catch (Exception $ex) {
            // TODO : Set flash if error
            //Utility\Flash::danger($ex->getMessage());
        }
    }


    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction() {

        /*
        if (isset($_COOKIE[$cookie])){
            // TODO: Delete the users remember me cookie if one has been stored.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L148
        }*/
        // Destroy all data registered to the session.
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header ("Location: /");

        return true;
    }

}
