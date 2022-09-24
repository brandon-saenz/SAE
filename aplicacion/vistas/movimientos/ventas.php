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
			// Nuevo / Editar
			if (isset($nuevo) || isset($editar)) {
			?>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label class="col-2 col-form-label">Folio</label>
						<div class="col-3">
							<input type="text" class="form-control mayusculas" value="<?php echo $datos['folio']; ?>" disabled />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Fecha de Creación</label>
						<div class="col-3">
							<input type="text" class="form-control mayusculas" value="<?php echo date('d/m/Y'); ?>" disabled />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Fecha de Vencimiento</label>
						<div class="col-3">
							<input type="text" class="form-control mayusculas datepicker" name="fecha_vencimiento" style="width: 100%;" required />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Destinatario</label>
						<div class="col-3">
							<select class="form-control" id="venta-destinatario" name="destinatario" required>
								<option value="">Selecciona destinatario...</option>
								<option value="1">PROSPECTO</option>
								<option value="2">PROPIETARIO</option>
							</select>
						</div>
					</div>

					<div class="form-group row" id="venta-propietario" style="display: none;">
						<label class="col-2 col-form-label">* Propietario</label>
						<div class="col-3">
							<select class="form-control" id="propietario" name="propietario" required>
								<option value="">Selecciona propietario...</option>
								<?php echo $listadoPropietarios; ?>
							</select>
						</div>
					</div>

					<div class="form-group row venta-prospecto" style="display: none;">
						<label class="col-2 col-form-label">* Nombre de Prospecto</label>
						<div class="col-3">
							<input type="text" class="form-control mayusculas" id="nombre_prospecto" name="nombre_prospecto" />
						</div>
					</div>
					<div class="form-group row venta-prospecto" style="display: none;">
						<label class="col-2 col-form-label">* Teléfono de Prospecto</label>
						<div class="col-3">
							<input type="text" class="form-control mayusculas" id="tel_prospecto" name="tel_prospecto" />
						</div>
					</div>
					<div class="form-group row venta-prospecto" style="display: none;">
						<label class="col-2 col-form-label">* Correo de Prospecto</label>
						<div class="col-3">
							<input type="text" class="form-control mayusculas" id="correo_prospecto" name="correo_prospecto" />
						</div>
					</div>

					<div class="form-group row">
						<label class="col-2 col-form-label">* Beneficio</label>
						<div class="col-3">
							<select class="form-control" name="promesa" required>
								<option value="">Selecciona beneficio...</option>
								<option value="1">2 NOCHES DE HOTEL</option>
								<option value="2">CENA FAMILIAR</option>
								<option value="3">KIT DE VINOS</option>
								<option value="4">LIMPIEZA DE TERRENO</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Motivo</label>
						<div class="col-3">
							<textarea name="motivo" class="form-control" rows="6" required></textarea>
						</div>
					</div>
				</div>

				<?php
				// Agregar
                if (isset($nuevo)) {
                ?>

                <div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<a href="<?php echo STASIS; ?>/principal/" class="btn btn-secondary">Regresar</a>
							<button type="submit" name="generar" class="btn btn-primary"><i class="fa fa-check"></i> Generar Beneficio de Compra</button>
						</div>
					</div>
				</div>

                <?php
                // Editar
                } elseif (isset($editar)) {
                ?>

                <div class="card-footer">
					<input type="hidden" name="promesaModificada" value="<?php echo $datos['id']; ?>" />
					<button type="submit" name="generar" class="btn btn-primary mr-2"><i class="fa fa-check"></i> Aplicar Cambios</button>
					<a href="<?php echo STASIS; ?>/movimientos/ventas/historial" class="btn btn-secondary">Regresar</a>
				</div>

                <?php
                }
                ?>
			</form>

			<?php
			// Historial
			} elseif (isset($historial)) {
			?>

			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
				</h3>

				<!-- <div class="card-toolbar">
					<a class="btn btn-success btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/movimientos/ventas/excel"><i class="fa fa-table"></i> Exportar a Excel</a>
				</div> -->

			</div>

			<div class="card-body pt-2">
				<div class="mb-7">
					<div class="row">

						<div class="col-md-9">
							<ul class="nav nav-tabs nav-bold">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#generadas">
										<span class="nav-icon">
											<i class="fa fa-clock"></i>
										</span>
										<span class="nav-text">Generadas <span class="label label-rounded label-success" style="width: 40px;"><?php echo $listado['nGeneradas']; ?></span></span>
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
							<!-- Generadas -->
							<div class="tab-pane active" id="generadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									    	<th style="text-align: center;">Vendedor</th>
									    	<th style="text-align: center;">Promesa</th>
									    	<th style="text-align: center;">Fecha de Creación</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['generadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['vendedor']; ?></td>
											<td style="text-align: center;"><?php echo $dato['promesa']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/ventas/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
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

			<?php
			}
			?>

		</div>
	</div>
</div>


<?php
require_once(APP . '/vistas/inc/pie_pagina.php');