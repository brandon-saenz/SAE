<?php
final class Configuracion extends Controlador {

	function index() {
		$acceso = $this->cargarModelo('acceso');
		$config = $this->cargarModelo('config');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('config');

		if (isset($_POST['modificarGuardar']))
		$config->modificarGuardar($_POST);

		$pagina->set('titulo', 'Configuración General de Sistema');
		$pagina->set('modificar', 1);
		$pagina->set('info', $config->modificar());

		if (!empty($config->mensajes)) $pagina->set('mensajes', $config->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'config');
		$pagina->renderizar();
	}

}