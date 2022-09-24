<?php
require_once(APP . '/vistas/encabezado.php');

if (!empty($mensajes)) {
	foreach ($mensajes as $mensaje) {
		echo '<div id="mensajes">' . $mensaje . '</div>';
	}
}

if (!empty($status)) echo $status;

// Nuevo
if (isset($nuevo)) {
?>

<div class="col-lg-12">
	<div class="kt-portlet">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Información del Empleado
				</h3>
			</div>
		</div>

		<form class="kt-form kt-form--label-right" action="" method="post">
			<div class="kt-portlet__body">
				<div class="form-group row">
					<label for="example-text-input" class="col-2 col-form-label">* Nombre</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text" name="nombre" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="example-search-input" class="col-2 col-form-label">* Apellidos</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text" name="apellidos" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="example-search-input" class="col-2 col-form-label">* Puesto Laboral</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text" name="puesto" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="example-email-input" class="col-2 col-form-label">* E-Mail</label>
					<div class="col-3">
						<input class="form-control minusculas" type="text" name="email" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="example-url-input" class="col-2 col-form-label">Teléfono</label>
					<div class="col-3">
						<input class="form-control numeric" type="text" name="telefono">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-tel-input" class="col-2 col-form-label">Celular</label>
					<div class="col-3">
						<input class="form-control numeric" type="text" name="celular">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-password-input" class="col-2 col-form-label">* Contraseña</label>
					<div class="col-3">
						<input class="form-control" type="password" name="contrasena1" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="example-number-input" class="col-2 col-form-label">* Repetir Contraseña</label>
					<div class="col-3">
						<input class="form-control" type="password" name="contrasena2" required>
					</div>
				</div>
			</div>
			<div class="kt-portlet__foot">
				<div class="kt-form__actions">
					<div class="row">
						<div class="col-2">
						</div>
						<div class="col-10">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-success">Agregar Empleado</button>
							<a href="<?php echo STASIS; ?>/empleados/administracion" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</div>
		</form>

	</div>
</div>

<?php
// Modificar
} elseif (isset($modificar)) {
?>

<div class="col-lg-12">
	<div class="kt-portlet">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Información del Empleado
				</h3>
			</div>
		</div>

		<form class="kt-form kt-form--label-right" action="" method="post">
			<div class="kt-portlet__body">
				<div class="form-group row">
					<label for="example-text-input" class="col-2 col-form-label">* Nombre</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text" name="nombre" required value="<?php echo $info->nombre; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-search-input" class="col-2 col-form-label">* Apellidos</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text" name="apellidos" required value="<?php echo $info->apellidos; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-search-input" class="col-2 col-form-label">* Puesto Laboral</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text" name="puesto" required value="<?php echo $info->puesto; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-email-input" class="col-2 col-form-label">* E-Mail</label>
					<div class="col-3">
						<input class="form-control minusculas" type="text" name="email" required value="<?php echo $info->email; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-url-input" class="col-2 col-form-label">Teléfono</label>
					<div class="col-3">
						<input class="form-control numeric" type="text" name="telefono" value="<?php echo $info->telefono; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-tel-input" class="col-2 col-form-label">Celular</label>
					<div class="col-3">
						<input class="form-control numeric" type="text" name="celular" value="<?php echo $info->celular; ?>">
					</div>
				</div>
			</div>
			<div class="kt-portlet__foot">
				<div class="kt-form__actions">
					<div class="row">
						<div class="col-2">
						</div>
						<div class="col-10">
							<input type="hidden" name="id" value="<?php echo $info->id; ?>">
							<input type="hidden" name="modificarGuardar" value="1">
							<button type="submit" class="btn btn-success">Aplicar Cambios</button>
							<a href="<?php echo STASIS; ?>/empleados/administracion" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</div>
		</form>

	</div>
</div>

<?php
// Listado de empleados
} else {
?>

<div class="col-lg-12">
	<div class="kt-portlet">
		<div class="kt-portlet__body">
			<div class="kt-section__body">

				<div class="form-group control-group">
					<a class="btn btn-primary" href="<?php echo STASIS; ?>/empleados/administracion/nuevo"><i class="fa fa-plus-circle"></i> Nuevo Empleado de Administración</a>
				</div>

				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#" data-target="#activos">Activos</a></li>
					<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#inactivos">Inactivos</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="activos" role="tabpanel">
						<table class="table tablesorter tabla-datos tabla-filtro">
				  			<thead>
				  				<tr>
							      	<th>Nombre</th>
							    	<th>Apellidos</th>
							    	<th>Puesto</th>
							    	<th>E-Mail</th>
							    	<th>Teléfono</th>
							    	<th>Celular</th>
							    	<th>Opciones</th>
						    	</tr>
						    </thead>
							<tbody>
								<?php
								foreach ($listado['activos'] as $dato) {
								?>
								<tr>
									<td><?php echo $dato['nombre']; ?></td>
									<td><?php echo $dato['apellidos']; ?></td>
									<td><?php echo $dato['puesto']; ?></td>
									<td><?php echo $dato['email']; ?></td>
									<td><?php echo $dato['telefono']; ?></td>
									<td><?php echo $dato['celular']; ?></td>
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
										    	Acción</a> <i class="icon-caret-down"></i>
										  	</button>

											<ul class="dropdown-menu">
												<li><a class="dropdown-item" href="<?php echo STASIS; ?>/empleados/administracion/modificar/<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-editar.png" /> Modificar</a></li>
												<li><a class="dropdown-item" href="<?php echo STASIS; ?>/empleados/administracion/inactivar/<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-inactivar.png" /> Inactivar</a></li>
											</ul>
										</div>
									</td>
								</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="inactivos" role="tabpanel">
						<table class="table teibolsorter tabla-datos tabla-filtro">
				  			<thead>
				  				<tr>
							      	<th>Nombre</th>
							    	<th>Apellidos</th>
							    	<th>Puesto</th>
							    	<th>E-Mail</th>
							    	<th>Teléfono</th>
							    	<th>Celular</th>
							    	<th>Opciones</th>
						    	</tr>
						    </thead>
							<tbody>
								<?php
								foreach ($listado['inactivos'] as $dato) {
								?>
								<tr>
									<td><?php echo $dato['nombre']; ?></td>
									<td><?php echo $dato['apellidos']; ?></td>
									<td><?php echo $dato['puesto']; ?></td>
									<td><?php echo $dato['email']; ?></td>
									<td><?php echo $dato['telefono']; ?></td>
									<td><?php echo $dato['celular']; ?></td>
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
										    	Acción</a> <i class="icon-caret-down"></i>
										  	</button>

											<ul class="dropdown-menu">
												<li><a class="dropdown-item" href="<?php echo STASIS; ?>/empleados/administracion/reactivar/<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-activar.png" /> Reactivar</a></li>
											</ul>
										</div>
									</td>
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
require_once(APP . '/vistas/pie_pagina.php');