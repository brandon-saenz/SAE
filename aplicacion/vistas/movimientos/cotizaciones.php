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
<script>
	document.addEventListener("DOMContentLoaded", function(event) {
		console.log('PRUEBA DE SCRIPT $READY - OK');
		let num_lote = localStorage.getItem('num_lote');
		if(num_lote==null){
			localStorage.setItem('num_lote', '8541');
			console.log('DATA FROM NUM_LOTE = '+num_lote);
		}else{
			console.log('DATA FROM NUM_LOTE = '+num_lote);
		}
	});
</script>
<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<?php
			// Modificar
			if (isset($modificar)) {
			?>

			<br />
			<form method="post" action="" autocomplete="off" name="form-cotizacion">
				<table class="centrar tabla-inputs">
					<tr>
						<td>Alfanumérico/Folio:</td>
						<td>Agente:</td>
						<td>Código de Cliente:</td>
						<td>Razón Social:</td>
					</tr>
					<tr>
						<td>
							<div class="">
								<input type="text" class="form-control input-1 mayusculas" name="folioAlfanumerico" value="<?php echo $datos['alfanumerico']; ?>" readonly />
								<input type="text" class="form-control" name="folio" value="<?php echo $datos['id']; ?>" style="width: 126px;" disabled />
							</div>
						</td>
						<td><input type="text" class="form-control mayusculas" value="<?php echo $datos['agente']; ?>" disabled /></td>
						<td>
							<input type="hidden" id="cotizacion-id-cliente" name="idCliente" value="<?php echo $datos['id_cliente']; ?>" />
							<input type="text" class="form-control mayusculas" id="cotizacion-codigo-cliente" value="<?php echo $datos['codigo']; ?>" />
						</td>
						<td><input type="text" class="form-control mayusculas input-5" id="cotizacion-razon-social" value="<?php echo $datos['razon_social']; ?>" disabled /></td>
					</tr>
					<tr>
						<td>Moneda:</td>
						<td>Nombre del Solicitante:</td>
						<td>Teléfono del Solicitante:</td>
						<td>Correo del Solicitante:</td>
					</tr>
					<tr>
						<td>
							<select class="form-control input-3" name="moneda" id="cotizacion-moneda">
								<option value="1" <?php if($datos['moneda'] == 1) echo 'selected="selected"'; ?>>Pesos</option>
								<option value="2" <?php if($datos['moneda'] == 2) echo 'selected="selected"'; ?>>Dólares</option>
							</select>
						</td>
						<td>
							<select class="form-control " id="cotizacion-nombre-solicitante" style="width: 174px;" name="idSolicitante">
								<option value="<?php echo $datos['id_solicitante']; ?>"><?php echo $datos['nombre']; ?></option>
							</select>
							<input type="text" class="form-control  escondido mayusculas" style="width: 174px; display: none;" id="cotizacion-nombre-solicitante-nuevo" name="nombreSolicitante" />
							<button type="button" id="agregar-solicitante" class=" form-control input-0 btn btn-success"><i class="fa fa-plus-circle"></i></button>
							<input type="hidden" value="0" name="nuevo-solicitante" id="cotizacion-nuevo-solicitante" />
						</td>
						<td><input type="text" class="form-control input-3 numeric" id="cotizacion-telefono-solicitante" name="telefonoSolicitante" value="<?php echo $datos['telefono']; ?>" /></td>
						<td><input type="text" class="form-control input-5 minusculas" id="cotizacion-correo-solicitante" name="correoSolicitante" value="<?php echo $datos['correo']; ?>" /></td>
					</tr>
					<tr>
						<td><div class="tipo-cambio-textos">T. de Cambio:</div></td>
						<td>Fecha Actual:</td>
						<td>Fecha de Vigencia:</td>
						<td></td>
					</tr>
					<tr>
						<td>
							<div class="input-group input-2 tipo-cambio-textos">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input type="text" class="form-control input-0" value="<?php echo number_format((float)$datos['tipoCambio'], 2, '.', ''); ?>" id="tipo-cambio" name="tipoCambio" readonly />
							</div>
						</td>
						<td><input type="text" class="form-control input-3" value="<?php echo date('d/m/Y'); ?>" disabled /></td>
						<td>
							<input type="text" id="cotizacion-fecha-vigencia" class="form-control input-3" value="<?php echo Modelos_Fecha::formatearFecha($datos['vigencia']); ?>" />
							<input type="hidden" name="fechaVigencia" value="<?php echo date('Y-m-d', strtotime("+30 days")); ?>" />
						</td>
						<td class="">
							<button type="button" id="agregar-fila" class="form-control btn btn-success"><i class="fa fa-plus-circle"></i> Agregar partida</button>
						</td>
					</tr>
				</table><br />

				<table class="table table-bordered">
					<thead>
						<tr>
							<th>
								<a href="<?php echo STASIS; ?>/catalogos/partes/nuevo" class="btn btn-success" target="_blank" style="padding: 3px 5px;"><i class="fa fa-plus-circle"></i></a>
								No. Parte:
							</th>
							<th>Descripción:</th>
							<th>UM:</th>
							<th>Cantidad:</th>
							<th>Precio:</th>
							<th>Total:</th>
							<th>T.E.:</th>
						</tr>
					</thead>
					<tbody id="cotizacion-columnas">
						<?php
						for($x=1; $x<=100; $x++) {
							if ($x >= $datos['conteoPartes']) {
								$clase = 'class="hidden"';
							} else {
								$clase = '';
							}
						?>
						<tr id="fila<?php echo $x; ?>" <?php echo $clase; ?>>
							<td>
								<input class="form-control input-2  noParteVerificarCotizacion mayusculas" id="noParte<?php echo $x; ?>" name="noParte<?php echo $x; ?>" type="text" value="<?php echo $datos['partes'][$x]['codigo']; ?>" title="Número de parte inexistente" />
								<input type="hidden" name="idParte<?php echo $x; ?>" id="idParte<?php echo $x; ?>" value="<?php echo $datos['partes'][$x]['id']; ?>" />
								<input type="hidden" name="nuevaParte<?php echo $x; ?>" id="nuevaParte<?php echo $x; ?>" value="" />
							</td>
							<td><input class="form-control input-5 mayusculas" id="descripcion<?php echo $x; ?>" name="descripcion<?php echo $x; ?>" type="text" readonly value="<?php echo $datos['partes'][$x]['descripcion']; ?>" /></td>
							<td><input class="form-control input-1" id="um<?php echo $x; ?>" name="um<?php echo $x; ?>" value="<?php echo $datos['partes'][$x]['um']; ?>" type="text" readonly /></td>
							<td><input class="form-control input-1 numeric" id="cantidad<?php echo $x; ?>" name="cantidad<?php echo $x; ?>" type="text" value="<?php echo $datos['partes'][$x]['cantidad']; ?>" /></td>
							<td>
								<div class="input-group input-2">
									<div class="input-group-prepend"><span class="input-group-text">$</span></div>
									<input name="precio-escondido<?php echo $x; ?>" id="precio-escondido<?php echo $x; ?>" type="hidden" />
									<input class="form-control numeric campo-precio" id="precio<?php echo $x; ?>" type="text" name="precio<?php echo $x; ?>" value="<?php echo $datos['partes'][$x]['precio1']; ?>" title="?" />
								</div>
							</td>
							<td>
								<div class="input-group input-2">
									<div class="input-group-prepend"><span class="input-group-text">$</span></div>
									<input class="form-control" id="total<?php echo $x; ?>" name="total<?php echo $x; ?>" type="text" disabled value="<?php echo $datos['partes'][$x]['total']; ?>" />
								</div>
							</td>
							<td>
								<div class="input-group input-2">
									<input type="text" name="tiempoEntrega<?php echo $x; ?>" class="form-control input-3 mayusculas" maxlength="50" value="<?php echo $datos['partes'][$x]['tiempo_entrega']; ?>" />
								</div>
							</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>

				<div class="">
					Observaciones: <input type="text" name="observaciones" class="form-control input-3 mayusculas" value="<?php echo $datos['observaciones']; ?>" />
				</div>
			
				<div class="pull-right">
					<div class="input-group input-3 ">
						<div style="display:inline-block; width: 100px;">% Impuesto:</div>
						<div style="display:inline-block;" class="input-2">
							<select name="porImpuesto" class="form-control" id="porImpuesto">
				                <option <?php if ($datos['por_impuesto'] == ".0") echo 'selected'; ?> value=".00">0.00%</option>
				                <option <?php if ($datos['por_impuesto'] == ".08") echo 'selected'; ?> value=".08">8.00%</option>
				                <option <?php if ($datos['por_impuesto'] == ".16") echo 'selected'; ?> value=".16">16.00%</option>
							</select>
						</div>
						</div>
					<div class="input-group input-3 ">
					Subtotal:
						<div class="input-group-prepend"><span class="input-group-text">$</span></div>
						<input type="text" name="subtotal" class="form-control" id="subtotal" value="<?php echo $datos['subtotal']; ?>" readonly />
					</div>
					<div class="input-group input-3 " style="margin-left: 10px;">
					Impuesto:
						<div class="input-group-prepend"><span class="input-group-text">$</span></div>
						<input type="text" name="impuesto" class="form-control" id="impuesto" value="<?php echo $datos['impuesto']; ?>" readonly />
					</div>
					<div class="input-group input-3 " style="margin-left: 10px;">
					Total:
						<div class="input-group-prepend"><span class="input-group-text">$</span></div>
						<input type="text" name="total" class="form-control" id="total" value="<?php echo $datos['total']; ?>" readonly />
					</div>
				</div>

				<div class="clearfix"></div>
				<br />

				<div class="">
					<input type="hidden" id="filas" value="<?php echo $datos['conteoPartes']; ?>" />
					<input type="hidden" name="cotizacionModificada" value="<?php echo $datos['idCotizacion']; ?>" />
					<a href="<?php echo STASIS; ?>/principal/" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</a>
					<button type="submit" name="descargar" id="movimiento-descargar-cotizacion" class="btn btn-primary" data-clickeado="0"><i class="fa fa-download"></i> Generar y <strong>descargar</strong></button>
					<button type="submit" name="enviar" class="btn btn-primary"><i class="fa fa-envelope"></i> Generar y <strong>enviar</strong> a solicitante</button>
				</div>

				<br />
			</form>

			<?php
			// Pago en Efectivo
			} elseif (isset($pagare)) {
			?>
			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Cotización</span>
						</h3>
					</div>

					<div class="card-body">
						<h3 class="font-size-lg text-dark font-weight-bold mb-6">1. Datos de Cotización:</h3>

						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Folio:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $datos['id']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Propietario:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $datos['nombre']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Lote:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $datos['lote']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Concepto de Pago:</label>
							<div class="col-lg-4">
								<input type="text" class="form-control form-disabled" value="<?php echo $datos['concepto']; ?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Subtotal:</label>
							<div class="col-lg-4">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">$</span></div>
									<input value="<?php echo $datos['subtotal']; ?>" class="form-control form-disabled" type="text" readonly />
									<div class="input-group-append"><span class="input-group-text"><?php echo $datos['monedaFormatted']; ?></span></div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">IVA:</label>
							<div class="col-lg-4">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">$</span></div>
									<input value="<?php echo $datos['impuesto']; ?>" class="form-control form-disabled" type="text" readonly />
									<div class="input-group-append"><span class="input-group-text"><?php echo $datos['monedaFormatted']; ?></span></div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Total:</label>
							<div class="col-lg-4">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">$</span></div>
									<input name="total" value="<?php echo $datos['total']; ?>" class="form-control form-disabled" type="text" readonly />
									<div class="input-group-append"><span class="input-group-text"><?php echo $datos['monedaFormatted']; ?></span></div>
								</div>
							</div>
						</div>

						<!-- Datos de pago -->
						<h3 class="font-size-lg text-dark font-weight-bold mb-6 mt-15">2. Datos de Pago:</h3>
						<div class="form-group row">
							<label class="col-lg-3 col-form-label">Tipo de Pago:</label>
							<div class="col-lg-4">
								<select class="form-control cpc-tipopago" name="cpc_tipopago">
									<option value="1" <?php if($datos['abonos'] == 0) echo 'selected="selected"'; ?>>IMPORTE TOTAL</option>
									<option value="2" <?php if($datos['abonos'] == 1) echo 'selected="selected"'; ?>>EN ABONOS</option>
								</select>
							</div>
						</div>

						<div class="tipopago-pago" <?php if($datos['abonos'] == 1) echo 'style="display: none;"'; ?>>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Método de Pago</label>
								<div class="col-lg-4">
									<select class="form-control cpc-metodopago" name="cpc_metodopago">
										<option value="">Selecciona...</option>
										<option value="1" <?php if($datos['cpc_metodopago'] == 1) echo 'selected="selected"'; ?>>EFECTIVO</option>
										<option value="2" <?php if($datos['cpc_metodopago'] == 2) echo 'selected="selected"'; ?>>CHEQUE</option>
										<option value="3" <?php if($datos['cpc_metodopago'] == 3) echo 'selected="selected"'; ?>>TRANSFERENCIA</option>
										<option value="4" <?php if($datos['cpc_metodopago'] == 4) echo 'selected="selected"'; ?>>DEPÓSITO BANCARIO</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Fecha de Pago</label>
								<div class="col-lg-4">
									<input type="text" class="form-control datepicker" style="width: 100%;" id="fecha_pago" name="fecha_pago" maxlength="80" value="<?php echo $datos['fecha_pago']; ?>">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Importe Pagado</label>
								<div class="col-lg-4">
									<div class="input-group">
										<div class="input-group-prepend"><span class="input-group-text">$</span></div>
										<input type="text" class="form-control numeric" id="importe_pagado" name="importe_pagado" maxlength="20" value="<?php echo $datos['importe_pagado']; ?>">
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Moneda de Pago</label>
								<div class="col-lg-4">
									<select class="form-control">
										<option value="">Selecciona...</option>
										<option value="1" <?php if($datos['cpc_metodopago'] == 1) echo 'selected="selected"'; ?>>PESOS</option>
										<option value="2" <?php if($datos['cpc_metodopago'] == 2) echo 'selected="selected"'; ?>>DÓLARES</option>
									</select>
								</div>
							</div>

							<div class="form-group row campo-banco" style="<?php if($datos['cpc_metodopago'] == 1 || $datos['cpc_metodopago'] == 0 || $datos['cpc_metodopago'] == 5) echo 'display: none;'; ?>">
								<label class="col-lg-3 col-form-label">Banco</label>
								<div class="col-lg-4">
									<input type="text" class="form-control mayusculas" id="banco" name="banco" maxlength="50" value="<?php echo $datos['banco']; ?>">
								</div>
							</div>

							<div class="form-group row campo-cheque" style="<?php if($datos['cpc_metodopago'] == 4 || $datos['cpc_metodopago'] == 1 || $datos['cpc_metodopago'] == 3 || $datos['cpc_metodopago'] == 0 || $datos['cpc_metodopago'] == 5) echo 'display: none;'; ?>">
								<label class="col-lg-3 col-form-label">Número de Cheque</label>
								<div class="col-lg-4">
									<input type="text" class="form-control mayusculas" id="num_cheque" name="num_cheque" maxlength="50" value="<?php echo $datos['num_cheque']; ?>">
								</div>
							</div>

							<div class="form-group row campo-aut" style="<?php if($datos['cpc_metodopago'] == 1 || $datos['cpc_metodopago'] == 2 || $datos['cpc_metodopago'] == 0) echo 'display: none;'; ?>">
								<label class="col-lg-3 col-form-label">Número de Autorización</label>
								<div class="col-lg-4">
									<input type="text" class="form-control mayusculas" id="num_aut" name="num_aut" maxlength="50" value="<?php echo $datos['num_aut']; ?>">
								</div>
							</div>
						</div>

						<!-- Abono -->
						<div class="tipopago-abono" <?php if($datos['abonos'] == 0) echo 'style="display: none;"'; ?>>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Total Abonado:</label>
								<div class="col-lg-4">
									<div class="input-group">
										<div class="input-group-prepend"><span class="input-group-text">$</span></div>
										<input type="text" class="form-control form-disabled" id="total_abonado" name="total_abonado" readonly>
									</div>
								</div>
							</div>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>No. Abono:</th>
										<th>Método de Pago:</th>
										<th>Fecha de Pago:</th>
										<th>Importe Pagado:</th>
										<th>Banco:</th>
										<th>Número de Cheque:</th>
										<th>Número de Autorización:</th>
									</tr>
								</thead>
								<tbody>
									<?php
									for ($x=1; $x<=5; $x++) {
									?>
									<tr id="fila<?php echo $x; ?>">
										<!-- No. de parte -->
										<td style="width: 10%;">
											<input class="form-control form-disabled" name="no_abono<?php echo $x; ?>" type="text" value="<?php echo $x; ?>" readonly />
										</td>
										<!-- Metodo de Pago -->
										<td style="width: 20%;">
											<select class="form-control cpc-metodopago" name="metodo_pago<?php echo $x; ?>">
												<option value="">Selecciona...</option>
												<option value="1" <?php if($datos['abonosArray'][$x]['metodo_pago'] == 1) echo 'selected="selected"'; ?>>EFECTIVO</option>
												<option value="2" <?php if($datos['abonosArray'][$x]['metodo_pago'] == 2) echo 'selected="selected"'; ?>>CHEQUE</option>
												<option value="3" <?php if($datos['abonosArray'][$x]['metodo_pago'] == 3) echo 'selected="selected"'; ?>>TRANSFERENCIA</option>
												<option value="4" <?php if($datos['abonosArray'][$x]['metodo_pago'] == 4) echo 'selected="selected"'; ?>>DEPÓSITO BANCARIO</option>
											</select>
										</td>
										<!-- Fecha de Pago -->
										<td style="width: 15%;">
											<input type="text" class="form-control datepicker" id="fecha_pago<?php echo $x; ?>" name="fecha_pago<?php echo $x; ?>" value="<?php echo $datos['abonosArray'][$x]['fecha_pago']; ?>">
										</td>
										<!-- Importe Pagado -->
										<td style="width: 20%;">
											<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">$</span></div>
												<input type="text" class="form-control numeric abonos-importe" id="importe_pagado<?php echo $x; ?>" name="importe_pagado<?php echo $x; ?>" value="<?php echo $datos['abonosArray'][$x]['importe_pagado']; ?>">
											</div>
										</td>
										<!-- Banco -->
										<td style="width: 15%;">
											<input type="text" class="form-control mayusculas" id="banco<?php echo $x; ?>" name="banco<?php echo $x; ?>" maxlength="80" value="<?php echo $datos['abonosArray'][$x]['banco']; ?>">
										</td>
										<!-- Numero de Cheque -->
										<td style="width: 10%;">
											<input type="text" class="form-control mayusculas" id="no_cheque<?php echo $x; ?>" name="no_cheque<?php echo $x; ?>" maxlength="80" value="<?php echo $datos['abonosArray'][$x]['no_cheque']; ?>">
										</td>
										<!-- Numero de Autorizacion -->
										<td style="width: 10%;">
											<input type="text" class="form-control mayusculas" id="no_autorizacion<?php echo $x; ?>" name="no_autorizacion<?php echo $x; ?>" maxlength="80" value="<?php echo $datos['abonosArray'][$x]['no_autorizacion']; ?>">
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
								<input type="hidden" name="aplicarCambiosPago" value="1">
								<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
								<a href="<?php echo STASIS; ?>/movimientos/cotizaciones/reporte" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Nueva
			} elseif (isset($generar)) {
			?>

			<form method="post" action="" autocomplete="off" name="form-cotizacion">
				<div class="card-body">
					<div class="form-group row">
						<div class="col-md-2">
							<label class="col-form-label">Folio:</label>
							<div class="input-group">
								<input type="text" class="form-control mayusculas" value="<?php echo $datos['folio']; ?>" disabled />
							</div>
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Usuario:</label>
							<input type="text" class="form-control mayusculas" value="<?php echo $datos['agente']; ?>" disabled />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Lote:</label>
							<select name="id_propietario" class="form-control" id="cotizacion-propietario" required>
								<option value="">Selecciona lote...</option>
								<?php echo $listadoPropietarios; ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="col-form-label">Propietario:</label>
							<input type="text" class="form-control mayusculas" id="propietario" disabled />
						</div>
						<div class="col-md-2">
							<label class="col-form-label">T. de Cambio:</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										$
									</span>
								</div>
								<input type="text" class="form-control" value="<?php echo number_format((float)$datos['tipoCambio'], 2, '.', ''); ?>" id="tipo-cambio" name="tipoCambio" disabled/>
							</div>
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Teléfono 1:</label>
							<div class="input-group">
								<input type="text" class="form-control" name="telefono1" id="telefono1" />
							</div>
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Teléfono 2:</label>
							<input type="text" class="form-control" name="telefono2" id="telefono2" />
						</div>
						<div class="col-md-4">
							<label class="col-form-label">Correo:</label>
							<input type="text" class="form-control minusculas" name="correo" id="correo" />
						</div>

						
						<div class="col-md-2">
							<label class="col-form-label">Fecha Actual:</label>
							<input type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" disabled />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Fecha de Vigencia:</label>
							<input type="text" name="fechaVigencia" class="form-control datepicker" value="<?php echo date('d/m/Y', strtotime("+30 days")); ?>" required style="width: 100%;" />
						</div>
					</div>

					<div class="table-responsive">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th style="text-align: center;">Descripción:</th>
									<th style="text-align: center;">Unidad de Medida:</th>
									<th style="text-align: center;">Cantidad:</th>
									<th style="text-align: center;">Moneda:</th>
									<th style="text-align: center;">Precio:</th>
									<th style="text-align: center;">Total:</th>
								</tr>
							</thead>
							<tbody id="cotizacion-columnas">
								<?php
								for($x=1; $x<=1; $x++) {
								?>
								<tr id="fila<?php echo $x; ?>" <?php echo $clase; ?>>
									<td>
										<select class="form-control input-6 cotizacion-servicio campo-precio-cotizacion" id="descripcion<?php echo $x; ?>" name="descripcion<?php echo $x; ?>">
											<option value="">Selecciona servicio...</option>
											<?php echo $listadoServicios; ?>
										</select>
									</td>

									<td id="um-select<?php echo $x; ?>"><input class="form-control input-2 mayusculas" id="um<?php echo $x; ?>" name="um<?php echo $x; ?>" type="text" /></td>

									<td><input class="form-control input-1 numeric campo-precio-cotizacion" id="cantidad<?php echo $x; ?>" name="cantidad<?php echo $x; ?>" type="text" /></td>

									<td><input class="form-control form-disabled input-2" id="moneda<?php echo $x; ?>" name="moneda" type="text" readonly required /></td>

									<td>
										<div class="input-group input-3">
											<div class="input-group-prepend"><span class="input-group-text">$</span></div>
											<input class="form-control numeric campo-precio-cotizacion" id="precio<?php echo $x; ?>" type="text" name="precio<?php echo $x; ?>" />
										</div>
									</td>

									<td>
										<div class="input-group input-3">
											<div class="input-group-prepend"><span class="input-group-text">$</span></div>
											<input class="form-control" id="total<?php echo $x; ?>" name="total<?php echo $x; ?>" type="text" disabled />
										</div>
									</td>

								</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>

					<div class="form-group row">
						<div class="col-md-4">
							<label class="col-form-label">Observaciones:</label>
							<input type="text" name="observaciones" class="form-control mayusculas" />
						</div>
						<div class="col-md-2">
							<label class="col-form-label">% Impuesto:</label>
							<div class="input-group ">
								<input type="text" name="porImpuesto" class="form-control form-disabled" id="porImpuesto" readonly required />
								<div class="input-group-append"><span class="input-group-text">%</span></div>
							</div>
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Subtotal:</label>
							<div class="input-group ">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input type="text" name="subtotal" class="form-control form-disabled" id="subtotal" readonly />
							</div>
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Impuesto:</label>
							<div class="input-group " style="margin-left: 10px;">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input type="text" name="impuesto" class="form-control form-disabled" id="impuesto" readonly />
							</div>
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Total:</label>
							<div class="input-group " style="margin-left: 10px;">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input type="text" name="total" class="form-control form-disabled" id="total" readonly />
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer text-center">
					<input type="hidden" id="filas" value="1" />
					<a href="<?php echo STASIS; ?>/principal/" class="btn btn-secondary">Regresar</a>
					<button type="submit" name="generar" class="btn btn-primary">Generar Cotización</button>
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
										<span class="nav-text">Pendientes <span class="label label-rounded label-success" style="width: 40px;"><?php echo count($listado['pendientes']); ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#revisadas">
										<span class="nav-icon">
											<i class="fa fa-eye"></i>
										</span>
										<span class="nav-text">Revisadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo count($listado['revisadas']); ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#aceptadas">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Aceptadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo count($listado['aceptadas']); ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#pagadas">
										<span class="nav-icon">
											<i class="fa fa-dollar-sign"></i>
										</span>
										<span class="nav-text">Pagadas <span class="label label-rounded label-info" style="width: 40px;"><?php echo count($listado['pagadas']); ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#rechazadas">
										<span class="nav-icon">
											<i class="fa fa-times"></i>
										</span>
										<span class="nav-text">Rechazadas <span class="label label-rounded label-danger" style="width: 40px;"><?php echo count($listado['rechazadas']); ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#canceladas">
										<span class="nav-icon">
											<i class="fa fa-times"></i>
										</span>
										<span class="nav-text">Canceladas <span class="label label-rounded label-danger" style="width: 40px;"><?php echo count($listado['canceladas']); ?></span></span>
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
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Generado Por</th>
											<th style="text-align: center;">Total</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Fecha de Vigencia</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['agente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['total']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_vigencia']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/cotizaciones/cancelar/<?php echo $dato['id']; ?>" class="navi-link">
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

							<!-- Revisadas -->
							<div class="tab-pane" id="revisadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Generado Por</th>
											<th style="text-align: center;">Total</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Fecha de Vigencia</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['revisadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['agente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['total']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_vigencia']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
															    <button class="dropdown-item btn-notificacion navi-link" data-id="<?php echo $dato['id']; ?>" data-correo="<?php echo $dato['email']; ?>" data-celular="<?php echo $dato['celular']; ?>" data-toggle="modal" data-target="#notificacion" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-paper-plane"></i>
																	</span>
																	<span class="navi-text">Enviar Notificación</span>
																</button>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/cotizaciones/cancelar/<?php echo $dato['id']; ?>" class="navi-link">
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

							<!-- Aceptadas -->
							<div class="tab-pane" id="aceptadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Generado Por</th>
											<th style="text-align: center;">Total</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Fecha de Vigencia</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['aceptadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['agente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['total']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_vigencia']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
															    <a href="<?php echo STASIS; ?>/e/p/f/<?php echo $dato['alfanumerico']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-credit-card"></i>
																	</span>
																	<span class="navi-text">Realizar Pago en Linea</span>
																</a>
															</li>
															<li class="navi-item">
															    <a href="<?php echo STASIS; ?>/movimientos/cotizaciones/referenciab/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-money-bill"></i>
																	</span>
																	<span class="navi-text">Referencia de Pago Bancaria</span>
																</a>
															</li>
															<li class="navi-item">
															    <a href="<?php echo STASIS; ?>/movimientos/cotizaciones/referenciat/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-money-bill"></i>
																	</span>
																	<span class="navi-text">Referencia de Pago en Tienda</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/cotizaciones/cancelar/<?php echo $dato['id']; ?>" class="navi-link">
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

							<!-- Pagadas -->
							<div class="tab-pane" id="pagadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Generado Por</th>
											<th style="text-align: center;">Total</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Autorización Openpay</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pagadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['agente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['total']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['openpay']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
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

							<!-- Rechazadas -->
							<div class="tab-pane" id="rechazadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Generado Por</th>
											<th style="text-align: center;">Total</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Fecha de Vigencia</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['rechazadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['agente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['total']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_vigencia']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/cotizaciones/reactivar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
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

							<!-- Canceladas -->
							<div class="tab-pane" id="canceladas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Propietario</th>
											<th style="text-align: center;">Lote</th>
											<th style="text-align: center;">Generado Por</th>
											<th style="text-align: center;">Total</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Fecha de Vigencia</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['canceladas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['lote']; ?></td>
											<td style="text-align: center;"><?php echo $dato['agente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['total']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_vigencia']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/cotizaciones/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
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
			<div class="modal fade" id="notificacion" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de enviar una notificación al propietario acerca de esta cotización?<br />Se enviará por SMS al número de celular <b><span id="propietario-celular"></span></b> y al correo <b><span id="propietario-correo"></span></b>
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <a href="#" type="button" class="btn btn-primary" id="btn-enviar-notificacion"><i class="fa fa-check"></i> Si, enviar</a>
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