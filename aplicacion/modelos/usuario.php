<?php
final class Modelos_Usuario extends Modelo {
	protected $_db = null;
	public $nombre;
	public $apellidos;
	public $grado;
	public $email;
	public $telefono;
	public $celular;
	public $extension;
	public $huella;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function obtenerDatos() {
		try {
			$sth = $this->_db->prepare("SELECT nombre, apellidos, grado, email, telefono, celular, extension, huella FROM usuarios WHERE id = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->setFetchMode(PDO::FETCH_INTO, $this);
			if(!$sth->execute()) throw New Exception();
			$sth->fetch();

	  		return $this;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificarDatosPersonales($datos) {
		try {
			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$email = strtolower($datos['email']);
			$grado = $datos['grado'];
			$telefono = $datos['telefono'];
			$extension = $datos['extension'];
			$celular = $datos['celular'];
			$contrasena1 = $datos['contrasena1'];
			$contrasena2 = $datos['contrasena2'];
			
			if ($nombre && $apellidos && $email && $telefono) {
				if (($contrasena1 != '' && $contrasena2 != '') && ($contrasena1 == $contrasena2)) {
					$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
					$contrasenaEncriptada = hash("sha256", $contrasena1.$salt);
					$arregloDatos = array($nombre, $apellidos, $email, $grado, $telefono, $extension, $celular, $salt, $contrasenaEncriptada, $_SESSION['login_id']);

					$sth = $this->_db->prepare("UPDATE usuarios SET
												nombre = ?,
												apellidos = ?,
												grado = ?,
												email = ?,
												telefono = ?,
												extension = ?,
												celular = ?,
												salt = ?,
												contrasena = ?
												WHERE id = ?");
					if($sth->execute($arregloDatos)) {
						$this->mensajes[] = Modelos_Sistema::status(2, 'Datos modificados exitosamente.');
					} else {
						throw New Exception();
					}
				} elseif($contrasena1 == '' && $contrasena2 == '') {
					$arregloDatos = array($nombre, $apellidos, $grado, $email, $telefono, $extension, $celular, $_SESSION['login_id']);

					$sth = $this->_db->prepare("UPDATE usuarios SET 
												nombre = ?,
												apellidos = ?,
												grado = ?,
												email = ?,
												telefono = ?,
												extension = ?,
												celular = ?
												WHERE id = ?");
					if($sth->execute($arregloDatos)) {
						$this->mensajes[] = Modelos_Sistema::status(2, 'Datos modificados exitosamente.');
					} else {
						throw New Exception();
					}
				} else {
					$this->mensajes[] = Modelos_Sistema::status(1, 'Las contrase&ntilde;as no coinciden.');
				}
			} else {
				$this->mensajes[] = Modelos_Sistema::status(1, 'Es necesario llenar los campos obligatorios.');
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function actualizarImagenFondo($imagen) {
		try {
			if (!$_SESSION['login_usuario_sitio']) {
				$sth = $this->_db->prepare("UPDATE usuarios SET skin_imagen_fondo = ? WHERE id = ?");
			} else {
				$sth = $this->_db->prepare("UPDATE usuarios_integraciones SET skin_imagen_fondo = ? WHERE id = ?");
			}
			$arregloDatos = array($imagen, $_SESSION['login_id']);
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Datos modificados exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function solicitudReporteGlobal($id, $tipo) {
		try {
			if ($id != 0) {
				$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
				$hash = hash("sha256", $_SESSION['login_id'].$salt);

				$sth = $this->_db->prepare("INSERT INTO usuarios_solicitudes (id_usuario, tipo_solicitud, hash) VALUES (?, ?, ?)");
				$sth->bindParam(1, $id);
				$sth->bindParam(2, $tipo);
				$sth->bindParam(3, $hash);
				if(!$sth->execute()) throw New Exception();

				return $hash;
		  	}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function verificarSolicitudGlobal($idEmpleado, $tipo) {
		try {
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM usuarios_solicitudes WHERE id_usuario = ? AND tipo_solicitud = ? LIMIT 1");
			$sth->bindParam(1, $idEmpleado);
			$sth->bindParam(2, $tipo);
			if(!$sth->execute()) throw New Exception();

			if ($sth->fetchColumn() >= 1) {
				return 1;
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function verificarReporteGlobal($idEmpleado, $tipo) {
		try {
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM usuarios_solicitudes WHERE id_usuario = ? AND tipo_solicitud = ? AND (status = 1 OR status = 2) LIMIT 1");
			$sth->bindParam(1, $idEmpleado);
			$sth->bindParam(2, $tipo);
			if(!$sth->execute()) throw New Exception();

			if ($sth->fetchColumn() >= 1) {
				return 1;
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cronBorrarSolicitudesTemporales() {
		$sth = $this->_db->query("DELETE FROM usuarios_solicitudes WHERE status = 1 AND fecha_revision < CURDATE()");
		if(!$sth->execute()) throw New Exception();
	}

	public function actualizarSolicitudGlobal($hash, $idEmpleado, $tipo, $status) {
		try {
			$sth = $this->_db->prepare("SELECT email FROM usuarios WHERE id = ? LIMIT 1");
			$sth->bindParam(1, $idEmpleado);
			if(!$sth->execute()) throw New Exception();
			$email = $sth->fetchColumn();

			switch($tipo) {
				case 'c': $titulo = 'Reporte global de clientes'; break;
				case 'p': $titulo = 'Reporte global de proveedores'; break;
				case 'pa': $titulo = 'Reporte global de partes'; break;
			}

			$status == 1? $texto = 'aprobada temporalmente' : $texto = 'aprobada permanentemente';
			
			$correo = Modelos_Contenedor::crearModelo('Correo');
   			$correo->statusSolicitud($correo, $titulo, $texto);

			// Aceptada temporal o permanentemente
			if ($status != 0) {
				$sth = $this->_db->prepare("UPDATE usuarios_solicitudes SET status = ? WHERE hash = ?");
				$sth->bindParam(1, $status);
				$sth->bindParam(2, $hash);
				$sth->execute();
			// Denegada
			} else {
				$sth = $this->_db->prepare("DELETE FROM usuarios_solicitudes WHERE hash = ?");
				$sth->bindParam(1, $hash);
				$sth->execute();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function imagenFondoPersonalizada() {
		$targetFolder = ROOT_DIR . 'static/img/skins/';

		if (!empty($_FILES)) {
			$tempFile = $_FILES['imagen']['tmp_name'];
			$targetPath = $targetFolder;
			$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['imagen']['name'];

			$fileTypes = array('jpg','jpeg','png');
			$fileParts = pathinfo($_FILES['imagen']['name']);
			
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				try {
					$temp = explode('.', $_FILES['imagen']['name']);
					$ext = array_pop( $temp);
					$nombreArchivo = implode('.', $temp);

					if (!$_SESSION['login_usuario_sitio']) {
						$sth = $this->_db->prepare("UPDATE usuarios SET skin_imagen_fondo = ? WHERE id = ?");
					} else {
						$sth = $this->_db->prepare("UPDATE usuarios_integraciones SET skin_imagen_fondo = ? WHERE id = ?");
					}

					$arregloDatos = array($nombreArchivo, $_SESSION['login_id']);
					if($sth->execute($arregloDatos)) {
						$this->mensajes[] = Modelos_Sistema::status(2, 'Datos modificados exitosamente.');
						header("Location:" . STASIS . "/usuario/tema");
					} else {
						throw New Exception();
					}
				} catch (Exception $e) {
					$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
				}
			}
		}
	}
}