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
					<span class="card-label font-weight-bolder text-dark">Información del Administrador</span>
				</h3>
			</div>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card-body">
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
						<label for="example-search-input" class="col-2 col-form-label">Departamento</label>
						<div class="col-3">
							<select class="form-control" name="id_departamento">
								<option value="">Selecciona un departamento...</option>
								<?php echo $listadoDepartamentos; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Centro de Trabajo</label>
						<div class="col-3">
							<select class="form-control" name="id_centro_trabajo">
								<option value="">Selecciona un centro de trabajo...</option>
								<?php echo $listadoCentrosTrabajo; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Puesto</label>
						<div class="col-3">
							<select class="form-control" name="id_puesto">
								<option value="">Selecciona un puesto...</option>
								<?php echo $listadoPuestos; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-email-input" class="col-2 col-form-label">E-Mail</label>
						<div class="col-3">
							<input class="form-control minusculas" type="text" name="email">
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
						<label for="example-search-input" class="col-2 col-form-label">Tipo</label>
						<div class="col-3">
							<select class="form-control" name="evaluador">
								<option value="">Selecciona tipo de administrador...</option>
								<option value="1">INTERNO</option>
								<option value="2">EXTERNO</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">¿Puede Autorizar?</label>
						<div class="col-3">
							<select class="form-control" name="autorizar">
								<option value="">Selecciona opción...</option>
								<option value="0">NO</option>
								<option value="1">SI</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">* Nombre de Usuario</label>
						<div class="col-3">
							<input class="form-control" type="text" name="nombreUsuario" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">Contraseña</label>
						<div class="col-3">
							<input class="form-control form-disabled" type="text" name="contrasena" readonly value="<?php echo rand(1000, 9999); ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">Foto</label>
						<div class="col-3">
							<input class="form-control" type="file" name="foto">
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Administrador</button>
							<a href="<?php echo STASIS; ?>/empleados/adsolicitudes" class="btn btn-secondary">Regresar</a>
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
					<span class="card-label font-weight-bolder text-dark">Información del Administrador</span>
				</h3>
			</div>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card-body">
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
						<label for="example-search-input" class="col-2 col-form-label">Departamento</label>
						<div class="col-3">
							<select class="form-control" name="id_departamento">
								<option value="">Selecciona un departamento...</option>
								<?php echo $listadoDepartamentos; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Centro de Trabajo</label>
						<div class="col-3">
							<select class="form-control" name="id_centro_trabajo">
								<option value="">Selecciona un centro de trabajo...</option>
								<?php echo $listadoCentrosTrabajo; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Puesto</label>
						<div class="col-3">
							<select class="form-control" name="id_puesto">
								<option value="">Selecciona un puesto...</option>
								<?php echo $listadoPuestos; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-email-input" class="col-2 col-form-label">E-Mail</label>
						<div class="col-3">
							<input class="form-control minusculas" type="text" name="email" value="<?php echo $info->email; ?>">
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
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Tipo</label>
						<div class="col-3">
							<select class="form-control" name="evaluador">
								<option value="">Selecciona tipo de administrador...</option>
								<option <?php if($info->evaluador == 1) echo 'selected'; ?> value="1">INTERNO</option>
								<option <?php if($info->evaluador == 2) echo 'selected'; ?> value="2">EXTERNO</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">¿Puede Autorizar?</label>
						<div class="col-3">
							<select class="form-control" name="autorizar">
								<option value="">Selecciona opción...</option>
								<option <?php if($info->autorizar == 0) echo 'selected'; ?> value="0">NO</option>
								<option <?php if($info->autorizar == 1) echo 'selected'; ?> value="1">SI</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">* Nombre de Usuario</label>
						<div class="col-3">
							<input class="form-control" type="text" name="nombreUsuario" value="<?php echo $info->nombreUsuario; ?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">Contraseña</label>
						<div class="col-3">
							<input class="form-control form-disabled" type="text" value="<?php echo $info->num; ?>" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">Foto Actual</label>
						<div class="col-3">
							<img src="<?php echo $info->foto; ?>" width="300" />
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">Cambiar Foto</label>
						<div class="col-3">
							<input class="form-control" type="file" name="foto">
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
							<a href="<?php echo STASIS; ?>/empleados/adsolicitudes" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Listado de Puestos
} else {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b card-stretch ">
			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Usuarios</span>
				</h3>

				<div class="card-toolbar">
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/empleados/adsolicitudes/nuevo"><i class="fa fa-plus"></i> Nuevo Administrador</a>
				</div>

				</div>
			<div class="card-body pt-2">
				<div class="mb-7">
					<div class="row">

						<div class="col-md-9">
							<ul class="nav nav-tabs nav-bold">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#activos">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Activos</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#inactivos">
										<span class="nav-icon">
											<i class="fa fa-times"></i>
										</span>
										<span class="nav-text">Inactivos</span>
									</a>
								</li>
							</ul>
						</div>

						<div class="col-md-3 text-right">
							<div class="input-icon">
								<input type="text" class="form-control" placeholder="Buscar..." id="kt_datatable_search">
								<span>
									<i class="las la-search text-muted"></i>
								</span>
							</div>
						</div>
						
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">

						<div class="tab-content">
							<!-- Activos -->
							<div class="tab-pane fade show active" id="activos" role="tabpanel" aria-labelledby="activos">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
									    	<th>Apellidos</th>
									    	<th>E-Mail</th>
									    	<th>Teléfono</th>
									    	<th>Tipo de Administrador</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['apellidos']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['evaluador']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/empleados/adsolicitudes/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/empleados/adsolicitudes/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-minus-circle"></i>
																	</span>
																	<span class="navi-text">Inactivar</span>
																</a>
															</li>

														</ul>
													</div>
												</div>
											</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>

							<!-- Inactivos -->
							<div class="tab-pane fade" id="inactivos" role="tabpanel" aria-labelledby="inactivos">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
									    	<th>Apellidos</th>
									    	<th>E-Mail</th>
									    	<th>Teléfono</th>
									    	<th>Tipo de Administrador</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['apellidos']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['evaluador']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/empleados/adsolicitudes/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check-circle"></i>
																	</span>
																	<span class="navi-text">Reactivar</span>
																</a>
															</li>

														</ul>
													</div>
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
	</div>
</div>

<?php
}
?>

<?php
require_once(APP . '/vistas/inc/pie_pagina.php');