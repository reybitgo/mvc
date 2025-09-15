<?php

// C:\laragon\www\mvc\src\controllers\Controller.php

namespace Reybi\MVC\Controllers;

class Controller
{
    /**
     * Renders a view file.
     *
     * @param string $view The name of the view file (without .php).
     * @param array $data Data to pass to the view.
     * @return void
     */
    protected function view($view, $data = [])
    {
        // Extract the data array to make variables available in the view
        extract($data);

        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("View '{$view}' not found at: {$viewPath}");
        }
    }

    /**
     * Redirect to a specific URL
     *
     * @param string $url
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
}
