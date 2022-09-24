<?php
final class Mercadotecnia extends Controlador {

	function prospectos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$prospectos = $this->cargarModelo('mercadotecnia');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('mercadotecnia');

		if (isset($_POST['nuevo']))
		$prospectos->nuevo($_POST);
		if (isset($_POST['modificarGuardar']))
		$prospectos->modificarGuardar($_POST);
		if (isset($_POST['generarCc']))
		$prospectos->generarCc($_POST);

		switch ($accion) {
			case 'inactivar':
			$prospectos->inactivar($id);
			break;

			case 'reactivar':
			$prospectos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Prospecto');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del prospecto actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('info', $prospectos->modificar($id));
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Prospecto');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Prospecto agregado.'));
			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Prospectos Nuevos');
			$pagina->set('listado', $prospectos->listado());
			break;
		}

		if (!empty($prospectos->mensajes)) $pagina->set('mensajes', $prospectos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'mercadotecnia');
		$pagina->set('menu2', 'prospectos');
		$pagina->set('menu3', 'nuevos');
		$pagina->renderizar();
	}

}