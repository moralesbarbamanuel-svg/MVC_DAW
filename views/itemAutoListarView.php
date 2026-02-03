<!-- Incluimos la cabecera -->
<?php include_once("common/cabecera.php"); ?>

<!-- Incluimos el menú -->
<?php include_once("common/menu.php"); ?>

<h1>Listado Items Auto</h1>
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
                            href="index.php?controlador=ItemAuto&accion=editar&codigo=<?php echo $item->getCodigo() ?>">Editar</a>
                        <a class="btn btn-sm btn-danger"
                            href="index.php?controlador=ItemAuto&accion=borrar&codigo=<?php echo $item->getCodigo() ?>">Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<a class="btn btn-success" href="index.php?controlador=ItemAuto&accion=nuevo">Nuevo</a>

<!-- Incluimos el pie de página -->
<?php include_once("common/pie.php"); ?>