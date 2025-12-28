<?php

namespace App;

class Router
{
    private array $routes = [];

    public function get(string $pattern, callable $callback): void
    {
        $this->routes['GET'][$pattern] = $callback;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }

        foreach ($this->routes[$method] as $pattern => $callback) {
            $regex = $this->convertPatternToRegex($pattern);

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($callback, $matches);
                return;
            }
        }

        $this->notFound();
    }

    private function convertPatternToRegex(string $pattern): string
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9\-_]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    private function notFound(): void
    {
        http_response_code(404);

        require_once __DIR__ . '/../vendor/autoload.php';

        $smarty = new \Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../templates');
        $smarty->setCompileDir(__DIR__ . '/../templates_c');
        $smarty->setCacheDir(__DIR__ . '/../cache');

        $smarty->assign('pageTitle', 'Страница не найдена');
        $smarty->display('pages/404.tpl');
    }
}
