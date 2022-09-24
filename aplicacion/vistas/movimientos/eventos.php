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
							<span class="card-label font-weight-bolder text-dark">Información de Evento</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Nombre</label>
							<div class="col-4">
								<input class="form-control" required type="text" name="nombre" value="<?php echo $datos['nombre']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Fecha</label>
							<div class="col-4">
								<input class="form-control datepicker" required type="text" name="fecha" value="<?php echo $datos['fecha']; ?>" style="width: 100%;">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Hora de Inicio</label>
							<div class="col-4">
								<input class="form-control" required type="text" name="hora" value="<?php echo $datos['hora']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Limite de Asistencia</label>
							<div class="col-4">
								<div class="input-group">
									<input class="form-control" type="text" name="limite" value="<?php echo $datos['limite']; ?>">
									<div class="input-group-append"><span class="input-group-text">personas</span></div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Imagen para Web</label>
							<div class="col-4">
								<input type="file" required name="imagen_web">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Imagen para Móviles</label>
							<div class="col-4">
								<input type="file" required name="imagen_movil">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Descripción</label>
							<div class="col-10">
								<textarea id="kt-tinymce-2" name="descripcion">
						            <h1>Nombre del Evento</h1>
						            <p>Texto breve descriptivo del evento.</p>
						            <h3>Encabezado</h3>
						            <p>Información detallada del evento.</p>
						        </textarea>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table">
								<thead class="thead-dark text-center">
									<tr>
										<th style="width: 10%;">Fase</th>
										<th style="width: 30%;">Fecha Cierre de Fase</th>
										<th style="width: 35%;">$ Entrada General</th>
										<th style="width: 35%;">$ Propietarios</th>
									</tr>
								</thead>
								<tbody id="cotizacion-columnas">
									<?php
									for($x=1; $x<=3; $x++) {
									?>
									<tr id="fila<?php echo $x; ?>">
										<td>
											<input class="form-control" type="text" value="<?php echo $x; ?>" style="width: 100%;">
										</td>
										<td>
											<input class="form-control datepicker" type="text" name="fase<?php echo $x; ?>_cierre" value="<?php echo $datos['fecha']; ?>" style="width: 100%;">
										</td>
										<td>
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">$</span></div>
												<input type="text" name="fase<?php echo $x; ?>_general" value="<?php echo $datos->precio; ?>" class="form-control" />
											</div>
										</td>
										<td>
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">$</span></div>
												<input type="text"  name="fase<?php echo $x; ?>_propietarios" value="<?php echo $datos->precio; ?>" class="form-control" />
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

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="agregarEvento" value="1">
								<button type="submit" class="btn btn-primary">Agregar Evento</button>
								<a href="<?php echo STASIS; ?>/" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Referencia
			} elseif (isset($referencia)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Pago</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Nombre</label>
							<div class="col-4">
								<input class="form-control mayusculas" required type="text" name="nombre" value="<?php echo $datos['nombre']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Sección</label>
							<div class="col-4">
								<select class="form-control" name="seccion">
									<option value="">Selecciona sección...</option>
									<option value="SC">CAÑADA DEL ENCINO</option>
									<option value="SR">HACIENDA DEL REY</option>
									<option value="SV">HACIENDA VALLE DE LOS ENCINOS</option>
									<option value="SL">LOMAS</option>
									<option value="VR">VISTA DEL REY</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Manzana</label>
							<div class="col-4">
								<input type="text" class="form-control mayusculas" name="manzana" maxlength="3">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Lote</label>
							<div class="col-4">
								<input type="text" class="form-control mayusculas" name="lote" maxlength="3">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Celular</label>
							<div class="col-4">
								<input type="text" class="form-control mask-telefono" id="celular" name="telefono" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Email</label>
							<div class="col-4">
								<input type="email" class="form-control minusculas" id="email1" name="email" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Confirmar Email</label>
							<div class="col-4">
								<input type="email" class="form-control minusculas" id="email2" name="email2" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Número de Boletos</label>
							<div class="col-4">
								<input type="number" class="form-control" id="fact-boletos" value="1" min="1" max="9" name="boletos" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Importe a Pagar</label>
							<div class="col-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-dollar-sign"></i>
										</span>
									</div>
									<input type="text" class="form-control form-disabled" id="fact-importe" name="importe" readonly value="900.00">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Concepto</label>
							<div class="col-4">
								<input type="text" class="form-control form-disabled" value="Boleto: Noche Mexicana Entre Viñedos" readonly required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="agregarReferencia" value="1">
								<button type="submit" class="btn btn-primary">Generar Referencia</button>
								<a href="<?php echo STASIS; ?>/movimientos/eventos/reservas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Transferencia bancaria
			} elseif (isset($transferencia)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Pago</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Nombre</label>
							<div class="col-4">
								<input class="form-control mayusculas" required type="text" name="nombre" value="<?php echo $datos['nombre']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Sección</label>
							<div class="col-4">
								<select class="form-control" name="seccion">
									<option value="">Selecciona sección...</option>
									<option value="SC">CAÑADA DEL ENCINO</option>
									<option value="SR">HACIENDA DEL REY</option>
									<option value="SV">HACIENDA VALLE DE LOS ENCINOS</option>
									<option value="SL">LOMAS</option>
									<option value="VR">VISTA DEL REY</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Manzana</label>
							<div class="col-4">
								<input type="text" class="form-control mayusculas" name="manzana" maxlength="3">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Lote</label>
							<div class="col-4">
								<input type="text" class="form-control mayusculas" name="lote" maxlength="3">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Celular</label>
							<div class="col-4">
								<input type="text" class="form-control mask-telefono" id="celular" name="telefono" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Email</label>
							<div class="col-4">
								<input type="email" class="form-control minusculas" id="email1" name="email" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Confirmar Email</label>
							<div class="col-4">
								<input type="email" class="form-control minusculas" id="email2" name="email2" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Número de Boletos</label>
							<div class="col-4">
								<input type="number" class="form-control" id="fact-boletos" value="1" min="1" max="9" name="boletos" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Importe a Pagar</label>
							<div class="col-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-dollar-sign"></i>
										</span>
									</div>
									<input type="text" class="form-control form-disabled" id="fact-importe" name="importe" readonly value="900.00">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Concepto</label>
							<div class="col-4">
								<input type="text" class="form-control form-disabled" value="Boleto: Noche Mexicana Entre Viñedos" readonly required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="agregarTransferencia" value="1">
								<button type="submit" class="btn btn-primary">Generar Referencia</button>
								<a href="<?php echo STASIS; ?>/movimientos/eventos/reservas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Efectivo
			} elseif (isset($efectivo)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Pago</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Nombre</label>
							<div class="col-4">
								<input class="form-control mayusculas" required type="text" name="nombre" value="<?php echo $datos['nombre']; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Sección</label>
							<div class="col-4">
								<select class="form-control" name="seccion">
									<option value="">Selecciona sección...</option>
									<option value="SC">CAÑADA DEL ENCINO</option>
									<option value="SR">HACIENDA DEL REY</option>
									<option value="SV">HACIENDA VALLE DE LOS ENCINOS</option>
									<option value="SL">LOMAS</option>
									<option value="VR">VISTA DEL REY</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Manzana</label>
							<div class="col-4">
								<input type="text" class="form-control mayusculas" name="manzana" maxlength="3">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Lote</label>
							<div class="col-4">
								<input type="text" class="form-control mayusculas" name="lote" maxlength="3">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Celular</label>
							<div class="col-4">
								<input type="text" class="form-control mask-telefono" id="celular" name="telefono" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Email</label>
							<div class="col-4">
								<input type="email" class="form-control minusculas" id="email1" name="email" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Confirmar Email</label>
							<div class="col-4">
								<input type="email" class="form-control minusculas" id="email2" name="email2" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Número de Boletos</label>
							<div class="col-4">
								<input type="number" class="form-control" id="fact-boletos" value="1" min="1" max="9" name="boletos" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Importe a Pagar</label>
							<div class="col-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-dollar-sign"></i>
										</span>
									</div>
									<input type="text" class="form-control form-disabled" id="fact-importe" name="importe" readonly value="900.00">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Concepto</label>
							<div class="col-4">
								<input type="text" class="form-control form-disabled" value="Boleto: Noche Mexicana Entre Viñedos" readonly required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="agregarEfectivo" value="1">
								<button type="submit" class="btn btn-primary">Generar Referencia</button>
								<a href="<?php echo STASIS; ?>/movimientos/eventos/reservas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Efectivo
			} elseif (isset($aplicarPago)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Reservaciön</span>
						</h3>
					</div>

					<div class="card-body">
						<h3 class="font-size-lg text-dark font-weight-bold mb-6">1. Datos de Cliente:</h3>

						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Código de Reservación:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $info['openpay_id']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Nombre:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $info['nombre']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Lote:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $info['lote']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Celular:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $info['telefono']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Email:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $info['email']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Número de Boletos:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $info['boletos']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Importe a Pagar</label>
							<div class="col-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-dollar-sign"></i>
										</span>
									</div>
									<input type="text" class="form-control form-disabled" id="fact-importe" name="importe" readonly value="<?php echo $info['importe']; ?>">
								</div>
							</div>
						</div>

						<!-- Datos de pago -->
						<h3 class="font-size-lg text-dark font-weight-bold mb-6 mt-15">2. Datos de Pago:</h3>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Tipo de Pago:</label>
							<div class="col-lg-4">
								<select class="form-control" disabled>
									<option>IMPORTE TOTAL</option>
								</select>
							</div>
						</div>

						<div class="tipopago-pago" <?php if($datos['abonos'] == 1) echo 'style="display: none;"'; ?>>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Método de Pago</label>
								<div class="col-lg-4">
									<select class="form-control" disabled>
										<option>EFECTIVO</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Fecha de Pago</label>
								<div class="col-lg-4">
									<input type="text" class="form-control" value="<?php echo date ('d/m/Y'); ?>" disabled>
								</div>
							</div>
							<div class="form-group row">
							<label class="col-lg-3 col-form-label">Importe Pagado</label>
							<div class="col-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-dollar-sign"></i>
										</span>
									</div>
									<input type="text" class="form-control form-disabled" value="<?php echo $info['importe']; ?>" disabled>
								</div>
							</div>
						</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Moneda de Pago</label>
								<div class="col-lg-4">
									<select class="form-control" disabled>
										<option>PESOS</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $info['openpay_id']; ?>">
								<input type="hidden" name="aplicarPago" value="1">
								<button type="submit" class="btn btn-primary">Aplicar Pago</button>
								<a href="<?php echo STASIS; ?>/movimientos/eventos/reservas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Asistencia
			} elseif (isset($asistencia)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header text-center">
						<h3 class="card-title text-center">
							<span class="card-label font-weight-bolder text-dark">Información de Asistencia</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="col-12 text-center mb-5" style="width: 100%; margin: 0 auto;" id="contenedor-asistencia"></div>

						<div class="col-12 text-center">
							Folio de Boleto
						</div>
						<div class="col-12 text-center">
							<input class="form-control" required type="text" id="asistencia" style="padding: 20px; width: 300px; text-align: center; font-size: 30px;">
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-12 text-center">
								<a href="<?php echo STASIS; ?>/movimientos/eventos/reservas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Listado Asistencia
			} elseif (isset($listadoAsistencia)) {
			?>

			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Asistencia</span>
				</h3>

				<div class="card-toolbar">
				</div>
			</div>

			<div class="card-body pt-2">
				<div class="row">
					<div class="col-md-12">

						<div class="tab-content">
							<!-- Activos -->
							<div class="tab-pane fade show active" id="activos" role="tabpanel" aria-labelledby="activos">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
											<th style="text-align: center;">Nombre</th>
											<th style="text-align: center;">Teléfono</th>
											<th style="text-align: center;">Email</th>
											<th style="text-align: center;"># Boletos</th>
											<th style="text-align: center;">Importe</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Forma de Pago</th>
											<th style="text-align: center;">Fecha de Compra</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listadoAsistencia['activos'] as $dato) {
										?>
										<tr <?php if($dato['confirmado'] == 1) echo 'class="table-primary"'; ?>>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['nombre']; ?></td>
											<td style="text-align: center;"><?php echo $dato['telefono']; ?></td>
											<td style="text-align: center;"><?php echo $dato['email']; ?></td>
											<td style="text-align: center;"><?php echo $dato['boletos']; ?></td>
											<td style="text-align: center;"><?php echo $dato['importe']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['forma_pago']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
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
			// Listado
			} elseif (isset($reservas)) {
			?>

			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
				</h3>

				<div class="card-toolbar">
					<div class="dropdown">
					    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					        <i class="fa fa-dollar-sign"></i> Generar Referencia
					    </button>
					    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					        <a class="dropdown-item" href="<?php echo STASIS; ?>/movimientos/eventos/referencia">Establecimiento</a>
					        <a class="dropdown-item" href="<?php echo STASIS; ?>/movimientos/eventos/transferencia">Transferencia Bancaria</a>
					        <a class="dropdown-item" href="<?php echo STASIS; ?>/movimientos/eventos/efectivo">Efectivo</a>
					    </div>
					</div>
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
										<span class="nav-text">Pagados <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nActivos']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#referencias">
										<span class="nav-icon">
											<i class="fa fa-clock"></i>
										</span>
										<span class="nav-text">Establecimientos <span class="label label-rounded label-warning" style="width: 40px;"><?php echo $listado['nReferencias']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#transferencias">
										<span class="nav-icon">
											<i class="fa fa-clock"></i>
										</span>
										<span class="nav-text">Transferencias <span class="label label-rounded label-warning" style="width: 40px;"><?php echo $listado['nTransferencias']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#efectivo">
										<span class="nav-icon">
											<i class="fa fa-clock"></i>
										</span>
										<span class="nav-text">Efectivo <span class="label label-rounded label-warning" style="width: 40px;"><?php echo $listado['nEfectivo']; ?></span></span>
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
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
											<th style="text-align: center;">Nombre</th>
											<th style="text-align: center;">Teléfono</th>
											<th style="text-align: center;">Email</th>
											<th style="text-align: center;"># Boletos</th>
											<th style="text-align: center;">Importe</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Forma de Pago</th>
											<th style="text-align: center;">Fecha de Compra</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['nombre']; ?></td>
											<td style="text-align: center;"><?php echo $dato['telefono']; ?></td>
											<td style="text-align: center;"><?php echo $dato['email']; ?></td>
											<td style="text-align: center;"><?php echo $dato['boletos']; ?></td>
											<td style="text-align: center;"><?php echo $dato['importe']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['forma_pago']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<?php
															if ($dato['status'] != 4) {
															?>
															<li class="navi-item">
																<a href="https://dashboard.openpay.mx/terminal/print-payment-conditions/m7aci0xq2pyewsqdhy9r/<?php echo $dato['openpay_id']; ?>" target="_blank" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Comprobante de Pago</span>
																</a>
															</li>
															<?php
															} else {
															?>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/eventos/comprobante_pago/<?php echo $dato['openpay_id']; ?>" target="_blank" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Comprobante de Pago</span>
																</a>
															</li>

															<?php
															}
															?>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/eventos/impresion/<?php echo $dato['id']; ?>" target="_blank" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Tickets de Reservación</span>
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

							<!-- Referencias -->
							<div class="tab-pane fade" id="referencias" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
											<th style="text-align: center;">Transacción Openpay</th>
											<th style="text-align: center;">Nombre</th>
											<th style="text-align: center;">Teléfono</th>
											<th style="text-align: center;">Email</th>
											<th style="text-align: center;"># Boletos</th>
											<th style="text-align: center;">Importe</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Fecha de Compra</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['referencias'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['openpay_id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['nombre']; ?></td>
											<td style="text-align: center;"><?php echo $dato['telefono']; ?></td>
											<td style="text-align: center;"><?php echo $dato['email']; ?></td>
											<td style="text-align: center;"><?php echo $dato['boletos']; ?></td>
											<td style="text-align: center;"><?php echo $dato['importe']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
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
																<a href="https://dashboard.openpay.mx/paynet-pdf/m7aci0xq2pyewsqdhy9r/transaction/<?php echo $dato['openpay_id']; ?>" target="_blank" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Referencia de Pago</span>
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

							<!-- Transferencias -->
							<div class="tab-pane fade" id="transferencias" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
											<th style="text-align: center;">Transacción Openpay</th>
											<th style="text-align: center;">Nombre</th>
											<th style="text-align: center;">Teléfono</th>
											<th style="text-align: center;">Email</th>
											<th style="text-align: center;"># Boletos</th>
											<th style="text-align: center;">Importe</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Fecha de Compra</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['transferencias'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['openpay_id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['nombre']; ?></td>
											<td style="text-align: center;"><?php echo $dato['telefono']; ?></td>
											<td style="text-align: center;"><?php echo $dato['email']; ?></td>
											<td style="text-align: center;"><?php echo $dato['boletos']; ?></td>
											<td style="text-align: center;"><?php echo $dato['importe']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
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
																<a href="https://dashboard.openpay.mx/spei-pdf/m7aci0xq2pyewsqdhy9r/<?php echo $dato['openpay_id']; ?>" target="_blank" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Referencia de Pago</span>
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

							<!-- Efectivo -->
							<div class="tab-pane fade" id="efectivo" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
											<th style="text-align: center;">Código Reservación</th>
											<th style="text-align: center;">Nombre</th>
											<th style="text-align: center;">Teléfono</th>
											<th style="text-align: center;">Email</th>
											<th style="text-align: center;"># Boletos</th>
											<th style="text-align: center;">Importe</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Fecha de Compra</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['efectivo'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['openpay_id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['nombre']; ?></td>
											<td style="text-align: center;"><?php echo $dato['telefono']; ?></td>
											<td style="text-align: center;"><?php echo $dato['email']; ?></td>
											<td style="text-align: center;"><?php echo $dato['boletos']; ?></td>
											<td style="text-align: center;"><?php echo $dato['importe']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
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
																<a href="<?php echo STASIS; ?>/movimientos/eventos/referencia_efectivo/<?php echo $dato['openpay_id']; ?>" target="_blank" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Referencia de Pago</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/eventos/aplicar_pago/<?php echo $dato['openpay_id']; ?>" target="_blank" class="navi-link">
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
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Activos <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nActivos']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#finalizados">
										<span class="nav-icon">
											<i class="fa fa-check-double"></i>
										</span>
										<span class="nav-text">Finalizados <span class="label label-rounded label-info" style="width: 40px;">0</span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#cancelados">
										<span class="nav-icon">
											<i class="fa fa-times"></i>
										</span>
										<span class="nav-text">Cancelados <span class="label label-rounded label-danger" style="width: 40px;">0</span></span>
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
							<div class="tab-pane active" id="activos" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">ID</th>
											<th style="text-align: center;">Nombre</th>
											<th style="text-align: center;">Fecha</th>
											<th style="text-align: center;">Limite de Asistencia</th>
											<th style="text-align: center;">Disponibilidad</th>
											<th style="text-align: center;">Imagen Web</th>
											<th style="text-align: center;">Imagen Movil</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['nombre']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td style="text-align: center;"><?php echo $dato['limite']; ?></td>
											<td style="text-align: center;"><?php echo $dato['existencia']; ?></td>
											<td><a href="<?php echo STASIS; ?>/data/privada/eventos/<?php echo $dato['imagen_web']; ?>"><i class="fa fa-download"></i> Descargar</a></td>
											<td><a href="<?php echo STASIS; ?>/data/privada/eventos/<?php echo $dato['imagen_movil']; ?>"><i class="fa fa-download"></i> Descargar</a></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/eventos/reservas/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-list"></i>
																	</span>
																	<span class="navi-text">Lista de Reservas</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/eventos/lista/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-list"></i>
																	</span>
																	<span class="navi-text">Lista de Asistencia</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/eventos/asistencia/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-users"></i>
																	</span>
																	<span class="navi-text">Confirmación de Asistencia</span>
																</a>
															</li>
															<!-- <li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/eventos/cancelar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-times"></i>
																	</span>
																	<span class="navi-text">Cancelar</span>
																</a>
															</li> -->

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