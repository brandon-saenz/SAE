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
					<span class="card-label font-weight-bolder text-dark">Información del Concepto</span>
				</h3>
			</div>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Nombre del Servicio</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="nombre" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Area Responsable</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="area" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Usuario Responsable</label>
						<div class="col-6">
							<select class="form-control" name="usuario" required>
								<?php echo $listadoUsuariosGlobales; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Clasificación</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="clasificacion" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Empresa</label>
						<div class="col-6">
							<select class="form-control" name="empresa">
								<option value="">Selecciona empresa...</option>
								<option <?php if ($info->empresa == 'EL ENCANTO RESORT CLUB, S DE RL DE CV') echo 'selected'; ?> value="EL ENCANTO RESORT CLUB, S DE RL DE CV">EL ENCANTO RESORT CLUB, S DE RL DE CV</option>
								<option <?php if ($info->empresa == 'COBROPLAN, SC') echo 'selected'; ?> value="COBROPLAN, SC">COBROPLAN, SC</option>
								<option <?php if ($info->empresa == 'ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC') echo 'selected'; ?> value="ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC">ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC</option>
								<option <?php if ($info->empresa == 'ENCINO DE PIEDRA DE BC, S DE RL DE CV') echo 'selected'; ?> value="ENCINO DE PIEDRA DE BC, S DE RL DE CV">ENCINO DE PIEDRA DE BC, S DE RL DE CV</option>
								<option <?php if ($info->empresa == 'LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV') echo 'selected'; ?> value="LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV">LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV</option>
								<option <?php if ($info->empresa == 'MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV') echo 'selected'; ?> value="MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV">MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV</option>
								<option <?php if ($info->empresa == 'INMOBILIARIA RANCHO TECATE S DE RL DE CV') echo 'selected'; ?> value="INMOBILIARIA RANCHO TECATE S DE RL DE CV">INMOBILIARIA RANCHO TECATE S DE RL DE CV</option>
								<option <?php if ($info->empresa == 'RGR-GLOBAL-BUSINESS') echo 'selected'; ?> value="RGR-GLOBAL-BUSINESS">RGR-GLOBAL-BUSINESS</option>
								<option <?php if ($info->empresa == 'CONSTRUCTORA RANCHO TECATE') echo 'selected'; ?> value="CONSTRUCTORA RANCHO TECATE">CONSTRUCTORA RANCHO TECATE</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Dirección</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="direccion" value="<?php echo $info->direccion; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">RFC</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="rfc" value="<?php echo $info->rfc; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Clave Prodserv</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="clave_prodserv" value="<?php echo $info->clave_prodserv; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Unidad de Medida</label>
						<div class="col-6">
							<select name="iva" class="form-control">
				                <option value="">Selecciona unidad...</option>
								<option value="111">PIEZA</option>
								<option value="112">ELEMENTO</option>
								<option value="113">UNIDAD DE SERVICIO</option>
								<option value="114">ACTIVIDAD</option>
								<option value="115">KILOGRAMO</option>
								<option value="116">TRABAJO</option>
								<option value="117">TARIFA</option>
								<option value="118">METRO</option>
								<option value="119">PAQUETE A GRANEL</option>
								<option value="120">CAJA BASE</option>
								<option value="121">KIT</option>
								<option value="122">CONJUNTO</option>
								<option value="123">LITRO</option>
								<option value="124">CAJA</option>
								<option value="125">MES</option>
								<option value="126">HORA</option>
								<option value="127">METRO CUADRADO</option>
								<option value="128">EQUIPOS</option>
								<option value="129">MILIGRAMO</option>
								<option value="130">PAQUETE</option>
								<option value="131">KIT (CONJUNTO DE PIEZAS)</option>
								<option value="132">VARIEDAD</option>
								<option value="133">GRAMO</option>
								<option value="134">PAR</option>
								<option value="135">DOCENAS DE PIEZAS</option>
								<option value="136">UNIDAD</option>
								<option value="137">DÍA</option>
								<option value="138">LOTE</option>
								<option value="139">GRUPOS</option>
								<option value="140">MILILITRO</option>
								<option value="141">VIAJE</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Tasa de IVA</label>
						<div class="col-6">
							<select name="iva" class="form-control">
				                <option value="">Selecciona IVA...</option>
				                <option <?php if ($info->iva == ".00") echo 'selected'; ?> value=".00">0.00%</option>
				                <option <?php if ($info->iva == ".08") echo 'selected'; ?> value=".08">8.00%</option>
				                <option <?php if ($info->iva == ".16") echo 'selected'; ?> value=".16">16.00%</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Moneda</label>
						<div class="col-6">
							<select name="moneda" class="form-control">
				                <option value="">Selecciona moneda...</option>
				                <option <?php if ($info->moneda == "1") echo 'selected'; ?> value="1">PESOS</option>
				                <option <?php if ($info->moneda == "2") echo 'selected'; ?> value="2">DÓLARES</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Aplica IEPS</label>
						<div class="col-6">
							<select name="ieps" class="form-control">
				                <option value="">Selecciona opción...</option>
				                <option <?php if ($info->ieps == "1") echo 'selected'; ?> value="1">SI</option>
				                <option <?php if ($info->ieps == "0") echo 'selected'; ?> value="0">NO</option>
							</select>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<div class="row">
						<div class="col-lg-2"></div>
						<div class="col-lg-6">
							<input type="hidden" name="nuevo" value="1">
							<button type="submit" class="btn btn-primary">Agregar Concepto</button>
							<a href="<?php echo STASIS; ?>/catalogos/conceptos" class="btn btn-secondary">Regresar</a>
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
					<span class="card-label font-weight-bolder text-dark">Información del Concepto</span>
				</h3>
			</div>

			<form class="form" action="" method="post">
				<div class="card-body">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Nombre del Servicio</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="nombre" value="<?php echo $info->nombre; ?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Area Responsable</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="area" value="<?php echo $info->area; ?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Usuario Responsable</label>
						<div class="col-6">
							<select class="form-control" name="usuario" required>
								<?php echo $listadoUsuariosGlobales; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Clasificación</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="clasificacion" value="<?php echo $info->clasificacion; ?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Empresa</label>
						<div class="col-6">
							<select class="form-control" name="empresa">
								<option value="">Selecciona empresa...</option>
								<option <?php if ($info->empresa == 'EL ENCANTO RESORT CLUB, S DE RL DE CV') echo 'selected'; ?> value="EL ENCANTO RESORT CLUB, S DE RL DE CV">EL ENCANTO RESORT CLUB, S DE RL DE CV</option>
								<option <?php if ($info->empresa == 'COBROPLAN, SC') echo 'selected'; ?> value="COBROPLAN, SC">COBROPLAN, SC</option>
								<option <?php if ($info->empresa == 'ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC') echo 'selected'; ?> value="ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC">ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC</option>
								<option <?php if ($info->empresa == 'ENCINO DE PIEDRA DE BC, S DE RL DE CV') echo 'selected'; ?> value="ENCINO DE PIEDRA DE BC, S DE RL DE CV">ENCINO DE PIEDRA DE BC, S DE RL DE CV</option>
								<option <?php if ($info->empresa == 'LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV') echo 'selected'; ?> value="LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV">LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV</option>
								<option <?php if ($info->empresa == 'MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV') echo 'selected'; ?> value="MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV">MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV</option>
								<option <?php if ($info->empresa == 'INMOBILIARIA RANCHO TECATE S DE RL DE CV') echo 'selected'; ?> value="INMOBILIARIA RANCHO TECATE S DE RL DE CV">INMOBILIARIA RANCHO TECATE S DE RL DE CV</option>
								<option <?php if ($info->empresa == 'RGR-GLOBAL-BUSINESS') echo 'selected'; ?> value="RGR-GLOBAL-BUSINESS">RGR-GLOBAL-BUSINESS</option>
								<option <?php if ($info->empresa == 'CONSTRUCTORA RANCHO TECATE') echo 'selected'; ?> value="CONSTRUCTORA RANCHO TECATE">CONSTRUCTORA RANCHO TECATE</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Dirección</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="direccion" value="<?php echo $info->direccion; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">RFC</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="rfc" value="<?php echo $info->rfc; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Clave Prodserv</label>
						<div class="col-6">
							<input class="form-control mayusculas" type="text" name="clave_prodserv" value="<?php echo $info->clave_prodserv; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">* Unidad de Medida</label>
						<div class="col-6">
							<select name="um" class="form-control">
				                <option value="">Selecciona unidad...</option>
								<option <?php if ($info->um == '111') echo 'selected'; ?> value="111">PIEZA</option>
								<option <?php if ($info->um == '112') echo 'selected'; ?> value="112">ELEMENTO</option>
								<option <?php if ($info->um == '113') echo 'selected'; ?> value="113">UNIDAD DE SERVICIO</option>
								<option <?php if ($info->um == '114') echo 'selected'; ?> value="114">ACTIVIDAD</option>
								<option <?php if ($info->um == '115') echo 'selected'; ?> value="115">KILOGRAMO</option>
								<option <?php if ($info->um == '116') echo 'selected'; ?> value="116">TRABAJO</option>
								<option <?php if ($info->um == '117') echo 'selected'; ?> value="117">TARIFA</option>
								<option <?php if ($info->um == '118') echo 'selected'; ?> value="118">METRO</option>
								<option <?php if ($info->um == '119') echo 'selected'; ?> value="119">PAQUETE A GRANEL</option>
								<option <?php if ($info->um == '120') echo 'selected'; ?> value="120">CAJA BASE</option>
								<option <?php if ($info->um == '121') echo 'selected'; ?> value="121">KIT</option>
								<option <?php if ($info->um == '122') echo 'selected'; ?> value="122">CONJUNTO</option>
								<option <?php if ($info->um == '123') echo 'selected'; ?> value="123">LITRO</option>
								<option <?php if ($info->um == '124') echo 'selected'; ?> value="124">CAJA</option>
								<option <?php if ($info->um == '125') echo 'selected'; ?> value="125">MES</option>
								<option <?php if ($info->um == '126') echo 'selected'; ?> value="126">HORA</option>
								<option <?php if ($info->um == '127') echo 'selected'; ?> value="127">METRO CUADRADO</option>
								<option <?php if ($info->um == '128') echo 'selected'; ?> value="128">EQUIPOS</option>
								<option <?php if ($info->um == '129') echo 'selected'; ?> value="129">MILIGRAMO</option>
								<option <?php if ($info->um == '130') echo 'selected'; ?> value="130">PAQUETE</option>
								<option <?php if ($info->um == '131') echo 'selected'; ?> value="131">KIT (CONJUNTO DE PIEZAS)</option>
								<option <?php if ($info->um == '132') echo 'selected'; ?> value="132">VARIEDAD</option>
								<option <?php if ($info->um == '133') echo 'selected'; ?> value="133">GRAMO</option>
								<option <?php if ($info->um == '134') echo 'selected'; ?> value="134">PAR</option>
								<option <?php if ($info->um == '135') echo 'selected'; ?> value="135">DOCENAS DE PIEZAS</option>
								<option <?php if ($info->um == '136') echo 'selected'; ?> value="136">UNIDAD</option>
								<option <?php if ($info->um == '137') echo 'selected'; ?> value="137">DÍA</option>
								<option <?php if ($info->um == '138') echo 'selected'; ?> value="138">LOTE</option>
								<option <?php if ($info->um == '139') echo 'selected'; ?> value="139">GRUPOS</option>
								<option <?php if ($info->um == '140') echo 'selected'; ?> value="140">MILILITRO</option>
								<option <?php if ($info->um == '141') echo 'selected'; ?> value="141">VIAJE</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Tasa de IVA</label>
						<div class="col-6">
							<select name="iva" class="form-control">
				                <option value="">Selecciona IVA...</option>
				                <option <?php if ($info->iva == ".00") echo 'selected'; ?> value=".00">0.00%</option>
				                <option <?php if ($info->iva == ".08") echo 'selected'; ?> value=".08">8.00%</option>
				                <option <?php if ($info->iva == ".16") echo 'selected'; ?> value=".16">16.00%</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Moneda</label>
						<div class="col-6">
							<select name="moneda" class="form-control">
				                <option value="">Selecciona moneda...</option>
				                <option <?php if ($info->moneda == "1") echo 'selected'; ?> value="1">PESOS</option>
				                <option <?php if ($info->moneda == "2") echo 'selected'; ?> value="2">DÓLARES</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Aplica IEPS</label>
						<div class="col-6">
							<select name="ieps" class="form-control">
				                <option value="">Selecciona opción...</option>
				                <option <?php if ($info->ieps == "1") echo 'selected'; ?> value="1">SI</option>
				                <option <?php if ($info->ieps == "0") echo 'selected'; ?> value="0">NO</option>
							</select>
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
							<a href="<?php echo STASIS; ?>/catalogos/conceptos" class="btn btn-secondary">Regresar</a>
						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<?php
// Listado de Departamentos
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
					<a class="btn btn-light-primary btn-md py-2 font-weight-bolder" href="<?php echo STASIS; ?>/catalogos/conceptos/nuevo"><i class="fa fa-plus"></i> Nuevo Concepto</a>
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
											<th>Area</th>
											<th>Responsable</th>
											<th>Clasificación</th>
											<th>Empresa</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['activos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['area']; ?></td>
											<td><?php echo $datos['responsable']; ?></td>
											<td><?php echo $datos['clasificacion']; ?></td>
											<td><?php echo $datos['empresa']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/conceptos/modificar/<?php echo $datos['id']; ?>" class="navi-link">
																	<span class="navi-icon">
																		<i class="las la-pen"></i>
																	</span>
																	<span class="navi-text">Editar</span>
																</a>
															</li>
															
															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/conceptos/inactivar/<?php echo $datos['id']; ?>" class="navi-link">
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
											<th>Area</th>
											<th>Responsable</th>
											<th>Clasificación</th>
											<th>Empresa</th>
							    			<th>Opciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($listado['inactivos'] as $datos) {
										?>
										<tr>
											<td><?php echo $datos['nombre']; ?></td>
											<td><?php echo $datos['area']; ?></td>
											<td><?php echo $datos['responsable']; ?></td>
											<td><?php echo $datos['clasificacion']; ?></td>
											<td><?php echo $datos['empresa']; ?></td>
											<td style="text-align: center;">
												<div class="dropdown dropdown-inline">
													<a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
														<i class="ki ki-bold-more-ver"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="">
														<ul class="navi navi-hover">
															<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Elige una opción:</li>

															<li class="navi-item">
																<a href="<?php echo STASIS; ?>/catalogos/conceptos/reactivar/<?php echo $datos['id']; ?>" class="navi-link">
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