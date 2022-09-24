<?php
class Login extends Controlador {

	function index() {
		$acceso = $this->cargarModelo('acceso');
		$sistema = $this->cargarModelo('sistema');
		$proveedor = $this->cargarModelo('proveedor');

		!$acceso->estaLoggeado()? $pagina = $this->cargarVista('login') : $this->redireccionar('principal');

		if (isset($_POST['registro']))
		$proveedor->registro();
		
		if (!empty($acceso->mensaje)) $pagina->set('mensaje', $acceso->mensaje);
		if (!empty($proveedor->mensaje)) $pagina->set('mensaje', $proveedor->mensaje);

		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('listadoSecciones', $sistema->listadoSecciones());
		$pagina->renderizar();
	}
}