<?php
final class Modelos_Movimientos_Qys extends Modelo {
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
				SELECT q.id, p.nombre, p.seccion, p.manzana, p.lote, q.fecha_creacion, q.tipo, q.id_servicio, q.otro, q.descripcion
				FROM qys q
				JOIN propietarios p
				ON p.id = q.id_propietario
			");
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
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