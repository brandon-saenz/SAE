<?php
final class Empleados extends Controlador {

	// Colaboradores
	function colaboradores($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_colaboradores');
		$jefes = $this->cargarModelo('empleados_jefes');
		
		$departamentos = $this->cargarModelo('catalogos_departamentos');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$puestos = $this->cargarModelo('catalogos_puestos');

		if ($accion == 'info_empleado') {
			$empleados->infoEmpleado(); die();
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/colaboradores');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$empleados->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Colaborador');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del colaborador actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $empleados->modificar($id));
			$pagina->set('listadoJefes', $jefes->listadoJefes($id));
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Colaborador');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Colaborador agregado.'));

			$pagina->set('listadoJefes', $jefes->listadoJefes($id));
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Colaboradores');
			$pagina->set('listado', $empleados->listado());
			break;
		}

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu', 'empleados');
		$pagina->set('submenu', 'colaboradores');
		$pagina->renderizar();
	}

	// Jefes de Compras
	function jefes($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_jefes');
		$departamentos = $this->cargarModelo('catalogos_departamentos');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$puestos = $this->cargarModelo('catalogos_puestos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/jefes');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$empleados->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Jefe Directo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del jefe directo actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $empleados->modificar($id));
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Jefe Directo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Jefe directo agregado.'));

			$pagina->set('nuevo', 1);
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			break;

			default:
			$pagina->set('titulo', 'Jefes Directos de Compras');
			$pagina->set('listado', $empleados->listado());
			break;
		}

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu', 'empleados');
		$pagina->set('submenu', 'jefes');
		$pagina->set('submenutipo', 'compras');
		$pagina->renderizar();
	}

	// Jefes de Solicitudes
	function jdsolicitudes($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_JefesSolicitudes');
		$departamentos = $this->cargarModelo('catalogos_departamentos');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$puestos = $this->cargarModelo('catalogos_puestos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/jefe_solicitudes');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$empleados->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Jefe Directo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del jefe directo actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $empleados->modificar($id));
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Jefe Directo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Jefe directo agregado.'));

			$pagina->set('nuevo', 1);
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			break;

			default:
			$pagina->set('titulo', 'Jefes Directos de Solicitudes');
			$pagina->set('listado', $empleados->listado());
			break;
		}

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu', 'empleados');
		$pagina->set('submenu', 'jefes');
		$pagina->set('submenutipo', 'solicitudes');
		$pagina->renderizar();
	}

	// Administradores de Compras
	function evaluadores($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_evaluadores');

		$departamentos = $this->cargarModelo('catalogos_departamentos');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$puestos = $this->cargarModelo('catalogos_puestos');

		if ($accion == 'info_empleado') {
			$empleados->infoEmpleado(); die();
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/evaluadores');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$empleados->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Administrador');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del administrador actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			$pagina->set('info', $empleados->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Administrador');
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Administrador agregado.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Administradores de Compras');
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			$pagina->set('listado', $empleados->listado());
			break;
		}

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu', 'empleados');
		$pagina->set('submenu', 'evaluadores');
		$pagina->set('submenutipo', 'compras');
		$pagina->renderizar();
	}

	// Administradores de Solicitudes
	function adsolicitudes($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_AdminSolicitudes');
		$departamentos = $this->cargarModelo('catalogos_departamentos');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$puestos = $this->cargarModelo('catalogos_puestos');

		if ($accion == 'info_empleado') {
			$empleados->infoEmpleado(); die();
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/admins_solicitudes');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$empleados->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Administrador');
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del administrador actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $empleados->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Administrador');
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Administrador agregado.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Administradores de Solicitudes');
			$pagina->set('listado', $empleados->listado());
			break;
		}

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu', 'empleados');
		$pagina->set('submenu', 'evaluadores');
		$pagina->set('submenutipo', 'solicitudes');
		$pagina->renderizar();
	}

	// Vendedores
	function vendedores($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_Vendedores');
		$departamentos = $this->cargarModelo('catalogos_departamentos');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$puestos = $this->cargarModelo('catalogos_puestos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/vendedores');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$empleados->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Vendedor');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del vendedor actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $empleados->modificar($id));
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Vendedor');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Vendedor agregado.'));

			$pagina->set('nuevo', 1);
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajo($id));
			$pagina->set('listadoPuestos', $puestos->listadoPuestos($id));
			break;

			default:
			$pagina->set('titulo', 'Vendedores');
			$pagina->set('listado', $empleados->listado());
			break;
		}

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu', 'empleados');
		$pagina->set('submenu', 'vendedores');
		$pagina->renderizar();
	}

	// Ejecutivos
	function ejecutivos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('empleados_ejecutivos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('empleados/ejecutivos');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$empleados->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Ejecutivo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del ejecutivo actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $empleados->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Ejecutivo');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Ejecutivo agregado.'));

			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Ejecutivos');
			$pagina->set('listado', $empleados->listado());
			break;
		}

		if (!empty($empleados->mensajes)) $pagina->set('mensajes', $empleados->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu', 'empleados');
		$pagina->set('submenu', 'ejecutivos');
		$pagina->renderizar();
	}
	
}