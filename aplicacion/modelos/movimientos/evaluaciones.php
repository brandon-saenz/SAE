<?php
final class Modelos_Movimientos_Evaluaciones extends Modelo {
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

			$sth = $this->_db->query("
				SELECT e.id, s.id AS id_solicitud, s.tipo, p.nombre AS propietario, e.calificacion, e.p1, e.p2, e.p3, e.p4, e.p5, e.comentarios, e.fecha_creacion
				FROM evaluaciones e
				JOIN solicitudes s
				ON s.id = e.id_solicitud
				JOIN propietarios p
				ON p.id = s.id_propietario
				ORDER BY e.id DESC
			");
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				switch ($datos['calificacion']) {
					case 1: $calificacion = 'PÉSIMO'; break;
					case 2: $calificacion = 'DEFICIENTE'; break;
					case 3: $calificacion = 'REGULAR'; break;
					case 4: $calificacion = 'BUENO'; break;
					case 5: $calificacion = 'EXCELENTE'; break;
				}

				if ($datos['calificacion'] != 5) {
					switch ($datos['p1']) {
						case 1: $p1 = 'PÉSIMO'; break;
						case 2: $p1 = 'DEFICIENTE'; break;
						case 3: $p1 = 'REGULAR'; break;
						case 4: $p1 = 'BUENO'; break;
						case 5: $p1 = 'EXCELENTE'; break;
					}

					switch ($datos['p2']) {
						case 1: $p2 = 'PÉSIMO'; break;
						case 2: $p2 = 'DEFICIENTE'; break;
						case 3: $p2 = 'REGULAR'; break;
						case 4: $p2 = 'BUENO'; break;
						case 5: $p2 = 'EXCELENTE'; break;
					}

					switch ($datos['p3']) {
						case 0: $p3 = 'NO'; break;
						case 1: $p3 = 'SI'; break;
					}

					switch ($datos['p4']) {
						case 0: $p4 = 'NO'; break;
						case 1: $p4 = 'SI'; break;
					}

					switch ($datos['p5']) {
						case 1: $p5 = 'PÉSIMO'; break;
						case 2: $p5 = 'DEFICIENTE'; break;
						case 3: $p5 = 'REGULAR'; break;
						case 4: $p5 = 'BUENO'; break;
						case 5: $p5 = 'EXCELENTE'; break;
					}
				} else {
					$p1 = '---';
					$p2 = '---';
					$p3 = '---';
					$p4 = '---';
					$p5 = '---';
				}

				$datosVista[] = array(
					'id' => $datos['id'],
					'id_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id_solicitud'], 5, '0', STR_PAD_LEFT),
					'propietario' => $datos['propietario'],
					'solicitud' => $datos['solicitud'],
					'calificacion' => $calificacion,
					'p1' => $p1,
					'p2' => $p2,
					'p3' => $p3,
					'p4' => $p4,
					'p5' => $p5
				);
			}

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}