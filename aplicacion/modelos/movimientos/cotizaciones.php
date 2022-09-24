<?php
use Openpay\Data\Openpay;
use SWServices\Stamp\StampService as StampService;
use SWServices\AccountBalance\AccountBalanceService as AccountBalanceService;
use SWServices\Cancelation\CancelationService as CancelationService;
use SWServices\Toolkit\SignService as Sellar;
use \CfdiUtils\XmlResolver\XmlResolver;
use \CfdiUtils\CadenaOrigen\DOMBuilder;

final class Modelos_Movimientos_Cotizaciones extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function realizarPago() {
    	try {
    		// DB
    		$sth = $this->_db->prepare("
    			SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones, c.status, co.nombre AS concepto, cp.um, cp.cantidad, cp.precio, co.id_empresa_cobroplan
    			FROM cotizaciones c
    			JOIN propietarios p
    			ON p.id = c.id_cliente
    			JOIN cotizaciones_partes cp
    			ON cp.id_cotizacion = c.id
    			JOIN conceptos co
    			ON co.id = cp.id_concepto
    			JOIN empleados e
    			ON e.id = c.id_agente
    			WHERE c.alfanumerico = ?
    		");
    		$sth->bindParam(1, $_POST['id']);
    		if(!$sth->execute()) throw New Exception();
    		$datos = $sth->fetch();

    		$alfanumerico = $datos['alfanumerico'];

    		switch ($datos['seccion']) {
    			case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
    			case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
    			case 'LOMAS (RGR)': $prefijo = 'SL'; break;
    			case 'LOMAS': $prefijo = 'SL'; break;
    			case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
    			case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
    			case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
    			case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
    			case 'VISTA DEL REY': $prefijo = 'VR'; break;
    		}
    		$datosArray['lote'] = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

    		if ($datos['moneda'] == 1) {
    			$moneda = 'MXN';
    		} elseif ($datos['moneda'] == 2) {
    			$moneda = 'USD';
    		}

    		$datosArray['concepto'] = $datos['concepto'];
    		$datosArray['um'] = $datos['um'];
    		$datosArray['id_empresa_cobroplan'] = $datos['id_empresa_cobroplan'];
    		$datosArray['cantidad'] = $datos['cantidad'];
    		$datosArray['precio'] = '$ ' . number_format($datos['precio'], 2, '.', ',');
    		$datosArray['totalConcepto'] = '$ ' . number_format($datos['precio']*$datos['cantidad'], 2, '.', ',');

    		$datosArray['folio'] = $datos['id'];
    		$datosArray['alfanumerico'] = $datos['alfanumerico'];
    		$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);
    		$datosArray['fecha_vigencia'] = Modelos_Fecha::formatearFecha($datos['vigencia']);
    		$datosArray['agente'] = $grado . mb_strtoupper($datos['agente'], 'UTF-8');
    		$datosArray['agenteCorreo'] = $datos['agente_email'];
    		$datosArray['agenteCelular'] = $datos['agente_celular'];
    		$datosArray['porImpuesto'] = $datos['por_impuesto']*100;
    		$datosArray['propietario'] = $datos['nombre'];
    		$datosArray['telefono1'] = $datos['telefono1'];
    		$datosArray['telefono2'] = $datos['telefono2'];
    		$datosArray['email'] = strtolower($datos['email']);
    		$datosArray['rfc'] = $datos['rfc'];
    		$datosArray['vigencia'] = Modelos_Fecha::formatearFecha($datos['vigencia']);
    		$datosArray['totalLetras'] = strtoupper(Modelos_Caracteres::num2letras($datos['total'], $moneda));
    		$datosArray['idCliente'] = $datos['id_cliente'];
    		$datosArray['cliente'] = $datos['cliente'];
    		$datosArray['subtotal'] = number_format($datos['subtotal'], 2, '.', ',');
    		$datosArray['impuesto'] = number_format($datos['impuesto'], 2, '.', ',');
    		$datosArray['total'] = number_format($datos['total'], 2, '.', '');
    		$datosArray['observaciones'] = $datos['observaciones'];
    		$datosArray['concepto'] = 'PAGO DE COTIZACIÓN FOLIO #' . $datos['id'];

    		// Openpay
    		require_once(APP . 'plugins/openpay/vendor/autoload.php');

			Openpay::setProductionMode(true);
			$openpay = Openpay::getInstance('m7aci0xq2pyewsqdhy9r', 'sk_3b3ded4ccf584eab9b4dd9536b4db4f3');

			$customer = array(
			     'name' => $datos['nombre'],
			     'phone_number' => $datos['telefono1'],
			     'email' => $datos['email'],
			);

			$chargeData = array(
			    'method' => 'card',
			    'currency' => $moneda,
			    'source_id' => $_POST["token_id"],
			    'amount' => $datosArray['total'],
			    'description' => 'PAGO DE COTIZACIÓN FOLIO #' . $datos['id'],
			    'use_card_points' => false,
			    'device_session_id' => $_POST["deviceIdHiddenFieldName"],
			    'customer' => $customer,
                'redirect_url' => 'https://saevalcas.mx/e/p/t/' . $datos['alfanumerico'] . '/'
		    );

			$charge = $openpay->charges->create($chargeData);
			$autorizacion = $charge->authorization;

			// Insertar autorizacion de Openpay a DB
			$sth = $this->_db->prepare("UPDATE cotizaciones SET openpay = ? WHERE id = ?");
			$sth->bindParam(1, $autorizacion);
			$sth->bindParam(2, $datos['id']);
			if(!$sth->execute()) throw New Exception();

			// API de insercion en Cobroplan
    		$json = '{
		        "openPayPaymentId": "' . $autorizacion . '",
		        "propiedad_id": "",
		        "servicio_id": 5,
		        "compania_id": ' . $datos['id_empresa_cobroplan'] . ',
		        "user_id": 1574,
		        "pagado": true,
		        "tipoPago": "debito",
		        "moneda": "' . $moneda . '",
		        "monto": ' . $datosArray['total'] . ',
		        "descripcion": "PAGO DE COTIZACIÓN FOLIO #' . $datos['id'] . '"
		    }';

    		$ch = curl_init('https://cobroplan.mx/api/SetPago');
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			    "Accept: application/json",
			    "Content-Type: application/json",
			    "Authorization: Bearer 1|NbuE07iBu3er5qzdr5Z4O94VmL1kwal2Btwz4NYD"
			));
			$response = curl_exec($ch);
			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if(curl_errno($ch)) throw new Exception(curl_error($ch));

			// API de Openpay
			$ch = curl_init('https://api.openpay.mx/v1/m7aci0xq2pyewsqdhy9r/charges/' . $autorizacion);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			    "Accept: application/json",
			    "Content-Type: application/json",
			    "Authorization: Basic c2tfM2IzZGVkNGNjZjU4NGVhYjliNGRkOTUzNmI0ZGI0ZjM6"
			));
			$response = curl_exec($ch);
			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if(curl_errno($ch)) throw new Exception(curl_error($ch));

			$sth = $this->_db->prepare("UPDATE cotizaciones SET status = 3 WHERE id = ?");
			$sth->bindParam(1, $datos['id']);
			if(!$sth->execute()) throw New Exception();

			// Redireccion
			$jsonReponse = json_decode($response);
			$locacion = $jsonReponse->payment_method->url;

			// $this->timbrar($datos['id']);

			header("Location: " . $locacion);
		} catch (OpenpayApiTransactionError $e) {
			var_dump('ERROR on the transaction: ' . $e->getMessage() . 
			      ' [error code: ' . $e->getErrorCode() . 
			      ', error category: ' . $e->getCategory() . 
			      ', HTTP code: '. $e->getHttpCode() . 
			      ', request ID: ' . $e->getRequestId() . ']', 0);

		} catch (OpenpayApiRequestError $e) {
			var_dump('ERROR on the request: ' . $e->getMessage(), 0);

		} catch (OpenpayApiConnectionError $e) {
			var_dump('ERROR while connecting to the API: ' . $e->getMessage(), 0);

		} catch (OpenpayApiAuthError $e) {
			var_dump('ERROR on the authentication: ' . $e->getMessage(), 0);
			
		} catch (OpenpayApiError $e) {
			var_dump('ERROR on the API: ' . $e->getMessage(), 0);
			
		} catch (Exception $e) {
			var_dump('Error on the script: ' . $e->getMessage(), 0);
		}

		// echo '<pre>' . var_export($charge, true) . '</pre>';
		// echo $response;
	}

	public function referenciaBancaria($id) {
    	try {
    		// DB
    		$sth = $this->_db->prepare("
    			SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones, c.status, co.nombre AS concepto, cp.um, cp.cantidad, cp.precio, co.id_empresa_cobroplan, p.clabe, c.tipo_cambio
    			FROM cotizaciones c
    			JOIN propietarios p
    			ON p.id = c.id_cliente
    			JOIN cotizaciones_partes cp
    			ON cp.id_cotizacion = c.id
    			JOIN conceptos co
    			ON co.id = cp.id_concepto
    			JOIN empleados e
    			ON e.id = c.id_agente
    			WHERE c.id = ?
    		");
    		$sth->bindParam(1, $id);
    		if(!$sth->execute()) throw New Exception();
    		$datos = $sth->fetch();

    		$alfanumerico = $datos['alfanumerico'];

    		switch ($datos['seccion']) {
    			case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
    			case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
    			case 'LOMAS (RGR)': $prefijo = 'SL'; break;
    			case 'LOMAS': $prefijo = 'SL'; break;
    			case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
    			case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
    			case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
    			case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
    			case 'VISTA DEL REY': $prefijo = 'VR'; break;
    		}
    		$lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

    		if ($datos['moneda'] == 1) {
    			$moneda = 'MXN';
    			$totalFinal = number_format($datos['total'], 2, '.', ',');
    		} elseif ($datos['moneda'] == 2) {
    			$moneda = 'USD';
    			$totalFinal = number_format($datos['total']*$datos['tipo_cambio'], 2, '.', ',');
    		}

    		$concepto = $datos['concepto'];
    		$um = $datos['um'];
    		$clabe = $datos['clabe'];
    		$id_empresa_cobroplan = $datos['id_empresa_cobroplan'];
    		$cantidad = $datos['cantidad'];
    		$precio = '$ ' . number_format($datos['precio'], 2, '.', ',');
    		$totalConcepto = '$ ' . number_format($datos['precio']*$datos['cantidad'], 2, '.', ',');

    		$folio = $datos['id'];
    		$alfanumerico = $datos['alfanumerico'];
    		$tipo_cambio = $datos['tipo_cambio'];
    		$fecha_creacion = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);
    		$fecha_vigencia = Modelos_Fecha::formatearFecha($datos['vigencia']);
    		$agente = mb_strtoupper($datos['agente'], 'UTF-8');
    		$agenteCorreo = $datos['agente_email'];
    		$agenteCelular = $datos['agente_celular'];
    		$porImpuesto = $datos['por_impuesto']*100;
    		$propietario = $datos['nombre'];
    		$telefono1 = $datos['telefono1'];
    		$telefono2 = $datos['telefono2'];
    		$email = strtolower($datos['email']);
    		$rfc = $datos['rfc'];
    		$vigencia = Modelos_Fecha::formatearFecha($datos['vigencia']);
    		$totalLetras = strtoupper(Modelos_Caracteres::num2letras($datos['total'], $moneda));
    		$idCliente = $datos['id_cliente'];
    		$cliente = $datos['cliente'];
    		$subtotal = number_format($datos['subtotal'], 2, '.', ',');
    		$impuesto = number_format($datos['impuesto'], 2, '.', ',');
    		$total = number_format($datos['total'], 2, '.', ',');
    		$observaciones = $datos['observaciones'];
    		$concepto = 'PAGO DE COTIZACIÓN FOLIO #' . $datos['id'];

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

				<span style="font-size: 12px; font-weight: bold;">Pago de cotización a través de referencia bancaria</span><br /><br />

				<table style="text-align: left; font-size: 9px;" border="0" cellpadding="1" cellspacing="0">
					<tr>
						<td>Para realizar su pago correspondiente de la <u>cotización folio #$folio</u>, favor de realizar la transferencia con los siguientes datos.<br />Por un total de <b>$ $totalFinal MXN</b> a banco <b>MONEX</b> con el siguiente número de referencia bancaria: <b>$clabe</b><br /></td>
					</tr>
					<tr>
						<td><b>Folio de Cotización:</b> $folio</td>
					</tr>
					<tr>
						<td><b>Concepto:</b> $concepto</td>
					</tr>
					
					<tr>
						<td><b>Nombre de Propietario:</b> $propietario</td>
					</tr>
					<tr>
						<td><b>Teléfono:</b> $telefono1</td>
					</tr>
					<tr>
						<td><b>Email:</b> $email</td>
					</tr>
					<tr>
						<td><b>Observaciones:</b> $observaciones</td>
					</tr>
					<tr>
						<td><b>Fecha de Cotización:</b> $fecha_creacion</td>
					</tr>
					<tr>
						<td><b>Fecha de Vigencia:</b> $fecha_vigencia</td>
					</tr>

					<tr>
						<td></td>
					</tr>

					<tr>
						<td><b>Tipo de Cambio:</b> $ $tipo_cambio</td>
					</tr>
					<tr>
						<td><b>Subtotal:</b> $ $subtotal $moneda</td>
					</tr>
					<tr>
						<td><b>IVA:</b> $ $impuesto $moneda</td>
					</tr>
					<tr>
						<td><b>Total:</b> $ $total $moneda</td>
					</tr>
				</table>
				<br /><br />

				<table style="border: 2px solid #DDDCDD;">
				</table>
EOF;
		
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->Cell(0, 0, 'Referencia Bancaria', 0, 1);
			$pdf->write1DBarcode($clabe, 'C39', '', '', '', 18, 0.4, $style, 'N');
			$pdf->Cell(0, 0, $clabe, 0, 1);
			$pdf->Ln();

			$pdf->lastPage();
			$pdf->Output('ReferenciaBancaria.pdf', 'I');
		} catch (Exception $e) {
			var_dump('Error on the script: ' . $e->getMessage(), 0);
		}
	}

    public function nueva() {
		try {
			$datosArray = array();

			// Folio
			$sth = $this->_db->query("SELECT id FROM cotizaciones ORDER BY id DESC LIMIT 1");
			$folio = $sth->fetchColumn()+1;
			
			// Fecha Actual
			$fecha = date('d/m/Y');

			// Nombre de Agente
			$agente = $_SESSION['login_nombre'] . ' ' . $_SESSION['login_apellidos'];

			$fechaActual = date('Y-m-d');

			$sth = $this->_db->query("SELECT tipo_cambio FROM tipos_cambio WHERE fecha = CURDATE() AND tipo_cambio IS NOT NULL LIMIT 1");
			if(!$sth->execute()) throw New Exception();

	    	$tipoCambio = $sth->fetchColumn();
	    	if (empty($tipoCambio)) {
	    		$datePlus = new DateTime();
				$datePlus->modify('+1 day');

				$date = $datePlus->format('Y-m-d') . '/' . $datePlus->format('Y-m-d');
				$token = 'dcd256707fdb6befb6b566404269d9bfe04aa8c1d70abb6ddb4534d436901881';
				$query = 'https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF60653/datos/' . $date . '?token='.$token;
				$json = json_decode(file_get_contents($query), true);
				$tipoCambio = $json['bmx']['series'][0]['datos'][0]['dato'];

				if ($tipoCambio) {
					$sth = $this->_db->prepare("INSERT INTO tipos_cambio (fecha, tipo_cambio) VALUES (?, ?)");
					$sth->bindParam(1, $fechaActual);
					$sth->bindParam(2, $tipoCambio);
					if(!$sth->execute()) throw New Exception();
				}
	    	}

	    	$datosArray['tipoCambio'] = $tipoCambio;
			$datosArray['folio'] = $folio;
			$datosArray['agente'] = $agente;
			
			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function infoPropietario() {
		try {
			$idPropietario = $_POST['idPropietario'];

			$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE id = ?");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			echo "
			    <script>
			        $('#propietario').val('" . $datos['tipo'] . " - " . $datos['nombre'] . "');
			        $('#telefono1').val('" . $datos['telefono1'] . "');
			        $('#telefono2').val('" . $datos['telefono2'] . "');
			        $('#correo').val('" . $datos['email'] . "');
		        </script>
	        ";
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoPropietarios($idCotizacion = null) {
		try {
			if (isset($idCotizacion)) {
				// $sth = $this->_db->prepare("SELECT id_centro_trabajo FROM empleados WHERE id = ?");
				// $sth->bindParam(1, $idCotizacion);
				// if(!$sth->execute()) throw New Exception();
				// $idCentroTrabajo = $sth->fetchColumn();
			}

			$sth = $this->_db->query("
				SELECT id, tipo, nombre, seccion, manzana, lote
				FROM propietarios
				WHERE status = 1
				ORDER BY seccion, manzana, lote
			");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				switch ($datos['seccion']) {
					case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
					case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
					case 'LOMAS (RGR)': $prefijo = 'SL'; break;
					case 'LOMAS': $prefijo = 'SL'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
					case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
					case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
					case 'VISTA DEL REY': $prefijo = 'VR'; break;
				}

				$lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
				// if (isset($idCentroTrabajo)) {
				// 	if ($idCentroTrabajo == $datos['id']) {
				// 		$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['nombre'] . '</option>';
				// 	} else {
				// 		$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
				// 	}
				// } else {
					
					$html .= '<option value="' . $datos['id'] . '">' . $lote . '</option>';

				// }
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificar($id) {
		try {
			$datosArray = array();

			// Datos de cotizacion
			$sth = $this->_db->prepare("
				SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				JOIN empleados e
				ON e.id = c.id_agente
				WHERE c.id = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$datosArray['alfanumerico'] = $datos['alfanumerico'];
			$datosArray['id'] = $datos['id'];
			$datosArray['id_cliente'] = $datos['id_cliente'];
			$datosArray['codigo'] = $datos['codigo'];
			$datosArray['razon_social'] = $datos['razon_social'];
			$datosArray['moneda'] = $datos['moneda'];
			$datosArray['id_solicitante'] = $datos['id_solicitante'];
			$datosArray['nombre'] = $datos['nombre'];
			$datosArray['telefono'] = $datos['telefono'];
			$datosArray['correo'] = $datos['correo'];
			$datosArray['vigencia'] = $datos['vigencia'];
			$datosArray['subtotal'] = number_format($datos['subtotal'], 2, '.', ',');
			$datosArray['impuesto'] = number_format($datos['impuesto'], 2, '.', ',');
			$datosArray['total'] = number_format($datos['total'], 2, '.', ',');
			$datosArray['por_impuesto'] = $datos['por_impuesto'];
			$datosArray['observaciones'] = $datos['observaciones'];

			switch ($datos['seccion']) {
				case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
				case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
				case 'LOMAS (RGR)': $prefijo = 'SL'; break;
				case 'LOMAS': $prefijo = 'SL'; break;
				case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
				case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
				case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
				case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
				case 'VISTA DEL REY': $prefijo = 'VR'; break;
			}
			$datosArray['lote'] = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

			if ($datos['moneda'] == 1) {
				$moneda = 'MXN';
			} elseif ($datos['moneda'] == 2) {
				$moneda = 'USD';
			}

			$datosArray['monedaFormatted'] = $moneda;
			$datosArray['concepto'] = 'PAGO DE COTIZACIÓN FOLIO #' . $datos['id'];

			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function generar() {
		try {
			$idAgente = $_SESSION['login_id'];
			$idCliente = $_POST['id_propietario'];
			$moneda = $_POST['moneda'];
			$tipoCambio = $_POST['tipoCambio'];
			$fechaVigencia = DateTime::createFromFormat('d/m/Y', $_POST['fechaVigencia']);
			$fechaVigencia = $fechaVigencia->format('Y-m-d');
			$telefono1 = $_POST['telefono1'];
			$telefono2 = $_POST['telefono2'];
			$correo = $_POST['correo'];

			$observaciones = strtoupper($_POST['observaciones']);
			$porImpuesto = $_POST['porImpuesto'];
			$subtotal = $_POST['subtotal'];
			$impuesto = $_POST['impuesto'];
			$total = $_POST['total'];
			$alfanumerico = uniqid();

			if ($moneda == 'PESOS') {
				$moneda = 1;
			} elseif ($moneda == 'DÓLARES') {
				$moneda = 2;
			}

			$arregloDatos = array($idAgente, $idCliente, $moneda, $fechaVigencia, $subtotal, $impuesto, $total, $observaciones, $tipoCambio, $porImpuesto, $alfanumerico, $telefono1, $telefono2, $correo);
			$sth = $this->_db->prepare("INSERT INTO cotizaciones (id_agente, id_cliente, moneda, vigencia, subtotal, impuesto, total, observaciones, tipo_cambio, por_impuesto, alfanumerico, telefono1, telefono2, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			if(!$sth->execute($arregloDatos)) throw New Exception();
			$id = $this->_db->lastInsertId();

			for($x=1; $x<=1; $x++) {
				$noParte = strtoupper($_POST["noParte$x"]);
				$descripcion = $_POST["descripcion$x"];
				$um = $_POST["um$x"];
				$cantidad = $_POST["cantidad$x"];
				$precio = $_POST["precio$x"];

				if ($precio) {
					$sth = $this->_db->prepare("INSERT INTO cotizaciones_partes (id_cotizacion, no_parte, id_concepto, um, cantidad, precio) VALUES (?, ?, ?, ?, ?, ?)");
					$arregloDatos = array($id, $noParte, $descripcion, $um, $cantidad, $precio);
					if(!$sth->execute($arregloDatos)) throw New Exception();
				}
			}

			header('Location:' . STASIS. '/movimientos/cotizaciones/generar/1');
		} catch (Exception $e) {
			echo $e->getMessage();
			die;
		}
	}

    public function listado() {
		try {
			$datosVista = [];

			$sth = $this->_db->query("
				SELECT c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS empleado, c.vigencia, c.correo AS email, c.telefono1, c.status, c.alfanumerico, c.openpay
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				JOIN empleados e
				ON e.id = c.id_agente
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				switch ($datos['seccion']) {
					case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
					case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
					case 'LOMAS (RGR)': $prefijo = 'SL'; break;
					case 'LOMAS': $prefijo = 'SL'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
					case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
					case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
					case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
					case 'VISTA DEL REY': $prefijo = 'VR'; break;
				}
				$lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

				if ($datos['moneda'] == 1) {
					$moneda = 'MXN';
				} elseif ($datos['moneda'] == 2) {
					$moneda = 'USD';
				}

				switch ($datos['status']) {
					case 0: $status = 'pendientes'; break;
					case 1: $status = 'revisadas'; break;
					case 2: $status = 'aceptadas'; break;
					case 3: $status = 'pagadas'; break;
					case 4: $status = 'rechazadas'; break;
					case -1: $status = 'canceladas'; break;
				}

				$datosVista[$status][] = array(
					'id' => $datos['id'],
					'id_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id_solicitud'], 5, '0', STR_PAD_LEFT),
					'alfanumerico' => $datos['alfanumerico'],
					'propietario' => $datos['nombre'],
					'lote' => $lote,
					'agente' => $datos['empleado'],
					'email' => $datos['email'],
					'openpay' => $datos['openpay'],
					'celular' => preg_replace('/\D/', '', $datos['telefono1']),
					'total' => '$ ' . number_format($datos['total'], 2, '.', ',') . ' ' . $moneda,
					'fecha_creacion' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
					'fecha_vigencia' => Modelos_Fecha::formatearFecha($datos['vigencia']),
				);

				$x++;
				$datosVista['nPendientes'] = $x;
			}

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function visualizar($id, $enviar = null, $uniqueId = null) {
		if (!$uniqueId) {
			$sth = $this->_db->prepare("
				SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones, c.status
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				JOIN empleados e
				ON e.id = c.id_agente
				WHERE c.id = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			if (!$datos) {
				$sth = $this->_db->prepare("
					SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones, c.status
					FROM cotizaciones c
					JOIN propietarios p
					ON p.id = c.id_cliente
					JOIN empleados e
					ON e.id = c.id_agente
					WHERE c.alfanumerico = ?
				");
				$sth->bindParam(1, $id);
				if(!$sth->execute()) throw New Exception();
				$datos = $sth->fetch();
			}
		} else {
			$sth = $this->_db->prepare("
				SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones, c.status
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				JOIN empleados e
				ON e.id = c.id_agente
				WHERE c.alfanumerico = ?
			");
			$sth->bindParam(1, $uniqueId);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();
		}

		if (!$datos) die;

		$status = $datos['status'];
		$id = $datos['id'];

		if ($status == 0) {
			$sth = $this->_db->prepare("UPDATE cotizaciones SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $datos['id']);
			if(!$sth->execute()) throw New Exception();
		}

		switch ($status) {
			case 0: $statusHtml = '<img src="' . STASIS . '/img/s-success.png" height="7" /> Pendiente'; break;
			case 1: $statusHtml = '<img src="' . STASIS . '/img/s-success.png" height="7" /> Pendiente'; break;
			case 2: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Aceptada'; break;
			case 3: $statusHtml = '<img src="' . STASIS . '/img/s-info.png" height="7" /> Pagada'; break;
			case -1: $statusHtml = '<img src="' . STASIS . '/img/s-danger.png" height="7" /> Cancelada'; break;
			case 4: $statusHtml = '<img src="' . STASIS . '/img/s-danger.png" height="7" /> Rechazada'; break;
			// case 9: $statusHtml = '<img src="' . STASIS . '/img/s-warning.png" height="7" /> En Revisión'; break;
		}

		$alfanumerico = $datos['alfanumerico'];

		switch ($datos['seccion']) {
			case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
			case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
			case 'LOMAS (RGR)': $prefijo = 'SL'; break;
			case 'LOMAS': $prefijo = 'SL'; break;
			case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
			case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
			case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
			case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
			case 'VISTA DEL REY': $prefijo = 'VR'; break;
		}
		$lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

		if ($datos['moneda'] == 1) {
			$moneda = 'MXN';
		} elseif ($datos['moneda'] == 2) {
			$moneda = 'USD';
		}

		$folio = $datos['id'];
		$fecha_creacion = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);
		$fecha_vigencia = Modelos_Fecha::formatearFecha($datos['vigencia']);
		
		$agente = $grado . mb_strtoupper($datos['agente'], 'UTF-8');
		$agenteCorreo = $datos['agente_email'];
		$agenteCelular = $datos['agente_celular'];

		$porImpuesto = $datos['por_impuesto'];
		
		$propietario = $datos['nombre'];
		$telefono1 = $datos['telefono1'];
		$telefono2 = $datos['telefono2'];
		$email = strtolower($datos['email']);

		$rfc = $datos['rfc'];
		$vigencia = Modelos_Fecha::formatearFecha($datos['vigencia']);
		$totalLetras = strtoupper(Modelos_Caracteres::num2letras($datos['total'], $moneda));
		$idCliente = $datos['id_cliente'];
		$cliente = $datos['cliente'];

		$observaciones = $datos['observaciones'];
		if ($observaciones) $observacionesTexto = '
			<span style="font-family: \'Roboto Bold\';">OBSERVACIONES:</span> ' . $observaciones . '<br /><br />
			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />
		';
		
		// PDF
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new RTFPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Solicitud');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetPrintHeader(false);
		$pdf->SetMargins(10, 10, 10, 0);
		$pdf->AddPage();

		$subtotal = number_format($datos['subtotal'], 2, '.', ',');
		$impuesto = number_format($datos['impuesto'], 2, '.', ',');
		$total = number_format($datos['total'], 2, '.', ',');
		$observaciones = $datos['observaciones'];
		if ($observaciones) $observacionesTexto = '
			<span style="font-family: \'Roboto Bold\';">OBSERVACIONES:</span> ' . $observaciones . '<br /><br />
			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />
		';

		// Partes
		$sth = $this->_db->prepare("
			SELECT cp.um, cp.cantidad, cp.precio, c.nombre AS descripcion, c.empresa, c.direccion, c.rfc
			FROM cotizaciones_partes cp
			JOIN conceptos c
			ON c.id = cp.id_concepto
			WHERE cp.id_cotizacion = ?
			ORDER BY cp.id ASC
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		
		$htmlPartidas = '';
		$x = 0;
		while ($datos = $sth->fetch()) {
			if ($x % 2 == 0) {
				$background = '#FFF';
			} else {
				$background = '#EAEAEA';
			}

			$empresa = $datos['empresa'];
			$direccion = $datos['direccion'];
			$rfc = $datos['rfc'];

			$htmlPartidas .= '<tr>';
			$htmlPartidas .= '<td style="text-align: center; background-color: '. $background . ';">' . number_format($datos['cantidad'], 0, '', ',') . '</td>';
			$htmlPartidas .= '<td style="text-align: center; background-color: '. $background . ';">' . mb_strtoupper($datos['um'], 'UTF-8') . '</td>';
			$htmlPartidas .= '<td style="text-align: center; background-color: '. $background . ';">' . $datos['descripcion'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center; background-color: '. $background . ';">$ ' . number_format((float)$datos['precio'], 2, '.', ',') . '</td>';
			$htmlPartidas .= '<td style="text-align: center; background-color: '. $background . ';">$ ' . number_format((float)$datos['cantidad']*$datos['precio'], 2, '.', ',') . '</td>';
			$htmlPartidas .= '</tr>';

			$x++;
		}

		// Acpetar pagar cotizacion
		if ($status != -1 && $status != 4 && $status != 3) {
			$aceptarPagarHtml = '
				<table style="text-align: center; font-size: 11px;" cellpadding="6" cellspacing="1">
					<tr>
					    <td style="width: 20%"></td>
						<td style="background-color: #358405; color: #FFF; width: 30%; text-align: center;"><a style="color: #FFF; font-family: \'SanFranciscoBold\';" href="' . STASIS . '/e/p/f/' . $alfanumerico . '">Aceptar y generar pago</a></td>
						<td style="background-color: #E85255; color: #FFF; width: 30%; text-align: center;"><a style="color: #FFF; font-family: \'SanFranciscoBold\';" href="' . STASIS . '/e/p/r/' . $alfanumerico . '">Rechazar cotización</a></td>
					</tr>
				</table>
			';
		}

		$stasis = STASIS;

		$html = <<<EOF
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 250px; color: #444;">
						<img src="$stasis/img/cobroplan.png" height="62" />
					</td>
					<td style="width: 213px; text-align: right; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">COTIZACIÓN A PROPIETARIO</span><br />
						<span style="font-size: 9px;">No. Folio: $id<br />Fecha de Creación: $fecha_creacion<br />Fecha de Vigencia: $fecha_vigencia</span><br />
						<span style="font-size: 9px;">$statusHtml</span>
					</td>
					<td style="width: 75px; text-align: right;">
						<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=https://saevalcas.mx/movimientos/cotizaciones/visualizar/$id&chld=H|0" height="65">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />

			<table style="font-size: 7px; text-align: center;" border="0" cellpadding="4" cellspacing="1">
			    <tr>
					<td style="width: 30%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">Empresa</span>
					</td>

					<td style="width: 15%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">RFC</span>
					</td>

					<td style="width: 55%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">Dirección</span>
					</td>
				</tr>
				<tr>
					<td>
						<span style="font-size: 7px;">$empresa</span>
					</td>

					<td>
						<span style="font-size: 7px;">$rfc</span>
					</td>

					<td>
						<span style="font-size: 7px;">$direccion</span>
					</td>
				</tr>

				<tr>
					<td style="width: 35%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">Propietario</span>
					</td>

					<td style="width: 15%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">Lote</span>
					</td>

					<td style="width: 15%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">Teléfono 1</span>
					</td>

					<td style="width: 15%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">Teléfono 2</span>
					</td>

					<td style="width: 19.5%; background-color: #004B93; color: #FFF;">
						<span style="font-size: 7px; text-align: center; font-family: 'Roboto Bold';">Correo</span>
					</td>
				</tr>
				<tr>
					<td>
						<span style="font-size: 7px;">$propietario</span>
					</td>

					<td>
						<span style="font-size: 7px;">$lote</span>
					</td>

					<td>
						<span style="font-size: 7px;">$telefono1</span>
					</td>

					<td>
						<span style="font-size: 7px;">$telefono2</span>
					</td>

					<td>
						<span style="font-size: 7px;">$email</span>
					</td>
				</tr>
			</table>

			<br /><br />

			<table style="font-size: 7px; text-align: left;" border="0" cellpadding="2" cellspacing="1">
				<tbody>
					<tr>
						<td style="width: 8%; text-align: center; background-color: #55B332; font-family: 'Roboto Bold'; color: #FFF;">Cantidad</td>
						<td style="width: 12%; text-align: center; background-color: #55B332; font-family: 'Roboto Bold'; color: #FFF;">Unidad de<br />Medida</td>
						<td style="width: 58%; text-align: center; background-color: #55B332; font-family: 'Roboto Bold'; color: #FFF;">Descripción</td>
						<td style="width: 11%; text-align: center; background-color: #55B332; font-family: 'Roboto Bold'; color: #FFF;">Precio<br />Unitario</td>
						<td style="width: 11%; text-align: center; background-color: #55B332; font-family: 'Roboto Bold'; color: #FFF;">Total</td>
					</tr>
				</tbody>
				$htmlPartidas
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />

			$observacionesTexto

			<table style="text-align: left; font-size: 9px;" border="0" cellpadding="1" cellspacing="0">
				<tr>
					<td style="width: 69%;">
						<span style="color: #888;">Para dudas y aclaraciones con respecto a esta cotización, contactar a:</span>
					</td>
					<td style="width: 15%; text-align: right;">
						Subtotal $
					</td>
					<td style="width: 16%; text-align: right;">
						$subtotal $moneda
					</td>
				</tr>
				<tr>
					<td><span style="color: #888;"><b>Nombre:</b> $agente</span></td>
					<td style="text-align: right; border-bottom: 1px solid #DADADA;">
						IVA ($porImpuesto %) $
					</td>
					<td style="text-align: right; border-bottom: 1px solid #DADADA;">
						$impuesto $moneda
					</td>
				</tr>
				<tr>
					<td><span style="color: #888;"><b>Correo:</b> $agenteCorreo</span></td>
					<td style="text-align: right;">
						<span style="font-family: 'Roboto Bold';">Total $</span>
					</td>
					<td style="text-align: right;">
						<span style="font-family: 'Roboto Bold';">$total $moneda</span>
					</td>
				</tr>
				<tr>
					<td><span style="color: #888;"><b>Teléfono:</b> $agenteCelular</span></td>
				</tr>
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />

			$aceptarPagarHtml
EOF;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if (!$enviar) {
			$pdf->Output('Cotizacion_' . $folio . '.pdf', 'I');
		} else {
			$nombrePdf = 'Cotizacion_' . $folio . '.pdf';
			$archivo = $pdf->Output(ROOT_DIR . "/data/tmp/$nombrePdf", 'F');
			return $nombrePdf;
		}
	}

	private function AltiriaSMS($sDestination, $sMessage, $sSenderId, $debug) {
		if($debug)        
		echo 'Enter AltiriaSMS <br/>';

		$baseUrl = 'https://www.altiria.net:8443/apirest/ws';
		$ch = curl_init($baseUrl.'/sendSms');
		$credentials = array(
		    'apiKey'    => 'DbJ7m2zrx3',
		    'apiSecret' => '5db29d4y9m'
		);
        $destinations = explode(',', $sDestination);
        $jsonMessage = array(
		    'msg' => substr($sMessage,0,160),
		    'senderId' => $sSenderId 
		);
		$jsonData = array(
		    'credentials' => $credentials, 
		    'destination' => $destinations,
		    'message'     => $jsonMessage
		);
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
		$response = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($debug) {   
			if ($statusCode != 200) { 
				echo 'ERROR GENERAL: '.$statusCode;
				echo $response;
			} else {
				echo 'Código de estado HTTP: '.$statusCode.'<br/>';
				$json_parsed = json_decode($response);
				$status = $json_parsed->status;
				echo 'Código de estado Altiria: '.$status.'<br/>';
				if ($status != '000')
					echo 'Error: '.$response.'<br/>';
				else {
					echo 'Cuerpo de la respuesta: <br/>';
					echo 'destails[0][destination]: '.$json_parsed->details[0]->destination.'<br/>';
					echo 'destails[0][status]: '.$json_parsed->details[0]->status.'<br/>';
					echo 'destails[1][destination]: '.$json_parsed->details[1]->destination.'<br/>';
					echo 'destails[1][status]: '.$json_parsed->details[1]->status.'<br/>';
				}
			}
		}
		
		if(curl_errno($ch)) throw new Exception(curl_error($ch));

		return $response;
	}

	public function enviar($id) {
		try {
			$sth = $this->_db->prepare("
				SELECT p.email, p.telefono1, c.alfanumerico
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				WHERE c.id = ?
				LIMIT 1
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$alfanumerico = $datos['alfanumerico'];
			$email = $datos['email'];
			$celular = preg_replace('/\D/', '', $datos['telefono1']);

			// Enviar SMS
			$celularLada = '52' . $celular;
			$this->AltiriaSMS($celularLada,"Rancho Tecate: Se envia liga PDF de la cotizacion solicitada con folio $id: https://saevalcas.mx/m/c/v/$alfanumerico", '', false);

			// Enviar Correo
			$correo = Modelos_Contenedor::crearModelo('Correo');
			$nombrePdf = $this->visualizar($id, 1);
			$correo->cotizacionPropietario($id, $nombrePdf, $email);

	  		header('Location: ' . STASIS . '/movimientos/cotizaciones/reporte/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function datosPago($id) {
		try {
			$datosArray = [];

			$sth = $this->_db->prepare("
				SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones, c.status, co.nombre AS concepto, cp.um, cp.cantidad, cp.precio, c.openpay, pm.nombre AS um, pm.abreviacion AS um_abreviacion, co.clave_prodserv, co.id AS id_concepto
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				JOIN cotizaciones_partes cp
				ON cp.id_cotizacion = c.id
				JOIN conceptos co
				ON co.id = cp.id_concepto
				JOIN empleados e
				ON e.id = c.id_agente
				LEFT JOIN partes_medidas pm
				ON pm.id = co.um
				WHERE c.alfanumerico = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			if ($datos['status'] == 1) {
				$sth = $this->_db->prepare("UPDATE cotizaciones SET status = 2 WHERE id = ?");
				$sth->bindParam(1, $datos['id']);
				if(!$sth->execute()) throw New Exception();
			}

			$alfanumerico = $datos['alfanumerico'];

			switch ($datos['seccion']) {
				case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
				case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
				case 'LOMAS (RGR)': $prefijo = 'SL'; break;
				case 'LOMAS': $prefijo = 'SL'; break;
				case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
				case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
				case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
				case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
				case 'VISTA DEL REY': $prefijo = 'VR'; break;
			}
			$datosArray['lote'] = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

			if ($datos['moneda'] == 1) {
				$datosArray['moneda'] = 'MXN';
				$datosArray['monedaRaw'] = 1;
			} elseif ($datos['moneda'] == 2) {
				$datosArray['moneda'] = 'USD';
				$datosArray['monedaRaw'] = 2;
			}

			$datosArray['conceptoConcepto'] = $datos['concepto'];
			$datosArray['um'] = mb_strtoupper($datos['um']);
			$datosArray['cantidad'] = $datos['cantidad'];
			$datosArray['precio'] = '$ ' . number_format($datos['precio'], 2, '.', ',');
			$datosArray['totalConcepto'] = '$ ' . number_format($datos['precio']*$datos['cantidad'], 2, '.', ',');

			$datosArray['folio'] = $datos['id'];
			$datosArray['alfanumerico'] = $datos['alfanumerico'];
			$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);
			$datosArray['fecha_vigencia'] = Modelos_Fecha::formatearFecha($datos['vigencia']);
			$datosArray['agente'] = $grado . mb_strtoupper($datos['agente'], 'UTF-8');
			$datosArray['agenteCorreo'] = $datos['agente_email'];
			$datosArray['agenteCelular'] = $datos['agente_celular'];
			$datosArray['porImpuesto'] = $datos['por_impuesto']*100;
			$datosArray['porImpuestoRaw'] = number_format($datos['por_impuesto']/100, 2, '.', ',');
			$datosArray['propietario'] = $datos['nombre'];
			$datosArray['telefono1'] = $datos['telefono1'];
			$datosArray['telefono2'] = $datos['telefono2'];
			$datosArray['email'] = strtolower($datos['email']);
			$datosArray['rfc'] = $datos['rfc'];
			$datosArray['vigencia'] = Modelos_Fecha::formatearFecha($datos['vigencia']);
			$datosArray['totalLetras'] = strtoupper(Modelos_Caracteres::num2letras($datos['total'], $moneda));
			$datosArray['idCliente'] = $datos['id_cliente'];
			$datosArray['cliente'] = $datos['cliente'];
			$datosArray['subtotal'] = number_format($datos['subtotal'], 2, '.', ',');
			$datosArray['impuesto'] = number_format($datos['impuesto'], 2, '.', ',');
			$datosArray['total'] = number_format($datos['total'], 2, '.', ',');
			$datosArray['observaciones'] = $datos['observaciones'];
			$datosArray['concepto'] = 'PAGO DE COTIZACIÓN FOLIO #' . $datos['id'];

			$datosArray['um'] = $datos['um'];
			$datosArray['um_abreviacion'] = $datos['um_abreviacion'];
			$datosArray['clave_prodserv'] = $datos['clave_prodserv'];
			$datosArray['id_concepto'] = $datos['id_concepto'];

			if (!empty($datos['openpay'])) {
				$ch = curl_init('https://api.openpay.mx/v1/m7aci0xq2pyewsqdhy9r/charges/' . $datos['openpay']);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				    "Accept: application/json",
				    "Content-Type: application/json",
				    "Authorization: Basic c2tfM2IzZGVkNGNjZjU4NGVhYjliNGRkOTUzNmI0ZGI0ZjM6"
				));
				$response = curl_exec($ch);
				$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				if(curl_errno($ch)) throw new Exception(curl_error($ch));

				$jsonReponse = json_decode($response);

				$fechaOperacion = new DateTime($jsonReponse->operation_date);
				
				$datosArray['openpay']['id'] = $datos['openpay'];
				$datosArray['openpay']['brand'] = $jsonReponse->card->brand;
				$datosArray['openpay']['card_number'] = $jsonReponse->card->card_number;
				$datosArray['openpay']['holder_name'] = $jsonReponse->card->holder_name;
				$datosArray['openpay']['bank_name'] = $jsonReponse->card->bank_name;
				$datosArray['openpay']['operation_date'] = $fechaOperacion->format('d/m/Y H:i:s');
			}

			return $datosArray;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function datosPagoCorreo($id) {
		try {
			$datosArray = [];

			$sth = $this->_db->prepare("
				SELECT c.alfanumerico, c.id, p.nombre, p.seccion, p.manzana, p.lote, c.fecha_creacion, c.vigencia, c.total, c.subtotal, c.impuesto, c.moneda, CONCAT(e.nombre, ' ', e.apellidos) AS agente, e.celular AS agente_celular, e.email AS agente_email, c.vigencia, c.correo AS email, c.telefono1, c.telefono2, c.por_impuesto, c.observaciones, c.status, co.nombre AS concepto, cp.um, cp.cantidad, cp.precio, c.openpay
				FROM cotizaciones c
				JOIN propietarios p
				ON p.id = c.id_cliente
				JOIN cotizaciones_partes cp
				ON cp.id_cotizacion = c.id
				JOIN conceptos co
				ON co.id = cp.id_concepto
				JOIN empleados e
				ON e.id = c.id_agente
				WHERE c.alfanumerico = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$alfanumerico = $datos['alfanumerico'];

			switch ($datos['seccion']) {
				case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
				case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
				case 'LOMAS (RGR)': $prefijo = 'SL'; break;
				case 'LOMAS': $prefijo = 'SL'; break;
				case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
				case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
				case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
				case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
				case 'VISTA DEL REY': $prefijo = 'VR'; break;
			}
			$datosArray['lote'] = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);

			if ($datos['moneda'] == 1) {
				$datosArray['moneda'] = 'MXN';
			} elseif ($datos['moneda'] == 2) {
				$datosArray['moneda'] = 'USD';
			}

			$datosArray['conceptoConcepto'] = $datos['concepto'];
			$datosArray['um'] = mb_strtoupper($datos['um']);
			$datosArray['cantidad'] = $datos['cantidad'];
			$datosArray['precio'] = '$ ' . number_format($datos['precio'], 2, '.', ',');
			$datosArray['totalConcepto'] = '$ ' . number_format($datos['precio']*$datos['cantidad'], 2, '.', ',');

			$datosArray['folio'] = $datos['id'];
			$datosArray['alfanumerico'] = $datos['alfanumerico'];
			$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);
			$datosArray['fecha_vigencia'] = Modelos_Fecha::formatearFecha($datos['vigencia']);
			$datosArray['agente'] = $grado . mb_strtoupper($datos['agente'], 'UTF-8');
			$datosArray['agenteCorreo'] = $datos['agente_email'];
			$datosArray['agenteCelular'] = $datos['agente_celular'];
			$datosArray['porImpuesto'] = $datos['por_impuesto']*100;
			$datosArray['propietario'] = $datos['nombre'];
			$datosArray['telefono1'] = $datos['telefono1'];
			$datosArray['telefono2'] = $datos['telefono2'];
			$datosArray['email'] = strtolower($datos['email']);
			$datosArray['rfc'] = $datos['rfc'];
			$datosArray['vigencia'] = Modelos_Fecha::formatearFecha($datos['vigencia']);
			$datosArray['totalLetras'] = strtoupper(Modelos_Caracteres::num2letras($datos['total'], $moneda));
			$datosArray['idCliente'] = $datos['id_cliente'];
			$datosArray['cliente'] = $datos['cliente'];
			$datosArray['subtotal'] = number_format($datos['subtotal'], 2, '.', ',');
			$datosArray['impuesto'] = number_format($datos['impuesto'], 2, '.', ',');
			$datosArray['total'] = number_format($datos['total'], 2, '.', ',');
			$datosArray['observaciones'] = $datos['observaciones'];
			$datosArray['concepto'] = 'PAGO DE COTIZACIÓN FOLIO #' . $datos['id'];

			if (!empty($datos['openpay'])) {
				$ch = curl_init('https://api.openpay.mx/v1/m7aci0xq2pyewsqdhy9r/charges/' . $datos['openpay']);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				    "Accept: application/json",
				    "Content-Type: application/json",
				    "Authorization: Basic c2tfM2IzZGVkNGNjZjU4NGVhYjliNGRkOTUzNmI0ZGI0ZjM6"
				));
				$response = curl_exec($ch);
				$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				if(curl_errno($ch)) throw new Exception(curl_error($ch));

				$jsonReponse = json_decode($response);

				$fechaOperacion = new DateTime($jsonReponse->operation_date);
				
				$datosArray['openpay']['id'] = $datos['openpay'];
				$datosArray['openpay']['brand'] = $jsonReponse->card->brand;
				$datosArray['openpay']['card_number'] = $jsonReponse->card->card_number;
				$datosArray['openpay']['holder_name'] = $jsonReponse->card->holder_name;
				$datosArray['openpay']['bank_name'] = $jsonReponse->card->bank_name;
				$datosArray['openpay']['operation_date'] = $fechaOperacion->format('d/m/Y H:i:s');
			}

			// Enviar Correo
			$correo = Modelos_Contenedor::crearModelo('Correo');
			$correo->cotizacionPago($datos['id']);

			return $datosArray;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cancelar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE cotizaciones SET status = -1, fecha_cancelacion = NOW() WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/cotizaciones/reporte/2');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function rechazar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE cotizaciones SET status = 4, fecha_cancelacion = NOW() WHERE alfanumerico = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function aceptar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE cotizaciones SET status = 2, fecha_aceptacion = NOW() WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/cotizaciones/reporte/3');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE cotizaciones SET status = 2 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/cotizaciones/reporte/4');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificarGuardar() {
		try {
			$id = $_POST['id'];

			// Abonos
			if ($_POST["cpc_tipopago"] == 2) {
				// $sth = $this->_db->prepare("UPDATE facturas SET abonos = 1 WHERE id = ?");
				// $sth->bindParam(1, $id);
				// if(!$sth->execute()) throw New Exception();

				// $sth = $this->_db->prepare("DELETE FROM facturas_abonos WHERE id_factura = ?");
				// $sth->bindParam(1, $id);
				// if(!$sth->execute()) throw New Exception();

				// $ultimaFechaPago = '';

				// for($x=1; $x<=5; $x++) {
				// 	if (!empty($_POST["fecha_pago$x"])) {
				// 		$fechaPago = DateTime::createFromFormat('d/m/Y', $_POST["fecha_pago$x"]);
				// 		$fechaPago = $fechaPago->format('Y-m-d');
				// 	} else {
				// 		$fechaPago = '';
				// 	}


				// 	$no_abono = $_POST["no_abono$x"];
				// 	$metodo_pago = $_POST["metodo_pago$x"];
				// 	$importe_pagado = $_POST["importe_pagado$x"];
				// 	$banco = $_POST["banco$x"];
				// 	$no_cheque = $_POST["no_cheque$x"];
				// 	$no_autorizacion = $_POST["no_autorizacion$x"];

				// 	if ($fechaPago != '') $ultimaFechaPago = $fechaPago;

				// 	$dataArray = array($no_abono, $id, $metodo_pago, $fechaPago, $importe_pagado, $banco, $no_cheque, $no_autorizacion);
				// 	$sth = $this->_db->prepare("INSERT INTO facturas_abonos (no_abono, id_factura, metodo_pago, fecha_pago, importe_pagado, banco, no_cheque, no_autorizacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
				// 	if(!$sth->execute($dataArray)) throw New Exception();
				// }

				// $totalAbonado = $_POST['total_abonado'];
				// $totalFactura = $_POST['total'];

				// if ($totalAbonado >= $totalFactura) {
				// 	$sth = $this->_db->prepare("UPDATE facturas SET cobrado = 1, fecha_pago = ?, importe_pagado = ? WHERE id = ?");
				// 	$sth->bindParam(1, $ultimaFechaPago);
				// 	$sth->bindParam(2, $totalFactura);
				// 	$sth->bindParam(3, $id);
				// 	if(!$sth->execute()) throw New Exception();

				// 	$sth = $this->_db->prepare("INSERT INTO cpc_pagos (id_factura, fecha) VALUES (?, NOW())");
				// 	$sth->bindParam(1, $id);
				// 	if(!$sth->execute()) throw New Exception();
				// } else {
				// 	$sth = $this->_db->prepare("UPDATE facturas SET cobrado = 0, fecha_pago = NULL, importe_pagado = ? WHERE id = ?");
				// 	$sth->bindParam(1, $totalAbonado);
				// 	$sth->bindParam(2, $id);
				// 	if(!$sth->execute()) throw New Exception();
				// }
			// Importe Total
			} else {
				$totalFactura = $_POST['total'];
				$importePagado = $_POST['importe_pagado'];

				if ($importePagado >= $totalFactura) {
					$cobrado = 1;

					$sth = $this->_db->prepare("INSERT INTO cpc_pagos (id_factura, fecha) VALUES (?, NOW())");
					$sth->bindParam(1, $id);
					if(!$sth->execute()) throw New Exception();
				} else {
					$cobrado = 0;
				}

				if (!empty($_POST['fecha_pago'])) {
					$fechaPago = DateTime::createFromFormat('d/m/Y', $_POST['fecha_pago']);
					$fechaPago = $fechaPago->format('Y-m-d');
				}

				$sth = $this->_db->prepare("UPDATE facturas SET fecha_pago = ?, importe_pagado = ?, banco = ?, num_aut = ?, num_cheque = ?, cpc_metodopago = ?, cobrado = ? WHERE id = ?");
				$sth->bindParam(1, $fechaPago);
				$sth->bindParam(2, $_POST["importe_pagado"]);
				$sth->bindParam(3, $_POST["banco"]);
				$sth->bindParam(4, $_POST["num_aut"]);
				$sth->bindParam(5, $_POST["num_cheque"]);
				$sth->bindParam(6, $_POST["cpc_metodopago"]);
				$sth->bindParam(7, $cobrado);
				$sth->bindParam(8, $id);
				if(!$sth->execute()) throw New Exception();
			}

			$sth = $this->_db->prepare("SELECT id_cliente FROM facturas WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$idCliente = $sth->fetchColumn();

			header('Location:' . STASIS. "/finanzas/cuentas_por_cobrar/facturas/$idCliente/1");
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
			die;
		}
	}

	public function nuevoCliente() {
		try {
			// Nuevo cliente
			$idCotizacion = mb_strtoupper($_POST['idCotizacion']);

			$razon_social = mb_strtoupper($_POST['razon_social']);
			$rfc = mb_strtoupper($_POST['rfc']);
			$uso_cfdi = mb_strtoupper($_POST['uso_cfdi']);
			$regimen = mb_strtoupper($_POST['regimen']);
			$nombre_calle = mb_strtoupper($_POST['nombre_calle']);
			$num_exterior = mb_strtoupper($_POST['num_exterior']);
			$num_interior = mb_strtoupper($_POST['num_interior']);
			$colonia = mb_strtoupper($_POST['colonia']);
			$cp = mb_strtoupper($_POST['cp']);
			$ciudad = mb_strtoupper($_POST['ciudad']);
			$estado = mb_strtoupper($_POST['estado']);
			$pais = mb_strtoupper($_POST['pais']);

			$arregloDatos = array($razon_social, $rfc, $regimen);
			$sth = $this->_db->prepare("INSERT INTO clientes (razon_social, rfc, regimen, status) VALUES (?, ?, ?, 1)");
			if(!$sth->execute($arregloDatos)) throw New Exception();
			$idCliente = $this->_db->lastInsertId();

			$arregloDatos = array($idCliente, 1, 0, $nombre_calle, $num_exterior, $num_interior, $colonia, $cp, $pais, $estado, $ciudad);
			$sth = $this->_db->prepare("INSERT INTO direcciones (id_catalogo, id_tipo, tipo_direccion, nombre_calle, num_exterior, num_interior, colonia, cp, pais, estado, ciudad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			if(!$sth->execute($arregloDatos)) throw New Exception();

			// Factura
			$prefijoTabla = '';

			$factPropietario = $_POST['factPropietario'];
			$factCorreo = $_POST['factCorreo'];
			$factLote = $_POST['factLote'];
			$factConcepto = $_POST['factConcepto'];
			$factImporte = $_POST['factImporte'];
			$factObservaciones = $_POST['factObservaciones'];
			$factMoneda = $_POST['factMoneda'];
			$factSubtotal = $_POST['factSubtotal'];
			$factImpuesto = $_POST['factImpuesto'];
			$factTotal = $_POST['factTotal'];
			$factPorimpuesto = $_POST['factPorimpuesto'];

			$idIntegracion = 1;
			$idAgente = 1;
			$idCliente = $idCliente;
			$formaPago = 'PAGO EN UNA SOLA EXHIBICION';
			$metodoPago = '04';
			$moneda = $factMoneda;
			$correo = $factCorreo;

			$subtotal = $factSubtotal;
			$porImpuesto = $factPorimpuesto;
			$impuesto = $factImpuesto;
			$total = $factTotal;
			$observaciones = mb_strtoupper($factObservaciones, 'UTF-8');
			$tipoRelacionCfdi = 1;
			$tipoCambio = '1.00';

			$sth = $this->_db->prepare("DELETE FROM facturas WHERE id = ?");
			$sth->bindParam(1, $idCotizacion);
			if(!$sth->execute()) throw New Exception();

			$sth = $this->_db->prepare("DELETE FROM facturas_partes WHERE id_factura = ?");
			$sth->bindParam(1, $idCotizacion);
			if(!$sth->execute()) throw New Exception();

			$arregloDatos = array($idCotizacion, $idAgente, $formaPago, $moneda, $metodoPago, $correo, $subtotal, $impuesto, $total, $observaciones, $idCliente, $tipoRelacionCfdi, $uso_cfdi, $porImpuesto, $tipoCambio);
			$sth = $this->_db->prepare("INSERT INTO facturas (id, id_agente, forma_pago, moneda, metodo_pago, email, subtotal, impuesto, total, observaciones, id_cliente, tipo_relacion, uso_cfdi, por_impuesto, tipo_cambio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			if(!$sth->execute($arregloDatos)) throw New Exception();
			$idFactura = $this->_db->lastInsertId();

			$factIdConcepto = $_POST['factIdConcepto'];
			$factUm = $_POST['factUm'];
			$factUmAbreviacion = $_POST['factUmAbreviacion'];
			$factClaveProdserv = $_POST['factClaveProdserv'];
			
			$idParte = $factIdConcepto;
			$claveProdServ = $factClaveProdserv;
			$claveUnidad = $factUmAbreviacion;
			$um = $factUm;
			$cantidad = 1;
			$precio = $subtotal;

			$sth = $this->_db->prepare("INSERT INTO facturas_partes (id_factura, id_parte, cantidad, precio, clave_prodserv, clave_unidad) VALUES (?, ?, ?, ?, ?, ?)");
			$arregloDatos = array($idFactura, $idParte, $cantidad, $precio, $claveProdServ, $claveUnidad);
			if(!$sth->execute($arregloDatos)) throw New Exception();

			echo $idFactura;
			die;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
			die;
		}
	}

	public function visualizarfactura($id) {
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		// Datos de Cotizacion
		$sth = $this->_db->prepare("
			SELECT f.id, f.forma_pago, f.moneda, f.metodo_pago, f.email, f.subtotal, f.impuesto, f.total, c.id AS id_cliente, c.razon_social AS cliente, c.rfc, f.fecha, f.tipo_relacion, f.uso_cfdi, f.observaciones, f.por_impuesto, f.tipo_cambio, f.cancelado, f.emisor
			FROM facturas f
			LEFT JOIN clientes c
			ON c.id = f.id_cliente
			WHERE f.id = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		switch ($datos['emisor']) {
			case 1:
			$empresa = 'MANTENIMIENTO Y ADMINISTRACION PROFESIONAL';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: MAP941111HE2<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - 1101 B<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 634 2808
			</span>';
			$logo = '<br /><br /><img src="' . STASIS . '/img/mapsa.png" width="140" alt="">';
			break;

			case 2:
			$empresa = 'LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: OCT830325RRA<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - 1101 H<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 634 2808
			</span>';
			$logo = '<br /><div style="font-size: 30px; font-weight: bold; color: #1E1E2D;">Las Olas</div>';
			break;

			case 3:
			$empresa = 'COBROPLAN';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: COB191009LT8<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - 1101 A<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 680 6052
			</span>';
			$logo = '<br /><img src="' . STASIS . '/img/cobroplan.png" width="140" alt="">';
			break;

			case 4:
			$empresa = 'INMOBILIARIA RANCHO TECATE S DE RL DE CV';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: IRT1910093T9<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - PISO 11 INT G <br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 680 6052
			</span>';
			$logo = '<br /><img src="' . STASIS . '/img/rtecate.png" width="140" alt="">';
			break;

			case 5:
			$empresa = 'RGR-GLOBAL-BUSINESS';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: RGR1904125Z7<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - LOCAL 2<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 634 2808
			</span>';
			$logo = '<div style="font-size: 40px; font-weight: bold; color: #1E1E2D;">RGR</div>';
			break;

			case 6:
			$empresa = 'ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: AUR091109QC4<br />
				RÉGIMEN FISCAL: PERSONAS MORALES CON FINES NO LUCRATIVOS
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - 1101 J<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 680 6052
			</span>';
			$logo = '<div style="font-size: 14px; text-align: center; font-weight: bold; color: #1E1E2D;">&nbsp;&nbsp;Asociación de Usuarios de Rancho Tecate</div>';
			break;

			case 7:
			$empresa = 'EL ENCANTO RESORT CLUB';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: ERC121129CV4<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - 1101 F<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 680 6052
			</span>';
			$logo = '<br /><img src="' . STASIS . '/img/encanto.png" width="90" alt="">';
			break;

			case 8:
			$empresa = 'ENCINO DE PIEDRA DE BC';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: EPB190430N5A<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - LOCAL 2 INT B<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 628 3006
			</span>';
			$logo = '<br /><img src="' . STASIS . '/img/encino.png" width="90" alt="">';
			break;
		}

		$email = $datos['email'];
		$forma_pago = $datos['forma_pago'];
		$porImpuesto = $datos['por_impuesto']*100;
		$tipo_cambio = $datos['tipo_cambio'];

		switch ($datos['forma_pago']) {
			case 'PAGO EN UNA SOLA EXHIBICION': $formaPago = 'PUE - PAGO EN UNA SOLA EXHIBICION'; break;
            case 'PAGO EN PARCIALIDADES O DIFERIDO': $formaPago = 'PPD - PAGO EN PARCIALIDADES O DIFERIDO'; break;
		}

		switch ($datos['metodo_pago']) {
			case '01': $metodoPago = '01 - EFECTIVO'; break;
            case '02': $metodoPago = '02 - CHEQUE NOMINATIVO'; break;
            case '03': $metodoPago = '03 - TRANSFERENCIA ELECTRÓNICA DE FONDOS'; break;
            case '04': $metodoPago = '04 - TARJETA DE CRÉDITO'; break;
            case '05': $metodoPago = '05 - MONEDERO ELECTRÓNICO'; break;
            case '06': $metodoPago = '06 - DINERO ELECTRÓNICO'; break;
            case '08': $metodoPago = '08 - VALES DE DESPENSA'; break;
            case '12': $metodoPago = '12 - DACIÓN EN PAGO'; break;
            case '13': $metodoPago = '13 - PAGO POR SUBROGACIÓN'; break;
            case '14': $metodoPago = '14 - PAGO POR CONSIGNACIÓN'; break;
            case '15': $metodoPago = '15 - CONDONACIÓN'; break;
            case '17': $metodoPago = '17 - COMPENSACIÓN'; break;
            case '23': $metodoPago = '23 - NOVACIÓN'; break;
            case '24': $metodoPago = '24 - CONFUSIÓN'; break;
            case '25': $metodoPago = '25 - REMISIÓN DE DEUDA'; break;
            case '26': $metodoPago = '26 - PRESCRIPCIÓN O CADUCIDAD'; break;
            case '27': $metodoPago = '27 - A SATISFACCIÓN DEL ACREEDOR'; break;
            case '28': $metodoPago = '28 - TARJETA DE DÉBITO'; break;
            case '29': $metodoPago = '29 - TARJETA DE SERVICIOS'; break;
            case '30': $metodoPago = '30 - APLICACIÓN DE ANTICIPOS'; break;
            case '99': $metodoPago = '99 - POR DEFINIR'; break;
		}
		switch ($datos['uso_cfdi']) {
			case 'G01': $usoCfdi = 'G01 - ADQUISICIÓN DE MERCANCIAS'; break;
			case 'G02': $usoCfdi = 'G02 - DEVOLUCIONES, DESCUENTOS O BONIFICACIONES'; break;
			case 'G03': $usoCfdi = 'G03 - GASTOS EN GENERAL'; break;
			case 'I01': $usoCfdi = 'I01 - CONSTRUCCIONES'; break;
			case 'I02': $usoCfdi = 'I02 - MOBILIARIO Y EQUIPO DE OFICINA POR INVERSIONES'; break;
			case 'I03': $usoCfdi = 'I03 - EQUIPO DE TRANSPORTE'; break;
			case 'I04': $usoCfdi = 'I04 - EQUIPO DE CÓMPUTO Y ACCESORIOS'; break;
			case 'I05': $usoCfdi = 'I05 - DADOS, TROQUELES, MOLDES, MATRICES Y HERRAMENTAL'; break;
			case 'I06': $usoCfdi = 'I06 - COMUNICACIONES TELEFÓNICAS'; break;
			case 'I07': $usoCfdi = 'I07 - COMUNICACIONES SATELITALES'; break;
			case 'I08': $usoCfdi = 'I08 - OTRA MAQUINARIA Y EQUIPO'; break;
			case 'D01': $usoCfdi = 'D01 - HONORARIOS MÉDICOS, DENTALES Y GASTOS HOSPITALARIOS'; break;
			case 'D02': $usoCfdi = 'D02 - GASTOS MÉDICOS POR INCAPACIDAD O DISCAPACIDAD'; break;
			case 'D03': $usoCfdi = 'D03 - GASTOS FUNERALES'; break;
			case 'D04': $usoCfdi = 'D04 - DONATIVOS'; break;
			case 'D05': $usoCfdi = 'D05 - INTERESES REALES EFECTIVAMENTE PAGADOS POR CRÉDITOS HIPOTECARIOS (CASA HABITACIÓN)'; break;
			case 'D06': $usoCfdi = 'D06 - APORTACIONES VOLUNTARIAS AL SAR'; break;
			case 'D07': $usoCfdi = 'D07 - PRIMAS POR SEGUROS DE GASTOS MÉDICOS'; break;
			case 'D08': $usoCfdi = 'D08 - GASTOS DE TRANSPORTACIÓN ESCOLAR OBLIGATORIA'; break;
			case 'D09': $usoCfdi = 'D09 - DEPÓSITOS EN CUENTAS PARA EL AHORRO, PRIMAS QUE TENGAN COMO BASE PLANES DE PENSIONES'; break;
			case 'D10': $usoCfdi = 'D10 - PAGOS POR SERVICIOS EDUCATIVOS (COLEGIATURAS)'; break;
			case 'P01': $usoCfdi = 'P01 - POR DEFINIR'; break;
		}


		$folio = $datos['id'];
		$fechaCreacion = $datos['fecha'];
		$rfc = $datos['rfc'];
		$moneda = $datos['moneda'];

		if ($moneda == 1) {
			$moneda = 'MXN';
			$monedaFormat = 'PESOS';
		} else {
			$moneda = 'USD';
			$monedaFormat = 'DÓLARES';
		}

		$numeroEntero = floor($datos['total']);
		$numeroFraccion = number_format($datos['total'] - $numeroEntero, 2, '.', '');
		$numeroSeparador = list($whole, $decimal) = explode('.', $numeroFraccion);
		$totalLetras = strtoupper(Modelos_Caracteres::num2letras(number_format($datos['total'], 2, '.', ''), 'pesos')) . ' ' . $monedaFormat . ' ' . $numeroSeparador[1] . '/100';

		$idCliente = $datos['id_cliente'];
		$cliente = $datos['cliente'];

		$Tsubtotal = number_format((float)$datos['subtotal'], 2, '.', ',');
		$Timpuesto = number_format((float)$datos['impuesto'], 2, '.', ',');
		$Ttotal = number_format((float)$datos['total'], 2, '.', ',');

		$texto = "Gracias, le atendió <strong>ALBERTO CASTRO</strong> estoy a sus ordenes para cualquier aclaración en el telefono 664 123 4567 y email: albert@dualstudio.com.mx";
		
		require_once(APP . 'plugins/tcpdf/tcpdf.php');

		$pdf = new RTFPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Cobroplan');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetPrintHeader(false);
		$pdf->SetMargins(10, 10, 10, 0);
		$pdf->AddPage();

		if (!empty($datos['observaciones'])) {
		 	$observaciones = $datos['observaciones'];
		} else {
		 	$observaciones = '';
		}

		// Direccion del Cliente
		$sth = $this->_db->prepare("SELECT nombre_calle, num_exterior, num_interior, colonia, cp, telefono1, email, pais, estado, ciudad FROM direcciones WHERE id_catalogo = ? AND id_tipo = 1");
		$sth->bindParam(1, $idCliente);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$nombreCalle = $datos['nombre_calle'];
		$numExterior = $datos['num_exterior'];
		$numInterior = $datos['num_interior'];
		$colonia = $datos['colonia'];
		$cp = $datos['cp'];
		$pais = $datos['pais'];
		$estado = $datos['estado'];
		$ciudad = $datos['ciudad'];

		$subtotal = 0;

		$sth2 = $this->_db->prepare("
			SELECT p.nombre AS descripcion, cp.cantidad, p.id AS codigo, cp.precio, cp.clave_prodserv, cp.clave_unidad
			FROM facturas_partes cp
			JOIN facturas f
			ON f.id = cp.id_factura
			JOIN conceptos p
			ON p.id = cp.id_parte
			WHERE cp.id_factura = ? ORDER BY cp.id ASC
		");
		$sth2->bindParam(1, $id);
		if(!$sth2->execute()) throw New Exception();
		
		$htmlPartidas = '';
		while ($datos = $sth2->fetch()) {
			switch ($datos['clave_unidad']) {
				case 'H2': $um = 'MEDIO LITRO'; break;
				case 'H87': $um = 'PIEZA'; break;
				case 'LTR': $um = 'LITRO'; break;
				case 'KGM': $um = 'KILOGRAMO'; break;
				case 'GL': $um = 'GALÓN'; break;
				case 'GH': $um = 'MEDIO GALÓN'; break;
				case 'XBJ': $um = 'CUBETA'; break;
				case 'E48': $um = 'UNIDAD DE SERVICIO'; break;
				case 'H87': $um = 'PIEZA'; break;
			}

			$htmlPartidas .= '<tr>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['codigo'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['clave_prodserv'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['descripcion'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['cantidad'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $um . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['clave_unidad'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">$ ' . number_format((float)$datos['precio'], 2, '.', ',') . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">$ ' . number_format((float)$datos['cantidad']*$datos['precio'], 2, '.', ',') . '</td>';
			$htmlPartidas .= '</tr>';
			$subtotal += number_format((float)$datos['cantidad']*$datos['precio'], 2, '.', '');
		}

		$clienteDireccion = "CALLE: $nombreCalle | COLONIA: $colonia | NO.EXT: $numExterior | NO.INT: $numInterior<br />CIUDAD: $ciudad | ESTADO: $estado | PAÍS: $pais | C.P: $cp";

		// Extraccion del xml timbrado
		$file = file_get_contents(ROOT_DIR . "data/xml/" . $id . "_timbrado.xml");
		$file = json_decode($file);

		$cadenaOriginalSAT = $file->data->cadenaOriginalSAT;
		$noCertificadoSAT = $file->data->noCertificadoSAT;
		$noCertificadoCFDI = $file->data->noCertificadoCFDI;
		$uuid = $file->data->uuid;
		$selloSAT = $file->data->selloSAT;
		$selloCFDI = $file->data->selloCFDI;
		$fechaTimbrado = $file->data->fechaTimbrado;
		$qrCode = $file->data->qrCode;
		$cfdi = $file->data->cfdi;

		$stasis = STASIS;

		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/SanFranciscoBold.ttf', 'TrueTypeUnicode', '', 96);
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/SanFrancisco.ttf', 'TrueTypeUnicode', '', 96);
		$html = <<<EOF
		<html>
		<head>
		<style type="text/css">
		body {
			font-family: "SanFrancisco";
		}
		.titulo {
			font-size: 10px;
			font-family: SanFranciscoBold;
		}
		.sinBorde {
			border-collapse: collapse;
			border: none;
		}
		.sinBorde tr td {
			font-size: 8px;
			border: none;
		}
		#partidas {
			border-collapse: collapse;
			font-size: 6px;
		}
		#partidas tr td {
			font-size: 6px;
		}
		</style>
		</head>
		<body>

		<table>
		<tbody>
			<tr>
				<td style="width: 150px; text-align: center;">
					$logo
				</td>
				<td style="width: 384px;">
					<table style="text-align: left;" cellpadding="3" cellspacing="3" border="0" style="font-size: 9px;">
						<tr>
							<td colspan="4" style="background-color: #444; color: #FFF; text-align: right; height: 17px;">
								<span style="font-family: 'SanFranciscoBold'; font-size: 13px;">Factura Electrónica (CFDI) v4.0</span>
							</td>
						</tr>
						<tr>
							<td style="font-family: 'SanFranciscoBold'; width: 48px;">Serie:</td>
							<td style="width: 67px;">$folio</td>

							<td style="font-family: 'SanFranciscoBold'; width: 70px; text-align: right;">Folio Fiscal:</td>
							<td style="width: 200px;">$uuid</td>
						</tr>
						<tr>
							<td style="font-family: 'SanFranciscoBold'; width: 48px;">Expedido:</td>
							<td>22205</td>
							
							<td style="font-family: 'SanFranciscoBold'; width: 70px; text-align: right;">Fecha:</td>
							<td>$fechaCreacion</td>
						</tr>
					</table>
				</td>
			</tr>
		</table><br />
	
		<table cellpadding="3" cellspacing="5" border="0" style="font-size: 9px;">
			<tr>
				<td style="background-color: #F1F1F1;">
					<span class="titulo">$empresa</span><br>
					$emisor
				</td>
				<td style="background-color: #F1F1F1;">
					<span class="titulo">DIRECCIÓN FISCAL</span><br>
					$direccionFiscal
				</td>
			</tr>
		</table>

		<table class="sinBorde" style="text-align: left;" cellpadding="2" cellspacing="3">
			<tr>
				<td style="background-color: #444; color: #FFF; width: 45%;">
					<span style="font-family: 'SanFranciscoBold';">Cliente:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 45%;">
					<span style="font-family: 'SanFranciscoBold';">Tipo de Comprobante:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 10%;">
					<span style="font-family: 'SanFranciscoBold';">Serie/Folio:</strong>
				</td>
			</tr>
			<tr>
				<td>$cliente</td>
				<td>FACTURA ELECTRÓNICA (CFDI) (I-INGRESO)</td>
				<td>$folio</td>
			</tr>
		</table>
		<table class="sinBorde" style="text-align: left;" cellpadding="2" cellspacing="3">
			<tr>
				<td style="background-color: #444; color: #FFF; width: 36%">
					<span style="font-family: 'SanFranciscoBold';">Forma y Condiciones de Pago:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 8%">
					<span style="font-family: 'SanFranciscoBold';">Moneda:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 29%;">
					<span style="font-family: 'SanFranciscoBold';">Método de Pago:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 16%;">
					<span style="font-family: 'SanFranciscoBold';">UsoCFDI:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 11%;">
					<span style="font-family: 'SanFranciscoBold';">T. Cambio:</strong>
				</td>
			</tr>
			<tr>
				<td>$metodoPago</td>
				<td>$moneda</td>
				<td>$formaPago</td>
				<td>$usoCfdi</td>
				<td>$tipo_cambio</td>
			</tr>
		</table>
		<table class="sinBorde" style="text-align: left;" cellpadding="2" cellspacing="3">
			<tr>
				<td style="background-color: #444; color: #FFF; width: 20%">
					<span style="font-family: 'SanFranciscoBold';">R.F.C.:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 60%">
					<span style="font-family: 'SanFranciscoBold';">Dirección:</strong>
				</td>
				<td style="background-color: #444; color: #FFF; width: 20%">
					<span style="font-family: 'SanFranciscoBold';">Fecha y Hora:</strong>
				</td>
			</tr>
			<tr>
				<td>$rfc</td>
				<td>$clienteDireccion</td>
				<td>$fechaCreacion</td>
			</tr>
		</table><br />

		<table id="partidas" style="font-size: 8px; text-align: left;" border="0" cellpadding="1" cellspacing="0">
			<tbody>
				<tr>
					<td style="width: 10%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Código</td>
					<td style="width: 10%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Clave<br />ProdServ</td>
					<td style="width: 29.4%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Descripción</td>
					<td style="width: 10%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Cantidad</td>
					<td style="width: 10%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Unidad</td>
					<td style="width: 10%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Clave<br />Unidad</td>
					<td style="width: 10%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Precio</td>
					<td style="width: 10%; text-align: center; background-color: #999; color: #FFF; font-family: 'SanFranciscoBold';">Importe</td>
				</tr>
				$htmlPartidas
			</tbody>
		</table><br />

		<br>
		<table class="sinBorde" style="text-align: left; width: 387px;" cellpadding="2" cellspacing="0">
			<tbody>
				<tr>
					<td style="font-size: 9px; width: 375px; background-color: #999; color: #FFF;">
						<span style="font-family: 'SanFranciscoBold';">Importe con Letra:</span>
					</td>
					<td style="font-size: 9px; text-align: right; width: 100px; background-color: #999; color: #FFF;">
						<span style="font-family: 'SanFranciscoBold';">Subtotal:</span>
					</td>
					<td style="font-size: 9px; width: 60px; text-align: right;">$ $Tsubtotal</td>
				</tr>
				<tr>
					<td style="font-size: 9px; width: 375px; text-align: center;">
						$totalLetras
					</td>
					<td style="font-size: 9px; text-align: right; width: 100px; background-color: #999; color: #FFF;">
						<span style="font-family: 'SanFranciscoBold';"> IVA $porImpuesto% Traslado:</span>
					</td>
					<td style="font-size: 9px; width: 60px; text-align: right;">
						$ $Timpuesto
					</td>
				</tr>
				<tr>
					<td style="font-size: 9px; width: 375px; background-color: #999; color: #FFF;">
						<span style="font-family: 'SanFranciscoBold';">Notas para el Cliente:</strong>
					</td>
					<td style="font-size: 9px; text-align: right; width: 100px; background-color: #999; color: #FFF;">
						<span style="font-family: 'SanFranciscoBold';">Total:</span>
					</td>
					<td style="font-size: 9px; width: 60px; text-align: right;">
						$ $Ttotal
					</td>
				</tr>
				<tr>
					<td>
						$observaciones
					</td>
				</tr>
			</tbody>
		</table><table class="sinBorde" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="width: 23%;">
					<img src="@,$qrCode" width="125" height="125">
				</td>
				<td style="width: 70%;">
					<br /><br /><br />
					<table cellspacing="2" cellpadding="4">
						<tr>
							<td style="background-color: #444; color: #FFF;">Folio fiscal:</td><td>$uuid</td>
						</tr>
						<tr>
							<td style="background-color: #444; color: #FFF;">No de serie del Certificado del SAT:</td><td>$noCertificadoSAT</td>
						</tr>
						<tr>
							<td style="background-color: #444; color: #FFF;">No de serie del Certificado del CSD:</td><td>$noCertificadoCFDI</td>
						</tr>
						<tr>
							<td style="background-color: #444; color: #FFF;">Fecha y hora de certificación:</td><td>$fechaTimbrado</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table class="sinBorde" border="0" cellpadding="3" cellspacing="3">
			<tr>
				<td style="background-color: #CACACA; color: #FFF;">Sello Digital del CFDI:</td>
			</tr>
			<tr>
				<td style="font-size: 7px;">$selloCFDI</td>
			</tr>

			<tr>
				<td style="background-color: #CACACA; color: #FFF;">Sello del SAT:</td>
			</tr>
			<tr>
				<td style="font-size: 7px;">$selloSAT</td>
			</tr>

			<tr>
				<td style="background-color: #CACACA; color: #FFF;">Cadena original del complemento de certificación digital del SAT:</td>
			</tr>
			<tr>
				<td style="font-size: 7px;">$cadenaOriginalSAT</td>
			</tr>
		</table>
		<br />

		<div style="text-align: center;">Este documento es una representación impresa de un CFDI</div>
		</body></html>
EOF;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		$pdfContent = $pdf->Output("Factura_$folio.pdf", 'S');
		$fp = fopen(ROOT_DIR . "/data/facturas/" . $uuid . ".pdf", 'w');
		fwrite($fp, $pdfContent);
		fclose($fp);
	}

	public function timbrar($id) {
		// Variables iniciales
		ini_set('memory_limit', '1024M');
		set_time_limit(0);
		
		$sth = $this->_db->prepare("
			SELECT f.id, f.forma_pago, f.moneda, f.metodo_pago, f.email, f.subtotal, f.impuesto, f.total, c.id AS id_cliente, c.razon_social AS cliente, c.rfc, f.fecha, f.tipo_relacion, f.uso_cfdi, f.observaciones, f.por_impuesto, f.tipo_cambio, f.cancelado, f.emisor, c.regimen
			FROM facturas f
			LEFT JOIN clientes c
			ON c.id = f.id_cliente
			WHERE f.id = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		switch ($datos['emisor']) {
			case 1:
			$empresa = 'MANTENIMIENTO Y ADMINISTRACION PROFESIONAL';
			$emisor = '<span style="font-size: 8px;"><br />
				RFC: MAP941111HE2<br />
				RÉGIMEN FISCAL: RÉGIMEN GENERAL DE LEY PERSONAS MORALES
			</span>';
			$direccionFiscal = '<span style="font-size: 8px;">MANUEL DOBLADO 2721 - 1101 B<br />
				COL. CALETE<br>
				TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22044<br>
				TEL. (664) 634 2808
			</span>';
			$logo = '<br /><br /><img src="' . STASIS . '/img/mapsa.png" width="140" alt="">';
			break;
		}

		$forma_pago = $datos['forma_pago'];
		if ($forma_pago == 'PAGO EN UNA SOLA EXHIBICION') {
			$formaPago = 'PUE';
		} elseif ($forma_pago == 'PAGO EN PARCIALIDADES O DIFERIDO') {
			$formaPago = 'PPD';
		}

		$uso_cfdi = $datos['uso_cfdi'];
		$regimenFiscalCliente = $datos['regimen'];
		$metodo_pago = $datos['metodo_pago'];
		$porImpuesto = $datos['por_impuesto'];
		$tipo_cambio = $datos['tipo_cambio'];
		if ($tipo_cambio == 1.00) {
			$tipo_cambio = 1;
		}
		$metodoPago = $datos['metodo_pago'];
		$folio = $datos['id'];
		$fechaCreacion = new DateTime($datos['fecha']);
		$fechaCreacion = $fechaCreacion->format('Y-m-d\TH:i:s');
		$clienteRfc = $datos['rfc'];
		$moneda = $datos['moneda'];

		if ($moneda == 1) {
			$moneda = 'MXN';
			$tipo_cambio = 1;
		} elseif ($moneda == 2) {
			$moneda = 'USD';
		}

		$idCliente = $datos['id_cliente'];
		$cliente = $datos['cliente'];

		$subtotal = $datos['subtotal'];
		$impuesto = $datos['impuesto'];
		$total = $datos['total'];

		// Direccion del Cliente
		$sth = $this->_db->prepare("SELECT nombre_calle, num_exterior, num_interior, colonia, cp, pais, estado, ciudad FROM direcciones WHERE id_catalogo = ? AND id_tipo = 1");
		$sth->bindParam(1, $idCliente);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$nombreCalle = $datos['nombre_calle'];
		$numExterior = $datos['num_exterior'];
		$numInterior = $datos['num_interior'];
		$colonia = $datos['colonia'];
		$cp = $datos['cp'];
		$domicilioFiscalCliente = $datos['cp'];
		$pais = $datos['pais'];
		$estado = $datos['estado'];
		$ciudad = $datos['ciudad'];

		$sth2 = $this->_db->prepare("
			SELECT p.nombre AS descripcion, cp.cantidad, p.id AS codigo, cp.precio, cp.clave_prodserv, cp.clave_unidad, f.por_impuesto
			FROM facturas_partes cp
			JOIN facturas f
			ON f.id = cp.id_factura
			JOIN conceptos p
			ON p.id = cp.id_parte
			WHERE cp.id_factura = ? ORDER BY cp.id ASC
		");
		$sth2->bindParam(1, $id);
		if(!$sth2->execute()) throw New Exception();

		// Servicios
		$traslados = '';
		$trasladosConcepto = '';
		$conceptos = '';
		$impuestosTrasladados0 = 0;
		$impuestosTrasladados8 = 0;
		$impuestosTrasladados16 = 0;
		$totalImpuestosTrasladados = 0;
		$totalImpuestosTrasladados0SinIva = 0;
		$totalImpuestosTrasladados8 = 0;
		$totalImpuestosTrasladados8SinIva = 0;
		$totalImpuestosTrasladados16 = 0;
		$totalImpuestosTrasladados16SinIva = 0;
		$totalImpuestosRetenidos = 0;
		$subtotal = 0;
		$total = 0;

		while ($datos2 = $sth2->fetch()) {
			$xmlRetencion = '';
			switch ($datos2['clave_unidad']) {
				case 'E48': $claveUnidad = 'UNIDAD DE SERVICIO'; break;
				case 'ACT': $claveUnidad = 'ACTIVIDAD'; break;
				case 'E51': $claveUnidad = 'TRABAJO'; break;
				case 'A9': $claveUnidad = 'TARIFA'; break;
				case 'E54': $claveUnidad = 'VIAJE'; break;
			}
			if ($datos2['por_impuesto'] == .12) {
				$porImpuesto = .16;
			} else {
				$porImpuesto = $datos['por_impuesto'];
			}
			$descripcion = htmlspecialchars(utf8_encode($datos2['descripcion']), ENT_XML1 | ENT_COMPAT, 'UTF-8');
			$valorUnitario = number_format((float)$datos2['precio'], 5, '.', '');
			$importe = number_format((float)$datos2['precio']*$datos2['cantidad'], 5, '.', '');
			$importeIva = number_format((float)($datos2['precio']*$datos2['cantidad'])*$porImpuesto, 5, '.', '');
			$subtotal += ($datos2['precio']*$datos2['cantidad']);
			if ($datos2['por_impuesto'] == .12) {
				$porImpuesto = .16;
				$impuestosRetenidos = number_format(($datos2['cantidad']*$datos2['precio'])*.04, 5, '.', '');
				$totalImpuestosRetenidos += ($datos2['cantidad']*$datos2['precio'])*.04;
				$impuestosTrasladados16 += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto - ($datos2['cantidad']*$datos2['precio'])*.04;
				$totalImpuestosTrasladados += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto - ($datos2['cantidad']*$datos2['precio'])*.04;
				$totalImpuestosTrasladados16 += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto - ($datos2['cantidad']*$datos2['precio'])*.04;
				$trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="' . $importeIva . '" />
				    <cfdi:Retenciones>';
				$xmlRetencion = '<cfdi:Retencion Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.040000" Importe="' . $impuestosRetenidos . '"/>
	                </cfdi:Retenciones>';
                $objetoImp = "02";
			} else {
				if ($datos2['por_impuesto'] == .00) {
					$porImpuesto = $datos2['por_impuesto'];
					$impuestosTrasladados0 = 1;
					$totalImpuestosTrasladados0SinIva += $datos2['cantidad']*$datos2['precio'];
					$trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="' . $porImpuesto . '0000" Importe="' . $importeIva . '" />';
					$objetoImp = "01";
				}
				if ($datos2['por_impuesto'] == .08) {
					$porImpuesto = $datos2['por_impuesto'];
					$impuestosTrasladados8 = number_format((float)($datos2['cantidad']*$datos2['precio'])*$porImpuesto, 5, '.', '');
					$totalImpuestosTrasladados += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto;
					$totalImpuestosTrasladados8 += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto;
					$totalImpuestosTrasladados8SinIva += $datos2['cantidad']*$datos2['precio'];
					$trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="' . $porImpuesto . '0000" Importe="' . $impuestosTrasladados8 . '" />';
					$objetoImp = "02";
				}
				if ($datos2['por_impuesto'] == .16) {
					$porImpuesto = $datos2['por_impuesto'];
					$importeIva = number_format((float)($datos2['cantidad']*$datos2['precio'])*$porImpuesto, 5, '.', '');
					$totalImpuestosTrasladados += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto;
					$totalImpuestosTrasladados16 += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto;
					$totalImpuestosTrasladados16SinIva += $datos2['cantidad']*$datos2['precio'];
					$trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="' . $porImpuesto . '0000" Importe="' . $importeIva . '" />';
					$objetoImp = "02";
				}
			}
			$conceptos .= '
				<cfdi:Concepto ClaveProdServ="' . $datos2['clave_prodserv'] . '" NoIdentificacion="' . $datos2['codigo'] . '" Cantidad="' . $datos2['cantidad'] . '" ClaveUnidad="' . $datos2['clave_unidad'] . '" Unidad="' . $claveUnidad . '" Descripcion="' . $descripcion . '" ValorUnitario="' . $valorUnitario . '" Importe="' . $importe . '" ObjetoImp="' . $objetoImp . '">
					<cfdi:Impuestos>
						<cfdi:Traslados>
						' . $trasladosConcepto . '
						</cfdi:Traslados>
						' . $xmlRetencion . '
					</cfdi:Impuestos>
				</cfdi:Concepto>';
		}
		
		$subtotal = number_format((float)$subtotal, 2, '.', '');
		$importeTotalIva = number_format((float)$totalImpuestosTrasladados, 2, '.', '');
		if ($impuestosTrasladados0 == 1) {
			$traslados .= '<cfdi:Traslado Base="' . number_format($totalImpuestosTrasladados0SinIva, 2, '.', '') . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.000000" Importe="0.00" />';
		}
		if ($totalImpuestosTrasladados8 != 0) {
			$traslados .= '<cfdi:Traslado Base="' . number_format($totalImpuestosTrasladados8SinIva, 2, '.', '') . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.080000" Importe="' . number_format($totalImpuestosTrasladados8, 2, '.', '') . '" />';
		}
		if ($totalImpuestosTrasladados16 != 0) {
			$traslados .= '<cfdi:Traslado Base="' . number_format($totalImpuestosTrasladados16SinIva, 2, '.', '') . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="' . number_format($totalImpuestosTrasladados16, 2, '.', '') . '" />';
		}
		// Si tiene impuesto retenido
		if ($totalImpuestosRetenidos) {
			$totalImpuestosRetenidos = 'TotalImpuestosRetenidos="' . number_format($totalImpuestosRetenidos, 5, '.', '') . '"';
			$retenciones = '
			    <cfdi:Retenciones>
			        <cfdi:Retencion Impuesto="002" Importe="' . $totalImpuestosRetenidos . '"/>
		        </cfdi:Retenciones>
	        ';
		} else {
			$total = number_format((float)$subtotal+$importeTotalIva, 2, '.', '');
			$totalImpuestosRetenidos = '';
			$retenciones = '';
		}

		//Regimenes fiscales: 601-Moral, 612-ActividadEmpresarial, 621-IncorporacionFiscal
		$nombreFiscal = 'MANTENIMIENTO Y ADMINISTRACION PROFESIONAL';
		$regimenFiscal = '601';
		$rfc = 'MAP941111HE2';

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<cfdi:Comprobante
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:cfdi="http://www.sat.gob.mx/cfd/4"
				LugarExpedicion="22044"
				MetodoPago="' . $formaPago . '"
				TipoDeComprobante="I"
				Total="' . $total . '"
				TipoCambio="' . $tipo_cambio . '"
				Moneda="' . $moneda . '"
				SubTotal="' . $subtotal . '"
				Exportacion="01"
				Certificado="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"
				NoCertificado="00001000000508264961"
				FormaPago="' . $metodoPago . '"
				Sello="' . $sello->sello . '"
				Fecha="' . $fechaCreacion . '"
				Folio="' . $id . '"
				Version="4.0"
				xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd">
				<cfdi:Emisor
					RegimenFiscal="' . $regimenFiscal. '"
					Nombre="' . $nombreFiscal . '"
					Rfc="' . $rfc . '" />
				<cfdi:Receptor
					Nombre="' . htmlspecialchars($cliente) . '"
					Rfc="' . htmlspecialchars($clienteRfc) . '"
					UsoCFDI="' . $uso_cfdi . '"
					DomicilioFiscalReceptor="' . $domicilioFiscalCliente . '"
					RegimenFiscalReceptor="' . $regimenFiscalCliente . '" />
				<cfdi:Conceptos>
					' . $conceptos . '
				</cfdi:Conceptos>
				<cfdi:Impuestos TotalImpuestosTrasladados="' . $importeTotalIva . '">
					<cfdi:Traslados>
						' . $traslados . '
					</cfdi:Traslados>
				</cfdi:Impuestos>
			</cfdi:Comprobante>';

		$fp = fopen(ROOT_DIR . "data/xml/" . $id . ".xml", 'w');
		fwrite($fp, $xml);
		fclose($fp);

		// Cadena origian Plugin
		require_once APP . '/plugins/cfdiutils/vendor/autoload.php';
		$xmlContent = file_get_contents(ROOT_DIR . "data/xml/" . $id . ".xml");
		$resolver = new XmlResolver();
		$location = $resolver->resolveCadenaOrigenLocation('4.0');
		$builder = new DOMBuilder();
		$cadenaorigen = $builder->build($xmlContent, $location);

		$fp = fopen(ROOT_DIR . "/data/xml/" . $id . ".txt", 'w');
		fwrite($fp, $cadenaorigen);
		fclose($fp);

        // Sellar
        require_once APP . '/plugins/lunasoft/autoload.php';
		$params = array(
	        "cadenaOriginal"=> ROOT_DIR . "/data/xml/" . $id . ".txt",
	        "archivoKeyPem"=> ROOT_DIR . "data/mapsa_key.pem",
	        "archivoCerPem"=> ROOT_DIR . "data/mapsa_cer.pem"
	    );

	    try {
	        // Meter sello en XML
	        $sello = Sellar::obtenerSello($params);

	        try {
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<cfdi:Comprobante
						xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
						xmlns:cfdi="http://www.sat.gob.mx/cfd/4"
						LugarExpedicion="22044"
						MetodoPago="' . $formaPago . '"
						TipoDeComprobante="I"
						Total="' . $total . '"
						TipoCambio="' . $tipo_cambio . '"
						Moneda="' . $moneda . '"
						SubTotal="' . $subtotal . '"
						Exportacion="01"
						Certificado="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"
						NoCertificado="00001000000508264961"
						FormaPago="' . $metodoPago . '"
						Sello="' . $sello->sello . '"
						Fecha="' . $fechaCreacion . '"
						Folio="' . $id . '"
						Version="4.0"
				        xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd">
						' . $xmlCfdiRelacionados . '
						<cfdi:Emisor
							RegimenFiscal="' . $regimenFiscal. '"
							Nombre="' . $nombreFiscal . '"
							Rfc="' . $rfc . '" />
						<cfdi:Receptor
							Nombre="' . htmlspecialchars($cliente) . '"
							Rfc="' . htmlspecialchars($clienteRfc) . '"
							UsoCFDI="' . $uso_cfdi . '"
							DomicilioFiscalReceptor="' . $domicilioFiscalCliente . '"
							RegimenFiscalReceptor="' . $regimenFiscalCliente . '" />
						<cfdi:Conceptos>
							' . $conceptos . '
						</cfdi:Conceptos>
						<cfdi:Impuestos TotalImpuestosTrasladados="' . $importeTotalIva . '">
							<cfdi:Traslados>
								' . $traslados . '
							</cfdi:Traslados>
						</cfdi:Impuestos>
					</cfdi:Comprobante>';

				$fp = fopen(ROOT_DIR . "/data/xml/" . $id . ".xml", 'w');
				fwrite($fp, $xml);
				fclose($fp);

				// Timbrar
		        try {
				    header('Content-type: application/json');

				    $params = array(
				        "url"=>"http://services.sw.com.mx",
				        "token"=>"T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRMNTZ4UHhPUFJkK2daaHZzd05sTjVLSnFkWlo4T2JqQmRpSEc4S3Rzbk5BellhdzZQOTJuaWhWRTRjNzJRNkFzUFZYT3NrRDhWZ01xNU9wSlQ2Z2NwMEQvcmlvbzF1STBTdnFQRXpVMXJ4L00welF4dGpMU0g0RHY1bTh4QTB3UERVVERDMGlodktXL24reWxYTlNRRmdmeWFxL3R2aHlrV2RyVTJwT1cyYUg1Z05IRitjNmczTFIwMW1BM2FZZDNHSDlrejJRYXdWL250akxiaTBWejdGZ3NnM05ORUNZdEhBYXZrZk5rR1FmTG96R0YwNHNkRmlhWnRQM05JZFJ6K213VDEvRyt4eWRvQW1YS2o3VlZwLzR4WkxDK2djaGZJTXI4a1RCRERKRkZrbExYZUdoS2R3YS84Q1JKcXJ6VnFkN0kvVmxyNFE5aTdFOTNiZXRCUTF3SG1XK0JoRjdGS00xZ3E1TGd5SVNKM2I1ckp3VFZUYjU5ZkcxeDRuWjY.tN-qEjyGkSRn0HQKMX7zjB_0kC3sgJ7NxP0UGD8ZtiQ"
			        );
				    
				    $xml = file_get_contents(ROOT_DIR . "data/xml/" . $id . ".xml");
				    $stamp = StampService::Set($params);

				    $result = $stamp::StampV4($xml);

				    if (empty($result->message)) {
				    	$fp = fopen(ROOT_DIR . "/data/xml/" . $id . "_timbrado.xml", 'w');
						fwrite($fp, json_encode($result));
						fclose($fp);
						
						$sth = $this->_db->prepare("UPDATE facturas SET timbrado = 1 WHERE id = ?");
						$sth->bindParam(1, $id);
						if(!$sth->execute()) throw New Exception();

						// Descarga
						$file = file_get_contents(ROOT_DIR . "/data/xml/" . $id . "_timbrado.xml");
						$file = json_decode($file);
						$uuid = $file->data->uuid;
						$cfdi = $file->data->cfdi;

						// Creacion XML
						$fp = fopen(ROOT_DIR . "/data/facturas/" . $uuid . ".xml", 'w');
						fwrite($fp, $cfdi);
						fclose($fp);

						// Creacion PDF
						$this->visualizarfactura($id);

						// Enviar Correo
						$correo = Modelos_Contenedor::crearModelo('Correo');
						$correo->factura($id);
				    } else {
				    	var_dump($result);
				    	die;
				    }
				} catch(Exception $e) {
				    echo 'Caught exception: ',  $e->getMessage(), "\n";
				    die;
				}
			} catch(Exception $e) {
		        echo var_dump($c);
		   	}
	    } catch(Exception $e) {
	        echo 'Caught exception: ',  $e->getMessage(), "\n";
	    }
	}

}