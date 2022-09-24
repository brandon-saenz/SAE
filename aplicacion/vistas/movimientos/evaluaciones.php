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

			<div class="card-header border-0">
				<h3 class="card-title">
					<span class="card-label font-weight-bolder text-dark">Listado de Registros</span>
				</h3>
			</div>

			<div class="card-body pt-2">
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

				<div class="row">
					<div class="col-md-12">

						<table class="table table-bordered table-striped kt_datatable-0">
				  			<thead>
				  				<tr>
							      	<th style="text-align: center;">Folio</th>
							    	<th style="text-align: center;">Propietario</th>
							    	<th style="text-align: center;">Solicitud</th>
							    	<th style="text-align: center;">Servicio y nivel de atención</th>
							    	<th style="text-align: center;">Proceso de solicitud es</th>
							    	<th style="text-align: center;">Atención del asesor fué</th>
							    	<th style="text-align: center;">Atención en tiempo acordado</th>
							    	<th style="text-align: center;">Recibió notificación por correo</th>
							    	<th style="text-align: center;">Retroalimentación del asesor</th>
						    	</tr>
						    </thead>
							<tbody>
								<?php
								foreach ($listado as $dato) {
								?>
								<tr>
									<td style="text-align: center;"><?php echo $dato['id']; ?></td>
									<td style="text-align: center;"><?php echo $dato['propietario']; ?></td>
									<td style="text-align: center;"><?php echo $dato['id_solicitud']; ?></td>
									<td style="text-align: center;"><?php echo $dato['calificacion']; ?></td>
									<td style="text-align: center;"><?php echo $dato['p1']; ?></td>
									<td style="text-align: center;"><?php echo $dato['p2']; ?></td>
									<td style="text-align: center;"><?php echo $dato['p3']; ?></td>
									<td style="text-align: center;"><?php echo $dato['p4']; ?></td>
									<td style="text-align: center;"><?php echo $dato['p5']; ?></td>
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


<?php
require_once(APP . '/vistas/inc/pie_pagina.php');