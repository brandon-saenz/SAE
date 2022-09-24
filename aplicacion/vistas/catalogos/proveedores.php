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
// Revision de Informacion
} elseif (isset($revision)) {
?>

<form class="form" action="" method="post">
	<div class="card card-custom">
		<div class="card-header">
			<h3 class="card-title">
				<span class="card-label font-weight-bolder text-dark">Comentario</span>
			</h3>
		</div>

		<div class="card-body">
			<div class="alert alert-warning p-5" role="alert">
			    <p class="m-0"><i class="fa fa-exclamation-triangle text-white"></i> El comentario especificado se enviará por correo del proveedor y posteriormente el status del mismo cambiará a "En Revisión".</p>
			</div>

			<div class="form-group row">
				<label class="col-2 col-form-label">Proveedor</label>
				<div class="col-5">
					<input class="form-control mayusculas" type="text" value="<?php echo $info['nombre']; ?>" disabled>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-2 col-form-label">E-Mail</label>
				<div class="col-5">
					<input class="form-control minusculas" type="text" value="<?php echo $info['email']; ?>" disabled>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-2 col-form-label">* Comentario</label>
				<div class="col-5">
					<textarea name="comentario" class="form-control" rows="6" required></textarea>
				</div>
			</div>
		</div>

		<div class="card-footer">
			<div class="row">
				<div class="col-lg-2"></div>
				<div class="col-lg-6">
					<input type="hidden" name="id" value="<?php echo $info['id']; ?>">
					<input type="hidden" name="revisionInformacion" value="1">
					<button type="submit" class="btn btn-primary">Enviar Comentario</button>
					<a href="<?php echo STASIS; ?>/catalogos/proveedores" class="btn btn-secondary">Regresar</a>
				</div>
			</div>
		</div>

	</div>
</form>

<?php
// Autorizar
} elseif (isset($autorizar)) {
?>

<form class="form" action="" method="post">
	<div class="card card-custom">
		<div class="card-header">
			<h3 class="card-title">
				<span class="card-label font-weight-bolder text-dark">Especificar Uso de CFDI</span>
			</h3>
		</div>

		<div class="card-body">
			<div class="alert alert-info p-5" role="alert">
			    <p class="m-0"><i class="fa fa-info-circle text-white"></i> Antes de poder autorizar a un proveedor, es necesario especificar el uso de CFDI.</p>
			</div>

			<div class="form-group row">
				<label class="col-2 col-form-label">Proveedor</label>
				<div class="col-5">
					<input class="form-control mayusculas" type="text" value="<?php echo $info['nombre']; ?>" disabled>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-2 col-form-label">* Uso CFDI #1</label>
				<div class="col-5">
					<select class="form-control" name="uso_cfdi1" required>
	                    <option value="">Selecciona opción...</option>
	                    <option value="P01">P01 - POR DEFINIR</option>
	                    <option value="G01">G01 - ADQUISICIÓN DE MERCANCÍAS</option>
	                    <option value="G02">G02 - DEVOLUCIONES, DESCUENTOS O BONIFICACIONES</option>
	                    <option value="G03">G03 - GASTOS EN GENERAL</option>
	                    <option value="I01">I01 - CONSTRUCCIONES</option>
	                    <option value="I02">I02 - MOBILIARIO Y EQUIPO DE OFICINA POR INVERSIONES</option>
	                    <option value="I03">I03 - EQUIPO DE TRANSPORTE</option>
	                    <option value="I04">I04 - EQUIPO DE COMPUTO Y ACCESORIOS</option>
	                    <option value="I05">I05 - DADOS, TROQUELES, MOLDES, MATRICES Y HERRAMENTAL</option>
	                    <option value="I06">I06 - COMUNICACIONES TELEFÓNICAS</option>
	                    <option value="I07">I07 - COMUNICACIONES SATELITALES</option>
	                    <option value="I08">I08 - OTRA MAQUINARIA Y EQUIPO</option>
	                    <option value="D01">D01 - HONORARIOS MÉDICOS, DENTALES Y GASTOS HOSPITALARIOS</option>
	                    <option value="D02">D02 - GASTOS MÉDICOS POR INCAPACIDAD O DISCAPACIDAD</option>
	                    <option value="D03">D03 - GASTOS FUNERALES</option>
	                    <option value="D04">D04 - DONATIVOS</option>
	                    <option value="D05">D05 - INTERESES REALES EFECTIVAMENTE PAGADOS POR CRÉDITOS HIPOTECARIOS (CASA HABITACIÓN)</option>
	                    <option value="D06">D06 - APORTACIONES VOLUNTARIAS AL SAR</option>
	                    <option value="D07">D07 - PRIMAS POR SEGUROS DE GASTOS MÉDICOS</option>
	                    <option value="D08">D08 - GASTOS DE TRANSPORTACIÓN ESCOLAR OBLIGATORIA</option>
	                    <option value="D09">D09 - DEPÓSITOS EN CUENTAS PARA EL AHORRO, PRIMAS QUE TENGAN COMO BASE PLANES DE PENSIONES</option>
	                    <option value="D10">D10 - PAGOS POR SERVICIOS EDUCATIVOS (COLEGIATURAS)</option>
	                    <option value="S01">S01 - SIN EFECTOS FISCALES</option>
	                    <option value="CP01">CP01 - PAGOS</option>
	                    <option value="CN01">CN01 - NÓMINA</option>
	                </select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-2 col-form-label">Uso CFDI #2</label>
				<div class="col-5">
					<select class="form-control" name="uso_cfdi2">
	                    <option value="">Selecciona opción...</option>
	                    <option value="G01">G01 - ADQUISICIÓN DE MERCANCÍAS</option>
	                    <option value="G02">G02 - DEVOLUCIONES, DESCUENTOS O BONIFICACIONES</option>
	                    <option value="G03">G03 - GASTOS EN GENERAL</option>
	                    <option value="I01">I01 - CONSTRUCCIONES</option>
	                    <option value="I02">I02 - MOBILIARIO Y EQUIPO DE OFICINA POR INVERSIONES</option>
	                    <option value="I03">I03 - EQUIPO DE TRANSPORTE</option>
	                    <option value="I04">I04 - EQUIPO DE COMPUTO Y ACCESORIOS</option>
	                    <option value="I05">I05 - DADOS, TROQUELES, MOLDES, MATRICES Y HERRAMENTAL</option>
	                    <option value="I06">I06 - COMUNICACIONES TELEFÓNICAS</option>
	                    <option value="I07">I07 - COMUNICACIONES SATELITALES</option>
	                    <option value="I08">I08 - OTRA MAQUINARIA Y EQUIPO</option>
	                    <option value="D01">D01 - HONORARIOS MÉDICOS, DENTALES Y GASTOS HOSPITALARIOS</option>
	                    <option value="D02">D02 - GASTOS MÉDICOS POR INCAPACIDAD O DISCAPACIDAD</option>
	                    <option value="D03">D03 - GASTOS FUNERALES</option>
	                    <option value="D04">D04 - DONATIVOS</option>
	                    <option value="D05">D05 - INTERESES REALES EFECTIVAMENTE PAGADOS POR CRÉDITOS HIPOTECARIOS (CASA HABITACIÓN)</option>
	                    <option value="D06">D06 - APORTACIONES VOLUNTARIAS AL SAR</option>
	                    <option value="D07">D07 - PRIMAS POR SEGUROS DE GASTOS MÉDICOS</option>
	                    <option value="D08">D08 - GASTOS DE TRANSPORTACIÓN ESCOLAR OBLIGATORIA</option>
	                    <option value="D09">D09 - DEPÓSITOS EN CUENTAS PARA EL AHORRO, PRIMAS QUE TENGAN COMO BASE PLANES DE PENSIONES</option>
	                    <option value="D10">D10 - PAGOS POR SERVICIOS EDUCATIVOS (COLEGIATURAS)</option>
	                    <option value="S01">S01 - SIN EFECTOS FISCALES</option>
	                    <option value="CP01">CP01 - PAGOS</option>
	                    <option value="CN01">CN01 - NÓMINA</option>
	                </select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-2 col-form-label">Uso CFDI #3</label>
				<div class="col-5">
					<select class="form-control" name="uso_cfdi3">
	                    <option value="">Selecciona opción...</option>
	                    <option value="G01">G01 - ADQUISICIÓN DE MERCANCÍAS</option>
	                    <option value="G02">G02 - DEVOLUCIONES, DESCUENTOS O BONIFICACIONES</option>
	                    <option value="G03">G03 - GASTOS EN GENERAL</option>
	                    <option value="I01">I01 - CONSTRUCCIONES</option>
	                    <option value="I02">I02 - MOBILIARIO Y EQUIPO DE OFICINA POR INVERSIONES</option>
	                    <option value="I03">I03 - EQUIPO DE TRANSPORTE</option>
	                    <option value="I04">I04 - EQUIPO DE COMPUTO Y ACCESORIOS</option>
	                    <option value="I05">I05 - DADOS, TROQUELES, MOLDES, MATRICES Y HERRAMENTAL</option>
	                    <option value="I06">I06 - COMUNICACIONES TELEFÓNICAS</option>
	                    <option value="I07">I07 - COMUNICACIONES SATELITALES</option>
	                    <option value="I08">I08 - OTRA MAQUINARIA Y EQUIPO</option>
	                    <option value="D01">D01 - HONORARIOS MÉDICOS, DENTALES Y GASTOS HOSPITALARIOS</option>
	                    <option value="D02">D02 - GASTOS MÉDICOS POR INCAPACIDAD O DISCAPACIDAD</option>
	                    <option value="D03">D03 - GASTOS FUNERALES</option>
	                    <option value="D04">D04 - DONATIVOS</option>
	                    <option value="D05">D05 - INTERESES REALES EFECTIVAMENTE PAGADOS POR CRÉDITOS HIPOTECARIOS (CASA HABITACIÓN)</option>
	                    <option value="D06">D06 - APORTACIONES VOLUNTARIAS AL SAR</option>
	                    <option value="D07">D07 - PRIMAS POR SEGUROS DE GASTOS MÉDICOS</option>
	                    <option value="D08">D08 - GASTOS DE TRANSPORTACIÓN ESCOLAR OBLIGATORIA</option>
	                    <option value="D09">D09 - DEPÓSITOS EN CUENTAS PARA EL AHORRO, PRIMAS QUE TENGAN COMO BASE PLANES DE PENSIONES</option>
	                    <option value="D10">D10 - PAGOS POR SERVICIOS EDUCATIVOS (COLEGIATURAS)</option>
	                    <option value="S01">S01 - SIN EFECTOS FISCALES</option>
	                    <option value="CP01">CP01 - PAGOS</option>
	                    <option value="CN01">CN01 - NÓMINA</option>
	                </select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-2 col-form-label">* Responsable</label>
				<div class="col-5">
					<select class="form-control" name="id_responsable">
	                    <?php echo $listadoUsuarios; ?>
	                </select>
				</div>
			</div>
			
		</div>

		<div class="card-footer">
			<div class="row">
				<div class="col-lg-2"></div>
				<div class="col-lg-6">
					<input type="hidden" name="id" value="<?php echo $info['id']; ?>">
					<input type="hidden" name="autorizarProveedor" value="1">
					<button type="submit" class="btn btn-primary">Autorizar Proveedor</button>
					<a href="<?php echo STASIS; ?>/catalogos/proveedores" class="btn btn-secondary">Regresar</a>
				</div>
			</div>
		</div>

	</div>
</form>

<?php
// Listado de Puestos
} else {
?>

<div class="row">
	<div class="col-xl-12">
		<div class="card card-custom gutter-b card-stretch ">
			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Proveedores</span>
				</h3>

				<div class="card-toolbar">
					<a class="btn btn-success btn-md py-2 mr-5 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/proveedores/excel"><i class="fa fa-table"></i> Exportar a Excel</a>

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
									<a class="nav-link" data-toggle="tab" href="#revision">
										<span class="nav-icon">
											<i class="fa fa-ellipsis-h"></i>
										</span>
										<span class="nav-text">En Revisión <span class="label label-rounded label-warning" style="width: 40px;"><?php echo $listado['nRevision']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#aprobados">
										<span class="nav-icon">
											<i class="fa fa-check"></i>
										</span>
										<span class="nav-text">Aprobados <span class="label label-rounded label-primary" style="width: 40px;"><?php echo $listado['nAprobados']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#autorizados">
										<span class="nav-icon">
											<i class="fa fa-check-double"></i>
										</span>
										<span class="nav-text">Autorizados <span class="label label-rounded label-info" style="width: 40px;"><?php echo $listado['nAutorizados']; ?></span></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#inactivos">
										<span class="nav-icon">
											<i class="fa fa-times"></i>
										</span>
										<span class="nav-text">Inactivos <span class="label label-rounded label-danger" style="width: 40px;"><?php echo $listado['nInactivos']; ?></span></span>
									</a>
								</li>
							</ul>
						</div>

					</div>
				</div>

				<div class="row">
					<div class="col-md-12">

						<div class="tab-content">
							<!-- Activos -->
							<div class="tab-pane fade show active" id="pendientes" role="tabpanel" aria-labelledby="pendientes">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
									    	<th>RFC</th>
									    	<th>Tipo</th>
									    	<th>Nombre de Contacto</th>
									    	<th>Teléfono</th>
									    	<th>E-Mail</th>
									    	<th>Ciudad</th>
									    	<th>Estado</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['pendientes'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['rfc']; ?></td>
											<td><?php echo $datos['tipo']; ?></td>
											<td><?php echo $datos['contacto']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['ciudad']; ?></td>
											<td><?php echo $datos['estado']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/proveedores/perfil/pdf/<?php echo $datos['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-folder"></i>
																	</span>
																	<span class="navi-text">Ver Perfil de Proveedor</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/catalogos/proveedores/revision/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-reply"></i>
																	</span>
																	<span class="navi-text">Revisión de Información</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/proveedores/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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

							<!-- Revision -->
							<div class="tab-pane fade" id="revision" role="tabpanel" aria-labelledby="revision">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
									    	<th>RFC</th>
									    	<th>Tipo</th>
									    	<th>Nombre de Contacto</th>
									    	<th>Teléfono</th>
									    	<th>E-Mail</th>
									    	<th>Ciudad</th>
									    	<th>Estado</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['revision'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['rfc']; ?></td>
											<td><?php echo $datos['tipo']; ?></td>
											<td><?php echo $datos['contacto']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['ciudad']; ?></td>
											<td><?php echo $datos['estado']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/proveedores/perfil/pdf/<?php echo $datos['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-folder"></i>
																	</span>
																	<span class="navi-text">Ver Perfil de Proveedor</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/catalogos/proveedores/revision/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-reply"></i>
																	</span>
																	<span class="navi-text">Revisión de Información</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/proveedores/aprobar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check"></i>
																	</span>
																	<span class="navi-text">Aprobar</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/proveedores/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-times"></i>
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

							<!-- Aprobados -->
							<div class="tab-pane fade" id="aprobados" role="tabpanel" aria-labelledby="aprobados">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
									    	<th>RFC</th>
									    	<th>Tipo</th>
									    	<th>Nombre de Contacto</th>
									    	<th>Teléfono</th>
									    	<th>E-Mail</th>
									    	<th>Ciudad</th>
									    	<th>Estado</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['aprobados'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['rfc']; ?></td>
											<td><?php echo $datos['tipo']; ?></td>
											<td><?php echo $datos['contacto']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['ciudad']; ?></td>
											<td><?php echo $datos['estado']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/proveedores/perfil/pdf/<?php echo $datos['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-folder"></i>
																	</span>
																	<span class="navi-text">Ver Perfil de Proveedor</span>
																</a>
															</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/catalogos/proveedores/revision/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-reply"></i>
																	</span>
																	<span class="navi-text">Revisión de Información</span>
																</a>
															</li>
															
															<?php
															if ($_SESSION['login_autorizar_proveedores'] == 1) {
															?>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/proveedores/autorizar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-check-double"></i>
																	</span>
																	<span class="navi-text">Autorizar</span>
																</a>
															</li>
															<?php
															}
															?>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/proveedores/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-times"></i>
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

							<!-- Autorizados -->
							<div class="tab-pane fade" id="autorizados" role="tabpanel" aria-labelledby="autorizados">
								<table class="table table-bordered table-striped kt_datatable-2">
									<thead>
										<tr>
											<th>Nombre</th>
									    	<th>RFC</th>
									    	<th>Tipo</th>
									    	<th>Nombre de Contacto</th>
									    	<th>Teléfono</th>
									    	<th>E-Mail</th>
									    	<th>Ciudad</th>
									    	<th>Estado</th>
									    	<th>Responsable</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['autorizados'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['rfc']; ?></td>
											<td><?php echo $datos['tipo']; ?></td>
											<td><?php echo $datos['contacto']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['ciudad']; ?></td>
											<td><?php echo $datos['estado']; ?></td>
											<td><?php echo $datos['responsable']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/proveedores/perfil/pdf/<?php echo $datos['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-folder"></i>
																	</span>
																	<span class="navi-text">Ver Perfil de Proveedor</span>
																</a>
															</li>
															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/catalogos/proveedores/revision/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-reply"></i>
																	</span>
																	<span class="navi-text">Revisión de Información</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/proveedores/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-times"></i>
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
									    	<th>RFC</th>
									    	<th>Tipo</th>
									    	<th>Nombre de Contacto</th>
									    	<th>Teléfono</th>
									    	<th>E-Mail</th>
									    	<th>Ciudad</th>
									    	<th>Estado</th>
									    	<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['rfc']; ?></td>
											<td><?php echo $datos['tipo']; ?></td>
											<td><?php echo $datos['contacto']; ?></td>
											<td><?php echo $datos['telefono']; ?></td>
											<td><?php echo $datos['email']; ?></td>
											<td><?php echo $datos['ciudad']; ?></td>
											<td><?php echo $datos['estado']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a target="_blank" href="<?php echo STASIS; ?>/proveedores/perfil/pdf/<?php echo $datos['uniqueid']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-folder"></i>
																	</span>
																	<span class="navi-text">Ver Perfil de Proveedor</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/proveedores/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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