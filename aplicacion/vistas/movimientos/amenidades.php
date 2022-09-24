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
			// Agregar
			if (isset($agregar)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Amenidad</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Nombre de la Amenidad</label>
							<div class="col-6">
								<input class="form-control" required type="text" value="<?php echo $datos['solicitado_por']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Precio</label>
							<div class="col-6">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">$</span></div>
									<input type="text" required value="<?php echo $datos->precio; ?>" class="form-control" />
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Descripción</label>
							<div class="col-6">
								<textarea class="form-control" required rows="10"><?php echo $datos['descripcion']; ?></textarea>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-2 col-form-label">* Imagen</label>
							<div class="col-6">
								<input type="file" required name="archivo">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="agregarAmenidad" value="1">
								<button type="submit" class="btn btn-primary">Agregar Amenidad</button>
								<a href="<?php echo STASIS; ?>/" class="btn btn-secondary">Regresar</a>
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
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#pendientes">
										<span class="nav-icon">
											<i class="fa fa-clock"></i>
										</span>
										<span class="nav-text">Pendientes <span class="label label-rounded label-success" style="width: 40px;"><?php echo $listado['nPendientes']; ?></span></span>
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
									<a class="nav-link" data-toggle="tab" href="#completadas">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Completadas <span class="label label-rounded label-info" style="width: 40px;"><?php echo $listado['nCompletadas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#canceladas">
										<span class="nav-icon">
											<i class="fa fa-times"></i>
										</span>
										<span class="nav-text">Canceladas <span class="label label-rounded label-danger" style="width: 40px;"><?php echo $listado['nCanceladas']; ?></span></span>
									</a>
								</li>
							</ul>
						</div>
						
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">

						<div class="tab-content">
							<!-- Pendientes -->
							<div class="tab-pane active" id="pendientes" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Titulo</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: center;"><?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/comentario/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/cancelar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-times"></i>
																	</span>
																	<span class="navi-text">Cancelar</span>
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