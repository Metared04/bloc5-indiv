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
        if(isset($_POST['submit'])){
            $articleId = $_POST['article_id'];
            header('Location: /product/' . $articleId . '?sent=1');
            exit;
        }
        header('Location: /');
    }
}
