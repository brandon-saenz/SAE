<?php
class Finanzas extends Controlador {

	public function cuentas_por_cobrar($accion = null, $id = null, $idSecundario = null) {
		$acceso = $this->cargarModelo('acceso');
		$cuentasPorCobrar = $this->cargarModelo('Finanzas_CuentasPorCobrar');

		if (isset($_POST['guardar']))
		$cuentasPorCobrar->modificarGuardar($_POST);
		if (isset($_POST['guardarMultiple']))
		$cuentasPorCobrar->modificarGuardarMultiple($_POST);

		switch ($accion) {
			case 'cobrada':
			$cuentasPorCobrar->cobrada($id, $idSecundario);
			break;

			case 'cancelada':
			$cuentasPorCobrar->cancelada($id, $idSecundario);
			break;

			case 'inactivar':
			$cuentasPorCobrar->inactivar($id, $idSecundario);
			break;

			case 'modificar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/cuentas_por_cobrar');
			$pagina->set('titulo', 'Cuentas Fac');

			$pagina->set('modificar', 1);
			$pagina->set('idFactura', $id);
			$pagina->set('datos', $cuentasPorCobrar->modificar($id));
			break;

			case 'facturas':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/cuentas_por_cobrar');
			$pagina->set('titulo', 'Cuentas Fac');

			if (!empty($idSecundario)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos de cobranza actualizados.'));

			$pagina->set('facturas', 1);
			$pagina->set('cliente', $cuentasPorCobrar->nombreCliente($id));
			$pagina->set('datos', $cuentasPorCobrar->facturas($id));
			break;

			default:
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/cuentas_por_cobrar');
			$pagina->set('titulo', 'Cuentas Fac');

			$cuentasPorCobrar->resumenGeneral();
			$activos = $cuentasPorCobrar->activos;
			$inactivos = $cuentasPorCobrar->inactivos;

			$pagina->set('activos', $activos);
			$pagina->set('datosExtras', $cuentasPorCobrar->datosExtras);
			$pagina->set('inactivos', $inactivos);
			break;
		}
		
		if (!empty($cuentasPorCobrar->mensajes)) $pagina->set('mensajes', $cuentasPorCobrar->mensajes);

		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

	public function deudas($accion = null, $id = null, $idSecundario = null) {
		$acceso = $this->cargarModelo('acceso');
		$deuda = $this->cargarModelo('Finanzas_Deudas');
		$clientes = $this->cargarModelo('catalogos_clientes');

		if (isset($_POST['nuevo']))
		$deuda->nuevo($_POST);
		if (isset($_POST['registrarPago']))
		$deuda->registrarPago($_POST);

		switch ($accion) {
			case 'cobrada':
			$deuda->cobrada($id, $idSecundario);
			break;

			case 'cancelada':
			$deuda->cancelada($id, $idSecundario);
			break;

			case 'inactivar':
			$deuda->inactivar($id, $idSecundario);
			break;

			case 'nuevo':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/deudas');
			$pagina->set('titulo', 'Nueva Cuenta Cero');

			$pagina->set('nuevo', 1);
			$pagina->set('clientsList', $clientes->listadoSelect());
			if (!empty($id)) $pagina->set('status', Modelos_Sistema::status(2, 'Cuenta cero agregada.'));
			break;

			case 'desglose':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/deudas');
			$pagina->set('titulo', 'Estado de Cuenta');

			$pagina->set('desglose', 1);
			$pagina->set('id', $id);
			$pagina->set('listado', $deuda->estadoCuenta($id));
			break;

			case 'pagar':
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/deudas');
			$pagina->set('titulo', 'Registrar Pago de Mensualidad');

			$pagina->set('pagar', 1);
			$pagina->set('idPago', $id);
			$pagina->set('datos', $deuda->datosPago($id));
			if (!empty($idSecundario)) $pagina->set('status', Modelos_Sistema::status(2, 'Pago registrado.'));
			break;

			case 'pdf':
			$deuda->pdf($id);
			break;

			case 'descargar':
			$deuda->descargar($id);
			break;

			default:
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/deudas');
			$pagina->set('titulo', 'Cuentas Cero');
			$pagina->set('listado', $deuda->resumenGeneral());
			break;
		}
		
		if (!empty($deuda->mensajes)) $pagina->set('mensajes', $cuentasPorCobrar->mensajes);

		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

	public function gastos($accion = null, $id = null) {
		$acceso = $this->cargarModelo('acceso');
		$gastos = $this->cargarModelo('Finanzas_Gastos');

		switch ($accion) {
			default:
			$gastos->descargar();
			// $gastos->procesar();
			
			!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('finanzas/gastos');
			$pagina->set('titulo', 'Recepción');
			$pagina->set('listado', $gastos->listadoSat());
			break;
		}
		
		if (!empty($deuda->mensajes)) $pagina->set('mensajes', $cuentasPorCobrar->mensajes);

		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}
}