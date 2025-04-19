<?php

namespace App\Controllers;

use App\Services\ResponseService;
use App\Models\ArticleModel;
use App\Services\QrCodeService;

class ArticleController extends Controller
{
    private $articleModel;

    function __construct()
    {
        $this->articleModel = new ArticleModel();
    }

    /**
     * Get paginated list of articles
     */
    function getAll()
    {
        $page = (int)($_GET["page"] ?? 1);
        ResponseService::Send($this->articleModel->getAll($page));
    }
    function get($id)
    {
        ResponseService::Send($this->articleModel->get($id));
    }
    function getQrCode($id)
    {
        $article_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/article/" . $id;
        echo QrCodeService::CreateHtmlPreviewImage($article_url);
    }
    function create()
    {
        // get data from $_POST request using base class method
        $data = $this->decodePostData();

        // validate input using base class method
        $this->validateInput(["title", "content", "author"], $data);

        // save to DB
        $newArticle = $this->articleModel->create($data);

        // send the newly created object back to user
        ResponseService::Send($newArticle);
    }
    function update($id)
    {
        // get data from PUT request
        $data = $this->decodePostData();

        // validate input
        $this->validateInput(["title", "content", "author"], $data);

        // update in DB
        $updatedArticle = $this->articleModel->update($id, $data);

        // send the updated object back to user
        ResponseService::Send($updatedArticle);
    }
    function delete($id)
    {
        $this->articleModel->delete($id);
        ResponseService::Send([], 204);
    }
}
