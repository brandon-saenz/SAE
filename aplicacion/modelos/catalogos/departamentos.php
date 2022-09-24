<?php
final class Modelos_Catalogos_Departamentos extends Modelo {
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
			$sth = $this->_db->query("SELECT *
				FROM departamentos
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8')
				);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT *
				FROM departamentos
				WHERE status = 0
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8')
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

			$arregloDatos = array($nombre);
			$sth = $this->_db->prepare("INSERT INTO departamentos (nombre) VALUES (?)");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Departamento agregado exitosamente.');
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
			$sth = $this->_db->prepare("UPDATE departamentos SET
										nombre = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Departamento modificado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM departamentos WHERE id = ?");
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
			$sth = $this->_db->prepare("UPDATE departamentos SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/departamentos');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE departamentos SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/departamentos');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoDepartamentos($idEmpleado) {
		try {
			if (isset($idEmpleado)) {
				$sth = $this->_db->prepare("SELECT id_departamento FROM empleados WHERE id = ?");
				$sth->bindParam(1, $idEmpleado);
				if(!$sth->execute()) throw New Exception();
				$idDepartamento = $sth->fetchColumn();
			}

			$sth = $this->_db->query("SELECT id, nombre
				FROM departamentos
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idDepartamento)) {
					if ($idDepartamento == $datos['id']) {
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

	public function listadoDepartamentosResultados($id = null) {
		try {
			$html = '';

			$sth = $this->_db->query("SELECT id, nombre
				FROM departamentos
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				if (isset($id)) {
					if ($id == $datos['id']) {
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

}