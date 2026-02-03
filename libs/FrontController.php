<?php
// FrontController: enruta requests a controladores
class FrontController
{
    public static function main()
    {
        // Cargar configuración y librerías necesarias
        if (file_exists(__DIR__ . '/config.php'))
            require_once __DIR__ . '/config.php';
        if (file_exists(__DIR__ . '/SPDO.php'))
            require_once __DIR__ . '/SPDO.php';
        if (file_exists(__DIR__ . '/View.php'))
            require_once __DIR__ . '/View.php';

        // Controlador y acción por defecto
        $controller = isset($_REQUEST['controlador']) ? $_REQUEST['controlador'] : 'Item';
        $action = isset($_REQUEST['accion']) ? $_REQUEST['accion'] : 'listar';

        $controllerClass = $controller . 'Controller';
        $controllerFile = __DIR__ . '/../controllers/' . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            // Mostrar error
            $view = new View();
            $view->show('errorView.php', array('error' => 'Controlador no encontrado: ' . $controllerFile));
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            $view = new View();
            $view->show('errorView.php', array('error' => 'Clase de controlador no encontrada: ' . $controllerClass));
            return;
        }

        $controllerObj = new $controllerClass();

        if (!method_exists($controllerObj, $action)) {
            $view = new View();
            $view->show('errorView.php', array('error' => 'Acción no encontrada: ' . $action));
            return;
        }

        // Llamar acción
        $controllerObj->$action();
    }
}

?>