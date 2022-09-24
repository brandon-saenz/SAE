<?php
final class Modelos_Catalogos_Contratos extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function listado() {
		try {
			$datosVista = array();

			// Activos
			$sth = $this->_db->query("
				SELECT c.id, c.id_contrato, a.nombre AS arrendatario, ar.nombre AS arrendadora, i.nombre AS lote, c.moneda, c.inicio_vig, c.fin_vig, tiempo
				FROM contratos c
				JOIN arrendatarios a
				ON a.id_arrendatario = c.id_arrendatario
				JOIN arrendadoras ar
				ON ar.id_arrendadora = c.id_arrendadora
				JOIN inventario i
				ON i.edificio = c.id_edificio
				ORDER BY c.id DESC
			");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'id_contrato' => $datos['id_contrato'],
					'arrendatario' => mb_strtoupper($datos['arrendatario'], 'UTF-8'),
					'arrendadora' => mb_strtoupper($datos['arrendadora'], 'UTF-8'),
					'lote' => mb_strtoupper($datos['lote'], 'UTF-8'),
					'inicio_vig' => Modelos_Fecha::formatearFecha($datos['inicio_vig']),
					'fin_vig' => Modelos_Fecha::formatearFecha($datos['fin_vig']),
					'tiempo' => $datos['tiempo'] . ' meses',
				);

				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			// $sth = $this->_db->query("SELECT *
			// 	FROM campanas
			// 	WHERE status = 0
			// 	ORDER BY nombre ASC");
			// if(!$sth->execute()) throw New Exception();
			
			// $inactivos = array();
			// while ($datos = $sth->fetch()) {
			// 	$arreglo = array(
			// 		'id' => $datos['id'],
			// 		'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8')
			// 	);
			// 	$inactivos[] = $arreglo;
			// }

			// $datosVista['inactivos'] = $inactivos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function nuevo($datos) {
		try {
			$nombre = strtoupper($datos['nombre']);

			$arregloDatos = array($nombre);
			$sth = $this->_db->prepare("INSERT INTO campanas (nombre) VALUES (?)");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Campaña agregada exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificarGuardar($datos) {
		try {
			$id = $datos['id'];
			$nombre = strtoupper($datos['nombre']);
			
			$arregloDatos = array($nombre, $id);
			$sth = $this->_db->prepare("UPDATE campanas SET
										nombre = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Campaña modificada exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM campanas WHERE id = ?");
			$sth->bindParam(1, $id);
			$sth->setFetchMode(PDO::FETCH_INTO, $this);
			if(!$sth->execute()) throw New Exception();
			$sth->fetch();

	  		return $this;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function inactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE campanas SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/campanas');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE campanas SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/campanas');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadocampanas($idEmpleado = null) {
		try {
			if (isset($idEmpleado)) {
				$sth = $this->_db->prepare("SELECT id_departamento FROM empleados WHERE id = ?");
				$sth->bindParam(1, $idEmpleado);
				if(!$sth->execute()) throw New Exception();
				$idDepartamento = $sth->fetchColumn();
			}

			$sth = $this->_db->query("SELECT id, nombre
				FROM campanas
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idDepartamento)) {
					if ($idDepartamento == $datos['id']) {
						$html .= '<option value="' . $datos['nombre'] . '" selected>' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['nombre'] . '">' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['nombre'] . '">' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}