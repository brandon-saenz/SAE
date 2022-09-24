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

<div id="log"></div>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<div class="card-header">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Información del Presupuesto</span>
				</h3>
			</div>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Folio de Contrato</label>
						<div class="col-5">
							<select class="form-control" id="cotizacion-propietario" name="id_contratante" required>
								<option value="">Selecciona folio de contrato...</option>
								<?php echo $listadoContratos; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Fecha de Inicio</label>
						<div class="col-5">
							<input type="text" class="form-control mayusculas" id="fecha_inicio" disabled />
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Fecha de Término</label>
						<div class="col-5">
							<input type="text" class="form-control mayusculas" id="fecha_termino" disabled />
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Nombre de la Obra</label>
						<div class="col-5">
							<input type="text" class="form-control mayusculas" id="descripcion" disabled />
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Responsable</label>
						<div class="col-5">
							<input type="text" class="form-control mayusculas" id="responsable" disabled />
						</div>
					</div>

					<h4 class="card-title mt-15">
						<span class="card-label font-weight-bolder text-dark">Partidas de la Obra</span>
					</h4>

					<div class="accordion accordion-toggle-arrow" id="accordionExample4">
						<?php
						$arregloPartidas = array(
							'Preliminares',
							'Cimentacion',
							'Firmes',
								'Banquetas y Guarniciones',
								'Muros',
								'Estructura',
								'Instalacion Electrica',
								'Instalacion Hidrosanitaria',
								'Instalaciones Especiales',
								'Puertas y Ventanas',
								'Acabados',
								'Herreria',
								'Mobiliario',
								'Obra Exterior',
								'Limpieza',
						);

						$x = 0;
						foreach ($arregloPartidas as $partida) {
							?>

							<div class="card">
								<div class="card-header">
									<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne<?php echo $x; ?>" aria-expanded="false">
									<i class="flaticon2-layers-1"></i><?php echo $partida; ?></div>
								</div>
								<div id="collapseOne<?php echo $x; ?>" class="collapse" data-parent="#accordionExample4">
									<div class="card-body">
										<table class="table table-bordered">
											<tbody>
												<?php
												for($x=1; $x<=6; $x++) {
													if ($x >= 2) {
														$clase = 'hidden';
													} else {
														$clase = '';
													}
												?>
												<tr class="filahead-<?php echo $x; ?> <?php echo $clase; ?>">
													<th style="background-color: #2F75B5; color: #FFF; white-space: nowrap; text-align: center;">Número de Partida</th>
													<th style="background-color: #2F75B5; color: #FFF; white-space: nowrap; text-align: center;">Descripción de Actividades</th>
													<th style="background-color: #2F75B5; color: #FFF; white-space: nowrap; text-align: center;">Unidad</th>
													<th style="background-color: #2F75B5; color: #FFF; white-space: nowrap; text-align: center;">Cantidad</th>
													<th style="background-color: #2F75B5; color: #FFF; white-space: nowrap; text-align: center;">P/U</th>
													<th style="background-color: #0F5089; color: #FFF; white-space: nowrap; text-align: center;">Subtotal</th>
													<th style="background-color: #0F5089; color: #FFF; white-space: nowrap; text-align: center;">Total</th>
												</tr>
												<tr class="fila2-<?php echo $x; ?> <?php echo $clase; ?>">
													<!-- No. Partida -->
													<td class="td-partida-<?php echo $x; ?>">
														<input class="form-control text-center form-disabled input-sm input-25 mayusculas" id="noPartida1<?php echo $x; ?>" name="noPartida1<?php echo $x; ?>" value="1" type="text" disabled />
														<input class="form-control text-center form-disabled input-sm input-25 mayusculas" id="noPartida2<?php echo $x; ?>" name="noPartida2<?php echo $x; ?>" value="2" type="text" disabled />
														<input class="form-control text-center form-disabled input-sm input-25 mayusculas" id="noPartida3<?php echo $x; ?>" name="noPartida3<?php echo $x; ?>" value="3" type="text" disabled />
														<input class="form-control text-center form-disabled input-sm input-25 mayusculas" style="display: none;" id="noPartida4<?php echo $x; ?>" name="noPartida4<?php echo $x; ?>" value="4" type="text" disabled />
														<input class="form-control text-center form-disabled input-sm input-25 mayusculas" style="display: none;" id="noPartida5<?php echo $x; ?>" name="noPartida5<?php echo $x; ?>" value="5" type="text" disabled />
														<input class="form-control text-center form-disabled input-sm input-25 mayusculas" style="display: none;" id="noPartida6<?php echo $x; ?>" name="noPartida6<?php echo $x; ?>" value="6" type="text" disabled />
														<input class="form-control text-center form-disabled input-sm input-25 mayusculas" style="display: none;" id="noPartida7<?php echo $x; ?>" name="noPartida7<?php echo $x; ?>" value="7" type="text" disabled />
													</td>

													<!-- Descripcion -->
													<td class="td-descripcion-<?php echo $x; ?>">
														<input class="form-control input-sm input-4 mayusculas" id="descripcion1<?php echo $x; ?>" name="descripcion1<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-4 mayusculas" id="descripcion2<?php echo $x; ?>" name="descripcion2<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-4 mayusculas" id="descripcion3<?php echo $x; ?>" name="descripcion3<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-4 mayusculas" style="display: none;" id="descripcion4<?php echo $x; ?>" name="descripcion4<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-4 mayusculas" style="display: none;" id="descripcion5<?php echo $x; ?>" name="descripcion5<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-4 mayusculas" style="display: none;" id="descripcion6<?php echo $x; ?>" name="descripcion6<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-4 mayusculas" style="display: none;" id="descripcion7<?php echo $x; ?>" name="descripcion7<?php echo $x; ?>" type="text" />
													</td>

													<!-- Unidad -->
													<td class="td-unidad-<?php echo $x; ?>">
														<input class="form-control input-sm mayusculas input-2" id="unidad1<?php echo $x; ?>" name="unidad1<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm mayusculas input-2" id="unidad2<?php echo $x; ?>" name="unidad2<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm mayusculas input-2" id="unidad3<?php echo $x; ?>" name="unidad3<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm mayusculas input-2" style="display: none;" id="unidad4<?php echo $x; ?>" name="unidad4<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm mayusculas input-2" style="display: none;" id="unidad5<?php echo $x; ?>" name="unidad5<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm mayusculas input-2" style="display: none;" id="unidad6<?php echo $x; ?>" name="unidad6<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm mayusculas input-2" style="display: none;" id="unidad7<?php echo $x; ?>" name="unidad7<?php echo $x; ?>" type="text" />
													</td>

													<!-- Cantidad -->
													<td class="td-cantidad-<?php echo $x; ?>">
														<input class="form-control input-sm input-2 cotizacion-formulas" id="cantidad1<?php echo $x; ?>" name="cantidad1<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" id="cantidad2<?php echo $x; ?>" name="cantidad2<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" id="cantidad3<?php echo $x; ?>" name="cantidad3<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="cantidad4<?php echo $x; ?>" name="cantidad4<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="cantidad5<?php echo $x; ?>" name="cantidad5<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="cantidad6<?php echo $x; ?>" name="cantidad6<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="cantidad7<?php echo $x; ?>" name="cantidad7<?php echo $x; ?>" type="text" />
													</td>

													<!-- Precio Unitario -->
													<td class="td-pu-<?php echo $x; ?>">
														<input class="form-control input-sm input-2 cotizacion-formulas" id="pu1<?php echo $x; ?>" name="pu1<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" id="pu2<?php echo $x; ?>" name="pu2<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" id="pu3<?php echo $x; ?>" name="pu3<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="pu4<?php echo $x; ?>" name="pu4<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="pu5<?php echo $x; ?>" name="pu5<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="pu6<?php echo $x; ?>" name="pu6<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 cotizacion-formulas" style="display: none;" id="pu7<?php echo $x; ?>" name="pu7<?php echo $x; ?>" type="text" />
													</td>

													<!-- Subtotal -->
													<td class="td-subtotal-<?php echo $x; ?>">
														<input class="form-control input-sm input-2 form-disabled" readonly id="subtotal1<?php echo $x; ?>" name="subtotal1<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 form-disabled" readonly id="subtotal2<?php echo $x; ?>" name="subtotal2<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 form-disabled" readonly id="subtotal3<?php echo $x; ?>" name="subtotal3<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 form-disabled" readonly style="display: none;" id="subtotal4<?php echo $x; ?>" name="subtotal4<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 form-disabled" readonly style="display: none;" id="subtotal5<?php echo $x; ?>" name="subtotal5<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 form-disabled" readonly style="display: none;" id="subtotal6<?php echo $x; ?>" name="subtotal6<?php echo $x; ?>" type="text" />
														<input class="form-control input-sm input-2 form-disabled" readonly style="display: none;" id="subtotal7<?php echo $x; ?>" name="subtotal7<?php echo $x; ?>" type="text" />
													</td>
													<td class="td-total-<?php echo $x; ?>" style="vertical-align: middle;">
														<h4 class="m-0 p-0" id="total-<?php echo $x; ?>">$0.00</h4>
													</td>
												</tr>
												<tr class="fila4-<?php echo $x; ?> <?php echo $clase; ?>">
													<td>
														<input type="hidden" id="filas-juego-<?php echo $x; ?>" value="3" />
														<button type="button" id="fila-btn-<?php echo $x; ?>" class="form-control btn btn-success agregar-fila-cotizacion"><i class="fa fa-plus-circle"></i> Agregar</button>
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

							<?php
							$x++;
						}
						?>
					</div>


				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Presupuesto</button>
							<a href="<?php echo STASIS; ?>/constructora/presupuestos" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Listado de Departamentos
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
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/constructora/presupuestos/nuevo"><i class="fa fa-plus"></i> Nuevo Presupuesto</a>
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
											<th>Lote</th>
											<th>Teléfono</th>
											<th>E-Mail</th>
											<th>Superficie M2</th>
											<th>$ Costo Total</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['lote']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['m2']; ?></td>
											<td><?php echo $datos['costo']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/constructora/presupuestos/visualizar/<?php echo $datos['id']; ?>" class="navi-link">
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

							<!-- Inactivos -->
							<div class="tab-pane fade" id="inactivos" role="tabpanel" aria-labelledby="inactivos">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
											<th>Area</th>
											<th>Responsable</th>
											<th>Clasificación</th>
											<th>Empresa</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['area']; ?></td>
											<td><?php echo $datos['responsable']; ?></td>
											<td><?php echo $datos['clasificacion']; ?></td>
											<td><?php echo $datos['empresa']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/constructora/presupuestos/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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