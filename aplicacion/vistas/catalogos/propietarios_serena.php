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
						<label for="example-url-input" class="col-2 col-form-label">* Condominio</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="condominio" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Edificio</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="edificio" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-email-input" class="col-2 col-form-label">* E-Mail</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Celular</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono1" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-tel-input" class="col-2 col-form-label">Teléfono Fijo</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono2">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Nombre de Usuario</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="nombreUsuario">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Contraseña</label>
						<div class="col-5">
							<input class="form-control form-disabled" type="text" name="contrasena" value="<?php echo rand(111111, 999999); ?>" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Elegir Foto</label>
						<div class="col-5">
							<input class="form-control" type="file" name="foto">
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="tipo" value="La Serena">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Propietario</button>
							<a href="<?php echo STASIS; ?>/catalogos/propietariosserena" class="btn btn-secondary">Regresar</a>
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
						<label for="example-url-input" class="col-2 col-form-label">* Condominio</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="condominio" required value="<?php echo $info->condominio; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Edificio</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="edificio" required value="<?php echo $info->edificio; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-email-input" class="col-2 col-form-label">* E-Mail</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required value="<?php echo $info->email; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-url-input" class="col-2 col-form-label">* Celular</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono1" required value="<?php echo $info->telefono1; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-tel-input" class="col-2 col-form-label">Teléfono Fijo</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono2" value="<?php echo $info->telefono2; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Nombre de Usuario</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="nombreUsuario" value="<?php echo $info->nombreUsuario; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Contraseña</label>
						<div class="col-5">
							<input class="form-control form-disabled" type="text" name="contrasena" value="<?php echo $info->no_propietario; ?>" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Foto Actual</label>
						<div class="col-5">
							<img src="<?php echo $info->foto; ?>" width="300" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Cambiar Foto</label>
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
							<a href="<?php echo STASIS; ?>/catalogos/propietariosserena" class="btn btn-secondary">Regresar</a>
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
							<a href="<?php echo STASIS; ?>/catalogos/propietariosserena" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>

			</form>

		</div>
	</div>
</div>

<?php
// Expediente
} elseif (isset($expediente)) {
?>

<div class="card card-custom gutter-b">
	<div class="card-body">
		<div class="d-flex mb-5">
			<div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
				<div>
					<img src="<?php echo $info->foto; ?>" height="110" />
				</div>
				<div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
					<span class="font-size-h3 symbol-label font-weight-boldest">JM</span>
				</div>
			</div>
			<div class="flex-grow-1">
				<span class="text-dark-75 font-size-h5 font-weight-bold mr-3"><?php echo $info->nombre; ?></span>
				<div class="d-flex flex-wrap justify-content-between mt-1">
					<div class="d-flex pr-8">
						<div class="mb-4">
							<div class="text-dark-50 font-weight-bold"><i class="flaticon-home mr-2 font-size-lg"></i>Edificio: LA SERENA</div>
							<div class="text-dark-50 font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2"><i class="flaticon2-placeholder mr-2 font-size-lg"></i>Condominio: <?php echo $info->condominio; ?></div>
							<div class="text-dark-50 font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2"><i class="flaticon2-email mr-2 font-size-lg"></i>Correo: <?php echo $info->email; ?></div>
							<div class="text-dark-50 font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2"><i class="flaticon2-phone mr-2 font-size-lg"></i>Celular: <?php echo $info->telefono1; ?></div>
						</div>
					</div>

					<div class="float-right" id="tabla-pago" style="display: none;">
						<table class="table">
							<tbody>
								<tr>
									<td class="pl-0">
									Subtotal</td>
									<td class="text-right align-middle" id="tabla-pago-subtotal"></td>
								</tr>
								<tr class="">
									<td class="pl-0">
									Penalidad</td>
									<td class="text-right align-middle">$0.00 USD</td>
								</tr>
								<tr class="font-weight-boldest ">
									<td class="pl-0">
									Total</td>
									<td class="text-primary text-right align-middle" id="tabla-pago-total"></td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="dropdown dropdown-inline">
											<a href="#" class="btn btn-light-primary btn-sm font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style=""><i class="fa fa-check"></i> Realizar Pago</a>
											<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" style="">
												<ul class="navi navi-hover">
													<li class="navi-header pb-1">
														<span class="text-primary text-uppercase font-weight-bold font-size-sm">Selecciona método de pago:</span>
													</li>
													<li class="navi-item">
														<a href="<?php echo STASIS; ?>/e/p/f" class="navi-link">
															<span class="navi-text">Tarjeta de débito/crédito</span>
														</a>
													</li>
													<li class="navi-item">
														<a href="#" class="navi-link">
															<span class="navi-text">Referencia bancaria</span>
														</a>
													</li>
													<li class="navi-item">
														<a href="#" class="navi-link">
															<span class="navi-text">Establecimientos afiliados</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
		<div class="separator separator-solid"></div>
		<div class="d-flex align-items-center flex-wrap mt-8">
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-coins display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Valor por Cuota</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold">$</span>250.00 USD</span>
				</div>
			</div>
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-confetti display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Meses Pagados</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold"></span>0</span>
				</div>
			</div>
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-statistics display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Meses Atrasados</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold"></span>0</span>
				</div>
			</div>
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-warning-sign display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Total Atrasado</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold">$</span>0</span>
				</div>
			</div>
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-file-2 display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column flex-lg-fill">
					<a href="#" class="text-primary font-weight-bolder">Ver Detalle</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card card-custom card-stretch gutter-b">
			<div class="card-header">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Detalle de Cuotas de Mantenimiento</span>
				</h3>
				<div class="card-toolbar">
					<div class="dropdown dropdown-inline">
						<a href="#" class="btn btn-light-primary btn-sm font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">Año 2022</a>
						<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" style="">
							<ul class="navi navi-hover">
								<li class="navi-header pb-1">
									<span class="text-primary text-uppercase font-weight-bold font-size-sm">Selecciona año:</span>
								</li>
								<li class="navi-item">
									<a href="#" class="navi-link">
										<span class="navi-text">2023</span>
									</a>
								</li>
								<li class="navi-item">
									<a href="#" class="navi-link">
										<span class="navi-text">2024</span>
									</a>
								</li>
								<li class="navi-item">
									<a href="#" class="navi-link">
										<span class="navi-text">2025</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body pt-2 pb-0 mt-n3">
				<div class="tab-content mt-5" id="myTabTables11">
					<div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
						<div class="table-responsive">
							<table class="table table-hover table-borderless table-vertical-center text-center">
								<thead>
									<tr class="bg-gray-100 text-center">
										<th>Año/Mes</th>
										<th>Importe</th>
										<th>Fecha de Vencimiento</th>
										<th>Penalidad</th>
										<th>Fecha de Pago</th>
										<th>Método de Pago</th>
										<th>Status</th>
										<th>Comprobante</th>
									</tr>
								</thead>
								<tbody>
									














									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Julio 2022</span>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$250.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Julio 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
											<div class="dropdown dropdown-inline">
												<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
													<i class="ki ki-bold-more-ver"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
													<ul class="navi navi-hover">
														<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
														
														<li class="navi-item">
															<a href="<?php echo STASIS; ?>/catalogos/propietariosserena/pagare/<?php echo $datos['id']; ?>" class="navi-link">
																<span class="navi-icon">
																	<i class="las la-dollar-sign"></i>
																</span>
																<span class="navi-text">Aplicar Pago</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Agosto 2022</span>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$250.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Agosto 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Septiembre 2022</span>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$250.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Septiembre 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Octubre 2022</span>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$250.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Octubre 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Noviembre 2022</span>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$250.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Noviembre 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Diciembre 2022</span>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$250.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Diciembre 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">---</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>























									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Enero 2022</span>
										</td>
										<td>
											<span class="text-primary font-weight-bolder d-block font-size-lg">$235.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Enero 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">$ 0.00</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">1 Enero 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Transferencia</span>
										</td>
										<td>
											<span class="label label-lg label-light-primary label-inline">Pagado</span>
										</td>
										<td>
											<a href="<?php echo STASIS; ?>/propietarios/pagos/recibo/1" class="btn btn-icon btn-light btn-hover-primary btn-sm">
												<i class="fa fa-print"></i>
											</a>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Febrero 2022</span>
										</td>
										<td>
											<span class="text-primary font-weight-bolder d-block font-size-lg">$235.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Febrero 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">$ 0.00</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">1 Febrero 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Transferencia</span>
										</td>
										<td>
											<span class="label label-lg label-light-primary label-inline">Pagado</span>
										</td>
										<td>
											<a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">
												<i class="fa fa-print"></i>
											</a>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Marzo 2022</span>
										</td>
										<td>
											<span class="text-primary font-weight-bolder d-block font-size-lg">$235.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Marzo 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">$ 0.00</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">1 Marzo 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Transferencia</span>
										</td>
										<td>
											<span class="label label-lg label-light-primary label-inline">Pagado</span>
										</td>
										<td>
											<a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">
												<i class="fa fa-print"></i>
											</a>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Abril 2022</span>
										</td>
										<td>
											<span class="text-primary font-weight-bolder d-block font-size-lg">$235.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Abril 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">$ 0.00</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">1 Abril 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Transferencia</span>
										</td>
										<td>
											<span class="label label-lg label-light-primary label-inline">Pagado</span>
										</td>
										<td>
											<a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">
												<i class="fa fa-print"></i>
											</a>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Mayo 2022</span>
										</td>
										<td>
											<span class="text-primary font-weight-bolder d-block font-size-lg">$235.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Mayo 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">$ 0.00</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">1 Mayo 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Transferencia</span>
										</td>
										<td>
											<span class="label label-lg label-light-primary label-inline">Pagado</span>
										</td>
										<td>
											<a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">
												<i class="fa fa-print"></i>
											</a>
										</td>
									</tr>
									<tr>
										<td>
											<span class="text-dark-75 font-weight-bolder mb-1 font-size-lg">Junio 2022</span>
										</td>
										<td>
											<span class="text-primary font-weight-bolder d-block font-size-lg">$235.00 USD</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">11 Junio 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">$ 0.00</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">1 Junio 2022</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Transferencia</span>
										</td>
										<td>
											<span class="label label-lg label-light-primary label-inline">Pagado</span>
										</td>
										<td>
											<a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">
												<i class="fa fa-print"></i>
											</a>
										</td>
									</tr>
















								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
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
					<a class="btn btn-success btn-md py-2 mr-5 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/propietariosserena/excel"><i class="fa fa-table"></i> Exportar a Excel</a>
					
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/propietariosserena/nuevo"><i class="fa fa-plus"></i> Nuevo Propietario</a>
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
									    	<th>Condominio</th>
									    	<th>Edificio</th>
									    	<th>Email</th>
									    	<th>Celular</th>
									    	<th>Teléfono Fijo</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['condominio']; ?></td>
											<td><?php echo $datos['edificio']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['telefono1']; ?></td>
											<td><?php echo $datos['telefono2']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosserena/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosserena/perfil/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-user"></i>
																	</span>
																	<span class="navi-text">Expediente</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosserena/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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
																<a href="<?php echo STASIS; ?>/catalogos/propietariosserena/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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