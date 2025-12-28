<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Article;
use Smarty;

class HomeController
{
    private Category $categoryModel;
    private Article $articleModel;
    private Smarty $smarty;
    private array $config;

    public function __construct(Category $categoryModel, Article $articleModel, Smarty $smarty, array $config)
    {
        $this->categoryModel = $categoryModel;
        $this->articleModel = $articleModel;
        $this->smarty = $smarty;
        $this->config = $config;
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getCategoriesWithArticles();

        $categoriesWithArticles = [];
        foreach ($categories as $category) {
            $articles = $this->articleModel->getLatestByCategory(
                $category['id'],
                $this->config['home_posts_per_category']
            );

            if (!empty($articles)) {
                $categoriesWithArticles[] = [
                    'category' => $category,
                    'articles' => $articles,
                ];
            }
        }

        $this->smarty->assign('categoriesWithArticles', $categoriesWithArticles);
        $this->smarty->assign('pageTitle', 'Главная');
        $this->smarty->display('pages/home.tpl');
    }
}
