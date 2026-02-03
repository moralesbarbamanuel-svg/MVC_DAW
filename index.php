<?php
// Este será el punto de entrada siempre en nuestra aplicación web

//Incluimos el FrontController que es el controlador de inicio de la aplicación
require 'libs/FrontController.php';

//Lo iniciamos con su método estático main.
FrontController::main();
?>