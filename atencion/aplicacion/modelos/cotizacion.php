<?php
final class Modelos_Cotizacion extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function listado() {
		try {
			$datosVista = [];

			$sth = $this->_db->prepare("
				SELECT c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS empleado, c.vigencia, p.email, p.telefono1, c.status, c.alfanumerico
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				JOIN empleados e
				ON e.id = c.id_agente
				WHERE c.id_cliente = ?
				ORDER BY id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				switch ($datos['seccion']) {
					case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
					case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
					case 'LOMAS (RGR)': $prefijo = 'SL'; break;
					case 'LOMAS': $prefijo = 'SL'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
					case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
					case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
					case 'VISTA DEL REY': $prefijo = 'VR'; break;
				}
				$lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

				if ($datos['moneda'] == 1) {
					$moneda = 'MXN';
				} elseif ($datos['moneda'] == 2) {
					$moneda = 'USD';
				}

				switch ($datos['status']) {
					case 0: $label = 'label-success'; $statusHtml = ' Pendiente'; break;
					case 1: $label = 'label-success'; $statusHtml = ' Pendiente'; break;
					case 2: $label = 'label-primary'; $statusHtml = ' Aceptada'; break;
					case 4: $label = 'label-info'; $statusHtml = ' Pagada'; break;
					case -1: $label = 'label-danger'; $statusHtml = ' Cancelada'; break;
					case 4: $label = 'label-danger'; $statusHtml = ' Rechazada'; break;
				}

				$datosVista[] = array(
					'id' => $datos['id'],
					'id_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id_solicitud'], 5, '0', STR_PAD_LEFT),
					'alfanumerico' => $datos['alfanumerico'],
					'propietario' => $datos['nombre'],
					'lote' => $lote,
					'label' => $label,
					'statusHtml' => $statusHtml,
					'agente' => $datos['empleado'],
					'email' => $datos['email'],
					'celular' => preg_replace('/\D/', '', $datos['telefono1']),
					'total' => '$ ' . number_format($datos['total'], 2, '.', ',') . ' ' . $moneda,
					'fecha_creacion' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
					'fecha_vigencia' => Modelos_Fecha::formatearFecha($datos['vigencia']),
				);
			}

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}