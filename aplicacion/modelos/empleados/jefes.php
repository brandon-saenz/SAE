<?php
final class Modelos_Empleados_Jefes extends Modelo {
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
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo = 1 AND e.status = 1
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento']
								);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo = 1 AND e.status = 0
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento']
								);
				$inactivos[] = $arreglo;
			}

			$datosVista['inactivos'] = $inactivos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoJefesDirectosSolicitudes() {
		try {
			$datosVista = array();

			// Activos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo = 5 AND e.status = 1
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento']
								);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo = 5 AND e.status = 0
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento']
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
			$nombreUsuario = $datos['nombreUsuario'];
			$contrasena = $datos['contrasena'];
			
			$id_departamento = $datos['id_departamento'];
			$id_centro_trabajo = $datos['id_centro_trabajo'];
			$id_puesto = $datos['id_puesto'];

			$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
			$contrasenaEncriptada = hash("sha256", $contrasena.$salt);
			$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $salt, $contrasenaEncriptada, $nombreUsuario, $contrasena, $id_departamento, $id_centro_trabajo, $id_puesto);

			$sth = $this->_db->prepare("INSERT INTO empleados (nombre, apellidos, puesto, email, telefono, celular, salt, contrasena, tipo, nombreUsuario, num, id_departamento, id_centro_trabajo, id_puesto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?)");
			if($sth->execute($arregloDatos)) {
				$id = $this->_db->lastInsertId();

				if (isset($_FILES['foto'])) {
					require APP . 'inc/class.upload.php';
					$archivoTime = uniqid();

					$handle = new upload($_FILES['foto']);
					if ($handle->uploaded) {
						$handle->image_resize         = true;
						$handle->image_x              = 300;
						$handle->image_ratio_y        = true;

						$archivoExtension = $handle->file_src_name_ext;
						$nombreOriginal = $handle->file_src_name;

						$handle->file_new_name_body = $archivoTime;
						
						$handle->process(ROOT_DIR . '/data/f/');
						if ($handle->processed) {
							$archivo = $archivoTime . '.' . $handle->file_src_name_ext;

							$sth = $this->_db->prepare("UPDATE empleados SET foto = ? WHERE id = ?");
							$sth->bindParam(1, $archivo);
							$sth->bindParam(2, $id);
							$sth->execute();
						}
					}
				}
				
				$this->mensajes[] = Modelos_Sistema::status(2, 'Jefe directo agregado exitosamente.');
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
			
			$id_departamento = $datos['id_departamento'];
			$id_centro_trabajo = $datos['id_centro_trabajo'];
			$id_puesto = $datos['id_puesto'];

			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$puesto = strtoupper($datos['puesto']);
			$email = strtolower($datos['email']);
			$telefono = $datos['telefono'];
			$celular = $datos['celular'];
			$nombreUsuario = $datos['nombreUsuario'];
			
			$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $id_departamento, $id_centro_trabajo, $id_puesto, $nombreUsuario, $id);
			$sth = $this->_db->prepare("UPDATE empleados SET
										nombre = ?,
										apellidos = ?,
										puesto = ?,
										email = ?,
										telefono = ?,
										celular = ?,
										id_departamento = ?,
										id_centro_trabajo = ?,
										id_puesto = ?,
										nombreUsuario = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				if (isset($_FILES['foto'])) {
					require APP . 'inc/class.upload.php';
					$archivoTime = uniqid();

					$handle = new upload($_FILES['foto']);
					if ($handle->uploaded) {
						$handle->image_resize         = true;
						$handle->image_x              = 300;
						$handle->image_ratio_y        = true;

						$archivoExtension = $handle->file_src_name_ext;
						$nombreOriginal = $handle->file_src_name;

						$handle->file_new_name_body = $archivoTime;
						
						$handle->process(ROOT_DIR . '/data/f/');
						if ($handle->processed) {
							$archivo = $archivoTime . '.' . $handle->file_src_name_ext;

							$sth = $this->_db->prepare("UPDATE empleados SET foto = ? WHERE id = ?");
							$sth->bindParam(1, $archivo);
							$sth->bindParam(2, $id);
							$sth->execute();
						}
					}
				}

				$this->mensajes[] = Modelos_Sistema::status(2, 'Jefe directo modificado exitosamente.');
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
			$datos = $sth->fetch();

			if (!$datos->foto) {
				$this->foto = STASIS . '/img/sin-imagen.png';
			} else {
				$this->foto = STASIS . '/data/f/' . $datos->foto;
			}

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
	  		header('Location: ' . STASIS . '/empleados/jefes');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE empleados SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/jefes');
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