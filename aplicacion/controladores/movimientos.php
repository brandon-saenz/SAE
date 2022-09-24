<?php
class Movimientos extends Controlador {
	
	public function compras($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/compras');
		$compras = $this->cargarModelo('movimientos_compras');
		$departamentos = $this->cargarModelo('catalogos_departamentos');
		$centrosTrabajo = $this->cargarModelo('catalogos_centrostrabajo');
		$tipos = $this->cargarModelo('catalogos_tipos');
		$proveedores = $this->cargarModelo('catalogos_proveedores');
		
		// Botones de form
		if (isset($_POST['generar']))
		$compras->generar($_POST);
		if (isset($_POST['generarModificar']))
		$compras->generarModificar($_POST);
		if (isset($_POST['procesar']))
		$compras->procesar($_POST);
		if (isset($_POST['rechazar']))
		$compras->rechazar($_POST);
		if (isset($_POST['procesarMultiples']))
		$compras->procesarMultiples($_POST);
		if (isset($_POST['recibirMultiples']))
		$compras->recibirMultiples($_POST);
		if (isset($_POST['entregarMultiples']))
		$compras->entregarMultiples($_POST);
		if (isset($_POST['procesarAplicarCambios']))
		$compras->procesarAplicarCambios($_POST);

		switch($accion) {
			// Generar un PDF solicitado desde otra página
			case 'pdf':
			$compras->pdf($id);
			break;
			// Descargar un PDF solicitado desde otra página
			case 'descargar':
			$compras->pdf($id, 1);
			break;
			// Visualizar PDF
			case 'visualizar':
			$compras->visualizar($id);
			break;
			// Visualizar PDF
			case 'visualizar_parte':
			$compras->visualizarParte($id);
			break;

			case 'visualizar_recibida':
			$compras->visualizarRecibida($id);
			break;
			// Convertir a orden de compra
			case 'convertir':
			$compras->convertir($id);
			break;
			
			case 'excel':
			$compras->excel();
			break;

			case 'excel_global':
			$compras->excelGlobal();
			break;

			case 'cancelar_parte':
			$compras->cancelarParte($id);
			break;

			case 'cancelar_requisicion':
			$compras->cancelarRequisicion($id);
			break;

			// Autorizar
			case 'autorizar':
			$compras->autorizar($id);
			break;

			// Recibir
			case 'recibir':
			$compras->recibir($id);
			break;

			// Recibir
			case 'entregar':
			$compras->entregar($id);
			break;

			// Modificar
			case 'modificar':
			$pagina->set('titulo', "Editar Requisición #$id");
			$pagina->set('editar', 1);
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajoHtml($id));
			$pagina->set('datos', $compras->modificar($id));
			$pagina->set('listadoTipos', $tipos->listadoTipos());
			$pagina->set('menu', 'historial');
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición actualizada.'));
			break;

			// Si no hay accion, mostrar vista para crear una cotizacion nueva
			case 'historial':
			$pagina->set('titulo', 'Reporte de Requisiciones');
			$pagina->set('historial', 1);
			$pagina->set('menu', 'historial');
			$pagina->set('listado', $compras->historial());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición autorizada.'));
			if ($id == 2) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición en proceso.'));
			if ($id == 3) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición recibida.'));
			if ($id == 4) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición rechazada.'));
			if ($id == 5) $pagina->set('status', Modelos_Sistema::status(2, 'Producto/servicio cancelado.'));
			if ($id == 6) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición cancelada.'));
			if ($id == 7) $pagina->set('status', Modelos_Sistema::status(2, 'Producto/servicio entregado.'));
			if ($id == 8) $pagina->set('status', Modelos_Sistema::status(2, 'Cambios aplicados en requisición.'));
			break;

			// Procesar
			case 'procesar':
			$datos = $compras->modificar($id);
			$pagina->set('titulo', "Procesar Requisición #" . $datos['id_requisicion']);
			$pagina->set('procesar', 1);
			$pagina->set('listadoProveedores', $proveedores->listadoProveedores());
			$pagina->set('listadoCuentasContables', $tipos->listadoCuentasContables());
			$pagina->set('datos', $datos);
			$pagina->set('menu', 'historial');
			break;

			// Procesar
			case 'editar_procesar':
			$datos = $compras->modificar($id);
			$pagina->set('titulo', "Editar Requisición #" . $datos['id_requisicion']);
			$pagina->set('editarProcesar', 1);
			$pagina->set('listadoProveedores', $proveedores->listadoProveedores($datos['id_proveedor']));
			$pagina->set('listadoCuentasContables', $tipos->listadoCuentasContables($datos['cuenta_contable']));
			$pagina->set('datos', $datos);
			$pagina->set('menu', 'historial');
			break;

			// Procesar Multiple
			case 'procesar_multiple':
			$datos = $compras->modificar($id);
			$pagina->set('titulo', "Procesar Múltiples Requisiciones");
			$pagina->set('procesarMultiple', 1);
			$pagina->set('listadoProveedores', $proveedores->listadoProveedores());
			$pagina->set('datos', $datos);
			$pagina->set('menu', 'historial');
			break;

			// Modificar
			case 'rechazar':
			$pagina->set('titulo', "Rechazar Requisición #$id");
			$pagina->set('id', $id);
			$pagina->set('menu', 'historial');
			$pagina->set('rechazar', 1);
			break;

			// Si no hay accion, mostrar vista para crear una cotizacion nueva
			default:
			$pagina->set('titulo', 'Generar Requisición');
			$pagina->set('listadoDepartamentos', $departamentos->listadoDepartamentos($id));
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajoHtml($id));
			$pagina->set('listadoTipos', $tipos->listadoTipos());
			$pagina->set('datos', $compras->nueva());
			$pagina->set('menu', 'nueva');
			$pagina->set('nuevo', 1);
			$datos = $compras->nueva();
			break;
		}
		if ($accion == 1) {
			$pagina->set('status', Modelos_Sistema::status(2, 'Requisición generada.'));
		}
		if (!empty($compras->mensajes)) $pagina->set('mensajes', $compras->mensajes);
		//
		$pagina->renderizar();
	}

	public function solicitudes($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$solicitudes = $this->cargarModelo('movimientos_solicitudes');
		$jefesDirectos = $this->cargarModelo('empleados_JefesSolicitudes');
		
		// Botones de form
		if (isset($_POST['autorizar']))
		$solicitudes->autorizar();
		if (isset($_POST['procesar']))
		$solicitudes->procesar();
		if (isset($_POST['rechazar']))
		$solicitudes->rechazar();
		if (isset($_POST['generarComentario']))
		$solicitudes->comentario();
		if (isset($_POST['finalizar']))
		$solicitudes->finalizar();
		if (isset($_POST['cerrar']))
		$solicitudes->cerrar();

		switch($accion) {
			// Generar un PDF solicitado desde otra página
			case 'pdf':
			$solicitudes->pdf($id);
			break;
			// Descargar un PDF solicitado desde otra página
			case 'descargar':
			$solicitudes->pdf($id, 1);
			break;
			// Visualizar PDF
			case 'visualizar':
			$solicitudes->visualizar($id);
			break;
			// Visualizar PDF
			case 'visualizar_parte':
			$solicitudes->visualizarParte($id);
			break;

			case 'visualizar_recibida':
			$solicitudes->visualizarRecibida($id);
			break;
			// Convertir a orden de compra
			case 'convertir':
			$solicitudes->convertir($id);
			break;
			
			case 'excel':
			$solicitudes->excel();
			break;

			case 'eliminar':
			$solicitudes->eliminar($id);
			break;
			
			case 'cancelar_requisicion':
			$solicitudes->cancelarRequisicion($id);
			break;

			// Finalizar
			case 'finalizar':
			$solicitudes->finalizar($id);
			break;

			// Liberar
			case 'liberar':
			$solicitudes->liberar($id);
			break;

			// Modificar
			case 'modificar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/solicitudes');
			$pagina->set('titulo', "Editar Requisición #$id");
			$pagina->set('editar', 1);
			$pagina->set('listadoCentrosTrabajo', $centrosTrabajo->listadoCentrosTrabajoHtml($id));
			$pagina->set('datos', $solicitudes->modificar($id));
			$pagina->set('listadoTipos', $tipos->listadoTipos());
			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición actualizada.'));
			break;

			// Modificar
			case 'rechazar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/solicitudes');
			$pagina->set('titulo', "Rechazar Requisición #$id");
			$pagina->set('id', $id);
			$pagina->set('rechazar', 1);
			break;

			// Cerrar
			case 'cerrar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/solicitudes');
			$pagina->set('titulo', 'Cerrar Solicitud');
			$pagina->set('cerrar', 1);
			$pagina->set('datos', $solicitudes->modificar($id));
			break;

			// Comentarios
			case 'comentario':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/solicitudes');
			$pagina->set('titulo', 'Agregar Comentario');
			$pagina->set('comentario', 1);
			$pagina->set('datos', $solicitudes->modificar($id));
			break;

			// Fecha
			case 'fecha':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/solicitudes');
			$pagina->set('titulo', 'Especificar Fecha Estimada de Entrega');
			$pagina->set('fecha', 1);
			$pagina->set('datos', $solicitudes->modificar($id));
			break;

			// Autorizar
			case 'autorizar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/solicitudes');
			$pagina->set('titulo', 'Autorizar Solicitud');
			$pagina->set('autorizar', 1);
			$pagina->set('datos', $solicitudes->modificar($id));
			$pagina->set('listadoJefes', $jefesDirectos->listadoJefes());
			$pagina->set('listadoCobranza', $jefesDirectos->listadoCobranza());
			break; 

			// Reporte 
			case 'reporte':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/solicitudes');
			$pagina->set('titulo', 'Reporte de Solicitudes de Propietarios');
			$pagina->set('listado', $solicitudes->listado());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud autorizada.'));
			if ($id == 2) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud en proceso.'));
			if ($id == 3) $pagina->set('status', Modelos_Sistema::status(2, 'Comentario agregado en solicitud.'));
			if ($id == 4) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud marcada como finalizada.'));
			if ($id == 5) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud cerrada y marcada como atendida.'));
			if ($id == 6) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud en status de <b>en revisión</b>.'));
			if ($id == 7) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud liberada.'));
			break;
		}
		
		if (!empty($solicitudes->mensajes)) $pagina->set('mensajes', $solicitudes->mensajes);
		$pagina->set('menu', 'solicitudes');
		$pagina->renderizar();
	}

	public function ventas($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/ventas');
		$ventas = $this->cargarModelo('movimientos_ventas');
		
		// Botones de form
		if (isset($_POST['generar']))
		$ventas->generar($_POST);

		switch($accion) {
			// Generar un PDF solicitado desde otra página
			case 'pdf':
			$ventas->pdf($id);
			break;

			// Descargar un PDF solicitado desde otra página
			case 'descargar':
			$ventas->pdf($id, 1);
			break;

			// Visualizar PDF
			case 'visualizar':
			$ventas->visualizar($id);
			break;

			// Si no hay accion, mostrar vista para crear una cotizacion nueva
			case 'historial':
			$pagina->set('titulo', 'Reporte de Beneficios de Compra');
			$pagina->set('historial', 1);
			$pagina->set('menu', 'historialPromesa');
			$pagina->set('listado', $ventas->historial());
			// if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Requisición autorizada.'));
			break;

			// Si no hay accion, mostrar vista para crear una cotizacion nueva
			default:
			$pagina->set('titulo', 'Generar Beneficio de Compra');
			$pagina->set('datos', $ventas->nueva());
			$pagina->set('listadoPropietarios', $ventas->listadoPropietarios());
			$pagina->set('menu', 'nuevaPromesa');
			$pagina->set('nuevo', 1);
			$datos = $ventas->nueva();
			break;
		}
		if ($accion == 1) {
			$pagina->set('status', Modelos_Sistema::status(2, 'Beneficio de compra generado.'));
		}
		if (!empty($ventas->mensajes)) $pagina->set('mensajes', $ventas->mensajes);
		//
		$pagina->renderizar();
	}

	public function evaluaciones() {
		$acceso = $this->cargarModelo('acceso');
		$evaluaciones = $this->cargarModelo('movimientos_evaluaciones');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/evaluaciones');
		$pagina->set('titulo', 'Reporte de Evaluaciones');
		$pagina->set('listado', $evaluaciones->listado());
		$pagina->set('menu', 'evaluaciones');
		
		$pagina->renderizar();
	}

	public function qys() {
		$acceso = $this->cargarModelo('acceso');
		$qys = $this->cargarModelo('movimientos_qys');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/qys');
		$pagina->set('titulo', 'Reporte de qys');
		$pagina->set('listado', $qys->listado());
		$pagina->set('menu', 'qys');
		
		$pagina->renderizar();
	}

	public function cotizaciones($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$cotizaciones = $this->cargarModelo('movimientos_cotizaciones');
		$conceptos = $this->cargarModelo('catalogos_conceptos');

		if ($accion == 'info_propietario') {
			$cotizaciones->infoPropietario(); die();
		}
		if ($accion == 'nuevo_cliente') {
			$cotizaciones->nuevoCliente(); die();
		}

		// Botones de form
		if (isset($_POST['generar']))
		$cotizaciones->generar();
		if (isset($_POST['aplicarCambiosPago']))
		$cotizaciones->aplicarCambiosPago();

		switch($accion) {
			case 'generar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/cotizaciones');
			$pagina->set('titulo', 'Generar Cotización');
			$pagina->set('generar', 1);
			$pagina->set('listadoPropietarios', $cotizaciones->listadoPropietarios());
			$pagina->set('listadoServicios', $conceptos->listadoServiciosNombre());
			$pagina->set('datos', $cotizaciones->nueva());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Cotización generada.'));

			$pagina->set('menu1', 'postventa');
			$pagina->set('menu2', 'cotizaciones');
			$pagina->set('menu3', 'generarCotizacion');
			break;

			// Generadas
			case 'reporte':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/cotizaciones');
			$pagina->set('titulo', 'Reporte de Cotizaciones');
			$pagina->set('listado', $cotizaciones->listado());
			$pagina->set('menu', 'cotizaciones');

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Notificación enviada a propietario.'));
			if ($id == 2) $pagina->set('status', Modelos_Sistema::status(2, 'Cotización cancelada.'));
			if ($id == 3) $pagina->set('status', Modelos_Sistema::status(2, 'Cotización aceptada por parte del cliente.'));
			if ($id == 4) $pagina->set('status', Modelos_Sistema::status(2, 'Cotización reactivada.'));

			$pagina->set('menu1', 'postventa');
			$pagina->set('menu2', 'cotizaciones');
			$pagina->set('menu3', 'reporteCotizaciones');
			break;

			case 'visualizar':
			$cotizaciones->visualizar($id);
			break;

			case 'enviar':
			$cotizaciones->enviar($id);
			break;

			case 'cancelar':
			$cotizaciones->cancelar($id);
			break;

			case 'aceptar':
			$cotizaciones->aceptar($id);
			break;

			case 'reactivar':
			$cotizaciones->reactivar($id);
			break;

			case 'factura':
			$cotizaciones->visualizarFactura($id);
			die;
			break;

			// Pagar en Efectivo
			case 'pagare':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/cotizaciones');
			$pagina->set('titulo', 'Pagar Cotización en Efectivo');
			$pagina->set('datos', $cotizaciones->modificar($id));
			$pagina->set('pagare', 1);

			$pagina->set('menu1', 'postventa');
			$pagina->set('menu2', 'cotizaciones');
			$pagina->set('menu3', 'reporteCotizaciones');
			break;

			// Referencia Bancaria
			case 'referenciab':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/cotizaciones');

			$cotizaciones->referenciaBancaria($id);

			$pagina->set('titulo', 'Pagar Cotización con Referencia Bancaria');
			$pagina->set('datos', $cotizaciones->modificar($id));
			$pagina->set('pagare', 1);

			$pagina->set('menu1', 'postventa');
			$pagina->set('menu2', 'cotizaciones');
			$pagina->set('menu3', 'reporteCotizaciones');
			break;

			case 'pagar':
			// $pagina = $this->cargarVista('movimientos/cotizaciones');
			// $pagina->set('titulo', 'Generar Cotización');
			// $pagina->set('generar', 1);
			// $pagina->set('listadoPropietarios', $cotizaciones->listadoPropietarios());
			// $pagina->set('datos', $cotizaciones->nueva());

			// if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Cotización generada.'));

			// $pagina->set('menu1', 'postventa');
			// $pagina->set('menu2', 'cotizaciones');
			// $pagina->set('menu3', 'generarCotizacion');
			break;
		}

		$pagina->renderizar();
	}

	public function procesos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$procesos = $this->cargarModelo('movimientos_procesos');
		
		// Botones de form
		if (isset($_POST['generarSolicitud']))
		$procesos->generarSolicitud();
		if (isset($_POST['generarComentario']))
		$procesos->generarComentario();
		if (isset($_POST['procesar']))
		$procesos->procesar();
		if (isset($_POST['finalizar']))
		$procesos->finalizar();
		if (isset($_POST['cerrar']))
		$procesos->cerrar();
		if (isset($_POST['rechazar']))
		$procesos->rechazar();

		if (isset($_POST['agregarIndicador']))
		$procesos->agregarIndicador();
		if (isset($_POST['aplicarCambiosIndicador']))
		$procesos->aplicarCambiosIndicador();

		switch($accion) {
			// Generar interaccion
			case 'generar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Generar Interacción');
			$pagina->set('generar', 1);
			$pagina->set('datos', $procesos->nueva());
			$pagina->set('listadoUsuariosGlobales', $procesos->listadoUsuariosGlobales());

			if ($id == 1) {
				$pagina->set('status', Modelos_Sistema::status(2, 'Interacción generada.'));
			}

			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'generar');
			break;

			// Generadas
			case 'generadas':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Interacciones Generadas');
			$pagina->set('listado', $procesos->listado());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Comentario agregado en solicitud interna.'));
			if ($id == 2) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud cancelada.'));
			if ($id == 3) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud cerrada.'));

			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'generadas');
			break;

			// Generadas
			case 'reporte':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Reporte Global de Interacciones');
			$pagina->set('listado', $procesos->listadoGlobal());

			$pagina->set('listadoGlobal', 1);
			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'reporte');
			break;

			// Asignadas
			case 'asignadas':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Interacciones Asignadas');
			$pagina->set('listado', $procesos->asignadas());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Comentario agregado en solicitud interna.'));
			if ($id == 2) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud en proceso.'));
			if ($id == 3) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud marcada como finalizada.'));

			if ($id == 5) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud cerrada y marcada como atendida.'));
			if ($id == 6) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud en status de <b>en revisión</b>.'));
			if ($id == 7) $pagina->set('status', Modelos_Sistema::status(2, 'Solicitud liberada.'));

			$pagina->set('asignadas', 1);
			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'asignadas');
			break;

			// Visualizar PDF
			case 'visualizar':
			$procesos->visualizar($id);
			break;

			// Comentarios
			case 'comentario':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Agregar Comentario');
			$pagina->set('comentario', 1);
			$pagina->set('datos', $procesos->modificar($id));
			break;

			// Cancelar
			case 'cancelar':
			$procesos->cancelar($id);
			break;

			// Finalizar
			case 'finalizar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Finalizar Solicitud Interna');
			$pagina->set('finalizar', 1);
			$pagina->set('datos', $procesos->modificar($id));
			break;

			// Generadas
			case 'dashboard':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Dashboard de Interacción de Procesos');
			$pagina->set('datos', $procesos->dashboard());
			$pagina->set('dashboard', 1);

			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'dashboard');
			break;

			// Indicadores
			case 'indicadores':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Reporte de Indicadores');

			$pagina->set('listadoIndicadores', $procesos->listadoIndicadores());
			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'indicadores');
			break;

			// Nuevo indicador
			case 'nuevo_indicador':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Nuevo Indicador');
			$pagina->set('nuevoIndicador', 1);
			$pagina->set('listadoUsuariosGlobales', $procesos->listadoUsuariosGlobales());

			if ($id == 1) {
				$pagina->set('status', Modelos_Sistema::status(2, 'Interacción generada.'));
			}

			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'indicadores');
			break;

			// Modificar
			case 'editar_indicador':
			$datos = $procesos->editarIndicador($id);

			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', "Editar Indicador");
			$pagina->set('editarIndicador', 1);
			$pagina->set('listadoUsuariosGlobales', $procesos->listadoUsuariosGlobales($datos->id_responsable));
			$pagina->set('datos', $datos);

			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'indicadores');
			break;

			// Fecha
			case 'fecha':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Especificar Fecha Estimada de Entrega');
			$pagina->set('fecha', 1);
			$pagina->set('datos', $procesos->modificar($id));
			break;

			// Detalles
			case 'detalles':
			$datos = $procesos->editarIndicador($id);

			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', "Detalles del Indicador");
			$pagina->set('detallesIndicador', 1);
			$pagina->set('listadoUsuariosGlobales', $procesos->listadoUsuariosGlobales($datos->id_responsable));
			$pagina->set('datos', $datos);

			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'indicadores');
			break;

			// Cerrar
			case 'cerrar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/procesos');
			$pagina->set('titulo', 'Cerrar Solicitud Interna');
			$pagina->set('cerrar', 1);
			$pagina->set('datos', $procesos->modificar($id));
			break;
		}
		
		if (!empty($procesos->mensajes)) $pagina->set('mensajes', $procesos->mensajes);
		$pagina->renderizar();
	}

	public function eventos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$eventos = $this->cargarModelo('movimientos_eventos');
		
		// Botones de form
		if (isset($_POST['agregarEvento']))
		$eventos->agregar();
		if (isset($_POST['agregarReferencia']))
		$eventos->agregarReferencia();
		if (isset($_POST['agregarTransferencia']))
		$eventos->agregarTransferencia();
		if (isset($_POST['agregarEfectivo']))
		$eventos->agregarEfectivo();
		if (isset($_POST['aplicarPago']))
		$eventos->aplicarPago();

		if ($accion == 'confirmar') {
			$eventos->confirmar($id); die();
		}

		switch($accion) {
			case 'agregar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Agregar Evento');
			$pagina->set('agregar', 1);

			if ($id == 1) {
				$pagina->set('status', Modelos_Sistema::status(2, 'Evento agregado.'));
			}

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'agregarEvento');
			break;

			case 'impresion':
			$eventos->impresion($id);
			break;

			case 'webhook':
			$eventos->webhook();
			break;

			case 'listado':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Listado de Eventos');
			$pagina->set('listado', $eventos->listado());

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'lista':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Lista de Asistencia');
			$pagina->set('listadoAsistencia', $eventos->listadoAsistencia());

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'asistencia':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Confirmar Asistencia de Evento');
			$pagina->set('asistencia', 1);

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'reservas':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Listado de Reservaciones');
			$pagina->set('reservas', 1);
			$pagina->set('listado', $eventos->listadoReservas());

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'referencia':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Generar Referencia de Pago en Establecimiento');
			$pagina->set('referencia', 1);

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Referencia de pago en establecimiento generada.'));

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'transferencia':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Generar Referencia para Transferencia Bancaria');
			$pagina->set('transferencia', 1);

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Referencia de pago para transferencia bancaria generada.'));

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'efectivo':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Generar Referencia para Pago en Efectivo');
			$pagina->set('efectivo', 1);

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Referencia de pago para pago en efectivo generada.'));

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'aplicar_pago':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/eventos');
			$pagina->set('titulo', 'Aplicar Pago de Evento');
			$pagina->set('aplicarPago', 1);

			if ($status == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Pago aplicado.'));

			$pagina->set('info', $eventos->infoPago($id));
			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'eventos');
			$pagina->set('menu3', 'listadoEventos');
			break;

			case 'pago':
			$pagina = $this->cargarVista('external/eventos');
			$pagina->set('pagar', 1);
			break;

			case 'inactivar':
			$eventos->inactivar($id);
			break;

			case 'comprobante_pago':
			$eventos->comprobantePago($id);
			break;

			case 'referencia_efectivo':
			$eventos->referenciaEfectivo($id);
			break;
		}
		
		if (!empty($eventos->mensajes)) $pagina->set('mensajes', $eventos->mensajes);
		$pagina->renderizar();
	}

	public function amenidades($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$amenidades = $this->cargarModelo('movimientos_amenidades');
		
		// Botones de form
		if (isset($_POST['generarSolicitud']))
		$amenidades->generarSolicitud();

		switch($accion) {
			case 'agregar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/amenidades');
			$pagina->set('titulo', 'Agregar Amenidad');
			$pagina->set('agregar', 1);

			if ($id == 1) {
				$pagina->set('status', Modelos_Sistema::status(2, 'Amenidad agregada.'));
			}

			$pagina->set('menu1', 'eventos');
			$pagina->set('menu2', 'amenidades');
			$pagina->set('menu3', 'agregarAmenidades');
			break;

			case 'listado':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('movimientos/amenidades');
			$pagina->set('titulo', 'Listado de amenidades');
			$pagina->set('listado', $amenidades->listado());

			if ($id == 1) $pagina->set('status', Modelos_Sistema::status(2, 'Comentario agregado en solicitud interna.'));

			$pagina->set('menu1', 'procesos');
			$pagina->set('menu2', 'generadas');
			break;

			case 'inactivar':
			$amenidades->inactivar($id);
			break;
		}
		
		if (!empty($amenidades->mensajes)) $pagina->set('mensajes', $amenidades->mensajes);
		$pagina->renderizar();
	}
	
}