<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\Database\Database;
use App\Models\Category;
use App\Models\Article;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\ArticleController;

$appConfig = require __DIR__ . '/../config/app.php';

$pdo = Database::getConnection();

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');
$smarty->setCacheDir(__DIR__ . '/../cache');

$smarty->assign('baseUrl', $appConfig['base_url']);
$smarty->assign('uploadsUrl', $appConfig['uploads_url']);

$categoryModel = new Category($pdo);
$articleModel = new Article($pdo);

$router = new Router();

$router->get('/', function () use ($categoryModel, $articleModel, $smarty, $appConfig) {
    $controller = new HomeController($categoryModel, $articleModel, $smarty, $appConfig);
    $controller->index();
});

$router->get('/category/{slug}', function ($slug) use ($categoryModel, $articleModel, $smarty, $appConfig) {
    $controller = new CategoryController($categoryModel, $articleModel, $smarty, $appConfig);
    $controller->show($slug);
});

$router->get('/article/{slug}', function ($slug) use ($articleModel, $smarty, $appConfig) {
    $controller = new ArticleController($articleModel, $smarty, $appConfig);
    $controller->show($slug);
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
