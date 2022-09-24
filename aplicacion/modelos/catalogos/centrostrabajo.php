<?php
final class Modelos_Catalogos_CentrosTrabajo extends Modelo {
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
				SELECT cc.*, CONCAT(ed.nombre, ' ', ed.apellidos) AS director, CONCAT(ec.nombre, ' ', ec.apellidos) AS comprador
				FROM centros_trabajo cc
				LEFT JOIN empleados ed
				ON ed.id = cc.id_director
				LEFT JOIN empleados ec
				ON ec.id = cc.id_comprador
				WHERE cc.status = 1
				ORDER BY cc.nombre ASC
			");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'director' => mb_strtoupper($datos['director'], 'UTF-8'),
					'comprador' => mb_strtoupper($datos['comprador'], 'UTF-8'),
				);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("
				SELECT cc.*, CONCAT(ed.nombre, ' ', ed.apellidos) AS director, CONCAT(ec.nombre, ' ', ec.apellidos) AS comprador
				FROM centros_trabajo cc
				LEFT JOIN empleados ed
				ON ed.id = cc.id_director
				LEFT JOIN empleados ec
				ON ec.id = cc.id_comprador
				WHERE cc.status = 0
				ORDER BY cc.nombre ASC
			");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'director' => mb_strtoupper($datos['director'], 'UTF-8'),
					'comprador' => mb_strtoupper($datos['comprador'], 'UTF-8'),
				);
				$inactivos[] = $arreglo;
			}

			$datosVista['inactivos'] = $inactivos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function nuevo($datos) {
		try {
			$nombre = strtoupper($datos['nombre']);
			$id_director = $datos['id_director'];
			$id_comprador = $datos['id_comprador'];

			$arregloDatos = array($nombre, $id_director, $id_comprador);
			$sth = $this->_db->prepare("INSERT INTO centros_trabajo (nombre, id_director, id_comprador) VALUES (?, ?, ?)");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Centro de trabajo agregado exitosamente.');
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
			$id_director = $datos['id_director'];
			$id_comprador = $datos['id_comprador'];
			
			$arregloDatos = array($nombre, $id_director, $id_comprador, $id);
			$sth = $this->_db->prepare("
				UPDATE centros_trabajo SET
				nombre = ?,
				id_director = ?,
				id_comprador = ?
				WHERE id = ?
			");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Centro de trabajo modificado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM centros_trabajo WHERE id = ?");
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
			$sth = $this->_db->prepare("UPDATE centros_trabajo SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/centros_trabajo');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE centros_trabajo SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/centros_trabajo');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoCentrosTrabajo($idEmpleado) {
		try {
			if (isset($idEmpleado)) {
				$sth = $this->_db->prepare("SELECT id_centro_trabajo FROM empleados WHERE id = ?");
				$sth->bindParam(1, $idEmpleado);
				if(!$sth->execute()) throw New Exception();
				$idCentroTrabajo = $sth->fetchColumn();
			}

			$sth = $this->_db->query("SELECT id, nombre
				FROM centros_trabajo
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idCentroTrabajo)) {
					if ($idCentroTrabajo == $datos['id']) {
						$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoCentrosTrabajoTiposGastos($idCentroTrabajo = null) {
		try {
			$sth = $this->_db->query("SELECT id, nombre
				FROM centros_trabajo
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idCentroTrabajo)) {
					if ($idCentroTrabajo == $datos['id']) {
						$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoCentrosTrabajoHtml($idRequisicion) {
		try {
			if (isset($idRequisicion)) {
				$sth = $this->_db->prepare("SELECT centro_costo FROM requisiciones WHERE id = ?");
				$sth->bindParam(1, $idRequisicion);
				if(!$sth->execute()) throw New Exception();
				$centroTrabajoSeleccionado = $sth->fetchColumn();
			}

			$sth = $this->_db->query("
				SELECT cc.id, cc.nombre, CONCAT(ed.nombre, ' ', ed.apellidos) AS director, CONCAT(ec.nombre, ' ', ec.apellidos) AS comprador
				FROM centros_trabajo cc
				LEFT JOIN empleados ed
				ON ed.id = cc.id_director
				LEFT JOIN empleados ec
				ON ec.id = cc.id_comprador
				WHERE cc.status = 1
				ORDER BY cc.nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($centroTrabajoSeleccionado)) {
					if ($centroTrabajoSeleccionado == $datos['nombre']) {
						$html .= '<option value="' . $datos['nombre'] . '" data-director="' . $datos['director'] . '" data-comprador="' . $datos['comprador'] . '" selected>' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['nombre'] . '" data-director="' . $datos['director'] . '" data-comprador="' . $datos['comprador'] . '">' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['nombre'] . '" data-director="' . $datos['director'] . '" data-comprador="' . $datos['comprador'] . '">' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}