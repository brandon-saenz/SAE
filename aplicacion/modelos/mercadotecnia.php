<?php
final class Modelos_Mercadotecnia extends Modelo {
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
				SELECT id, seccion, manzana, lote, nombre, email, telefono1, telefono2, superficie, nombreUsuario, clave_catastral
				FROM propietarios
				WHERE tipo = 'IRT' AND status = 1
				ORDER BY nombre ASC
			");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				if (!empty($datos['clave_catastral'])) {
					$claveCatastral = '<a href="' . STASIS . '/data/f/' . $datos['clave_catastral'] . '">' . $datos['clave_catastral'] . '</a>';
				} else {
					$claveCatastral = '';
				}

				$arreglo = array(
					'id' => $datos['id'],
					'seccion' => mb_strtoupper($datos['seccion'], 'UTF-8'),
					'manzana' => str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT),
					'lote' => str_pad($datos['lote'], 2, '0', STR_PAD_LEFT),
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'email' => strtolower($datos['email']),
					'telefono1' => $datos['telefono1'],
					'telefono2' => $datos['telefono2'],
					'clave_catastral' => $claveCatastral,
					'superficie' => number_format($datos['superficie'], 2, '.', ','),
					'nombreUsuario' => $datos['nombreUsuario'],
				);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("
				SELECT id, seccion, manzana, lote, nombre, email, telefono1, telefono2, superficie, nombreUsuario, clave_catastral
				FROM propietarios
				WHERE tipo = 'IRT' AND status = 0
				ORDER BY nombre ASC
			");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				if (!empty($datos['clave_catastral'])) {
					$claveCatastral = '<a href="' . STASIS . '/data/f/' . $datos['clave_catastral'] . '">' . $datos['clave_catastral'] . '</a>';
				} else {
					$claveCatastral = '';
				}

				$arreglo = array(
					'id' => $datos['id'],
					'seccion' => mb_strtoupper($datos['seccion'], 'UTF-8'),
					'manzana' => str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT),
					'lote' => str_pad($datos['lote'], 2, '0', STR_PAD_LEFT),
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'email' => strtolower($datos['email']),
					'telefono1' => $datos['telefono1'],
					'telefono2' => $datos['telefono2'],
					'clave_catastral' => $claveCatastral,
					'superficie' => number_format($datos['superficie'], 2, '.', ','),
					'nombreUsuario' => $datos['nombreUsuario'],
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
			$nombre = mb_strtoupper($_POST['nombre']);
			$seccion = mb_strtoupper($_POST['seccion']);
			$manzana = $_POST['manzana'];
			$lote = $_POST['lote'];
			$email = strtolower($_POST['email']);
			$telefono1 = $_POST['telefono1'];
			$telefono2 = $_POST['telefono2'];
			$superficie = $_POST['superficie'];
			$tipo = $_POST['tipo'];

			$contrasena = strtok(mb_strtolower($datos['nombre']), ' ');
			$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
			$contrasenaEncriptada = hash("sha256", $contrasena.$salt);

			$arregloDatos = array($nombre, $seccion, $manzana, $lote, $email, $telefono1, $telefono2, $superficie, $salt, $contrasenaEncriptada, $tipo, $contrasena);
			$sth = $this->_db->prepare("INSERT INTO propietarios (nombre, seccion, manzana, lote, email, telefono1, telefono2, superficie, salt, contrasena, tipo, nombreUsuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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

							$sth = $this->_db->prepare("UPDATE propietarios SET foto = ? WHERE id = ?");
							$sth->bindParam(1, $archivo);
							$sth->bindParam(2, $id);
							$sth->execute();
						}
					}
				}

				$this->mensajes[] = Modelos_Sistema::status(2, 'Propietario agregado exitosamente.');
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

			$nombre = mb_strtoupper($_POST['nombre']);
			$seccion = mb_strtoupper($_POST['seccion']);
			$manzana = $_POST['manzana'];
			$lote = $_POST['lote'];
			$email = strtolower($_POST['email']);
			$telefono1 = $_POST['telefono1'];
			$telefono2 = $_POST['telefono2'];
			$superficie = $_POST['superficie'];

			$contrasena = strtok(mb_strtolower($datos['nombre']), ' ');
			$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
			$contrasenaEncriptada = hash("sha256", $contrasena.$salt);

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

						$sth = $this->_db->prepare("UPDATE propietarios SET foto = ? WHERE id = ?");
						$sth->bindParam(1, $archivo);
						$sth->bindParam(2, $id);
						$sth->execute();
					}
				}
			}

			$arregloDatos = array($nombre, $seccion, $manzana, $lote, $email, $telefono1, $telefono2, $superficie, $salt, $contrasenaEncriptada, $contrasena, $id);
			$sth = $this->_db->prepare("UPDATE propietarios SET
										nombre = ?,
										seccion = ?,
										manzana = ?,
										lote = ?,
										email = ?,
										telefono1 = ?,
										telefono2 = ?,
										superficie = ?,
										salt = ?,
										contrasena = ?,
										nombreUsuario = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Propietario modificado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE id = ?");
			$sth->bindParam(1, $id);
			$sth->setFetchMode(PDO::FETCH_INTO, $this);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			if (!$datos->foto) {
				$this->foto = STASIS . '/img/sin-imagen.png';
			} else {
				$this->foto = STASIS . '/data/f/' . $datos->foto;
			}

			// Contraseña
			$contrasena = strtok(mb_strtolower($datos->nombre), ' ');
			$this->contrasena = $contrasena;

	  		return $this;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function inactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE propietarios SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/propietariosirt');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE propietarios SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/propietariosirt');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function getFotoEmpleado($id) {
		try {
			$sth = $this->_db->prepare("SELECT foto FROM propietarios WHERE id = ?");
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

	public function generarCc() {
		try {
			$id = $_POST['id'];
			$clave_catastral_cuenta = $_POST['clave_catastral_cuenta'];
			$adeudo = $_POST['adeudo'];

			// Si tiene clave catastral
			if ($clave_catastral_cuenta == 1) {
				// Si tiene adeudo, enviar correo de adeudo
				if ($adeudo != '' && $adeudo != '0' && $adeudo != '0.00') {
					$arregloDatos = array($adeudo, $id);
					$sth = $this->_db->prepare("UPDATE propietarios SET clave_catastral_cuenta = 1, adeudo = ? WHERE id = ?");
					if(!$sth->execute($arregloDatos)) throw New Exception();

					$correo = Modelos_Contenedor::crearModelo('Correo');
					$correo->claveCatastralAdeudo($id);
				// Si no tiene adeudo
				} else {
					if (!$_FILES['archivo']['size'] == 0) {
						require APP . 'inc/class.upload.php';
						
						$handle = new upload($_FILES['archivo']);
						if ($handle->uploaded) {
							$archivo = str_replace(' ', '_', $handle->file_src_name_body) . '-' . time();
							$handle->file_new_name_body   = $archivo;
							$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
							$handle->process(ROOT_DIR . 'data/f/');
							if ($handle->processed) {
								$handle->clean();
							}
						}

						$arregloDatos = array($archivoDb, $id);
						$sth = $this->_db->prepare("UPDATE propietarios SET clave_catastral_cuenta = 1, clave_catastral = ?, adeudo = 0 WHERE id = ?");
						if(!$sth->execute($arregloDatos)) throw New Exception();
					}

					$correo = Modelos_Contenedor::crearModelo('Correo');
					$correo->claveCatastral($id);
				}
			// Si no tiene la clave catastral
			} else {
				$arregloDatos = array($id);
				$sth = $this->_db->prepare("UPDATE propietarios SET clave_catastral_cuenta = 0, adeudo = '0.00' WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

	  		header('Location: ' . STASIS . '/catalogos/propietariosirt/cc/' . $id . '/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}