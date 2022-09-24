<?php
final class Catalogos extends Controlador {

	/////////////////////
	// Administrativo //
	/////////////////////
	function centros_trabajo($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$procesos = $this->cargarModelo('movimientos_procesos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/centros_trabajo');

		if (isset($_POST['nuevo']))
		$centrosTrabajo->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$centrosTrabajo->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$centrosTrabajo->inactivar($id);
			break;

			case 'reactivar':
			$centrosTrabajo->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Centro de Trabajo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del centro de trabajo actualizado.'));

			$datos = $centrosTrabajo->modificar($id);
			$pagina->set('modificar', 1);
			$pagina->set('info', $datos);
			$pagina->set('listadoUsuariosDirector', $procesos->listadoUsuariosGlobales($datos->id_director));
			$pagina->set('listadoUsuariosComprador', $procesos->listadoUsuariosGlobales($datos->id_comprador));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Centro de Trabajo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Centro de trabajo agregado.'));

			$pagina->set('nuevo', 1);
			$pagina->set('listadoUsuarios', $procesos->listadoUsuariosGlobales());
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Centros de Trabajo');
			$pagina->set('listado', $centrosTrabajo->listado());
			break;
		}

		if (!empty($centrosTrabajo->mensajes)) $pagina->set('mensajes', $centrosTrabajo->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'administrativo');
		$pagina->set('menu3', 'centros_trabajo');
		$pagina->renderizar();
	}

	function departamentos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$departamentos = $this->cargarModelo('catalogos_departamentos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/departamentos');

		if (isset($_POST['nuevo']))
		$departamentos->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$departamentos->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$departamentos->inactivar($id);
			break;

			case 'reactivar':
			$departamentos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Departamento');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del departamento actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $departamentos->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Departamento');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Departamento agregado.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Departamentos');
			$pagina->set('listado', $departamentos->listado());
			break;
		}

		if (!empty($departamentos->mensajes)) $pagina->set('mensajes', $departamentos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'administrativo');
		$pagina->set('menu3', 'departamentos');
		$pagina->renderizar();
	}

	function puestos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$puestos = $this->cargarModelo('catalogos_puestos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/puestos');

		if (isset($_POST['nuevo']))
		$puestos->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$puestos->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$puestos->inactivar($id);
			break;

			case 'reactivar':
			$puestos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Puesto');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del puesto actualizado.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $puestos->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Puesto');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Puesto agregado.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Puestos');
			$pagina->set('listado', $puestos->listado());
			break;
		}

		if (!empty($puestos->mensajes)) $pagina->set('mensajes', $puestos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'administrativo');
		$pagina->set('menu3', 'puestos');
		$pagina->renderizar();
	}

	/////////////////////
	// Telemarketing //
	/////////////////////
	function tipificacion($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$tipificacion = $this->cargarModelo('catalogos_tipificacion');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/tipificacion');

		if (isset($_POST['nuevo']))
		$tipificacion->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$tipificacion->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$tipificacion->inactivar($id);
			break;

			case 'reactivar':
			$tipificacion->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Tipificación');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Tipificación actualizada.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $tipificacion->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Tipificación');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Tipificación agregada.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Tipificación');
			$pagina->set('listado', $tipificacion->listado());
			break;
		}

		if (!empty($tipificacion->mensajes)) $pagina->set('mensajes', $tipificacion->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'telemarketing');
		$pagina->set('menu3', 'tipificacion');
		$pagina->renderizar();
	}

	function campanas($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$campanas = $this->cargarModelo('catalogos_campanas');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/campanas');

		if (isset($_POST['nuevo']))
		$campanas->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$campanas->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$campanas->inactivar($id);
			break;

			case 'reactivar':
			$campanas->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña actualizada.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $campanas->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña agregada.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Campaña');
			$pagina->set('listado', $campanas->listado());
			break;
		}

		if (!empty($campanas->mensajes)) $pagina->set('mensajes', $campanas->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'telemarketing');
		$pagina->set('menu3', 'campanas');
		$pagina->renderizar();
	}

	/////////////////////
	// Postventa //
	/////////////////////
	function propietariosirt($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$propietarios = $this->cargarModelo('Catalogos_PropietariosIrt');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/propietarios_irt');

		if (isset($_POST['nuevo']))
		$propietarios->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$propietarios->modificarGuardar($_POST);
		if (isset($_POST['generarCc']))
		$propietarios->generarCc($_POST);

		switch ($accion) {
			case 'inactivar':
			$propietarios->inactivar($id);
			break;

			case 'reactivar':
			$propietarios->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del Propietario actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $propietarios->modificar($id));
			break;

			case 'perfil':
			$pagina->set('titulo', 'Expediente de Propietario');
			$pagina->set('expediente', 1);
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Propietario agregado.'));
			$pagina->set('nuevo', 1);
			break;

			case 'cc':
			$pagina->set('titulo', 'Adjuntar Clave Catastral');
			$pagina->set('info', $propietarios->modificar($id));
			$pagina->set('id', $id);

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Cambios aplicados.'));
			$pagina->set('cc', 1);
			break;

			case 'excel':
			$propietarios->excel();
			break;

			default:
			$pagina->set('titulo', 'Propietarios IRT');
			$pagina->set('listado', $propietarios->listado());
			break;
		}

		if (!empty($propietarios->mensajes)) $pagina->set('mensajes', $propietarios->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'propietarios');
		$pagina->set('menu4', 'irt');
		$pagina->renderizar();
	}

	function propietariosrgr($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$propietarios = $this->cargarModelo('Catalogos_PropietariosRgr');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/propietarios_rgr');

		if (isset($_POST['nuevo']))
		$propietarios->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$propietarios->modificarGuardar($_POST);
		if (isset($_POST['generarCc']))
		$propietarios->generarCc($_POST);

		switch ($accion) {
			case 'inactivar':
			$propietarios->inactivar($id);
			break;

			case 'reactivar':
			$propietarios->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del Propietario actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $propietarios->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Propietario agregado.'));
			$pagina->set('nuevo', 1);
			break;

			case 'cc':
			$pagina->set('titulo', 'Adjuntar Clave Catastral');
			$pagina->set('id', $id);

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Clave catastral adjuntada a propietario.'));
			$pagina->set('cc', 1);
			break;

			case 'excel':
			$propietarios->excel();
			break;

			default:
			$pagina->set('titulo', 'Propietarios RGR');
			$pagina->set('listado', $propietarios->listado());
			break;
		}

		if (!empty($propietarios->mensajes)) $pagina->set('mensajes', $propietarios->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'propietarios');
		$pagina->set('menu4', 'rgr');
		$pagina->renderizar();
	}

	function propietariosserena($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$propietarios = $this->cargarModelo('Catalogos_PropietariosSerena');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/propietarios_serena');

		if (isset($_POST['nuevo']))
		$propietarios->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$propietarios->modificarGuardar($_POST);
		if (isset($_POST['generarCc']))
		$propietarios->generarCc($_POST);

		switch ($accion) {
			case 'inactivar':
			$propietarios->inactivar($id);
			break;

			case 'reactivar':
			$propietarios->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del Propietario actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $propietarios->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Propietario agregado.'));
			$pagina->set('nuevo', 1);
			break;

			case 'cc':
			$pagina->set('titulo', 'Adjuntar Clave Catastral');
			$pagina->set('id', $id);

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Clave catastral adjuntada a propietario.'));
			$pagina->set('cc', 1);
			break;

			case 'excel':
			$propietarios->excel();
			break;

			case 'perfil':
			$pagina->set('titulo', 'Expediente de Propietario');
			$pagina->set('info', $propietarios->modificar($id));
			$pagina->set('expediente', 1);
			break;

			default:
			$pagina->set('titulo', 'Propietarios La Serena');
			$pagina->set('listado', $propietarios->listado());
			break;
		}

		if (!empty($propietarios->mensajes)) $pagina->set('mensajes', $propietarios->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'propietarios');
		$pagina->set('menu4', 'serena');
		$pagina->renderizar();
	}

	function cobroplan($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$cobroplan = $this->cargarModelo('Catalogos_Cobroplan');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/cobroplan');

		if (isset($_POST['nuevo']))
		$cobroplan->nuevo();
		if (isset($_POST['modificarGuardar']))
		$cobroplan->modificarGuardar();
		if (isset($_POST['generarCc']))
		$cobroplan->generarCc();

		switch ($accion) {
			case 'inactivar':
			$cobroplan->inactivar($id);
			break;

			case 'reactivar':
			$cobroplan->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del Propietario actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $cobroplan->modificar($id));
			break;

			case 'perfil':
			$pagina->set('titulo', 'Expediente de Propietario');
			$pagina->set('expediente', 1);
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Propietario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Propietario agregado.'));
			$pagina->set('nuevo', 1);
			break;

			case 'cc':
			$pagina->set('titulo', 'Adjuntar Clave Catastral');
			$pagina->set('info', $cobroplan->modificar($id));
			$pagina->set('id', $id);

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Cambios aplicados.'));
			$pagina->set('cc', 1);
			break;

			case 'excel':
			$cobroplan->excel();
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Usuarios en Cobroplan');
			$pagina->set('listado', $cobroplan->listado());
			break;
		}

		if (!empty($cobroplan->mensajes)) $pagina->set('mensajes', $cobroplan->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'cobroplan');
		$pagina->set('menu4', 'irt');
		$pagina->renderizar();
	}

	/////////////////////
	// Compras //
	/////////////////////

	function tipos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$tipos = $this->cargarModelo('catalogos_tipos');
		$centrosTrabajo = $this->cargarModelo('Catalogos_CentrosTrabajo');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/tipos');

		if (isset($_POST['nuevo']))
		$tipos->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$tipos->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$tipos->inactivar($id);
			break;

			case 'reactivar':
			$tipos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Tipo de Gasto');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del tipo de gasto actualizados.'));

			$info = $tipos->modificar($id);
			$pagina->set('modificar', 1);
			$pagina->set('listadoTipos', $centrosTrabajo->listadoCentrosTrabajoTiposGastos($info->id_unidad));
			$pagina->set('info', $info);
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Tipo de Gasto');
			$pagina->set('listadoTipos', $centrosTrabajo->listadoCentrosTrabajoTiposGastos());

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Tipo de gasto agregado.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Tipos de Gastos');
			$pagina->set('listado', $tipos->listado());
			break;
		}

		if (!empty($tipos->mensajes)) $pagina->set('mensajes', $tipos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'compras');
		$pagina->set('menu3', 'tipos');
		$pagina->renderizar();
	}

	function servicios($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$servicios = $this->cargarModelo('catalogos_servicios');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/servicios');

		if (isset($_POST['nuevo']))
		$servicios->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$servicios->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$servicios->inactivar($id);
			break;

			case 'reactivar':
			$servicios->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Servicio');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del servicio actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $servicios->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Servicio');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Servicio agregado.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Servicios');
			$pagina->set('listado', $servicios->listado());
			break;
		}

		if (!empty($servicios->mensajes)) $pagina->set('mensajes', $servicios->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'servicios');
		$pagina->renderizar();
	}

	function conceptos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$conceptos = $this->cargarModelo('catalogos_conceptos');
		$procesos = $this->cargarModelo('movimientos_procesos');

		if ($accion == 'info_concepto') {
			$conceptos->infoConcepto(); die();
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/conceptos');

		if (isset($_POST['nuevo']))
		$conceptos->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$conceptos->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$conceptos->inactivar($id);
			break;

			case 'reactivar':
			$conceptos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Servicio');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del servicio actualizados.'));

			$datos = $conceptos->modificar($id);
			$pagina->set('modificar', 1);
			$pagina->set('info', $datos);
			$pagina->set('listadoUsuariosGlobales', $procesos->listadoUsuariosGlobales($datos->id_responsable));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Servicio');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Servicio agregado.'));

			$pagina->set('nuevo', 1);
			$pagina->set('listadoUsuariosGlobales', $procesos->listadoUsuariosGlobales());
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Conceptos');
			$pagina->set('listado', $conceptos->listado());
			break;
		}

		if (!empty($conceptos->mensajes)) $pagina->set('mensajes', $conceptos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'conceptos');
		$pagina->renderizar();
	}

	function proveedores($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$proveedores = $this->cargarModelo('Catalogos_Proveedores');
		$procesos = $this->cargarModelo('Movimientos_Procesos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/proveedores');

		if (isset($_POST['revisionInformacion']))
		$proveedores->revisionInformacion();
		if (isset($_POST['autorizarProveedor']))
		$proveedores->autorizar();

		switch ($accion) {
			case 'inactivar':
			$proveedores->inactivar($id);
			break;

			case 'reactivar':
			$proveedores->reactivar($id);
			break;

			case 'aprobar':
			$proveedores->aprobar($id);
			break;

			// case 'autorizar':
			// $proveedores->autorizar($id);
			// if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Comentario enviado por correo.'));
			// break;
			case 'autorizar':
			$pagina->set('titulo', 'Autorizar Proveedor');
			$pagina->set('info', $proveedores->getInformacion($id));
			$pagina->set('autorizar', 1);
			$pagina->set('listadoUsuarios', $procesos->listadoUsuariosGlobales());
			break;

			case 'revision':
			$pagina->set('titulo', 'Revisión de Información');
			$pagina->set('info', $proveedores->getInformacion($id));
			$pagina->set('revision', 1);

			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Correo enviado a proveedor.'));
			break;

			case 'excel':
			$proveedores->excel($id);
			break;

			default:
			$pagina->set('titulo', 'Proveedores');
			$pagina->set('listado', $proveedores->listado());

			if ($accion == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Proveedor aprobado.'));
			if ($accion == 2) $pagina->set('status', Modelos_Sistema::status(2, 'Proveedor autorizado.'));
			break;
		}

		if (!empty($proveedores->mensajes)) $pagina->set('mensajes', $proveedores->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'proveedores');
		$pagina->renderizar();
	}

	function facturas($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$facturas = $this->cargarModelo('Catalogos_Facturas');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/facturas');

		switch ($accion) {
			default:
			$pagina->set('titulo', 'Listado de Facturas de Proveedores');
			$pagina->set('listado', $facturas->listado());
			break;
		}

		if (!empty($facturas->mensajes)) $pagina->set('mensajes', $facturas->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'facturas');
		$pagina->renderizar();
	}

	function inventario($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$inventario = $this->cargarModelo('catalogos_inventario');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/inventario');

		if (isset($_POST['nuevo']))
		$inventario->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$inventario->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$inventario->inactivar($id);
			break;

			case 'reactivar':
			$inventario->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña actualizada.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $inventario->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña agregada.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Inventario de Terrenos');
			$pagina->set('listado', $inventario->listado());
			break;
		}

		if (!empty($inventario->mensajes)) $pagina->set('mensajes', $inventario->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'inventario');
		$pagina->renderizar();
	}

	function contratos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$contratos = $this->cargarModelo('catalogos_contratos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/contratos');

		if (isset($_POST['nuevo']))
		$contratos->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$contratos->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$contratos->inactivar($id);
			break;

			case 'reactivar':
			$contratos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña actualizada.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $contratos->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña agregada.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Contratos');
			$pagina->set('listado', $contratos->listado());
			break;
		}

		if (!empty($contratos->mensajes)) $pagina->set('mensajes', $contratos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'contratos');
		$pagina->renderizar();
	}

	function arrendatarios($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$arrendatarios = $this->cargarModelo('catalogos_arrendatarios');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/arrendatarios');

		if (isset($_POST['nuevo']))
		$arrendatarios->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$arrendatarios->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$arrendatarios->inactivar($id);
			break;

			case 'reactivar':
			$arrendatarios->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña actualizada.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $arrendatarios->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Campaña');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Campaña agregada.'));

			$pagina->set('nuevo', 1);
			break;

			case 'cuotas':
			$pagina->set('titulo', 'Cuotas de Mantenimiento');
			$pagina->set('listado', $arrendatarios->cuotas($id));
			$pagina->set('cuotas', 1);
			break;

			case 'amortizacion':
			$pagina->set('titulo', 'Tabla de Amortización');
			$pagina->set('listado', $arrendatarios->amortizacion($id));
			$pagina->set('amortizacion', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Arrendatarios');
			$pagina->set('listado', $arrendatarios->listado());
			break;
		}

		if (!empty($inventario->mensajes)) $pagina->set('mensajes', $inventario->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'catalogos');
		$pagina->set('menu2', 'postventa');
		$pagina->set('menu3', 'arrendatarios');
		$pagina->renderizar();
	}

}