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

			if ($_SESSION['entregadas'] >= 1) {
			?>

			<div class="row mb-12">
				<div class="col-md-12">
					<div class="card card-custom">
						<div class="card-body text-center">
							<div class="container px-40">
								<h1 style="font-weight: bold;" class="pt-10 pb-10">Requisiciones pendientes por recibir.</h1>
								<h4><i class="fa fa-exclamation-triangle"></i> Actualmente tienes requisiciones pendientes en status de <b>entregadas</b> que deben ser <b>recibidas</b>.</h4>
							</div>
						</div>
						<div class="card-footer text-center">
							<a href="<?php echo STASIS; ?>/movimientos/compras/historial" class="btn btn-primary"><i class="fa fa-table"></i> Listado de Requisiciones</a>
						</div>
					</div>
				</div>
			</div>

			<?php
			} else {
			?>

			<form method="post" action="" autocomplete="off">
				<div class="card-body">

					<div class="form-group row">
						<div class="col-md-2">
							<label class="col-form-label">Folio:</label>
							<input type="text" class="form-control mayusculas" value="<?php echo $datos['folio']; ?>" disabled />
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Fecha:</label>
							<input type="text" class="form-control mayusculas" value="<?php echo date('d/m/Y'); ?>" disabled />
						</div>
						<div class="col-md-4">
							<label class="col-form-label">Solicita:</label>
							<input type="text" class="form-control mayusculas" value="<?php echo $_SESSION['login_nombre'] . ' ' . $_SESSION['login_apellidos']; ?>" disabled />
						</div>
						<div class="col-md-4">
							<label class="col-form-label">Departamento:</label>
							<input type="text" class="form-control mayusculas" value="<?php echo $_SESSION['login_centro_trabajo']; ?>" disabled />
							<input type="hidden" name="id_departamento" value="<?php echo $_SESSION['login_id_departamento']; ?>" />
						</div>
						<div class="col-md-4">
							<label class="col-form-label">* Centro de Costo:</label>
							<select class="form-control" name="centro_costo" id="requi-cc" required>
								<option value="">Selecciona opción...</option>
								<?php echo $listadoCentrosTrabajo; ?>
							</select>
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Director:</label>
							<input type="text" id="director" class="form-control mayusculas" disabled />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Comprador:</label>
							<input type="text" id="comprador" class="form-control mayusculas" disabled />
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Agregar Fila:</label>
							<button type="button" id="agregar-fila" class="form-control btn btn-success"><i class="fa fa-plus-circle"></i> Agregar Fila</button>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th style="width: 25%;">Producto y/o Servicio:</th>
									<th style="width: 15%;">Tipo:</th>
									<th style="width: 10%;">Cantidad:</th>
									<th style="width: 10%;">UM:</th>
									<th style="width: 25%;">Explicación y/o<br />Justificación:</th>
									<th style="width: 15%;">Observaciones / Link Producto:</th>
								</tr>
							</thead>
							<tbody id="cotizacion-columnas">
								<?php
								for($x=1; $x<=50; $x++) {
									if (isset($datos['conteoPartes'])) {
										if ($x < $datos['conteoPartes']) {
											$clase = '';
										} else {
											$clase = 'class="hidden"';
										}
									} else {
										if ($x >= 6) {
											$clase = 'class="hidden"';
										} else {
											$clase = '';
										}
									}
								?>
								<tr id="fila<?php echo $x; ?>" <?php echo $clase; ?>>
									<td>
										<input class="form-control input-4 mayusculas requisicion-producto" value="<?php echo $datos['partes'][$x]['producto']; ?>" id="producto<?php echo $x; ?>" name="producto<?php echo $x; ?>" type="text" <?php if ($x == 1) echo 'required'; ?> />
									</td>
									<td>
										<select class="form-control input-3" id="tipo<?php echo $x; ?>" name="tipo<?php echo $x; ?>">
											<option value=""></option>
											<?php
											if ($datos['partes'][$x]['tipo'] != '') {
												echo $datos['partes'][$x]['tipo'];
											} else {
												echo $listadoTipos;
											}
											?>
										</select>
									</td>
									<td>
										<input class="form-control input-2 mayusculas" value="<?php echo $datos['partes'][$x]['cantidad']; ?>" id="cantidad<?php echo $x; ?>" name="cantidad<?php echo $x; ?>" type="text" />
									</td>
									<td>
										<input class="form-control input-2 mayusculas" value="<?php echo $datos['partes'][$x]['um']; ?>" id="um<?php echo $x; ?>" name="um<?php echo $x; ?>" type="text" />
									</td>
									<td>
										<input class="form-control input-4 mayusculas" value="<?php echo $datos['partes'][$x]['justificacion']; ?>" id="justificacion<?php echo $x; ?>" name="justificacion<?php echo $x; ?>" type="text" />
									</td>
									<td>
										<input class="form-control input-3 mayusculas" value="<?php echo $datos['partes'][$x]['observaciones']; ?>" id="observaciones<?php echo $x; ?>" name="observaciones<?php echo $x; ?>" type="text" />
									</td>
								</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

				<?php
				// Agregar
                if (isset($nuevo)) {
                ?>

                <div class="card-footer text-center">
					<input type="hidden" id="filas" value="5" />
					<a href="<?php echo STASIS; ?>/principal/" class="btn btn-secondary">Regresar</a>
					<button type="submit" name="generar" class="btn btn-primary"><i class="fa fa-check"></i> Generar Requisición</button>
				</div>

                <?php
                // Editar
                } elseif (isset($editar)) {
                ?>

                <div class="card-footer text-center">
					<input type="hidden" id="filas" value="<?php echo $datos['conteoPartes']; ?>" />
					<input type="hidden" name="id" value="<?php echo $datos['id_requisicion']; ?>" />
					<button type="submit" name="generarModificar" class="btn btn-primary mr-2"><i class="fa fa-check"></i> Aplicar Cambios</button>
					<a href="<?php echo STASIS; ?>/movimientos/compras/historial" class="btn btn-secondary">Regresar</a>
				</div>

                <?php
                }
                ?>
			</form>

			<?php
			}
			?>

			

			<?php
			// Rechazar
			} elseif (isset($rechazar)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Requisición</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $id; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">Motivo</label>
							<div class="col-md-6">
								<select class="form-control" name="tipo_rechazo" required>
									<option value="">Selecciona opción</option>
									<option value="1">INTERNO</option>
									<option value="2">EXTERNO</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">Motivo de Rechazo</label>
							<div class="col-md-6">
								<input type="text" name="motivo_rechazo" class="form-control mayusculas" required />
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
								<input type="hidden" name="rechazar" value="1">
								<button type="submit" class="btn btn-primary">Rechazar Requisición</button>
								<a href="<?php echo STASIS; ?>/movimientos/compras/historial" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Procesar
			} elseif (isset($procesar)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Requisición</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['id_requisicion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Solicitado Por</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['solicita']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Departamento</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['departamento']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Producto y/o Servicio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['producto']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Tipo</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['tipo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Cantidad</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['cantidad']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Unidad de Medida</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['um']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Explicación y/o Justificación</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['justificacion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Observaciones / Link Producto</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['observaciones']; ?>" disabled>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Orden de Compra</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Proveedor</label>
							<div class="col-3">
								<select class="form-control" name="proveedor" required>
									<option value="">Selecciona proveedor...</option>
									<option value="0">N/A</option>
									<?php echo $listadoProveedores; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Cuenta Contable</label>
							<div class="col-3">
								<select class="form-control" name="cuenta_contable" required>
									<option value="">Selecciona cuenta contable...</option>
									<?php echo $listadoCuentasContables; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Folio de Orden de Compra</label>
							<div class="col-3">
								<input class="form-control mayusculas" type="text" name="oc" value="<?php echo $datos['oc']; ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Dias de Entrega</label>
							<div class="col-3">
								<input class="form-control numeric" type="text" name="dias_entrega" maxlength="2" value="<?php echo $datos['dias_entrega']; ?>" required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['folio']; ?>">
								<input type="hidden" name="procesar" value="1">
								<button type="submit" class="btn btn-primary">Procesar Requisición</button>
								<a href="<?php echo STASIS; ?>/movimientos/compras/historial" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Editar Procesar
			} elseif (isset($editarProcesar)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Requisición</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['id_requisicion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Solicitado Por</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['solicita']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Departamento</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['departamento']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Producto y/o Servicio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['producto']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Tipo</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['tipo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Cantidad</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['cantidad']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Unidad de Medida</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['um']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Explicación y/o Justificación</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['justificacion']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-search-input" class="col-2 col-form-label">Observaciones / Link Producto</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['observaciones']; ?>" disabled>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Orden de Compra</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Proveedor</label>
							<div class="col-3">
								<select class="form-control" name="proveedor" required>
									<option value="">Selecciona proveedor...</option>
									<option value="0">N/A</option>
									<?php echo $listadoProveedores; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Cuenta Contable</label>
							<div class="col-3">
								<select class="form-control" name="cuenta_contable" required>
									<option value="">Selecciona cuenta contable...</option>
									<?php echo $listadoCuentasContables; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Folio de Orden de Compra</label>
							<div class="col-3">
								<input class="form-control mayusculas" type="text" name="oc" value="<?php echo $datos['oc']; ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">Dias de Entrega</label>
							<div class="col-3">
								<input class="form-control numeric" type="text" name="dias_entrega" maxlength="2" value="<?php echo $datos['dias_entrega']; ?>" disabled>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['folio']; ?>">
								<input type="hidden" name="procesarAplicarCambios" value="1">
								<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
								<a href="<?php echo STASIS; ?>/movimientos/compras/historial" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Procesar multiples requsiciones
			} elseif (isset($procesarMultiple)) {
			?>

			<form class="form" action="" method="post">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Orden de Compra</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Proveedor</label>
							<div class="col-3">
								<select class="form-control" name="proveedor" required>
									<option value="">Selecciona proveedor...</option>
									<option value="0">N/A</option>
									<?php echo $listadoProveedores; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Folio de Orden de Compra</label>
							<div class="col-3">
								<input class="form-control mayusculas" type="text" name="oc" value="<?php echo $datos['oc']; ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label">* Dias de Entrega</label>
							<div class="col-3">
								<input class="form-control numeric" type="text" name="dias_entrega" maxlength="2" value="<?php echo $datos['dias_entrega']; ?>" required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="ids" value="<?php echo $_GET['ids']; ?>">
								<input type="hidden" name="procesarMultiples" value="1">
								<button type="submit" class="btn btn-primary">Procesar Requisiciones</button>
								<a href="<?php echo STASIS; ?>/movimientos/compras/historial" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Historial
			} elseif (isset($historial)) {
				// Tipo 1: Jefe directo
				// Tipo 2: Colaborador
				// Tipo 3: Evaluador
			?>

			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
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

					<div class="row mb-3">
						<div class="col-md-6">
							<form method="post" action="<?php echo STASIS; ?>/movimientos/compras/excel_global">
								<button type="submit" class="btn btn-success btn-md font-weight-bolder" ><i class="fa fa-table"></i> Reporte Global</button>
								<input type="text" class="form-control datepicker" style="width: 150px;" name="fechaInicio" placeholder="Fecha inicio" />
								<input type="text" class="form-control datepicker" style="width: 150px;" name="fechaFin" placeholder="Fecha fin" />
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
					        <a class="btn btn-primary font-weight-bolder" href="<?php echo STASIS; ?>/movimientos/compras/excel"><i class="fa fa-table"></i> Reporte Autorizadas</a>
					    </div>
					</div><br />

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
									<a class="nav-link" data-toggle="tab" href="#autorizadas">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Autorizadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nAutorizadas']; ?></span></span>
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
									<a class="nav-link" data-toggle="tab" href="#entregadas">
										<span class="nav-icon">
											<i class="fa fa-box"></i>
										</span>
										<span class="nav-text">Entregadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nEntregadas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#recibidas">
										<span class="nav-icon">
											<i class="fa fa-box-open"></i>
										</span>
										<span class="nav-text">Recibidas <span class="label label-rounded label-info" style="width: 40px;"><?php echo $listado['nRecibidas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#atrasadas">
										<span class="nav-icon">
											<i class="fa fa-exclamation-triangle"></i>
										</span>
										<span class="nav-text">Atrasadas <span class="label label-rounded label-warning" style="width: 40px;"><?php echo $listado['nAtrasadas']; ?></span></span>
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
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#rechazadas">
										<span class="nav-icon">
											<i class="fa fa-ban"></i>
										</span>
										<span class="nav-text">Rechazadas <span class="label label-rounded label-danger" style="width: 40px;"><?php echo $listado['nRechazadas']; ?></span></span>
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
									      	<th style="text-align: center;">Folio</th>
									    	<th style="text-align: center;">Solicitado Por</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Autoriza</th>
									    	<th style="text-align: center;">Fecha de Creación</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['jefe']; ?></td>
											<td data-sort="<?php echo $dato['fechaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<?php
															// Colaborador
															if ($_SESSION['login_tipo'] == 2) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/compras/modificar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las fa-pen"></i>
																	</span>
																	<span class="navi-text">Modificar</span>
																</a>
															</li>
															<li class="navi-item">
																<button class="dropdown-item btn-cancelar-requisicion navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#cancelar-requisicion" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las fa-times"></i>
																	</span>
																	<span class="navi-text">Cancelar</span>
																</button>
															</li>
															<?php
															}

															// Jefe directo
															if ($_SESSION['login_tipo'] == 1) {
															?>
															<li class="navi-item">
																<button class="dropdown-item btn-autorizar-requisicion navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#autorizar-requisicion" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
																	</span>
																	<span class="navi-text">Autorizar</span>
																</button>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/compras/rechazar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las fa-ban"></i>
																	</span>
																	<span class="navi-text">Rechazar</span>
																</a>
															</li>
															<?php
															}
															?>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
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

							<!-- Autorizadas -->
							<div class="tab-pane" id="autorizadas" role="tabpanel">
								<div class="text-right">
									<form method="get" action="<?php echo STASIS; ?>/movimientos/compras/procesar_multiple/">
										<input type="hidden" name="ids" id="ids" />
									    <button type="submit" id="btn-cargar" class="btn btn-primary btn-md mb-5 py-2 font-weight-bolder" style="display: none;"><i class="fa fa-cog"></i> Procesar Múltiples Requisiciones</button>
									</form>
								</div>

								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									      	<?php
											if ($_SESSION['login_puesto'] == 'GERENTE DE COMPRAS' || $_SESSION['login_puesto'] == 'AUXILIAR DE COMPRAS' || $_SESSION['login_tipo'] == 3) {
											?>
									      	<th style="text-align: center;">Procesar</th>
									      	<?php
											}
											?>
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
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id_requisicion']; ?>" class="navi-link"><?php echo $dato['id_requisicion']; ?></a></td>
											
											<?php
											if ($_SESSION['login_puesto'] == 'GERENTE DE COMPRAS' || $_SESSION['login_puesto'] == 'AUXILIAR DE COMPRAS' || $_SESSION['login_tipo'] == 3) {
											?>
											<td style="text-align: center;">
												<input type="checkbox" class="form-control checkbox-procesar" style="width: 20px; height: 20px;" name="checkboxIds" value="<?php echo $dato['id']; ?>">
											</td>
											<?php
											}
											?>

											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['um']; ?></td>
											<td data-sort="<?php echo $dato['fechaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td style="text-align: center;"><?php echo $dato['autoriza']; ?></td>
											<td data-sort="<?php echo $dato['fechaAutorizacionTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_autorizacion']; ?></td>
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

							<!-- Procesando -->
							<div class="tab-pane" id="procesando" role="tabpanel">
								<div class="text-right">
									<form method="post" action="" id="form-recibir-multiples">
										<input type="hidden" name="entregarMultiples" value="1" />
										<input type="hidden" name="ids" id="ids-recibir" />
									</form>

								    <button type="submit" id="btn-recibir-multiples-mini" class="btn btn-primary btn-md py-2 mb-5 font-weight-bolder" style="display: none;" data-toggle="modal" data-target="#recibir-multiples"><i class="fa fa-box"></i> Entregar Múltiples Requisiciones</button>
								</div>

								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									      	<th style="text-align: center;">Entregar</th>
									    	<th style="text-align: center;">Solicitado Por</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Producto</th>
									    	<th style="text-align: center;">Cantidad</th>
									    	<th style="text-align: center;">Orden de Compra</th>
									    	<th style="text-align: center;">Fecha de Creación</th>
									    	<th style="text-align: center;">Fecha Procesada</th>
									    	<th style="text-align: center;">Dias de Entrega</th>
									    	<th style="text-align: center;">Fecha de Vencimiento</th>
									    	<th style="text-align: center;">Dias Restantes</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['procesando'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id_requisicion']; ?>" class="navi-link"><?php echo $dato['id_requisicion']; ?></a></td>
											<td style="text-align: center;">
												<input type="checkbox" class="form-control checkbox-recibir" style="width: 20px; height: 20px;" name="checkboxIds" value="<?php echo $dato['id']; ?>">
											</td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['oc']; ?></td>
											<td data-sort="<?php echo $dato['fechaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td data-sort="<?php echo $dato['fechaProcesaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_procesa']; ?></td>
											<td style="text-align: center;"><?php echo $dato['dias_entrega']; ?></td>
											<td data-sort="<?php echo $dato['fechaVencimientoTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_vencimiento']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_vencidos']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
														    <?php
														    // Jefe directo
															if ($_SESSION['login_tipo'] == 1) {
															?>
															<li class="navi-item">
																<button class="dropdown-item btn-recibir navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#recibir" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-box"></i>
																	</span>
																	<span class="navi-text">Entregar</span>
																</button>
															</li>
															<?php
														    }
															?>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/editar_procesar/<?php echo $dato['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>
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

							<!-- Entregadas -->
							<div class="tab-pane" id="entregadas" role="tabpanel">

								<div class="text-right">
									<form method="post" action="" id="form-recibir2-multiples">
										<input type="hidden" name="recibirMultiples" value="1" />
										<input type="hidden" name="ids" id="ids-recibir2" />
									</form>

								    <button type="submit" id="btn-recibir2-multiples-mini" class="btn btn-primary btn-md py-2 mb-5 font-weight-bolder" style="display: none;" data-toggle="modal" data-target="#recibir2-multiples"><i class="fa fa-box"></i> Recibir Múltiples Requisiciones</button>
								</div>

								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									      	<th style="text-align: center;">Recibir</th>
									    	<th style="text-align: center;">Solicitado Por</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Producto</th>
									    	<th style="text-align: center;">Cantidad</th>
									    	<th style="text-align: center;">Orden de Compra</th>
									    	<th style="text-align: center;">Fecha de Creación</th>
									    	<th style="text-align: center;">Fecha Procesada</th>
									    	<th style="text-align: center;">Dias de Entrega</th>
									    	<th style="text-align: center;">Fecha de Vencimiento</th>
									    	<th style="text-align: center;">Dias Restantes</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['entregadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id_requisicion']; ?>" class="navi-link"><?php echo $dato['id_requisicion']; ?></a></td>
											<td style="text-align: center;">
												<input type="checkbox" class="form-control checkbox-recibir2" style="width: 20px; height: 20px;" name="checkboxIds" value="<?php echo $dato['id']; ?>">
											</td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['oc']; ?></td>
											<td data-sort="<?php echo $dato['fechaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td data-sort="<?php echo $dato['fechaProcesaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_procesa']; ?></td>
											<td style="text-align: center;"><?php echo $dato['dias_entrega']; ?></td>
											<td data-sort="<?php echo $dato['fechaVencimientoTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_vencimiento']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_vencidos']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<button class="dropdown-item btn-recibir2 navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#recibir2" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-box"></i>
																	</span>
																	<span class="navi-text">Recibir</span>
																</button>
															</li>
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

							<!-- Recibidas -->
							<div class="tab-pane" id="recibidas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									    	<th style="text-align: center;">Solicita</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Producto</th>
									    	<th style="text-align: center;">Tipo</th>
									    	<th style="text-align: center;">Cantidad</th>
									    	<th style="text-align: center;">UM</th>
									    	<th style="text-align: center;">Orden de Compra</th>
									    	<th style="text-align: center;">Recibido Por</th>
									    	<th style="text-align: center;">Fecha de Recibo</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['recibidas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar_recibida/<?php echo $dato['id_requisicion']; ?>" class="navi-link"><?php echo $dato['id_requisicion']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['tipo']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['um']; ?></td>
											<td style="text-align: center;"><?php echo $dato['oc']; ?></td>
											<td style="text-align: center;"><?php echo $dato['recibe']; ?></td>
											<td data-sort="<?php echo $dato['fechaReciboTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_recibo']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar_recibida/<?php echo $dato['id']; ?>" class="navi-link">
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

							<!-- Atrasadas -->
							<div class="tab-pane" id="atrasadas" role="tabpanel">

								<div class="text-right">
									<form method="post" action="" id="form-recibir-atrasadas">
										<input type="hidden" name="entregarMultiples" value="1" />
										<input type="hidden" name="ids" id="ids-recibir-atrasadas" />
									</form>

								    <button type="submit" id="btn-recibir-atrasadas-mini" class="btn btn-primary btn-md py-2 mb-5 font-weight-bolder" style="display: none;" data-toggle="modal" data-target="#recibir-atrasadas"><i class="fa fa-box"></i> Entregar Múltiples Requisiciones</button>
								</div>

								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">Folio</th>
									      	<th style="text-align: center;">Entregar</th>
									    	<th style="text-align: center;">Solicitado Por</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Producto</th>
									    	<th style="text-align: center;">Cantidad</th>
									    	<th style="text-align: center;">Orden de Compra</th>
									    	<th style="text-align: center;">Fecha de Creación</th>
									    	<th style="text-align: center;">Fecha Procesada</th>
									    	<th style="text-align: center;">Dias de Entrega</th>
									    	<th style="text-align: center;">Fecha de Vencimiento</th>
									    	<th style="text-align: center;">Dias Restantes</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['atrasadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id_requisicion']; ?>" class="navi-link"><?php echo $dato['id_requisicion']; ?></a></td>
											<td style="text-align: center;">
												<input type="checkbox" class="form-control checkbox-recibir-atrasadas" style="width: 20px; height: 20px;" name="checkboxIds" value="<?php echo $dato['id']; ?>">
											</td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['oc']; ?></td>
											<td data-sort="<?php echo $dato['fechaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td data-sort="<?php echo $dato['fechaProcesaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_procesa']; ?></td>
											<td style="text-align: center;"><?php echo $dato['dias_entrega']; ?></td>
											<td data-sort="<?php echo $dato['fechaVencimientoTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_vencimiento']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_vencidos']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<button class="dropdown-item btn-recibir navi-link" data-id="<?php echo $dato['id']; ?>" data-toggle="modal" data-target="#recibir" style="background: transparent; border: none;">
																	<span class="navi-icon">
																		<i class="las la-box"></i>
																	</span>
																	<span class="navi-text">Entregar</span>
																</button>
															</li>
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

							<!-- Canceladas -->
							<div class="tab-pane" id="canceladas" role="tabpanel">
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
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['canceladas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar_parte/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id_requisicion']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td style="text-align: center;"><?php echo $dato['producto']; ?></td>
											<td style="text-align: center;"><?php echo $dato['cantidad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['um']; ?></td>
											<td data-sort="<?php echo $dato['fechaTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar_parte/<?php echo $dato['id']; ?>" class="navi-link">
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
									      	<th style="text-align: center;">Folio</th>
									    	<th style="text-align: center;">Solicitado Por</th>
									    	<th style="text-align: center;">Departamento</th>
									    	<th style="text-align: center;">Fecha de Rechazo</th>
									    	<th style="text-align: center;">Rechazada Por</th>
									    	<th style="text-align: center;">Tipo</th>
									    	<th style="text-align: center;">Motivo</th>
									    	<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['rechazadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['solicita']; ?></td>
											<td style="text-align: center;"><?php echo $dato['departamento']; ?></td>
											<td data-sort="<?php echo $dato['fechaRechazoTimeStamp']; ?>" style="text-align: center;"><?php echo $dato['fecha_rechazo']; ?></td>
											<td style="text-align: center;"><?php echo $dato['rechaza']; ?></td>
											<td style="text-align: center;"><?php echo $dato['tipoRechazo']; ?></td>
											<td style="text-align: center;"><?php echo $dato['motivo_rechazo']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/compras/visualizar/<?php echo $dato['id']; ?>" class="navi-link">
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
			<div class="modal fade" id="cancelar-requisicion" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de cancelar esta requisición?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <a href="#" type="button" class="btn btn-primary" id="btn-cancelar"><i class="fa fa-check"></i> Si, aceptar</a>
			            </div>
			        </div>
			    </div>
			</div>

			<!-- Modal-->
			<div class="modal fade" id="autorizar-requisicion" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de autorizar esta requisición?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <a href="#" type="button" class="btn btn-primary" id="btn-autorizar"><i class="fa fa-check"></i> Si, aceptar</a>
			            </div>
			        </div>
			    </div>
			</div>

			<!-- Modal-->
			<div class="modal fade" id="cancelar-parte" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de cancelar el producto/servicio de esta requisición?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <a href="#" type="button" class="btn btn-primary" id="btn-cancelar-parte"><i class="fa fa-check"></i> Si, aceptar</a>
			            </div>
			        </div>
			    </div>
			</div>

			<!-- Modal-->
			<div class="modal fade" id="recibir" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de entregar el producto/servicio de esta requisición?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <a href="#" type="button" class="btn btn-primary" id="btn-recibir-final"><i class="fa fa-check"></i> Si, aceptar</a>
			            </div>
			        </div>
			    </div>
			</div>

			<!-- Modal-->
			<div class="modal fade" id="recibir-multiples" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de entregar los múltiples productos/servicios seleccionados?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <button type="button" class="btn btn-primary" id="btn-recibir-multiples"><i class="fa fa-check"></i> Si, aceptar</button>
			            </div>
			        </div>
			    </div>
			</div>

			<!-- Modal-->
			<div class="modal fade" id="recibir-atrasadas" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de entregar los múltiples productos/servicios seleccionados?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <button type="button" class="btn btn-primary" id="btn-recibir-atrasadas"><i class="fa fa-check"></i> Si, aceptar</button>
			            </div>
			        </div>
			    </div>
			</div>

			<!-- Recibir -->
			<div class="modal fade" id="recibir2" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de recibir el producto/servicio de esta requisición?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <a href="#" type="button" class="btn btn-primary" id="btn-recibir2-final"><i class="fa fa-check"></i> Si, aceptar</a>
			            </div>
			        </div>
			    </div>
			</div>
			<div class="modal fade" id="recibir2-multiples" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		        <div class="modal-dialog modal-dialog-centered" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h5 class="modal-title">Confirmación</h5>
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                    <i aria-hidden="true" class="ki ki-close"></i>
			                </button>
			            </div>
			            <div class="modal-body">
			                ¿Estás seguro de recibir los múltiples productos/servicios seleccionados?
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
			                <button type="button" class="btn btn-primary" id="btn-recibir2-multiples"><i class="fa fa-check"></i> Si, aceptar</button>
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