<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once(APP . 'plugins/PHPMailer/src/Exception.php');
require_once(APP . 'plugins/PHPMailer/src/PHPMailer.php');
require_once(APP . 'plugins/PHPMailer/src/SMTP.php');

final class Modelos_Correo extends Modelo {
	protected $_db = null;
	private $_bodySuperior;
	private $_bodyInferior;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    private function enviarCorreo($cuerpo, $titulo, $destinatario1, $destinatario2 = null, $archivoAdjunto = null, $solicitudGenerada = null) {
		
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->isHTML(true); 
		$mail->CharSet 		= 'UTF-8';
		$mail->Mailer 		= 'smtp';
		$mail->SMTPDebug  	= 0;
		$mail->SMTPAuth   	= true;
		$mail->SMTPSecure 	= 'ssl';
		$mail->Port     	= 465;
		$mail->Host			= 'saevalcas.mx';
		$mail->Username 	= 'notificaciones@saevalcas.mx';
		$mail->Password 	= 'Provisional123.';
		$mail->SetFrom('notificaciones@saevalcas.mx', 'Grupo Valcas');
		$mail->addAddress($destinatario1);

		if ($solicitudGenerada == 1) {
			$sth = $this->_db->prepare("
				SELECT email
				FROM empleados
				WHERE tipo = 4 AND status = 1
			");
			if(!$sth->execute()) throw New Exception();
			while ($datos = $sth->fetch()) {
				$mail->addAddress($datos['email']);
			}
		}

		$mail->Subject = $titulo;
		$mail->Body    = $this->_bodySuperior . $cuerpo . $this->_bodyInferior;
		if ($archivoAdjunto) $mail->AddAttachment(ROOT_DIR . "data/tmp/$archivoAdjunto");
		$mail->send();

		if (!empty($redireccion)) header("Location:" . $redireccion);
	}

	public function __construct() {
		$this->_bodySuperior = '<html><head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <style type="text/css"> #outlook a{padding:0;} body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} body{-webkit-text-size-adjust:none;} .ExternalClass * {line-height: 100%} body{margin:0; padding:0;} img{border:0; line-height:100% !important; outline:none; text-decoration:none;} table td{border-collapse:collapse;} #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;} a{text-decoration:none;} </style> </head> <body>';
		$this->_bodyInferior = '<br /></body></html>';
    }

    // Solicitud generada
	public function solicitudGenerada($id, $nombrePdf) {
		$sth = $this->_db->prepare("
			SELECT so.id, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.fecha_autorizada, so.fecha_compromiso, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, d.nombre AS departamento, p.email, p.telefono1 AS telefono, so.descripcion, e.foto, so.fecha_atendida, so.conclusion, CONCAT(a.nombre, ' ', a.apellidos) AS administrador, so.motivo_cancelacion, so.otro
			FROM solicitudes so
			LEFT JOIN servicios se
			ON se.id = so.id_servicio
			LEFT JOIN propietarios p
			ON p.id = so.id_propietario
			LEFT JOIN empleados e
			ON e.id = so.id_responsable
			LEFT JOIN departamentos d
			ON d.id = e.id_departamento
			LEFT JOIN empleados a
			ON a.id = so.id_autorizado
			WHERE so.id = ?
			ORDER BY so.id DESC
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

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

		if ($datos['tipo'] == 'A') {
			$tipo = 'ATENCIÓN';
		} elseif ($datos['tipo'] == 'S') {
			$tipo = 'SERVICIO';
		}

		if (!$datos['servicio']) {
			$servicio = mb_strtoupper($datos['otro']);
		} else {
			$servicio = $datos['servicio'];
		}

		$id = $id;
		$no_solicitud = $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT);
		$propietario = $datos['propietario'];
		$servicio = $servicio;
		$motivo_cancelacion = $datos['motivo_cancelacion'];
		$fecha_creacion = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);

		if ($datos['fecha_autorizada']) {
			$fecha_autorizada = Modelos_Fecha::formatearFechaHora($datos['fecha_autorizada']);
		} else {
			$fecha_autorizada = '';
		}
		if ($datos['fecha_compromiso']) {
			$fecha_compromiso = $datos['fecha_compromiso'];
		} else {
			$fecha_compromiso = '';
		}
		if ($datos['fecha_atendida']) {
			$fecha_atendida = Modelos_Fecha::formatearFecha($datos['fecha_atendida']);

			$fechaAtendidaDateTime = new DateTime($datos['fecha_atendida']);
			$fechaAtendidaDateTime = $fechaAtendidaDateTime->getTimestamp();
			$fechaAtendidaFormatted = ucfirst(strftime("%A %d de %B, %Y", $fechaAtendidaDateTime));
		} else {
			$fecha_atendida = '';
		}

		$descripcion = $datos['descripcion'];
		$status = $datos['status'];
		$responsable = $datos['responsable'];
		$departamento = $datos['departamento'];
		$email = $datos['email'];
		$telefono = $datos['telefono'];
		$conclusion = $datos['conclusion'];
		$administrador = $datos['administrador'];

		$cuerpo = <<<EOT
			<center>
				<table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family: Calibri, Arial, sans-serif;">
					<tr>
						<td bgcolor="#FFF" style="padding: 0 0; text-align: center; color: #FFF;" colspan="2"><img src="https://saevalcas.mx/img/gvalcas.png" /><br /><br /></td>
					</tr>
					<tr>
						<td bgcolor="#0C73B9" style="padding: 10px 0; text-align: center; color: #FFF;" colspan="2">Solicitud Generada por Propietario</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>No. Solicitud:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$no_solicitud}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Propietario:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$propietario}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Lote:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$lote}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Servicio:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$servicio}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Descripción Detallada:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$descripcion}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Fecha de Creación:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$fecha_creacion}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Correo de Contacto:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$email}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Teléfono de Contacto:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$telefono}</td>
					</tr>
				</table>
			</center>
EOT;
	
		$correo = $email;
		$titulo = 'Solicitud Generada por Propietario';

		$this->enviarCorreo($cuerpo, $titulo, $correo, '', $nombrePdf, 1);
	}

	// Informacion actulizada
	public function informacionActualizada() {
		$sth = $this->_db->prepare("SELECT email, telefono1, telefono2 FROM propietarios WHERE id = ?");
		$sth->bindParam(1, $_SESSION['login_id']);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$email = $datos['email'];
		$telefono1 = $datos['telefono1'];
		$telefono2 = $datos['telefono2'];

		$cuerpo = <<<EOT
			<center>
				<table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family: Calibri, Arial, sans-serif;">
					<tr>
						<td bgcolor="#FFF" style="padding: 0 0; text-align: center; color: #FFF;" colspan="2"><img src="http://gvalcas.dualstudio.com.mx/img/gvalcas.png" /><br /><br /></td>
					</tr>
					<tr>
						<td bgcolor="#0C73B9" style="padding: 10px 0; text-align: center; color: #FFF;" colspan="2">Datos de Contacto de Propietario Actualizados</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Nuevo Correo Electrónico:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$email}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Nuevo Teléfono 1:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$telefono1}</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>Nuevo Teléfono 2:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$telefono2}</td>
					</tr>
				</table>
			</center>
EOT;
	
		$correo = $email;
		$titulo = 'Información de Propietario Actualizada';

		$this->enviarCorreo($cuerpo, $titulo, $correo, '', '', 1);
	}

	public function solicitudComentario($id, $comentario, $nombrePdf) {
		$sth = $this->_db->prepare("
			SELECT so.id, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.fecha_autorizada, so.fecha_compromiso, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, d.nombre AS departamento, e.email, e.telefono, so.descripcion, e.foto, so.fecha_atendida, so.conclusion, CONCAT(a.nombre, ' ', a.apellidos) AS administrador, so.motivo_cancelacion, so.otro
			FROM solicitudes so
			LEFT JOIN servicios se
			ON se.id = so.id_servicio
			LEFT JOIN propietarios p
			ON p.id = so.id_propietario
			LEFT JOIN empleados e
			ON e.id = so.id_responsable
			LEFT JOIN departamentos d
			ON d.id = e.id_departamento
			LEFT JOIN empleados a
			ON a.id = so.id_autorizado
			WHERE so.id = ?
			ORDER BY so.id DESC
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$no_solicitud = $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT);
		$correo = $datos['email'];

		$cuerpo = <<<EOT
			<center>
				<table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family: Calibri, Arial, sans-serif;">
					<tr>
						<td bgcolor="#FFF" style="padding: 0 0; text-align: center; color: #FFF;" colspan="2"><img src="https://saevalcas.mx/img/gvalcas.png" /><br /><br /></td>
					</tr>
					<tr>
						<td bgcolor="#0C73B9" style="padding: 10px 0; text-align: center; color: #FFF;" colspan="2">Comentario Agregado en Solicitud</td>
					</tr>
					<tr>
						<td bgcolor="#EAEAEA" style="padding: 10px;"><b>No. Solicitud:</b></td>
						<td bgcolor="#EAEAEA" style="padding: 10px;">{$no_solicitud}</td>
					</tr>
					<tr>
						<td bgcolor="#0C73B9" style="padding: 10px 0; text-align: center; color: #FFF;" colspan="2">$comentario</td>
					</tr>
				</table>
			</center>
EOT;
	
		$titulo = 'Comentario Agregado en Solicitud';
		$this->enviarCorreo($cuerpo, $titulo, $correo, '', $nombrePdf);
	}

}