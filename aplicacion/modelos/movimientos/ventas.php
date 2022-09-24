<?php
final class Modelos_Movimientos_Ventas extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function historial() {
		try {
			$datosVista = array();

			// Vendedor
			if ($_SESSION['login_tipo'] == 6) {
				$qry = 'AND pv.id_usuario = ' . $_SESSION['login_id'];
			} else  {
				$qry = '';
			}

			// Pendientes
			$sth = $this->_db->query("
				SELECT pv.id, CONCAT(e.nombre, ' ', e.apellidos) AS vendedor, pv.promesa, pv.fecha_creacion
				FROM promesas_venta pv
				JOIN empleados e
				ON e.id = pv.id_usuario
				WHERE pv.status = 1 $qry
				ORDER BY pv.id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				switch ($datos['promesa']) {
					case 1: $promesa = '2 NOCHES DE HOTEL'; break;
					case 2: $promesa = 'CENA FAMILIAR'; break;
					case 3: $promesa = 'KIT DE VINOS'; break;
					case 4: $promesa = 'LIMPIEZA DE TERRENO'; break;
				}

				$arreglo = array(
					'id' => $datos['id'],
					'vendedor' => $datos['vendedor'],
					'promesa' => $promesa,
					'fecha' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion'])
				);
				$datosVista['generadas'][] = $arreglo;

				$x++;
			}
			$datosVista['nGeneradas'] = $x;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function nueva() {
		try {
			$datosArray = array();

			// Folio
			$sth = $this->_db->query("SELECT id FROM promesas_venta ORDER BY id DESC LIMIT 1");
			$datosArray['folio'] = $sth->fetchColumn()+1;

			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function generar() {
		try {
			$idGenerado = $_SESSION['login_id'];
			$promesa = $_POST['promesa'];

			$fecha_vencimiento = $_POST['fecha_vencimiento'];
			$destinatario = $_POST['destinatario'];
			$propietario = $_POST['propietario'];
			$nombre_prospecto = $_POST['nombre_prospecto'];
			$tel_prospecto = $_POST['tel_prospecto'];
			$correo_prospecto = $_POST['correo_prospecto'];
			$promesa = $_POST['promesa'];
			$motivo = $_POST['motivo'];

			$sth = $this->_db->prepare("INSERT INTO promesas_venta (id_usuario, promesa, fecha_creacion, fecha_vencimiento, destinatario, propietario, nombre_prospecto, tel_prospecto, correo_prospecto, motivo) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)");
			$sth->bindParam(1, $idGenerado);
			$sth->bindParam(2, $promesa);
			if(!$sth->execute()) throw New Exception();

			header('Location:' . STASIS. '/movimientos/ventas/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function visualizar($id) {
		$this->pdf($id,0,1);
	}

	public function pdf($id) {
		// PDF
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Promesa de Venta');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->SetMargins(10, 10, 10, 0);
		$pdf->AddPage();

		$sth = $this->_db->prepare("
			SELECT pv.id, CONCAT(e.nombre, ' ', e.apellidos) AS vendedor, pv.promesa, pv.fecha_creacion, e.foto, e.telefono, e.email
			FROM promesas_venta pv
			JOIN empleados e
			ON e.id = pv.id_usuario
			WHERE pv.id = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		switch ($datos['promesa']) {
			case 1: $promesa = '2 NOCHES DE HOTEL'; break;
			case 2: $promesa = 'CENA FAMILIAR'; break;
			case 3: $promesa = 'KIT DE VINOS'; break;
			case 4: $promesa = 'LIMPIEZA DE TERRENO'; break;
		}

		$id = $datos['id'];
		$vendedor = $datos['vendedor'];
		$promesa = $promesa;
		$fecha_creacion = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);

		if (!$datos['foto']) {
			$foto = 'img/prop.png';
		} else {
			$foto = 'data/f/' . $datos['foto'];
		}
		$telefono = $datos['telefono'];
		$email = $datos['email'];

		$stasis = STASIS;
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Bold.ttf', 'TrueTypeUnicode', '', 96);
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Regular.ttf', 'TrueTypeUnicode', '', 96);

		$html = <<<EOF
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 250px; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">Rancho Tecate</span><br />

						<span style="font-size: 9px;">Km 10 Carretera Tecate-Ensenada<br />
							Tecate, Baja California, México C.P. 21530<br />
							Correo: atenciontelefonica@ranchotecate.mx<br />
							Teléfono: (665) 502-8767
						</span>
					</td>
					<td style="width: 213px; text-align: right;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">PROMESA DE VENTA</span><br /><br />
						<span style="font-size: 9px;">Folio: $id<br />Fecha: $fecha_creacion</span><br />
					</td>
					<td style="width: 75px; text-align: right;">
						<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=https://gvalcas.dualstudio.com.mx/movimientos/ventas/visualizar/$id&chld=H|0" height="65">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br />

			<div style="text-align: center; font-size: 9px;">
				<span style="font-weight: bold; text-align: center; font-size: 10px;">NOMBRE DEL VENDEDOR</span>
			</div>

			<table>
				<tr>
					<td style="width: 15%">
						<img src="$stasis/$foto" height="55" />
					</td>
					<td style="width: 85%">
						<table style="text-align: left; font-size: 8px;" cellpadding="2" cellspacing="1">
							<tr>
								<td style="background-color: #00436C; color: #FFF; width: 50%">
									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Nombre:</strong>
								</td>
								<td style="background-color: #00436C; color: #FFF; width: 50%">
									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Departamento:</strong>
								</td>
							</tr>
							<tr>
								<td style="text-align: center;">$vendedor</td>
								<td style="text-align: center;">VENTAS</td>
							</tr>
							<tr>
								<td style="background-color: #00436C; color: #FFF; width: 50%">
									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Teléfono:</strong>
								</td>
								<td style="background-color: #00436C; color: #FFF; width: 50%">
									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Correo:</strong>
								</td>
							</tr>
							<tr>
								<td style="text-align: center;">$telefono</td>
								<td style="text-align: center;">$email</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="text-align: left; font-size: 8px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 537px">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Promesa de Venta:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$promesa</td>
				</tr>
			</table>
			<br />

			$htmlResponsable
EOF;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		$pdf->Output('I');
	}

	public function eliminar($id) {
		try {
			$sth = $this->_db->prepare("DELETE FROM requisiciones WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/ventas/historial/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function autorizar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE requisiciones SET status = 2 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

			$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 1, id_autoriza = ?, fecha_autorizacion = NOW() WHERE id_requisicion = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/ventas/historial/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function rechazar() {
		try {
			$id = $_POST['id'];
			$motivo_rechazo = mb_strtoupper($_POST['motivo_rechazo'], 'UTF-8');
			$tipo_rechazo = $_POST['tipo_rechazo'];

			$sth = $this->_db->prepare("UPDATE requisiciones SET status = 3, fecha_rechazo = NOW(), motivo_rechazo = ?, tipo_rechazo = ?, id_usuario_rechaza = ? WHERE id = ?");
			$sth->bindParam(1, $motivo_rechazo);
			$sth->bindParam(2, $tipo_rechazo);
			$sth->bindParam(3, $_SESSION['login_id']);
			$sth->bindParam(4, $id);
			if(!$sth->execute()) throw New Exception();

	  		header('Location: ' . STASIS . '/movimientos/ventas/historial/4');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function procesar() {
		try {
			$id = $_POST['id'];
			$oc = strtoupper($_POST['oc']);
			$dias_entrega = $_POST['dias_entrega'];

			$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 2, oc = ?, dias_entrega = ?, id_procesa = ?, fecha_procesa = NOW() WHERE id = ?");
			$sth->bindParam(1, $oc);
			$sth->bindParam(2, $dias_entrega);
			$sth->bindParam(3, $_SESSION['login_id']);
			$sth->bindParam(4, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/ventas/historial/2');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoPropietarios() {
		try {
			$sth = $this->_db->query("
				SELECT id, tipo, nombre
				FROM propietarios
				WHERE seccion != '' AND status = 1
				ORDER BY tipo ASC, nombre ASC
			");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				$html .= '<option value="' . $datos['id'] . '">' . $datos['tipo'] . ' - ' . $datos['nombre'] . '</option>';
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}