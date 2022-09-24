<?php
final class Excel extends Controlador {
	
	// Ej. Tipo = Baselines, Subtipo = 4 (ID de SMK)
	public function catalogo($tipo, $subtipo = null) {
		$acceso = $this->cargarModelo('acceso');
		$usuario = $this->cargarModelo('usuario');

		$xls = $this->cargarModelo("catalogos_$tipo");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');

		if ($tipo == 'empleados' || $tipo == 'baselines' || $tipo == 'almacenes') {
			$reporteGlobal = 1;
		} else {
			$reporteGlobal = $usuario->verificarReporteGlobal($_SESSION['login_id'], $tipo);
		}

		if ($reporteGlobal == 1 && empty($_POST['busqueda'])) {
			$xls->excel('', $subtipo);
		} elseif(!empty($_POST['busqueda'])) {
			$xls->excel($_POST['busqueda']);
		}
	}

	public function masterbaseline() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("catalogos_baselines");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->masterBaseline();
	}

	public function baselineinactivos($integracion) {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("catalogos_baselines");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excelInactivos($integracion);
	}

	public function ajustes_inventario($integracion) {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("integraciones_ajustes");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel($integracion);
	}

	public function usuariobaselines($integracion) {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("integraciones_usuariobaseline");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel($integracion);
	}

	public function existencias($idIntegracion) {
		$acceso = $this->cargarModelo('acceso');
		$usuario = $this->cargarModelo('usuario');

		$xls = $this->cargarModelo("integraciones_existencias");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');

		if (empty($_POST['busqueda'])) {
			$xls->excel('', $idIntegracion);
		} elseif(!empty($_POST['busqueda'])) {
			$xls->excel($_POST['busqueda'], $idIntegracion);
		}
	}

	public function importexport($id) {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_importexport");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel($id);
	}

	public function concentrado_facturacion() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_facturacion");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel_concentrado($_POST);
	}

	public function desglose_facturacion() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_facturacion");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel_desglose($_POST);
	}

	public function desglose_facturacion_fecha() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_facturacion");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel_desglose_fecha($_POST);
	}

	public function nopartes_facturados() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_facturacion");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel_nopartes($_POST);
	}

	public function nopartes_invoices() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_facturacion");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel_nopartes_invoices($_POST);
	}

	public function consumos_semanales() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("reportes_ConsumosSemanales");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');

		if (isset($_POST['consumoGlobal2016'])) {
			$xls->excelGlobal();
		} else {
			$xls->excelConsumoSemanal($_POST);
		}
	}

	public function transferencias() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("reportes_Transferencias");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excelTransferencias($_POST);
	}

	public function requisiciones90d() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("reportes_Requisiciones90D");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excelRequisiciones90D($_POST);
	}

	public function cuentas_cobrar() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("Finanzas_CuentasPorCobrar");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel();
	}

	public function sumario_cuentas_cobrar() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("Finanzas_CuentasPorCobrar");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->sumarioExcel();
	}

	public function cuentas_cobrar_totales() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("Finanzas_CuentasPorCobrar");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excelTotales();
	}

	public function traspasos() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_traspasos");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel_desglose($_POST);
	}

	public function desglose_ordenescompra() {
		$acceso = $this->cargarModelo('acceso');
		if ($_POST['idIntegracion'] == 0) {
			$xls = $this->cargarModelo("reportes_ordenescompra");
			if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
			$xls->desglose($_POST);
		} else {
			$xls = $this->cargarModelo("integraciones_ordencompra");
			if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
			$xls->desglose($_POST);
		}
	}

	public function desglose_cotizaciones() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("integraciones_cotizcion");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->desglose($_POST);
	}

	public function listado_carga() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_importexport");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->listadoCarga();
	}

	public function reporte_utilidades() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("reportes_utilidades");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel();
	}

	public function concentrado_requisiciones_empleado() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("reportes_requisiciones");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excelRequisicionesEmpleado();
	}

	public function concentrado_remisiones() {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("movimientos_remision");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel_concentrado($_POST);
	}

	public function expeditar_ordenes() {
		$acceso = $this->cargarModelo('acceso');

		if ($_POST['idIntegracion'] == 0) {
			$xls = $this->cargarModelo("reportes_ordenescompra");
			if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
			$xls->expeditar($_POST);
		} else {
			$xls = $this->cargarModelo("integraciones_ordencompra");
			if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
			$xls->expeditar($_POST);
		}
	}

	public function unico() {
		$acceso = $this->cargarModelo('acceso');

		if ($_POST['idIntegracion'] == 0) {
			$xls = $this->cargarModelo("reportes_ordenescompra");
			if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
			$xls->unico($_POST);
		} else {
			$xls = $this->cargarModelo("integraciones_ordencompra");
			if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
			$xls->unico($_POST);
		}
	}

	public function inventario($idAlmacen) {
		$acceso = $this->cargarModelo('acceso');
		$xls = $this->cargarModelo("catalogos_inventario");
		if(!$acceso->estaLoggeado()) $pagina = $this->redireccionar('./');
		$xls->excel($idAlmacen);
	}
}