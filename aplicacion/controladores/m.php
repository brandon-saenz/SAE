<?php
class M extends Controlador {

	public function c($accion = null, $id = null) {
		$cotizaciones = $this->cargarModelo('movimientos_cotizaciones');

		switch($accion) {
			case 'v':
			$cotizaciones->visualizar('', '', $id);
			break;
		}
	}
	
}