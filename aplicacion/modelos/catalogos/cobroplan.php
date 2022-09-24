<?php
final class Modelos_Catalogos_Cobroplan extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function generarReporte() {
		try {
			$fecha_inicio = DateTime::createFromFormat('d/m/Y', $_POST['fecha_inicio']);
			$fecha_inicio = $fecha_inicio->format('Y-m-d');

			$fecha_fin = DateTime::createFromFormat('d/m/Y', $_POST['fecha_fin']);
			$fecha_fin = $fecha_fin->format('Y-m-d');

    		$ch = curl_init('https://cobroplan.mx/Transacciones?key=jYe2X2uwvJvtsH9M68vk2VjS9NjMq2&f1=' . $fecha_inicio . '&f2=' . $fecha_fin);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);

			file_put_contents(ROOT_DIR . "data/reporte_movimientos.xlsx", $response);
			Modelos_Descarga::descargarArchivo(ROOT_DIR . "data/reporte_movimientos.xlsx", 'reporte_movimientos_cobroplan-' . date('d-m-Y') . '.xlsx');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listado() {
		try {
			$db2 = new PDO("mysql:host=173.201.190.248;dbname=cobroplan;charset=utf8", 'admincobroplan', 'B4v8a=2jppat@');
			$datosVista = array();

			// Activos
			$sth = $db2->query("
				SELECT id, name, email, saari_id, user_type, created_at
				FROM users
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'name' => mb_strtoupper($datos['name'], 'UTF-8'),
					'email' => strtolower($datos['email']),
					'saari_id' => $datos['saari_id'],
					'user_type' => $datos['user_type'],
					'created_at' => Modelos_Fecha::formatearFecha($datos['created_at']),
				);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function nuevo() {
		try {
			$db2 = new PDO("mysql:host=173.201.190.248;dbname=cobroplan;charset=utf8", 'admincobroplan', 'B4v8a=2jppat@');

			$name = mb_strtoupper($_POST['name']);
			$email = strtolower($_POST['email']);
			$saari_id = $_POST['saari_id'];

			$pasword = $_POST['contrasena1'];
			$cost = 10;
			$contrasena = password_hash($pasword, PASSWORD_BCRYPT, ['cost' => $cost]);

			$arregloDatos = array($name, $email, $saari_id, $contrasena);
			$sth = $db2->prepare("INSERT INTO users (name, email, saari_id, password, user_type, created_at) VALUES (?, ?, ?, ?, 'propietario', NOW())");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Usuario agregado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificarGuardar() {
		try {
			$db2 = new PDO("mysql:host=173.201.190.248;dbname=cobroplan;charset=utf8", 'admincobroplan', 'B4v8a=2jppat@');

			$id = $_POST['id'];
			$name = mb_strtoupper($_POST['name']);
			$email = strtolower($_POST['email']);
			$saari_id = $_POST['saari_id'];

			if (!empty($_POST['contrasena1'])) {
				$pasword = $_POST['contrasena1'];
				$cost = 10;
				$contrasena = password_hash($pasword, PASSWORD_BCRYPT, ['cost' => $cost]);

				$arregloDatos = array($name, $email, $saari_id, $contrasena, $id);
				$sth = $db2->prepare("
					UPDATE users SET
					name = ?,
					email = ?,
					saari_id = ?,
					password = ?
					WHERE id = ?
				");
				if($sth->execute($arregloDatos)) {
					$this->mensajes[] = Modelos_Sistema::status(2, 'Propietario modificado exitosamente.');
				} else {
					throw New Exception();
				}
			} else {
				$arregloDatos = array($name, $email, $saari_id, $id);
				$sth = $db2->prepare("
					UPDATE users SET
					name = ?,
					email = ?,
					saari_id = ?
					WHERE id = ?
				");
				if($sth->execute($arregloDatos)) {
					$this->mensajes[] = Modelos_Sistema::status(2, 'Propietario modificado exitosamente.');
				} else {
					throw New Exception();
				}
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$db2 = new PDO("mysql:host=173.201.190.248;dbname=cobroplan;charset=utf8", 'admincobroplan', 'B4v8a=2jppat@');

			$sth = $db2->prepare("SELECT * FROM users WHERE id = ?");
			$sth->bindParam(1, $id);
			$sth->setFetchMode(PDO::FETCH_INTO, $this);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Grupo Valcas - Reporte de Propietarios IRT');
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFill()->getStartColor()->setARGB('256BB3');
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true);
    	
    	$i++;
    	$letra = 'A';

    	$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Nombre'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Sección'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Manzana'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Lote'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Email'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Teléfono 1'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Teléfono 2'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Superficie');

		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->getStartColor()->setARGB('748F2C');
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		
		// Listado de facturas
		$i++;
    	$sth = $this->_db->query("
    		SELECT id, seccion, manzana, lote, nombre, email, telefono1, telefono2, superficie, nombreUsuario, clave_catastral
			FROM propietarios
			WHERE tipo = 'IRT' AND status = 1
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
		header('Content-Disposition: attachment;filename="gvalcas_reporte_propietarios_irt.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

}