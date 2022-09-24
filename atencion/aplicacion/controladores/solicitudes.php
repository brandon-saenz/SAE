<?php
final class Solicitudes extends Controlador {

	function index() {
		$acceso = $this->cargarModelo('acceso');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('solicitudes/reporte');
		
		$pagina->set('titulo', 'Reporte de Solicitudes');
		$pagina->set('menu', 'solicitudes');
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

	function nueva($enviada = null) {
		$acceso = $this->cargarModelo('acceso');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('solicitudes/solicitud');
		
		if (!$enviada) {
			$pagina->set('titulo', 'Solicitud de Atención a Propietarios');
			$pagina->set('nueva', 1);
		} else {
			$pagina->set('titulo', 'Solicitud Enviada');
			$pagina->set('enviada', 1);
		}
		
		$pagina->set('menu', 'nueva');
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

}