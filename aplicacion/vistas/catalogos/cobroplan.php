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
					<span class="card-label font-weight-bolder text-dark">Información del Propietario</span>
				</h3>
			</div>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card-body">

					<h3 class="font-size-lg text-dark font-weight-bold mb-6">1. Personal</h3>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Nombre</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="name" required value="<?php echo $info->name; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Email</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required value="<?php echo $info->email; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Saari ID</label>
						<div class="col-5">
							<input class="form-control" type="text" name="saari_id" required value="<?php echo $info->saari_id; ?>">
						</div>
					</div>

					<h3 class="font-size-lg text-dark font-weight-bold mb-6 mt-15">2. Seguridad</h3>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">Contraseña:</label>
						<div class="col-lg-5">
							<input type="password" class="form-control" id="contrasena1" name="contrasena1" maxlength="50" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">Repetir Contraseña:</label>
						<div class="col-lg-5">
							<input type="password" class="form-control" id="contrasena2" name="contrasena2" maxlength="50" required>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Usuario</button>
							<a href="<?php echo STASIS; ?>/catalogos/cobroplan" class="btn btn-secondary">Regresar</a>
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
					<span class="card-label font-weight-bolder text-dark">Información del Usuario</span>
				</h3>
			</div>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card-body">

					<h3 class="font-size-lg text-dark font-weight-bold mb-6">1. Personal</h3>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Nombre</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="name" required value="<?php echo $info->name; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Email</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required value="<?php echo $info->email; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Saari ID</label>
						<div class="col-5">
							<input class="form-control" type="text" name="saari_id" required value="<?php echo $info->saari_id; ?>">
						</div>
					</div>

					<h3 class="font-size-lg text-dark font-weight-bold mb-6 mt-15">2. Seguridad</h3>
					<p>Si deseas mantener la contraseña actual del usuario, deja los dos campos vacíos.</p>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">Contraseña:</label>
						<div class="col-lg-5">
							<input type="password" class="form-control" id="contrasena1" name="contrasena1" maxlength="50">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">Repetir Contraseña:</label>
						<div class="col-lg-5">
							<input type="password" class="form-control" id="contrasena2" name="contrasena2" maxlength="50">
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
							<a href="<?php echo STASIS; ?>/catalogos/cobroplan" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Modificar
} elseif (isset($reporte)) {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Rango de Fechas</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Fecha Inicio</label>
							<div class="col-3">
								<input class="form-control datepicker" type="text" name="fecha_inicio" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Fecha Fin</label>
							<div class="col-3">
								<input class="form-control datepicker" type="text" name="fecha_fin" required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="generarReporte" value="1">
								<button type="submit" class="btn btn-primary">Generar Reporte</button>
								<a href="<?php echo STASIS; ?>/" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Listado
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
					<!-- <a class="btn btn-success btn-md py-2 mr-5 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/cobroplan/excel"><i class="fa fa-table"></i> Exportar a Excel</a> -->
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/cobroplan/nuevo"><i class="fa fa-plus"></i> Nuevo Usuario</a>
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
								<table class="table table-bordered table-striped kt_datatable-0">
									<thead>
										<tr>
											<th>Folio</th>
									    	<th>Nombre</th>
									    	<th>Correo</th>
									    	<th>ID Saari</th>
									    	<th>Tipo de Usuario</th>
									    	<th>Fecha de Creación</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['id']; ?></td>
											<td><?php echo $datos['name']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['saari_id']; ?></td>
											<td><?php echo $datos['user_type']; ?></td>
											<td><?php echo $datos['created_at']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/cobroplan/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>

															<!-- <li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/cobroplan/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-minus-circle"></i>
																	</span>
																	<span class="navi-text">Inactivar</span>
																</a>
															</li> -->

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
											<th>Folio</th>
									    	<th>Nombre</th>
									    	<th>Correo</th>
									    	<th>ID Saari</th>
									    	<th>Tipo de Usuario</th>
									    	<th>Fecha de Creación</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['id']; ?></td>
											<td><?php echo $datos['name']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['saari_id']; ?></td>
											<td><?php echo $datos['user_type']; ?></td>
											<td><?php echo $datos['created_at']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/cobroplan/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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