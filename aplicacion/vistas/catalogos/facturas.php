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
			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Facturas</span>
				</h3>

				<div class="card-toolbar">
					<div class="text-right">
						<div class="input-icon">
							<input type="text" class="form-control" placeholder="Buscar..." id="kt_datatable_search">
							<span>
								<i class="las la-search text-muted"></i>
							</span>
						</div>
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
										<span class="nav-text">Pendientes de Pago <span class="label label-rounded label-success" style="width: 40px;"><?php echo $listado['nPendientes']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#autorizadas">
										<span class="nav-icon">
											<i class="fa fa-dollar-sign"></i>
										</span>
										<span class="nav-text">Pagadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nPagadas']; ?></span></span>
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
									      	<th style="text-align: center;">Folio Requisición</th>
									      	<th style="text-align: center;">Orden de Compra</th>
									    	<th style="text-align: center;">Unidad de Negocio</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Archivo PDF</th>
									    	<th style="text-align: center;">Monto</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id_requisicion']; ?>" class="navi-link"><?php echo $dato['id_requisicion']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['oc']; ?></td>
											<td style="text-align: center;"><?php echo $dato['centro_costo']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/proveedores/data/privada/facturas/<?php echo $dato['archivo_pdf']; ?>"><?php echo $dato['archivo_pdf']; ?></a></td>
											<td style="text-align: center;"></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<button class="dropdown-item btn-autorizar-requisicion navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#autorizar-requisicion" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
																	</span>
																	<span class="navi-text">Pagar</span>
																</button>
															</li>
															<li class="navi-item">
																<button class="dropdown-item btn-autorizar-requisicion navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#autorizar-requisicion" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-sync"></i>
																	</span>
																	<span class="navi-text">Solicitar Refacturación</span>
																</button>
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

							<!-- Autorizadas -->
							<div class="tab-pane" id="autorizadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									    	<th style="text-align: center;">Solicitado Por</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Producto</th>
									    	<th style="text-align: center;">Cantidad</th>
									    	<th style="text-align: center;">UM</th>
									    	<th style="text-align: center;">Fecha de Creación</th>
									    	<th style="text-align: center;">Autorizado Por</th>
									    	<th style="text-align: center;">Fecha de Autorización</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['autorizadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id_requisicion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['um']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td style="text-align: center;"><?php echo $dato['autoriza']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_autorizacion']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<?php
															// Compras
															if ($_SESSION['login_puesto'] == 'GERENTE DE COMPRAS' || $_SESSION['login_puesto'] == 'AUXILIAR DE COMPRAS' || $_SESSION['login_tipo'] == 3) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/compras/procesar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-cog"></i>
																	</span>
																	<span class="navi-text">Procesar</span>
																</a>
															</li>
															<li class="navi-item">
																<button class="dropdown-item btn-cancelar-parte navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#cancelar-parte" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-times"></i>
																	</span>
																	<span class="navi-text">Cancelar</span>
																</button>
															</li>
															<?php
															}
															?>
															
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id_requisicion']; ?>" class="navi-link">
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

		</div>
	</div>
</div>


<?php
require_once(APP . '/vistas/inc/pie_pagina.php');