<?php
// Controlador para el modelo ItemModel (puede haber más controladores en la aplicación)
// Un controlador no tiene porque estar asociado a un objeto del modelo
class ItemAutoController {
    // Atributo con el motor de plantillas del microframework
    protected $view;

    // Constructor. Únicamente instancia un objeto View y lo asigna al atributo
    function __construct() {
        //Creamos una instancia de nuestro mini motor de plantillas
        $this->view = new View();
    }

    // Método del controlador para listar los items almacenados
    public function listar() {
        //Incluye el modelo que corresponde
        require 'models/ItemAutoModel.php';

        //Creamos una instancia de nuestro "modelo"
        $items = new ItemAutoModel();

        //Le pedimos al modelo todos los items
        $listado = $items->getAll();

        //Pasamos a la vista toda la información que se desea representar
        $data['items'] = $listado;

        // Finalmente presentamos nuestra plantilla 
        // Llamamos al método "show" de la clase View, que es el motor de plantillas
        // Genera la vista de respuesta a partir de la plantilla y de los datos
        $this->view->show("itemAutoListarView.php", $data);
    }

    // Método del controlador para crear un nuevo item
    public function nuevo() {
        require 'models/ItemAutoModel.php';
        $item = new ItemAutoModel();

        $errores = array();

        // Si recibe por GET o POST el objeto y lo guarda en la BG
        if (isset($_REQUEST['submit'])) {
            // Comprobamos si se ha recibido el nombre
            if (!isset($_REQUEST['nombre']) || empty($_REQUEST['nombre']))
                $errores['nombre'] = "* Nombre: debes indicar un nombre.";

            // Si no hay errores actualizamos en la BD
            if (empty($errores)) {
                $item->setNombre($_REQUEST['nombre']);
                $item->save();

                // Finalmente llama al método listar para que devuelva vista con listado
                header("Location: index.php?controlador=ItemAuto&accion=listar");
            }
        }

        // Si no recibe el item para añadir, devuelve la vista para añadir un nuevo item
        $this->view->show("itemAutoNuevoView.php", array('errores' => $errores));



    }

    // Método que procesa la petición para editar un item
    public function editar() {

        require 'models/ItemAutoModel.php';
        $items = new ItemAutoModel();

        // Recuperar el item con el código recibido
        $item = $items->getById($_REQUEST['codigo']);

        if ($item == null) {
            $this->view->show("errorView.php", array('error' => 'No existe codigo'));
        }

        $errores = array();

        // Si se ha pulsado el botón de actualizar
        if (isset($_REQUEST['submit'])) {

            // Comprobamos si se ha recibido el nombre
            if (!isset($_REQUEST['nombre']) || empty($_REQUEST['nombre']))
                $errores['nombre'] = "* Nombre: es obligatorio el nombre.";

            // Si no hay errores actualizamos en la BD
            if (empty($errores)) {
                // Cambia el valor del item y lo guarda en BD
                $item->setNombre($_REQUEST['nombre']);
                $item->save();

                // Reenvía a la aplicación a la lista de items
                header("Location: index.php?controlador=ItemAuto&accion=listar");
            }
        }

        // Si no se ha pulsado el botón de actualizar se carga la vista para editar el item
        $this->view->show("itemAutoEditarView.php", array('item' => $item, 'errores' => $errores));



    }

    // Método para borrar un item 
    public function borrar() {
        //Incluye el modelo que corresponde
        require_once 'models/ItemAutoModel.php';

        //Creamos una instancia de nuestro "modelo"
        $items = new ItemAutoModel();

        // Recupera el item con el código recibido por GET o por POST
        $item = $items->getById($_REQUEST['codigo']);

        if ($item == null) {
            $this->view->show("errorView.php", array('error' => 'No existe codigo'));
        } else {
            // Si existe lo elimina de la base de datos y vuelve al inicio de la aplicación
            $item->delete();
            header("Location: index.php?controlador=ItemAuto&accion=listar");
        }
    }

}
?>