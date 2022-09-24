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
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Nombre</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="nombre" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Sección</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="seccion" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Manzana</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="manzana" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Lote</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="lote" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-email-input" class="col-2 col-form-label">* E-Mail</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Teléfono 1</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono1" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-tel-input" class="col-2 col-form-label">Teléfono 2</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono2">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-tel-input" class="col-2 col-form-label">* Superficie</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="superficie" required>
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
							<input type="hidden" name="tipo" value="RGR">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Propietario</button>
							<a href="<?php echo STASIS; ?>/catalogos/propietariosrgr" class="btn btn-secondary">Regresar</a>
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
					<span class="card-label font-weight-bolder text-dark">Información del Propietario</span>
				</h3>
			</div>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Nombre</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="nombre" required value="<?php echo $info->nombre; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Sección</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="seccion" required value="<?php echo $info->seccion; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Manzana</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="manzana" required value="<?php echo $info->manzana; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Lote</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="lote" required value="<?php echo $info->lote; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-email-input" class="col-2 col-form-label">* E-Mail</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required value="<?php echo $info->email; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Teléfono 1</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono1" required value="<?php echo $info->telefono1; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-tel-input" class="col-2 col-form-label">Teléfono 2</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono2" value="<?php echo $info->telefono2; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-tel-input" class="col-2 col-form-label">* Superficie</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="superficie" required value="<?php echo $info->superficie; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">* Contraseña</label>
						<div class="col-5">
							<input class="form-control form-disabled" type="text" value="<?php echo $info->contrasena; ?>" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">Foto Actual</label>
						<div class="col-5">
							<img src="<?php echo $info->foto; ?>" width="300" />
						</div>
					</div>
					<div class="form-group row">
						<label for="example-password-input" class="col-2 col-form-label">Cambiar Foto</label>
						<div class="col-5">
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
							<a href="<?php echo STASIS; ?>/catalogos/propietariosrgr" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Comentario
} elseif (isset($cc)) {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<div class="card-header">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Especificar Archivo</span>
				</h3>
			</div>

            <form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Archivo</label>
						<div class="col-6">
							<input type="file" name="archivo">
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="id" value="<?php echo $id; ?>">
							<input type="hidden" name="generarCc" value="1">
							<button type="submit" class="btn btn-primary">Agregar Clave Catastral</button>
							<a href="<?php echo STASIS; ?>/catalogos/propietariosrgr" class="btn btn-secondary">Regresar</a>
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
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
				</h3>

				<div class="card-toolbar">
					<a class="btn btn-success btn-md py-2 mr-5 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/propietariosrgr/excel"><i class="fa fa-table"></i> Exportar a Excel</a>
					
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/propietariosrgr/nuevo"><i class="fa fa-plus"></i> Nuevo Propietario</a>
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
									    	<th>Sección</th>
									    	<th>Manzana</th>
									    	<th>Lote</th>
									    	<th>Email</th>
									    	<th>Teléfono 1</th>
									    	<th>Teléfono 2</th>
									    	<th>Superficie</th>
									    	<th>Clave Catastral</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['seccion']; ?></td>
											<td><?php echo $datos['manzana']; ?></td>
											<td><?php echo $datos['lote']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['telefono1']; ?></td>
											<td><?php echo $datos['telefono2']; ?></td>
											<td><?php echo $datos['superficie']; ?></td>
											<td><?php echo $datos['clave_catastral']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosrgr/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosrgr/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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
									    	<th>Sección</th>
									    	<th>Manzana</th>
									    	<th>Lote</th>
									    	<th>Email</th>
									    	<th>Teléfono 1</th>
									    	<th>Teléfono 2</th>
									    	<th>Superficie</th>
									    	<th>Clave Catastral</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['seccion']; ?></td>
											<td><?php echo $datos['manzana']; ?></td>
											<td><?php echo $datos['lote']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['telefono1']; ?></td>
											<td><?php echo $datos['telefono2']; ?></td>
											<td><?php echo $datos['superficie']; ?></td>
											<td><?php echo $datos['clave_catastral']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosrgr/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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