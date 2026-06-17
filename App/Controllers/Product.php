<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;

/**
 * Product controller
 */
class Product extends \Core\Controller
{

    /**
     * Affiche la page d'ajout
     * @return void
     */
    public function indexAction()
    {

        if(isset($_POST['submit'])) {

            try {
                $formData = $_POST;

                $formData['user_id'] = $_SESSION['user']['id'];

                if(!isset($_FILES['picture']) || $_FILES['picture']['error'] !== UPLOAD_ERR_OK){
                    throw new \Exception('Une photo est obligatoire.');
                }

                $article = Articles::save($formData);

                $pictureName = Upload::uploadFile($_FILES['picture'], $article);

                Articles::attachPicture($article, $pictureName);

                header('Location: /product/' . $article);
                exit;
            } catch (\Exception $e){
                View::renderTemplate('Product/Add.html', ['error' => $e->getMessage()]);
                return;
            }
        }

        View::renderTemplate('Product/Add.html');
    }

    /**
     * Affiche la page d'un produit
     * @return void
     */
    public function showAction()
    {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
        } catch(\Exception $e){
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions
        ]);
    }
    public function contactAction()
    {
        /*
        if(isset($_POST['submit'])){
            $articleId = $_POST['article_id'];
            header('Location: /product/' . $articleId . '?sent=1');
            exit;
        }
        header('Location: /');
        */
        if (isset($_POST['submit'])) {
            $articleId = (int) ($_POST['article_id'] ?? 0);
            $email = trim($_POST['contact_email'] ?? '');
            $message = trim($_POST['contact_message'] ?? '');

            if ($articleId <= 0) {
                header('Location: /');
                exit;
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: /product/' . $articleId . '?error=email');
                exit;
            }

            if (empty($message)) {
                header('Location: /product/' . $articleId . '?error=message');
                exit;
            }

            try {

                $article = Articles::getOne($articleId);

                if (empty($article)) {
                    header('Location: /');
                    exit;
                }
                $article = $article[0];

                \App\Utility\Mailer::sendContactMail(
                    $article['email'],
                    $article['username'],
                    $email,
                    $article['name'],
                    $message
                );

                header('Location: /product/' . $articleId . '?sent=1');

            } catch (\Exception $e) {
                //error_log('[contactAction] ' . $e->getMessage());
                //header('Location: /product/' . $articleId . '?error=server');
                die('Erreur : ' . $e->getMessage());
            }

            exit;
        }

        header('Location: /');
        exit;
    }
}
