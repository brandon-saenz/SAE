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
// Tabla de Amortizacion
} elseif (isset($amortizacion)) {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b card-stretch ">
			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
				</h3>

				<div class="card-toolbar">
				</div>
			</div>
			<div class="card-body pt-2">
				<div class="mb-7">
					<div class="row">

						<div class="col-md-9">
							<ul class="nav nav-tabs nav-bold">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#mensualidades">
										<span class="nav-icon">
											<i class="fa fa-calendar"></i>
										</span>
										<span class="nav-text">Mensualidades</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#enganches">
										<span class="nav-icon">
											<i class="fa fa-certificate"></i>
										</span>
										<span class="nav-text">Enganches</span>
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
							<!-- Mensualidades -->
							<div class="tab-pane fade show active" id="mensualidades" role="tabpanel" aria-labelledby="mensualidades">
								<table class="table table-bordered table-striped kt_datatable-1">
									<thead>
										<tr>
											<th>Lote</th>
											<th>Concepto</th>
											<th>Importe</th>
											<th>Penalidad</th>
											<th>Morosidad</th>
											<th>Total a Pagar</th>
											<th>Fecha Vencimiento</th>
											<th>Fecha Pago</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['mensualidades'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['lote']; ?></td>
											<td data-sort="<?php echo $datos['periodo']; ?>"><?php echo $datos['concepto']; ?></td>
											<td><?php echo $datos['importe']; ?></td>
											<td><?php echo $datos['penalidad']; ?></td>
											<td><?php echo $datos['morosidad']; ?></td>
											<td><?php echo $datos['total']; ?></td>
											<td><?php echo $datos['fecha_vencimiento']; ?></td>
											<td><?php echo $datos['fecha_pagado']; ?></td>
											<td><?php echo $datos['status']; ?></td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>

							<!-- Enganches -->
							<div class="tab-pane fade" id="enganches" role="tabpanel" aria-labelledby="enganches">
								<table class="table table-bordered table-striped kt_datatable-1">
									<thead>
										<tr>
											<th>Lote</th>
											<th>Concepto</th>
											<th>Importe</th>
											<th>Penalidad</th>
											<th>Morosidad</th>
											<th>Total a Pagar</th>
											<th>Fecha Vencimiento</th>
											<th>Fecha Pago</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['enganches'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['lote']; ?></td>
											<td data-sort="<?php echo $datos['periodo']; ?>"><?php echo $datos['concepto']; ?></td>
											<td><?php echo $datos['importe']; ?></td>
											<td><?php echo $datos['penalidad']; ?></td>
											<td><?php echo $datos['morosidad']; ?></td>
											<td><?php echo $datos['total']; ?></td>
											<td><?php echo $datos['fecha_vencimiento']; ?></td>
											<td><?php echo $datos['fecha_pagado']; ?></td>
											<td><?php echo $datos['status']; ?></td>
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
// Cuotas de Mantenimiento
} elseif (isset($cuotas)) {
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

						<table class="table table-bordered table-striped kt_datatable-todos">
							<thead>
								<tr>
									<th>Periodo</th>
									<th>Lote</th>
									<th>Concepto</th>
									<th>Importe</th>
									<th>Penalidad</th>
									<th>Total a Pagar</th>
									<th>Fecha Vencimiento</th>
									<th>Fecha Pago</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($listado['activos'] as $datos) {
								?>
								<tr>
									<td><?php echo $datos['periodo']; ?></td>
									<td><?php echo $datos['lote']; ?></td>
									<td><?php echo $datos['concepto']; ?></td>
									<td><?php echo $datos['importe']; ?></td>
									<td><?php echo $datos['penalidad']; ?></td>
									<td><?php echo $datos['total']; ?></td>
									<td><?php echo $datos['fecha_vencimiento']; ?></td>
									<td><?php echo $datos['fecha_pagado']; ?></td>
									<td><?php echo $datos['status']; ?></td>
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
// Listado de Arrendatarios
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
					<!-- <a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/campanas/nuevo"><i class="fa fa-plus"></i> Nueva Campaña</a> -->
				</div>
			</div>
			<div class="card-body pt-2">
				<div class="mb-7">
					<div class="row">

						<!-- <div class="col-md-9"> -->
							<!-- <ul class="nav nav-tabs nav-bold">
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
							</ul> -->
						<!-- </div> -->

						<!-- <div class="col-md-3 text-right">
							<div class="input-icon">
								<input type="text" class="form-control" placeholder="Buscar..." id="kt_datatable_search">
								<span>
									<i class="las la-search text-muted"></i>
								</span>
							</div>
						</div> -->
						
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">

						<!-- <div class="tab-content"> -->
							<!-- Activos -->
							<!-- <div class="tab-pane fade show active" id="activos" role="tabpanel" aria-labelledby="activos"> -->
								<table class="table table-bordered table-striped kt_datatable-0">
									<thead>
										<tr>
											<th>Folio</th>
											<th>Contrato</th>
											<th>Nombre</th>
											<th>RFC</th>
											<th>Fecha Alta</th>
											<th>Tipo</th>
											<th>Lote</th>
											<th>Asignador</th>
											<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['id']; ?></td>
											<td><?php echo $datos['id_arrendatario']; ?></td>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['rfc']; ?></td>
											<td style="white-space: nowrap;" data-sort="<?php echo $datos['fechaTimeStamp']; ?>"><?php echo $datos['fecha_alta']; ?></td>
											<td><?php echo $datos['tipo']; ?></td>
											<td><?php echo $datos['lote']; ?></td>
											<td><?php echo $datos['asignador']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/arrendatarios/amortizacion/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-table"></i>
																	</span>
																	<span class="navi-text">Tabla de Amortización</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/arrendatarios/cuotas/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-hammer"></i>
																	</span>
																	<span class="navi-text">Cuotas de Mantenimiento</span>
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

							<!-- <div class="tab-pane fade" id="inactivos" role="tabpanel" aria-labelledby="inactivos">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/campanas/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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
							</div> -->
						<!-- </div> -->
						
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