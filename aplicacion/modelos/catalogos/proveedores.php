<?php
final class Modelos_Catalogos_Proveedores extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function listado() {
		try {
			$datosVista = array();

			$sth = $this->_db->query("
				SELECT p.*, CONCAT(e.nombre, ' ', e.apellidos) AS responsable
				FROM proveedores p
				LEFT JOIN empleados e
				ON e.id = p.id_responsable
				ORDER BY p.nombre DESC
			");
			if(!$sth->execute()) throw New Exception();

			$nPendientes = 0;
			$nRevision = 0;
			$nAprobados = 0;
			$nAutorizados = 0;
			$nInactivos = 0;
			
			$pendientes = array();
			$revision = array();

			while ($datos = $sth->fetch()) {
				switch($datos['tipo']) {
					case 1: $tipo = 'PERSONA FÍSICA'; break;
					case 2: $tipo = 'PERSONA MORAL'; break;
					case 3: $tipo = 'CONTRATISTA DE PROYECTO Y OBRAS'; break;
				}

				$idProveedor = $datos['id'];
				$nombre = $datos['nombre'];
				$rfc = $datos['rfc'];
				$contacto = $datos['contacto'];
				$telefono = $datos['telefono'];
				$responsable = $datos['responsable'];
				$email = $datos['email'];

				// Cumplimiento
				if ($datos['tipo'] == 1) {
					$tareas = 10;
				} elseif ($datos['tipo'] == 2) {
					$tareas = 12;
				} elseif ($datos['tipo'] == 3) {
					$tareas = 15;
				}
				$cumplidas = 0;

				// Fecha de vencimiento
				$fechaVencimiento = new DateTime();
				$fechaVencimiento->add(new DateInterval('P7D'));
				$fechaVencimiento = $fechaVencimiento->getTimestamp();
				$fechaVencimiento = utf8_encode(ucfirst(strftime("%A %d de %B del %Y", $fechaVencimiento)));

				// Datos principales
				$sth2 = $this->_db->prepare("SELECT * FROM proveedores WHERE id = ?");
				$sth2->bindParam(1, $idProveedor);
				if(!$sth2->execute()) throw New Exception();
				$datos2 = $sth2->fetch();

				if ($datos2['nombre'] && $datos2['rfc'] && $datos2['contacto'] && $datos2['telefono'] && $datos2['email'] && $datos2['domicilio'] && $datos2['ciudad'] && $datos2['estado'] && $datos2['ofrece']) {
					$cumplidas++;
				}

				if ($datos2['logo']) {
					$cumplidas++;
				}

				// Referencias
				$sth2 = $this->_db->prepare("SELECT COUNT(id) FROM proveedores_referencias WHERE id_proveedor = ?");
				$sth2->bindParam(1, $idProveedor);
				if(!$sth2->execute()) throw New Exception();
				$cReferencias = $sth2->fetchColumn();

				if ($cReferencias >= 1) {
					$cumplidas++;
				}

				// Referencias
				$sth2 = $this->_db->prepare("SELECT * FROM proveedores WHERE id = ?");
				$sth2->bindParam(1, $idProveedor);
				if(!$sth2->execute()) throw New Exception();
				$datos2 = $sth2->fetch();

				if ($datos2['garantia_certificaciones']) {
					$cumplidas++;
				}

				// Archivos
				if ($datos2['csf']) {
					$cumplidas++;
				}
				if ($datos2['cdd']) {
					$cumplidas++;
				}
				if ($datos2['edocta'] || $datos2['cta_clabe']) {
					$cumplidas++;
				}
				if ($datos2['opcs']) {
					$cumplidas++;
				}
				if ($datos2['ce']) {
					$cumplidas++;
				}
				if ($datos2['ine_anverso'] && $datos2['ine_reverso']) {
					$cumplidas++;
				}

				// Contratista
				if ($datos2['tipo'] == 2) {
					if ($datos2['ac']) {
						$cumplidas++;
					}
					if ($datos2['pnrl']) {
						$cumplidas++;
					}
				}

				$porcentaje = round(($cumplidas/$tareas)*100);
				// Termina cumplimiento

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'nombre' => $datos['nombre'],
					'tipo' => $tipo,
					'rfc' => $datos['rfc'],
					'contacto' => $datos['contacto'],
					'telefono' => $datos['telefono'],
					'email' => $datos['email'],
					'ciudad' => $datos['ciudad'],
					'estado' => $datos['estado'],
					'responsable' => $responsable,
				);

				switch ($datos['status']) {
					// Pendientes
					case 1:
					if ($porcentaje != 100) {
						$pendientes[] = $arreglo;
						$nPendientes++;
					} else {
						$revision[] = $arreglo;
						$nRevision++;
					}
					break;

					case 2:
					$aprobados[] = $arreglo;
					$nAprobados++;
					break;

					case 3:
					$autorizados[] = $arreglo;
					$nAutorizados++;
					break;

					case 0:
					$inactivos[] = $arreglo;
					$nInactivos++;
					break;
				}
			}

			$datosVista['nPendientes'] = $nPendientes;
			$datosVista['nRevision'] = $nRevision;
			$datosVista['nAprobados'] = $nAprobados;
			$datosVista['nAutorizados'] = $nAutorizados;
			$datosVista['nInactivos'] = $nInactivos;

	  		$datosVista['pendientes'] = $pendientes;
	  		$datosVista['revision'] = $revision;
	  		$datosVista['aprobados'] = $aprobados;
	  		$datosVista['autorizados'] = $autorizados;
	  		$datosVista['inactivos'] = $inactivos;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function inactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE proveedores SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/proveedores');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE proveedores SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/proveedores');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function aprobar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE proveedores SET status = 2, id_aprueba = ?, fecha_aprobacion = NOW() WHERE id = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/proveedores/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function autorizar() {
		try {
			$sth = $this->_db->prepare("
				UPDATE proveedores SET
				status = 3,
				id_autoriza = ?,
				fecha_autorizacion = NOW(),
				uso_cfdi1 = ?,
				uso_cfdi2 = ?,
				uso_cfdi3 = ?,
				id_responsable = ?
				WHERE id = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $_POST['uso_cfdi1']);
			$sth->bindParam(3, $_POST['uso_cfdi2']);
			$sth->bindParam(4, $_POST['uso_cfdi3']);
			$sth->bindParam(5, $_POST['id_responsable']);
			$sth->bindParam(6, $_POST['id']);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/proveedores/2');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoProveedores($id = null) {
		try {
			$sth = $this->_db->query("SELECT id, razon_social
				FROM proveedores
				WHERE status = 3
				ORDER BY razon_social ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if ($id == $datos['id']) {
					$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['razon_social'] . '</option>';
				} else {
					$html .= '<option value="' . $datos['id'] . '">' . $datos['razon_social'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function getInformacion($id) {
		try {
			$datosVista = array();

			$sth = $this->_db->prepare("SELECT * FROM proveedores WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

			$datos = $sth->fetch();
			$datosVista['id'] = $datos['id'];
			$datosVista['nombre'] = $datos['nombre'];
			$datosVista['razon_social'] = $datos['razon_social'];
			$datosVista['email'] = $datos['email'];

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function revisionInformacion() {
		try {
			$correo = Modelos_Contenedor::crearModelo('Correo');
			$correo->revisionInformacion();
			header('Location: ' . STASIS . '/catalogos/proveedores/revision/' . $_POST['id'] . '/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function excel() {
		// Inicializadores
		ini_set('memory_limit', '1024M');
		set_time_limit(0);
		require_once(APP . 'inc/phpexcel/phpexcel.php');

		// Variables iniciales
		$fechaActual = new DateTime();

		// Inicializador Excel
		$i = 1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Grupo Valcas")->setTitle("Reporte de Proveedores")->setSubject("Reporte de Proveedores");
		$objPHPExcel->setActiveSheetIndex(0);

		// Facturas
    	$objPHPExcel->getActiveSheet()->setTitle("Proveedores");
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Grupo Valcas - Reporte de Proveedores');
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFill()->getStartColor()->setARGB('256BB3');
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->setBold(true);
    	
    	$i++;
    	$letra = 'A';

		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Status'); $letra++;
    	$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Nombre'); $letra++;
    	$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Razón Social'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'RFC'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Tipo'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Nombre de Contacto'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Teléfono'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'E-Mail'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Domicilio'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Ciudad'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Estado'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Servicio/Producto Que Ofrece'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Certificaciones'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Garantias'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Banco'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Sucursal'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Cuenta'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'CLABE'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Nombre de Encargado');

		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->getStartColor()->setARGB('748F2C');
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		
		$sth = $this->_db->query("
			SELECT p.uniqueid, CONCAT(ap.nombre, ' ', ap.apellidos) AS aprueba, CONCAT(au.nombre, ' ', au.apellidos) AS autoriza, p.*
			FROM proveedores p
			LEFT JOIN empleados ap
			ON ap.id = p.id_aprueba
			LEFT JOIN empleados au
			ON au.id = p.id_autoriza
			WHERE p.status != 0
			ORDER BY razon_social ASC
		");
		if(!$sth->execute()) throw New Exception();
		
		$i++;
		while ($datos = $sth->fetch()) {
			switch($datos['tipo']) {
				case 1: $tipo = 'PERSONA FÍSICA'; break;
				case 2: $tipo = 'PERSONA MORAL'; break;
				case 3: $tipo = 'CONTRATISTA DE PROYECTO Y OBRAS'; break;
			}

			switch($datos['status']) {
				case 1: $status = 'EN REVISIÓN'; break;
				case 2: $status = 'APROBADO'; break;
				case 3: $status = 'AUTORIZADO'; break;
				case 0: $status = 'INACTIVO'; break;
			}

			$letra = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $status); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['nombre']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['razon_social']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['rfc']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $tipo); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['contacto']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['telefono']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['email']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['domicilio']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['ciudad']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['estado']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['ofrece']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['certificaciones']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['garantias']); $letra++;

			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", mb_strtoupper($datos['cta_banco'])); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", mb_strtoupper($datos['cta_sucursal'])); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", mb_strtoupper($datos['cta_cuenta'])); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", mb_strtoupper($datos['cta_clabe'])); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", mb_strtoupper($datos['cta_encargado'])); $letra++;

			$i++;
    	}

    	// Final de Excel
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="gvalcas_reporte_proveedores.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

}