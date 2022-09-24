<?php
use Openpay\Data\Openpay;

final class Modelos_Movimientos_Eventos extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function agregar() {
		try {
			require APP . 'inc/class.upload.php';
			$bytes = random_bytes(10);

			$uniqueId = bin2hex($bytes);
			$nombre = $_POST['nombre'];
			$fecha = DateTime::createFromFormat('d/m/Y', $_POST['fecha']);
			$fecha = $fecha->format('Y-m-d');
			$hora = $_POST['hora'];
			$limite = $_POST['limite'];
			$descripcion = $_POST['descripcion'];

			$datosArray = array($uniqueId, $nombre, $hora, $limite, $limite, $fecha, $descripcion);
			$sth = $this->_db->prepare("INSERT INTO eventos (uniqueid, nombre, hora, limite, existencia, fecha, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)");
			if(!$sth->execute($datosArray)) throw New Exception();
			$idEvento = $this->_db->lastInsertId();

			if (!$_FILES['imagen_web']['size'] == 0) {
				$handle = new upload($_FILES['imagen_web']);
				if ($handle->uploaded) {
					$archivo = time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/eventos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $idEvento);
				$sth = $this->_db->prepare("UPDATE eventos SET imagen_web = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

			if (!$_FILES['imagen_movil']['size'] == 0) {
				$handle = new upload($_FILES['imagen_movil']);
				if ($handle->uploaded) {
					$archivo = time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/eventos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $idEvento);
				$sth = $this->_db->prepare("UPDATE eventos SET imagen_movil = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

			for ($x=1; $x<=3; $x++) {
				if (!empty($_POST["fase{$x}_cierre"])) {
					$fechaCierre = DateTime::createFromFormat('d/m/Y', $_POST["fase{$x}_cierre"]);
					$fechaCierre = $fechaCierre->format('Y-m-d');

					$sth = $this->_db->prepare("
						UPDATE eventos SET
						fase{$x}_cierre = ?,
						fase{$x}_general = ?,
						fase{$x}_propietarios = ?
						WHERE id = ?
					");

					$sth->bindParam(1, $fechaCierre);
					$sth->bindParam(2, $_POST["fase{$x}_general"]);
					$sth->bindParam(3, $_POST["fase{$x}_propietarios"]);
					$sth->bindParam(4, $idEvento);
					if(!$sth->execute()) throw New Exception();
				}
			}

	  		header('Location: ' . STASIS . '/movimientos/eventos/agregar/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function webhook() {
		$json = file_get_contents('php://input');
		file_put_contents('webhook.txt', $json.PHP_EOL , FILE_APPEND | LOCK_EX);
		die;
	}

	public function agregarReferencia() {
		try {
			$nombre = mb_strtoupper($_POST['nombre'], 'UTF-8');
			$telefono = $_POST['telefono'];
			$email = strtolower($_POST['email']);
			$email2 = strtolower($_POST['email2']);
			$seccion = $_POST['seccion'];
			$manzana = $_POST['manzana'];
			$boletos = $_POST['boletos'];
			$importe = $_POST['importe'];

			if ($seccion) {
    			$lote = $_POST['seccion'] . '-' . str_pad($_POST['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($_POST['lote'], 2, '0', STR_PAD_LEFT);
    		} else {
    			$lote = '';
    		}

			// Openpay
    		require_once(APP . 'plugins/openpay/vendor/autoload.php');

			//produccion
			// Openpay::setSandboxMode(false);
			// $openpay = Openpay::getInstance('m7aci0xq2pyewsqdhy9r','sk_3b3ded4ccf584eab9b4dd9536b4db4f3');

            //test
            Openpay::setSandboxMode(true);
            $openpay = Openpay::getInstance('mkbx4mcmpt7ptigpxp19','sk_9a0e14de64b54c7cb90da2511c98f2f8');

            $customer = array(
			     'name' => $nombre,
			     'phone_number' => $telefono,
			     'email' => $email,
			);

            $chargeData = array(
	        	'method' => 'store',
	        	'customer' => $customer,
		        'amount' => $importe,
		        'description' => $boletos . ' Boleto(s): Noche Mexicana Entre Viñedos'
	    	);
		    $charge = $openpay->charges->create($chargeData);
		    $autorizacion = $charge->id;

		    for ($x=1; $x<=$boletos; $x++) {
				// Insertar autorizacion de Openpay a SAE Valcas
				$sth = $this->_db->prepare("INSERT INTO eventos_reservas (openpay_id, nombre, telefono, email, lote, boletos, importe, fecha_creacion, status) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 2)");
				$sth->bindParam(1, $autorizacion);
				$sth->bindParam(2, $nombre);
				$sth->bindParam(3, $telefono);
				$sth->bindParam(4, $email);
				$sth->bindParam(5, $lote);
				$sth->bindParam(6, $boletos);
				$sth->bindParam(7, $importe);
				$sth->execute();
			}

	  		header('Location: ' . STASIS . '/movimientos/eventos/referencia/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function agregarTransferencia() {
		try {
			$nombre = mb_strtoupper($_POST['nombre'], 'UTF-8');
			$telefono = $_POST['telefono'];
			$email = strtolower($_POST['email']);
			$email2 = strtolower($_POST['email2']);
			$seccion = $_POST['seccion'];
			$manzana = $_POST['manzana'];
			$boletos = $_POST['boletos'];
			$importe = $_POST['importe'];

			if ($seccion) {
    			$lote = $_POST['seccion'] . '-' . str_pad($_POST['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($_POST['lote'], 2, '0', STR_PAD_LEFT);
    		} else {
    			$lote = '';
    		}

			// Openpay
    		require_once(APP . 'plugins/openpay/vendor/autoload.php');

			//produccion
			Openpay::setSandboxMode(false);
			$openpay = Openpay::getInstance('m7aci0xq2pyewsqdhy9r','sk_3b3ded4ccf584eab9b4dd9536b4db4f3');

            //test
            // Openpay::setSandboxMode(true);
            // $openpay = Openpay::getInstance('mkbx4mcmpt7ptigpxp19','sk_9a0e14de64b54c7cb90da2511c98f2f8');

            $customer = array(
			     'name' => $nombre,
			     'phone_number' => $telefono,
			     'email' => $email,
			);

            $chargeData = array(
	        	'method' => 'bank_account',
	        	'customer' => $customer,
		        'amount' => $importe,
		        'description' => $boletos . ' Boleto(s): Noche Mexicana Entre Viñedos'
	    	);
		    $charge = $openpay->charges->create($chargeData);
		    $autorizacion = $charge->id;

		    for ($x=1; $x<=$boletos; $x++) {
				// Insertar autorizacion de Openpay a SAE Valcas
				$sth = $this->_db->prepare("INSERT INTO eventos_reservas (openpay_id, nombre, telefono, email, lote, boletos, importe, fecha_creacion, status) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 3)");
				$sth->bindParam(1, $autorizacion);
				$sth->bindParam(2, $nombre);
				$sth->bindParam(3, $telefono);
				$sth->bindParam(4, $email);
				$sth->bindParam(5, $lote);
				$sth->bindParam(6, $boletos);
				$sth->bindParam(7, $importe);
				$sth->execute();
			}

	  		header('Location: ' . STASIS . '/movimientos/eventos/transferencia/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function agregarEfectivo() {
		try {
			$openpay_id = Modelos_Caracteres::generarRandomString();
			$nombre = mb_strtoupper($_POST['nombre'], 'UTF-8');
			$telefono = $_POST['telefono'];
			$email = strtolower($_POST['email']);
			$email2 = strtolower($_POST['email2']);
			$seccion = $_POST['seccion'];
			$manzana = $_POST['manzana'];
			$boletos = $_POST['boletos'];
			$importe = $_POST['importe'];

			if ($seccion) {
    			$lote = $_POST['seccion'] . '-' . str_pad($_POST['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($_POST['lote'], 2, '0', STR_PAD_LEFT);
    		} else {
    			$lote = '';
    		}

		    for ($x=1; $x<=$boletos; $x++) {
				// Insertar autorizacion de Openpay a SAE Valcas
				$sth = $this->_db->prepare("INSERT INTO eventos_reservas (openpay_id, nombre, telefono, email, lote, boletos, importe, fecha_creacion, status) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 4)");
				$sth->bindParam(1, $openpay_id);
				$sth->bindParam(2, $nombre);
				$sth->bindParam(3, $telefono);
				$sth->bindParam(4, $email);
				$sth->bindParam(5, $lote);
				$sth->bindParam(6, $boletos);
				$sth->bindParam(7, $importe);
				$sth->execute();
			}

	  		header('Location: ' . STASIS . '/movimientos/eventos/efectivo/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function infoPago($id) {
		$datosArray = [];

		$sth = $this->_db->prepare("SELECT * FROM eventos_reservas WHERE openpay_id = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$datosArray['openpay_id'] = $datos['openpay_id'];
		$datosArray['nombre'] = mb_strtoupper($datos['nombre'], 'UTF-8');
		$datosArray['telefono'] = $datos['telefono'];
		$datosArray['email'] = strtolower($datos['email']);
		$datosArray['email2'] = strtolower($datos['email2']);
		$datosArray['lote'] = $datos['lote'];
		$datosArray['boletos'] = $datos['boletos'];
		$datosArray['importe'] = number_format($datos['importe'], 2, '.', ',');
		$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);

		return $datosArray;
	}

	public function aplicarPago() {
		try {
			$id = $_POST['id'];

			$sth = $this->_db->prepare("UPDATE eventos_reservas SET pagado = 1, fecha_pago = NOW() WHERE openpay_id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/eventos/aplicar_pago/' . $id . '/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function referenciaEfectivo($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM eventos_reservas WHERE openpay_id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$nombre = mb_strtoupper($datos['nombre'], 'UTF-8');
			$telefono = $datos['telefono'];
			$email = strtolower($datos['email']);
			$email2 = strtolower($datos['email2']);
			$seccion = $datos['seccion'];
			$manzana = $datos['manzana'];
			$boletos = $datos['boletos'];
			$importe = number_format($datos['importe'], 2, '.', ',');
			$fecha_creacion = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);

			if ($lote) {
				$precio = '$ 900.00';
				$loteHtml = '<tr><td><b>Lote:</b> ' . $lote . '</td></tr>';
			} else {
				$precio = '$ 1100.00';
				$loteHtml = '';
			}

			// PDF
			require_once(APP . 'plugins/tcpdf/tcpdf.php');
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetTitle('Referencia Bancaria');
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->SetFont('helvetica', '', 10);
			$pdf->SetPrintHeader(false);
			$pdf->SetMargins(10, 10, 10, 0);

			$stasis = STASIS;

			$nQr = sprintf('%03d', $id+$x);
			$xFake = $x+1;

			$pdf->AddPage();
			$html = <<<EOF
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 250px; color: #444;">
							<img src="$stasis/img/cobroplan.png" height="62" />
						</td>
					</tr>
				</table>
				<br /><br />

				<table style="border: 2px solid #DDDCDD;">
				</table>
				<br /><br />

				<span style="font-size: 12px; font-weight: bold;">Pago en efectivo para reservación de evento<br />Noche Mexicana Entre Viñedos</span><br /><br />

				<table style="text-align: left; font-size: 9px;" border="0" cellpadding="1" cellspacing="0">
					<tr>
						<td>Para realizar su pago correspondiente, favor de visitarnos en Rancho Tecate con esta hoja impresa o en forma digital.<br /></td>
					</tr>
					<tr>
						<td><b>Concepto:</b> $boletos Boleto(s) Noche Mexicana Entre Viñedos</td>
					</tr>
					
					<tr>
						<td><b>Nombre de Cliente:</b> $nombre</td>
					</tr>
					$loteHtml
					<tr>
						<td><b>Teléfono:</b> $telefono</td>
					</tr>
					<tr>
						<td><b>Email:</b> $email</td>
					</tr>
					<tr>
						<td><b>Fecha de Creación:</b> $fecha_creacion</td>
					</tr>

					<tr>
						<td></td>
					</tr>

					<tr>
						<td style="font-size: 12px;"><b>Código de Reservación:</b> $id</td>
					</tr>
					<tr>
						<td style="font-size: 12px;"><b>Total a Pagar:</b> $ $importe MXN</td>
					</tr>
				</table>
				<br /><br />

				<table style="border: 2px solid #DDDCDD;">
				</table>
EOF;
		
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->lastPage();
			$pdf->Output('ReferenciaBancaria.pdf', 'I');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

    public function listado() {
		try {
			$datosVista = array();

			// Pendientes
			$sth = $this->_db->prepare("SELECT * FROM eventos ORDER BY id DESC ");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => $datos['nombre'],
					'fecha' => Modelos_Fecha::formatearFecha($datos['fecha']),
					'hora' => $datos['hora'],
					'limite' => $datos['limite'],
					'existencia' => $datos['existencia'],
					'imagen_web' => $datos['imagen_web'],
					'imagen_movil' => $datos['imagen_movil'],
				);

				$datosVista['activos'][] = $arreglo;
				$x++;
			}
			$datosVista['nActivos'] = $x;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function impresion($id) {
		try {
			$sth = $this->_db->prepare("
				SELECT *
				FROM eventos_reservas
				WHERE id = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			if (!$datos) die;

			$openpay_id = $datos['openpay_id'];
			$nombre = $datos['nombre'];
			$telefono = $datos['telefono'];
			$email = $datos['email'];
			$boletos = $datos['boletos'];
			$importe = $datos['importe'];
			$lote = $datos['lote'];
			$fechaCreacion = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);

			if ($lote) {
				$precio = '$ 900.00';
				$loteHtml = '<tr><td><b>Lote:</b> ' . $lote . '</td></tr>';
			} else {
				$precio = '$ 1100.00';
				$loteHtml = '';
			}
			
			// PDF
			require_once(APP . 'plugins/tcpdf/tcpdf.php');
			$pdf = new RTFPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetTitle('Boleto de Evento');
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->SetFont('helvetica', '', 10);
			$pdf->SetPrintHeader(false);
			$pdf->SetMargins(10, 10, 10, 0);

			$stasis = STASIS;

			for ($x=0; $x<=$boletos-1; $x++) {
				$nQr = sprintf('%03d', $id+$x);
				$xFake = $x+1;

				$pdf->AddPage();
				$html = <<<EOF
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width: 250px; color: #444;">
								<img src="$stasis/img/rtecate.png" height="62" />
							</td>
						</tr>
					</table>
					<br /><br />

					<table style="border: 2px solid #DDDCDD;">
					</table>
					<br /><br />

					<table style="border: 1px solid #DDDCDD;" cellpadding="10">
						<tr>
							<td>
								RESIDENCIAL RANCHO TECATE<br />
								<span style="color: #999; font-size: 8px;">PRESENTA</span><br />
								<span style="font-size: 12px; font-weight: bold;">Noche Mexicana Entre Viñedos</span><br /><br />

								<span style="color: #999; font-size: 8px;">RTEV1 FOLIO $nQr</span><br />
								Jueves 15 Sep 2022<br />
								06:00 pm<br /><br />

								ADMISION <b>$precio</b><br />
								BOLETO <b>$xFake/$boletos</b><br />
								<span style="color: #999; font-size: 8px;">Tarjeta de crédito o débito</span>
							</td>
							<td style="text-align: right;">
								<br /><br />
								<img src="http://chart.apis.google.com/chart?cht=qr&chs=125x125&chl=$nQr&chld=H|0" height="125"><br />
							</td>
						</tr>
					</table>

					<br /><br />

					<table style="text-align: left; font-size: 9px;" border="0" cellpadding="1" cellspacing="0">
						<tr>
							<td>Para dudas y aclaraciones con respecto a este boleto, favor de contactárnos al número 664 387 8533.<br /></td>
						</tr>
						<tr>
							<td><b>ID Transacción:</b> $openpay_id</td>
						</tr>
						<tr>
							<td><b>Nombre:</b> $nombre</td>
						</tr>
						$loteHtml
						<tr>
							<td><b>Teléfono:</b> $telefono</td>
						</tr>
						<tr>
							<td><b>Email:</b> $email</td>
						</tr>
						<tr>
							<td><b>Fecha de Compra:</b> $fechaCreacion</td>
						</tr>
					</table>
					<br /><br />

					<table style="border: 2px solid #DDDCDD;">
					</table>
					<br /><br />

					<div style="text-align: center;">
						<a href="https://residencialrt.mx">www.residencialrt.mx</a>
					</div>
EOF;
			
				$pdf->writeHTML($html, true, false, true, false, '');
			}

			$pdf->lastPage();

			$pdf->Output('Boletos_' . $folio . '.pdf', 'I');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificar($id) {
		try {
			$datosArray = array();
			
			$sth = $this->_db->prepare("
				SELECT i.id, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.mensaje, i.prioridad, i.fecha_creacion, i.archivo
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.uniqueid = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			if (!$datos) die;

			$datosArray['id'] = $datos['id'];
			$datosArray['remitente'] = $datos['remitente'];
			$datosArray['destinatario'] = $datos['destinatario'];
			$datosArray['titulo'] = $datos['titulo'];
			$datosArray['mensaje'] = $datos['mensaje'];
			$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);
			$datosArray['motivo_cancelacion'] = $datos['motivo_cancelacion'];
			$datosArray['status'] = $datos['status'];
			$datosArray['conclusion'] = $datos['conclusion'];
			$datosArray['administrador'] = $datos['administrador'];
			$datosArray['conclusion_archivo'] = $datos['conclusion_archivo'];

			switch ($datos['prioridad']) {
				case 1: $datosArray['prioridad'] = 'BAJA'; break;
				case 2: $datosArray['prioridad'] = 'MEDIA'; break;
				case 3: $datosArray['prioridad'] = 'ALTA'; break;
			}

			$datosArray['archivos'] = [];
			if ($datos['archivo']) {
				$archivosArray[] = $datos['archivo'];
				$datosArray['archivos'] = $archivosArray;
			}

			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoReservas() {
		try {
			$datosVista = array();

			// Pagadas
			$sth = $this->_db->query("
				SELECT *
				FROM eventos_reservas
				WHERE (status = 1) OR (status = 4 AND pagado = 1) OR (status = 3 AND pagado = 1)
				GROUP BY openpay_id
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				switch ($datos['status']) {
					case 1: $formaPago = 'TARJETA'; break;
					case 2: $formaPago = 'ESTABLECIMIENTO'; break;
					case 3: $formaPago = 'TRANSFERENCIA'; break;
					case 4: $formaPago = 'EFECTIVO'; break;
				}

				$arreglo = array(
					'id' => $datos['id'],
					'status' => $datos['status'],
					'openpay_id' => $datos['openpay_id'],
					'nombre' => mb_strtoupper($datos['nombre']),
					'telefono' => $datos['telefono'],
					'email' => strtolower($datos['email']),
					'boletos' => $datos['boletos'],
					'importe' => '$ ' . $datos['importe'],
					'lote' => $datos['lote'],
					'confirmado' => $datos['confirmado'],
					'forma_pago' => $formaPago,
					'fecha_creacion' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
				);

				$datosVista['activos'][] = $arreglo;
				$x++;
			}
			$datosVista['nActivos'] = $x;

			// Referencias
			$sth = $this->_db->query("
				SELECT *
				FROM eventos_reservas
				WHERE status = 2 AND pagado = 0
				GROUP BY openpay_id
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'openpay_id' => $datos['openpay_id'],
					'nombre' => mb_strtoupper($datos['nombre']),
					'telefono' => $datos['telefono'],
					'email' => strtolower($datos['email']),
					'boletos' => $datos['boletos'],
					'importe' => '$ ' . $datos['importe'],
					'lote' => $datos['lote'],
					'fecha_creacion' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
				);

				$datosVista['referencias'][] = $arreglo;
				$x++;
			}
			$datosVista['nReferencias'] = $x;

			// Bancarias
			$sth = $this->_db->query("
				SELECT *
				FROM eventos_reservas
				WHERE status = 3 AND pagado = 0
				GROUP BY openpay_id
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'openpay_id' => $datos['openpay_id'],
					'nombre' => mb_strtoupper($datos['nombre']),
					'telefono' => $datos['telefono'],
					'email' => strtolower($datos['email']),
					'boletos' => $datos['boletos'],
					'importe' => '$ ' . $datos['importe'],
					'lote' => $datos['lote'],
					'fecha_creacion' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
				);

				$datosVista['transferencias'][] = $arreglo;
				$x++;
			}
			$datosVista['nTransferencias'] = $x;

			// Efectivo
			$sth = $this->_db->query("
				SELECT *
				FROM eventos_reservas
				WHERE status = 4 AND pagado = 0
				GROUP BY openpay_id
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'openpay_id' => $datos['openpay_id'],
					'nombre' => mb_strtoupper($datos['nombre']),
					'telefono' => $datos['telefono'],
					'email' => strtolower($datos['email']),
					'boletos' => $datos['boletos'],
					'importe' => '$ ' . $datos['importe'],
					'lote' => $datos['lote'],
					'fecha_creacion' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
				);

				$datosVista['efectivo'][] = $arreglo;
				$x++;
			}
			$datosVista['nEfectivo'] = $x;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoAsistencia() {
		try {
			$datosVista = array();

			// Pagadas
			$sth = $this->_db->query("
				SELECT *
				FROM eventos_reservas
				WHERE (status = 1) OR (status = 4 AND pagado = 1) OR (status = 3 AND pagado = 1)
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				switch ($datos['status']) {
					case 1: $formaPago = 'TARJETA'; break;
					case 2: $formaPago = 'ESTABLECIMIENTO'; break;
					case 3: $formaPago = 'TRANSFERENCIA'; break;
					case 4: $formaPago = 'EFECTIVO'; break;
				}

				$arreglo = array(
					'id' => $datos['id'],
					'status' => $datos['status'],
					'openpay_id' => $datos['openpay_id'],
					'nombre' => mb_strtoupper($datos['nombre']),
					'telefono' => $datos['telefono'],
					'email' => strtolower($datos['email']),
					'boletos' => $datos['boletos'],
					'importe' => '$ ' . $datos['importe'],
					'lote' => $datos['lote'],
					'confirmado' => $datos['confirmado'],
					'forma_pago' => $formaPago,
					'fecha_creacion' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
				);

				$datosVista['activos'][] = $arreglo;
				$x++;
			}

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function comprobantePago($id) {
		$sth = $this->_db->prepare("SELECT * FROM eventos_reservas WHERE openpay_id = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$openpay_id = $datos['openpay_id'];
		$nombre = mb_strtoupper($datos['nombre'], 'UTF-8');
		$telefono = $datos['telefono'];
		$email = strtolower($datos['email']);
		$email2 = strtolower($datos['email2']);
		$boletos = $datos['boletos'];
		$importe = number_format($datos['importe'], 2, '.', ',');
		
		$fechaPago = new DateTime($datos['fecha_pagado']);
		$dia = $fechaPago->format('d');
		$mes = $fechaPago->format('m');
		$ano = $fechaPago->format('Y');

		$concepto = $boletos . ' Boleto(s) Noche Mexicana Entre Viñedos';

		if ($datos['lote']) {
			$lote = $datos['lote'];
		} else {
			$lote = 'N/A';
		}

		if ($lote) {
			$precio = '$ 900.00';
		} else {
			$precio = '$ 1100.00';
		}

		// PDF
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new RTPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Comprobante de Pago');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetPrintHeader(false);
		$pdf->SetMargins(10, 10, 10, 0);
		$pdf->AddPage();

		$stasis = STASIS;
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Bold.ttf', 'TrueTypeUnicode', '', 96);
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Regular.ttf', 'TrueTypeUnicode', '', 96);

		$html = <<<EOF
		    <table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 405px;">
						<table border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td style="width: 135px; color: #444; border-top: 1px #000 solid; border-left: 1px #000 solid; border-bottom: 1px #000 solid;">
									<br /><br /><img src="$stasis/img/cobroplan.png" height="50" />
								</td>

								<td style="width: 130px; text-align: center; color: #444; border-top: 1px #000 solid; border-bottom: 1px #000 solid;">
									<br /><br /><span style="font-size: 8px; font-family: 'Roboto Bold';">COBROPLAN S.C.</span><br />
									<span style="font-size: 7px;">MANUEL DOBLADO 1101 A,<br />CALETE, TIJUANA, B.C.<br />TEL 664 680 6052</span>
								</td>

								<td style="width: 135px; text-align: center; color: #444; border-top: 1px #000 solid; border-right: 1px #000 solid; border-bottom: 1px #000 solid;">
									<span style="font-size: 8px; font-family: 'Roboto Bold';">ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT</span><br />
									<span style="font-size: 7px;">KM 10.5 CARRETERA<br />TECATE - ENSENADA<br />TECATE, B.C.<br />TEL 665 654 0011</span></td>
							</tr>
						</table>
						<br /><br />

						<table border="1" cellpadding="5" cellspacing="0">
							<tr>
								<td style="width: 95px; font-family: 'Roboto Bold'; font-size: 8px; color: #444;">
									CLIENTE: 
								</td>
								<td style="width: 305px; font-size: 8px; color: #444;">
									$nombre
								</td>
							</tr>
							<tr>
								<td style="width: 95px; font-family: 'Roboto Bold'; font-size: 8px; color: #444;">
									CELULAR: 
								</td>
								<td style="width: 305px; font-size: 8px; color: #444;">
									$telefono
								</td>
							</tr>
							<tr>
								<td style="width: 95px; font-family: 'Roboto Bold'; font-size: 8px; color: #444;">
									EMAIL: 
								</td>
								<td style="width: 305px; font-size: 8px; color: #444;">
									$email
								</td>
							</tr>
						</table>
					</td>

					<td style="width: 134px;">
                        <table border="1" cellpadding="5" cellspacing="0">
							<tr>
							    <td colspan="3" style="text-align: center; color: #FFF; background-color: #004B93; font-size: 8px;">
									CÓDIGO DE RESERVACIÓN
								</td>
							</tr>
							<tr>
							    <td colspan="3" style="text-align: center; color: #000; font-size: 12px;">
									$openpay_id
								</td>
							</tr>

							<tr>
							    <td style="text-align: center; color: #FFF; background-color: #004B93; font-size: 8px;">
									DIA
								</td>
								<td style="text-align: center; color: #FFF; background-color: #004B93; font-size: 8px;">
									MES
								</td>
								<td style="text-align: center; color: #FFF; background-color: #004B93; font-size: 8px;">
									AÑO
								</td>
							</tr>
							<tr>
							    <td style="text-align: center; color: #000; font-size: 7px;">
									$dia
								</td>
								<td style="text-align: center; color: #000; font-size: 7px;">
									$mes
								</td>
								<td style="text-align: center; color: #000; font-size: 7px;">
									$ano
								</td>
							</tr>

							<tr>
							    <td colspan="3" style="text-align: center; color: #FFF; background-color: #004B93; font-size: 8px;">
									LOTE DEL CLIENTE
								</td>
							</tr>
							<tr>
							    <td colspan="3" style="text-align: center; color: #000; font-size: 7px;">
									$lote
								</td>
							</tr>

						</table>
					</td>

				</tr>
			</table>

			<br /><br />

			<table border="1" cellpadding="5" cellspacing="0">
				<tr>
				    <td style="text-align: center; color: #FFF; width: 411px; background-color: #004B93; font-size: 8px;">
						CONCEPTO
					</td>
					<td style="text-align: center; color: #FFF; width: 128px; background-color: #004B93; font-size: 8px;">
						IMPORTE
					</td>
				</tr>
				<tr>
				    <td style="text-align: center; color: #000; font-size: 8px;">
						$concepto
					</td>
					<td style="text-align: center; color: #000; font-size: 8px;">
						$ $importe
					</td>
				</tr>
			</table>

			<br /><br /><br /><br />

			<table border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td style="width:100%; text-align: center; font-family: \'Roboto\';">___________________________<br />Firma</td>
				</tr>
			</table>

			<br />
			<div style="text-align: center; font-size: 7px; color: #666;">COBROPLAN S.C actuando como mandatario por nombre y cuenta de ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT.</div>
EOF;
		
		$fechaPdf = date('d-m-Y');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		$pdf->Output("ComprobantePago.pdf", 'I');
	}

	public function confirmar($id) {
		$boleto = $id;

		$sth = $this->_db->prepare("SELECT * FROM eventos_reservas WHERE id = ?");
		$sth->bindParam(1, $boleto);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		if ($datos) {
			if ($datos['confirmado'] == 1) {
				$html = '
					<div class="alert alert-custom alert-warning m-0 p-0" role="alert" style="padding: 20px !important;">
						<div class="alert-text text-center" id="info-boleto">
							<i class="fa fa-exclamation-triangle text-center text-white" style="font-size: 50px; margin-bottom: 10px;"></i><br />
							Código ya ingresado en el evento<br /><br />

							' . $datos['nombre'] . '<br />
							<b>' . $datos['boletos'] . ' Boleto(s)</b><br /><br />
							' . $datos['telefono'] . '<br />
							' . $datos['email'] . '<br />
							' . $datos['lote'] . '
						</div>
					</div>
				';
			} else {
				$html = '
					<div class="alert alert-custom alert-primary m-0 p-0" role="alert" style="padding: 20px !important;">
						<div class="alert-text text-center" id="info-boleto">
							<i class="fa fa-check text-center text-white" style="font-size: 50px; margin-bottom: 10px;"></i><br />
							' . $datos['nombre'] . '<br />
							<b>' . $datos['boletos'] . ' Boleto(s)</b><br /><br />
							' . $datos['telefono'] . '<br />
							' . $datos['email'] . '<br />
							' . $datos['lote'] . '
						</div>
					</div>
				';
			}
		} else {
			$html = '
				<div class="alert alert-custom alert-danger m-0 p-0" role="alert" style="padding: 20px !important;">
					<div class="alert-text text-center" id="info-boleto">
						<i class="fa fa-times text-center text-white" style="font-size: 50px; margin-bottom: 10px;"></i><br />
						Boleto de reservación no existe
					</div>
				</div>
			';
		}

		$sth = $this->_db->prepare("UPDATE eventos_reservas SET confirmado = 1 WHERE id = ?");
		$sth->bindParam(1, $boleto);
		if(!$sth->execute()) throw New Exception();

		echo $html;
	}

}