<?php
class Propietarios extends Controlador {
	
	public function pagos($accion = null, $tipo = null, $status = null) {
		$acceso = $this->cargarModelo('acceso');
		$propietarios = $this->cargarModelo('propietarios');

		!$acceso->estaLoggeado()? $pagina = $this->redireccionar('./') : $pagina = $this->cargarVista('solicitudes');

		// if ($accion == 'info') {
		// 	$solicitudes->info(); die();
		// }

		if (isset($_POST['generar']))
		$solicitudes->generar();

		switch($accion) {
			case 'recibo':
			$propietarios->recibo();
			break;

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

}