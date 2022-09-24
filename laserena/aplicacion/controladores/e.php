<?php
class E extends Controlador {

	// Pagar cotizacion
	public function p($accion = null, $id = null) {
		// $cotizaciones = $this->cargarModelo('movimientos_cotizaciones');

		// if (isset($_POST['realizarPago']))
		// $cotizaciones->realizarPago();

		switch($accion) {
			// Datos de pago
			case 'f':
			$pagina = $this->cargarVista('pago');
			// $pagina->set('datos', $cotizaciones->datosPago($id));
			$pagina->set('pagar', 1);
			$pagina->renderizar();
			break;
		}
	}
	
}