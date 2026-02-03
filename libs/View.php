<?php
class View
{
    public function __construct()
    {
        // constructor vacío
    }

    // Muestra una vista que se encuentra en la carpeta views/
    public function show($view, $data = array())
    {
        if (is_array($data))
            extract($data);
        $viewPath = __DIR__ . '/../views/' . $view;
        if (!file_exists($viewPath)) {
            die('Vista no encontrada: ' . $view);
        }
        include $viewPath;
    }
}

?>