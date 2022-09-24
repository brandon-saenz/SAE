<?php
final class Usuario extends Controlador {

	function ajustes($status = null) {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('usuario/ajustes');
		$pagina->set('titulo', 'Ajustes Personales');

		$usuario = $this->cargarModelo('usuario');
		if (isset($_POST['guardarDatosPersonales'])) {
			$usuario->modificarDatosPersonales($_POST);
			$this->redireccionar('usuario/ajustes/1');
		}
		$datos = $usuario->obtenerDatos();

		if ($status == 1) {
			$pagina->set('status', Modelos_Sistema::status(2, 'Datos actualizados exitosamente.'));
		}
		if (!empty($usuario->mensajes)) $pagina->set('mensajes', $usuario->mensajes);
		
		$barraEstadisticas = $this->cargarModelo('barra');
		$pagina->set('datos', $datos);
		$pagina->set('barraEstadisticas', $barraEstadisticas->contenido($acceso->estaLoggeado()));
		$pagina->set('skinFondo', $acceso->skinFondo);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

	function solicitud() {
		$pagina = $this->cargarVista('usuario/solicitud');
		$usuario = $this->cargarModelo('usuario');
		$usuario->actualizarSolicitudGlobal($_GET['h'], $_GET['u'], $_GET['t'], $_GET['s']);
		$pagina->renderizar();
	}

	function tema($imagen = null) {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('usuario/tema');
		$pagina->set('titulo', 'Cambiar Tema');

		$usuario = $this->cargarModelo('usuario');
		if (!empty($imagen)) {
			$usuario->actualizarImagenFondo($imagen);
			die();
		}
		$datos = $usuario->obtenerDatos();
		
		$barraEstadisticas = $this->cargarModelo('barra');
		$pagina->set('datos', $datos);
		$pagina->set('barraEstadisticas', $barraEstadisticas->contenido($acceso->estaLoggeado()));
		$pagina->set('skinFondo', $acceso->skinFondo);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->renderizar();
	}

	public function verificar_sesion() {
		echo 1;
	}

	public function cs() {
		$acceso = $this->cargarModelo('acceso');
		$acceso->cerrarSesion();
		$this->redireccionar('./');
	}

	public function skin_personalizado() {
		$acceso = $this->cargarModelo('acceso');
		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('usuario/tema');
		$usuario = $this->cargarModelo('usuario');
		$usuario->imagenFondoPersonalizada();
		die();
	}
}