<?php 
require_once(APP . '/vistas/inc/encabezado.php');

if (!empty($mensajes)) {
	foreach ($mensajes as $mensaje) {
		echo '<div id="mensajes">' . $mensaje . '</div>';
	}
}
if (!empty($status)) echo $status;
?>

<div id="log"></div>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<?php
			// Fecha de Entrega
			if (isset($fecha)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['no_solicitud']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Propietario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['propietario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Lote</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['lote']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tipo de Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['tipo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Servicio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['servicio']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Descripción Detallada</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['descripcion']; ?></textarea>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>

					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/atencion/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i> <?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Entrega</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Fecha Estimada de Entrega</label>
							<div class="col-4">
								<input class="form-control datepicker" type="text" name="fecha_entrega">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="procesar" value="1">
								<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
								<a href="<?php echo STASIS; ?>/movimientos/solicitudes/reporte" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Autorizar
			} elseif (isset($autorizar)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['no_solicitud']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Propietario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['propietario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Lote</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['lote']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tipo de Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['tipo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Servicio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['servicio']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Descripción Detallada</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['descripcion']; ?></textarea>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>

					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/atencion/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i> <?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Responsable</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* ¿Propietario se encuentra al corriente con sus pagos?</label>
							<div class="col-4">
								<select class="form-control" id="solicitud-corriente" name="corriente" required>
									<option value="">Selecciona opción...</option>
									<option value="0">NO</option>
									<option value="1">SI</option>
								</select>
							</div>
						</div>
						<div class="form-group row" style="display: none;" id="solicitud-responsable">
							<label class="col-2 col-form-label">* Usuario Responsable</label>
							<div class="col-4">
								<select class="form-control" name="id_responsable" id="solicitud-id_responsable">
									<option value="">Selecciona usuario...</option>
									<?php echo $listadoJefes; ?>
								</select>
							</div>
						</div>
						<div class="form-group row" style="display: none;" id="solicitud-cobranza-responsable">
							<label class="col-2 col-form-label">* Usuario de Cobranza Responsable</label>
							<div class="col-4">
								<select class="form-control" name="id_cobranza_responsable" id="solicitud-id_cobranza_responsable">
									<option value="">Selecciona usuario...</option>
									<?php echo $listadoCobranza; ?>
								</select>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="autorizar" value="1">
								<button type="submit" class="btn btn-primary">Autorizar Solcitud</button>
								<a href="<?php echo STASIS; ?>/movimientos/solicitudes/reporte" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Comentario
			} elseif (isset($comentario)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['no_solicitud']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Propietario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['propietario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Lote</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['lote']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tipo de Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['tipo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Servicio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['servicio']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Descripción Detallada</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['descripcion']; ?></textarea>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>

					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/atencion/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i><?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Comentario a Añadir</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Comentario</label>
							<div class="col-6">
								<textarea name="comentario" class="form-control" rows="6" required></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Archivo</label>
							<div class="col-6">
								<input type="file" name="archivo">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="generarComentario" value="1">
								<button type="submit" class="btn btn-primary">Agregar Comentario</button>
								<a href="<?php echo STASIS; ?>/movimientos/solicitudes/reporte" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Cerrar
			} elseif (isset($cerrar)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['no_solicitud']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Propietario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['propietario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Lote</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['lote']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tipo de Solicitud</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['tipo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Servicio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['servicio']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Descripción Detallada</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['descripcion']; ?></textarea>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>
 
					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/atencion/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i> <?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Conclusión Final por Parte de Administración</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Comentario</label>
							<div class="col-6">
								<textarea name="comentario" class="form-control" rows="6" required></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Archivo</label>
							<div class="col-6">
								<input type="file" name="archivo">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="cerrar" value="1">
								<button type="submit" class="btn btn-primary">Marcar solicitud como atendida</button>
								<a href="<?php echo STASIS; ?>/movimientos/solicitudes/reporte" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Listado
			} elseif (isset($listado)) {
			?>

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
				<div class="mb-7">
					<div class="row">

						<div class="col-md-12">
							<ul class="nav nav-tabs nav-bold">
								<?php
								// Responsable
								if ($_SESSION['login_tipo'] != 5) {
								?>
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#pendientes">
										<span class="nav-icon">
											<i class="fa fa-clock"></i>
										</span>
										<span class="nav-text">Pendientes <span class="label label-rounded label-success" style="width: 40px;"><?php echo $listado['nPendientes']; ?></span></span>
									</a>
								</li>
								<?php
								}
								?>

								<?php
								// En revision
								if ($_SESSION['login_id_departamento'] == 21 || $_SESSION['login_tipo'] == 4 || $_SESSION['login_id'] == 1) {
								?>

								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#revision">
										<span class="nav-icon">
											<i class="fa fa-ellipsis-h"></i>
										</span>
										<span class="nav-text">En Revisión <span class="label label-rounded label-warning" style="width: 40px;"><?php echo $listado['nRevision']; ?></span></span>
									</a>
								</li>

								<?php
								}
								?>

								<li class="nav-item">
									<?php
									if ($_SESSION['login_tipo'] == 5) {
										echo '<a class="nav-link active" data-toggle="tab" href="#autorizadas">';
									} else {
										echo '<a class="nav-link" data-toggle="tab" href="#autorizadas">';
									}
									?>
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Autorizadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nAutorizadas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#procesando">
										<span class="nav-icon">
											<i class="fa fa-cog"></i>
										</span>
										<span class="nav-text">Procesando <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nProcesando']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#finalizadas">
										<span class="nav-icon">
											<i class="fa fa-certificate"></i>
										</span>
										<span class="nav-text">Finalizadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nFinalizadas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#atendidas">
										<span class="nav-icon">
											<i class="fa fa-user-check"></i>
										</span>
										<span class="nav-text">Atendidas <span class="label label-rounded label-info" style="width: 40px;"><?php echo $listado['nAtendidas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#noatendidas">
										<span class="nav-icon">
											<i class="fa fa-exclamation-triangle"></i>
										</span>
										<span class="nav-text">No Atendidas <span class="label label-rounded label-warning" style="width: 40px;"><?php echo $listado['nNoAtendidas']; ?></span></span>
									</a>
								</li>
								<?php
								// Responsable
								if ($_SESSION['login_tipo'] != 5) {
								?>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#canceladas">
										<span class="nav-icon">
											<i class="fa fa-times"></i>
										</span>
										<span class="nav-text">Canceladas <span class="label label-rounded label-danger" style="width: 40px;"><?php echo $listado['nCanceladas']; ?></span></span>
									</a>
								</li>
								<?php
								}
								?>
							</ul>
						</div>
						
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">

						<div class="tab-content">
							<!-- Pendientes -->

							<?php
							// Responsable
							if ($_SESSION['login_tipo'] != 5) {
							?>
							<div class="tab-pane active" id="pendientes" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha</th>
											<th style="text-align: center;">Tiempo Restante</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['tiempo_restante']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<?php
															// Administrador
															if ($_SESSION['login_tipo'] == 4 && $_SESSION['login_autorizar'] == 1) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/solicitudes/autorizar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
																	</span>
																	<span class="navi-text">Autorizar</span>
																</a>
															</li>
															<?php
															}
															?>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
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
							<?php
							}
							?>

							<!-- En Revision -->
							<?php
							if ($_SESSION['login_id_departamento'] == 21 || $_SESSION['login_tipo'] == 4 || $_SESSION['login_id'] == 1) {
							?>
							<div class="tab-pane" id="revision" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha</th>
											<th style="text-align: center;">Fecha Enviada a Revisión</th>
											<th style="text-align: center;">Responsable de Cobranza</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['revision'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_revision']; ?></td>
											<td style="text-align: center;"><?php echo $dato['responsable']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<?php
															// Responsable de cobranza
															if ($_SESSION['login_tipo'] == 5 && $_SESSION['login_id_departamento'] == 21) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/solicitudes/liberar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
																	</span>
																	<span class="navi-text">Liberar</span>
																</a>
															</li>
															<?php
															}
															?>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
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
							<?php
							}
							?>

							<!-- Autorizadas -->

							<?php
							// Responsable
							if ($_SESSION['login_tipo'] == 5) {
								echo '<div class="tab-pane active" id="autorizadas" role="tabpanel">';
							} else {
								echo '<div class="tab-pane" id="autorizadas" role="tabpanel">';
							}
							?>
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha Creada</th>
											<th style="text-align: center;">Fecha Autorizada</th>
											<th style="text-align: center;">Responsable</th>
											<th style="text-align: center;">Tiempo Restante</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['autorizadas'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_autorizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['responsable']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['tiempo_restante']; ?></td>
											<td style="text-align: center; text-align: center; padding: 0;">
												<span class="navi-icon btn btn-clean btn-hover-light-primary btn-sm btn-icon">
													<i class="las la-money-check-alt" style="font-size: 25px; cursor: pointer;"></i>
												</span>
												<span class="navi-icon btn btn-clean btn-hover-light-primary btn-sm btn-icon">
													<i class="la la-group" style="font-size: 25px; cursor: pointer;"></i>
												</span>
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<?php
															// Jefe Directo
															if ($_SESSION['login_tipo'] == 5) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/solicitudes/fecha/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-calendar-check"></i>
																	</span>
																	<span class="navi-text">Especificar Fecha de Entrega</span>
																</a>
															</li>
															<?php
															}
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/solicitudes/comentario/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/cotizaciones/generar" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-money-check-alt"></i>
																	</span>
																	<span class="navi-text">Generar Cotización</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/generar" class="navi-link">
																	<span class="navi-icon">
																		<i class="la la-group"></i>
																	</span>
																	<span class="navi-text">Generar Interacción</span>
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

							<!-- Procesando -->
							<div class="tab-pane" id="procesando" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha Creada</th>
											<th style="text-align: center;">Fecha Autorizada</th>
											<th style="text-align: center;">Responsable</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['procesando'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_autorizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['responsable']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/solicitudes/comentario/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<?php
															// Jefe Directo
															if ($_SESSION['login_tipo'] == 5) {
															?>
															<li class="navi-item">
																<button class="dropdown-item btn-finalizar navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#finalizar" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-flag"></i>
																	</span>
																	<span class="navi-text">Finalizar</span>
																</button>
															</li>
															<?php
															}
															?>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/cotizaciones/generar" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-money-check-alt"></i>
																	</span>
																	<span class="navi-text">Generar Cotización</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/generar" class="navi-link">
																	<span class="navi-icon">
																		<i class="la la-group"></i>
																	</span>
																	<span class="navi-text">Generar Interacción</span>
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

							<!-- Finalizadas -->
							<div class="tab-pane" id="finalizadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha Creada</th>
											<th style="text-align: center;">Fecha Autorizada</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['finalizadas'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_autorizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<?php
															// Administrador
															if ($_SESSION['login_tipo'] == 4) {
															?>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/cerrar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check-double"></i>
																	</span>
																	<span class="navi-text">Cerrar</span>
																</a>
															</li>
															<?php
															}
															?>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
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

							<!-- Atendida -->
							<div class="tab-pane" id="atendidas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha Creada</th>
											<th style="text-align: center;">Fecha Autorizada</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Fecha de Cierre</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['atendidas'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_autorizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_atendida']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
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

							<!-- Atrasadas -->
							<div class="tab-pane" id="atrasadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									    	<th style="text-align: center;">Solicitado Por</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Producto</th>
									    	<th style="text-align: center;">Cantidad</th>
									    	<th style="text-align: center;">Orden de Compra</th>
									    	<th style="text-align: center;">Fecha de Creación</th>
									    	<th style="text-align: center;">Fecha Procesada</th>
									    	<th style="text-align: center;">Dias de Entrega</th>
									    	<th style="text-align: center;">Fecha de Vencimiento</th>
									    	<th style="text-align: center;">Dias Restantes</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['atrasadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id_requisicion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['oc']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_procesa']; ?></td>
											<td style="text-align: center;"><?php echo $dato['dias_entrega']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_vencimiento']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_vencidos']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<button class="dropdown-item btn-recibir navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#recibir" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-box"></i>
																	</span>
																	<span class="navi-text">Recibir</span>
																</button>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['id_requisicion']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
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

							<!-- Canceladas -->
							<div class="tab-pane" id="canceladas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha Creada</th>
											<th style="text-align: center;">Fecha Cancelada</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['canceladas'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_cancelada']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
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

							<!-- No Atendidas -->
							<div class="tab-pane" id="noatendidas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Solicitud</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">No. Lote</th>
											<th style="text-align: center;">Servicio</th>
											<th style="text-align: center;">Fecha</th>
											<th style="text-align: center;">Tiempo Restante</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['noAtendidas'] as $dato) {
										?>
										<tr>
											<td data-sort="<?php echo $dato['id']; ?>" style="text-align: center;"><?php echo $dato['no_solicitud']; ?></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['servicio']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['tiempo_restante']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<?php
															// Administrador
															if ($_SESSION['login_tipo'] == 4 && $_SESSION['login_autorizar'] == 1) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/solicitudes/autorizar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
																	</span>
																	<span class="navi-text">Autorizar</span>
																</a>
															</li>
															<?php
															}
															?>
															
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/solicitudes/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
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

			<!-- Modal-->
			<div class="modal fade" id="finalizar" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de marcar esta solicitud como finalizada?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <a href="#" type="button" class="btn btn-primary" id="btn-finalizar"><i class="fa fa-check"></i> Si, aceptar</a>
			            </div>
			        </div>
			    </div>
			</div>

			<?php
			}
			?>

		</div>
	</div>
</div>

<?php
require_once(APP . '/vistas/inc/pie_pagina.php');