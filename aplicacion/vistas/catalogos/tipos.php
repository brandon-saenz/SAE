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
					<span class="card-label font-weight-bolder text-dark">Información del Tipo de Gasto</span>
				</h3>
			</div>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Nombre</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="nombre" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Cuenta Contable</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="cuenta_contable" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Año:</label>
						<div class="col-3">
							<input class="form-control mayusculas" type="text" name="nombre">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Presupuesto Anual:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Enero:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Febrero:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Marzo:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Abril:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Mayo:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Junio:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Julio:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Agosto:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Septiembre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Octubre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Noviembre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Diciembre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Tipo de Gasto</button>
							<a href="<?php echo STASIS; ?>/catalogos/tipos" class="btn btn-secondary">Regresar</a>
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
					<span class="card-label font-weight-bolder text-dark">Información del Tipo de Gasto</span>
				</h3>
			</div>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Nombre</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="nombre" value="<?php echo $info->nombre; ?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Cuenta Contable</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="cuenta_contable" value="<?php echo $info->cuenta_contable; ?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Año:</label>
						<div class="col-3">
							<input class="form-control mayusculas" type="text" name="ano" value="<?php echo $info->ano; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Tipo de Cambio:</label>
						<div class="col-3">
							<input class="form-control mayusculas" type="text" id="tc" value="<?php echo $info->tc; ?>" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Presupuesto Anual:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2dec" type="text" disabled id="presupuesto-mxn">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2dec" type="text" disabled id="presupuesto-usd">
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Enero:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="ene" name="ene" value="<?php echo $info->ene; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="ene-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Febrero:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="feb" name="feb" value="<?php echo $info->feb; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="feb-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Marzo:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="mar" name="mar" value="<?php echo $info->mar; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="mar-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Abril:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="abr" name="abr" value="<?php echo $info->abr; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="abr-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Mayo:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="may" name="may" value="<?php echo $info->may; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="may-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Junio:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="jun" name="jun" value="<?php echo $info->jun; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="jun-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Julio:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="jul" name="jul" value="<?php echo $info->jul; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="jul-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Agosto:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="ago" name="ago" value="<?php echo $info->ago; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="ago-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Septiembre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="sep" name="sep" value="<?php echo $info->sep; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="sep-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Octubre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="oct" name="oct" value="<?php echo $info->oct; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="oct-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Noviembre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="nov" name="nov" value="<?php echo $info->nov; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="nov-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">* Presupuesto Diciembre:</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2 presupuesto-importe" type="text" id="dic" name="dic" value="<?php echo $info->dic; ?>">
								<div class="input-group-append"><span class="input-group-text">MXN</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text">$</span></div>
								<input class="form-control money2" type="text" id="dic-usd" disabled>
								<div class="input-group-append"><span class="input-group-text">USD</span></div>
							</div>
						</div>
						<div class="col-lg-3">
							<input class="form-control mayusculas" type="text" placeholder="Justificación del presupuesto">
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
							<a href="<?php echo STASIS; ?>/catalogos/tipos" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Listado de Tipo de Gastos
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
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/tipos/nuevo"><i class="fa fa-plus"></i> Nuevo Tipo de Gasto</a>
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
											<th>Cuenta Contable</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['cuenta_contable']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/tipos/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/tipos/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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
											<th>Cuenta Contable</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['cuenta_contable']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/tipos/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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