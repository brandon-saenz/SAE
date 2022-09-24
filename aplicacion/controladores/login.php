<?php
class Login extends Controlador {

	function index() {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->cargarVista('login') : $this->redireccionar('principal');
		
		if (!empty($acceso->mensajes)) $pagina->set('mensajes', $acceso->mensajes);

		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}
}