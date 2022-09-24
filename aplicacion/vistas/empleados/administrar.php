<?php
require_once(APP . '/vistas/encabezado.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet">
            <div class="kt-portlet__body">
		<?php
		if (!empty($mensajes)) {
			foreach ($mensajes as $mensaje) {
				echo '<div id="mensajes">' . $mensaje . '</div>';
			}
		}
		if (!empty($status)) echo $status;

		// Nuevo
		if (isset($nuevo)) {
		?>

		<!--begin::Portlet-->
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Información del Empleado
				</h3>
			</div>
		</div>

		<!--begin::Form-->
		<form class="kt-form kt-form--label-right">
			<div class="kt-portlet__body">
				<div class="form-group row">
					<label for="example-text-input" class="col-2 col-form-label">Nombre</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-search-input" class="col-2 col-form-label">Apellidos</label>
					<div class="col-3">
						<input class="form-control mayusculas" type="text">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-email-input" class="col-2 col-form-label">E-Mail</label>
					<div class="col-3">
						<input class="form-control minusculas" type="text">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-url-input" class="col-2 col-form-label">Teléfono</label>
					<div class="col-3">
						<input class="form-control numeric" type="text">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-tel-input" class="col-2 col-form-label">Celular</label>
					<div class="col-3">
						<input class="form-control numeric" type="text">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-password-input" class="col-2 col-form-label">Contraseña</label>
					<div class="col-3">
						<input class="form-control" type="password">
					</div>
				</div>
				<div class="form-group row">
					<label for="example-number-input" class="col-2 col-form-label">Repetir Contraseña</label>
					<div class="col-3">
						<input class="form-control" type="password">
					</div>
				</div>
			</div>
			<div class="kt-portlet__foot">
				<div class="kt-form__actions">
					<div class="row">
						<div class="col-2">
						</div>
						<div class="col-10">
							<button type="reset" class="btn btn-success">Agregar Empleado</button>
							<button type="reset" class="btn btn-secondary">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
		</form>

		<?php
		// Modificar
		} elseif (isset($modificar)) {
		?>

		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="form-datos-personales" action="" method="post" autocomplete="off">
					<fieldset>
						<legend>Personal</legend>
						<div class="form-group control-group">
							<label for="nombreUsuario" class="control-label col-sm-2">Nombre de Usuario</label>
							<div class="controls col-sm-7">
								<input type="text" class="form-control input-3" name="nombreUsuario" value="<?php echo $datos->nombreUsuario; ?>">
								<span class="help-block">El nombre de usuario tiene que estar en minúsculas, no debe contener espacios y ningún caracter especial (!&quot;#$%&amp;/&deg;)</em>.</span>
							</div>
						</div>
						<div class="form-group control-group">
							<label for="nombre" class="control-label col-sm-2">Nombre</label>
							<div class="controls col-sm-7">
								<input type="text" class="form-control input-3 mayusculas" id="nombre" name="nombre" maxlength="30" value="<?php echo $datos->nombre; ?>">
							</div>
						</div>
						<div class="form-group control-group">
							<label for="apellidos" class="control-label col-sm-2">Apellidos</label>
							<div class="controls col-sm-7">
								<input type="text" class="form-control input-3 mayusculas" id="apellidos" name="apellidos" maxlength="30" value="<?php echo $datos->apellidos; ?>">
							</div>
						</div>
						<div class="form-group control-group">
							<label for="apellidos" class="control-label col-sm-2">Grado</label>
							<div class="controls col-sm-7">
								<select name="grado" class="form-control input-3">
									<option value="">Seleccionar grado...</option>
									<option value="1" <?php if ($datos->grado == 1) echo 'selected="selected"'; ?>>LIC.</option>
									<option value="2"  <?php if ($datos->grado == 2) echo 'selected="selected"'; ?>>ING.</option>
									<option value="3"  <?php if ($datos->grado == 3) echo 'selected="selected"'; ?>>MTRO.</option>
									<option value="4"  <?php if ($datos->grado == 4) echo 'selected="selected"'; ?>>MTRA.</option>
									<option value="5"  <?php if ($datos->grado == 5) echo 'selected="selected"'; ?>>DR.</option>
									<option value="6"  <?php if ($datos->grado == 6) echo 'selected="selected"'; ?>>DRA.</option>
								</select>
							</div>
						</div>
						<div class="form-group control-group">
							<label for="email" class="control-label col-sm-2">E-Mail</label>
							<div class="controls col-sm-7">
								<input type="text" class="form-control input-4 minusculas" id="email" name="email" maxlength="100" value="<?php echo $datos->email; ?>">
							</div>
						</div>
						<div class="form-group control-group">
							<label for="telefono" class="control-label col-sm-2">Tel&eacute;fono</label>
							<div class="controls col-sm-7">
								<input type="text" class="form-control input-3 mayusculas" id="telefono" name="telefono" maxlength="15" value="<?php echo $datos->telefono; ?>">
								<span class="help-block">Ejemplo de formato de tel&eacute;fono aceptado: 664-123-4567</span>
							</div>
						</div>
						<div class="form-group control-group">
							<label for="extension" class="control-label col-sm-2">Extensi&oacute;n</label>
							<div class="controls col-sm-7">
								<input type="text" class="form-control input-3" id="extension" name="extension" maxlength="5" value="<?php echo $datos->extension; ?>">
							</div>
						</div>
						<div class="form-group control-group">
							<label for="celular" class="control-label col-sm-2">Celular</label>
							<div class="controls col-sm-7">
								<input type="text" class="form-control input-3 mayusculas" id="celular" name="celular" maxlength="15" value="<?php echo $datos->celular; ?>">
								<span class="help-block">Ejemplo de formato de n&uacute;mero de celular aceptado: 664-123-4567</span>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend>Empresa</legend>
						<div class="form-group control-group">
							<label for="sitio" class="control-label col-sm-2">Sitio</label>
							<div class="controls col-sm-7">
								<select name="sitio" class="form-control input-3">
									<option value="">Seleccionar sitio...</option>
									<option value="1" <?php if ($datos->sitio == 1) echo 'selected="selected"'; ?>>MATRIZ</option>
								</select>
							</div>
						</div>
						<div class="form-group control-group">
							<label for="sitio" class="control-label col-sm-2">Puesto Laboral</label>
							<div class="controls col-sm-7">
								<select name="puesto" class="form-control input-3">
									<option value="">Seleccionar puesto...</option>
									<option value="1" <?php if ($datos->puesto == 1) echo 'selected="selected"'; ?>>ADMINISTRADOR</option>
									<option value="2" <?php if ($datos->puesto == 2) echo 'selected="selected"'; ?>>GERENTE DE SITIO</option>
									<option value="3" <?php if ($datos->puesto == 3) echo 'selected="selected"'; ?>>COMPRAS/VENTAS</option>
									<option value="4"  <?php if ($datos->puesto == 4) echo 'selected="selected"'; ?>>ALMACENISTA</option>
									<option value="5"  <?php if ($datos->puesto == 5) echo 'selected="selected"'; ?>>LOGISTICA</option>
									<option value="6"  <?php if ($datos->puesto == 6) echo 'selected="selected"'; ?>>FACTURACION</option>
									<option value="7"  <?php if ($datos->puesto == 7) echo 'selected="selected"'; ?>>IMPORT/EXPORT</option>
									<option value="8"  <?php if ($datos->puesto == 8) echo 'selected="selected"'; ?>>UTILIDADES</option>
									<option value="9"  <?php if ($datos->puesto == 9) echo 'selected="selected"'; ?>>FINANZAS</option>
									<option value="10"  <?php if ($datos->puesto == 10) echo 'selected="selected"'; ?>>CONTABILIDAD</option>
								</select>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend>Horario de Trabajo</legend>
						<div class="form-group control-group">
							<label for="horario_inicio" class="control-label col-sm-2">Hora de Entrada</label>
							<div class="controls col-sm-7">
								<select name="horario_inicio" class="form-control input-3">
									<option value="">Seleccionar hora...</option>
									<option value="06:00:00" <?php if ($datos->horario_inicio == '06:00:00') echo 'selected="selected"'; ?>>6:00 AM</option>
									<option value="07:00:00" <?php if ($datos->horario_inicio == '07:00:00') echo 'selected="selected"'; ?>>7:00 AM</option>
									<option value="08:00:00" <?php if ($datos->horario_inicio == '08:00:00') echo 'selected="selected"'; ?>>8:00 AM</option>
									<option value="09:00:00" <?php if ($datos->horario_inicio == '09:00:00') echo 'selected="selected"'; ?>>9:00 AM</option>
									<option value="10:00:00" <?php if ($datos->horario_inicio == '10:00:00') echo 'selected="selected"'; ?>>10:00 AM</option>
									<option value="11:00:00" <?php if ($datos->horario_inicio == '11:00:00') echo 'selected="selected"'; ?>>11:00 AM</option>
									<option value="12:00:00" <?php if ($datos->horario_inicio == '12:00:00') echo 'selected="selected"'; ?>>12:00 AM</option>
									<option value="13:00:00" <?php if ($datos->horario_inicio == '13:00:00') echo 'selected="selected"'; ?>>1:00 PM</option>
									<option value="14:00:00" <?php if ($datos->horario_inicio == '14:00:00') echo 'selected="selected"'; ?>>2:00 PM</option>
									<option value="15:00:00" <?php if ($datos->horario_inicio == '15:00:00') echo 'selected="selected"'; ?>>3:00 PM</option>
									<option value="16:00:00" <?php if ($datos->horario_inicio == '16:00:00') echo 'selected="selected"'; ?>>4:00 PM</option>
									<option value="17:00:00" <?php if ($datos->horario_inicio == '17:00:00') echo 'selected="selected"'; ?>>5:00 PM</option>
									<option value="18:00:00" <?php if ($datos->horario_inicio == '18:00:00') echo 'selected="selected"'; ?>>6:00 PM</option>
									<option value="19:00:00" <?php if ($datos->horario_inicio == '19:00:00') echo 'selected="selected"'; ?>>7:00 PM</option>
									<option value="20:00:00" <?php if ($datos->horario_inicio == '20:00:00') echo 'selected="selected"'; ?>>8:00 PM</option>
									<option value="21:00:00" <?php if ($datos->horario_inicio == '21:00:00') echo 'selected="selected"'; ?>>9:00 PM</option>
									<option value="22:00:00" <?php if ($datos->horario_inicio == '22:00:00') echo 'selected="selected"'; ?>>10:00 PM</option>
									<option value="23:00:00" <?php if ($datos->horario_inicio == '23:00:00') echo 'selected="selected"'; ?>>11:00 PM</option>
								</select>
							</div>
						</div>
						<div class="form-group control-group">
							<label for="horario_fin" class="control-label col-sm-2">Hora de Salida</label>
							<div class="controls col-sm-7">
								<select name="horario_fin" class="form-control input-3">
									<option value="">Seleccionar hora...</option>
									<option value="06:00:00" <?php if ($datos->horario_fin == '06:00:00') echo 'selected="selected"'; ?>>6:00 AM</option>
									<option value="07:00:00" <?php if ($datos->horario_fin == '07:00:00') echo 'selected="selected"'; ?>>7:00 AM</option>
									<option value="08:00:00" <?php if ($datos->horario_fin == '08:00:00') echo 'selected="selected"'; ?>>8:00 AM</option>
									<option value="09:00:00" <?php if ($datos->horario_fin == '09:00:00') echo 'selected="selected"'; ?>>9:00 AM</option>
									<option value="10:00:00" <?php if ($datos->horario_fin == '10:00:00') echo 'selected="selected"'; ?>>10:00 AM</option>
									<option value="11:00:00" <?php if ($datos->horario_fin == '11:00:00') echo 'selected="selected"'; ?>>11:00 AM</option>
									<option value="12:00:00" <?php if ($datos->horario_fin == '12:00:00') echo 'selected="selected"'; ?>>12:00 AM</option>
									<option value="13:00:00" <?php if ($datos->horario_fin == '13:00:00') echo 'selected="selected"'; ?>>1:00 PM</option>
									<option value="14:00:00" <?php if ($datos->horario_fin == '14:00:00') echo 'selected="selected"'; ?>>2:00 PM</option>
									<option value="15:00:00" <?php if ($datos->horario_fin == '15:00:00') echo 'selected="selected"'; ?>>3:00 PM</option>
									<option value="16:00:00" <?php if ($datos->horario_fin == '16:00:00') echo 'selected="selected"'; ?>>4:00 PM</option>
									<option value="17:00:00" <?php if ($datos->horario_fin == '17:00:00') echo 'selected="selected"'; ?>>5:00 PM</option>
									<option value="18:00:00" <?php if ($datos->horario_fin == '18:00:00') echo 'selected="selected"'; ?>>6:00 PM</option>
									<option value="19:00:00" <?php if ($datos->horario_fin == '19:00:00') echo 'selected="selected"'; ?>>7:00 PM</option>
									<option value="20:00:00" <?php if ($datos->horario_fin == '20:00:00') echo 'selected="selected"'; ?>>8:00 PM</option>
									<option value="21:00:00" <?php if ($datos->horario_fin == '21:00:00') echo 'selected="selected"'; ?>>9:00 PM</option>
									<option value="22:00:00" <?php if ($datos->horario_fin == '22:00:00') echo 'selected="selected"'; ?>>10:00 PM</option>
									<option value="23:00:00" <?php if ($datos->horario_fin == '23:00:00') echo 'selected="selected"'; ?>>11:00 PM</option>
								</select>
							</div>
						</div>
						<div class="form-group control-group">
							<label for="dia_inicio" class="control-label col-sm-2">Dia Inicio</label>
							<div class="controls col-sm-7">
								<select name="dia_inicio" class="form-control input-3">
									<option value="">Seleccionar dia...</option>
									<option value="0" <?php if ($datos->dia_inicio == 0) echo 'selected="selected"'; ?>>Domingo</option>
									<option value="1" <?php if ($datos->dia_inicio == 1) echo 'selected="selected"'; ?>>Lunes</option>
									<option value="2" <?php if ($datos->dia_inicio == 2) echo 'selected="selected"'; ?>>Martes</option>
									<option value="3" <?php if ($datos->dia_inicio == 3) echo 'selected="selected"'; ?>>Miércoles</option>
									<option value="4" <?php if ($datos->dia_inicio == 4) echo 'selected="selected"'; ?>>Jueves</option>
									<option value="5" <?php if ($datos->dia_inicio == 5) echo 'selected="selected"'; ?>>Viernes</option>
									<option value="6" <?php if ($datos->dia_inicio == 6) echo 'selected="selected"'; ?>>Sábado</option>
								</select>
							</div>
						</div>
						<div class="form-group control-group">
							<label for="dia_fin" class="control-label col-sm-2">Dia Fin</label>
							<div class="controls col-sm-7">
								<select name="dia_fin" class="form-control input-3">
									<option value="">Seleccionar dia...</option>
									<option value="0">Domingo</option>
									<option value="1">Lunes</option>
									<option value="2">Martes</option>
									<option value="3">Miércoles</option>
									<option value="4">Jueves</option>
									<option value="5">Viernes</option>
									<option value="6">Sábado</option>
								</select>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend>Seguridad</legend>
						<p>Si quieres mantener la contrase&ntilde;a actual del empleado, deja los dos campos vac&iacute;os.</p>
						<div class="form-group control-group">
							<label for="contrasena1" class="control-label col-sm-2">Contrase&ntilde;a</label>
							<div class="controls col-sm-7">
								<input type="password" class="form-control input-3" id="contrasena1" name="contrasena1" maxlength="20">
							</div>
						</div>
						<div class="form-group control-group">
							<label for="contrasena2" class="control-label col-sm-2">Repetir Contrase&ntilde;a</label>
							<div class="controls col-sm-7">
								<input type="password" class="form-control input-3" id="contrasena2" name="contrasena2" maxlength="20">
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend>Huella Dactilar Digital</legend>
						<?php
						if (!empty($datos->huella)) {
						?>
						<p>¡Muy bien! La huella dactilar de este empleado ya est&aacute; registrada en el sistema.</p>
						<div class="form-group control-group">
							<label for="huella" class="control-label col-sm-2">Huella Dactilar Registrada</label>
							<div class="controls col-sm-7">
								<img src="<?php echo STASIS; ?>/img/icono-huella_si.png" />
							</div>
						</div>
						<?php
						} else {
						?>
						<p>La huella dactilar de este empleado no est&aacute; registrada en el sistema.</p>
						<div class="form-group control-group">
							<label for="huella" class="control-label col-sm-2">Huella Registrada</label>
							<div class="controls col-sm-7">
								<img src="<?php echo STASIS; ?>/img/icono-huella_no.png" />
							</div>
						</div>
						<?php
						}
						?>
					</fieldset>
					
					<div class="well">
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
					    <a href="<?php echo STASIS; ?>/empleados/administrar" class="btn btn-default"><i class="fa fa-reply"></i> Regresar</a>
			    		<button type="submit" class="btn btn-primary" name="guardarCambios"><i class="fa fa-check"></i> Guardar Cambios</button>
		    		</div>
				</form>
			</div>
		</div>
		<?php
		// Listado de empleados
		} else {
		?>

		<div style="">
			<div class="form-group control-group">
				<a class="btn btn-primary" href="<?php echo STASIS; ?>/empleados/administrar/nuevo"><i class="fa fa-plus-circle"></i> Nuevo Empleado</a>
			</div>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#" data-target="#activos">Activos</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#inactivos">Inactivos</a></li>
		</ul>
			<div class="tab-content">
			<div class="tab-pane active" id="activos" role="tabpanel">
				<table class="table tablesorter tabla-datos tabla-filtro">
		  			<thead>
		  				<tr>
					      	<th>Nombre</th>
					    	<th>Apellidos</th>
					    	<th>Puesto</th>
					    	<th>E-Mail</th>
					    	<th>Teléfono</th>
					    	<th>Celular</th>
					    	<th>Opciones</th>
				    	</tr>
				    </thead>
					<tbody>
						<?php
						foreach ($activos as $dato) {
						?>
						<tr>
							<td><?php echo $dato['nombre']; ?></td>
							<td><?php echo $dato['apellidos']; ?></td>
							<td><?php echo $dato['puesto']; ?></td>
							<td><?php echo $dato['email']; ?></td>
							<td><?php echo $dato['telefono']; ?></td>
							<td><?php echo $dato['celular']; ?></td>
							<td class="centrar">
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
								    	<i class="icon-user"></i> Acci&oacute;n</a> <i class="icon-caret-down"></i>
								  	</button>

									<ul class="dropdown-menu">
										<li><a href="<?php echo STASIS; ?>/empleados/administrar/modificar/<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-editar.png" /> Modificar</a></li>
										<li><a href="javascript:void(0)" class="empleado-inactivar" id="<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-inactivar.png" /> Inactivar</a></li>
									</ul>
								</div>
							</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="inactivos" role="tabpanel">
				<table class="table teibolsorter tabla-datos tabla-filtro">
		  			<thead>
		  				<tr>
					      	<th>Nombre</th>
					    	<th>Apellidos</th>
					    	<th>Puesto</th>
					    	<th>E-Mail</th>
					    	<th>Teléfono</th>
					    	<th>Celular</th>
					    	<th>Opciones</th>
				    	</tr>
				    </thead>
					<tbody>
						<?php
						foreach ($inactivos as $dato) {
						?>
						<tr>
							<td><?php echo $dato['nombre']; ?></td>
							<td><?php echo $dato['apellidos']; ?></td>
							<td><?php echo $dato['puesto']; ?></td>
							<td><?php echo $dato['email']; ?></td>
							<td><?php echo $dato['telefono']; ?></td>
							<td><?php echo $dato['celular']; ?></td>
							<td class="centrar">
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
								    	<i class="icon-user"></i> Acci&oacute;n</a> <i class="icon-caret-down"></i>
								  	</button>

									<ul class="dropdown-menu">
										<li><a href="<?php echo STASIS; ?>/empleados/administrar/modificar/<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-editar.png" /> Modificar</a></li>
										<li><a href="#" class="empleado-reactivar" id="<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-activar.png" /> Reactivar</a></li>
									</ul>
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

		<?php
		}
		?>
	</div>

	</div></div></div></div>
<?php
require_once(APP . '/vistas/pie_pagina.php');