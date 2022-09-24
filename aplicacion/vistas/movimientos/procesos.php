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

<?php
// Dashboard
if (isset($dashboard)) {
?>

<div class="row" id="dashboard-procesos">
	<div class="col-xl-3">
		<div class="card card-custom card-stretch gutter-b">
			<div class="card-body">
				<i class="fa fa-file-signature fa-2x text-primary"></i>
				<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block"><?php echo $datos['interaccionesGeneradas']; ?></span>
				<span class="font-weight-bold text-muted font-size-sm">Total de Interacciones Generadas</span>
			</div>
		</div>
	</div>
	<div class="col-xl-2">
		<div class="card card-custom bg-success card-stretch gutter-b">
			<div class="card-body">
				<i class="fa fa-clock fa-2x text-white"></i>
				<span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?php echo $datos['interaccionesPendientes']; ?></span>
				<span class="font-weight-bold text-white font-size-sm">Interacciones Pendientes</span>
			</div>
		</div> 
	</div>
	<div class="col-xl-2">
		<div class="card card-custom bg-primary card-stretch gutter-b">
			<div class="card-body">
				<i class="fa fa-check-double fa-2x text-white"></i>
				<span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?php echo $datos['interaccionesCompletadas']; ?></span>
				<span class="font-weight-bold text-white font-size-sm">Interacciones Completadas</span>
			</div>
		</div> 
	</div>
	<div class="col-xl-2">
		<div class="card card-custom bg-warning card-stretch gutter-b">
			<div class="card-body">
				<i class="fa fa-sms fa-2x text-white"></i>
				<span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?php echo $datos['saldoSms']; ?></span>
				<span class="font-weight-bold text-white font-size-sm">Saldo SMS</span>
			</div>
		</div>
	</div>
	<div class="col-xl-3">
		<div class="card card-custom bg-dark card-stretch gutter-b">
			<div class="card-body">
				<i class="fa fa-hourglass fa-2x text-white"></i>
				<span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?php echo $datos['horasPromedio']; ?></span>
				<span class="font-weight-bold text-white font-size-sm">Tiempo Promedio por Interacción</span>
			</div>
		</div> 
	</div>

	<div class="col-lg-12">
		<div class="card card-custom gutter-b">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Top Interacciones Generadas por Departamento</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts"></div>
		    </div>
		</div>
	</div>

	<div class="col-lg-12">
		<div class="card card-custom gutter-b">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Top Interacciones Asignadas por Departamento</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts7"></div>
		    </div>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-3">
		<div class="card card-custom gutter-b" style="height: 300px;">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Porcentaje por Prioridades</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts6"></div>
		    </div>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-3">
		<div class="card card-custom gutter-b" style="height: 300px;">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Porcentaje por Origen</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts-origen"></div>
		    </div>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-3">
		<div class="card card-custom gutter-b" style="height: 300px;">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Cumplidas Vs No Cumplidas</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts4"></div>
		    </div>
		</div>
	</div>


	<div class="col-sm-12 col-md-12 col-lg-3">
		<div class="card card-custom gutter-b" style="height: 300px;">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Cumplidas en Fecha Requerida</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts5"></div>
		    </div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card card-custom gutter-b">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Total de Interacciones 2022</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts2"></div>
		    </div>
		</div>
	</div>

	<div class="col-lg-8">
		<div class="card card-custom gutter-b">
			<div class="card-header h-auto">
				<div class="card-title py-5">
					<h3 class="card-label">Interacciones Generadas por Departamento</h3>
				</div>
			</div>
			<div class="card-body">
				<div id="charts3"></div>
		    </div>
		</div>
	</div>

	<div class="col-md-12 col-lg-4">
		<div class="card card-custom card-stretch gutter-b">
			<div class="card-header border-0">
				<h3 class="card-title font-weight-bolder text-dark">Usuarios con Más Interacciones</h3>
			</div>
			
			<div class="card-body pt-0">
				<?php
				foreach ($datos['usuariosTopGeneradas'] as $info) {
				?>
				<div class="d-flex align-items-center flex-wrap mb-8">
					<div class="symbol symbol-50 symbol-light mr-5">
						<img src="<?php echo STASIS; ?>/<?php echo $info['foto']; ?>" class="h-100" alt="">
					</div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1"><?php echo $info['nombre']; ?></a>
						<span class="text-muted font-weight-bold"><?php echo $info['puesto']; ?></span>
					</div>
					<span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder"><?php echo $info['c']; ?></span>
				</div>
				<?php
				}
				?>
			</div>

		</div>
	</div>

	<div class="col-md-12 col-lg-4">
		<div class="card card-custom card-stretch gutter-b">
			<div class="card-header border-0">
				<h3 class="card-title font-weight-bolder text-dark">Alto % Efectividad de Seguimiento</h3>
			</div>
			<div class="card-body pt-0">
				<?php
				foreach ($datos['usuariosAltaEfectividadSort'] as $k => $v) {
				?>
				<div class="d-flex align-items-center flex-wrap mb-8">
					<div class="symbol symbol-50 symbol-light mr-5">
						<img src="<?php echo STASIS; ?>/<?php echo $datos['usuariosAltaEfectividad'][$k]['foto']; ?>" class="h-100" alt="">
					</div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1"><?php echo $datos['usuariosAltaEfectividad'][$k]['nombre']; ?></a>
						<span class="text-muted font-weight-bold"><?php echo $datos['usuariosAltaEfectividad'][$k]['puesto']; ?></span>
					</div>
					<span class="label label-xl label-primary label-inline my-lg-0 my-2 font-weight-bolder"><?php echo $datos['usuariosAltaEfectividad'][$k]['horasPromedio']; ?> horas</span>
				</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>

	<div class="col-md-12 col-lg-4">
		<div class="card card-custom card-stretch gutter-b">
			<div class="card-header border-0">
				<h3 class="card-title font-weight-bolder text-dark">Bajo % Efectividad de Seguimiento</h3>
			</div>
			<div class="card-body pt-0">
				<?php
				foreach ($datos['usuariosBajaEfectividadSort'] as $k => $v) {
					if ($datos['usuariosBajaEfectividad'][$k]['horasPromedio'] != 0) {
				?>
				<div class="d-flex align-items-center flex-wrap mb-8">
					<div class="symbol symbol-50 symbol-light mr-5">
						<img src="<?php echo STASIS; ?>/<?php echo $datos['usuariosBajaEfectividad'][$k]['foto']; ?>" class="h-100" alt="">
					</div>
					<div class="d-flex flex-column flex-grow-1 mr-2">
						<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1"><?php echo $datos['usuariosBajaEfectividad'][$k]['nombre']; ?></a>
						<span class="text-muted font-weight-bold"><?php echo $datos['usuariosBajaEfectividad'][$k]['puesto']; ?></span>
					</div>
					<span class="label label-xl label-danger label-inline my-lg-0 my-2 font-weight-bolder"><?php echo $datos['usuariosBajaEfectividad'][$k]['horasPromedio']; ?> horas</span>
				</div>
				<?php
				} }
				?>
			</div>
		</div>
	</div>


</div>

<?php
}
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b">
			<?php
			// Generar
			if (isset($generar)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud Interna</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['folio']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Remitente</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['solicitado_por']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Destinatario</label>
							<div class="col-6">
								<div class="input-group">
									<select class="form-control" name="id_destinatario" required>
										<!-- <option value="">Selecciona usuario...</option> -->
										<?php echo $listadoUsuariosGlobales; ?>
									</select>
									<div class="input-group-append">
										<button class="btn btn-primary cc-adjuntar" type="button"><i class="fa fa-plus-circle"></i> Adjuntar Usuario</button>
										<input type="hidden" value="0" id="cc-num" />
									</div>
								</div>
							</div>
						</div>

						<?php
						for ($x=1; $x<=10; $x++) {
						?>
						<div class="form-group row hidden" id="cc-<?php echo $x; ?>">
							<label class="col-2 col-form-label"></label>
							<div class="col-6">
								<div class="input-group">
									<select class="form-control" name="id_cc<?php echo $x; ?>">
										<!-- <option value="">Selecciona usuario...</option> -->
										<?php echo $listadoUsuariosGlobales; ?>
									</select>
								</div>
							</div>
						</div>
						<?php
						}
						?>

						<div class="form-group row">
							<label class="col-2 col-form-label">* Tema</label>
							<div class="col-6">
								<input class="form-control" type="text" name="titulo" value="<?php echo $datos['titulo']; ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Mensaje</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" name="mensaje" required><?php echo $datos['mensaje']; ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Origen</label>
							<div class="col-6">
								<div class="input-group">
									<select class="form-control" name="origen" required>
									<option value="">Selecciona origen...</option>
									<option value="1">QUEJA DE PROPIETARIO</option>
									<option value="2">QUEJA DE PROVEEDOR</option>
									<option value="3">QUEJA DE COLABORADOR</option>
									<option value="4">SOLICITUD INTERNA</option>
									<option value="5">ACCIÓN CORRECTIVA</option>
									<option value="6">ACTUALIZACIÓN DE DOCUMENTOS</option>
									<option value="7">OPORTUNIDAD DE MEJORA</option>
									<option value="8">PROCEDIMIENTOS</option>
									<option value="9">REUNIONES DE RESULTADOS</option>
									<option value="11">EXPEDIENTE COBRANZA-LEGAL</option>
									<option value="10">OTRO</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Prioridad</label>
							<div class="col-6">
								<select class="form-control" name="prioridad" required>
									<option value="">Selecciona prioridad...</option>
									<option value="1">BAJA</option>
									<option value="2">MEDIA</option>
									<option value="3">ALTA</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Fecha Requerida</label>
							<div class="col-6">
								<input class="form-control datepicker" name="fecha_requerida" type="text" value="<?php echo $datos['fecha_creacion']; ?>" style="width: 100%;" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Archivo</label>
							<div class="col-6">
								<input type="file" name="archivo">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="generarSolicitud" value="1">
								<button type="submit" class="btn btn-primary">Generar Solicitud Interna</button>
								<a href="<?php echo STASIS; ?>/" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Fecha
			} elseif (isset($fecha)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud Interna</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['id']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Remitente</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['remitente']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Destinatario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['destinatario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tema</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['titulo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Mensaje</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['mensaje']; ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Prioridad</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['prioridad']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>

					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i><?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Entrega</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Fecha Estimada de Entrega</label>
							<div class="col-4">
								<input class="form-control datepicker" type="text" name="fecha_entrega" required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="procesar" value="1">
								<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
								<a href="<?php echo STASIS; ?>/movimientos/procesos/asignadas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Finalizar
			} elseif (isset($finalizar)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud Interna</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['id']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Remitente</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['remitente']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Destinatario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['destinatario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tema</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['titulo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Mensaje</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['mensaje']; ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Prioridad</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['prioridad']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>

					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i><?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Comentario a Añadir</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Comentario Final</label>
							<div class="col-6">
								<textarea name="comentario" class="form-control" rows="6" required></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Archivo</label>
							<div class="col-6">
								<input type="file" name="archivo">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="finalizar" value="1">
								<button type="submit" class="btn btn-primary">Finalizar</button>
								<a href="<?php echo STASIS; ?>/movimientos/procesos/asignadas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Comentario
			} elseif (isset($comentario)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud Interna</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['id']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Remitente</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['remitente']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Destinatario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['destinatario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tema</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['titulo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Mensaje</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['mensaje']; ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Prioridad</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['prioridad']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>

					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i><?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Comentario a Añadir</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Comentario</label>
							<div class="col-6">
								<textarea name="comentario" class="form-control" rows="6" required></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Archivo</label>
							<div class="col-6">
								<input type="file" name="archivo">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="generarComentario" value="1">
								<button type="submit" class="btn btn-primary">Agregar Comentario</button>
								<a href="<?php echo STASIS; ?>/" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Cerrar
			} elseif (isset($cerrar)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información de Solicitud Interna</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">No. Folio</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['id']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Remitente</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['remitente']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Destinatario</label>
							<div class="col-6">
								<input class="form-control mayusculas" type="text" value="<?php echo $datos['destinatario']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Tema</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['titulo']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Mensaje</label>
							<div class="col-6">
								<textarea class="form-control" rows="10" disabled><?php echo $datos['mensaje']; ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Prioridad</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['prioridad']; ?>" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Fecha de Creación</label>
							<div class="col-6">
								<input class="form-control" type="text" value="<?php echo $datos['fecha_creacion']; ?>" disabled>
							</div>
						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Archivos Cargados</span>
						</h3>
					</div>

					<div class="card-body">
						<?php if (!empty($datos['archivos'])) { ?>
						<table class="table col-4">
							<?php foreach ($datos['archivos'] as $archivo) { ?>
							<tr>
								<td><a target="_blank" href="https://saevalcas.mx/data/privada/archivos/<?php echo $archivo; ?>"><i class="fa fa-download mr-2"></i><?php echo $archivo; ?></a></td>
							</tr>
							<?php } ?>
						</table>
						<?php } else { ?>
						<span class="text-muted">No hay archivos cargados para esta solicitud.</span>
						<?php } ?>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Conclusión Final</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Comentario Final</label>
							<div class="col-6">
								<textarea name="comentario" class="form-control" rows="6" required></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Archivo</label>
							<div class="col-6">
								<input type="file" name="archivo">
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
								<input type="hidden" name="cerrar" value="1">
								<button type="submit" class="btn btn-primary">Cerrar</button>
								<a href="<?php echo STASIS; ?>/movimientos/procesos/generadas" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Asignadas
			} elseif (isset($asignadas)) {
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
										<span class="nav-text">Pendientes <span class="label label-rounded label-success" style="width: 40px;"><?php echo $listado['nPendientes']; ?></span></span>
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
									<a class="nav-link" data-toggle="tab" href="#finalizadas">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Finalizadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nFinalizadas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#completadas">
										<span class="nav-icon">
											<i class="fa fa-check-double"></i>
										</span>
										<span class="nav-text">Completadas <span class="label label-rounded label-info" style="width: 40px;"><?php echo $listado['nCompletadas']; ?></span></span>
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
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<?php
															if ($dato['encargado'] == 1) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/fecha/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-calendar-check"></i>
																	</span>
																	<span class="navi-text">Especificar Fecha de Entrega</span>
																</a>
															</li>
															<?php
															}
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/comentario/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
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
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['procesando'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/comentario/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/finalizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
																	</span>
																	<span class="navi-text">Finalizar</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

							<!-- Finalizadas -->
							<div class="tab-pane" id="finalizadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['finalizadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/comentario/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

							<!-- Completadas -->
							<div class="tab-pane" id="completadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Fecha Cierre</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['completadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_cierre']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['canceladas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><?php echo $dato['id']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: center;"><?php echo $dato['prioridad']; ?></td>
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
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

			<?php
			// Listado Global
			} elseif (isset($listadoGlobal)) {
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
										<span class="nav-text">Pendientes <span class="label label-rounded label-success" style="width: 40px;"><?php echo $listado['nPendientes']; ?></span></span>
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
									<a class="nav-link" data-toggle="tab" href="#finalizadas">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Finalizadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nFinalizadas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#completadas">
										<span class="nav-icon">
											<i class="fa fa-check-double"></i>
										</span>
										<span class="nav-text">Completadas <span class="label label-rounded label-info" style="width: 40px;"><?php echo $listado['nCompletadas']; ?></span></span>
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
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['procesando'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

							<!-- Finalizadas -->
							<div class="tab-pane" id="finalizadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['finalizadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

							<!-- Completadas -->
							<div class="tab-pane" id="completadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Fecha Cierre</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['completadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_cierre']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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
									      	<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['canceladas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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
										<span class="nav-text">Pendientes <span class="label label-rounded label-success" style="width: 40px;"><?php echo $listado['nPendientes']; ?></span></span>
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
									<a class="nav-link" data-toggle="tab" href="#finalizadas">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Finalizadas <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nFinalizadas']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#completadas">
										<span class="nav-icon">
											<i class="fa fa-check-double"></i>
										</span>
										<span class="nav-text">Completadas <span class="label label-rounded label-info" style="width: 40px;"><?php echo $listado['nCompletadas']; ?></span></span>
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
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-file-pdf"></i>
																	</span>
																	<span class="navi-text">Visualizar PDF</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/comentario/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/cancelar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

							<!-- Procesando -->
							<div class="tab-pane" id="procesando" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['procesando'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/comentario/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

							<!-- Finalizadas -->
							<div class="tab-pane" id="finalizadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['finalizadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/comentario/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-edit"></i>
																	</span>
																	<span class="navi-text">Agregar Comentario</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/cerrar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check-double"></i>
																	</span>
																	<span class="navi-text">Cerrar</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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

							<!-- Completadas -->
							<div class="tab-pane" id="completadas" role="tabpanel">
								<table class="table table-bordered table-striped kt_datatable-0">
						  			<thead>
						  				<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Fecha Compromiso</th>
											<th style="text-align: center;">Fecha Finalizada</th>
											<th style="text-align: center;">Fecha Cierre</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['completadas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_compromiso']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_finalizada']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_cierre']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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
									      	<tr>
									      	<th style="text-align: center;">No. Folio</th>
											<th style="text-align: center;">Fecha de Creación</th>
											<th style="text-align: center;">Remitente</th>
											<th style="text-align: center;">Destinatario</th>
											<th style="text-align: center;">Tema</th>
											<th style="text-align: center;">Prioridad</th>
											<th style="text-align: center;">Fecha Requerida</th>
											<th style="text-align: center;">Dias Restantes</th>
											<th style="text-align: center;">Opciones</th>
								    	</tr>
								    </thead>
									<tbody>
										<?php
										foreach ($listado['canceladas'] as $dato) {
										?>
										<tr>
											<td style="text-align: center;"><a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link"><?php echo $dato['id']; ?></a></td>
											<td style="text-align: center;"><?php echo $dato['fecha_creacion']; ?></td>
											<td style="text-align: center;"><?php echo $dato['remitente']; ?></td>
											<td style="text-align: center;"><?php echo $dato['destinatario']; ?></td>
											<td style="text-align: center;"><?php echo $dato['titulo']; ?></td>
											<td style="text-align: left;"><span class="label label-dot <?php echo $dato['prioridad_label']; ?>" style="width: 15px; height: 15px;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dato['prioridad']; ?></td>
											<td style="text-align: center;"><?php echo $dato['fecha_requerida']; ?></td>
											<td style="text-align: center; background-color: <?php echo $dato['color']; ?>"><img src="<?php echo STASIS; ?>/img/<?php echo $dato['icono']; ?>" height="14" style="margin-top: -4px;" /> <?php echo $dato['dias_restantes']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/movimientos/procesos/visualizar/<?php echo $dato['uniqueid']; ?>" class="navi-link">
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
			// Listado
			} elseif (isset($nuevoIndicador)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información del Indicador</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Responsable</label>
							<div class="col-4">
								<div class="input-group">
									<select class="form-control" name="id_responsable" required>
										<?php echo $listadoUsuariosGlobales; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Procedimiento</label>
							<div class="col-4">
								<input class="form-control" type="file" required name="archivo">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Meta Porcentaje</label>
							<div class="col-4">
								<div class="input-group">
									<input type="text" required name="meta" class="form-control" />
									<div class="input-group-append"><span class="input-group-text">%</span></div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Indicador</label>
							<div class="col-4">
								<input class="form-control mayusculas" type="text" name="indicador" value="<?php echo $datos['solicitado_por']; ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Medición</label>
							<div class="col-4">
								<div class="input-group">
									<select class="form-control" name="medicion" required>
										<option value="">Selecciona opción...</option>
										<option value="1">SEMANAL</option>
										<option value="2">MENSUAL</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Revisión</label>
							<div class="col-4">
								<input class="form-control mayusculas" type="text" name="revision" value="<?php echo $datos['solicitado_por']; ?>" required>
							</div>
						</div>

						
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="agregarIndicador" value="1">
								<button type="submit" class="btn btn-primary">Agregar Indicador</button>
								<a href="<?php echo STASIS; ?>/movimientos/procesos/indicadores" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Listado
			} elseif (isset($editarIndicador)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información del Indicador</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-2 col-form-label">* Responsable</label>
							<div class="col-4">
								<div class="input-group">
									<select class="form-control" name="id_responsable" required>
										<?php echo $listadoUsuariosGlobales; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">Procedimiento</label>
							<div class="col-4">
								<input class="form-control" type="file" name="archivo">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Meta Porcentaje</label>
							<div class="col-4">
								<div class="input-group">
									<input type="text" required name="meta" class="form-control" value="<?php echo $datos->meta; ?>" />
									<div class="input-group-append"><span class="input-group-text">%</span></div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Indicador</label>
							<div class="col-4">
								<input class="form-control mayusculas" type="text" name="indicador" value="<?php echo $datos->indicador; ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Medición</label>
							<div class="col-4">
								<div class="input-group">
									<select class="form-control" name="medicion" required>
										<option value="">Selecciona opción...</option>
										<option <?php if ($datos->medicion == 1) echo 'selected'; ?> value="1">SEMANAL</option>
										<option <?php if ($datos->medicion == 2) echo 'selected'; ?> value="2">MENSUAL</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-2 col-form-label">* Revisión</label>
							<div class="col-4">
								<input class="form-control mayusculas" type="text" name="revision" value="<?php echo $datos->revision; ?>" required>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos->id; ?>">
								<input type="hidden" name="aplicarCambiosIndicador" value="1">
								<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
								<a href="<?php echo STASIS; ?>/movimientos/procesos/indicadores" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Listado
			} elseif (isset($detallesIndicador)) {
			?>

			<form class="form" action="" method="post" enctype="multipart/form-data">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Información del Indicador</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="row">
							
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-3 col-form-label">Responsable</label>
									<div class="col-9">
										<div class="input-group">
											<select class="form-control" name="id_responsable" disabled>
												<?php echo $listadoUsuariosGlobales; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-3 col-form-label">Puesto</label>
									<div class="col-9">
										<input class="form-control mayusculas" type="text" value="<?php echo $datos->puesto; ?>" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-3 col-form-label">Departamento</label>
									<div class="col-9">
										<input class="form-control mayusculas" type="text" value="<?php echo $datos->departamento; ?>" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-3 col-form-label">Indicador</label>
									<div class="col-9">
										<input class="form-control mayusculas" type="text" name="indicador" value="<?php echo $datos->indicador; ?>" disabled>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-3 col-form-label">Medición</label>
									<div class="col-9">
										<div class="input-group">
											<select class="form-control" name="medicion" disabled>
												<option value="">Selecciona opción...</option>
												<option <?php if ($datos->medicion == 1) echo 'selected'; ?> value="1">SEMANAL</option>
												<option <?php if ($datos->medicion == 2) echo 'selected'; ?> value="2">MENSUAL</option>
											</select>
										</div>
									</div>
								</div>
								
							</div>

							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-3 col-form-label">Meta Porcentaje</label>
									<div class="col-9">
										<div class="input-group">
											<input type="text" disabled name="meta" class="form-control" value="<?php echo $datos->meta; ?>" />
											<div class="input-group-append"><span class="input-group-text">%</span></div>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-3 col-form-label">Revisión</label>
									<div class="col-9">
										<input class="form-control mayusculas" type="text" name="revision" value="<?php echo $datos->revision; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-3 col-form-label">Motivo del Cambio</label>
									<div class="col-9">
										<textarea class="form-control" name="motivo" style="height: 161px;"><?php echo $datos->motivo; ?></textarea>
									</div>
								</div>
							</div>

						</div>
					</div>

					<div class="card-header">
						<h3 class="card-title">
							<span class="card-label font-weight-bolder text-dark">Desglose</span>
						</h3>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table text-center">
									<thead>
										<tr>
											<th></th>
											<th>Enero</th>
											<th>Febrero</th>
											<th>Marzo</th>
											<th>Abril</th>
											<th>Mayo</th>
											<th>Junio</th>
											<th>Julio</th>
											<th>Agosto</th>
											<th>Septiembre</th>
											<th>Octubre</th>
											<th>Noviembre</th>
											<th>Diciembre</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$table = '';
										$x = 0;
										$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

										for ($x=0; $x<=4; $x++) {
											$table .= '<tr>';
											if ($x == 0) {
												$table .= '<th class="table-primary">Meta</th>';

												foreach ($meses as $mes) {
													$table .= '<td>' . $datos->meta . '%</td>';
												}
											} elseif ($x == 1) {
												$table .= '<th class="table-warning">Actual</th>';

												foreach ($meses as $mes) {
													$table .= '<td>0.00%</td>';
												}
											} elseif ($x == 2) {
												$table .= '<td>Pagadas</td>';

												foreach ($meses as $mes) {
													$table .= '<td>0</td>';
												}
											} elseif ($x == 3) {
												$table .= '<td>No Pagadas</td>';
												foreach ($meses as $mes) {
													$table .= '<td>0</td>';
												}
											}

											$table .= '</tr>';
										}

										echo $table;
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="card-footer">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-6">
								<input type="hidden" name="id" value="<?php echo $datos->id; ?>">
								<input type="hidden" name="aplicarCambiosIndicador" value="1">
								<button type="submit" class="btn btn-primary">Aplicar Cambios</button>
								<a href="<?php echo STASIS; ?>/movimientos/procesos/indicadores" class="btn btn-secondary">Regresar</a>
							</div>
						</div>
					</div>

				</div>
			</form>

			<?php
			// Listado
			} elseif (isset($listadoIndicadores)) {
			?>

			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
				</h3>
				<div class="card-toolbar">
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/movimientos/procesos/nuevo_indicador"><i class="fa fa-plus"></i> Nuevo Indicador</a>
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
								<table class="table table-bordered table-striped kt_datatable-0">
									<thead>
										<tr>
									      	<th>No. Folio</th>
											<th>Indicador</th>
											<th>Procedimiento</th>
											<th>Responsable</th>
											<th>Puesto</th>
											<th>Departamento</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listadoIndicadores['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['id']; ?></td>
											<td><?php echo $datos['indicador']; ?></td>
											<td><a href="<?php echo STASIS; ?>/data/privada/archivos/<?php echo $datos['procedimiento']; ?>"><i class="las la-download"></i> Descargar</a></td>
											<td><?php echo $datos['responsable']; ?></td>
											<td><?php echo $datos['puesto']; ?></td>
											<td><?php echo $datos['departamento']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/editar_indicador/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/detalles/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-chart-bar"></i>
																	</span>
																	<span class="navi-text">Detalle de Indicador</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/movimientos/procesos/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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
								<table class="table table-bordered table-striped kt_datatable-0">
									<thead>
										<tr>
									      	<th>No. Folio</th>
											<th>Indicador</th>
											<th>Procedimiento</th>
											<th>Medición</th>
											<th>Puesto</th>
											<th>Departamento</th>
											<th>Revisión</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listadoIndicadores['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['id']; ?></td>
											<td><?php echo $datos['indicador']; ?></td>
											<td><?php echo $datos['procedimiento']; ?></td>
											<td><?php echo $datos['medicion']; ?></td>
											<td><?php echo $datos['puesto']; ?></td>
											<td><?php echo $datos['departamento']; ?></td>
											<td><?php echo $datos['revision']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/centros_trabajo/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/centros_trabajo/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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