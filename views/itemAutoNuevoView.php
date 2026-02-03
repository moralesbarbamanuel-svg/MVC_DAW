<!-- Incluimos la cabecera -->
<?php include_once("common/cabecera.php"); ?>

<!-- Incluimos un menú para la aplicación -->
<?php include_once("common/menu.php"); ?>

<h1>Crear Item Auto</h1>
<form action="index.php" method="post" class="w-50">
	<input type="hidden" name="controlador" value="ItemAuto">
	<input type="hidden" name="accion" value="nuevo">

	<div class="mb-3">
		<label for="nombre" class="form-label">Nombre</label>
		<input type="text" class="form-control" name="nombre" id="nombre"
			value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
		<?php if (isset($errores["nombre"])): ?>
			<div class="text-danger small"><?php echo $errores["nombre"]; ?></div><?php endif; ?>
	</div>

	<button type="submit" name="submit" class="btn btn-primary">Aceptar</button>
	<a class="btn btn-secondary" href="index.php?controlador=ItemAuto&accion=listar">Cancelar</a>
</form>

<?php if (isset($errores) && count($errores) > 0): ?>
	<div class="mt-3">
		<?php foreach ($errores as $error): ?>
			<div class="alert alert-danger mb-1"><?php echo $error; ?></div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<!-- Incluimos el pie de la página -->
<?php include_once("common/pie.php"); ?>