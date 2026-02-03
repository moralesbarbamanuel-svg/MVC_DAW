<!-- Incluimos la cabecera -->
<?php include_once("common/cabecera.php"); ?>

<!-- Incluimos un menú para la aplicación -->
<?php include_once("common/menu.php"); ?>

<!-- Parte específica de nuestra vista -->
<h1>Listado de Items</h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item->getCodigo() ?></td>
                    <td><?php echo $item->getNombre() ?></td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-primary"
                            href="index.php?controlador=Item&accion=editar&codigo=<?php echo $item->getCodigo() ?>">Editar</a>
                        <a class="btn btn-sm btn-danger"
                            href="index.php?controlador=Item&accion=borrar&codigo=<?php echo $item->getCodigo() ?>">Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<a class="btn btn-success" href="index.php?controlador=Item&accion=nuevo">Nuevo</a>

<!-- Incluimos el pie de la página -->
<?php include_once("common/pie.php"); ?>