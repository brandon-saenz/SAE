<?php
require_once(APP . '/vistas/inc/encabezado.php');
?>

<?php
// Nueva
if (isset($nueva)) {
?>
<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<form class="form" method="post" action="" enctype="multipart/form-data" id="form-nueva-solicitud">
				<div class="card-body">
					<div class="row">

						<div class="col-md-6">
							<div class="form-group row">
								<div class="col-md-4">
									<label>No. Solicitud:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['folio']; ?>" />
								</div>
								<div class="col-md-8">
									<label>Nombre del Propietario:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_nombre'] . ' ' . $_SESSION['login_apellidos']; ?>" />
								</div>
								
							</div>

							<div class="form-group row">
								<div class="col-lg-4">
									<label>* Tipo de Solicitud:</label>
									<select name="tipo" id="solicitud-tipo" class="form-control" required>
										<option value="">Selecciona tipo...</option>
										<option value="A">ATENCIÓN</option>
										<option value="S">SERVICIO</option>
									</select>
								</div>
								<div class="col-lg-8">
									<label>* Servicio:</label>
									<select name="id_servicio" id="solicitud-servicios" class="form-control" required>
										<option value="">Selecciona servicio...</option>
										<?php echo $listadoServicios; ?>
									</select>
								</div>
							</div>

							<div class="form-group row" id="contenedor-otro" style="display: none;">
								<div class="col-lg-4"></div>
								<div class="col-lg-8">
									<label>* Especifica Servicio Requerido:</label>
									<input type="text" class="form-control" id="input-otro" name="otro" value="<?php echo $datos['otro']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-6">
									<label>Adjuntar Archivo:</label>
									<input type="file" name="archivo[]" multiple="1">
									<span class="form-text text-muted">(Subir uno o más archivos)</span>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group row">
								<div class="col-md-4">
									<label>No. Lote:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_lote']; ?>" />
								</div>
								<div class="col-md-4">
									<label>Fecha:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo date('d/m/Y'); ?>" />
								</div>
								<div class="col-md-4">
									<label>Hora:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo date('H:i'); ?> hrs" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-md-8">
									<label>Email:</label>
									<input type="text" class="form-control" name="email" value="<?php echo $_SESSION['login_email']; ?>" />
								</div>
								<div class="col-md-4">
									<label>Teléfono:</label>
									<input type="text" class="form-control" name="telefono" value="<?php echo $_SESSION['login_telefono1']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>* Descripción Detallada y Observaciones del Servicio:</label>
									<textarea name="descripcion" class="form-control" rows="10" required></textarea>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="card-footer">
					<input type="hidden" value="1" name="generar" />
					<a href="<?php echo STASIS; ?>" class="btn btn-secondary">Regresar</a>
					<button type="submit" id="btn-enviar-solicitud" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-check"></i> Enviar Solicitud</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
// Verificacion
} elseif (isset($verificacion)) {
?>
<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<form class="form" method="post" action="">
				<div class="card-body">
					<div class="alert alert-primary mb-5 p-5" role="alert">
					    <h4 class="alert-heading">Estimado Propietario</h4>
					    <p class="m-0">Antes de poder realizar <b><u>su primer solicitud</u></b> a través de nuestra plataforma necesitamos corroborar sus datos de contacto.<br />En caso de que sean incorrectos le pedimos de favor que los actualice, ya que será la forma en la que nos comunicaremos con usted.</p>
					</div>

					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Nombre de Propietario</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" value="<?php echo $_SESSION['login_nombre']; ?>" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Lote</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" value="<?php echo $_SESSION['login_lote']; ?>" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">* E-Mail</label>
						<div class="col-6">
							<input class="form-control minusculas" type="email" required name="email" value="<?php echo $_SESSION['login_email']; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">* Teléfono 1</label>
						<div class="col-6">
							<input class="form-control" type="text" required name="telefono1" value="<?php echo $_SESSION['login_telefono1']; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">* Teléfono 2</label>
						<div class="col-6">
							<input class="form-control" type="text" required name="telefono2" value="<?php echo $_SESSION['login_telefono2']; ?>">
						</div>
					</div>
				</div>

				<div class="card-footer">
					<input type="hidden" value="<?php echo $datos['id']; ?>" name="id" />
					<input type="hidden" value="1" name="actualizarDatos" />
					<a href="<?php echo STASIS; ?>" class="btn btn-secondary">Regresar</a>
					<button type="submit" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-check"></i> Confirmar Datos de Contacto</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
// Comentario
} elseif (isset($comentario)) {
?>
<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<form class="form" method="post" action="" enctype="multipart/form-data">
				<div class="card-body">
					<div class="row">

						<div class="col-md-6">
							<div class="form-group row">
								<div class="col-md-4">
									<label>No. Solicitud:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['no_solicitud']; ?>" />
								</div>
								<div class="col-md-8">
									<label>Nombre del Propietario:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_nombre'] . ' ' . $_SESSION['login_apellidos']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-4">
									<label>Tipo de Solicitud:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['tipo']; ?>" />
								</div>
								<div class="col-lg-8">
									<label>Servicio:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['servicio']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>* Comentario Adicional:</label>
									<textarea name="comentario" class="form-control" rows="4" required></textarea>
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-6">
									<label>Adjuntar Archivo:</label>
									<input type="file" name="archivo">
									<span class="form-text text-muted">(Subir un solo archivo)</span>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group row">
								<div class="col-md-4">
									<label>No. Lote:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_lote']; ?>" />
								</div>
								<div class="col-md-4">
									<label>Fecha:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['fecha_creacion']; ?>" />
								</div>
								<div class="col-md-4">
									<label>Hora:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['hora_creacion']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-md-8">
									<label>Email:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_email']; ?>" />
								</div>
								<div class="col-md-4">
									<label>Teléfono:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_telefono1']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>Descripción Detallada y Observaciones del Servicio:</label>
									<textarea name="descripcion" class="form-control form-disabled" rows="10" disabled><?php echo $datos['descripcion']; ?></textarea>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="card-footer">
					<input type="hidden" value="<?php echo $datos['id']; ?>" name="id" />
					<input type="hidden" value="1" name="generarComentario" />
					<a href="<?php echo STASIS; ?>" class="btn btn-secondary">Regresar</a>
					<button type="submit" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-check"></i> Enviar Comentario</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
// Cancelar
} elseif (isset($cancelar)) {
?>
<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<form class="form" method="post" action="" enctype="multipart/form-data">
				<div class="card-body">
					<div class="row">

						<div class="col-md-6">
							<div class="form-group row">
								<div class="col-md-4">
									<label>No. Solicitud:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['no_solicitud']; ?>" />
								</div>
								<div class="col-md-8">
									<label>Nombre del Propietario:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_nombre'] . ' ' . $_SESSION['login_apellidos']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-4">
									<label>Tipo de Solicitud:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['tipo']; ?>" />
								</div>
								<div class="col-lg-8">
									<label>Servicio:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['servicio']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>* Motivo de Cancelación:</label>
									<textarea name="motivo_cancelacion" class="form-control" rows="4" required></textarea>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group row">
								<div class="col-md-4">
									<label>No. Lote:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $_SESSION['login_lote']; ?>" />
								</div>
								<div class="col-md-4">
									<label>Fecha:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['fecha_creacion']; ?>" />
								</div>
								<div class="col-md-4">
									<label>Hora:</label>
									<input type="text" class="form-control form-disabled" disabled value="<?php echo $datos['hora_creacion']; ?>" />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-lg-12">
									<label>Descripción Detallada y Observaciones del Servicio:</label>
									<textarea name="descripcion" class="form-control form-disabled" rows="10" disabled><?php echo $datos['descripcion']; ?></textarea>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="card-footer">
					<input type="hidden" value="<?php echo $datos['id']; ?>" name="id" />
					<input type="hidden" value="1" name="generarCancelacion" />
					<a href="<?php echo STASIS; ?>" class="btn btn-secondary">Regresar</a>
					<button type="submit" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-check"></i> Cancelar Solicitud</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
// Evaluar
} elseif (isset($evaluar)) {
?>
<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<form class="form" method="post" action="">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Nombre de Propietario</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" value="<?php echo $_SESSION['login_nombre']; ?>" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">Lote</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" value="<?php echo $_SESSION['login_lote']; ?>" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">* ¿Cómo evalúa nuestro servicio y nivel de atención?</label>
						<div class="col-6">
							<select id="evaluacion-tipo" class="form-control" required name="calificacion">
								<option value="">Selecciona calificación...</option>
								<option value="1">PÉSIMO</option>
								<option value="2">DEFICIENTE</option>
								<option value="3">REGULAR</option>
								<option value="4">BUENO</option>
								<option value="5">EXCELENTE</option>
							</select>
						</div>
					</div>

					<div class="form-group row evaluacion-preguntas" style="display: none;">
						<label for="example-search-input" class="col-2 col-form-label">Considera que el proceso de solicitud es</label>
						<div class="col-6">
							<select class="form-control" name="p1">
								<option value="">Selecciona...</option>
								<option value="1">PÉSIMO</option>
								<option value="2">DEFICIENTE</option>
								<option value="3">REGULAR</option>
								<option value="4">BUENO</option>
								<option value="5">EXCELENTE</option>
							</select>
						</div>
					</div>
					<div class="form-group row evaluacion-preguntas" style="display: none;">
						<label for="example-search-input" class="col-2 col-form-label">La atención del asesor fue</label>
						<div class="col-6">
							<select class="form-control" name="p2">
								<option value="">Selecciona...</option>
								<option value="1">PÉSIMO</option>
								<option value="2">DEFICIENTE</option>
								<option value="3">REGULAR</option>
								<option value="4">BUENO</option>
								<option value="5">EXCELENTE</option>
							</select>
						</div>
					</div>
					<div class="form-group row evaluacion-preguntas" style="display: none;">
						<label for="example-search-input" class="col-2 col-form-label">Atendieron sus solicitud en el tiempo acordado</label>
						<div class="col-6">
							<select class="form-control" name="p3">
								<option value="">Selecciona...</option>
								<option value="0">NO</option>
								<option value="1">SI</option>
							</select>
						</div>
					</div>
					<div class="form-group row evaluacion-preguntas" style="display: none;">
						<label for="example-search-input" class="col-2 col-form-label">Recibió la notificación por correo o WhatsApp de la aprobación de su solicitud</label>
						<div class="col-6">
							<select class="form-control" name="p4">
								<option value="">Selecciona...</option>
								<option value="0">NO</option>
								<option value="1">SI</option>
							</select>
						</div>
					</div>
					<div class="form-group row evaluacion-preguntas" style="display: none;">
						<label for="example-search-input" class="col-2 col-form-label">Como describe la retroalimentación del asesor</label>
						<div class="col-6">
							<select class="form-control" name="p5">
								<option value="">Selecciona...</option>
								<option value="1">PÉSIMO</option>
								<option value="2">DEFICIENTE</option>
								<option value="3">REGULAR</option>
								<option value="4">BUENO</option>
								<option value="5">EXCELENTE</option>
							</select>
						</div>
					</div>
					<div class="form-group row evaluacion-preguntas" style="display: none;">
						<label for="example-search-input" class="col-2 col-form-label">Comentarios adicionales</label>
						<div class="col-6">
							<textarea class="form-control" rows="4" name="comentarios"></textarea>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<input type="hidden" value="<?php echo $id; ?>" name="id" />
					<input type="hidden" value="1" name="generarEvaluacion" />
					<a href="<?php echo STASIS; ?>" class="btn btn-secondary">Regresar</a>
					<button type="submit" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-check"></i> Enviar Evaluación</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
// Enviada
} elseif (isset($enviada)) {
?>

<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-body text-center">
				<div class="container px-40">
					<h1 style="font-weight: bold;" class="pt-10 pb-10">¡SU SOLICITUD HA SIDO ENVIADA CORRECTAMENTE!</h1>
					<h4>Gracias por enviar su solicitud, dentro de un periodo de 24 hrs se estará procesando su solicitud y se le asignará un miembro responsable quien le dará seguimiento a su proceso hasta concluir y con quien usted podrá comunicarse.</h4>
					<img src="<?php echo STASIS; ?>/img/guirnalda.png" width="80" class="pt-2 pb-10" />
				</div>
			</div>
			<div class="card-footer text-center">
				<a href="<?php echo STASIS; ?>/movimientos/solicitudes/nueva" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-edit"></i> Nueva Solicitud</a>
				<a href="<?php echo STASIS; ?>/movimientos/solicitudes/s/enviadas" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-chart-line"></i> Estatus de Solicitudes</a>
			</div>
		</div>
	</div>
</div>

<?php
// Verificado
} elseif (isset($verificado)) {
?>

<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-body text-center">
				<div class="container pt-5 pb-1">
					<h4>Gracias por confirmar sus datos de contacto.<br />Ya puede crear su primer solicitud en nuestra plataforma.</h4>
				</div>
			</div>
			<div class="card-footer text-center">
				<a href="<?php echo STASIS; ?>/movimientos/solicitudes/nueva" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-edit"></i> Nueva Solicitud</a>
			</div>
		</div>
	</div>
</div>

<?php
// Verificado
} elseif (isset($evenviado)) {
?>

<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-body text-center">
				<div class="container px-40">
					<h1 style="font-weight: bold;" class="pt-10 pb-10">SU EVALUACIÓN HA SIDO ENVIADA CORRECTAMENTE</h1>
					<h4>Gracias por haber realizado la encuesta derivada del seguimiento de su evaluación cualquier duda o comentario adicional nos puede contactar a través de la sección de <a href="<?php echo STASIS; ?>/movimientos/qys/nueva">Quejas y Sugerencias</a>.</h4>
					<img src="<?php echo STASIS; ?>/img/guirnalda.png" width="80" class="pt-2 pb-10" />
				</div>
			</div>
			<div class="card-footer text-center">
				<a href="<?php echo STASIS; ?>/" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-reply"></i> Regresar</a>
			</div>
		</div>
	</div>
</div>

<?php
// Comentario Enviado
} elseif (isset($cenviado)) {
?>

<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-body text-center">
				<div class="container px-40">
					<h1 style="font-weight: bold;" class="pt-10 pb-10">SU COMENTARIO HA SIDO REGISTRADO CORRECTAMENTE</h1>
					<h4>Gracias por enviar sus comentarios u observaciones adicionales, nuestro departamento correspondiente lo revisará y dará seguimiento.</h4>
					<img src="<?php echo STASIS; ?>/img/guirnalda.png" width="80" class="pt-2 pb-10" />
				</div>
			</div>
			<div class="card-footer text-center">
				<a href="<?php echo STASIS; ?>/movimientos/solicitudes/nueva" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-edit"></i> Nueva Solicitud</a>
				<a href="<?php echo STASIS; ?>/movimientos/solicitudes/s/enviadas" class="btn btn-primary mr-2" style="background: #83AB29; border: 1px #83AB29 solid;"><i class="fa fa-chart-line"></i> Estatus de Solicitudes</a>
			</div>
		</div>
	</div>
</div>

<?php
// Listado
} else {
?>

<div class="row mb-8">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-body">

				<div class="row">
					<div class="col-md-9">
						<div class="mb-7">
							<div class="row">
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

						<table class="table table-sm table-bordered table-striped kt_datatable-0">
							<thead>
								<tr>
									<th style="text-align: center;">No. Solicitud</th>
									<th style="text-align: center;">Propietario</th>
									<th style="text-align: center;">No. Lote</th>
									<th style="text-align: center;">Servicio</th>
									<th style="text-align: center;">Fecha</th>
									<th style="text-align: center;">Hora</th>
									<th style="text-align: center;">Responsable</th>
									<th style="text-align: center;">Estatus</th>
								</tr>
							</thead>
							<tbody>
								
								<?php
								$x = 1;
								foreach ($listado as $datos) {
								?>
								<tr>
									<td data-sort="<?php echo $datos['id']; ?>" style="text-align: center;"><a href="javascript:;" class="id-solicitud" data-id="<?php echo $datos['id']; ?>" style="color: #83AB29;"><?php echo $datos['no_solicitud']; ?></a></td>
									<td style="text-align: center;"><?php echo $_SESSION['login_nombre'] . ' ' . $_SESSION['login_apellidos']; ?></td>
									<td style="text-align: center; white-space: nowrap;"><?php echo $_SESSION['login_lote']; ?></td>
									<td style="text-align: center;"><?php echo $datos['servicio']; ?></td>
									<td style="text-align: center;"><?php echo $datos['fecha_creacion']; ?></td>
									<td style="text-align: center;"><?php echo $datos['hora_creacion']; ?></td>
									<td style="text-align: center;"><?php echo $datos['responsable']; ?></td>
									<td style="text-align: center;"><span class="label label-dot <?php echo $datos['label']; ?>" style="width: 15px; height: 15px;"></span></td>
								</tr>
								<?php
								$x++;
								}
								?>
							</tbody>
						</table>

						<div class="row mt-10" id="info-comentarios"></div>
					</div>

					<div class="col-md-3 pt-19">
						<div class="row" id="info-solicitud"></div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
}

require_once(APP . '/vistas/inc/pie_pagina.php');
?>