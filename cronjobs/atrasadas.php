<?php
// Requires
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once('../aplicacion/plugins/PHPMailer/src/Exception.php');
require_once('../aplicacion/plugins/PHPMailer/src/PHPMailer.php');
require_once('../aplicacion/plugins/PHPMailer/src/SMTP.php');

// DB
$host			= 'localhost';
$usuario		= 'dualstud_gvalcas';
$contrasena		= 'BO0B13S777.';
$nombre			= 'dualstud_gvalcas';
$db = new PDO("mysql:host=$host;dbname=$nombre;charset=utf8", $usuario, $contrasena);
$db->exec("SET NAMES UTF8");
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fecha Function
function formatearFecha($original='', $format="%d/%m/%Y") {
	$format = ($format=='date' ? "%m-%d-%Y" : $format);
	$format = ($format=='datetime' ? "%m-%d-%Y %H:%M:%S" : $format);
	$format = ($format=='mysql-date' ? "%Y-%m-%d" : $format);
	$format = ($format=='mysql-datetime' ? "%Y-%m-%d %H:%M:%S" : $format);
	return (!empty($original) ? strftime($format, strtotime($original)) : "" );
}

// Body del Correo
$bodySuperior = '<html><head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <style type="text/css"> #outlook a{padding:0;} body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} body{-webkit-text-size-adjust:none;} .ExternalClass * {line-height: 100%} body{margin:0; padding:0;} img{border:0; line-height:100% !important; outline:none; text-decoration:none;} table td{border-collapse:collapse;} #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;} a{text-decoration:none;} </style> </head> <body>';
$bodyInferior = '<br /></body></html>';

// Checar requis atrasadas
$sth = $db->query("
	SELECT rp.id, rp.id_requisicion, DATE(rp.fecha_creacion) AS fecha_creacion, DATE(rp.fecha_procesa) AS fecha_procesa, rp.fecha_autorizacion, rp.dias_entrega, rp.correo_enviado
	FROM requisiciones_partes rp
	JOIN requisiciones r
	ON r.id = rp.id_requisicion
	WHERE rp.status = 2 AND rp.correo_enviado = 0
	ORDER BY rp.id_requisicion DESC
");
if(!$sth->execute()) throw New Exception();

$idsAtrasados = [];
while ($datos = $sth->fetch()) {
	$fechaActual = new DateTime(date('Y-m-d 00:00:00'));
	$diasEntrega = $datos['dias_entrega'];

	$fechaVencimiento = new DateTime($datos['fecha_procesa']);
	$fechaVencimiento->modify("+$diasEntrega days");

	$diasVencidos = $fechaActual->diff($fechaVencimiento);
	$diasVencidos = $diasVencidos->format("%r%a");

	if ($diasVencidos >= 1) {
		$status = $diasVencidos;
	} elseif ($diasVencidos == 0) {
		$status = 'HOY';
	} else {
		$status = 'ATRASADA';
	}

	if ($status == 'ATRASADA') {
		$idsAtrasados[] = $datos['id'];
	}
}

foreach ($idsAtrasados as $id) {
	$sth = $db->prepare("
		SELECT rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, e.email AS solicita_email, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, DATE(rp.fecha_creacion) AS fecha_creacion, DATE(rp.fecha_procesa) AS fecha_procesa, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, es.email AS autoriza_email, rp.fecha_autorizacion, rp.dias_entrega, rp.oc
		FROM requisiciones_partes rp
		JOIN departamentos d
		ON d.id = rp.id_departamento
		JOIN empleados e
		ON e.id = rp.id_solicita
		JOIN empleados es
		ON es.id = rp.id_autoriza
		JOIN requisiciones r
		ON r.id = rp.id_requisicion
		WHERE rp.id = 14
		LIMIT 1
	");
	$sth->bindParam(1, $id);
	if(!$sth->execute()) throw New Exception();
	$datos = $sth->fetch();

	$fechaActual = new DateTime(date('Y-m-d 00:00:00'));
	$diasEntrega = $datos['dias_entrega'];
	$fechaVencimiento = new DateTime($datos['fecha_procesa']);
	$fechaVencimiento->modify("+$diasEntrega days");
	$diasVencidos = $fechaActual->diff($fechaVencimiento);
	$diasVencidos = $diasVencidos->format("%r%a");

	$id_requisicion = $datos['id_requisicion'];
	$solicita = $datos['solicita'];
	$autoriza = $datos['autoriza'];
	$departamento = $datos['departamento'];
	$producto = $datos['producto'];
	$tipo = $datos['tipo'];
	$cantidad = $datos['cantidad'];
	$dias_entrega = $datos['dias_entrega'];
	$fecha = formatearFecha($datos['fecha_creacion']);
	$fecha_procesa = formatearFecha($datos['fecha_procesa']);
	$fecha_vencimiento = formatearFecha($fechaVencimiento->format('Y-m-d'));
	$fecha_autorizacion = formatearFecha($datos['fecha_autorizacion']);

	$solicita_email = $datos['solicita_email'];
	$autoriza_email = $datos['autoriza_email'];

	$cuerpo = <<<EOT
		<center>
			<table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family: Calibri, Arial, sans-serif;">
				<tr>
					<td bgcolor="#FFFFFF" style="padding: 0 0; text-align: center; color: #FFF;" colspan="2"><img src="http://gvalcas.dualstudio.com.mx/img/gvalcas.png" /></td>
				</tr>
				<tr>
					<td bgcolor="#0C73B9" style="padding: 10px 0; text-align: center; color: #FFF;" colspan="2">Requisición Atrasada</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Folio de Requisición:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$id_requisicion}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Solicitado Por:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$solicita}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Autorizado Por:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$autoriza}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Departamento:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$departamento}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Producto:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$producto}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Cantidad:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$cantidad}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Fecha de Creación:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$fecha}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Fecha Autorizada:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$fecha_autorizacion}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Fecha Procesada:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$fecha_procesa}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Dias de Entrega:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$dias_entrega}</td>
				</tr>
				<tr>
					<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Fecha de Vencimiento:</b></td>
					<td bgcolor="#EAEAEA" style="padding: 10px;">{$fecha_vencimiento}</td>
				</tr>
			</table>
		</center>
EOT;

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->isHTML(true);     
	$mail->CharSet 		= 'UTF-8';
	$mail->Host			= 'mail.dualstudio.com.mx';  
	$mail->Port     	= 465;
	$mail->SMTPAuth 	= true;
	$mail->Username 	= 'gvalcas@dualstudio.com.mx';                            
	$mail->Password 	= '(s&{Wiz6~LIm';                           
	$mail->SMTPSecure 	= 'ssl';                            
	$mail->From 		= 'gvalcas@dualstudio.com.mx';
	$mail->FromName 	= 'Sistema Grupo Valcas';
	$mail->Subject 		= 'Requisición Atrasada';
	$mail->Body   		= $bodySuperior . $cuerpo . $bodyInferior;
	
	if (!empty($solicita_email)) $mail->addAddress($solicita_email);
	if (!empty($autoriza_email)) $mail->addAddress($autoriza_email);
	$mail->addCC('abelmonte@grupovalcas.mx');
	$mail->addCC('procesos@grupovalcas.mx');

	if($mail->send()) {
		$sth = $db->prepare("UPDATE requisiciones_partes SET correo_enviado = 1 WHERE id = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
	}
}