<?php
class Movimientos extends Controlador {
	
	public function solicitudes($accion = null, $tipo = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$solicitudes = $this->cargarModelo('solicitudes');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('solicitudes');

		if ($accion == 'info') {
			$solicitudes->info(); die();
		}
		if ($accion == 'info_comentarios') {
			$solicitudes->infoComentarios(); die();
		}

		if (isset($_POST['generar']))
		$solicitudes->generar();
		if (isset($_POST['generarComentario']))
		$solicitudes->generarComentario();
		if (isset($_POST['generarCancelacion']))
		$solicitudes->generarCancelacion();
		if (isset($_POST['actualizarDatos']))
		$solicitudes->actualizarDatos();
		if (isset($_POST['generarEvaluacion']))
		$solicitudes->generarEvaluacion();

		switch($accion) {
			case 'enviada':
			$pagina->set('titulo', 'Solicitud Enviada');
			$pagina->set('enviada', 1);
			break;

			case 'cenviado':
			$pagina->set('titulo', 'Comentario Enviado');
			$pagina->set('cenviado', 1);
			break;

			case 'evenviado':
			$pagina->set('titulo', 'Evaluación Enviada');
			$pagina->set('evenviado', 1);
			break;

			case 'verificado':
			$pagina->set('titulo', 'Datos de Contacto Actualizados');
			$pagina->set('verificado', 1);
			break;

			case 'nueva':
			if ($solicitudes->verificarDatosActualizados() == 1) {
				$pagina->set('titulo', 'Solicitud de Atención a Propietarios');
				$pagina->set('datos', $solicitudes->nueva());
				$pagina->set('listadoServicios', $solicitudes->listadoServicios());
				$pagina->set('nueva', 1);
				$pagina->set('menu', 'nueva');
			} else {
				$pagina->set('titulo', 'Verificación de Datos de Contacto');
				$pagina->set('datos', $solicitudes->datosPropietario());
				$pagina->set('verificacion', 1);
				$pagina->set('menu', 'nueva');
			}
			break;

			case 'comentario':
			$pagina->set('titulo', 'Agregar Comentario en Solicitud');
			$pagina->set('datos', $solicitudes->modificar($tipo));
			$pagina->set('listadoServicios', $solicitudes->listadoServicios());
			$pagina->set('comentario', 1);
			break;

			case 'cancelar':
			$pagina->set('titulo', 'Cancelar Solicitud');
			$pagina->set('datos', $solicitudes->modificar($tipo));
			$pagina->set('listadoServicios', $solicitudes->listadoServicios());
			$pagina->set('cancelar', 1);
			break;

			case 's':
			$pagina->set('titulo', 'Reporte de Solicitudes');
			$pagina->set('listado', $solicitudes->listado($tipo));
			$pagina->set('menu', $tipo);
			break;

			case 'evaluar':
			$pagina->set('titulo', 'Evaluar Atención y Seguimiento de Solicitud');
			$pagina->set('evaluar', 1);
			$pagina->set('id', $tipo);
			$pagina->set('menu', 'nueva');
			break;

			default:
			$pagina->set('titulo', 'Reporte de Solicitudes');
			$pagina->set('listado', $solicitudes->listado());
			$pagina->set('menu', 'solicitudes');
			break;
		}
		
		$pagina->renderizar();
	}

	public function qys($accion = null, $tipo = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$qys = $this->cargarModelo('qys');
		$solicitudes = $this->cargarModelo('solicitudes');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('qys');

		if (isset($_POST['generar']))
		$qys->generar();

		switch($accion) {
			case 'enviada':
			$pagina->set('titulo', 'Solicitud Enviada');
			$pagina->set('enviada', 1);
			break;

			default:
			$pagina->set('titulo', 'Quejas y Sugerencias');
			$pagina->set('listadoServicios', $solicitudes->listadoServicios());
			$pagina->set('datos', $qys->nueva());
			$pagina->set('nueva', 1);
			$pagina->set('menu', 'qys');
			break;
		}
		
		$pagina->renderizar();
	}

	public function cotizaciones($accion = null, $tipo = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$cotizacion = $this->cargarModelo('cotizacion');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('cotizaciones');

		switch($accion) {
			case 'listado':
			$pagina->set('titulo', 'Listado de Cotizaciones Generadas');
			$pagina->set('listado', $cotizacion->listado());
			$pagina->set('menu', 'solicitudes');
			break;
		}
		
		$pagina->renderizar();
	}

}