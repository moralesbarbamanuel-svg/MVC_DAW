<!-- Incluimos la cabecera -->
<?php include_once("common/cabecera.php"); ?>

<!-- Vista para editar un elemento de la tabla -->

<body>
    <!-- Incluimos un menú para la aplicación -->
    <?php include_once("common/menu.php"); ?>

    <!-- Parte específica de nuestra vista -->
    <p>Se ha producido un error</p></br>

    <?php
    if (isset($error))
        echo $error . "</br>";
    ?>

    <!-- Incluimos el pie de la página -->
    <?php include_once("common/pie.php"); ?>
</body>

</html>