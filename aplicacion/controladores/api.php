<?php
final class Api extends Controlador {

	function arrendatarios($accion = null, $id = null, $status = null) {
		$arrendatarios = $this->cargarModelo('catalogos_arrendatarios');

		switch ($accion) {
			case 'verificacion':
			$arrendatarios->apiVerificacion($id);
			break;

			case 'amortizacion':
			$arrendatarios->amortizacion($id);
			break;

			// Listado de pagos
			case 'cuotas':
			$arrendatarios->apiCuotas($id);
			break;

			case 'mensualidades':
			$arrendatarios->apiMensualidades($id);
			break;

			case 'enganches':
			$arrendatarios->apiEnganches($id);
			break;

			// Listado de conceptos elegidos
			case 'conceptos':
			$arrendatarios->apiConceptos();
			break;

			case 'conceptos_mensualidades':
			$arrendatarios->apiConceptosMensualidades();
			break;

			case 'conceptos_enganches':
			$arrendatarios->apiConceptosEnganches();
			break;

			// Aplicar pago en Cobroplan
			case 'info_pago':
			$arrendatarios->apiInfoPago($id);
			break;

			case 'info_pago_mensualidad':
			$arrendatarios->apiInfoPagoMensualidad($id);
			break;

			case 'info_pago_enganche':
			$arrendatarios->apiInfoPagoEnganche($id);
			break;

			// Aplicar pago en SAE Valcas
			case 'aplicar_pago':
			$arrendatarios->apiAplicarPago();
			break;

			case 'aplicar_pago_mensualidad':
			$arrendatarios->apiAplicarPagoMensualidad();
			break;

			case 'aplicar_pago_enganche':
			$arrendatarios->apiAplicarPagoEnganche();
			break;

		}
	}

}