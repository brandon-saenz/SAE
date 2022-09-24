<?php
final class Qys extends Controlador {

	function index() {
		$acceso = $this->cargarModelo('acceso');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('qys');
		
		$pagina->set('titulo', 'Quejas y Sugerencias');
		$pagina->set('menu', 'qys');
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

}