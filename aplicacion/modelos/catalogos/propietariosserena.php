<?php
final class Modelos_Catalogos_PropietariosSerena extends Modelo {
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
				SELECT id, condominio, edificio, lote, nombre, email, telefono1, telefono2, superficie, nombreUsuario, clave_catastral
				FROM propietarios
				WHERE tipo = 'La Serena' AND status = 1
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
					'condominio' => mb_strtoupper($datos['condominio'], 'UTF-8'),
					'edificio' => str_pad($datos['edificio'], 2, '0', STR_PAD_LEFT),
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
				SELECT id, condominio, edificio, lote, nombre, email, telefono1, telefono2, superficie, nombreUsuario
				FROM propietarios
				WHERE tipo = 'La Serena' AND status = 0
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
					'condominio' => mb_strtoupper($datos['condominio'], 'UTF-8'),
					'edificio' => str_pad($datos['edificio'], 2, '0', STR_PAD_LEFT),
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
			$condominio = mb_strtoupper($_POST['condominio']);
			$edificio = mb_strtoupper($_POST['edificio']);
			$email = strtolower($_POST['email']);
			$telefono1 = $_POST['telefono1'];
			$telefono2 = $_POST['telefono2'];
			$tipo = $_POST['tipo'];

			$nombreUsuario = strtolower($_POST['nombreUsuario']);
			$contrasena = $datos['contrasena'];
			$salt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 64);
			$contrasenaEncriptada = hash("sha256", $contrasena.$salt);

			$arregloDatos = array($nombre, $condominio, $edificio, $email, $telefono1, $telefono2, $tipo, $nombreUsuario, $salt, $contrasenaEncriptada, $contrasena);
			$sth = $this->_db->prepare("INSERT INTO propietarios (nombre, condominio, edificio, email, telefono1, telefono2, tipo, nombreUsuario, salt, contrasena, no_propietario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
			$condominio = mb_strtoupper($_POST['condominio']);
			$edificio = $_POST['edificio'];
			$email = strtolower($_POST['email']);
			$telefono1 = $_POST['telefono1'];
			$telefono2 = $_POST['telefono2'];

			$nombreUsuario = strtolower($_POST['nombreUsuario']);
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

			$arregloDatos = array($nombre, $condominio, $edificio, $email, $telefono1, $telefono2, $id);
			$sth = $this->_db->prepare("
				UPDATE propietarios SET
				nombre = ?,
				condominio = ?,
				edificio = ?,
				email = ?,
				telefono1 = ?,
				telefono2 = ?
				WHERE id = ?
			");
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

			// Contrase??a
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
	  		header('Location: ' . STASIS . '/catalogos/propietariosserena');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE propietarios SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/propietariosserena');
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
				$sth = $this->_db->prepare("UPDATE propietarios SET clave_catastral = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

			$correo = Modelos_Contenedor::crearModelo('Correo');
			$correo->claveCatastral($id);

	  		header('Location: ' . STASIS . '/catalogos/propietariosserena/cc/' . $id . '/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function excel() {
		// Inicializadores
		ini_set('memory_limit', '1024M');
		set_time_limit(0);
		require_once(APP . 'inc/phpexcel/phpexcel.php');

		// Inicializador Excel
		$i = 1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Grupo Valcas")->setTitle("Reporte")->setSubject("Reporte");
		$objPHPExcel->setActiveSheetIndex(0);

		// Facturas
    	$objPHPExcel->getActiveSheet()->setTitle("Propietarios");
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Grupo Valcas - Reporte de Propietarios RGR');
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFill()->getStartColor()->setARGB('256BB3');
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true);
    	
    	$i++;
    	$letra = 'A';

    	$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Nombre'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Secci??n'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Manzana'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Lote'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Email'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Tel??fono 1'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Tel??fono 2'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Superficie');

		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->getStartColor()->setARGB('748F2C');
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		
		// Listado de facturas
		$i++;
    	$sth = $this->_db->query("
    		SELECT id, seccion, manzana, lote, nombre, email, telefono1, telefono2, superficie, nombreUsuario, clave_catastral
			FROM propietarios
			WHERE tipo = 'La Serena' AND status = 1
			ORDER BY nombre ASC
		");
    	if(!$sth->execute()) throw New Exception();
    	while ($datos = $sth->fetch()) {
			$letra = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['nombre']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", mb_strtoupper($datos['seccion'], 'UTF-8')); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValueExplicit("$letra$i", str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValueExplicit("$letra$i", str_pad($datos['lote'], 2, '0', STR_PAD_LEFT),PHPExcel_Cell_DataType::TYPE_STRING); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", strtolower($datos['email'])); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['telefono1']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['telefono2']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValueExplicit("$letra$i", number_format($datos['superficie'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING); $letra++;

			$i++;
    	}

    	// Final de Excel
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="gvalcas_reporte_propietarios_rgr.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

}