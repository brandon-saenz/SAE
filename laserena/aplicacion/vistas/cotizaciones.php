<?php
require_once(APP . '/vistas/inc/encabezado.php');
?>

<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-body">

				<div class="row">
					<div class="col-md-12">
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
									<th style="text-align: center;">No. Folio</th>
									<th style="text-align: center;">Propietario</th>
									<th style="text-align: center;">Lote</th>
									<th style="text-align: center;">Generado Por</th>
									<th style="text-align: center;">Total</th>
									<th style="text-align: center;">Fecha de Creación</th>
									<th style="text-align: center;">Fecha de Vigencia</th>
									<th style="text-align: center;">Status</th>
								</tr>
							</thead>
							<tbody>
								
								<?php
								$x = 1;
								foreach ($listado as $datos) {
								?>
								<tr>
									<td style="text-align: center;"><a target="_blank" href="https://saevalcas.mx/m/c/v/<?php echo $datos['alfanumerico']; ?>" class="navi-link"><?php echo $datos['id']; ?></a></td>
									<td style="text-align: center;"><?php echo $datos['propietario']; ?></td>
									<td style="text-align: center;"><?php echo $datos['lote']; ?></td>
									<td style="text-align: center;"><?php echo $datos['agente']; ?></td>
									<td style="text-align: center;"><?php echo $datos['total']; ?></td>
									<td style="text-align: center;"><?php echo $datos['fecha_creacion']; ?></td>
									<td style="text-align: center;"><?php echo $datos['fecha_vigencia']; ?></td>
									<td style="text-align: center; white-space: nowrap;"><span class="label label-dot <?php echo $datos['label']; ?>" style="width: 15px; height: 15px;"></span> <?php echo $datos['statusHtml']; ?></td>
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
require_once(APP . '/vistas/inc/pie_pagina.php');
?>