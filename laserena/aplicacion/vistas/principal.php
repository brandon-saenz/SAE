<?php
require_once(APP . '/vistas/inc/encabezado.php');
?>

<div class="card card-custom gutter-b">
	<div class="card-body">
		<div class="d-flex mb-5">
			<div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
				<div>
					<img src="<?php echo $_SESSION['login_foto']; ?>" height="110" />
				</div>
				<div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
					<span class="font-size-h3 symbol-label font-weight-boldest">JM</span>
				</div>
			</div>
			<div class="flex-grow-1">
				<span class="text-dark-75 font-size-h5 font-weight-bold mr-3"><?php echo $_SESSION['login_nombre']; ?></span>
				<div class="d-flex flex-wrap justify-content-between mt-1">
					<div class="d-flex pr-8">
						<div class="mb-4">
							<div class="text-dark-50 font-weight-bold"><i class="flaticon-home mr-2 font-size-lg"></i>Edificio: LA SERENA</div>
							<div class="text-dark-50 font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2"><i class="flaticon2-placeholder mr-2 font-size-lg"></i>Condominio: <?php echo $_SESSION['login_condominio']; ?></div>
							<div class="text-dark-50 font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2"><i class="flaticon2-email mr-2 font-size-lg"></i>Correo: <?php echo $_SESSION['login_email']; ?></div>
							<div class="text-dark-50 font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2"><i class="flaticon2-phone mr-2 font-size-lg"></i>Celular: <?php echo $_SESSION['login_telefono1']; ?></div>
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
							<table class="table table-borderless table-vertical-center text-center">
								<thead>
									<tr class="bg-gray-100 text-center">
										<th>Pagar</th>
										<th>Año/Mes</th>
										<th>Importe</th>
										<th>Fecha de Vencimiento</th>
										<th>Método de Pago</th>
										<th>Status</th>
										<th>Comprobante</th>
									</tr>
								</thead>
								<tbody>
									














									<tr>
										<td class="text-center">
											<label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg" style="display: inline-block;">
											<input type="checkbox" class="verificar-pago" name="pagar[]" data-id="0">
											<span></span></label>
										</td>
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
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="text-center">
											<label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg" style="display: inline-block;">
											<input type="checkbox" class="verificar-pago" name="pagar[]" data-id="1">
											<span></span></label>
										</td>
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
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="text-center">
											<label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg" style="display: inline-block;">
											<input type="checkbox" class="verificar-pago" name="pagar[]" data-id="2">
											<span></span></label>
										</td>
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
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="text-center">
											<label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg" style="display: inline-block;">
											<input type="checkbox" class="verificar-pago" name="pagar[]" data-id="3">
											<span></span></label>
										</td>
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
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="text-center">
											<label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg" style="display: inline-block;">
											<input type="checkbox" class="verificar-pago" name="pagar[]" data-id="4">
											<span></span></label>
										</td>
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
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>
									<tr>
										<td class="text-center">
											<label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg" style="display: inline-block;">
											<input type="checkbox" class="verificar-pago" name="pagar[]" data-id="5">
											<span></span></label>
										</td>
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
											<span class="label label-lg label-light label-inline">Próximo a Pagar</span>
										</td>
										<td>
										</td>
									</tr>























									<tr>
										<td class="text-center">
											<label class="checkbox checkbox-lg" style="display: inline-block;">
											<input type="checkbox" checked disabled>
											<span></span></label>
										</td>
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
										<td class="text-center">
											<label class="checkbox checkbox-lg" style="display: inline-block;">
											<input type="checkbox" checked disabled>
											<span></span></label>
										</td>
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
										<td class="text-center">
											<label class="checkbox checkbox-lg" style="display: inline-block;">
											<input type="checkbox" checked disabled>
											<span></span></label>
										</td>
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
										<td class="text-center">
											<label class="checkbox checkbox-lg" style="display: inline-block;">
											<input type="checkbox" checked disabled>
											<span></span></label>
										</td>
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
										<td class="text-center">
											<label class="checkbox checkbox-lg" style="display: inline-block;">
											<input type="checkbox" checked disabled>
											<span></span></label>
										</td>
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
										<td class="text-center">
											<label class="checkbox checkbox-lg" style="display: inline-block;">
											<input type="checkbox" checked disabled>
											<span></span></label>
										</td>
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
require_once(APP . '/vistas/inc/pie_pagina.php');
?>