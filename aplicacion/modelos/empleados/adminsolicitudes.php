<?php
final class Modelos_Empleados_AdminSolicitudes extends Modelo {
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
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.puesto, e.email, e.telefono, e.celular, e.id_jefe, e.evaluador
				FROM empleados e
				WHERE e.tipo = 4 AND e.status = 1
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT CONCAT(nombre, ' ', apellidos) AS jefe_directo FROM empleados WHERE id = ?");
				$sth2->bindParam(1, $datos['id_jefe']);
				if(!$sth2->execute()) throw New Exception();
				$jefeDirecto = $sth2->fetchColumn();

				switch ($datos['evaluador']) {
					case 1: $evaluador = 'INTERNO'; break;
					case 2: $evaluador = 'EXTERNO'; break;
				}

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'jefe_directo' => mb_strtoupper($jefeDirecto, 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'evaluador' => $evaluador,
								 'celular' => $datos['celular'],
								 'puesto' => $datos['puesto']
								);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.puesto, e.email, e.telefono, e.celular, e.id_jefe, e.evaluador
				FROM empleados e
				WHERE e.tipo = 4 AND e.status = 0
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT CONCAT(nombre, ' ', apellidos) AS jefe_directo FROM empleados WHERE id = ?");
				$sth2->bindParam(1, $datos['id_jefe']);
				if(!$sth2->execute()) throw New Exception();
				$jefeDirecto = $sth2->fetchColumn();

				switch ($datos['evaluador']) {
					case 1: $evaluador = 'INTERNO'; break;
					case 2: $evaluador = 'EXTERNO'; break;
				}

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'jefe_directo' => mb_strtoupper($jefeDirecto, 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'evaluador' => $evaluador,
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
			$evaluador = $datos['evaluador'];
			$nombreUsuario = $datos['nombreUsuario'];
			$contrasena = $datos['contrasena'];
			$autorizar = $datos['autorizar'];

			$id_departamento = $datos['id_departamento'];
			$id_centro_trabajo = $datos['id_centro_trabajo'];
			$id_puesto = $datos['id_puesto'];

			$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
			$contrasenaEncriptada = hash("sha256", $contrasena.$salt);
			$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $evaluador, $salt, $contrasenaEncriptada, $nombreUsuario, $contrasena, $id_departamento, $id_centro_trabajo, $id_puesto, $autorizar);

			$sth = $this->_db->prepare("INSERT INTO empleados (nombre, apellidos, puesto, email, telefono, celular, evaluador, salt, contrasena, tipo, nombreUsuario, num, id_departamento, id_centro_trabajo, id_puesto, autorizar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 4, ?, ?, ?, ?, ?, ?)");
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

				$this->mensajes[] = Modelos_Sistema::status(2, 'Administrador agregado exitosamente.');
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
			$id_jefe = $datos['id_jefe'];
			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$puesto = strtoupper($datos['puesto']);
			$email = strtolower($datos['email']);
			$telefono = $datos['telefono'];
			$celular = $datos['celular'];
			$evaluador = $datos['evaluador'];
			$nombreUsuario = $datos['nombreUsuario'];
			$autorizar = $datos['autorizar'];

			$id_departamento = $datos['id_departamento'];
			$id_centro_trabajo = $datos['id_centro_trabajo'];
			$id_puesto = $datos['id_puesto'];

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
			
			$arregloDatos = array($nombre, $apellidos, $puesto, $email, $telefono, $celular, $evaluador, $id_jefe, $nombreUsuario, $id_departamento, $id_centro_trabajo, $id_puesto, $autorizar, $id);
			$sth = $this->_db->prepare("UPDATE empleados SET
										nombre = ?,
										apellidos = ?,
										puesto = ?,
										email = ?,
										telefono = ?,
										celular = ?,
										evaluador = ?,
										id_jefe = ?,
										nombreUsuario = ?,
										id_departamento = ?,
										id_centro_trabajo = ?,
										id_puesto = ?,
										autorizar = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Administrador modificado exitosamente.');
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
	  		header('Location: ' . STASIS . '/empleados/adsolicitudes');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE empleados SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/adsolicitudes');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoEmpleados() {
		try {
			$sth = $this->_db->query("SELECT id, CONCAT(nombre, ' ', apellidos) AS empleado
				FROM empleados
				WHERE status = 1 AND tipo = 4
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