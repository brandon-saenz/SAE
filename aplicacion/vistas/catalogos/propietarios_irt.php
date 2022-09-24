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
						<label class="col-2 col-form-label">* Nombre</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="nombre" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Sección</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="seccion" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Manzana</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="manzana" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Lote</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="lote" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* E-Mail</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Teléfono 1</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono1" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Teléfono 2</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono2">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Superficie</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="superficie" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Foto</label>
						<div class="col-3">
							<input class="form-control" type="file" name="foto">
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="tipo" value="IRT">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Propietario</button>
							<a href="<?php echo STASIS; ?>/catalogos/propietariosirt" class="btn btn-secondary">Regresar</a>
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
						<label class="col-2 col-form-label">* Nombre</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="nombre" required value="<?php echo $info->nombre; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Sección</label>
						<div class="col-5">
							<input class="form-control mayusculas" type="text" name="seccion" required value="<?php echo $info->seccion; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Manzana</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="manzana" required value="<?php echo $info->manzana; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Lote</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="lote" required value="<?php echo $info->lote; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* E-Mail</label>
						<div class="col-5">
							<input class="form-control minusculas" type="text" name="email" required value="<?php echo $info->email; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Teléfono 1</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono1" required value="<?php echo $info->telefono1; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">Teléfono 2</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="telefono2" value="<?php echo $info->telefono2; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Superficie</label>
						<div class="col-5">
							<input class="form-control numeric" type="text" name="superficie" required value="<?php echo $info->superficie; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Contraseña</label>
						<div class="col-5">
							<input class="form-control form-disabled" type="text" value="<?php echo $info->contrasena; ?>" readonly>
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
							<a href="<?php echo STASIS; ?>/catalogos/propietariosirt" class="btn btn-secondary">Regresar</a>
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
					<span class="card-label font-weight-bolder text-dark">Información de Clave Catastral</span>
				</h3>
			</div>

            <form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card-body">
					<div class="form-group row">
						<label class="col-2 col-form-label">* ¿Ya cuenta con clave catastral?</label>
						<div class="col-6">
							<select class="form-control" id="cc-cuenta" name="clave_catastral_cuenta" required>
								<option value="">Selecciona opción...</option>
								<option value="1" <?php if ($info->clave_catastral_cuenta == 1) echo 'selected'; ?>>SI</option>
								<option value="0" <?php if ($info->clave_catastral_cuenta == 0) echo 'selected'; ?>>NO</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-2 col-form-label">* Adeudo</label>
						<div class="col-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">$</span>
								</div>
								<input class="form-control <?php if ($info->clave_catastral_cuenta == 0) echo 'form-disabled'; ?>" id="cc-adeudo" type="text" name="adeudo" value="<?php echo $info->adeudo; ?>" <?php if ($info->clave_catastral_cuenta == 0) echo 'readonly'; ?> />
							</div>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-2 col-form-label">Archivo</label>
						<div class="col-6">
							<input type="file" name="archivo" id="cc-archivo" disabled><br />
							<span class="text-muted">El archivo solo se podrá subir cuando se cuente con la clave catastral y que el adeudo esté en $0</span>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="id" value="<?php echo $id; ?>">
							<input type="hidden" name="generarCc" value="1">
							<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
							<a href="<?php echo STASIS; ?>/catalogos/propietariosirt" class="btn btn-secondary">Regresar</a>
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

<!--begin::Card-->
<div class="card card-custom gutter-b">
	<div class="card-body">
		<!--begin::Details-->
		<div class="d-flex mb-5">
			<!--begin: Pic-->
			<div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
				<div class="symbol symbol-50 symbol-lg-120">
					<img src="assets/media/users/300_4.jpg" alt="image" />
				</div>
				<div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
					<span class="font-size-h3 symbol-label font-weight-boldest">JM</span>
				</div>
			</div>
			<!--end::Pic-->
			<!--begin::Info-->
			<div class="flex-grow-1">
				<!--begin::Title-->
				<div class="d-flex justify-content-between flex-wrap mt-1">
					<div class="d-flex mr-3">
						<a href="#" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">Estrada Aviles Virginia Guadalupe</a>
						<span class="label label-warning label-inline font-weight-bold label-lg">Status de la Propiedad: Construcción en Proceso</span>
					</div>
					<div class="my-lg-0 my-3">
						<a href="#" class="btn btn-sm btn-info font-weight-bolder"><i class="fa fa-envelope"></i> Enviar</a>
						<a href="#" class="btn btn-sm btn-info font-weight-bolder"><i class="fa fa-file-pdf"></i> Exportar PDF</a>
					</div>
				</div>
				<!--end::Title-->
				<!--begin::Content-->
				<div class="d-flex flex-wrap justify-content-between mt-1">
					<div class="d-flex flex-column flex-grow-1 pr-8">
						<div class="d-flex flex-wrap mb-4">
							<a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
							<i class="flaticon2-placeholder mr-2 font-size-lg"></i>SC-01-01</a>

							<a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
							<i class="flaticon2-email mr-2 font-size-lg"></i>vestrada@ranchotecate.mx</a>
							<a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
							<i class="flaticon2-phone mr-2 font-size-lg"></i>664-4075-599</a>
							<a href="#" class="text-dark-50 text-hover-primary font-weight-bold">
							<i class="flaticon-home mr-2 font-size-lg"></i>450.00m²</a>
						</div>
						<span class="font-weight-bold text-dark-50">Propietario con antiguedad de 3 años con 6 meses.</span>
						<span class="font-weight-bold text-dark-50">Último pago realizado hace 2 meses.</span>
					</div>
					<div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
						<span class="font-weight-bold text-dark-75">Terreno Pagado</span>
						<div class="progress progress-xs mx-3 w-100">
							<div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<span class="font-weight-bolder text-dark">85%</span>
					</div>
				</div>
				<!--end::Content-->
			</div>
			<!--end::Info-->
		</div>
		<!--end::Details-->
		<div class="separator separator-solid"></div>
		<!--begin::Items-->
		<div class="d-flex align-items-center flex-wrap mt-8">
			<!--begin::Item-->
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-coins display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Valor del Terreno</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold">$</span>175,500</span>
				</div>
			</div>
			<!--end::Item-->
			<!--begin::Item-->
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-confetti display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Total Pagado</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold">$</span>125,500</span>
				</div>
			</div>
			<!--end::Item-->
			<!--begin::Item-->
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-statistics display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Total Por Pagar</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold">$</span>50,000</span>
				</div>
			</div>
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-warning-sign display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column text-dark-75">
					<span class="font-weight-bolder font-size-sm">Total Atrasado</span>
					<span class="font-weight-bolder font-size-h5">
					<span class="text-dark-50 font-weight-bold">$</span>50,000</span>
				</div>
			</div>
			<!--end::Item-->
			<!--begin::Item-->
			<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
				<span class="mr-4">
					<i class="flaticon-file-2 display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="d-flex flex-column flex-lg-fill">
					<span class="text-dark-75 font-weight-bolder font-size-sm">120 Pagos</span>
					<a href="#" class="text-primary font-weight-bolder">Ver Detalle</a>
				</div>
			</div>
			<!--end::Item-->
			<!--begin::Item-->
			<div class="d-flex align-items-center flex-lg-fill mb-2 float-left">
				<span class="mr-4">
					<i class="flaticon-network display-4 text-muted font-weight-bold"></i>
				</span>
				<div class="symbol-group symbol-hover">
					<div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="Alberto Castro">
						<img alt="Pic" src="assets/media/users/300_12.jpg" />
					</div>
					<div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="Marlon Anguiano">
						<img alt="Pic" src="assets/media/users/300_25.jpg" />
					</div>
				</div>
			</div>
			<!--end::Item-->
		</div>
		<!--begin::Items-->
	</div>
</div>
<!--end::Card-->

<!--begin::Row-->
<div class="row">
	<div class="col-lg-7">
		<!--begin::Advance Table Widget 2-->
		<div class="card card-custom card-stretch gutter-b">
			<!--begin::Header-->
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label font-weight-bolder text-dark">Detalle de Mensualidades</span>
					<span class="text-muted mt-3 font-weight-bold font-size-sm">3 de 120 pagos realizados</span>
				</h3>
				<div class="card-toolbar">
					<ul class="nav nav-pills nav-pills-sm nav-dark-75">
						<li class="nav-item">
							<a class="nav-link py-2 px-4" data-toggle="tab" href="#kt_tab_pane_11_1">Cuotas de Mantenimiento</a>
						</li>
						<li class="nav-item">
							<a class="nav-link py-2 px-4" data-toggle="tab" href="#kt_tab_pane_11_2">Enganche</a>
						</li>
						<li class="nav-item">
							<a class="nav-link py-2 px-4 active" data-toggle="tab" href="#kt_tab_pane_11_3">Terreno</a>
						</li>
					</ul>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Body-->
			<div class="card-body pt-2 pb-0 mt-n3">
				<div class="tab-content mt-5" id="myTabTables11">
					<div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
						<!--begin::Table-->
						<div class="table-responsive">
							<table class="table table-borderless table-vertical-center text-center">
								<thead>
									<tr class="bg-gray-100 text-center">
										<th># Pago</th>
										<th>Importe</th>
										<th>Método de Pago</th>
										<th>Fecha de Vencimiento</th>
										<th>Status</th>
										<th>Comprobante</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">1/120</a>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Pesos<br />Efectivo</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Enero 2022</span>
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
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">2/120</a>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">Dólares<br />Transferencia</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Febrero 2022</span>
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
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">3/120</a>
										</td>
										<td>
											<span class="text-warning font-weight-bolder d-block font-size-lg">$600</span>
										</td>

										<td>
											<span class="text-muted font-weight-500">Pesos<br />Tarjeta Crédito</span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Marzo 2022</span>
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
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">4/120</a>
										</td>
										<td>
											<span class="text-danger font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Abril 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light-danger label-inline">Atrasado</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">5/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Mayo 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">6/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Junio 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">7/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Julio 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">8/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Agosto 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">9/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Septiembre 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">10/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Octubre 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">11/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Noviembre 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">12/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Diciembre 2022</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="pl-0">
											<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">13/120</a>
										</td>
										<td>
											<span class="text-dark-25 font-weight-bolder d-block font-size-lg">$600</span>
										</td>
										<td>
											<span class="text-dark-75 font-weight-bolder d-block font-size-lg"></span>
										</td>
										<td>
											<span class="text-muted font-weight-500">10 Enero 2023</span>
										</td>
										<td>
											<span class="label label-lg label-light label-inline">Pendiente</span>
										</td>
										<td>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--end::Table-->
					</div>
				</div>
			</div>
			<!--end::Body-->
		</div>
		<!--end::Advance Table Widget 2-->
	</div>
	<div class="col-lg-5">

	<!--begin::List Widget 10-->
	<div class="card card-custom card-stretch gutter-b">
		<!--begin::Header-->
		<div class="card-header border-0">
			<h3 class="card-title font-weight-bolder text-dark">Servicios</h3>
		</div>
		<!--end::Header-->
		<!--begin::Body-->
		<div class="card-body py-0">

			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Agua</a>
						</div>
						<span class="label label-lg label-light-primary label-inline font-weight-bold py-4">En Proceso</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Instalación de Agua</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Luz</a>
						</div>
						<span class="label label-lg label-light-primary label-inline font-weight-bold py-4">En Proceso</span>
					</div>
				</div>
			</div>
		</div>

		<div class="card-header border-0">
			<h3 class="card-title font-weight-bolder text-dark">Expediente</h3>
		</div>
		<!--end::Header-->
		<!--begin::Body-->
		<div class="card-body pt-0">
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" checked value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Clave Catastral</a>
						</div>
						<span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Entregada</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Carta Finiquito</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Clave de No Adeudo</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Firma Carta Fideicomiso</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Entrega de Contrato</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Entrega Fisica</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Llaves de Acceso</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>
			<div class="mb-6">
				<div class="d-flex align-items-center flex-grow-1">
					<label class="checkbox checkbox-lg checkbox-lg flex-shrink-0 mr-4">
						<input type="checkbox" value="1" disabled />
						<span></span>
					</label>
					<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
						<div class="d-flex flex-column align-items-cente py-2 w-75">
							<a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">Terreno Escriturado</a>
						</div>
						<span class="label label-lg label-light label-inline font-weight-bold py-4">Pendiente</span>
					</div>
				</div>
			</div>

		</div>
		<!--end: Card Body-->
	</div>
	<!--end: Card-->
	<!--end: List Widget 10-->

	</div>
</div>
<!--end::Row-->
<!--begin::Row-->
<div class="row">
	<div class="col-md-12">
	<div class="card card-custom">
		<div class="card-header pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label font-weight-bolder text-dark">Seguimiento Comité de Diseño y Construcción</span>
				</h3>
			</div>
									<div class="card-body p-0">
										<!--begin::Wizard-->
										<div class="wizard wizard-1" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="false">
											<!--begin::Wizard Nav-->
											<div class="wizard-nav border-bottom">
												<div class="wizard-steps p-8 p-lg-10">
													<!--begin::Wizard Step 1 Nav-->
													<div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
														<div class="wizard-label">
															<i class="wizard-icon flaticon-list"></i>
															<h3 class="wizard-title">1. Cotización</h3>
														</div>
														<span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</div>
													<!--end::Wizard Step 1 Nav-->
													<!--begin::Wizard Step 2 Nav-->
													<div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
														<div class="wizard-label">
															<i class="wizard-icon flaticon-cogwheel"></i>
															<h3 class="wizard-title">2. Diseño</h3>
														</div>
														<span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</div>
													<!--end::Wizard Step 2 Nav-->
													<!--begin::Wizard Step 3 Nav-->
													<div class="wizard-step" data-wizard-type="step">
														<div class="wizard-label">
															<i class="wizard-icon flaticon-presentation-1"></i>
															<h3 class="wizard-title">3. Revisión de Comité</h3>
														</div>
														<span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</div>
													<!--end::Wizard Step 3 Nav-->
													<!--begin::Wizard Step 4 Nav-->
													<div class="wizard-step" data-wizard-type="step">
														<div class="wizard-label">
															<i class="wizard-icon flaticon-map-location"></i>
															<h3 class="wizard-title">4. Inicio de Obra</h3>
														</div>
														<span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</div>
													<!--end::Wizard Step 4 Nav-->
													<!--begin::Wizard Step 5 Nav-->
													<div class="wizard-step" data-wizard-type="step">
														<div class="wizard-label">
															<i class="wizard-icon flaticon-truck"></i>
															<h3 class="wizard-title">5. Constructora</h3>
														</div>
														<span class="svg-icon svg-icon-xl wizard-arrow last">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</div>
													<!--end::Wizard Step 5 Nav-->
												</div>
											</div>
											<!--end::Wizard Nav-->
											<!--begin::Wizard Body-->
											
											<!--end::Wizard Body-->
										</div>
										<!--end::Wizard-->
									</div>
									<!--end::Wizard-->
								</div>
							</div>
</div>
<!--end::Row-->















































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
					<a class="btn btn-success btn-md py-2 mr-5 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/propietariosirt/excel"><i class="fa fa-table"></i> Exportar a Excel</a>
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/propietariosirt/nuevo"><i class="fa fa-plus"></i> Nuevo Propietario</a>
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
																<a href="<?php echo STASIS; ?>/catalogos/propietariosirt/perfil/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-user"></i>
																	</span>
																	<span class="navi-text">Expediente</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosirt/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosirt/cc/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-upload"></i>
																	</span>
																	<span class="navi-text">Clave Catastral</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosirt/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/propietariosirt/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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