<?php
final class Modelos_Catalogos_Facturas extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function listado() {
		$datosVista = [];

		$sth = $this->_db->query("
			SELECT rp.id, rp.id_requisicion, r.centro_costo, d.nombre AS departamento, rp.tipo, DATE(rp.fecha_creacion) AS fecha_creacion, DATE(rp.fecha_procesa) AS fecha_procesa, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, rp.fecha_autorizacion, rp.dias_entrega, rp.oc, rp.archivo_pdf, rp.archivo_xml
			FROM requisiciones_partes rp
			JOIN departamentos d
			ON d.id = rp.id_departamento
			JOIN empleados e
			ON e.id = rp.id_solicita
			JOIN empleados es
			ON es.id = rp.id_autoriza
			JOIN requisiciones r
			ON r.id = rp.id_requisicion
			JOIN empleados ej
			ON ej.id = e.id_jefe
			WHERE rp.status IN(2, 3) AND archivo_pdf IS NOT NULL AND archivo_xml IS NOT NULL
			ORDER BY rp.id_requisicion DESC
		");
		if(!$sth->execute()) throw New Exception();

		$datosVista['nPagadas'] = 0;
		$datosVista['nCargados'] = 0;

		while ($datos = $sth->fetch()) {
			if ($diasVencidos >= 3) {
				$icono = 'icono-activar.png';
				$color = '#AFE5AF';
			} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
				$icono = 'icono-alerta_amarillo.png';
				$color = '#FFFAC1';
			} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
				$icono = 'icono-advertencia.png';
				$color = '#FFB4AA';
			}

			$arreglo = array(
				'id' => $datos['id'],
				'id_requisicion' => $datos['id_requisicion'],
				'centro_costo' => $datos['centro_costo'],
				'autoriza' => $datos['autoriza'],
				'departamento' => $datos['departamento'],
				'producto' => $datos['producto'],
				'tipo' => $datos['tipo'],
				'cantidad' => $datos['cantidad'],
				'um' => $datos['um'],
				'oc' => $datos['oc'],
				'dias_entrega' => $datos['dias_entrega'],
				'archivo_pdf' => $datos['archivo_pdf'],
				'archivo_xml' => $datos['archivo_xml'],
				'dias_vencidos' => $status,
				'icono' => $icono,
				'color' => $color,
				'fecha' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
				'fecha_procesa' => Modelos_Fecha::formatearFecha($datos['fecha_procesa']),
			);

			$datosVista['pendientes'][] = $arreglo;
			$datosVista['nPendientes']++;
		}

		return $datosVista;
	}

}