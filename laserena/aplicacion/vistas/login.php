<!DOCTYPE html>
<html lang="en">
	<head>
		<base href="<?php echo STASIS; ?>/">
		<meta charset="utf-8" />
		<title>La Serena Propietarios</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link href="assets/css/pages/login/classic/login-4.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
		<link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<style type="text/css">
		.mayusculas {text-transform: uppercase;}
		.form-disabled {background-color: #F3F6F9 !important; }
		</style>
	</head>
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<div class="d-flex flex-column flex-root">
			<div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
				<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background: #FAFAFA;">

					<div class="card card-custom login-form p-7 position-relative overflow-hidden">
						<div class="d-flex flex-center">
							<a href="#">
								<img src="img/usuario.png" class="max-h-90px mb-5" alt="" />
							</a>
						</div>
						<div class="login-signin">
							<form class="form" action="" method="post">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>No. Condominio:</label>
											<input type="text" name="condominio" class="form-control" required maxlength="3">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Contraseña:</label>
											<input type="password" class="form-control" name="contrasena" required>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Apellido:</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="la la-user"></i>
											</span>
										</div>
										<input type="text" class="form-control" name="nombreUsuario" required>
									</div>
								</div>

								<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
									<div class="checkbox-inline">
										<label class="checkbox m-0 text-muted">
										<input type="checkbox" name="recordar" />
										<span></span>Recordarme</label>
									</div>
								</div>

								<?php
								if ($mensaje) {
									echo '<div class="alert alert-custom alert-danger" role="alert"> <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div> <div class="alert-text">' . $mensaje . '</div> </div>';
								}
								?>

								<div class="text-center">
									<button type="submit" name="login" class="btn font-weight-bold px-9 py-4 mb-3 mx-4" style="background: #83AB29; color: #FFF;">Ingresar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<!-- <script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script> -->
		<!-- <script src="assets/js/scripts.bundle.js"></script> -->
		<!-- <script src="assets/js/pages/custom/login/login-general.js"></script> -->
		<script src="<?php echo STASIS; ?>/js/plugins.js?<?php echo filemtime(ROOT_DIR . 'static/js/plugins.js'); ?>"></script>
		<script>
		$(function(){
			// Seccion login
			$("#login-seccion").on("change", function(event){
		        var o = $(this).val();

		        switch (o) {
					case 'HACIENDA DEL REY (RGR)': var seccion = 'SR'; break;
					case 'HACIENDA DEL REY': var seccion = 'SR'; break;
					case 'LOMAS (RGR)': var seccion = 'SL'; break;
					case 'LOMAS': var seccion = 'SL'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': var seccion = 'SV'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS': var seccion = 'SV'; break;
					case 'CAÑADA DEL ENCINO': var seccion = 'SC'; break;
					case 'VISTA DEL REY (RGR)': var seccion = 'VR'; break;
					case 'VISTA DEL REY': var seccion = 'VR'; break;
				}

		    	$('#login-seccion-prefijo').val(seccion);
			});

			// Todos los campos .numeric que sean solo numericos
			$("input.numeric").numeric();
		});
	    </script>
	</body>
</html>