<?php
require_once(APP . '/vistas/encabezado.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet">
            <!-- <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Text
                    </h3>
                </div>
            </div> -->

            <!--begin::Portlet-->
            <div class="kt-portlet__body">
		<?php
		if (!empty($mensajes)) {
			foreach ($mensajes as $mensaje) {
				echo '<div id="mensajes">' . $mensaje . '</div>';
			}
		}
		if (!empty($status)) echo $status;

		// Modificar y asignar privilegios
		if (isset($idEmpleado)) {
		?>

		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" role="form" id="form-datos-personales" action="" method="post" autocomplete="off">
					<p>Elige los módulos y submódulos a los que puede accesar el empleado elegido.</p>

					<fieldset>
						<legend>Catálogos</legend>
						<input type="checkbox" class="form-control" />
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['catalogoClientes'] == 1) echo 'checked="checked"';?> name="catalogoClientes"> Clientes
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['catalogoProveedores'] == 1) echo 'checked="checked"';?> name="catalogoProveedores"> Proveedores
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['catalogoAlmacenes'] == 1) echo 'checked="checked"';?> name="catalogoAlmacenes"> Almacenes
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['catalogoPartes'] == 1) echo 'checked="checked"';?> name="catalogoPartes"> Partes
							</label>
						</div>
					</fieldset><br />

					<fieldset>
						<legend>Movimientos</legend>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['cotizaciones'] == 1) echo 'checked="checked"';?> name="cotizaciones"> Generar cotizaciones
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['ordenCompraMx'] == 1) echo 'checked="checked"';?> name="ordenCompraMx"> Generar órdenes de compra
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['traspasos'] == 1) echo 'checked="checked"';?> name="traspasos"> Realizar traspasos
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['facturacion'] == 1) echo 'checked="checked"';?> name="facturacion"> Facturación
							</label>
						</div>
					</fieldset><br />
								
					<fieldset>
						<legend>Empleados</legend>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['horasTrabajo'] == 1) echo 'checked="checked"';?> name="horasTrabajo"> Listado de Empleados
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['privilegios'] == 1) echo 'checked="checked"';?> name="privilegios"> Privilegios
							</label>
						</div>
					</fieldset><br />

					<fieldset>
						<legend>Reportes</legend>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['reportesCotizaciones'] == 1) echo 'checked="checked"';?> name="reportesCotizaciones"> Cotizaciones
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['reportesOrdenesCompra'] == 1) echo 'checked="checked"';?> name="reportesOrdenesCompra"> Órdenes de compra
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['reportesVentas'] == 1) echo 'checked="checked"';?> name="reportesVentas"> Ventas
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['reportesFacturas'] == 1) echo 'checked="checked"';?> name="reportesFacturas"> Facturas
							</label>
						</div>
					</fieldset><br />

					<fieldset>
						<legend>Finanzas</legend>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['finanzasUtilidades'] == 1) echo 'checked="checked"';?> name="finanzasUtilidades"> Utilidades
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="1" <?php if ($privilegios['finanzasGastos'] == 1) echo 'checked="checked"';?> name="finanzasGastos"> Gastos
							</label>
						</div>
					</fieldset><br />
					
					<div class="well">
						<input type="hidden" name="id" value="<?php echo $idEmpleado; ?>" />
					    <a href="<?php echo STASIS; ?>/empleados/administrar" class="btn btn-default"><i class="fa fa-reply"></i> Regresar</a>
			    		<button type="submit" class="btn btn-primary" name="guardar"><i class="fa fa-check"></i> Asignar Privilegios</button>
		    		</div>
				</form>
			</div>
		</div>
		<?php
		// Listado de empleados
		} else {
		?>

		<table class="table tablesorter tabla-datos tabla-filtro">
  			<thead>
  				<tr>
		  			<th>ID</th>
			      	<th>Nombre</th>
			    	<th>Apellidos</th>
			    	<th>Sitio</th>
			    	<th>Opciones</th>
		    	</tr>
		    </thead>
			<tbody>
				<?php
				foreach ($datos as $dato) {
				?>
				<tr>
					<td><?php echo $dato['id']; ?></td>
					<td><?php echo $dato['nombre']; ?></td>
					<td><?php echo $dato['apellidos']; ?></td>
					<td><?php echo $dato['sitio']; ?></td>
					<td class="centrar">
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
						    	<i class="icon-user"></i> Acci&oacute;n</a> <i class="icon-caret-down"></i>
						  	</button>

							<ul class="dropdown-menu">
								<li><a href="<?php echo STASIS; ?>/empleados/privilegios/<?php echo $dato['id']; ?>"><img src="<?php echo STASIS; ?>/img/icono-trofeo.png" /> Asignar Privilegios</a></li>
							</ul>
						</div>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>

		<?php
		}
		?>
	</div>

	</div></div></div></div>
<?php
require_once(APP . '/vistas/pie_pagina.php');