<?php
final class Modelos_Empleados_Administrar extends Modelo {
	protected $_db = null;
	public $nombreUsuario;
	public $nombre;
	public $apellidos;
	public $grado;
	public $email;
	public $telefono;
	public $celular;
	public $extension;
	public $huella;
	public $sitio;
	public $puesto;
	public $horario_inicio;
	public $horario_fin;
	public $dia_inicio;
	public $dia_fin;
	public $activos = array();
	public $inactivos = array();
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function obtenerDatos() {
		try {
			// Activos
			$qryContador = "SELECT COUNT(id) FROM usuarios WHERE status = 1";
			$qry = "SELECT usuarios.puesto, usuarios.id, usuarios.nombre, usuarios.apellidos, sitios.nombre AS sitio, usuarios.email, usuarios.telefono, usuarios.extension, usuarios.celular FROM usuarios JOIN sitios ON usuarios.sitio = sitios.id WHERE usuarios.status = 1 AND usuarios.id != 1 ORDER BY sitios.nombre, usuarios.nombre ASC";
			$limite = 500;
			$adyacentes = 6;
			$paginaLink = STASIS . '/catalogos/empleados/?p=';

			$paginacion = Modelos_Contenedor::crearModelo('paginacion');
			$paginacion->crear($qryContador, $qry, $limite, $adyacentes, $paginaLink);
			$this->paginacionHtml = $paginacion->mostrar();

			$sth = $this->_db->query($paginacion->query());
			if(!$sth->execute()) throw New Exception();

			$datosVista = array();
			while ($datos = $sth->fetch()) {
				switch ($datos['puesto']) {
					case '1': $puesto = 'ADMINISTRADOR'; break;
					case '2': $puesto = 'GERENTE DE SITIO'; break;
					case '3': $puesto = 'COMPRAS/VENTAS'; break;
					case '4': $puesto = 'ALMACENISTA'; break;
					case '5': $puesto = 'LOGISTICA'; break;
					case '6': $puesto = 'FACTURACION'; break;
					case '7': $puesto = 'IMPORT/EXPORT'; break;
					case '8': $puesto = 'UTILIDADES'; break;
					case '9': $puesto = 'FINANZAS'; break;
					case '10': $puesto = 'CONTABILIDAD'; break;
					default: $puesto = ''; break;
				}

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'sitio' => mb_strtoupper($datos['sitio'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'puesto' => $puesto,
								 'extension' => $datos['extension'],
								 'celular' => $datos['celular']);
				$datosVista[] = $arreglo;
			}

	  		$this->activos = $datosVista;

	  		// Inactivos
	  		$qryContador = "SELECT COUNT(id) FROM usuarios WHERE status = 0";
			$qry = "SELECT usuarios.puesto, usuarios.id, usuarios.nombre, usuarios.apellidos, sitios.nombre AS sitio, usuarios.email, usuarios.telefono, usuarios.extension, usuarios.celular FROM usuarios JOIN sitios ON usuarios.sitio = sitios.id WHERE usuarios.status = 0 AND usuarios.id != 1 ORDER BY sitios.nombre, usuarios.nombre ASC";
			$limite = 500;
			$adyacentes = 6;
			$paginaLink = STASIS . '/catalogos/empleados/?p=';

			$paginacion = Modelos_Contenedor::crearModelo('paginacion');
			$paginacion->crear($qryContador, $qry, $limite, $adyacentes, $paginaLink);
			$this->paginacionHtml = $paginacion->mostrar();

			$sth = $this->_db->query($paginacion->query());
			if(!$sth->execute()) throw New Exception();

			$datosVista = array();
			while ($datos = $sth->fetch()) {
				switch ($datos['puesto']) {
					case '1': $puesto = 'ADMINISTRADOR'; break;
					case '2': $puesto = 'GERENTE DE SITIO'; break;
					case '3': $puesto = 'COMPRAS/VENTAS'; break;
					case '4': $puesto = 'ALMACENISTA'; break;
					case '5': $puesto = 'LOGISTICA'; break;
					case '6': $puesto = 'FACTURACION'; break;
					case '7': $puesto = 'IMPORT/EXPORT'; break;
					case '8': $puesto = 'UTILIDADES'; break;
					case '9': $puesto = 'FINANZAS'; break;
					case '10': $puesto = 'CONTABILIDAD'; break;
					default: $puesto = ''; break;
				}

				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'sitio' => mb_strtoupper($datos['sitio'], 'UTF-8'),
								 'email' => strtolower($datos['email']),
								 'telefono' => $datos['telefono'],
								 'puesto' => $puesto,
								 'extension' => $datos['extension'],
								 'celular' => $datos['celular']);
				$datosVista[] = $arreglo;
			}

	  		$this->inactivos = $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function nuevo($datos) {
		try {
			$nombreUsuario = Modelos_Caracteres::generar_slug($datos['nombreUsuario']);
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
					$arregloDatos = array($nombreUsuario, $nombre, $apellidos, $email, $grado, $telefono, $extension, $celular, $salt, $contrasenaEncriptada);

					$sth = $this->_db->prepare("INSERT INTO usuarios (nombreUsuario, nombre, apellidos, email, grado, telefono, extension, celular, salt, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
					if($sth->execute($arregloDatos)) {
						$this->mensajes[] = Modelos_Sistema::status(2, 'Empleado agregado exitosamente.');
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

	public function modificarGuardar($datos) {
		try {
			$id = $datos['id'];
			$nombreUsuario = Modelos_Caracteres::generar_slug($datos['nombreUsuario']);
			$nombre = strtoupper($datos['nombre']);
			$apellidos = strtoupper($datos['apellidos']);
			$email = strtolower($datos['email']);
			$grado = $datos['grado'];
			$telefono = $datos['telefono'];
			$sitio = $datos['sitio'];
			$puesto = $datos['puesto'];
			$extension = $datos['extension'];
			$celular = $datos['celular'];
			$contrasena1 = $datos['contrasena1'];
			$contrasena2 = $datos['contrasena2'];
			$horario_inicio = $datos['horario_inicio'];
			$horario_fin = $datos['horario_fin'];
			$dia_inicio = $datos['dia_inicio'];
			$dia_fin = $datos['dia_fin'];
			
			if ($nombre && $apellidos && $email && $telefono) {
				if (($contrasena1 != '' && $contrasena2 != '') && ($contrasena1 == $contrasena2)) {
					$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
					$contrasenaEncriptada = hash("sha256", $contrasena1.$salt);
					$arregloDatos = array($nombreUsuario, $nombre, $apellidos, $email, $grado, $telefono, $sitio, $puesto, $extension, $celular, $salt, $horario_inicio, $horario_fin, $dia_inicio, $dia_fin, $contrasenaEncriptada, $id);

					$sth = $this->_db->prepare("UPDATE usuarios SET
												nombreUsuario = ?,
												nombre = ?,
												apellidos = ?,
												grado = ?,
												email = ?,
												telefono = ?,
												sitio = ?,
												puesto = ?,
												extension = ?,
												celular = ?,
												salt = ?,
												horario_inicio = ?,
												horario_fin = ?,
												dia_inicio = ?,
												dia_fin = ?,
												contrasena = ?
												WHERE id = ?");
					if($sth->execute($arregloDatos)) {
						$this->mensajes[] = Modelos_Sistema::status(2, 'Datos modificados exitosamente.');
					} else {
						throw New Exception();
					}
				} elseif($contrasena1 == '' && $contrasena2 == '') {
					$arregloDatos = array($nombreUsuario, $nombre, $apellidos, $grado, $email, $telefono, $sitio, $puesto, $extension, $celular, $horario_inicio, $horario_fin, $dia_inicio, $dia_fin, $id);

					$sth = $this->_db->prepare("UPDATE usuarios SET 
												nombreUsuario = ?,
												nombre = ?,
												apellidos = ?,
												grado = ?,
												email = ?,
												telefono = ?,
												sitio = ?,
												puesto = ?,
												extension = ?,
												celular = ?,
												horario_inicio = ?,
												horario_fin = ?,
												dia_inicio = ?,
												dia_fin = ?
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
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT nombreUsuario, nombre, apellidos, grado, email, telefono, celular, extension, huella, sitio, puesto, horario_inicio, horario_fin, dia_inicio, dia_fin FROM usuarios WHERE id = ?");
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
			$sth = $this->_db->prepare("UPDATE usuarios SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/administrar');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE usuarios SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/empleados/administrar');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
}