<?php
require_once(APP . '/vistas/inc/encabezado.php');
?>

<?php
if ($_SESSION['clave_catastral_cuenta'] == 1) {
?>
<div class="card card-custom gutter-b">
	<div class="row">
		<div class="col-md-12 text-center">
			<div class="alert alert-primary mb-0 p-5" role="alert">
			    <h4 class="alert-heading">Clave Catastral</h4>

			    <?php
			    // Si no tiene adeudo
			    if ($_SESSION['adeudo'] == '0.00') {
		    	?>

		    	<p class="m-0">Estimado propetario, su clave catastral ha sido adjuntada.</p><br />
			    <a href="http://saevalcas.mx/data/f/<?php echo $_SESSION['clave_catastral']; ?>" class="btn btn-info mr-2"><i class="fa fa-download"></i> Descargar Clave Catastral</a>

		    	<?php
		    	// Si tiene adeudo
			    } else {
		    	?>

		    	<p class="m-0">Estimado propetario, actualmente tiene un adeudo por $ <?php echo $_SESSION['adeudo']; ?>.</p><br />
			    <a target="_blank" href="http://residencialrt.mx/propietarioInformacion" class="btn btn-info mr-2"><i class="fa fa-dollar-sign"></i> Solicitar Costos</a>

		    	<?php
			    }
		    	?>
			    
			</div>
		</div>
	</div>
</div>
<?php
}
?>

<div class="card card-custom gutter-b">

	<div class="row">
		<div class="col-lg-3 col-xl-3">
		</div>

		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="http://residencialrt.mx/propietarioInformacion">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-info-circle fa-4x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Estatus de Entrega de Clave Catastral</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="http://residencialrt.mx/RedesElectricas">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-calendar fa-4x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Programación de Red Eléctrica</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="https://cobroplan.mx">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-dollar-sign fa-4x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Realiza tu Pago Aquí</p>
				</div>
			</a>
		</div>
	</div>

</div>

<div class="card card-custom gutter-b">
	<div class="row">
		<div class="col-md-12 text-center" style="margin: 20px 0 20px;">
			<div class="header-menu-wrapper header-menu-wrapper-left" style="font-weight: bold; font-size: 20px; margin-bottom: 10px;">Manual de Propietarios</div>
			<img src="<?php echo STASIS; ?>/img/qr.png" style="height: 130px" />
		</div>
	</div>
</div>

<div class="card card-custom gutter-b">

	<div class="row">
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-21.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-users fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Asociación de Usuarios</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-5.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-certificate fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Beneficios</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-4.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-home fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Bienvenida</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-19.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-balance-scale fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Cesiones de Derechos / Cancelaciones</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-20.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-credit-card fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Cobroplan</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-7-10.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-pencil-ruler fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Comité de Diseño</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-17.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-file-invoice-dollar fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Pago Predial y Clave Catastral</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-11-14.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-hammer fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Proceso de Construcción</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-15.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-faucet fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Red Hidráulica</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-16.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-bolt fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Red Eléctrica</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-29-30.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-swimmer fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Reglamento de Alberca</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-25.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-dumpster fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Reglamento de Basura</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-26.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-water fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Reglamento de Uso de Lago</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-32-37.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-book-reader fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Reglas Generales</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-22-23.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-check-circle fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Reglas de Uso</p>
				</div>
			</a>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-4 col-xl-2 text-center">
			<a target="_blank" href="<?php echo STASIS; ?>/data/privada/Revista_Propietarios_RT2-18.pdf">
				<div class="card-body principal-cards" style="padding: 25px 15px;">
					<i class="fa fa-file-contract fa-3x ml-n1" style="color: #83AB29;"></i><br /><br />
					<p class="text-dark-75 font-weight-bolder font-size-h6 m-0">Tramite de Escrituración</p>
				</div>
			</a>
		</div>

	</div>
</div>

<?php
require_once(APP . '/vistas/inc/pie_pagina.php');
?>