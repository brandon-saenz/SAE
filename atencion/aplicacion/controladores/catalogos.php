<?php
final class Catalogos extends Controlador {

	function empleados($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$empleados = $this->cargarModelo('catalogos_empleados');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/empleados');

		if (isset($_POST['nuevo']))
		$empleados->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$empleados->editarAplicar($_POST);

		switch ($accion) {
			case 'inactivar':
			$empleados->inactivar($id);
			break;

			case 'reactivar':
			$empleados->reactivar($id);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Empleado');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del empleado actualizada.'));
			$pagina->set('editar', 1);
			$pagina->set('info', $empleados->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Empleado');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Empleado agregado.'));
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			break;

			default:
			$pagina->set('titulo', 'Administración de Empleados');
			$pagina->set('listado', $empleados->listado());
			break;
		}

		$pagina->set('menu', 'empleados');
		$pagina->renderizar();
	}

	function clientes($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$clientes = $this->cargarModelo('catalogos_clientes');
		$presupuestos = $this->cargarModelo('catalogos_presupuestos');
		$obras = $this->cargarModelo('catalogos_obras');
		$garantias = $this->cargarModelo('catalogos_garantias');

		if ($accion == 'info') {
			$clientes->info();
			die;
		}

		if ($accion == 'inmueble') {
			$clientes->inmueble();
			die;
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/clientes');

		if (isset($_POST['nuevo']))
		$clientes->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$clientes->editarAplicar($_POST);

		if (isset($_POST['nuevoInmueble']))
		$clientes->nuevoInmueble($_POST);
		if (isset($_POST['editarAplicarInmueble']))
		$clientes->editarAplicarInmueble($_POST);

		switch ($accion) {
			case 'inactivar':
			$clientes->inactivar($id);
			break;

			case 'reactivar':
			$clientes->reactivar($id);
			break;

			case 'inactivar_inmueble':
			$clientes->inactivarInmueble($id, $status);
			break;

			case 'reactivar_inmueble':
			$clientes->reactivarInmueble($id, $status);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Cliente');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del cliente actualizada.'));
			$pagina->set('editar', 1);
			$pagina->set('info', $clientes->editar($id));
			break;

			case 'editar_inmueble':
			$pagina->set('titulo', 'Editar Inmueble');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del inmueble actualizada.'));
			$pagina->set('editarInmueble', 1);
			$pagina->set('info', $clientes->editarInmueble($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Cliente');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Cliente agregado.'));
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			break;

			case 'agregar_inmueble':
			$pagina->set('titulo', 'Nuevo Inmueble de Cliente: ' . $clientes->getNombre($id));

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Inmueble agregado.'));
			$pagina->set('nuevoInmueble', 1);
			$pagina->set('id', $id);
			break;

			case 'documentos':
			$pagina->set('titulo', 'Documentos de Cliente: ' . $clientes->getNombre($id));
			$pagina->set('listadoPresupuestos', $presupuestos->listado($id));
			$pagina->set('listadoObras', $obras->listado($id));
			$pagina->set('listadoGarantias', $garantias->listado($id));
			$pagina->set('documentos', 1);
			break;

			case 'inmuebles':
			$pagina->set('titulo', 'Inmuebles de Cliente: ' . $clientes->getNombre($id));
			$pagina->set('listadoInmuebles', $clientes->listadoInmuebles($id));
			$pagina->set('id', $id);
			$pagina->set('inmuebles', 1);
			break;

			default:
			$pagina->set('titulo', 'Administración de Clientes');
			$pagina->set('listado', $clientes->listado());
			break;
		}

		$pagina->set('menu', 'clientes');
		$pagina->renderizar();
	}

	function clientes_f($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$clientes = $this->cargarModelo('catalogos_clientesf');
		$presupuestos = $this->cargarModelo('catalogos_presupuestos');
		$obras = $this->cargarModelo('catalogos_obras');
		$garantias = $this->cargarModelo('catalogos_garantias');

		if ($accion == 'info') {
			$clientes->info();
			die;
		}

		if ($accion == 'inmueble') {
			$clientes->inmueble();
			die;
		}

		!$acceso->estaLoggeado() ? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/clientes_f');

		if (isset($_POST['nuevo']))
		$clientes->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$clientes->editarAplicar($_POST);

		if (isset($_POST['nuevoInmueble']))
		$clientes->nuevoInmueble($_POST);
		if (isset($_POST['editarAplicarInmueble']))
		$clientes->editarAplicarInmueble($_POST);

		switch ($accion) {
			case 'inactivar':
				$clientes->inactivar($id);
				break;

			case 'reactivar':
				$clientes->reactivar($id);
				break;

			case 'inactivar_inmueble':
				$clientes->inactivarInmueble($id, $status);
				break;

			case 'reactivar_inmueble':
				$clientes->reactivarInmueble($id, $status);
				break;

			case 'editar':
				$pagina->set('titulo', 'Editar Cliente');

				if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del cliente actualizada.'));
				$pagina->set('editar', 1);
				$pagina->set('info', $clientes->editar($id));
				break;

			case 'editar_inmueble':
				$pagina->set('titulo', 'Editar Inmueble');

				if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del inmueble actualizada.'));
				$pagina->set('editarInmueble', 1);
				$pagina->set('info', $clientes->editarInmueble($id));
				break;

			case 'nuevo':
				$pagina->set('titulo', 'Nuevo Cliente');

				if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Cliente agregado.'));
				$pagina->set('nuevo', 1);
				$pagina->set('agregar', 1);
				break;

			case 'agregar_inmueble':
				$pagina->set('titulo', 'Nuevo Inmueble de Cliente: ' . $clientes->getNombre($id));

				if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Inmueble agregado.'));
				$pagina->set('nuevoInmueble', 1);
				$pagina->set('id', $id);
				break;

			case 'documentos':
				$pagina->set('titulo', 'Documentos de Cliente: ' . $clientes->getNombre($id));
				$pagina->set('listadoPresupuestos', $presupuestos->listado($id));
				$pagina->set('listadoObras', $obras->listado($id));
				$pagina->set('listadoGarantias', $garantias->listado($id));
				$pagina->set('documentos', 1);
				break;

			case 'inmuebles':
				$pagina->set('titulo', 'Inmuebles de Cliente: ' . $clientes->getNombre($id));
				$pagina->set('listadoInmuebles', $clientes->listadoInmuebles($id));
				$pagina->set('id', $id);
				$pagina->set('inmuebles', 1);
				break;

			default:
				$pagina->set('titulo', 'Administración de Clientes');
				$pagina->set('listado', $clientes->listado());
				break;
		}

		$pagina->set('menu', 'clientes');
		$pagina->renderizar();
	}

	function obras($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$obras = $this->cargarModelo('catalogos_obras');
		$clientes = $this->cargarModelo('catalogos_clientes');
		$empleados = $this->cargarModelo('catalogos_empleados');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/obras');

		if (isset($_POST['nuevo']))
		$obras->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$obras->editarAplicar($_POST);

		switch ($accion) {
			case 'inactivar':
			$obras->inactivar($id);
			break;

			case 'reactivar':
			$obras->reactivar($id);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Obra');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información de la obra actualizada.'));
			$pagina->set('editar', 1);
			$pagina->set('info', $obras->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Obra');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Obra agregada.'));
			$pagina->set('listadoClientes', $clientes->listadoSelect());
			$pagina->set('listadoSupervisores', $empleados->listadoSupervisores());
			$pagina->set('listadoResponsables', $empleados->listadoResponsables());
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			break;

			default:
			$pagina->set('titulo', 'Administración de Obras');
			$pagina->set('listado', $obras->listado());
			break;
		}

		$pagina->set('menu', 'obras');
		$pagina->renderizar();
	}

	function procesos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$procesos = $this->cargarModelo('catalogos_procesos');

		if ($accion == 'superficie') {
			$procesos->procesosSuperficie();
			die;
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/procesos');

		if (isset($_POST['nuevo']))
		$procesos->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$procesos->editarAplicar($_POST);

		switch ($accion) {
			case 'inactivar':
			$procesos->inactivar($id);
			break;

			case 'reactivar':
			$procesos->reactivar($id);
			break;

			case 'exportar':
			$procesos->exportar();
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Proceso');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del proceso actualizado.'));
			$pagina->set('editar', 1);
			$pagina->set('info', $procesos->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Proceso');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Proceso agregado.'));
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			break;

			default:
			$pagina->set('titulo', 'Administración de Procesos');
			$pagina->set('listado', $procesos->listado());
			break;
		}

		$pagina->set('menu', 'procesos');
		$pagina->renderizar();
	}

	function presupuestos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$presupuestos = $this->cargarModelo('catalogos_presupuestos');
		$clientes = $this->cargarModelo('catalogos_clientes');
		$procesos = $this->cargarModelo('catalogos_procesos');
		$empleados = $this->cargarModelo('catalogos_empleados');
		$tyc = $this->cargarModelo('catalogos_tyc');

		if ($accion == 'listado_procesos') {
			$presupuestos->listadoProcesos($id);
			die;
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/presupuestos');

		if (isset($_POST['nuevo']))
		$presupuestos->nuevo($_POST);
		if (isset($_POST['editar']))
		$presupuestos->nuevo($_POST);
		if (isset($_POST['convertir']))
		$presupuestos->convertir($_POST);
		if (isset($_POST['subirFotos']))
		$presupuestos->subirFotos();
		if (isset($_POST['eliminarFoto']))
		$presupuestos->eliminarFoto($_POST);

		switch ($accion) {
			case 'inactivar':
			$presupuestos->inactivar($id);
			break;

			case 'reactivar':
			$presupuestos->reactivar($id);
			break;

			case 'pdf':
			$presupuestos->pdf($id);
			break;

			case 'descargar':
			$presupuestos->pdf($id, 1);
			break;

			case 'editar':
			$datos = $presupuestos->editar($id);
			$pagina->set('titulo', 'Editar Presupuesto');
			$pagina->set('clientsList', $clientes->listadoSelect($id, 'presupuesto'));
			$pagina->set('listadoProcesos', $procesos->listadoSelect($id, 'presupuesto'));
			$pagina->set('listadoTycs', $tyc->listadoSelect($id, 'presupuesto'));
			$pagina->set('listadoResponsables', $empleados->listadoSupervisores($id, 'presupuesto'));
			$pagina->set('nuevo', 1);
			$pagina->set('editar', 1);
			$pagina->set('datos', $datos);
			$pagina->set('listadoInmuebles', $clientes->inmueblePresupuesto($id));
			$pagina->set('folio', $datos->id);
			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Presupuesto editado.'));
			break;

			case 'convertir':
			$datos = $presupuestos->editar($id);
			$pagina->set('titulo', 'Convertir Presupuesto a Obra');
			$pagina->set('clientsList', $clientes->listadoSelect($id, 'presupuesto'));
			$pagina->set('listadoProcesos', $procesos->listadoSelect($id, 'presupuesto'));
			$pagina->set('listadoTycs', $tyc->listadoSelect($id, 'presupuesto'));
			$pagina->set('listadoResponsables', $empleados->listadoSupervisores($id, 'presupuesto'));
			$pagina->set('listadoTecnicos', $empleados->listadoResponsables());
			$pagina->set('convertir', 1);
			$pagina->set('datos', $datos);
			$pagina->set('folio', $datos->id);
			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Presupuesto convertido a obra.'));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Presupuesto');
			$pagina->set('clientsList', $clientes->listadoSelect());
			$pagina->set('listadoTycs', $tyc->listadoSelect());
			$pagina->set('listadoResponsables', $empleados->listadoSupervisores());
			$pagina->set('folio', $presupuestos->ultimoFolio());
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			if (!empty($id)) $pagina->set('status', Modelos_Sistema::status(2, 'Presupuesto agregado.'));
			break;

			case 'fotos':
			$pagina->set('titulo', 'Fotos de Presupuesto #' . $id);
			$pagina->set('fotos', 1);
			$pagina->set('datos', $presupuestos->fotos($id));
			$pagina->set('id', $id);
			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Presupuesto editado.'));
			break;

			default:
			$pagina->set('titulo', 'Administración de Presupuestos');
			$pagina->set('listado', $presupuestos->listado());
			break;
		}

		$pagina->set('menu', 'presupuestos');
		$pagina->renderizar();
	}

	function tyc($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$tyc = $this->cargarModelo('catalogos_tyc');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/tyc');

		if (isset($_POST['nuevo']))
		$tyc->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$tyc->editarAplicar($_POST);

		switch ($accion) {
			case 'inactivar':
			$tyc->inactivar($id);
			break;

			case 'reactivar':
			$tyc->reactivar($id);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Proceso');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del término/condición actualizado.'));
			$pagina->set('editar', 1);
			$pagina->set('info', $tyc->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Término/Condición');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Término/condición agregado.'));
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			break;

			default:
			$pagina->set('titulo', 'Administración de Términos y Condiciones');
			$pagina->set('listado', $tyc->listado());
			break;
		}

		$pagina->set('menu', 'tyc');
		$pagina->renderizar();
	}

	function garantias($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$garantias = $this->cargarModelo('catalogos_garantias');
		$clientes = $this->cargarModelo('catalogos_clientes');
		$empleados = $this->cargarModelo('catalogos_empleados');

		if ($accion == 'periodo') {
			$garantias->periodo();
			die;
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/garantias');

		if (isset($_POST['nuevo']))
		$garantias->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$garantias->editarAplicar($_POST);
		if (isset($_POST['subirFotos']))
		$garantias->subirFotos();
		if (isset($_POST['eliminarFoto']))
		$garantias->eliminarFoto($_POST);

		switch ($accion) {
			case 'inactivar':
			$garantias->inactivar($id);
			break;

			case 'reactivar':
			$garantias->reactivar($id);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Obra');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información de la póliza actualizada.'));
			$pagina->set('editar', 1);
			$pagina->set('info', $garantias->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Póliza de Garantía');

			$pagina->set('listadoClientes', $clientes->listadoSelect());
			$pagina->set('listadoSupervisores', $empleados->listadoSupervisores());
			$pagina->set('listadoResponsables', $empleados->listadoResponsables());
			$pagina->set('folio', $garantias->getFolio());
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			if (!empty($id)) $pagina->set('status', Modelos_Sistema::status(2, 'Póliza de garantía agregada.'));
			break;

			case 'pdf':
			$garantias->pdf($id);
			break;

			case 'fotos':
			$pagina->set('titulo', 'Fotos de Garantía #' . $id);
			$pagina->set('fotos', 1);
			$pagina->set('datos', $garantias->fotos($id));
			$pagina->set('id', $id);
			break;

			default:
			$pagina->set('titulo', 'Administración de Garantías');
			$pagina->set('listado', $garantias->listado());
			break;
		}

		$pagina->set('menu', 'garantias');
		$pagina->renderizar();
	}

	function usuarios($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$usuarios = $this->cargarModelo('catalogos_usuarios');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/usuarios');

		if (isset($_POST['nuevo']))
		$usuarios->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$usuarios->editarAplicar($_POST);

		switch ($accion) {
			case 'inactivar':
			$usuarios->inactivar($id);
			break;

			case 'reactivar':
			$usuarios->reactivar($id);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Usuario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del usuario actualizada.'));
			$pagina->set('editar', 1);
			$pagina->set('info', $usuarios->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Usuario');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Usuario agregado.'));
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			break;

			case 'privilegios':
			$pagina->set('titulo', 'Privilegios de Usuario');
			
			if(isset($_POST['editarPrivilegios']))
				$usuarios->modificarPrivilegios();

			$pagina->set('datos', $usuarios->obtenerPrivilegios($id));
			$pagina->set('nombreUsuario', $usuarios->obtenerNombre($id));
			$pagina->set('id', $id);
			$pagina->set('privilegios', 1);
			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Privilegios del usuario actualizados.'));
			break;

			default:
			$pagina->set('titulo', 'Administración de Usuarios');
			$pagina->set('listado', $usuarios->listado());
			break;
		}

		$pagina->set('menu', 'usuarios');
		$pagina->renderizar();
	}

	function facturas($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$facturacion = $this->cargarModelo('catalogos_facturacion');
		$clientesF = $this->cargarModelo('catalogos_clientesf');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/facturas');
		
		if (isset($_POST['generar']))
		$facturacion->generar();

		switch($accion) {
			// Vista final
			case 'prefactura_oc_generada':
			$pagina->set('titulo', 'Pre-factura Generada');
			$pagina->set('finalPrefacturaOrdenCompra', 1);
			break;
			// Generar un PDF solicitado desde otra página
			case 'timbrar':
			$facturacion->timbrar($id);
			break;
			// Generar un PDF solicitado desde otra página
			case 'generarZip':
			$facturacion->generarZip($id);
			break;
			// Descancelar una factura
			case 'reactivar':
			$facturacion->reactivar($id);
			break;
			// Cancelar factura
			case 'cancelar':
			$facturacion->cancelar($id);
			break;
			// Eliminar factura
			case 'inactivar':
			$facturacion->inactivar($id);
			break;
			// Generar un PDF solicitado desde otra página
			case 'pdf':
			$facturacion->pdf($id);
			break;
			// Descargar un PDF solicitado desde otra página
			case 'descargarPdfXml':
			$facturacion->descargarPdfXml($id);
			break;
			// Enviar un PDF solicitado desde otra página
			case 'enviarPdfXml':
			$facturacion->enviarPdfXml($id);
			break;
			// Visualizar PDF
			case 'visualizar':
			$facturacion->generarPdf('visualizar', $id, '', '');
			break;
			// Descargar un PDF solicitado desde otra página
			case 'descargar':
			$facturacion->generarPdf('descargar', $id, '', '');
			break;
			//Inactivar
			case 'inactivar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/clientes');
			$facturacion->inactivar($id);
			break;

			// Modificar
			case 'modificar':
			$pagina->set('titulo', "Modificar Factura #$id");
			$pagina->set('modificar', 1);
			$pagina->set('datos', $facturacion->modificar($id));
			$pagina->set('clientsList', $clientesF->listadoRfcSelect($id, 'factura'));

			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Factura editada.'));
			break;

			// Pre-Factura
			case 'nuevo':
			$pagina->set('titulo', "Nueva Factura");
			$pagina->set('preFactura', 1);
			$pagina->set('datos', $facturacion->preFactura());
			$pagina->set('clientsList', $clientesF->listadoRfcSelect());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Prefactura generada.'));
			break;

			case 'descargar_cancelacion':
			$facturacion->descargarCancelacion($id);
			break;

			// Pre-Factura vacia
			default:
			$pagina->set('titulo', 'Administración de Facturas');
			$pagina->set('datos', $facturacion->listado());
			$pagina->set('saldo', $facturacion->saldo());
			$pagina->set('estaLoggeado', $acceso->estaLoggeado());
			break;
		}
		if ($accion == 1) {
			$pagina->set('mensajes', Modelos_Sistema::status(2, 'Factura generada.'));
		}
		if (!empty($facturacion->mensajes)) $pagina->set('mensajes', $facturacion->mensajes);
		
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

	public function partes($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$partes = $this->cargarModelo('catalogos_partes');
		$usuario = $this->cargarModelo('usuario');

		if ($accion == 'actualizarLocacionCedis') {
			$partes->actualizarLocacionCedis();
			die();
		}

		if ($accion == 'checarparte') {
			$partes->checarParte();
			die();
		}

		switch ($accion) {
			case 'inactivar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/partes');
			$partes->inactivar($id);
			break;

			case 'reactivar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/partes');
			$partes->reactivar($id);
			break;

			case 'editar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista("catalogos/partes");
			$pagina->set('titulo', 'Administración de Productos y Servicios');
			
			if (isset($_POST['guardar']))
				$partes->modificarGuardar($_POST);

			$partes->modificar($id);
			$datos = $partes;

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información del producto/servicio actualizada.'));
			$pagina->set('modificar', 1);
			$pagina->set('datos', $datos);
			break;

			case 'nuevo':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista("catalogos/partes");
			$pagina->set('titulo', 'Administración de Productos y Servicios');
			
			if (isset($_POST['guardar']))
				$partes->nuevo($_POST);

			$partes->modificar(0);
			$datos = $partes;

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Producto/servicio agregado.'));
			$pagina->set('nuevo', 1);
			$pagina->set('datos', $datos);
			break;

			default:
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/partes');
			$pagina->set('titulo', 'Administración de Productos y Servicios');
			$pagina->set('datos', $partes->listado());
			break;
		}
		
		if (!empty($partes->mensajes)) $pagina->set('mensajes', $partes->mensajes);

		$pagina->set('solicitudGlobal', $usuario->verificarSolicitudGlobal($_SESSION['login_id'], 'partes'));
		$pagina->set('reporteGlobalPermitido', $usuario->verificarReporteGlobal($_SESSION['login_id'], 'partes'));
		$pagina->set('paginacion', $partes->paginacionHtml);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());

		$pagina->renderizar();
	}

	public function prodserv($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$prodserv = $this->cargarModelo('catalogos_prodserv');
		$usuario = $this->cargarModelo('usuario');

		switch ($accion) {
			case 'buscar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/prodserv');
			$pagina->set('titulo', 'Catálogo de Productos y Servicios (SAT)');

			$prodserv->buscar($_GET['b']);
			$paginacionHtml = $prodserv->paginacionHtml;

			$pagina->set('busqueda', 1);
			$pagina->set('activos', $prodserv->activos);
			$pagina->set('inactivos', $prodserv->inactivos);
			$pagina->set('paginacionHtml', $paginacionHtml);
			break;

			default:
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/prodserv');
			$pagina->set('titulo', 'Catálogo de Productos y Servicios (SAT)');
			break;
		}
		
		if (!empty($prodserv->mensajes)) $pagina->set('mensajes', $prodserv->mensajes);

		$barraEstadisticas = $this->cargarModelo('barra');
		$pagina->set('paginacion', $prodserv->paginacionHtml);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());

		$pagina->renderizar();
	}

	function boveda($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$boveda = $this->cargarModelo('catalogos_boveda');

		if ($accion == 'actualizar_status') {
			$boveda->actualizarStatus();
			die;
		}

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/boveda');

		if (isset($_POST['nuevo']))
		$boveda->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$boveda->editarAplicar($_POST);

		switch ($accion) {
			case 'inactivar':
			$boveda->inactivar($id);
			break;

			case 'reactivar':
			$boveda->reactivar($id);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Garantía en Bóveda');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Información de la garantía actualizada.'));
			$pagina->set('editar', 1);
			$pagina->set('folio', 'BV-'.$id);
			$pagina->set('info', $boveda->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nueva Póliza de Garantía en Bóveda');
			$pagina->set('folio', $boveda->getFolio());
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);
			if (!empty($id)) $pagina->set('status', Modelos_Sistema::status(2, 'Póliza de garantía agregada en bóveda.'));
			break;

			case 'pdf':
			$boveda->pdf($id);
			break;

			default:
			$pagina->set('titulo', 'Administración de Bóveda');
			$pagina->set('listado', $boveda->listado());
			break;
		}

		$pagina->set('menu', 'boveda');
		$pagina->renderizar();
	}

	function calendario($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$calendario = $this->cargarModelo('catalogos_calendario');
		$clientes = $this->cargarModelo('catalogos_clientes');
		$usuarios = $this->cargarModelo('catalogos_usuarios');
		$boveda = $this->cargarModelo('catalogos_boveda');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/calendario');

		if (isset($_POST['nuevo']))
		$calendario->nuevo($_POST);
		if (isset($_POST['editarAplicar']))
		$calendario->editarAplicar($_POST);

		switch ($accion) {
			case 'inactivar':
			$calendario->inactivar($id);
			break;

			case 'eliminar':
			$calendario->eliminar($id);
			break;

			case 'editar':
			$pagina->set('titulo', 'Editar Evento');
			$pagina->set('clientes', $clientes->listadoSelect($id, 'calendario'));
			$pagina->set('listadoResponsables', $usuarios->listadoResponsablesCalendarioCheckboxes($id));
			$pagina->set('nuevo', 1);
			$pagina->set('editar', 1);
			$pagina->set('info', $calendario->editar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Evento');
			$pagina->set('clientes', $clientes->listadoSelect());
			$pagina->set('listadoResponsables', $usuarios->listadoResponsablesCalendarioCheckboxes());
			$pagina->set('nuevo', 1);
			$pagina->set('agregar', 1);

			if (isset($id)) $pagina->set('info', $boveda->infoEvento($id));

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Evento agregado.'));
			break;
		}

		$pagina->set('menu', 'calendario');
		$pagina->renderizar();
	}

	public function pagos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$pagos = $this->cargarModelo('catalogos_pagos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('catalogos/pagos');
		if (isset($_POST['generar']))
			$pagos->generar($_POST);

		switch($accion) {
			case 'timbrar':
			$pagos->timbrar($id, '');
			break;

			case 'generarZip':
			$pagos->generarZip($id, '');

			case 'reactivar':
			$pagos->reactivar($sitio, $id);
			break;

			case 'cancelar':
			$pagos->cancelar($sitio, $id);
			break;

			case 'pdf':
			$pagos->pdf($id);
			break;

			case 'descargarPdfXml':
			$pagos->descargarPdfXml($id, '');
			break;

			case 'enviarPdfXml':
			$pagos->enviarPdfXml($id);
			break;

			case 'visualizar':
			$pagos->generarPdf('visualizar', $id, '', '');
			break;

			case 'descargar':
			$pagos->generarPdf('descargar', $id, '', '');
			break;

			case 'modificar':
			$pagina->set('titulo', "Modificar Complemento de Pago #$id");
			$pagina->set('modificar', 1);
			$pagina->set('datos', $pagos->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', "Nuevo Complemento de Pago");
			$pagina->set('preFactura', 1);
			$pagina->set('datos', $pagos->preFactura());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Complemento de pago generado.'));
			break;

			case 'administrar':
			$pagina->set('saldo', $pagos->saldo());
			$pagina->set('datos', $pagos->listado());
			$pagina->set('titulo', "Administración de Complementos de Pago");
			$pagina->set('tipoCambio', Modelos_Sistema::tipoCambio());
			break;
		}
		if ($accion == 1) {
			$pagina->set('mensajes', Modelos_Sistema::status(2, 'Factura generada.'));
		}
		if (!empty($pagos->mensajes)) $pagina->set('mensajes', $pagos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

}