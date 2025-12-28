<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Article;
use Smarty;

class CategoryController
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

    public function show(string $slug): void
    {
        $category = $this->categoryModel->findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            $this->smarty->assign('pageTitle', 'Категория не найдена');
            $this->smarty->display('pages/404.tpl');
            return;
        }

        $sortBy = $_GET['sort'] ?? 'published_at';
        $page = max(1, (int)($_GET['page'] ?? 1));

        $articles = $this->articleModel->getByCategoryWithPagination(
            $category['id'],
            $sortBy,
            $page,
            $this->config['per_page']
        );

        $totalArticles = $this->articleModel->getCountByCategory($category['id']);
        $totalPages = ceil($totalArticles / $this->config['per_page']);

        $this->smarty->assign('category', $category);
        $this->smarty->assign('articles', $articles);
        $this->smarty->assign('currentPage', $page);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('sortBy', $sortBy);
        $this->smarty->assign('pageTitle', $category['name']);
        $this->smarty->display('pages/category.tpl');
    }
}
