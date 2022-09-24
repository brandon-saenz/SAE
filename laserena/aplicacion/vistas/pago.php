<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head>
        <base href="<?php echo STASIS; ?>/">
        <meta charset="utf-8" />
        <title>La Serena Propietarios</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

        <!--end::Fonts-->

        <!--begin::Page Vendors Styles(used by this page)-->
        <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

        <!--end::Page Vendors Styles-->

        <!--begin::Global Theme Styles(used by all pages)-->
        <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

        <!--end::Global Theme Styles-->

        <!--begin::Layout Themes(used by all pages)-->
        <link href="assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/brand/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/aside/light.css" rel="stylesheet" type="text/css" />

        <link href="css/global.css?<?php echo filemtime(ROOT_DIR . 'static/css/global.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="css/app.css?<?php echo filemtime(ROOT_DIR . 'static/css/app.css'); ?>" rel="stylesheet" type="text/css" />

        <!--end::Layout Themes-->
        <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
        <link rel="manifest" href="site.webmanifest">

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
		<script type='text/javascript' src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
		<script type="text/javascript">
	        $(document).ready(function() {

	            OpenPay.setId('m7aci0xq2pyewsqdhy9r');
	            OpenPay.setApiKey('pk_82384048ef5f4be6acd702903e7c38df');
	            // OpenPay.setSandboxMode(true);
	            
	            //Se genera el id de dispositivo
	            var deviceSessionId = OpenPay.deviceData.setup("payment-form", "deviceIdHiddenFieldName");
	            
	            $('#pay-button').on('click', function(event) {
                    event.preventDefault();
                    $("#pay-button").prop( "disabled", true);
                    OpenPay.token.extractFormAndCreate('payment-form', sucess_callbak, error_callbak);
                });

                var sucess_callbak = function(response) {
                  var token_id = response.data.id;
                  $('#token_id').val(token_id);
                  $('#payment-form').submit();
                };

                var error_callbak = function(response) {
                    var desc = response.data.description != undefined ? response.data.description : response.message;
                    alert("ERROR [" + response.status + "] " + desc);
                    $("#pay-button").prop("disabled", false);
                };

	   //          function SuccessCallback(response) {
				//     alert('Operación exitosa');
				//     var content = '', results = document.getElementById('resultDetail');
				//     content .= 'Id tarjeta: ' + response.data.id+ '';
				//     content .= 'A nombre de: ' + response.data.holder_name + '';
				//     content .= 'Marca de tarjeta usada: ' + response.data.brand + '';
				//     results.innerHTML = content;
				// }

				// function ErrorCallback(response) {
				//     alert('Fallo en la transacción');
				//     var content = '', results = document.getElementById('resultDetail');
				//     content .= 'Estatus del error: ' + response.data.status + '';
				//     content .= 'Error: ' + response.message + '';
				//     content .= 'Descripción: ' + response.data.description + '';
				//     content .= 'ID de la petición: ' + response.data.request_id + '';
				//     results.innerHTML = content;
				// }

	        });
	    </script>
	    <style>
			* {
			    color: #444;
			    font-size: 16px;
			    font-weight: 300;
			}
			::-webkit-input-placeholder {
			   font-style: italic;
			}
			:-moz-placeholder {
			   font-style: italic;
			}
			::-moz-placeholder {
			   font-style: italic;
			}
			:-ms-input-placeholder {  
			   font-style: italic;
			}

			strong {
				font-weight: 700;
			}
			a {
			    cursor: pointer;
			    display: block;
			    text-decoration: none;
			}
			a.button {
			    text-align: center;
			    font-size: 21px;
			    font-weight: 400;
			    padding: 12px 0;
			    width: 100%;
			    display: table;
			    background: #E51F04;
			    background: -moz-linear-gradient(top,  #E51F04 0%, #A60000 100%);
			    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#E51F04), color-stop(100%,#A60000));
			    background: -webkit-linear-gradient(top,  #E51F04 0%,#A60000 100%);
			    background: -o-linear-gradient(top,  #E51F04 0%,#A60000 100%);
			    background: -ms-linear-gradient(top,  #E51F04 0%,#A60000 100%);
			    background: linear-gradient(top,  #E51F04 0%,#A60000 100%);
			    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#E51F04', endColorstr='#A60000',GradientType=0 );
			}
			a.button i {
			    margin-right: 10px;
			}
			a.button.disabled {
			    background: none repeat scroll 0 0 #ccc;
			    cursor: default;
			}
			.bkng-tb-cntnt {
			    float: left;
			    width: 800px;
			}
			.bkng-tb-cntnt a.button {
			    color: #fff;
			    float: right;
			    font-size: 18px;
			    padding: 5px 20px;
			    width: auto;
			}
			.bkng-tb-cntnt a.button.o {
			    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
			    color: #e51f04;
			}
			.bkng-tb-cntnt a.button i {
			    color: #fff;
			}
			.bkng-tb-cntnt a.button.o i {
			    color: #e51f04;
			}
			.bkng-tb-cntnt a.button.right i {
			    float: right;
			    margin: 2px 0 0 10px;
			}
			.bkng-tb-cntnt a.button.left {
			    float: left;
			}
			.bkng-tb-cntnt a.button.disabled.o {
			    color: #ccc;
			}
			.bkng-tb-cntnt a.button.disabled.o i {
			    color: #ccc;
			}
			.pymnts {
			    float: left;
			    width: 800px;
			}
			.pymnts * {
			    float: left;
			}

			.sctn-row {
			    margin-bottom: 35px;
			    width: 800px;
			}
			.sctn-col {
			    width: 375px;
			}
			.sctn-col.l {
			    width: 425px;
			}
			.sctn-col input {
			    font-size: 18px;
			    line-height: 24px;
			    padding: 10px 12px;
			    width: 333px;
			}
			.sctn-col label {
			    font-size: 24px;
			    line-height: 24px;
			    margin-bottom: 10px;
			    width: 100%;
			}
			.sctn-col.x3 {
			    width: 300px;
			}
			.sctn-col.x3.last {
			    width: 200px;
			}
			.sctn-col.x3 input {
			    width: 210px;
			}
			.sctn-col.x3 a {
			    float: right;
			}
			.pymnts-sctn {
			    width: 800px;
			}
			.pymnt-itm {
			    margin: 0 0 3px;
			    width: 800px;
			}
			.pymnt-itm h2 {
			    background-color: #e9e9e9;
			    font-size: 24px;
			    line-height: 24px;
			    margin: 0;
			    padding: 28px 0 28px 20px;
			    width: 780px;
			}
			.pymnt-itm.active h2 {
			    background-color: #574B42;
			    color: #fff;
			    cursor: default;
			}
			.pymnt-itm div.pymnt-cntnt {
			    display: none;
			}
			.pymnt-itm.active div.pymnt-cntnt {
			    display: block;
			    padding: 0 0 30px;
			    width: 100%;
			}

			.pymnt-cntnt div.sctn-row {
			    margin: 20px 30px 0;
			    width: 740px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col {
			    width: 345px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col.l {
			    width: 395px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col input {
			    width: 303px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col.half {
			    width: 155px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col.half.l {
			    float: left;
			    width: 190px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col.half input {
			    width: 113px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col.cvv {
			    background-image: url("./img/cvv.png");
			    background-position: 156px center;
			    background-repeat: no-repeat;
			    padding-bottom: 30px;
			}
			.pymnt-cntnt div.sctn-row div.sctn-col.cvv div.sctn-col.half input {
			    width: 110px;
			}
			.openpay {
			    float: right;
			    height: 60px;
			    margin: 10px 30px 0 0;
			    width: 435px;
			}
			.openpay div.logo {
			    background-image: url("./img/openpay.png");
			    background-position: left bottom;
			    background-repeat: no-repeat;
			    font-size: 12px;
			    font-weight: 400;
			    height: 65px;
			    padding: 15px 20px 0 0;
			}
			.openpay div.shield {
			    background-image: url("./img/security.png");
			    background-position: left bottom;
			    background-repeat: no-repeat;
			    font-size: 12px;
			    font-weight: 400;
			    margin-left: 20px;
			    padding: 20px 0 0 40px;
			    width: 200px;
			}
			.card-expl {
			    float: left;
			    height: 80px;
			    margin: 20px 0;
			    width: 800px;
			}
			.card-expl div {
			    background-position: left 45px;
			    background-repeat: no-repeat;
			    height: 70px;
			    padding-top: 10px;
			}
			.card-expl div.debit {
			    background-image: url("./img/cards2.png");
			    margin-left: 20px;
			    width: 540px;
			}
			.card-expl div.credit {
			    background-image: url("./img/cards1.png");
			    margin-left: 30px;
			    width: 209px;
			}
			.card-expl h4 {
			    font-weight: 400;
			    margin: 0;
			}
			</style>
    </head>

    <!--end::Head-->

    <!--begin::Body-->
    <body id="kt_body" class="header-fixed header-mobile-fixed aside-disabled page-loading">

        
<!--begin::Main-->

        
<!--begin::Header Mobile-->
        <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">

            <!--begin::Logo-->
            <a href="#">
                <img alt="Logo" src="img/logo.png" height="18" />
            </a>

            <!--end::Logo-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">

                <!--begin::Aside Mobile Toggle-->
                <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                    <span></span>
                </button>

                <!--end::Aside Mobile Toggle-->


                <!--end::Header Menu Mobile Toggle-->

                <!--begin::Topbar Mobile Toggle-->
                <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                    <span class="svg-icon svg-icon-xl">

                        <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>

                        <!--end::Svg Icon-->
                    </span>
                </button>

                <!--end::Topbar Mobile Toggle-->
            </div>

            <!--end::Toolbar-->
        </div>

        <!--end::Header Mobile-->
        <div class="d-flex flex-column flex-root">

            <!--begin::Page-->
            <div class="d-flex flex-row flex-column-fluid page">

                <!--begin::Wrapper-->
                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

                    
<!--begin::Header-->
                    <div id="kt_header" class="header header-fixed">

                        <!--begin::Container-->
                        <div class="container-fluid d-flex align-items-stretch justify-content-between">

                            <!--begin::Header Menu Wrapper-->
                            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper" style=" margin-top: 9px;">
                                <a href="#">
					                <img alt="Logo" src="img/logo.png" height="47" />
					            </a>
                            </div>
                            <!--end::Header Menu Wrapper-->

                            <!--begin::Topbar-->
                            <div class="topbar">

                                <!--begin::User-->
                                <div class="topbar-item">
                                    <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2">
                                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1"><i class="fa fa-user-circle mr-2"></i> Propietario</span>
                                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline"><?php echo $_SESSION['login_nombre']; ?></span>
                                    </div>
                                </div>

                                <!--end::User-->
                            </div>

                            <!--end::Topbar-->
                        </div>

                        <!--end::Container-->
                    </div>

                    <!--end::Header-->

                    <!--begin::Content-->
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                        
<!--begin::Entry-->
                        <div class="d-flex flex-column-fluid">

                            
<!--begin::Container-->
                            <div class="container-fluid">

                                <!--begin::Dashboard-->












<?php
if (isset($pagar)) {
?>
<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
				
				<div class="card-body">

					<div class="row">
						<div class="col-md-4">

			                <div class="pymnt-itm card active">
			                    <h2>Información de Pago</h2>

			                    <div style="padding: 14px 20px;"> 
			                    	<div class="row">
			                    		<div class="col-md-7">

				                    		<div class="form-group">
				                    			<label>Propietario</label>
				                    			<input type="text" class="form-control form-control-solid form-control-lg" value="<?php echo $_SESSION['login_nombre']; ?>" disabled>
				                    		</div>
				                    		<div class="form-group">
				                    			<label>Condominio</label>
				                    			<input type="text" class="form-control form-control-solid form-control-lg" value="<?php echo $_SESSION['login_condominio']; ?>" disabled>
				                    		</div>
				                    		<div class="form-group">
				                    			<label>Edificio</label>
				                    			<input type="text" class="form-control form-control-solid form-control-lg" value="LA SERANA" disabled>
				                    		</div>
				                    		<div class="form-group">
				                    			<label>Concepto</label>
				                    			<input type="text" class="form-control form-control-solid form-control-lg" value="1 CUOTA DE MANTENIMIENTO" disabled>
				                    		</div>
				                    		<div class="form-group">
				                    			<label>Importe a Pagar</label>
				                    			<input type="text" class="form-control form-control-solid form-control-lg" value="$ 250.00 USD" disabled>
				                    		</div>
				                    	
				                    	</div>
			                        </div>
			                    </div>
			                </div>
						</div>

						<div class="col-md-6">
							<div class="bkng-tb-cntnt">
						        <div class="pymnts">
						            <form action="" method="POST" id="payment-form">
						                <input type="hidden" name="token_id" id="token_id">
						                <div class="pymnt-itm card active">
						                    <h2 style="text-align: center;">Tarjeta de Crédito o Débito</h2>
						                    <div class="pymnt-cntnt">
						                        <div class="card-expl">
						                            <div class="credit"><h4>Tarjetas de crédito</h4></div>
						                            <div class="debit"><h4>Tarjetas de débito</h4></div>
						                        </div>
						                        <div class="sctn-row">
						                            <div class="sctn-col l">
														<label>Nombre del Titular</label>
														<input type="text" class="form-control form-control-lg" data-openpay-card="holder_name" placeholder="Como aparece en la tarjeta">
						                            </div>
						                            <div class="sctn-col">
						                                <label>Número de Tarjeta</label>
						                                <input type="text" class="form-control form-control-lg" data-openpay-card="card_number">
					                                </div>
					                            </div>
						                            <div class="sctn-row">
						                                <div class="sctn-col l">
						                                    <label>Fecha de expiración</label>
												            <div class="sctn-col half l"><input class="form-control form-control-lg" type="text" placeholder="Mes" data-openpay-card="expiration_month" maxlength="2"></div>
												            <div class="sctn-col half l"><input class="form-control form-control-lg" type="text" placeholder="Año" data-openpay-card="expiration_year" maxlength="2"></div>
						                                </div>
						                                <div class="sctn-col cvv"><label>Código de seguridad</label>
						                                    <div class="sctn-col half l"><input type="password" class="form-control form-control-lg" autocomplete="off" data-openpay-card="cvv2" maxlength="4"></div>
						                                </div>
						                            </div>
						                            <div class="openpay"><div class="logo">Transacciones realizadas vía:</div>
						                            <div class="shield">Tus pagos se realizan de forma segura con encriptación de 256 bits</div>
						                        </div>
						                        <div class="sctn-row">
						                        	<input type="hidden" name="id" value="<?php echo $datos['alfanumerico']; ?>" />
						                        	<input type="hidden" name="realizarPago" value="1" />
					                                <a class="btn btn-lg btn-primary" id="pay-button">Realizar Pago</a>
						                        </div>
						                    </div>
						                </div>
						            </form>
						        </div>
						    </div>
						</div>
					</div>

					


				</div>
		</div>
	</div>
</div>
<?php
} elseif (isset($rechazada)) {
?>






















<div class="row mb-12">
	<div class="col-md-12">
		<div class="card card-custom">
			<div class="card-body text-center">
				<div class="container px-40">
					<h1 style="font-weight: bold;" class="pt-10 pb-10">Cotización Rechazada por Parte de Propietario</h1>
					<h4>Gracias por el seguimiento, en base a su decisión la cotización ha sido rechazada con éxito. En caso de cambiar de opinión favor de comunicarse a nuestras oficinas para reactivar la cotización y pueda realizar su pago dentro de la vigencia de la misma.<br /><br />Ya puede cerrar esta ventana.</h4>
					<img src="<?php echo STASIS; ?>/img/guirnalda.png" width="80" class="pt-2 pb-10" />
				</div>
			</div>
		</div>
	</div>
</div>

<?php
} elseif (isset($transaccion)) {
?>

<div class="container">
	<!--begin::Invoice-->
	<div class="card card-custom position-relative overflow-hidden">
		<!--begin::Shape-->
		<div class="position-absolute opacity-30">
			<span class="svg-icon svg-icon-10x svg-logo-white">
				<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/shapes/abstract-8.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
					<g clip-path="url(#clip0)">
						<path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
						<path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
						<path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
						<path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
						<path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
						<path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
						<path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>
		<!--end::Shape-->
		<!--begin::Invoice header-->
		<div class="row justify-content-center py-8 px-8 py-md-36 px-md-0" style="background: #574B42;">
			<div class="col-md-9">
				<div class="text-center">
					<h1 class="display-3 font-weight-boldest text-white">GRACIAS POR SU PAGO</h1>
				</div>
			</div>
		</div>
		<!--end::Invoice header-->
		<div class="row justify-content-center py-8 px-8 py-md-30 px-md-0">
			<div class="col-md-9">
				<!--begin::Invoice body-->
				<div class="row pb-26">
					<div class="col-md-3 border-right-md pr-md-10 py-md-10">
						<!--begin::Invoice To-->
						<div class="text-dark-50 font-size-lg font-weight-bold mb-3">Propietario</div>
						<div class="font-size-lg font-weight-bold mb-10"><?php echo $datos['propietario']; ?></div>
						<!--end::Invoice To-->
						<!--begin::Invoice To-->
						<div class="text-dark-50 font-size-lg font-weight-bold mb-3">Lote</div>
						<div class="font-size-lg font-weight-bold mb-10"><?php echo $datos['lote']; ?></div>
						<!--begin::Invoice No-->
						<div class="text-dark-50 font-size-lg font-weight-bold mb-3">Folio de Cotización</div>
						<div class="font-size-lg font-weight-bold mb-10"><?php echo $datos['folio']; ?></div>
						<!--end::Invoice No-->
						<!--begin::Invoice Date-->
						<div class="text-dark-50 font-size-lg font-weight-bold mb-3">Fecha</div>
						<div class="font-size-lg font-weight-bold"><?php echo date('d/m/Y'); ?></div>
						<!--end::Invoice Date-->
					</div>
					<div class="col-md-9 py-10 pl-md-10">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th class="pt-1 pb-9 text-center font-weight-bolder text-muted font-size-lg text-uppercase">Cantidad</th>
										<th class="pt-1 pb-9 text-center font-weight-bolder text-muted font-size-lg text-uppercase">Unidad de Medida</th>
										<th class="pt-1 pb-9 text-center font-weight-bolder text-muted font-size-lg text-uppercase">Descripción</th>
										<th class="pt-1 pb-9 text-center font-weight-bolder text-muted font-size-lg text-uppercase">Precio Unitario</th>
										<th class="pt-1 pb-9 text-center font-weight-bolder text-muted font-size-lg text-uppercase">Total</th>
									</tr>
								</thead>
								<tbody>
									<tr class="font-weight-bolder border-bottom-0 font-size-lg">
										<td class="border-top-0 text-center"><?php echo $datos['cantidad']; ?></td>
										<td class="border-top-0 text-center"><?php echo $datos['um']; ?></td>
										<td class="border-top-0 text-center">
											<span class="navi-icon mr-2">
												<i class="fa fa-genderless text-primary font-size-h2"></i>
											</span><?php echo $datos['conceptoConcepto']; ?>
										</td>
										<td style="white-space: nowrap;" class="border-top-0 text-center"><?php echo $datos['precio']; ?></td>
										<td style="white-space: nowrap;" class="border-top-0 text-center font-size-h6 font-weight-boldest"><?php echo $datos['totalConcepto']; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!--end::Invoice body-->
				<!--begin::Invoice footer-->
				<div class="row">
					<div class="col-md-7 border-top pt-14 pb-10 pb-md-18">
						<div class="d-flex flex-column flex-md-row">
							<div class="d-flex flex-column">
								<div class="font-weight-bold font-size-h6 mb-3">DATOS DE LA TRANSACCIÓN</div>
								<div class="d-flex justify-content-between font-size-lg mb-3">
									<span class="font-weight-bold mr-15">Folio de transacción:</span>
									<span class="text-right"><?php echo $datos['openpay']['id']; ?></span>
								</div>
								<div class="d-flex justify-content-between font-size-lg mb-3">
									<span class="font-weight-bold mr-15">Fecha y hora de operación:</span>
									<span class="text-right"><?php echo $datos['openpay']['operation_date']; ?></span>
								</div>
								<div class="d-flex justify-content-between font-size-lg mb-3">
									<span class="font-weight-bold mr-15">Nombre de Titular:</span>
									<span class="text-right"><?php echo $datos['openpay']['holder_name']; ?></span>
								</div>
								<div class="d-flex justify-content-between font-size-lg mb-3">
									<span class="font-weight-bold mr-15">Tarjeta:</span>
									<span class="text-right"><?php echo $datos['openpay']['card_number']; ?></span>
								</div>
								<div class="d-flex justify-content-between font-size-lg mb-3">
									<span class="font-weight-bold mr-15">Banco:</span>
									<span class="text-right"><?php echo $datos['openpay']['bank_name']; ?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-5 pt-md-25">
						<div class="bg-primary rounded d-flex align-items-center justify-content-between text-white max-w-350px position-relative ml-auto p-7">
							<!--begin::Shape-->
							<div class="position-absolute opacity-30 top-0 right-0">
								<span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
									<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/shapes/abstract-8.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
										<g clip-path="url(#clip0)">
											<path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
											<path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
											<path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
											<path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
											<path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
											<path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
											<path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</div>
							<!--end::Shape-->
							<div class="font-weight-boldest font-size-h5 text-white">TOTAL PAGADO</div>
							<div class="text-right d-flex flex-column text-white">
								<span class="font-weight-boldest font-size-h3 text-white line-height-sm">$ <?php echo $datos['total']; ?></span>
								<span class="font-size-sm text-white">IVA incluido</span>
							</div>
						</div>
					</div>
				</div>
				<!--end::Invoice footer-->
			</div>
		</div>
		<!-- begin: Invoice action-->
		<<!-- div class="row justify-content-center border-top py-8 px-8 py-md-28 px-md-0">
			<div class="col-md-9">
				<div class="d-flex font-size-sm flex-wrap">
					<button type="button" class="btn btn-primary font-weight-bolder py-4 mr-3 mr-sm-14 my-1" onclick="window.print();">Print Invoice</button>
					<button type="button" class="btn btn-light-primary font-weight-bolder mr-3 my-1">Download</button>
					<button type="button" class="btn btn-warning font-weight-bolder ml-sm-auto my-1">Create Invoice</button>
				</div>
			</div>
		</div> -->
		<!-- end: Invoice action-->
	</div>
	<!--end::Invoice-->
</div>

<?php
}
?>



































                            </div>

                            <!--end::Container-->
                        </div>

                        <!--end::Entry-->
                    </div>

                    <!--end::Content-->
                </div>

                <!--end::Wrapper-->
            </div>

            <!--end::Page-->
        </div>

        <!--end::Main-->
        
<!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop">
            <span class="svg-icon">

                <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                        <path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>

                <!--end::Svg Icon-->
            </span>
        </div>

        <!--end::Scrolltop-->

        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/js/pages/widgets.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        
        <script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
        <script src="assets/plugins/custom/fullcalendar/locales.js"></script>

        <script>window.STASIS = '<?php echo str_replace(array("\"", "'"), array("&quot;", '&#039;'), addslashes(STASIS)); ?>';</script>
        <script src="<?php echo STASIS; ?>/js/plugins.js?<?php echo filemtime(ROOT_DIR . 'static/js/plugins.js'); ?>"></script>
        <script src="<?php echo STASIS; ?>/js/app.js?<?php echo filemtime(ROOT_DIR . 'static/js/app.js'); ?>"></script>

        <script>
            var KTAppSettings = {
                "breakpoints": {
                    "sm": 576,
                    "md": 768,
                    "lg": 992,
                    "xl": 1200,
                    "xxl": 1400
                },
                "colors": {
                    "theme": {
                        "base": {
                            "white": "#ffffff",
                            "primary": "#3699FF",
                            "secondary": "#E5EAEE",
                            "success": "#1BC5BD",
                            "info": "#8950FC",
                            "warning": "#FFA800",
                            "danger": "#F64E60",
                            "light": "#E4E6EF",
                            "dark": "#181C32"
                        },
                        "light": {
                            "white": "#ffffff",
                            "primary": "#E1F0FF",
                            "secondary": "#EBEDF3",
                            "success": "#C9F7F5",
                            "info": "#EEE5FF",
                            "warning": "#FFF4DE",
                            "danger": "#FFE2E5",
                            "light": "#F3F6F9",
                            "dark": "#D6D6E0"
                        },
                        "inverse": {
                            "white": "#ffffff",
                            "primary": "#ffffff",
                            "secondary": "#3F4254",
                            "success": "#ffffff",
                            "info": "#ffffff",
                            "warning": "#ffffff",
                            "danger": "#ffffff",
                            "light": "#464E5F",
                            "dark": "#ffffff"
                        }
                    },
                    "gray": {
                        "gray-100": "#F3F6F9",
                        "gray-200": "#EBEDF3",
                        "gray-300": "#E4E6EF",
                        "gray-400": "#D1D3E0",
                        "gray-500": "#B5B5C3",
                        "gray-600": "#7E8299",
                        "gray-700": "#5E6278",
                        "gray-800": "#3F4254",
                        "gray-900": "#181C32"
                    }
                },
                "font-family": "Poppins"
            };
        </script>
    </body>

    <!--end::Body-->
</html>