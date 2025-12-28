<?php

namespace App\Controllers;

use App\Models\Article;
use Smarty;

class ArticleController
{
    private Article $articleModel;
    private Smarty $smarty;
    private array $config;

    public function __construct(Article $articleModel, Smarty $smarty, array $config)
    {
        $this->articleModel = $articleModel;
        $this->smarty = $smarty;
        $this->config = $config;
    }

    public function show(string $slug): void
    {
        $article = $this->articleModel->findBySlug($slug);

        if (!$article) {
            http_response_code(404);
            $this->smarty->assign('pageTitle', 'Статья не найдена');
            $this->smarty->display('pages/404.tpl');
            return;
        }

        $this->articleModel->incrementViews($article['id']);

        $categories = $this->articleModel->getCategoriesByArticle($article['id']);
        $categoryIds = array_column($categories, 'id');

        $similarArticles = $this->articleModel->getSimilarArticles(
            $article['id'],
            $categoryIds,
            $this->config['similar_posts_count']
        );

        $this->smarty->assign('article', $article);
        $this->smarty->assign('categories', $categories);
        $this->smarty->assign('similarArticles', $similarArticles);
        $this->smarty->assign('pageTitle', $article['title']);
        $this->smarty->display('pages/article.tpl');
    }
}
