<?php
require_once(APP . '/vistas/inc/encabezado.php');

if (!empty($mensajes)) {
	foreach ($mensajes as $mensaje) {
		echo '<div id="mensajes">' . $mensaje . '</div>';
	}
}

if (!empty($status)) echo $status;

// Nuevo
if (isset($nuevo)) {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<div class="card-header">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Información de la Campaña</span>
				</h3>
			</div>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Nombre</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="nombre" required>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Campaña</button>
							<a href="<?php echo STASIS; ?>/catalogos/campanas" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Modificar
} elseif (isset($modificar)) {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<div class="card-header">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Información de la Campaña</span>
				</h3>
			</div>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Nombre</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="nombre" required value="<?php echo $info->nombre; ?>">
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="id" value="<?php echo $info->id; ?>">
							<input type="hidden" name="modificarGuardar" value="1">
							<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
							<a href="<?php echo STASIS; ?>/catalogos/campanas" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Listado de Tipificaciones
} else {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b card-stretch ">
			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
				</h3>

				<div class="card-toolbar">
					<div class="input-icon">
						<input type="text" class="form-control" placeholder="Buscar..." id="kt_datatable_search">
						<span>
							<i class="las la-search text-muted"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="card-body pt-2">
				<div class="row">
					<div class="col-md-12">

						<table class="table table-bordered table-striped kt_datatable-0">
							<thead>
								<tr>
									<th>Folio Contrato</th>
									<th>Contrato</th>
									<th>Propietario</th>
									<th>Empresa</th>
									<th>Lote</th>
									<th>Inicio de Contrato</th>
									<th>Fin de Contrato</th>
									<th>Tiempo</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($listado['activos'] as $datos) {
								?>
								<tr>
									<td><?php echo $datos['id']; ?></td>
									<td><?php echo $datos['id_contrato']; ?></td>
									<td><?php echo $datos['arrendatario']; ?></td>
									<td><?php echo $datos['arrendadora']; ?></td>
									<td style="white-space: nowrap;"><?php echo $datos['lote']; ?></td>
									<td><?php echo $datos['inicio_vig']; ?></td>
									<td><?php echo $datos['fin_vig']; ?></td>
									<td style="white-space: nowrap;"><?php echo $datos['tiempo']; ?></td>
								</tr>
								<?php
								}
								?>
							</tbody>
						</table>
						
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
}
?>

<?php
require_once(APP . '/vistas/inc/pie_pagina.php');