<?php
final class Empleados extends Controlador {

	// Administrar
	function administrar($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_administrar');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/administrar');

		switch ($accion) {
			case 'inactivar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/administrar');
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/administrar');
			$empleados->reactivar($id);
			break;

			case 'modificar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista("empleados/administrar");
			$pagina->set('titulo', 'Catálogo de Empleados');
			
			if (isset($_POST['guardarCambios']))
				$empleados->modificarGuardar($_POST);

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del empleado actualizados.'));
			$pagina->set('modificar', 1);
			$pagina->set('datos', $empleados->modificar($id));
			break;

			case 'nuevo':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista("empleados/administrar");
			$pagina->set('titulo', 'Catálogo de Empleados');
			
			if (isset($_POST['nuevo']))
				$empleados->nuevo($_POST);

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Empleado agregado.'));
			$pagina->set('nuevo', 1);
			break;

			case 'privilegios':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista("empleados/administrar");
			$pagina->set('titulo', 'Catálogo de Empleados');
			
			if(isset($_POST['guardar']))
				$privilegios->modificar($idEmpleado, $_POST);

			$datos = $privilegios->obtenerNombre($idEmpleado);
			$nombre = $privilegios->nombre;
			$apellidos = $privilegios->apellidos;
			$privilegios->obtenerPrivilegios($idEmpleado);
			$pagina->set('integraciones', $privilegios->integraciones);
			$pagina->set('integracionesSubmodulos', $privilegios->integracionesSubmodulos);
			// $pagina->set('privilegios', $privilegios->privilegios);
			$pagina->set('titulo', "Privilegios de Empleados [$nombre $apellidos]");

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Empleado agregado.'));
			$pagina->set('privilegios', 1);
			break;

			default:
			$pagina->set('titulo', 'Catálogo de Empleados');
			$empleados->obtenerDatos();
			$activos = $empleados->activos;
			$inactivos = $empleados->inactivos;
			$pagina->set('activos', $activos);
			$pagina->set('inactivos', $inactivos);
			break;
		}

			// if(isset($_POST['guardar']))
			// 	$empleados->modificar($idEmpleado, $_POST);

			// $datos = $empleados->obtenerNombre($idEmpleado);
			// $nombre = $empleados->nombre;
			// $apellidos = $empleados->apellidos;
			// $empleados->obtenerempleados($idEmpleado);
			// $pagina->set('integraciones', $empleados->integraciones);
			// $pagina->set('integracionesSubmodulos', $empleados->integracionesSubmodulos);
			// $pagina->set('empleados', $empleados->empleados);
			// $pagina->set('titulo', "empleados de Empleados [$nombre $apellidos]");

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$barraEstadisticas = $this->cargarModelo('barra');
		$pagina->set('id', $id);
		$pagina->set('barraEstadisticas', $barraEstadisticas->contenido($acceso->estaLoggeado()));
		$pagina->set('skinFondo', $acceso->skinFondo);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

	function agregar($status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleado = $this->cargarModelo('empleado');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados');

		$pagina->set('titulo', 'Agregar Empleado');

		if (isset($_POST['agregar'])) $empleado->agregar();
		if($status == 1) $empleado->mensajes = Modelos_Sistema::status(2, 'El empleado ha sido agregado.');
		if (!empty($empleado->mensajes)) $pagina->set('mensajes', $empleado->mensajes);

		$pagina->set('listadoDepartamentos', $empleado->listadoDepartamentos());
		$pagina->set('listadoProcedimientos', $empleado->checkboxesProcedimientos());
		$pagina->set('agregar', 1);
		$pagina->renderizar();
	}

	function subir_descriptivo_puestos() {
		$empleado = $this->cargarModelo('empleado');
		$empleado->subirDescriptivoPuestos();
	}

	function subir_expediente() {
		$empleado = $this->cargarModelo('empleado');
		$empleado->subirExpediente();
	}

	function inactivate($id) {
		$user = $this->cargarModelo('user');
		$user->inactivate($id);
	}

	function reactivate($id) {
		$user = $this->cargarModelo('user');
		$user->reactivate($id);
	}

	function edit($id, $status = null) {
		$pagina = $this->cargarVista('users');
		$user = $this->cargarModelo('user');
		$center = $this->cargarModelo('center');
		$campaign = $this->cargarModelo('campaign');

		if (isset($_POST['edit'])) $user->applyChanges();

		$user->edit($id);
		$pagina->set('info', $user);
		$pagina->set('edit', 1);

		$pagina->set('centers', $center->getCenters());
		$pagina->set('campaigns', $campaign->getCampaigns());
		$pagina->set('status', $campaign->getStatus());
		$pagina->set('levels', $user->getLevels());
		$pagina->set('recruiters', $user->getUsersByLevel(9));

		if($status == 1) $user->mensajes = Modelos_Sistema::status(2, 'Changes applied.');

		if (!empty($user->mensajes)) $pagina->set('mensajes', $user->mensajes);
		$pagina->set('jsFileScript', 'users.js');
		$pagina->set('active', 'Users');
		$pagina->renderizar();
	}

	function picture($id, $status = null) {
		$pagina = $this->cargarVista('users');
		$user = $this->cargarModelo('user');

		$user->edit($id);
		$pagina->set('info', $user);
		$pagina->set('picture', 1);

		if (!empty($user->mensajes)) $pagina->set('mensajes', $user->mensajes);
		$pagina->set('jsFileScript', 'users.js');
		$pagina->set('active', 'Users');
		$pagina->renderizar();
	}

	function upload($id) {
		$user = $this->cargarModelo('user');
		return $user->uploadPicture($id);
	}
}