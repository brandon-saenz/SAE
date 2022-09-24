<?php
class Perfil extends Controlador {
	
	public function datos($accion = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('perfil');

		$proveedor = $this->cargarModelo('proveedor');
		
		if (isset($_POST['actualizarDatos']))
		$proveedor->actualizarDatosPrincipales();
		if (isset($_POST['actualizarReferencias']))
		$proveedor->actualizarReferencias();
		if (isset($_POST['actualizarCertificaciones']))
		$proveedor->actualizarCertificaciones();
		if (isset($_POST['actualizarCsf']))
		$proveedor->actualizarCsf();
		if (isset($_POST['actualizarCdd']))
		$proveedor->actualizarCdd();
		if (isset($_POST['actualizarEdocta']))
		$proveedor->actualizarEdocta();
		if (isset($_POST['actualizarOpcs']))
		$proveedor->actualizarOpcs();
		if (isset($_POST['actualizarCe']))
		$proveedor->actualizarCe();
		if (isset($_POST['actualizarLogo']))
		$proveedor->actualizarLogo();

		if (isset($_POST['actualizarAc']))
		$proveedor->actualizarAc();
		if (isset($_POST['actualizarPnrl']))
		$proveedor->actualizarPnrl();
		if (isset($_POST['actualizarIorl']))
		$proveedor->actualizarIorl();
		if (isset($_POST['actualizarUpp']))
		$proveedor->actualizarUpp();
		if (isset($_POST['actualizarEoss']))
		$proveedor->actualizarEoss();
		if (isset($_POST['actualizarPep']))
		$proveedor->actualizarPep();

		if (isset($_POST['actualizarIne']))
		$proveedor->actualizarIne();

		switch($accion) {
			case 'principales':
			$pagina->set('titulo', "Datos Principales de Proveedor");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('principales', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Datos principales actualizados.'));
			break;

			case 'referencias':
			$pagina->set('titulo', "Referencias Comerciales");
			$pagina->set('datos', $proveedor->referencias());
			$pagina->set('referencias', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Referencias comerciales actualizadas.'));
			break;

			case 'certificaciones':
			$pagina->set('titulo', "Certificaciones");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('certificaciones', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Información de certificaciones actualizada.'));
			break;

			case 'csf':
			$pagina->set('titulo', "Subir archivo de Constancia de Situación Fiscal");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('csf', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'cdd':
			$pagina->set('titulo', "Subir archivo de Comprobante de Domicilio");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('cdd', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;
			case 'edocta':
			$pagina->set('titulo', "Subir archivo de Estado de Cuenta Bancario");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('edocta', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Cambios aplicados.'));
			break;
			case 'opcs':
			$pagina->set('titulo', "Subir archivo de Opinión Positiva del Cumplimiento");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('opcs', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'ce':
			$pagina->set('titulo', "Aceptar de conformidad el Código de Ética");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('ce', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Cambios aplicados.'));
			break;

			case 'logo':
			$pagina->set('titulo', "Cargar logo de la empresa");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('logo', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'carga':
			$pagina->set('titulo', "carga");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('carga', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Carga de .'));
			break;

			case 'ac':
			$pagina->set('titulo', "Subir archivo de Acta Constitutiva - Sociedades Anónimas o SA CV");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('ac', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'pnrl':
			$pagina->set('titulo', "Subir archivo del Poder Notarial del Representante Legal");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('pnrl', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'iorl':
			$pagina->set('titulo', "Subir archivo de la Identificación Oficial del Representante Legal");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('iorl', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'upp':
			$pagina->set('titulo', "Subir archivo del último pago provisional ISR");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('upp', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'eoss':
			$pagina->set('titulo', "Subir archivo del comprobante de pago al seguro social");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('eoss', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'pep':
			$pagina->set('titulo', "Subir archivo del la notificación a proveedores y evaluación de proveedores");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('pep', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			case 'ine':
			$pagina->set('titulo', "Subir archivo de Identificación Oficial");
			$pagina->set('datos', $proveedor->datosPrincipales());
			$pagina->set('ine', 1);
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Archivo subido.'));
			break;

			default:
			$pagina->set('titulo', 'Generar Requisición');
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajoHtml($id));
			$pagina->set('listadoTipos', $tipos->listadoTipos());
			$pagina->set('datos', $proveedor->nueva());
			$pagina->set('menu', 'nueva');
			$pagina->set('nuevo', 1);
			$datos = $proveedor->nueva();
			break;
		}

		if (!empty($proveedor->mensajes)) $pagina->set('mensajes', $proveedor->mensajes);
		$pagina->renderizar();
	}

	public function pdf($id = null) {
		$proveedor = $this->cargarModelo('proveedor');
		$proveedor->descargarPdf($id);
	}

	public function carga() {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('perfil');
		$proveedor = $this->cargarModelo('proveedor');

		if (isset($_POST['subirPdfXml']))
		$proveedor->subirPdfXml();

		$pagina->set('titulo', "Carga de PDF y XML");
		$pagina->set('ordenesCompra', $proveedor->ordenesCompra($_GET['ids']));
		$pagina->set('carga', 1);

		if (!empty($proveedor->mensajes)) $pagina->set('mensajes', $proveedor->mensajes);
		$pagina->renderizar();
	}

}