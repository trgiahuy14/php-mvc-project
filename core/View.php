<?php

declare(strict_types=1);

if (!defined('APP_KEY')) die('Access denied');

class View
{
    private string $viewPath;
    private string $layoutPath;

    public function __construct()
    {
        $this->viewPath = dirname(__DIR__) . '/app/Views/';
        $this->layoutPath = dirname(__DIR__) . '/app/Views/layouts/';
    }

    // Render a view with optional layout
    public function render(string $view, ?string $layout = null, array $data = []): void
    {
        $content = $this->renderView($view, $data);

        if ($layout) {
            echo $this->renderLayout($layout, array_merge($data, ['content' => $content]));
        } else {
            echo $content;
        }
    }

    // Render a view file and return content
    private function renderView(string $view, array $data = []): string
    {
        $path = $this->viewPath . $view . '.php';

        if (!file_exists($path)) {
            throw new Exception("View not found: {$path}");
        }

        extract($data);

        ob_start();
        require $path;
        return ob_get_clean();
    }

    // Render a layout file and return content
    private function renderLayout(string $layout, array $data = []): string
    {
        $path = $this->layoutPath . $layout . '.php';

        if (!file_exists($path)) {
            throw new Exception("Layout not found: {$path}");
        }

        extract($data);

        ob_start();
        require $path;
        return ob_get_clean();
    }

    // Render a partial
    public function partial(string $partial, array $data = []): void
    {
        $path = $this->viewPath . $partial . '.php';

        if (!file_exists($path)) {
            throw new \Exception("Partial not found: {$path}");
        }

        extract($data);
        require $path;
    }

    // Check if a view exists
    public function exists(string $view): bool
    {
        return file_exists($this->viewPath . $view . '.php');
    }
}
