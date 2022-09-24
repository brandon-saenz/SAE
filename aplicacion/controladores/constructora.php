<?php
final class Constructora extends Controlador {

	function contratos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$cotizaciones = $this->cargarModelo('movimientos_cotizaciones');
		$contratos = $this->cargarModelo('constructora_contratos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('constructora/contratos');

		if (isset($_POST['nuevo']))
		$contratos->nuevo();
		if (isset($_POST['modificarGuardar']))
		$contratos->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$contratos->inactivar($id);
			break;

			case 'reactivar':
			$contratos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Tipo de Gasto');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del tipo de gasto actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('listadoTipos', $centrosTrabajo->listadoCentrosTrabajoTiposGastos($info->id_unidad));
			$pagina->set('info', $info);
			break;

			case 'visualizar':
			$contratos->visualizar($id);
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Contrato');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Tipo de gasto agregado.'));

			$pagina->set('listadoPropietarios', $cotizaciones->listadoPropietarios());
			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Reporte de Contratos');
			$pagina->set('listado', $contratos->listado());
			break;
		}

		if (!empty($contratos->mensajes)) $pagina->set('mensajes', $contratos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'constructora');
		$pagina->set('menu2', 'constructoraContratos');
		$pagina->renderizar();
	}

	function presupuestos($accion = null, $id = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$cotizaciones = $this->cargarModelo('movimientos_cotizaciones');
		$presupuestos = $this->cargarModelo('constructora_presupuestos');
		$contratos = $this->cargarModelo('constructora_contratos');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('constructora/presupuestos');

		if (isset($_POST['nuevo']))
		$presupuestos->nuevo();
		if (isset($_POST['modificarGuardar']))
		$presupuestos->modificarGuardar($_POST);

		switch ($accion) {
			case 'inactivar':
			$presupuestos->inactivar($id);
			break;

			case 'reactivar':
			$presupuestos->reactivar($id);
			break;

			case 'modificar':
			$pagina->set('titulo', 'Editar Tipo de Gasto');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Datos del tipo de gasto actualizados.'));

			$pagina->set('modificar', 1);
			$pagina->set('listadoTipos', $centrosTrabajo->listadoCentrosTrabajoTiposGastos($info->id_unidad));
			$pagina->set('info', $info);
			break;

			case 'visualizar':
			$presupuestos->visualizar($id);
			break;

			case 'nuevo':
			$pagina->set('titulo', 'Nuevo Presupuesto de Obra');

			if (!empty($status)) $pagina->set('status', Modelos_Sistema::status(2, 'Tipo de gasto agregado.'));

			$pagina->set('listadoContratos', $contratos->listadoContratos());
			$pagina->set('nuevo', 1);
			break;

			default:
			$pagina->set('titulo', 'Reporte de Presupuestos de Obras');
			$pagina->set('listado', $presupuestos->listado());
			break;
		}

		if (!empty($presupuestos->mensajes)) $pagina->set('mensajes', $presupuestos->mensajes);
		$pagina->set('estaLoggeado', $acceso->estaLoggeado());
		$pagina->set('menu1', 'constructora');
		$pagina->set('menu2', 'constructoraPresupuestos');
		$pagina->renderizar();
	}

}