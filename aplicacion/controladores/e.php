<?php
class E extends Controlador {

	// Pagar cotizacion
	public function p($accion = null, $id = null) {
		$cotizaciones = $this->cargarModelo('movimientos_cotizaciones');

		if (isset($_POST['realizarPago']))
		$cotizaciones->realizarPago();

		switch($accion) {
			// Datos de pago
			case 'f':
			$pagina = $this->cargarVista('external/cotizaciones');
			$pagina->set('datos', $cotizaciones->datosPago($id));
			$pagina->set('pagar', 1);
			$pagina->renderizar();
			break;

			// Rechazar
			case 'r':
			$cotizaciones->rechazar($id);

			$pagina = $this->cargarVista('external/cotizaciones');
			$pagina->set('rechazada', 1);
			$pagina->renderizar();
			// $pagina->set('titulo', 'Generar Cotización');
			// $pagina->set('generar', 1);
			// $pagina->set('listadoPropietarios', $cotizaciones->listadoPropietarios());
			break;

			// Transaccion realizada
			case 't':
			$pagina = $this->cargarVista('external/cotizaciones');
			$pagina->set('datos', $cotizaciones->datosPagoCorreo($id));
			$pagina->set('transaccion', 1);
			$pagina->renderizar();
			break;
		}
	}
	
}