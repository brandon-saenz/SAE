<?php
require_once(APP . '/vistas/inc/encabezado.php');
?>

<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">

			<form class="form">
				<div class="card-body">
					<div class="form-group row">
						<div class="col-md-2">
							<label>No. Solicitud:</label>
							<input type="text" class="form-control form-disabled" disabled value="00001" />
						</div>
						<div class="col-md-4">
							<label>Nombre del Propietario:</label>
							<input type="text" class="form-control form-disabled" disabled value="Marlon Jair Anguiano García" />
						</div>
						<div class="col-md-2">
							<label>No. Lote:</label>
							<input type="text" class="form-control form-disabled" disabled value="SV-2907" />
						</div>
						<div class="col-md-2">
							<label>Fecha:</label>
							<input type="text" class="form-control form-disabled" disabled value="<?php echo date('d/m/Y'); ?>" />
						</div>
						<div class="col-md-2">
							<label>Hora:</label>
							<input type="text" class="form-control form-disabled" disabled value="<?php echo date('H:i'); ?> hrs" />
						</div>
					</div>
					<div class="form-group row">
						<div class="col-lg-2">
							<label>Adjuntar Archivo:</label>
							<input type="text" class="form-control form-disabled" disabled />
							<span class="form-text text-muted">(Subir uno o más archivos)</span>
							<div class="text-right mt-2">
								<button type="button" class="btn btn-primary" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-upload"></i> Subir archivo(s)</button>
							</div>
						</div>
						<div class="col-lg-10">
							<label>Por favor, escríbenos tus comentarios:</label>
							<textarea class="form-control" rows="10"></textarea>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<a href="<?php echo STASIS; ?>" class="btn btn-secondary">Regresar</a>
					<a href="#" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-check"></i> Enviar Comentarios</a>
				</div>
			</form>
			
		</div>
	</div>
</div>

<?php
require_once(APP . '/vistas/inc/pie_pagina.php');
?>