<?php
final class Modelos_Empleados_Administracion extends Modelo {
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
				FROM empleados
				WHERE tipo = 1 AND status = 1
				ORDER BY nombre ASC, apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'celular' => $datos['celular'],
								 'puesto' => $datos['puesto']
								);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT *
				FROM empleados
				WHERE tipo = 1 AND status = 0
				ORDER BY nombre ASC, apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'celular' => $datos['celular'],
								 'puesto' => $datos['puesto']
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
			$apellidos = strtoupper($datos['apellidos']);
			$puesto = strtoupper($datos['puesto']);
			$email = strtolower($datos['email']);
			$telefono = $datos['telefono'];
			$celular = $datos['celular'];
			$contrasena1 = $datos['contrasena1'];
			$contrasena2 = $datos['contrasena2'];

			if (($contrasena1 != '' && $contrasena2 != '') && ($contrasena1 == $contrasena2)) {
				$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
				$contrasenaEncriptada = hash("sha256", $contrasena1.$salt);
				$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $salt, $contrasenaEncriptada);

				$sth = $this->_db->prepare("INSERT INTO empleados (nombre, apellidos, puesto, email, telefono, celular, salt, contrasena, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
				if($sth->execute($arregloDatos)) {
					$this->mensajes[] = Modelos_Sistema::status(2, 'Empleado agregado exitosamente.');
				} else {
					throw New Exception();
				}
			} else {
				$this->mensajes[] = Modelos_Sistema::status(1, 'Las contraseñas no coinciden.');
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificarGuardar($datos) {
		try {
			$id = $datos['id'];
			$id_jefe = $datos['id_jefe'];
			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$puesto = strtoupper($datos['puesto']);
			$email = strtolower($datos['email']);
			$telefono = $datos['telefono'];
			$celular = $datos['celular'];
			
			$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $id_jefe, $id);
			$sth = $this->_db->prepare("UPDATE empleados SET
										nombre = ?,
										apellidos = ?,
										puesto = ?,
										email = ?,
										telefono = ?,
										celular = ?,
										id_jefe = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Empleado modificado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM empleados WHERE id = ?");
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
			$sth = $this->_db->prepare("UPDATE empleados SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/administracion');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE empleados SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/administracion');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoJefes($idEmpleado) {
		try {
			if (isset($idEmpleado)) {
				$sth = $this->_db->prepare("SELECT id_jefe FROM empleados WHERE id = ?");
				$sth->bindParam(1, $idEmpleado);
				if(!$sth->execute()) throw New Exception();
				$idJefeEmpleado = $sth->fetchColumn();
			}

			$sth = $this->_db->query("SELECT id, CONCAT(nombre, ' ', apellidos) AS jefe
				FROM empleados
				WHERE status = 1 AND tipo = 1
				ORDER BY nombre ASC, apellidos ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idJefeEmpleado)) {
					if ($idJefeEmpleado == $datos['id']) {
						$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['jefe'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['id'] . '">' . $datos['jefe'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['id'] . '">' . $datos['jefe'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
}