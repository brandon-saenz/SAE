<?php
final class Modelos_Empleado extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function __construct($db) {
		$this->iniciarDb($db);
	}

	public function listadoEmpleados() {
		$sth = $this->_db->query("SELECT id, no_empleado, CONCAT(nombre, ' ', apellidos) AS nombre FROM usuarios2 WHERE status = 1 ORDER BY no_empleado ASC");
		if(!$sth->execute()) throw New Exception();
		
		$html = '<option value="">Selecciona empleado...</option>';
		while ($datos = $sth->fetch()) {
			$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . ' [' . $datos['no_empleado'] . ']</option>';
		}

		return $html;
	}

	public function listadoDepartamentos() {
		$sth = $this->_db->query("SELECT id, nombre FROM departamentos ORDER BY nombre ASC");
		if(!$sth->execute()) throw New Exception();
		
		$html = '<option value="">Selecciona departamento...</option>';
		while ($datos = $sth->fetch()) {
			$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
		}

		return $html;
	}

	public function checkboxesProcedimientos() {
		$procedimiento = Modelos_Contenedor::crearModelo('Procedimiento');
		$procedimientos = $procedimiento->listadoProcedimientosArreglo();
		
		$html = '';
		foreach($procedimientos as $valor) {
			$html .= '<div class="form-group">
				<label class="col-sm-2 control-label"><input type="checkbox" class="form-control" /></label>
					<div class="col-sm-10">' . $valor . '</div>
				</div>';
		}

		return $html;
	}

	public function arregloExpedientes() {
		$expedientes = array(1=>'Acta de Nacimiento', 'Curriculum Vitae', 'Solicitud de Empleo', 'Copia INE', 'Comp. de Domicilio', 'RFC', 'CURP', 'NSS', 'Titulo / Cedula', 'Certificado de Estudios', 'No. De Cuenta', 'Reglamento Interno', 'Contrato Laboral', 'Contrato de Confidenc.');
		
		return $expedientes;
	}

	public function agregar() {
		try {
			$name = strtoupper($_POST['name']);

			$nameArray = explode(' ', $name);
			$usernameSlug = $nameArray[0] . ' ' . $nameArray[1];
			$username = Modelos_Caracteres::generarSlugUsuario($usernameSlug);

			$password = $_POST['password1'];
			$center = $_POST['center'];
			$level = $_POST['level'];
			
			$dobYear = $_POST['dobYear'];
			$dobMonth = $_POST['dobMonth'];
			$dobDay = $_POST['dobDay'];
			$phoneHome = $_POST['phoneHome'];
			$cellPhone = $_POST['cellPhone'];
			$address = $_POST['address'];
			$email = $_POST['email'];
			$bilingual = $_POST['bilingual'];
			$referral = $_POST['referral'];

			$dob = $dobYear . '-' . $dobMonth . '-' . $dobDay;
			$nameArray = explode(' ', $_POST['name']);
			$firstName = strtolower(substr($nameArray[0], 0, 1));
			$lastName = strtolower(substr($nameArray[1], 0, 1));
			$password = $firstName . $lastName . $dobDay . $dobMonth . $dobYear;

			// Agregando valores iniciales
			if ($name && $dobYear && $dobMonth && $dobDay && $center && $level) {
				$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
				$contrasenaEncriptada = hash("sha256", $password.$salt);
				$arregloDatos = array($center, $name, $level, $username, $salt, $contrasenaEncriptada, $_SESSION['login_id'], $cellPhone, $phoneHome, $address, $email, $dob, $bilingual, $referral);

				$sth = $this->_db->prepare("INSERT INTO users (id_center, name, level, user, salt, password, created_by, phone_cell, phone_home, address, email, dob, bilingual, id_referral) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				if($sth->execute($arregloDatos)) {
					$idUser = $this->_db->lastInsertId();

					// Agregando campañas
					foreach ($_POST['campaigns'] as $idCampaign) {
						$arregloDatos = array($idUser, $idCampaign);
						$sth = $this->_db->prepare("INSERT INTO users_campaigns (id_user, id_campaign) VALUES (?, ?)");
						$sth->execute($arregloDatos);
					}

					// Agregando privilegios
					foreach ($_POST['privileges'] as $privilege) {
						$arregloDatos = array($idUser, $privilege);
						$sth = $this->_db->prepare("INSERT INTO users_privileges (id_user, privilege) VALUES (?, ?)");
						$sth->execute($arregloDatos);
					}

					$this->mensajes = Modelos_Sistema::status(2, 'User added with the username <strong>' . $username . '</strong> and the password <strong>' . $password . '</strong>');
				} else {
					throw New Exception();
				}
			} else {
				$this->mensajes = Modelos_Sistema::status(1, 'Required fields missing.');
			}
		} catch (Exception $e) {
			$this->mensajes = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function infoIndividual($id) {
		$sth = $this->_db->prepare("SELECT * FROM users WHERE id = ?");
		$sth->bindParam(1, $id);
		$sth->setFetchMode(PDO::FETCH_INTO, $this);
		if(!$sth->execute()) throw New Exception();
		$sth->fetch();

		$sth = $this->_db->prepare("SELECT name, dob FROM users WHERE id = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();
		$dob = explode('-', $datos['dob']);
		$this->dobYear = $dob[0];
		$this->dobMonth = $dob[1];
		$this->dobDay = $dob[2];

		$nameArray = explode(' ', $datos['name']);
		$firstName = strtolower(substr($nameArray[0], 0, 1));
		$lastName = strtolower(substr($nameArray[1], 0, 1));
		$this->password = $firstName . $lastName . $this->dobDay . $this->dobMonth . $this->dobYear;

		$sth = $this->_db->prepare("SELECT * FROM users_campaigns WHERE id_user = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$this->campaigns[] = $datos['id_campaign'];
		}

		$sth = $this->_db->prepare("SELECT * FROM users_privileges WHERE id_user = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$this->privileges[] = $datos['privilege'];
		}

		return $this;
	}

	public function inactivar($id) {
		try {
			$inactiveDate = date('Y-m-d'); 
			$sth = $this->_db->prepare("UPDATE users SET status = 0, inactive_date = ? WHERE id = ?");
			$sth->bindParam(1, $inactiveDate);
			$sth->bindParam(2, $id);
			if(!$sth->execute($arregloDatos)) throw New Exception();

			// Borrar del grupo al que este asignado (si lo está)
			$sth = $this->_db->prepare("DELETE FROM users_group WHERE id_supervisor = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute($arregloDatos)) throw New Exception();

			$sth = $this->_db->prepare("DELETE FROM users_group_members WHERE id_member = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute($arregloDatos)) throw New Exception();

			header("Location:" . STASIS . "/users");
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE users SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute($arregloDatos)) throw New Exception();

			header("Location:" . STASIS . "/users");
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificar() {
		try {
			$name = strtoupper($_POST['name']);
			$center = $_POST['center'];
			$level = $_POST['level'];
			$id = $_POST['id'];

			$dobYear = $_POST['dobYear'];
			$dobMonth = $_POST['dobMonth'];
			$dobDay = $_POST['dobDay'];
			$phoneHome = $_POST['phoneHome'];
			$cellPhone = $_POST['cellPhone'];
			$address = $_POST['address'];
			$email = $_POST['email'];
			$salary = $_POST['salary'];
			$referral = $_POST['referral'];
			
			$bilingual = $_POST['bilingual'];
			$shift = $_POST['shift'];
			$team = $_POST['team'];
			$debitCard = $_POST['debitCard'];
			$debitCardExpMonth = $_POST['debitCardExpMonth'];
			$debitCardExpYear = $_POST['debitCardExpYear'];
			$bank = $_POST['bank'];

			$dob = $dobYear . '-' . $dobMonth . '-' . $dobDay;
			$nameArray = explode(' ', $_POST['name']);
			$firstName = strtolower(substr($nameArray[0], 0, 1));
			$lastName = strtolower(substr($nameArray[1], 0, 1));
			$password = $firstName . $lastName . $dobDay . $dobMonth . $dobYear;
			
			// Agregando valores iniciales
			if ($name && $dobYear && $dobMonth && $dobDay && $center && $level) {
				$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
				$contrasenaEncriptada = hash("sha256", $password.$salt);

				$arregloDatos = array($center, $name, $level, $cellPhone, $phoneHome, $address, $email, $dob, $salary, $bilingual, $shift, $team, $debitCard, $debitCardExpMonth, $debitCardExpYear, $bank, $salt, $contrasenaEncriptada, $referral, $id);
				$sth = $this->_db->prepare("UPDATE users SET id_center = ?, name = ?, level = ?, phone_cell = ?, phone_home = ?, address = ?, email = ?, dob = ?, salary = ?, bilingual = ?, shift = ?, team = ?, debit_card = ?, debit_card_month = ?, debit_card_year = ?, bank = ?, salt = ?, password = ?, id_referral = ? WHERE id = ?");
				if($sth->execute($arregloDatos)) {
					// Agregando campañas
					$arregloDatos = array($id);
					$sth = $this->_db->prepare("DELETE FROM users_campaigns WHERE id_user = ?");
					$sth->execute($arregloDatos);

					foreach ($_POST['campaigns'] as $idCampaign) {
						$arregloDatos = array($id, $idCampaign);
						$sth = $this->_db->prepare("INSERT INTO users_campaigns (id_user, id_campaign) VALUES (?, ?)");
						$sth->execute($arregloDatos);
					}

					// Agregando privilegios
					$arregloDatos = array($id);
					$sth = $this->_db->prepare("DELETE FROM users_privileges WHERE id_user = ?");
					$sth->execute($arregloDatos);

					foreach ($_POST['privileges'] as $privilege) {
						$arregloDatos = array($id, $privilege);
						$sth = $this->_db->prepare("INSERT INTO users_privileges (id_user, privilege) VALUES (?, ?)");
						$sth->execute($arregloDatos);
					}

					header("Location:" . STASIS . "/users/edit/$id/1");
				} else {
					throw New Exception();
				}
			} else {
				$this->mensajes = Modelos_Sistema::status(1, 'Required fields missing.');
			}
		} catch (Exception $e) {
			$this->mensajes = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function subirFoto($id) {
		if ($_POST['type'] == "pixel") {
			$im = imagecreatetruecolor(320, 240);

			foreach (explode("|", $_POST['image']) as $y => $csv) {
				foreach (explode(";", $csv) as $x => $color) {
					imagesetpixel($im, $x, $y, $color);
				}
			}
		} else {
			$im = imagecreatefrompng($_POST['image']);
		}

		$time = time();
		imagejpeg($im, ROOT_DIR . "/data/pictures/$time.jpg");

		$sth = $this->_db->prepare("UPDATE users SET picture = ? WHERE id = ?");
		$sth->bindParam(1, $time);
		$sth->bindParam(2, $id);
		$sth->execute();

		$html = '<label class="col-md-2 control-label">Picture Taken</label> <div class="col-md-4"> <img src="' . STASIS . '/data/pictures/' . $time . '.jpg" /></div>';
		return $html;
	}

	public function subirDescriptivoPuestos() {
		// Imagen
		if (isset($_FILES['imagen'])) {
			require APP . 'inc/class.upload.php';

			$archivo = $_FILES['imagen']['name'];
			$handle = new upload($_FILES['imagen']);
			if ($handle->uploaded) {
				$handle->image_resize         = true;
				$handle->image_x              = 800;
				$handle->image_ratio_y        = true;

				$archivoExtension = $handle->file_src_name_ext;
				$nombreOriginal = $handle->file_src_name;

				$archivo = time();
				$handle->file_new_name_body = $archivo;
				
				$handle->process(ROOT_DIR . '/data/privada/descriptivos_puestos/');
				if ($handle->processed) {
					$archivo .= '.' . $handle->file_src_name_ext;
					$handle->clean();
				}
			}

			$idEmpleado = $_POST['idEmpleado'];

			$sth = $this->_db->prepare("UPDATE usuarios2 SET archivo_descriptivo_puestos = ? WHERE id = ?");
			$sth->bindParam(1, $archivo);
			$sth->bindParam(2, $idEmpleado);
			$sth->execute();
		}

		header("Location:" . STASIS . "/principal");
	}

	public function subirExpediente() {
		// Imagen
		if (isset($_FILES['imagen'])) {
			require APP . 'inc/class.upload.php';
			$archivoTime = time();

			$handle = new upload($_FILES['imagen']);
			if ($handle->uploaded) {
				$handle->image_resize         = true;
				$handle->image_x              = 800;
				$handle->image_ratio_y        = true;

				$archivoExtension = $handle->file_src_name_ext;
				$nombreOriginal = $handle->file_src_name;

				$handle->file_new_name_body = $archivoTime;
				
				$handle->process(ROOT_DIR . '/data/privada/expedientes/');
				if ($handle->processed) {
					$archivo = $archivoTime . '.' . $handle->file_src_name_ext;
				}
			}

			$handle = new upload($_FILES['imagen']);
			if ($handle->uploaded) {
				$handle->image_resize         = true;
				$handle->image_x              = 260;
				$handle->image_ratio_y        = true;

				$archivoExtension = $handle->file_src_name_ext;
				$nombreOriginal = $handle->file_src_name;

				$handle->file_new_name_body = $archivoTime;
				
				$handle->process(ROOT_DIR . '/data/privada/expedientes/th/');
				if ($handle->processed) {
					$archivo = $archivoTime . '.' . $handle->file_src_name_ext;
					$handle->clean();
				}
			}

			$idEmpleado = $_POST['idEmpleado'];
			$idExpediente = $_POST['idExpediente'];

			$sth = $this->_db->prepare("UPDATE usuarios_expedientes SET archivo = ? WHERE id_empleado = ? AND id_expediente = ?");
			$sth->bindParam(1, $archivo);
			$sth->bindParam(2, $idEmpleado);
			$sth->bindParam(3, $idExpediente);
			$sth->execute();
		}

		header("Location:" . STASIS . "/principal");
	}

	public function obtenerInfo($idEmpleado) {
		// Datos Principales
		$html = '<script>';
		
		$sth = $this->_db->prepare("SELECT id, nombre, apellidos, fecha_ingreso, direccion, telefono, rfc, email, nss, ext, curp, departamento, empleo_anterior, archivo_descriptivo_puestos FROM usuarios2 WHERE id = ?");
		$sth->bindParam(1, $idEmpleado);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$fechaActual = new DateTime();
		$fechaIngreso = new DateTime($datos['fecha_ingreso']);
		$interval = $fechaActual->diff($fechaIngreso);
		$antiguedad = $interval->format('%y año(s), %m mes(es), %d día(s)');

		$html .= '$("#nombre_empleado").val("' . $datos['nombre'] . ' ' . $datos['apellidos'] . '");
			$("#fecha_ingreso").val("' . Modelos_Fecha::formatearFecha($datos['fecha_ingreso']) . '");
			$("#direccion").val("' . $datos['direccion'] . '");
			$("#telefono").val("' . $datos['telefono'] . '");
			$("#ext").val("' . $datos['ext'] . '");
			$("#rfc").val("' . $datos['rfc'] . '");
			$("#email").val("' . $datos['email'] . '");
			$("#nss").val("' . $datos['nss'] . '");
			$("#curp").val("' . $datos['curp'] . '");
			$("#departamento").val("' . $datos['departamento'] . '");
			$("#antiguedad").val("' . $antiguedad . '");
			$("#empleo_anterior").val("' . $datos['empleo_anterior'] . '");
			$(".idEmpleado").val("' . $datos['id'] . '");
			';

		// Imagen Descriptivo Puestos
		if (empty($datos['archivo_descriptivo_puestos'])) {
			$imagen = 'http://placehold.it/800x500?text=Sin+Imagen';
		} else {
			$imagen = STASIS . '/data/privada/descriptivos_puestos/' . $datos['archivo_descriptivo_puestos'];
		}
		$html .= '$("#imagen-descriptivo-puestos").attr("src","' . $imagen . '");';

		// Expediente Inicio
		$sth = $this->_db->prepare("SELECT id_expediente, archivo FROM usuarios_expedientes WHERE id_empleado = ?");
		$sth->bindParam(1, $idEmpleado);
		if(!$sth->execute()) throw New Exception();
		
		$expedientesCompletos = 0;
		while ($datos = $sth->fetch()) {
			if (!empty($datos['archivo'])) {
				$html .= '$("#expediente' . $datos['id_expediente'] . '").html("<img src=\"' . STASIS . '/img/icono-activar.png\" />");';
				$expedientesCompletos++;
			} else {
				$html .= '$("#expediente' . $datos['id_expediente'] . '").html("<img src=\"' . STASIS . '/img/icono-eliminar.png\" />");';
			}
		}
		$totalExpedientes = 14;
		$porcentaje = number_format( $expedientesCompletos/$totalExpedientes * 100, 2 ) . '%';
		$html .= '$("#porcentaje-expedientes").html("' . $porcentaje . '");';

		// Expediente Tab
		$expedientes = array(1=>'Acta de Nacimiento', 'Curriculum Vitae', 'Solicitud de Empleo', 'Copia INE', 'Comp. Domicilio', 'RFC', 'CURP', 'NSS', 'Titulo / Cedula', 'Certificado de Estudios', 'No. De Cuenta', 'Reglamento Interno de Trabajo', 'Contrato Laboral', 'Contrato de Confidenc.');
		
		foreach ($expedientes as $llave => $valor) {
			$sth = $this->_db->prepare("SELECT archivo FROM usuarios_expedientes WHERE id_empleado = ? AND id_expediente = ? ORDER BY id_expediente ASC");
			$sth->bindParam(1, $idEmpleado);
			$sth->bindParam(2, $llave);
			if(!$sth->execute()) throw New Exception();
			
			$datos = $sth->fetch();
			if (empty($datos['archivo'])) {
				$imagen = 'http://placehold.it/210x260?text=Sin+Imagen';
				$html .= '$("#link-expediente' . $llave . '").attr("href","#");';
			} else {
				$imagenLink = STASIS . '/data/privada/expedientes/' . $datos['archivo'];
				$imagen = STASIS . '/data/privada/expedientes/th/' . $datos['archivo'];
				$html .= '$("#link-expediente' . $llave . '").attr("href","' . $imagenLink . '");';
			}

			$html .= '$("#img-expediente' . $llave . '").attr("src","' . $imagen . '");';
		}

		// Procedimientos
		$sth = $this->_db->prepare("SELECT id_procedimiento, status FROM usuarios_procedimientos WHERE id_empleado = ?");
		$sth->bindParam(1, $idEmpleado);
		if(!$sth->execute()) throw New Exception();

		$procedimientosEspecificados = 0;		
		while ($datos = $sth->fetch()) {
			if ($datos['status'] == 0) {
				$html .= '$("#procedimiento' . $datos['id_procedimiento'] . '").html("<img src=\"' . STASIS . '/img/icono-eliminar.png\" />");';
			} else {
				$html .= '$("#procedimiento' . $datos['id_procedimiento'] . '").html("<img src=\"' . STASIS . '/img/icono-activar.png\" />");';
				$procedimientosEspecificados++;
			}
		}
		$totalProcedimientos = 19;
		$porcentaje = number_format( $procedimientosEspecificados/$totalProcedimientos * 100, 2 ) . '%';
		$html .= '$("#porcentaje-procedimientos").html("' . $porcentaje . '");';

		// Porcentaje de Capacitacion
		$html .= '$("#porcentaje-capacitacion").html("0.00%");';

		$html .= '</script>';
		return $html;
	}

}