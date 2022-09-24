<?php
final class Principal extends Controlador {

	function index() {
		$acceso = $this->cargarModelo('acceso');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('principal');
		
		$pagina->set('titulo', 'Administración Grupo Valcas');
		$pagina->set('menu', 'principal');
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

}