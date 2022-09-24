<?php
class Login extends Controlador {

	function index() {
		$acceso = $this->cargarModelo('acceso');
		$sistema = $this->cargarModelo('sistema');
		!$acceso->estaLoggeado()? $pagina = $this->cargarVista('login') : $this->redireccionar('principal');
		
		if (!empty($acceso->mensaje)) $pagina->set('mensaje', $acceso->mensaje);

		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('listadoSecciones', $sistema->listadoSecciones());
		$pagina->renderizar();
	}
}