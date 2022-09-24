<?php
final class Modelos_Propietarios extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function recibo() {
		// PDF
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new RTPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Solicitud');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetPrintHeader(false);
		$pdf->SetMargins(10, 10, 10, 0);
		$pdf->AddPage();

		$uniqueId = $id;

		// $sth = $this->_db->prepare("
		// 	SELECT so.id, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.fecha_autorizada, so.fecha_compromiso, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, d.nombre AS departamento, e.email, e.telefono, so.descripcion, e.foto, so.fecha_atendida, so.conclusion, CONCAT(a.nombre, ' ', a.apellidos) AS administrador, so.motivo_cancelacion, so.otro, so.conclusion_archivo
		// 	FROM solicitudes so
		// 	LEFT JOIN servicios se
		// 	ON se.id = so.id_servicio
		// 	LEFT JOIN propietarios p
		// 	ON p.id = so.id_propietario
		// 	LEFT JOIN empleados e
		// 	ON e.id = so.id_responsable
		// 	LEFT JOIN departamentos d
		// 	ON d.id = e.id_departamento
		// 	LEFT JOIN empleados a
		// 	ON a.id = so.id_autorizado
		// 	WHERE so.uniqueid = ?
		// 	ORDER BY so.id DESC
		// ");
		// $sth->bindParam(1, $id);
		// if(!$sth->execute()) throw New Exception();
		// $datos = $sth->fetch();

		// if (!$datos) die;

		// $id = $datos['id'];
		// if ($datos['tipo'] == 'A') {
		// 	$tipo = 'ATENCIÓN';
		// } elseif ($datos['tipo'] == 'S') {
		// 	$tipo = 'SERVICIO';
		// }

		// if (!$datos['servicio']) {
		// 	$servicio = mb_strtoupper($datos['otro']);
		// } else {
		// 	$servicio = $datos['servicio'];
		// }

		// switch ($datos['seccion']) {
		// 	case 'HACIENDA DEL REY (RGR)': $prefijo = 'SR'; break;
		// 	case 'HACIENDA DEL REY': $prefijo = 'SR'; break;
		// 	case 'LOMAS (RGR)': $prefijo = 'SL'; break;
		// 	case 'LOMAS': $prefijo = 'SL'; break;
		// 	case 'HACIENDA VALLE DE LOS ENCINOS (RGR)': $prefijo = 'SV'; break;
		// 	case 'HACIENDA VALLE DE LOS ENCINOS': $prefijo = 'SV'; break;
		// 	case 'CAÑADA DEL ENCINO': $prefijo = 'SC'; break;
		// 	case 'VISTA DEL REY (RGR)': $prefijo = 'VR'; break;
		// 	case 'VISTA DEL REY': $prefijo = 'VR'; break;
		// }

		// $no_solicitud = $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT);
		// $propietario = $datos['propietario'];
		// $lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
		// $servicio = $servicio;
		// $motivo_cancelacion = $datos['motivo_cancelacion'];
		// $fecha_creacion = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);

		// if ($datos['fecha_autorizada']) {
		// 	$fecha_autorizada = Modelos_Fecha::formatearFechaHora($datos['fecha_autorizada']);
		// } else {
		// 	$fecha_autorizada = '';
		// }
		// if ($datos['fecha_compromiso']) {
		// 	$fecha_compromiso = $datos['fecha_compromiso'];
		// } else {
		// 	$fecha_compromiso = '';
		// }
		// if ($datos['fecha_atendida']) {
		// 	$fecha_atendida = Modelos_Fecha::formatearFecha($datos['fecha_atendida']);

		// 	$fechaAtendidaDateTime = new DateTime($datos['fecha_atendida']);
		// 	$fechaAtendidaDateTime = $fechaAtendidaDateTime->getTimestamp();
		// 	$fechaAtendidaFormatted = utf8_encode(ucfirst(strftime("%A %d de %B del %Y a las %H:%M hrs", $fechaAtendidaDateTime)));
		// } else {
		// 	$fecha_atendida = '';
		// }

		// $descripcion = $datos['descripcion'];

		// // Archivos de solicitud
		// $solicitudArchivos = '';
		// $sth2 = $this->_db->prepare("SELECT archivo FROM solicitudes_archivos WHERE id_solicitud = ?");
		// $sth2->bindParam(1, $id);
		// if(!$sth2->execute()) throw New Exception();

		// $x = 0;
		// while ($datos2 = $sth2->fetch()) {
		// 	if ($x == 0) $solicitudArchivos .= '<br /><br />';
		// 	$solicitudArchivos .= 'Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/atencion/data/privada/archivos/' . $datos2['archivo'] . '">' . $datos2['archivo'] . '</a><br />';
		// 	$x++;
		// }
		
		// $descripcion .= $solicitudArchivos;
		// // Fin archivos descripcion

		// $status = $datos['status'];
		
		// $responsable = $datos['responsable'];
		// $departamento = $datos['departamento'];
		// $email = $datos['email'];
		// $telefono = $datos['telefono'];

		// if (!$datos['foto']) {
		// 	$foto = 'img/prop.png';
		// } else {
		// 	$foto = 'data/f/' . $datos['foto'];
		// }
		// $conclusion = $datos['conclusion'];
		// $administrador = $datos['administrador'];
		// $conclusion_archivo = $datos['conclusion_archivo'];

		// // Comentarios
		// $sth = $this->_db->prepare("
		// 	SELECT COUNT(s.id)
		// 	FROM solicitudes_comentarios s
		// 	LEFT JOIN empleados e
		// 	ON e.id = s.id_usuario
		// 	WHERE s.id_solicitud = ?
		// 	ORDER BY s.fecha DESC
		// ");
		// $sth->bindParam(1, $id);
		// if(!$sth->execute()) throw New Exception();
		// $cComentarios = $sth->fetchColumn();

		// if ($cComentarios >= 1) {
		// 	$htmlComentarios = '
		// 		<br />
		// 		<table style="border: 2px solid #DDDCDD;">
		// 		</table>
		// 		<br />
		// 		<div style="text-align: center; font-size: 9px;">
		// 			<span style="font-weight: bold; text-align: center; font-size: 10px;">BITÁCORA DE SEGUIMIENTO</span><br />
		// 		</div>
		// 		<table style="text-align: left; font-size: 8px;" cellpadding="0" border="0">
		// 	';

		// 	$sth = $this->_db->prepare("
		// 		SELECT s.comentario, s.fecha, CONCAT(e.nombre, ' ', e.apellidos) AS usuario, s.fecha, e.foto, p.nombre AS puesto, s.archivo
		// 		FROM solicitudes_comentarios s
		// 		LEFT JOIN empleados e
		// 		ON e.id = s.id_usuario
		// 		LEFT JOIN puestos p
		// 		ON p.id = e.id_puesto
		// 		WHERE s.id_solicitud = ?
		// 		ORDER BY s.fecha DESC
		// 	");
		// 	$sth->bindParam(1, $id);
		// 	if(!$sth->execute()) throw New Exception();
		// 	while ($datos = $sth->fetch()) {
		// 		$fechaComentario = Modelos_Fecha::formatearFechaHora($datos['fecha']);
		// 		if (!$datos['usuario']) {
		// 			$usuario = $propietario . ' (PROPIETARIO)';
		// 			$fotoComentario = '<img src="' . STASIS . '/img/prop.png" height="50" />';

		// 			if ($datos['archivo']) {
		// 				$archivo = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/atencion/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a>';
		// 			} else {
		// 				$archivo = '';
		// 			}
		// 		} else {
		// 			if ($datos['foto'] == '') {
		// 				$fotoComentarioArchivo = 'img/prop.png';
		// 			} else {
		// 				$fotoComentarioArchivo = 'data/f/' . $datos['foto'];
		// 			}
		// 			$fotoComentario = '<img src="' . STASIS . '/' . $fotoComentarioArchivo . '" height="50" />';
		// 			$usuario = $datos['usuario'] . ' (' . $datos['puesto'] . ')';

		// 			if ($datos['archivo']) {
		// 				$archivo = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a>';
		// 			} else {
		// 				$archivo = '';
		// 			}
		// 		}

		// 		$htmlComentarios .= '
		// 			<tr>
		// 				<td style="width: 12%; text-align: center;" rowspan="2">' . $fotoComentario . '</td>
		// 				<td style="background-color: #EAEAEA; color: #000; width: 88%"><span style="line-height: 2; font-family: \'SanFranciscoBold\';">' . $usuario . '</span> | ' . $fechaComentario . '</td>
		// 			</tr>
		// 			<tr>
		// 				<td>
		// 					' . $datos['comentario'] . $archivo . '
		// 				</td>
		// 			</tr>
		// 			<tr>
		// 				<td></td>
		// 			</tr>
		// 		';
		// 	}

		// 	$htmlComentarios .= '</table><br /><br />';
		// }

		// if (empty($motivo_cancelacion)) {
		// 	if (!empty($responsable)) {
		// 		// Si ya se atendio
		// 		if (!empty($fecha_atendida)) {

		// 			if ($conclusion_archivo) {
		// 				$archivoConclusion = '<br /><br />Archivo adjunto:<br /><img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $conclusion_archivo . '">' . $conclusion_archivo . '</a>';
		// 			} else {
		// 				$archivoConclusion = '';
		// 			}

		// 			$htmlCompromiso = '
		// 				<br />
		// 				<table style="border: 2px solid #DDDCDD;">
		// 				</table>
		// 				<br />

		// 				<div style="text-align: center; font-size: 9px;">
		// 					<span style="font-weight: bold; text-align: center; font-size: 10px;">CONCLUSIÓN</span>
		// 				</div>

		// 				<div style="background-color: #DBDECE; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">' . $conclusion . '</span><br /><br /><img src="' . STASIS . '/img/guirnalda.png" height="20" /><br />Atentamente:<b><br />' . $administrador . '<br />' . $fechaAtendidaFormatted . '</b>' . $archivoConclusion . '<br /></div>
		// 			';
		// 		// Si hay fecha compromiso
		// 		} else {
		// 			if (!empty($fecha_compromiso)) {
		// 				$fechaCompromisoDateTime = new DateTime($fecha_compromiso);
		// 				$fechaCompromisoDateTime = $fechaCompromisoDateTime->getTimestamp();
		// 				$fechaCompromisoFormatteada = ucfirst(utf8_encode(strftime("%A %d de %B, %Y", $fechaCompromisoDateTime)));

		// 				$htmlCompromiso = '
		// 					<div style="background-color: #7FAA41; color: #FFF; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFranciscoBold\';">Fecha Estimada de Entrega:</span><br />' . $fechaCompromisoFormatteada . '<br /></div>
		// 				';
		// 			} else {
		// 				$htmlCompromiso = '<div style="background-color: #C4DEED; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">Está por determinarse la fecha estimada de entrega por el reponsable acorde a lo solicitado.<br />Asignaremos la fecha en un periodo máximo de 24 horas.</span><br /></div>';
		// 			}
		// 		}

		// 		$htmlResponsable = '
		// 			<br />
		// 			<table style="border: 2px solid #DDDCDD;">
		// 			</table>
		// 			<br />
					
		// 			<div style="text-align: center; font-size: 9px;">
		// 				<span style="font-weight: bold; text-align: center; font-size: 10px;">NOMBRE DEL RESPONSABLE</span>
		// 			</div>

		// 			<table>
		// 				<tr>
		// 					<td style="width: 15%; text-align: center;">
		// 						<img src="' . STASIS . '/' . $foto . '" height="60" />
		// 					</td>
		// 					<td style="width: 85%">
		// 						<table style="text-align: left; font-size: 8px;" cellpadding="2" cellspacing="1">
		// 							<tr>
		// 								<td style="background-color: #00436C; color: #FFF; width: 50%">
		// 									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Nombre:</strong>
		// 								</td>
		// 								<td style="background-color: #00436C; color: #FFF; width: 50%">
		// 									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Departamento:</strong>
		// 								</td>
		// 							</tr>
		// 							<tr>
		// 								<td style="text-align: center;">' . $responsable . '</td>
		// 								<td style="text-align: center;">' . $departamento . '</td>
		// 							</tr>
		// 							<tr>
		// 								<td style="background-color: #00436C; color: #FFF; width: 50%">
		// 									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Teléfono:</strong>
		// 								</td>
		// 								<td style="background-color: #00436C; color: #FFF; width: 50%">
		// 									<span style="text-align: center; font-family: \'SanFranciscoBold\';">Correo:</strong>
		// 								</td>
		// 							</tr>
		// 							<tr>
		// 								<td style="text-align: center;">' . $telefono . '</td>
		// 								<td style="text-align: center;">' . $email . '</td>
		// 							</tr>
		// 						</table>
		// 					</td>
		// 				</tr>
		// 			</table>

		// 			<br />
		// 			' . $htmlCompromiso . '
		// 			' . $htmlComentarios . '
		// 		';
		// 	} else {
		// 		$htmlResponsable = '
		// 			<div style="background-color: #C4DEED; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">La solicitud será revisada en un periodo máximo de 24 horas a partir del momento de su creación.</span><br /></div>
		// 		';
		// 	}
		// } else {
		// 	$htmlResponsable = '
		// 		<div style="background-color: #FFBCC6; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">Solicitud cancelada por propietario con el siguiente motivo de cancelación:<br/><br/>' . $motivo_cancelacion . '</span><br /></div>
		// 	';
		// }

		// switch($status) {
		// 	case 0: $statusHtml = '<img src="' . STASIS . '/img/s-success.png" height="7" /> Pendiente'; break;
		// 	case 1: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Autorizada'; break;
		// 	case 2: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Procesando'; break;
		// 	case 3: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Procesando'; break;
		// 	case 4: $statusHtml = '<img src="' . STASIS . '/img/s-info.png" height="7" /> Atendida'; break;
		// 	case -1: $statusHtml = '<img src="' . STASIS . '/img/s-danger.png" height="7" /> Cancelada'; break;
		// 	case 9: $statusHtml = '<img src="' . STASIS . '/img/s-warning.png" height="7" /> En Revisión'; break;
		// }

		$stasis = STASIS;
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Bold.ttf', 'TrueTypeUnicode', '', 96);
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Regular.ttf', 'TrueTypeUnicode', '', 96);

		$html = <<<EOF
		    <table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 405px;">
						<table border="1" cellpadding="5" cellspacing="0">
							<tr>
								<td style="width: 135px; color: #444;">
									<img src="$stasis/img/logo2.png" height="50" />
								</td>

								<td style="width: 130px; text-align: left; color: #444;">
									<span style="font-size: 8px; font-family: 'Roboto Bold';">COBROPLAN S.C.</span><br />
									<span style="font-size: 7px;">ESCUADRON 201 #3110INT D,<br />AVIACION, TIJUANA, B.C. CP 22014<br />TEL (664) 680-6052</span>
								</td>

								<td style="width: 135px; text-align: left; color: #444;">
									<span style="font-size: 8px; font-family: 'Roboto Bold';">INMOBILIARIA RANCHO TECATE</span><br />
									<span style="font-size: 7px;">KM 10.5 CARRETERA TECATE<br />- ENSENADA<br />TECATE, B.C. 01 (665)654-00-11</span></td>
							</tr>
						</table>
						<br /><br />

						<table border="1" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width: 135px; font-family: 'Roboto Bold'; font-size: 10px; color: #444;">
									CLIENTE: 
								</td>
								<td style="width: 265px; font-size: 10px; color: #444;">
									ALBERTO CASTRO PEREZ
								</td>
							</tr>
							<tr>
								<td style="width: 135px; font-family: 'Roboto Bold'; font-size: 10px; color: #444;">
									DOMICILIO: 
								</td>
								<td style="width: 265px; font-size: 10px; color: #444;">
									LA SERENA AT BAJAMAR - CONDOMINIO 302
								</td>
							</tr>
							<tr>
								<td style="width: 135px; font-family: 'Roboto Bold'; font-size: 10px; color: #444;">
									CIUDAD: 
								</td>
								<td style="width: 265px; font-size: 10px; color: #444;">
									ROSARITO, BAJA CALIFORNIA
								</td>
							</tr>
						</table>
					</td>

					<td style="width: 134px;">
                        <table border="1" cellpadding="5" cellspacing="0">
							<tr>
							    <td colspan="3" style="text-align: center; color: #FFF; background-color: #004B93; font-size: 8px;">
									RECIBO DE COBRO
								</td>
							</tr>
							<tr>
							    <td colspan="3" style="text-align: center; color: #000; font-size: 12px;">
									001
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
									29
								</td>
								<td style="text-align: center; color: #000; font-size: 7px;">
									06
								</td>
								<td style="text-align: center; color: #000; font-size: 7px;">
									22
								</td>
							</tr>

							<tr>
							    <td colspan="3" style="text-align: center; color: #FFF; background-color: #004B93; font-size: 8px;">
									RFC DEL CLIENTE
								</td>
							</tr>
							<tr>
							    <td colspan="3" style="text-align: center; color: #000; font-size: 7px;">
									CAPA890620J63
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
						Cuota de Mantenimiento Periodo: Marzo 2022
					</td>
				</tr>
			</table>

			
			
EOF;
		$fechaPdf = date('d-m-Y');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		$pdf->Output("Solicitud_{$no_solicitud}_{$fechaPdf}.pdf", 'I');
	}

}