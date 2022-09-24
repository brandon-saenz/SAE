<?php
final class Modelos_Empleados_Colaboradores extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function listado() {
		try {
			$datosVista = array();

			// Actualizacion de password y nombre de usuario
			// $usuariosArray = [];
			// $sth = $this->_db->query("SELECT id, nombre, apellidos FROM empleados WHERE tipo = 2 AND nombreUsuario IS NULL");
			// if(!$sth->execute()) throw New Exception();
			// while ($datos = $sth->fetch()) {
			// 	$idEmpleado = $datos['id'];
				
			// 	// Numero
			// 	$contrasena = rand(1000, 9999);
			// 	$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
			// 	$contrasenaEncriptada = hash("sha256", $contrasena.$salt);

			// 	// Nombre de usuario
			// 	$nombre = substr($datos['nombre'], strpos($datos['nombre'], ",") + 2);
			// 	if ( preg_match('/\s/',$nombre) ) {
			// 		$nombre = substr($nombre, 0, strpos($nombre, ' '));
			// 	}

			// 	$nombreUsuario = mb_strtolower($nombre . '.' . substr($datos['nombre'], 0, strpos($datos['nombre'], ' ')));

			// 	if (in_array($nombreUsuario, $usuariosArray)) {
			// 		$nombreUsuario = $nombreUsuario . '2';
			// 	}
			// 	$usuariosArray[] = $nombreUsuario;

			// 	$sth2 = $this->_db->prepare("UPDATE empleados SET contrasena = ?, salt = ?, num = ?, nombreUsuario = ? WHERE id = ?");
			// 	$sth2->bindParam(1, $contrasenaEncriptada);
			// 	$sth2->bindParam(2, $salt);
			// 	$sth2->bindParam(3, $contrasena);
			// 	$sth2->bindParam(4, $nombreUsuario);
			// 	$sth2->bindParam(5, $datos['id']);
			// 	if(!$sth2->execute()) throw New Exception();
			// }

			// // Actualizacion de departamento y puesto
			// $sth = $this->_db->query("SELECT id, nombre, apellidos, puesto, departamento FROM empleados WHERE tipo = 2");
			// if(!$sth->execute()) throw New Exception();
			// while ($datos = $sth->fetch()) {
			// 	$idEmpleado = $datos['id'];

			// 	// Departamento
			// 	$sth2 = $this->_db->prepare("SELECT id FROM departamentos WHERE nombre = ?");
			// 	$sth2->bindParam(1, $datos['departamento']);
			// 	if(!$sth2->execute()) throw New Exception();
			// 	$idDepartamento = $sth2->fetchColumn();

			// 	$sth2 = $this->_db->prepare("UPDATE empleados SET id_departamento = ? WHERE id = ?");
			// 	$sth2->bindParam(1, $idDepartamento);
			// 	$sth2->bindParam(2, $idEmpleado);
			// 	if(!$sth2->execute()) throw New Exception();

			// 	// Puesto
			// 	$sth2 = $this->_db->prepare("SELECT id FROM puestos WHERE nombre = ?");
			// 	$sth2->bindParam(1, $datos['puesto']);
			// 	if(!$sth2->execute()) throw New Exception();
			// 	$idPuesto = $sth2->fetchColumn();

			// 	$sth2 = $this->_db->prepare("UPDATE empleados SET id_puesto = ? WHERE id = ?");
			// 	$sth2->bindParam(1, $idPuesto);
			// 	$sth2->bindParam(2, $idEmpleado);
			// 	if(!$sth2->execute()) throw New Exception();
			// }

			// Activos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento, e.num, e.nombreUsuario, e.calificacion
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

				if ($datos['calificacion']) {
					if ($datos['calificacion'] < 50 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #9CE5F6; border-radius: 25px; "></span> <strong>Nulo</strong>';
					} elseif ($datos['calificacion'] >= 50 && $datos['calificacion'] < 75 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #6BF56E; border-radius: 25px; "></span> <strong>Bajo</strong>';
					} elseif ($datos['calificacion'] >= 75 && $datos['calificacion'] < 99 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFFF00; border-radius: 25px; "></span> <strong>Medio</strong>';
					} elseif ($datos['calificacion'] >= 99 && $datos['calificacion'] < 140 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFC000; border-radius: 25px; "></span> <strong>Alto</strong>';
					} elseif ($datos['calificacion'] >= 140 ) {
						$resultado = '<div style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FE0000; border-radius: 25px; "></div> <strong>Muy alto</strong>';
					}
				} else {
					$resultado = '';
				}

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'jefe_directo' => mb_strtoupper($jefeDirecto, 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'num' => $datos['num'],
								 'calificacion' => $datos['calificacion'],
								 'resultado' => $resultado,
								 'usuario' => $datos['nombreUsuario'],
								 
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento'],
								);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento, e.num, e.nombreUsuario, e.calificacion
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
								 'num' => $datos['num'],
								 'calificacion' => $datos['calificacion'],
								 'usuario' => $datos['nombreUsuario'],
								 
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

	public function listadoDashboard() {
		try {
			$datosVista = array();

			// Activos
			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento, e.num, e.nombreUsuario, e.calificacion
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo != 3 AND e.status = 1
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT CONCAT(nombre, ' ', apellidos) AS jefe_directo FROM empleados WHERE id = ?");
				$sth2->bindParam(1, $datos['id_jefe']);
				if(!$sth2->execute()) throw New Exception();
				$jefeDirecto = $sth2->fetchColumn();

				if ($datos['calificacion']) {
					if ($datos['calificacion'] < 50 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #9CE5F6; border-radius: 25px; "></span> <strong>Nulo</strong>';
					} elseif ($datos['calificacion'] >= 50 && $datos['calificacion'] < 75 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #6BF56E; border-radius: 25px; "></span> <strong>Bajo</strong>';
					} elseif ($datos['calificacion'] >= 75 && $datos['calificacion'] < 99 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFFF00; border-radius: 25px; "></span> <strong>Medio</strong>';
					} elseif ($datos['calificacion'] >= 99 && $datos['calificacion'] < 140 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFC000; border-radius: 25px; "></span> <strong>Alto</strong>';
					} elseif ($datos['calificacion'] >= 140 ) {
						$resultado = '<div style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FE0000; border-radius: 25px; "></div> <strong>Muy alto</strong>';
					}
				} else {
					$resultado = '';
				}

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'jefe_directo' => mb_strtoupper($jefeDirecto, 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'num' => $datos['num'],
								 'calificacion' => $datos['calificacion'],
								 'resultado' => $resultado,
								 'usuario' => $datos['nombreUsuario'],
								 
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento'],
								);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function nuevo($datos) {
		try {
			$id_jefe = $datos['id_jefe'];
			$id_departamento = $datos['id_departamento'];
			$id_centro_trabajo = $datos['id_centro_trabajo'];
			$id_puesto = $datos['id_puesto'];

			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$genero = $datos['genero'];
			$email = strtolower($datos['email']);
			$telefono = $datos['telefono'];
			$celular = $datos['celular'];
			$nombreUsuario = $datos['nombreUsuario'];
			$contrasena = $datos['contrasena'];

			$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
			$contrasenaEncriptada = hash("sha256", $contrasena.$salt);
			$arregloDatos = array($nombre, $apellidos, $genero, $email, $telefono, $celular, $salt, $contrasenaEncriptada, $id_jefe, $id_departamento, $id_centro_trabajo, $id_puesto, $nombreUsuario, $contrasena);

			$sth = $this->_db->prepare("INSERT INTO empleados (nombre, apellidos, genero, email, telefono, celular, salt, contrasena, id_jefe, id_departamento, id_centro_trabajo, id_puesto, nombreUsuario, num, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 2)");
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

				$this->mensajes[] = Modelos_Sistema::status(2, 'Colaborador agregado exitosamente.');
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
			$id_departamento = $datos['id_departamento'];
			$id_centro_trabajo = $datos['id_centro_trabajo'];
			$id_puesto = $datos['id_puesto'];

			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$genero = $datos['genero'];
			$puesto = strtoupper($datos['puesto']);
			$email = strtolower($datos['email']);
			$telefono = $datos['telefono'];
			$celular = $datos['celular'];
			$nombreUsuario = $datos['nombreUsuario'];

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

			$arregloDatos = array($nombre, $apellidos, $genero, $puesto, $email, $telefono, $celular, $id_jefe, $id_departamento, $id_centro_trabajo, $id_puesto, $nombreUsuario, $id);
			$sth = $this->_db->prepare("UPDATE empleados SET
										nombre = ?,
										apellidos = ?,
										genero = ?,
										puesto = ?,
										email = ?,
										telefono = ?,
										celular = ?,
										id_jefe = ?,
										id_departamento = ?,
										id_centro_trabajo = ?,
										id_puesto = ?,
										nombreUsuario = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Colaborador modificado exitosamente.');
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
	  		header('Location: ' . STASIS . '/empleados/colaboradores');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE empleados SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/colaboradores');
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

			$sth = $this->_db->prepare("SELECT email, telefono, puesto FROM empleados WHERE id = ?");
			$sth->bindParam(1, $_POST['id']);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$array = array(
				'email' => $datos['email'],
				'telefono' => $datos['telefono'],
				'puesto' => $datos['puesto']
			);

	  		echo json_encode($array);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function getNombreEmpleado($id) {
		try {
			$sth = $this->_db->prepare("SELECT nombre FROM empleados WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

	  		return $sth->fetchColumn();
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function getPuestoEmpleado($id) {
		try {
			$sth = $this->_db->prepare("
				SELECT p.nombre
				FROM empleados e
				JOIN puestos p
				ON p.id = e.id_puesto
				WHERE e.id = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

	  		return $sth->fetchColumn();
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function getFotoEmpleado($id) {
		try {
			$sth = $this->_db->prepare("SELECT foto FROM empleados WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$foto = $sth->fetchColumn();

			if ($foto) {
				return '<img src="' . STASIS . '/data/f/' . $foto . '" height="150" style="margin: 20px 20px 0;" /><br />';
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoUsuarios() {
		try {
			$sth = $this->_db->query("SELECT id, CONCAT(nombre, ' ', apellidos) AS empleado
				FROM empleados
				WHERE status = 1 AND (tipo = 2 OR tipo = 1)
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

	public function listadoPendientes() {
		try {
			$datosVista = array();

			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento, e.num, e.nombreUsuario
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo != 3 AND e.status = 1
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				// Encuesta 1 llenada
				$sth2 = $this->_db->prepare("SELECT COUNT(id) FROM evaluaciones WHERE id_usuario = ? AND tipo_evaluacion = 1");
				$sth2->bindParam(1, $datos['id']);
				if(!$sth2->execute()) throw New Exception();
				if ($sth2->fetchColumn()) {
					$encuesta1 = 1;
				} else {
					$encuesta1 = 0;
				}

				// Encuesta 3 llenada
				$sth2 = $this->_db->prepare("SELECT COUNT(id) FROM evaluaciones WHERE id_usuario = ? AND tipo_evaluacion = 3");
				$sth2->bindParam(1, $datos['id']);
				if(!$sth2->execute()) throw New Exception();
				if ($sth2->fetchColumn()) {
					$encuesta3 = 1;
				} else {
					$encuesta3 = 0;
				}

				if (!$encuesta1 && !$encuesta3) {
					$arreglo = array('id' => $datos['id'],
									 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
									 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
									 'email' => strtolower($datos['email']),
									 'telefono' => $datos['telefono'],
									 
									 'puesto' => $datos['puesto'],
									 'centro_trabajo' => $datos['centro_trabajo'],
									 'departamento' => $datos['departamento'],
									);
					$activos[] = $arreglo;
				}
			}

	  		$datosVista['activos'] = $activos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoRealizadas() {
		try {
			$datosVista = array();

			$sth = $this->_db->query("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento, e.num, e.nombreUsuario, e.calificacion
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.tipo != 3 AND e.status = 1
				ORDER BY e.nombre ASC, e.apellidos ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				// Encuesta 1 llenada
				$sth2 = $this->_db->prepare("SELECT COUNT(id) FROM evaluaciones WHERE id_usuario = ? AND tipo_evaluacion = 1");
				$sth2->bindParam(1, $datos['id']);
				if(!$sth2->execute()) throw New Exception();
				if ($sth2->fetchColumn()) {
					$encuesta1 = 1;
				} else {
					$encuesta1 = 0;
				}

				// Encuesta 3 llenada
				$sth2 = $this->_db->prepare("SELECT COUNT(id) FROM evaluaciones WHERE id_usuario = ? AND tipo_evaluacion = 3");
				$sth2->bindParam(1, $datos['id']);
				if(!$sth2->execute()) throw New Exception();
				if ($sth2->fetchColumn()) {
					$encuesta3 = 1;
				} else {
					$encuesta3 = 0;
				}

				if ($encuesta1 && $encuesta3) {
					if ($datos['calificacion']) {
						if ($datos['calificacion'] < 50 ) {
							$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #9CE5F6; border-radius: 25px; "></span> <strong>Nulo</strong>';
						} elseif ($datos['calificacion'] >= 50 && $datos['calificacion'] < 75 ) {
							$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #6BF56E; border-radius: 25px; "></span> <strong>Bajo</strong>';
						} elseif ($datos['calificacion'] >= 75 && $datos['calificacion'] < 99 ) {
							$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFFF00; border-radius: 25px; "></span> <strong>Medio</strong>';
						} elseif ($datos['calificacion'] >= 99 && $datos['calificacion'] < 140 ) {
							$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFC000; border-radius: 25px; "></span> <strong>Alto</strong>';
						} elseif ($datos['calificacion'] >= 140 ) {
							$resultado = '<div style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FE0000; border-radius: 25px; "></div> <strong>Muy alto</strong>';
						}
					} else {
						$resultado = '';
					}

					$arreglo = array('id' => $datos['id'],
									 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
									 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
									 'email' => strtolower($datos['email']),
									 'telefono' => $datos['telefono'],
									 'calificacion' => $datos['calificacion'],
									 'resultado' => $resultado,
									 
									 'puesto' => $datos['puesto'],
									 'centro_trabajo' => $datos['centro_trabajo'],
									 'departamento' => $datos['departamento'],
									);
					$activos[] = $arreglo;
				}
			}

	  		$datosVista['activos'] = $activos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoAts() {
		try {
			$datosVista = array();

			$empleadosAts = [];
			$sth = $this->_db->prepare("
				SELECT id_usuario, pregunta, respuesta
				FROM evaluaciones e
				JOIN evaluaciones_respuestas er
				ON er.id_evaluacion = e.id
				WHERE e.tipo_evaluacion = 1
			");
			if(!$sth->execute()) throw New Exception();
			$x = 0;
			while ($datos = $sth->fetch()) {
				if ($datos['pregunta'] >= 1 && $datos['pregunta'] <= 20) {
					if ($datos['respuesta'] == 1) {
						if (!in_array($datos['id_usuario'], $empleadosAts)) {
							$empleadosAts[] = $datos['id_usuario'];
						}
					}
				}
			}

			$activos = array();
			foreach ($empleadosAts as $key => $idEmpleado) {
				$sth = $this->_db->prepare("SELECT e.id, e.nombre, e.apellidos, e.email, e.telefono, e.id_jefe, p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento, e.num, e.nombreUsuario, e.calificacion
					FROM empleados e
					LEFT JOIN puestos p
					ON p.id = e.id_puesto
					LEFT JOIN centros_trabajo ct
					ON ct.id = e.id_centro_trabajo
					LEFT JOIN departamentos d
					ON d.id = e.id_departamento
					WHERE e.tipo != 3 AND e.status = 1 AND e.id = ?
					ORDER BY e.nombre ASC, e.apellidos ASC");
				$sth->bindParam(1, $idEmpleado);
				if(!$sth->execute()) throw New Exception();
				
				$datos = $sth->fetch();

				if ($datos['calificacion']) {
					if ($datos['calificacion'] < 50 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #9CE5F6; border-radius: 25px; "></span> <strong>Nulo</strong>';
					} elseif ($datos['calificacion'] >= 50 && $datos['calificacion'] < 75 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #6BF56E; border-radius: 25px; "></span> <strong>Bajo</strong>';
					} elseif ($datos['calificacion'] >= 75 && $datos['calificacion'] < 99 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFFF00; border-radius: 25px; "></span> <strong>Medio</strong>';
					} elseif ($datos['calificacion'] >= 99 && $datos['calificacion'] < 140 ) {
						$resultado = '<span style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FFC000; border-radius: 25px; "></span> <strong>Alto</strong>';
					} elseif ($datos['calificacion'] >= 140 ) {
						$resultado = '<div style="margin-top: -1px; padding: 10px; vertical-align: middle; display: inline-block; background-color: #FE0000; border-radius: 25px; "></div> <strong>Muy alto</strong>';
					}
				} else {
					$resultado = '';
				}

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'calificacion' => $datos['calificacion'],
								 'resultado' => $resultado,
								 
								 'puesto' => $datos['puesto'],
								 'centro_trabajo' => $datos['centro_trabajo'],
								 'departamento' => $datos['departamento'],
								);
				$activos[] = $arreglo;
		  	}

		  	$datosVista['activos'] = $activos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}