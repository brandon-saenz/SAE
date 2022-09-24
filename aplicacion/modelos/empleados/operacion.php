<?php
final class Modelos_Empleados_Operacion extends Modelo {
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
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo = 2 AND e.status = 1
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT CONCAT(nombre, ' ', apellidos) AS jefe_directo FROM empleados WHERE id = ?");
				$sth2->bindParam(1, $datos['id_jefe']);
				if(!$sth2->execute()) throw New Exception();
				$jefeDirecto = $sth2->fetchColumn();

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'jefe_directo' => mb_strtoupper($jefeDirecto, 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'celular' => $datos['celular'],
								 
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento'],
								);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo = 2 AND e.status = 0
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT CONCAT(nombre, ' ', apellidos) AS jefe_directo FROM empleados WHERE id = ?");
				$sth2->bindParam(1, $datos['id_jefe']);
				if(!$sth2->execute()) throw New Exception();
				$jefeDirecto = $sth2->fetchColumn();

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'jefe_directo' => mb_strtoupper($jefeDirecto, 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'celular' => $datos['celular'],
								 
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento'],
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
			$id_jefe = $datos['id_jefe'];
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
				$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $salt, $contrasenaEncriptada, $id_jefe);

				$sth = $this->_db->prepare("INSERT INTO empleados (nombre, apellidos, puesto, email, telefono, celular, salt, contrasena, tipo, id_jefe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 2, ?)");
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
			$id_departamento = $datos['id_departamento'];
			$id_centro_trabajo = $datos['id_centro_trabajo'];
			$id_puesto = $datos['id_puesto'];

			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$puesto = strtoupper($datos['puesto']);
			$email = strtolower($datos['email']);
			$telefono = $datos['telefono'];
			$celular = $datos['celular'];
			
			$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $id_jefe, $id_departamento, $id_centro_trabajo, $id_puesto, $id);
			$sth = $this->_db->prepare("UPDATE empleados SET
										nombre = ?,
										apellidos = ?,
										puesto = ?,
										email = ?,
										telefono = ?,
										celular = ?,
										id_jefe = ?,
										id_departamento = ?,
										id_centro_trabajo = ?,
										id_puesto = ?
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
	  		header('Location: ' . STASIS . '/empleados/operacion');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE empleados SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/operacion');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoEmpleados() {
		try {
			$sth = $this->_db->query("SELECT id, CONCAT(nombre, ' ', apellidos) AS empleado
				FROM empleados
				WHERE status = 1 AND tipo = 2
				ORDER BY nombre ASC, apellidos ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				$html .= '<option value="' . $datos['id'] . '">' . $datos['empleado'] . '</option>';
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function infoEmpleado() {
		try {
			$array = array();

			$sth = $this->_db->prepare("SELECT email, celular, puesto FROM empleados WHERE id = ?");
			$sth->bindParam(1, $_POST['id']);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$array = array(
				'email' => $datos['email'],
				'celular' => $datos['celular'],
				'puesto' => $datos['puesto']
			);

	  		echo json_encode($array);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}