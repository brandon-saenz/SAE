<?php
final class Modelos_Movimientos_Procesos extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function nueva() {
		try {
			$datosArray = array();

			// Folio
			$sth = $this->_db->query("SELECT id FROM interacciones ORDER BY id DESC LIMIT 1");
			$datosArray['folio'] = str_pad($sth->fetchColumn()+1, 3, '0', STR_PAD_LEFT);
			$datosArray['solicitado_por'] = $_SESSION['login_nombre'] . ' ' . $_SESSION['login_apellidos'];

			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
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

	public function generarSolicitud() {
		try {
			$id_destinatario = $_POST['id_destinatario'];
			$titulo = $_POST['titulo'];
			$mensaje = $_POST['mensaje'];
			$prioridad = $_POST['prioridad'];
			$origen = $_POST['origen'];
			$archivo = $_POST['archivo'];
			$fecha_requerida = DateTime::createFromFormat('d/m/Y', $_POST['fecha_requerida']);
			$fecha_requerida = $fecha_requerida->format('Y-m-d');

			$bytes = random_bytes(10);
			$uniqueId = bin2hex($bytes);

			$sth = $this->_db->prepare("INSERT INTO interacciones (id_remitente, id_destinatario, titulo, mensaje, prioridad, fecha_creacion, uniqueid, fecha_requerida, origen) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $id_destinatario);
			$sth->bindParam(3, $titulo);
			$sth->bindParam(4, $mensaje);
			$sth->bindParam(5, $prioridad);
			$sth->bindParam(6, $uniqueId);
			$sth->bindParam(7, $fecha_requerida);
			$sth->bindParam(8, $origen);
			if(!$sth->execute()) throw New Exception();
			$idSolicitud = $this->_db->lastInsertId();

			if (!$_FILES['archivo']['size'] == 0) {
				require APP . 'inc/class.upload.php';
				
				$handle = new upload($_FILES['archivo']);
				if ($handle->uploaded) {
					$archivo = str_replace(' ', '_', $handle->file_src_name_body) . '-' . time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/archivos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $idSolicitud);
				$sth = $this->_db->prepare("UPDATE interacciones SET archivo = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

			$nombrePdf = $this->pdf($uniqueId, 1, 0);

			for($x=1; $x<=50; $x++) {
				$id_cc = $_POST["id_cc$x"];

				if ($id_cc) {
					$emailCC = '';

					$sth = $this->_db->prepare("INSERT INTO interacciones_usuarios (id_interaccion, id_usuario) VALUES (?, ?)");
					$arregloDatos = array($idSolicitud, $id_cc);
					if(!$sth->execute($arregloDatos)) throw New Exception();

					// Enviar correo a CC
					$sth = $this->_db->prepare("SELECT email FROM empleados WHERE id = ?");
					$sth->bindParam(1, $id_cc);
					if(!$sth->execute()) throw New Exception();
					$emailCC = $sth->fetchColumn();

					if ($emailCC) {
						$correo = Modelos_Contenedor::crearModelo('Correo');
						$correo->interaccionParticipante($idSolicitud, $nombrePdf, $emailCC);
					}
				}
			}

			$sth = $this->_db->prepare("SELECT celular FROM empleados WHERE id = ?");
			$sth->bindParam(1, $id_destinatario);
			if(!$sth->execute()) throw New Exception();
			$celular = $sth->fetchColumn();

			if ($celular) {
				$celularLada = '52' . $celular;
				$folio = str_pad($idSolicitud, 3, '0', STR_PAD_LEFT);
				$tituloMayusculas = substr(mb_strtoupper($titulo), 0, 70);
				$this->AltiriaSMS($celularLada,"SAE Valcas: Se ha asignado el folio $folio de atención interna: https://saevalcas.mx/movimientos/procesos/visualizar/$uniqueId", '', false);

				$sth = $this->_db->query("UPDATE config SET sms = sms-1");
				if(!$sth->execute()) throw New Exception();
			}

			// Enviar correo a destinatario
			$sth = $this->_db->prepare("SELECT email FROM empleados WHERE id = ?");
			$sth->bindParam(1, $id_destinatario);
			if(!$sth->execute()) throw New Exception();
			$emailDestinatario = $sth->fetchColumn();

			if ($emailDestinatario) {
				$correo = Modelos_Contenedor::crearModelo('Correo');
				$correo->interaccion($idSolicitud, $nombrePdf);
			}

	  		header('Location: ' . STASIS . '/movimientos/procesos/generar/1');
		} catch (Exception $e) {
			echo $e->getMessage();
			die;
		}
	}

	public function listadoUsuariosGlobales($id = null) {
		try {
			// Todos los que tienen correo
			$sth = $this->_db->query("
				SELECT e.id, CONCAT(e.nombre, ' ', e.apellidos) AS nombre, d.nombre AS departamento
				FROM empleados e
				JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.status = 1
				ORDER BY e.nombre ASC
			");
			if(!$sth->execute()) throw New Exception();

			$usuarios = [];
			while ($datos = $sth->fetch()) {
				$idUsuario = $datos['id'];
				$nombre = $datos['nombre'];
				$departamento = $datos['departamento'];

				if (!array_key_exists($nombre, $usuarios)) {
					$usuarios[$nombre][] = [
						'id' => $idUsuario,
						'nombre' => $nombre,
						'departamento' => $departamento
					];
				}
			}

			$html = '<option value="">Selecciona usuario...</option>';
			foreach ($usuarios as $value) {
				if ($id == $value[0]['id']) {
					$html .= '<option value="' . $value[0]['id'] . '" selected>' . mb_strtoupper($value[0]['nombre'], 'UTF-8') . ' - [' . $value[0]['departamento'] . ']</option>';
				} else {
					$html .= '<option value="' . $value[0]['id'] . '">' . mb_strtoupper($value[0]['nombre'], 'UTF-8') . ' - [' . $value[0]['departamento'] . ']</option>';
				}
			}
	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

    public function listado() {
		try {
			$fechaActual = new DateTime(date('Y-m-d 00:00:00'));
			$datosVista = array();

			// Pendientes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 1 AND i.id_remitente = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);
				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
				);

				$datosVista['pendientes'][] = $arreglo;
				$x++;
			}
			$datosVista['nPendientes'] = $x;

			// Proceso
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_compromiso
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 2 AND i.id_remitente = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);

				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
				);

				$datosVista['procesando'][] = $arreglo;
				$x++;
			}
			$datosVista['nProcesando'] = $x;

			// Finalizadas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 3 AND i.id_remitente = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
				);

				$datosVista['finalizadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nFinalizadas'] = $x;

			// Completadas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso, i.fecha_cierre
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 4 AND i.id_remitente = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
					'fecha_cierre' => Modelos_Fecha::formatearFecha($datos['fecha_cierre']),
				);

				$datosVista['completadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCompletadas'] = $x;

			// Canceladas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 0 AND i.id_remitente = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);
				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
				);

				$datosVista['canceladas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCanceladas'] = $x;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoGlobal() {
		try {
			$fechaActual = new DateTime(date('Y-m-d 00:00:00'));
			$datosVista = array();

			// Pendientes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 1
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);
				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
				);

				$datosVista['pendientes'][] = $arreglo;
				$x++;
			}
			$datosVista['nPendientes'] = $x;

			// Proceso
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_compromiso
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 2
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);

				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
				);

				$datosVista['procesando'][] = $arreglo;
				$x++;
			}
			$datosVista['nProcesando'] = $x;

			// Finalizadas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 3
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
				);

				$datosVista['finalizadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nFinalizadas'] = $x;

			// Completadas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso, i.fecha_cierre
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 4
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
					'fecha_cierre' => Modelos_Fecha::formatearFecha($datos['fecha_cierre']),
				);

				$datosVista['completadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCompletadas'] = $x;

			// Canceladas
			$sth = $this->_db->query("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 0
				ORDER BY i.id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);
				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
				);

				$datosVista['canceladas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCanceladas'] = $x;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function asignadas() {
		try {
			$fechaActual = new DateTime(date('Y-m-d 00:00:00'));
			$datosVista = array();

			// Pendientes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 1 AND ed.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);
				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'encargado' => 1,
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
				);

				$datosVista['pendientes'][] = $arreglo;
				$x++;
			}
			$datosVista['nPendientes'] = $x;

			// Pendientes Participantes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida
				FROM interacciones i
				JOIN interacciones_usuarios iu
				ON iu.id_interaccion = i.id
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				JOIN empleados iue
				ON iue.id = iu.id_usuario
				WHERE i.status = 1 AND iue.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);
				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
				);

				$datosVista['pendientes'][] = $arreglo;
				$x++;
			}
			$datosVista['nPendientes'] += $x;

			// Proceso
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_compromiso
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 2 AND ed.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);

				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
				);

				$datosVista['procesando'][] = $arreglo;
				$x++;
			}
			$datosVista['nProcesando'] = $x;

			// Proceso Participantes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_compromiso
				FROM interacciones i
				JOIN interacciones_usuarios iu
				ON iu.id_interaccion = i.id
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				JOIN empleados iue
				ON iue.id = iu.id_usuario
				WHERE i.status = 2 AND iue.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				$fechaRequerida = new DateTime($datos['fecha_requerida']);

				$diasVencidos = $fechaActual->diff($fechaRequerida);
				$diasVencidos = $diasVencidos->format("%r%a");

				if ($diasVencidos >= 1) {
					$status = $diasVencidos;
				} elseif ($diasVencidos == 0) {
					$status = 'HOY';
				} else {
					$status = 'ATRASADA';
				}

				if ($diasVencidos >= 3) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($diasVencidos >= 1 && $diasVencidos <= 2) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($diasVencidos == 0 || $status == 'ATRASADA') {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
				);

				$datosVista['procesando'][] = $arreglo;
				$x++;
			}
			$datosVista['nProcesando'] += $x;

			// Finalizadas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 3 AND ed.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
				);

				$datosVista['finalizadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nFinalizadas'] = $x;

			// Finalizadas Participantes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso
				FROM interacciones i
				JOIN interacciones_usuarios iu
				ON iu.id_interaccion = i.id
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				JOIN empleados iue
				ON iue.id = iu.id_usuario
				WHERE i.status = 3 AND iue.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad_label' => $prioridadLabel,
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
				);

				$datosVista['finalizadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nFinalizadas'] += $x;

			// Atendidas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso, i.fecha_cierre
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 4 AND i.id_destinatario = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				switch ($datos['prioridad']) {
					case 1: $prioridad = 'BAJA'; break;
					case 2: $prioridad = 'MEDIA'; break;
					case 3: $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad' => $prioridad,
					'prioridad_label' => $prioridadLabel,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
					'fecha_cierre' => Modelos_Fecha::formatearFecha($datos['fecha_cierre']),
				);

				$datosVista['completadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCompletadas'] = $x;

			// Atendidas Participantes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion, i.fecha_requerida, i.fecha_finalizada, i.fecha_compromiso, i.fecha_cierre
				FROM interacciones i
				JOIN interacciones_usuarios iu
				ON iu.id_interaccion = i.id
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				JOIN empleados iue
				ON iue.id = iu.id_usuario
				WHERE i.status = 4 AND iue.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				switch ($datos['prioridad']) {
					case 1: $prioridadLabel = 'label-success'; $prioridad = 'BAJA'; break;
					case 2: $prioridadLabel = 'label-warning'; $prioridad = 'MEDIA'; break;
					case 3: $prioridadLabel = 'label-danger'; $prioridad = 'ALTA'; break;
				}

				switch ($datos['prioridad']) {
					case 1: $prioridad = 'BAJA'; break;
					case 2: $prioridad = 'MEDIA'; break;
					case 3: $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad' => $prioridad,
					'prioridad_label' => $prioridadLabel,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_requerida' => Modelos_Fecha::formatearFecha($datos['fecha_requerida']),
					'fecha_finalizada' => Modelos_Fecha::formatearFecha($datos['fecha_finalizada']),
					'fecha_cierre' => Modelos_Fecha::formatearFecha($datos['fecha_cierre']),
				);

				$datosVista['completadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCompletadas'] += $x;

			// Canceladas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE i.status = 0 AND i.id_destinatario = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {

				switch ($datos['prioridad']) {
					case 1: $prioridad = 'BAJA'; break;
					case 2: $prioridad = 'MEDIA'; break;
					case 3: $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => str_pad($datos['id'], 3, '0', STR_PAD_LEFT),
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
				);

				$datosVista['canceladas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCanceladas'] = $x;

	  		return $datosVista;
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

			$datosArray['id'] = str_pad($datos['id'], 3, '0', STR_PAD_LEFT);
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

	public function generarComentario() {
		try {
			$id = $_POST['id'];
			$comentario = $_POST['comentario'];

			$sth = $this->_db->prepare("INSERT INTO interacciones_comentarios (id_interaccion, id_usuario, comentario, fecha) VALUES (?, ?, ?, NOW())");
			$sth->bindParam(1, $id);
			$sth->bindParam(2, $_SESSION['login_id']);
			$sth->bindParam(3, $comentario);
			if(!$sth->execute()) throw New Exception();
			$idComentario = $this->_db->lastInsertId();

			if (!$_FILES['archivo']['size'] == 0) {
				require APP . 'inc/class.upload.php';
				
				$handle = new upload($_FILES['archivo']);
				if ($handle->uploaded) {
					$archivo = str_replace(' ', '_', $handle->file_src_name_body) . '-' . time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/archivos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $idComentario);
				$sth = $this->_db->prepare("UPDATE interacciones_comentarios SET archivo = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

			$sth = $this->_db->prepare("SELECT id_destinatario FROM interacciones WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$idDestinatario = $sth->fetchColumn();

			$sth = $this->_db->prepare("
				SELECT i.id,
				CONCAT(er.nombre, ' ', er.apellidos) AS remitente, erp.nombre AS remitente_puesto, er.email AS remitente_email,
				CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, erd.nombre AS destinatario_puesto, ed.email AS destinatario_email
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				LEFT JOIN puestos erp
				ON erp.id = er.id_puesto
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				LEFT JOIN puestos erd
				ON erd.id = ed.id_puesto
				WHERE i.id = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();
			$correoDestinatario = $datos['destinatario_email'];
			$correoRemitente = $datos['remitente_email'];

			if ($idDestinatario == $_SESSION['login_id']) {
				// Enviar Correo
				$correo = Modelos_Contenedor::crearModelo('Correo');
				$correo->interaccionComentario($id, $comentario, $correoRemitente);
				// $correo->interaccionComentario($id, $comentario, $correoDestinatario);

				$sth = $this->_db->prepare("
					SELECT e.email
					FROM interacciones i
					JOIN interacciones_usuarios iu
					ON iu.id_interaccion = i.id
					JOIN empleados e
					ON e.id = iu.id_usuario
					WHERE i.id = ?
				");
				$sth->bindParam(1, $id);
				if(!$sth->execute()) throw New Exception();
				while ($datos = $sth->fetch()) {
					$correo->interaccionComentario($id, $comentario, $datos['email']);
				}

	  			header('Location: ' . STASIS . '/movimientos/procesos/asignadas/1');
	  		} else {
	  			// Enviar Correo
	  			$correo = Modelos_Contenedor::crearModelo('Correo');
				// $correo->interaccionComentario($id, $comentario, $correoRemitente);
				$correo->interaccionComentario($id, $comentario, $correoDestinatario);

				$sth = $this->_db->prepare("
					SELECT e.email
					FROM interacciones i
					JOIN interacciones_usuarios iu
					ON iu.id_interaccion = i.id
					JOIN empleados e
					ON e.id = iu.id_usuario
					WHERE i.id = ?
				");
				$sth->bindParam(1, $id);
				if(!$sth->execute()) throw New Exception();
				while ($datos = $sth->fetch()) {
					$correo->interaccionComentario($id, $comentario, $datos['email']);
				}

	  			header('Location: ' . STASIS . '/movimientos/procesos/generadas/1');
	  		}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function finalizar() {
		try {
			$id = $_POST['id'];
			$comentario = $_POST['comentario'];

			$sth = $this->_db->prepare("UPDATE interacciones SET conclusion_remitente = ?, fecha_finalizada = NOW(), status = 3 WHERE id = ?");
			$sth->bindParam(1, $comentario);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();

			if (!$_FILES['archivo']['size'] == 0) {
				require APP . 'inc/class.upload.php';
				
				$handle = new upload($_FILES['archivo']);
				if ($handle->uploaded) {
					$archivo = str_replace(' ', '_', $handle->file_src_name_body) . '-' . time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/archivos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $id);
				$sth = $this->_db->prepare("UPDATE interacciones SET conclusion_remitente_archivo = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

  			header('Location: ' . STASIS . '/movimientos/procesos/asignadas/3');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function procesar() {
		try {
			$id = $_POST['id'];
			$fecha_compromiso = DateTime::createFromFormat('d/m/Y', $_POST['fecha_entrega']);
			$fecha_compromiso = $fecha_compromiso->format('Y-m-d');

			$sth = $this->_db->prepare("UPDATE interacciones SET status = 2, fecha_compromiso = ? WHERE id = ?");
			$sth->bindParam(1, $fecha_compromiso);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();

	  		header('Location: ' . STASIS . '/movimientos/procesos/asignadas/2');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cancelar($uniqueid) {
		try {
			$sth = $this->_db->prepare("UPDATE interacciones SET status = 0 WHERE uniqueid = ?");
			$sth->bindParam(1, $uniqueid);
			if(!$sth->execute()) throw New Exception();

	  		header('Location: ' . STASIS . '/movimientos/procesos/generadas/2');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cerrar() {
		try {
			$id = $_POST['id'];
			$comentario = $_POST['comentario'];

			$sth = $this->_db->prepare("UPDATE interacciones SET conclusion_destinatario = ?, fecha_cierre = NOW(), status = 4 WHERE id = ?");
			$sth->bindParam(1, $comentario);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();

			if (!$_FILES['archivo']['size'] == 0) {
				require APP . 'inc/class.upload.php';
				
				$handle = new upload($_FILES['archivo']);
				if ($handle->uploaded) {
					$archivo = str_replace(' ', '_', $handle->file_src_name_body) . '-' . time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/archivos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $id);
				$sth = $this->_db->prepare("UPDATE interacciones SET conclusion_destinatario_archivo = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

  			header('Location: ' . STASIS . '/movimientos/procesos/generadas/3');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function visualizar($id) {
		$this->pdf($id,0,1);
	}

	public function descargar($id) {
		$this->pdf($id,1,0);
	}

	public function pdf($id, $descargar = null, $visualizar = null) {
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

		$sth = $this->_db->prepare("
			SELECT i.id,
			CONCAT(er.nombre, ' ', er.apellidos) AS remitente, erp.nombre AS remitente_puesto, er.email AS remitente_email,
			CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, erd.nombre AS destinatario_puesto, ed.email AS destinatario_email,
			i.titulo, i.mensaje, i.prioridad, i.fecha_creacion, i.archivo, i.fecha_requerida, i.status, i.fecha_compromiso, i.fecha_finalizada, i.conclusion_remitente, i.conclusion_remitente_archivo, i.fecha_cierre, i.conclusion_destinatario, i.conclusion_destinatario_archivo, i.origen
			FROM interacciones i
			JOIN empleados er
			ON er.id = i.id_remitente
			LEFT JOIN puestos erp
			ON erp.id = er.id_puesto

			JOIN empleados ed
			ON ed.id = i.id_destinatario
			LEFT JOIN puestos erd
			ON erd.id = ed.id_puesto

			WHERE i.uniqueid = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		if (!$datos) die;

		$id = $datos['id'];
		$no_solicitud = str_pad($datos['id'], 3, '0', STR_PAD_LEFT);
		$remitente = $datos['remitente'];
		$remitente_puesto = $datos['remitente_puesto'];
		$remitente_email = $datos['remitente_email'];
		$destinatario = $datos['destinatario'];
		$destinatario_puesto = $datos['destinatario_puesto'];
		$destinatario_email = $datos['destinatario_email'];

		$titulo = $datos['titulo'];
		$mensaje = $datos['mensaje'];
		$fecha_creacion = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);
		$fecha_compromiso = $datos['fecha_compromiso'];
		$fecha_cierre = $datos['fecha_cierre'];

		$fecha_requerida = new DateTime($datos['fecha_requerida']);
		$fechaRequeridaTimestamp = $fecha_requerida->getTimestamp();
		$fechaRequeridaFormatted = ucfirst(utf8_encode(strftime("%A %d de %B, %Y", $fechaRequeridaTimestamp)));

		$status = $datos['status'];

		$conclusion_remitente = $datos['conclusion_remitente'];
		$conclusion_remitente_archivo = $datos['conclusion_remitente_archivo'];
		$conclusion_destinatario = $datos['conclusion_destinatario'];
		$conclusion_destinatario_archivo = $datos['conclusion_destinatario_archivo'];

		switch($datos['origen']) {
			case 1: $origen = 'Queja de propietario'; break;
			case 2: $origen = 'Queja de proveedor'; break;
			case 3: $origen = 'Queja de colaborador'; break;
			case 4: $origen = 'Solicitud interna'; break;
			case 5: $origen = 'Acción correctiva'; break;
			case 6: $origen = 'Actualización de documentos'; break;
			case 7: $origen = 'Oportunidad de mejora'; break;
			case 8: $origen = 'Procedimientos'; break;
			case 9: $origen = 'Reuniones de resultados'; break;
			case 10: $origen = 'Otro'; break;
			case 11: $origen = 'Expediente cobranza-legal'; break;
		}

		switch ($datos['prioridad']) {
			case 1: $prioridad = 'BAJA'; break;
			case 2: $prioridad = 'MEDIA'; break;
			case 3: $prioridad = 'ALTA'; break;
		}

		// Archivo
		if ($datos['archivo']) {
			$archivoPrincipal = '<br /><br />';
			$archivoPrincipal .= 'Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a><br />';
			$mensaje .= $archivoPrincipal;
		}

		if ($datos['fecha_finalizada']) {
			$fecha_finalizada = Modelos_Fecha::formatearFecha($datos['fecha_finalizada']);
			$fechaFinalizadaDateTime = new DateTime($datos['fecha_finalizada']);
			$fechaFinalizadaDateTime = $fechaFinalizadaDateTime->getTimestamp();
			$fechaFinalizadaFormatted = utf8_encode(ucfirst(strftime("%A %d de %B del %Y a las %H:%M hrs", $fechaFinalizadaDateTime)));
		} else {
			$fecha_finalizada = '';
		}

		if ($datos['fecha_cierre']) {
			$fecha_cierre = Modelos_Fecha::formatearFecha($datos['fecha_cierre']);
			$fechaCierreDateTime = new DateTime($datos['fecha_cierre']);
			$fechaCierreDateTime = $fechaCierreDateTime->getTimestamp();
			$fechaCierreFormatted = utf8_encode(ucfirst(strftime("%A %d de %B del %Y a las %H:%M hrs", $fechaCierreDateTime)));
		} else {
			$fecha_cierre = '';
		}

		// Fecha Compromiso
		$htmlCompromiso = '';
		if (!empty($fecha_finalizada)) {
			if ($conclusion_remitente_archivo) {
				$archivoRemitente = '<br /><br />Archivo adjunto:<br /><img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $conclusion_remitente_archivo . '">' . $conclusion_remitente_archivo . '</a>';
			} else {
				$archivoRemitente = '';
			}

			if ($conclusion_destinatario_archivo) {
				$archivoDestinatario = '<br /><br />Archivo adjunto:<br /><img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $conclusion_destinatario_archivo . '">' . $conclusion_destinatario_archivo . '</a>';
			} else {
				$archivoDestinatario = '';
			}

			if (!empty($fecha_cierre)) {
				$htmlCompromiso .= '
				    <br />
				    <table style="border: 2px solid #DDDCDD;">
				    </table>
				    <br />

				    <div style="width: 300px; text-align: center; font-size: 8px;">
				    <span style="text-align: center; font-size: 9px; font-weight: bold; text-align: center;">CONCLUSIÓN DE ' . $remitente . '</span><br />
				    <span style="font-family: \'SanFrancisco\';">' . $conclusion_destinatario . '</span><br /><br /><img src="' . STASIS . '/img/guirnalda.png" height="20" /><br /><b>' . $fechaCierreFormatted . '</b>' . $archivoDestinatario . '</div>
				';
			}

			$htmlCompromiso .= '
			    <br />
			    <table style="border: 2px solid #DDDCDD;">
			    </table>
			    <br />

			    <div style="width: 300px; text-align: center; font-size: 8px;">
			    <span style="text-align: center; font-size: 9px; font-weight: bold; text-align: center;">CONCLUSIÓN DE ' . $destinatario . '</span><br />
			    <span style="font-family: \'SanFrancisco\';">' . $conclusion_remitente . '</span><br /><br /><img src="' . STASIS . '/img/guirnalda.png" height="20" /><br /><b>' . $fechaFinalizadaFormatted . '</b>' . $archivoRemitente . '</div>
			';
		} else {
			if (!empty($fecha_compromiso)) {
				$fechaCompromisoDateTime = new DateTime($fecha_compromiso);
				$fechaCompromisoDateTime = $fechaCompromisoDateTime->getTimestamp();
				$fechaCompromisoFormatteada = ucfirst(utf8_encode(strftime("%A %d de %B, %Y", $fechaCompromisoDateTime)));

				$htmlCompromiso .= '
					<div style="background-color: #7FAA41; color: #FFF; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFranciscoBold\';">Fecha Compromiso:</span><br />' . $fechaCompromisoFormatteada . '<br /></div>
				';
			}
		}

		// Usuarios
		$sth = $this->_db->prepare("
			SELECT COUNT(iu.id)
			FROM interacciones_usuarios iu
			JOIN interacciones i
			ON i.id = iu.id_interaccion
			WHERE i.id = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$cParticipantes = $sth->fetchColumn();

		if ($cParticipantes >= 1) {
			$htmlParticipantes = '
				<br />
				<table style="border: 2px solid #DDDCDD;">
				</table>
				<br />
				<div style="text-align: center; font-size: 8px;">
					<span style="font-weight: bold; text-align: center; font-size: 9px;">PARTICIPANTES</span>
				</div>
				<table style="text-align: left; font-size: 7px;" cellpadding="2" cellspacing="1">
			    <tr>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Nombre:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 30%">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Puesto:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Correo:</strong>
					</td>
				</tr>
			';

			$sth = $this->_db->prepare("
				SELECT CONCAT(e.nombre, ' ', e.apellidos) AS nombre, p.nombre AS puesto, e.email
				FROM interacciones_usuarios iu
				JOIN interacciones i
				ON i.id = iu.id_interaccion
				JOIN empleados e
				ON e.id = iu.id_usuario
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				WHERE i.id = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			while ($datos = $sth->fetch()) {
				$htmlParticipantes .= '
					<tr>
						<td style="text-align: center;">' . $datos['nombre'] . '</td>
						<td style="text-align: center;">' . $datos['puesto'] . '</td>
						<td style="text-align: center;">' . $datos['email'] . '</td>
					</tr>
				';
			}

			$htmlParticipantes .= '</table><br />';
		}

		// Comentarios
		$sth = $this->_db->prepare("
			SELECT COUNT(s.id)
			FROM interacciones_comentarios s
			LEFT JOIN empleados e
			ON e.id = s.id_usuario
			WHERE s.id_interaccion = ?
			ORDER BY s.fecha DESC
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$cComentarios = $sth->fetchColumn();

		if ($cComentarios >= 1) {
			$htmlComentarios = '
				<br />
				<table style="border: 2px solid #DDDCDD;">
				</table>
				<br />
				<div style="text-align: center; font-size: 8px;">
					<span style="font-weight: bold; text-align: center; font-size: 9px;">BITÁCORA DE SEGUIMIENTO</span><br />
				</div>
				<table style="text-align: left; font-size: 7px;" cellpadding="0" border="0">
			';

			$sth = $this->_db->prepare("
				SELECT s.comentario, s.fecha, CONCAT(e.nombre, ' ', e.apellidos) AS usuario, s.fecha, e.foto, p.nombre AS puesto, s.archivo
				FROM interacciones_comentarios s
				LEFT JOIN empleados e
				ON e.id = s.id_usuario
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				WHERE s.id_interaccion = ?
				ORDER BY s.fecha DESC
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			while ($datos = $sth->fetch()) {
				$fechaComentario = Modelos_Fecha::formatearFechaHora($datos['fecha']);
				if (!$datos['usuario']) {
					$usuario = $propietario . ' (PROPIETARIO)';
					$fotoComentario = '<img src="' . STASIS . '/img/prop.png" height="50" />';

					if ($datos['archivo']) {
						$archivo = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a>';
					} else {
						$archivo = '';
					}
				} else {
					if ($datos['foto'] == '') {
						$fotoComentarioArchivo = 'img/prop.png';
					} else {
						$fotoComentarioArchivo = 'data/f/' . $datos['foto'];
					}
					$fotoComentario = '<img src="' . STASIS . '/' . $fotoComentarioArchivo . '" height="50" />';
					$usuario = $datos['usuario'] . ' (' . $datos['puesto'] . ')';

					if ($datos['archivo']) {
						$archivo = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a>';
					} else {
						$archivo = '';
					}
				}

				$htmlComentarios .= '
					<tr>
						<td style="width: 12%; text-align: center;" rowspan="2">' . $fotoComentario . '</td>
						<td style="background-color: #EAEAEA; color: #000; width: 88%"><span style="line-height: 2; font-family: \'SanFranciscoBold\';">' . $usuario . '</span> | ' . $fechaComentario . '</td>
					</tr>
					<tr>
						<td>
							' . $datos['comentario'] . $archivo . '
						</td>
					</tr>
					<tr>
						<td></td>
					</tr>
				';
			}

			$htmlComentarios .= '</table><br /><br />';
		}

		if (empty($motivo_cancelacion)) {
			// Si ya se atendio
			if (!empty($fecha_atendida)) {

				if ($conclusion_archivo) {
					$archivoConclusion = '<br /><br />Archivo adjunto:<br /><img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $conclusion_archivo . '">' . $conclusion_archivo . '</a>';
				} else {
					$archivoConclusion = '';
				}

				$htmlCompromiso = '
					<br />
					<table style="border: 2px solid #DDDCDD;">
					</table>
					<br />

					<div style="text-align: center; font-size: 8px;">
						<span style="font-weight: bold; text-align: center; font-size: 9px;">CONCLUSIÓN</span>
					</div>

					<div style="background-color: #DBDECE; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">' . $conclusion . '</span><br /><br /><img src="' . STASIS . '/img/guirnalda.png" height="20" /><br />Atentamente:<b><br />' . $administrador . '<br />' . $fechaAtendidaFormatted . '</b>' . $archivoConclusion . '<br /></div>
				';
			}
		} else {
			$htmlResponsable = '
				<div style="background-color: #FFBCC6; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">Solicitud cancelada por propietario con el siguiente motivo de cancelación:<br/><br/>' . $motivo_cancelacion . '</span><br /></div>
			';
		}

		switch($status) {
			case 0: $statusHtml = '<img src="' . STASIS . '/img/s-danger.png" height="7" /> Cancelada'; break;
			case 1: $statusHtml = '<img src="' . STASIS . '/img/s-success.png" height="7" /> Pendiente'; break;
			case 2: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Procesando'; break;
			case 3: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Finalizada'; break;
			case 4: $statusHtml = '<img src="' . STASIS . '/img/s-info.png" height="7" /> Cerrada'; break;
		}

		$stasis = STASIS;
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Bold.ttf', 'TrueTypeUnicode', '', 96);
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Regular.ttf', 'TrueTypeUnicode', '', 96);

		$html = <<<EOF
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 250px; color: #444;">
						<img src="$stasis/img/gvalcas.png" height="64" />
					</td>
					<td style="width: 213px; text-align: right; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">INTERACCIÓN DE PROCESOS</span><br /><br />
						<span style="font-size: 8px;">No. Folio: $no_solicitud<br />Fecha: $fecha_creacion</span><br />
						<span style="font-size: 8px;">$statusHtml</span>
					</td>
					<td style="width: 75px; text-align: right;">
						<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=https://saevalcas.mx/movimientos/procesos/visualizar/$uniqueId&chld=H|0" height="65">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br />

			<div style="text-align: center; font-size: 8px;">
				<span style="font-weight: bold; text-align: center; font-size: 9px;">REMITENTE</span>
			</div>

			<table style="text-align: left; font-size: 7px;" cellpadding="2" cellspacing="1">
			    <tr>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Nombre:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 30%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Puesto:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Correo:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$remitente</td>
					<td style="text-align: center;">$remitente_puesto</td>
					<td style="text-align: center;">$remitente_email</td>
				</tr>
			</table>

			<br />

			<div style="text-align: center; font-size: 8px;">
				<span style="font-weight: bold; text-align: center; font-size: 9px;">DESTINATARIO</span>
			</div>

			<table style="text-align: left; font-size: 7px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Nombre:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 30%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Puesto:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Correo:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$destinatario</td>
					<td style="text-align: center;">$destinatario_puesto</td>
					<td style="text-align: center;">$destinatario_email</td>
				</tr>
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />

			<table style="text-align: left; font-size: 7px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #326EB7; color: #FFF; width: 33.3%;">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Tema:</strong>
					</td>
					<td style="background-color: #326EB7; color: #FFF; width: 33.3%;">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Origen:</strong>
					</td>
					<td style="background-color: #326EB7; color: #FFF; width: 33.3%;">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Fecha Requerida:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$titulo</td>
					<td style="text-align: center;">$origen</td>
					<td style="text-align: center;">$fechaRequeridaFormatted</td>
				</tr>
				<tr>
					<td style="background-color: #326EB7; color: #FFF; width: 537px">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Mensaje:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$mensaje</td>
				</tr>
			</table>
			<br />

			$htmlCompromiso
			$htmlParticipantes
			$htmlComentarios
EOF;
		$fechaPdf = date('d-m-Y');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if ($visualizar == 1) {
			$pdf->Output("Interaccion_{$no_solicitud}_{$fechaPdf}.pdf", 'I');
		} elseif ($descargar == 1) {
			$nombrePdf = "Interaccion_{$no_solicitud}_{$fechaPdf}.pdf";
			$archivo = $pdf->Output(ROOT_DIR . "/data/tmp/$nombrePdf", 'F');
			return $nombrePdf;
		}
	}

	public function dashboard() {
		$html = array();
		$html['js'] = '';

		// Total de Interacciones Generadas
		$sth = $this->_db->query("SELECT COUNT(*) FROM interacciones WHERE status != 0");
		if(!$sth->execute()) throw New Exception();
		$html['interaccionesGeneradas'] = $sth->fetchColumn();

		// Interacciones Pendientes
		$sth = $this->_db->query("SELECT COUNT(*) FROM interacciones WHERE status IN (1,2,3)");
		if(!$sth->execute()) throw New Exception();
		$html['interaccionesPendientes'] = $sth->fetchColumn();

		// Interacciones Completadas
		$sth = $this->_db->query("SELECT COUNT(*) FROM interacciones WHERE status = 4");
		if(!$sth->execute()) throw New Exception();
		$html['interaccionesCompletadas'] = $sth->fetchColumn();

		// Saldo SMSs
		$sth = $this->_db->query("SELECT sms FROM config");
		if(!$sth->execute()) throw New Exception();
		$html['saldoSms'] = $sth->fetchColumn();

		// Horas Promedio
		$horasArray = [];
		$sth = $this->_db->query("
			SELECT fecha_creacion, fecha_cierre
			FROM interacciones
			WHERE status = 4
		");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$date1 = new DateTime($datos['fecha_creacion']);
			$date2 = new DateTime($datos['fecha_cierre']);
			$diff = $date2->diff($date1);

			$hours = $diff->h;
			$hours = $hours + ($diff->days*24);

			$horasArray[] = $hours;
		}

		if (!empty($horasArray)) {
			$horasPromedio = array_sum($horasArray)/count($horasArray);
			$html['horasPromedio'] = number_format($horasPromedio, 2, '.', ',') . ' horas';
		} else {
			$html['horasPromedio'] = 'N/A';
		}

		// Interacciones por Departamento
		$interaccionesDepartamento = [];

		$sth = $this->_db->query("
			SELECT COUNT(*) AS c, d.nombre
			FROM interacciones i
			JOIN empleados e
			ON e.id = i.id_remitente
			JOIN departamentos d
			ON d.id = e.id_departamento
			WHERE e.id_departamento IS NOT NULL AND i.status != 0
			GROUP BY e.id_departamento
			ORDER BY COUNT(*) DESC
		");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$interaccionesDepartamento[$datos['nombre']] = $datos['c'];
		}

		$jsData = [];
		foreach ($interaccionesDepartamento as $nombre => $c) {
			$jsData[$nombre] = $c;
		}

		$jsData1 = '';
		$jsData2 = '';
		foreach ($jsData as $nombre => $c) {
			$jsData1 .= $c . ',';
			$jsData2 .= "'" . $nombre . "',";
		}
		$jsData1 = substr($jsData1, 0, -1);

		$html['js'] .= "
			var options = {
			  series: [{
			  name: 'Interacciones',
			  data: [$jsData1]
			}],
			  chart: {
			  height: 350,
			  type: 'bar',
			  toolbar: {
			      show: false
			    }
			},
			plotOptions: {
			  bar: {
			    borderRadius: 10,
			    dataLabels: {
			      position: 'top',
			    },
			  }
			},
			dataLabels: {
			  enabled: true,
			  formatter: function (val) {
			    return val;
			  },
			  offsetY: -20,
			  style: {
			    fontSize: '12px',
			  }
			},

			xaxis: {
			  categories: [$jsData2],
			  position: 'bottom',
			  axisBorder: {
			    show: false
			  },
			  axisTicks: {
			    show: false
			  },
			  crosshairs: {
			    fill: {
			      type: 'gradient',
			      gradient: {
			        colorFrom: '#D8E3F0',
			        colorTo: '#BED1E6',
			        stops: [0, 100],
			        opacityFrom: 0.4,
			        opacityTo: 0.5,
			      }
			    }
			  },
			  tooltip: {
			    enabled: true,
			  }
			},
			yaxis: {
			  axisBorder: {
			    show: false
			  },
			  axisTicks: {
			    show: false,
			  },
			  labels: {
			    show: false,
			    formatter: function (val) {
			      return val;
			    }
			  }

			}
			};

			var chart = new ApexCharts(document.querySelector(\"#charts\"), options);
			chart.render();
		";

		// Interacciones Recibidas por Departamento
		$interaccionesDepartamento = [];

		$sth = $this->_db->query("
			SELECT COUNT(*) AS c, d.nombre
			FROM interacciones i
			JOIN empleados e
			ON e.id = i.id_destinatario
			JOIN departamentos d
			ON d.id = e.id_departamento
			WHERE e.id_departamento IS NOT NULL AND i.status != 0
			GROUP BY e.id_departamento
			ORDER BY COUNT(*) DESC
		");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$interaccionesDepartamento[$datos['nombre']] = $datos['c'];
		}

		$jsData = [];
		foreach ($interaccionesDepartamento as $nombre => $c) {
			$jsData[$nombre] = $c;
		}

		$jsData1 = '';
		$jsData2 = '';
		foreach ($jsData as $nombre => $c) {
			$jsData1 .= $c . ',';
			$jsData2 .= "'" . $nombre . "',";
		}
		$jsData1 = substr($jsData1, 0, -1);
		$jsData2 = substr($jsData2, 0, -1);

		$html['js'] .= "
			var options = {
			  series: [{
			  name: 'Interacciones',
			  data: [$jsData1]
			}],
			  chart: {
			  height: 350,
			  type: 'bar',
			  toolbar: {
			      show: false
			    }
			},
			plotOptions: {
			  bar: {
			    borderRadius: 10,
			    dataLabels: {
			      position: 'top',
			    },
			  }
			},
			dataLabels: {
			  enabled: true,
			  formatter: function (val) {
			    return val;
			  },
			  offsetY: -20,
			  style: {
			    fontSize: '12px',
			  }
			},

			xaxis: {
			  categories: [$jsData2],
			  position: 'bottom',
			  axisBorder: {
			    show: false
			  },
			  axisTicks: {
			    show: false
			  },
			  crosshairs: {
			    fill: {
			      type: 'gradient',
			      gradient: {
			        colorFrom: '#D8E3F0',
			        colorTo: '#BED1E6',
			        stops: [0, 100],
			        opacityFrom: 0.4,
			        opacityTo: 0.5,
			      }
			    }
			  },
			  tooltip: {
			    enabled: true,
			  }
			},
			yaxis: {
			  axisBorder: {
			    show: false
			  },
			  axisTicks: {
			    show: false,
			  },
			  labels: {
			    show: false,
			    formatter: function (val) {
			      return val;
			    }
			  }

			}
			};

			var chart = new ApexCharts(document.querySelector(\"#charts7\"), options);
			chart.render();
		";

		// Porcentaje por Prioridades
		$porcentajeOrigenes = [];

		$sth = $this->_db->query("
			SELECT COUNT(id) AS c, i.origen
			FROM interacciones i
			WHERE i.status != 0
			GROUP BY i.origen
			ORDER BY origen DESC
		");
		if(!$sth->execute()) throw New Exception();

		$jsData1 = '';
		$jsData2 = '';
		while ($datos = $sth->fetch()) {

			switch($datos['origen']) {
				case 1: $origen = 'QUEJA DE PROPIETARIO'; break;
				case 2: $origen = 'QUEJA DE PROVEEDOR'; break;
				case 3: $origen = 'QUEJA DE COLABORADOR'; break;
				case 4: $origen = 'SOLICITUD INTERNA'; break;
				case 5: $origen = 'ACCIÓN CORRECTIVA'; break;
				case 6: $origen = 'ACTUALIZACIÓN DE DOCUMENTOS'; break;
				case 7: $origen = 'OPORTUNIDAD DE MEJORA'; break;
				case 8: $origen = 'PROCEDIMIENTOS'; break;
				case 9: $origen = 'REUNIONES DE RESULTADOS'; break;
				case 10: $origen = 'OTRO'; break;
				case 11: $origen = 'EXPEDIENTE COBRANZA-LEGAL'; break;
			}

			$jsData1 .= $datos['c'] . ',';
			$jsData2 .= "'" . $origen . "',";
		}

		$html['js'] .= "
			 var options = {
	          series: [$jsData1],
	          chart: {
	          type: 'pie',
	          height: 200,
	        },
	        legend: {
	          show: false
	        },
	        labels: [$jsData2],
	        responsive: [{
	          breakpoint: 480,
	          options: {
	            chart: {
	              width: 200
	            },
	            legend: {
	              position: 'bottom'
	            }
	          }
	        }]
	        };
	        var chart = new ApexCharts(document.querySelector(\"#charts-origen\"), options);
	        chart.render();
	    ";

		// Porcentaje por Prioridades
		$porcentajePrioridades = [];

		$sth = $this->_db->query("
			SELECT COUNT(id) AS c, i.prioridad
			FROM interacciones i
			WHERE i.status != 0
			GROUP BY i.prioridad
			ORDER BY prioridad DESC
		");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$porcentajePrioridades[] = $datos['c'];
		}
		$porcentajePrioridades = implode(',', $porcentajePrioridades);

		$html['js'] .= "
			 var options = {
	          series: [$porcentajePrioridades],
	          chart: {
	          type: 'pie',
	          height: 200,
	        },
	        legend: {
	          show: false
	        },
	        colors : ['#F57E8B', '#FEB019', '#1BC5BD'],
	        labels: ['Alta', 'Media', 'Baja'],
	        responsive: [{
	          breakpoint: 480,
	          options: {
	            chart: {
	              width: 200
	            },
	            legend: {
	              position: 'bottom'
	            }
	          }
	        }]
	        };
	        var chart = new ApexCharts(document.querySelector(\"#charts6\"), options);
	        chart.render();
	    ";

	    // Cumplidas Vs No Cumplidas
		$html['js'] .= "
			 var options = {
	          series: [" . $html['interaccionesCompletadas'] . ", " . $html['interaccionesPendientes'] . "],
	          chart: {
	          type: 'pie',
	          height: 200,
	        },
	        colors : ['#00E396', '#F57E8B'],
	        labels: ['Cumplidas', 'No Cumplidas'],
	        legend: {
	          show: false
	        },
	        responsive: [{
	          breakpoint: 480,
	          options: {
	            chart: {
	              width: 200
	            },
	            legend: {
	              position: 'bottom'
	            }
	          }
	        }]
	        };

	        var chart = new ApexCharts(document.querySelector(\"#charts4\"), options);
	        chart.render();
	    ";

	    // Cumplidas en Fecha Requerida
		$sth = $this->_db->query("
		    SELECT COUNT(id) AS c
			FROM interacciones
			WHERE DATE(fecha_cierre) < fecha_requerida
		");
		if(!$sth->execute()) throw New Exception();
		$cumplidas = $sth->fetchColumn();
		$noCumplidas = $html['interaccionesCompletadas'] - $cumplidas;

		$html['js'] .= "
			 var options = {
	          series: [$cumplidas, $noCumplidas],
	          chart: {
	          type: 'pie',
	          height: 200,
	        },
	        legend: {
	          show: false
	        },
	        colors : ['#00E396', '#F57E8B'],
	        labels: ['A Tiempo', 'Fuera de Tiempo'],
	        responsive: [{
	          breakpoint: 480,
	          options: {
	            chart: {
	              width: 200
	            },
	            legend: {
	              position: 'bottom'
	            }
	          }
	        }]
	        };

	        var chart = new ApexCharts(document.querySelector(\"#charts5\"), options);
	        chart.render();
        ";

        // Total de Interacciones Generadas
		$sth = $this->_db->query("SELECT COUNT(*) FROM interacciones WHERE status != 0 AND fecha_creacion BETWEEN '2022-05-01' AND '2022-05-31'");
		if(!$sth->execute()) throw New Exception();
		$html['interaccionesGeneradas1'] = $sth->fetchColumn();
		$sth = $this->_db->query("SELECT COUNT(*) FROM interacciones WHERE status != 0 AND fecha_creacion BETWEEN '2022-06-01' AND '2022-06-30'");
		if(!$sth->execute()) throw New Exception();
		$html['interaccionesGeneradas2'] = $sth->fetchColumn();
		$sth = $this->_db->query("SELECT COUNT(*) FROM interacciones WHERE status != 0 AND fecha_creacion BETWEEN '2022-07-01' AND '2022-07-31'");
		if(!$sth->execute()) throw New Exception();
		$html['interaccionesGeneradas3'] = $sth->fetchColumn();

        // Total de Interacciones 2022
        $html['js'] .= "
			var options = {
			  series: [{
			    name: \"Interacciones\",
			    data: [0, " . $html['interaccionesGeneradas1'] . ", " . $html['interaccionesGeneradas2'] . ", " . $html['interaccionesGeneradas3'] . "]
			}],
			  chart: {
			  height: 350,
			  type: 'line',
			  zoom: {
			    enabled: false
			  },
			  toolbar: {
			      show: false
			    }
			},
			dataLabels: {
			  enabled: false
			},
			stroke: {
			  curve: 'straight'
			},
			grid: {
			  row: {
			    colors: ['#f3f3f3', 'transparent'],
			    opacity: 0.5
			  },
			},
			xaxis: {
			  categories: ['Abril', 'Mayo', 'Junio', 'Julio'],
			}
			};

			var chart = new ApexCharts(document.querySelector(\"#charts2\"), options);
			chart.render();
		";

		// Interacciones Desglosado por Departamento
		$interaccionesDepartamento = [];

		$sth = $this->_db->query("
			SELECT COUNT(*) AS c, d.nombre
			FROM interacciones i
			JOIN empleados e
			ON e.id = i.id_remitente
			JOIN departamentos d
			ON d.id = e.id_departamento
			WHERE e.id_departamento IS NOT NULL AND i.status != 0 AND i.fecha_creacion BETWEEN '2022-05-01' AND '2022-05-31'
			GROUP BY e.id_departamento
			ORDER BY COUNT(*) DESC
		");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$interaccionesDepartamento[$datos['nombre']]['mayo'] = $datos['c'];
		}

		$sth = $this->_db->query("
			SELECT COUNT(*) AS c, d.nombre
			FROM interacciones i
			JOIN empleados e
			ON e.id = i.id_remitente
			JOIN departamentos d
			ON d.id = e.id_departamento
			WHERE e.id_departamento IS NOT NULL AND i.status != 0 AND i.fecha_creacion BETWEEN '2022-06-01' AND '2022-06-30'
			GROUP BY e.id_departamento
			ORDER BY COUNT(*) DESC
		");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$interaccionesDepartamento[$datos['nombre']]['junio'] = $datos['c'];
		}

		$sth = $this->_db->query("
			SELECT COUNT(*) AS c, d.nombre
			FROM interacciones i
			JOIN empleados e
			ON e.id = i.id_remitente
			JOIN departamentos d
			ON d.id = e.id_departamento
			WHERE e.id_departamento IS NOT NULL AND i.status != 0 AND i.fecha_creacion BETWEEN '2022-07-01' AND '2022-07-31'
			GROUP BY e.id_departamento
			ORDER BY COUNT(*) DESC
		");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$interaccionesDepartamento[$datos['nombre']]['julio'] = $datos['c'];
		}

		$jsData = [];
		foreach ($interaccionesDepartamento as $nombre => $c) {
			$jsData[$nombre] = $c;
		}

		// echo '<pre>' . var_export($jsData, true) . '</pre>';die;

		$jsData1 = '';
		foreach ($jsData as $nombre => $c) {
			$jsData1 .= '{name: \'' . $nombre . '\', data: [0, ' . $c['mayo'] . ', ' . $c['junio'] . ', ' . $c['julio'] . '] },';
		}
		$jsData1 = substr($jsData1, 0, -1);

		$html['js'] .= "
			var options = {
			  series: [$jsData1],
			  chart: {
			  type: 'bar',
			  height: 350,
			  stacked: true,
			  stackType: '100%',
			  toolbar: {
			      show: false
			    }
			},
			xaxis: {
			  categories: ['Abril', 'Mayo', 'Junio', 'Julio'],
			},
			fill: {
			  opacity: 1
			},
			legend: {
			  position: 'left',
			  offsetX: -20,
			},
			};

			var chart = new ApexCharts(document.querySelector(\"#charts3\"), options);
			chart.render();
	    ";

	    // Top interacciones
	    $usuariosTopGeneradas = [];

	    $sth = $this->_db->query("
	    	SELECT COUNT(*) AS c, CONCAT(e.nombre, ' ', e.apellidos) AS nombre, p.nombre AS puesto, e.foto
	    	FROM interacciones i
	    	JOIN empleados e
	    	ON e.id = i.id_remitente
	    	JOIN puestos p
	    	ON p.id = e.id_puesto
	    	GROUP BY CONCAT(e.nombre, ' ', e.apellidos)
	    	ORDER BY COUNT(*) DESC
	    	LIMIT 10
		");
	    if(!$sth->execute()) throw New Exception();
	    while ($datos = $sth->fetch()) {
	    	if (!$datos['foto']) {
				$foto = 'img/prop.png';
			} else {
				$foto = 'data/f/' . $datos['foto'];
			}

	    	$usuariosTopGeneradas[] = [
	    		'c' => $datos['c'],
	    		'nombre' => $datos['nombre'],
	    		'puesto' => $datos['puesto'],
	    		'foto' => $foto,
	    	];
	    }

	    $html['usuariosTopGeneradas'] = $usuariosTopGeneradas;

	    // Usuarios alta efectividad
	    $usuariosAltaEfectividad = [];

	    $sth = $this->_db->query("
	    	SELECT i.id_destinatario, CONCAT(e.nombre, ' ', e.apellidos) AS nombre, p.nombre AS puesto, i.fecha_creacion, i.fecha_cierre, e.foto
			FROM interacciones i
			JOIN empleados e
			ON e.id = i.id_destinatario
			JOIN puestos p
			ON p.id = e.id_puesto
			WHERE i.status = 4
		");
	    if(!$sth->execute()) throw New Exception();
	    while ($datos = $sth->fetch()) {
	    	$date1 = new DateTime($datos['fecha_creacion']);
			$date2 = new DateTime($datos['fecha_cierre']);
			$diff = $date2->diff($date1);

			$hours = $diff->h;
			$hours = $hours + ($diff->days*24);

			if (!array_key_exists($datos['nombre'], $usuariosAltaEfectividad)) {
				if (!$datos['foto']) {
					$foto = 'img/prop.png';
				} else {
					$foto = 'data/f/' . $datos['foto'];
				}

		    	$usuariosAltaEfectividad[$datos['nombre']] = [
		    		'nombre' => $datos['nombre'],
		    		'puesto' => $datos['puesto'],
		    		'horas' => [$hours],
		    		'foto' => $foto,
		    	];
		    } else {
				array_push($usuariosAltaEfectividad[$datos['nombre']]['horas'], $hours);
		    }
	    }

	    $usuariosAltaEfectividadHoras = [];
	    foreach ($usuariosAltaEfectividad as $k => $v) {
	    	$horasPromedio = array_sum($v['horas'])/count($v['horas']);
	    	$usuariosAltaEfectividad[$k]['horasPromedio'] = number_format($horasPromedio, 0);
	    }
	    foreach($usuariosAltaEfectividad as $k => $v) $usuariosAltaEfectividadHoras[$k] = $v['horasPromedio'];
	    asort($usuariosAltaEfectividadHoras);
	    $html['usuariosAltaEfectividadSort'] = $usuariosAltaEfectividadHoras;
	    $html['usuariosAltaEfectividad'] = $usuariosAltaEfectividad;

	    // Usuarios baja efectividad
	    $usuariosBajaEfectividad = [];

	    $sth = $this->_db->query("
	    	SELECT i.id_destinatario, CONCAT(e.nombre, ' ', e.apellidos) AS nombre, p.nombre AS puesto, i.fecha_creacion, i.fecha_cierre, e.foto
			FROM interacciones i
			JOIN empleados e
			ON e.id = i.id_destinatario
			JOIN puestos p
			ON p.id = e.id_puesto
			WHERE i.status = 4
		");
	    if(!$sth->execute()) throw New Exception();
	    while ($datos = $sth->fetch()) {
	    	$date1 = new DateTime($datos['fecha_creacion']);
			$date2 = new DateTime($datos['fecha_cierre']);
			$diff = $date2->diff($date1);

			$hours = $diff->h;
			$hours = $hours + ($diff->days*24);

			if (!array_key_exists($datos['nombre'], $usuariosBajaEfectividad)) {
				if (!$datos['foto']) {
					$foto = 'img/prop.png';
				} else {
					$foto = 'data/f/' . $datos['foto'];
				}

		    	$usuariosBajaEfectividad[$datos['nombre']] = [
		    		'nombre' => $datos['nombre'],
		    		'puesto' => $datos['puesto'],
		    		'horas' => [$hours],
		    		'foto' => $foto,
		    	];
		    } else {
				array_push($usuariosBajaEfectividad[$datos['nombre']]['horas'], $hours);
		    }
	    }

	    foreach ($usuariosBajaEfectividad as $k => $v) {
	    	$horasPromedio = array_sum($v['horas'])/count($v['horas']);
	    	$usuariosBajaEfectividad[$k]['horasPromedio'] = number_format($horasPromedio, 0);
	    }
	    foreach($usuariosBajaEfectividad as $k => $v) $usuariosBajaEfectividadHoras[$k] = $v['horasPromedio'];
	    arsort($usuariosBajaEfectividadHoras);
	    $html['usuariosBajaEfectividadSort'] = $usuariosBajaEfectividadHoras;
	    $html['usuariosBajaEfectividad'] = $usuariosBajaEfectividad;

		return $html;
	}

	public function agregarIndicador() {
		try {
			$id_responsable = $_POST['id_responsable'];
			$meta = $_POST['meta'];
			$indicador = mb_strtoupper($_POST['indicador']);
			$medicion = $_POST['medicion'];
			$revision = $_POST['revision'];

			$sth = $this->_db->prepare("INSERT INTO indicadores (id_responsable, meta, indicador, medicion, revision) VALUES (?, ?, ?, ?, ?)");
			$sth->bindParam(1, $id_responsable);
			$sth->bindParam(2, $meta);
			$sth->bindParam(3, $indicador);
			$sth->bindParam(4, $medicion);
			$sth->bindParam(5, $revision);
			if(!$sth->execute()) throw New Exception();
			$idIndicador = $this->_db->lastInsertId();

			if (!$_FILES['archivo']['size'] == 0) {
				require APP . 'inc/class.upload.php';
				
				$handle = new upload($_FILES['archivo']);
				if ($handle->uploaded) {
					$archivo = str_replace(' ', '_', $handle->file_src_name_body) . '-' . time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/archivos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $idIndicador);
				$sth = $this->_db->prepare("UPDATE indicadores SET procedimiento = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

	  		header('Location: ' . STASIS . '/movimientos/procesos/nuevo_indicador/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function aplicarCambiosIndicador() {
		try {
			$id = $_POST['id'];
			$id_responsable = $_POST['id_responsable'];
			$meta = $_POST['meta'];
			$indicador = mb_strtoupper($_POST['indicador']);
			$medicion = $_POST['medicion'];
			$revision = $_POST['revision'];

			$sth = $this->_db->prepare("
				UPDATE indicadores SET
				id_responsable = ?,
				meta = ?,
				indicador = ?,
				medicion = ?,
				revision = ?
				WHERE id = ?");
			$sth->bindParam(1, $id_responsable);
			$sth->bindParam(2, $meta);
			$sth->bindParam(3, $indicador);
			$sth->bindParam(4, $medicion);
			$sth->bindParam(5, $revision);
			$sth->bindParam(6, $id);
			if(!$sth->execute()) throw New Exception();
			$idIndicador = $this->_db->lastInsertId();

			if (!$_FILES['archivo']['size'] == 0) {
				require APP . 'inc/class.upload.php';
				
				$handle = new upload($_FILES['archivo']);
				if ($handle->uploaded) {
					$archivo = str_replace(' ', '_', $handle->file_src_name_body) . '-' . time();
					$handle->file_new_name_body   = $archivo;
					$archivoDb = $archivo . '.' . $handle->file_src_name_ext;
					$handle->process(ROOT_DIR . 'data/privada/archivos/');
					if ($handle->processed) {
						$handle->clean();
					}
				}

				$arregloDatos = array($archivoDb, $idIndicador);
				$sth = $this->_db->prepare("UPDATE indicadores SET procedimiento = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

			$this->mensajes[] = Modelos_Sistema::status(2, 'Indicador editado exitosamente.');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function editarIndicador($id) {
		try {
			$datosArray = array();

			$sth = $this->_db->prepare("
				SELECT i.id, i.id_responsable, i.meta, i.procedimiento, i.indicador, i.medicion, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, p.nombre AS puesto, d.nombre AS departamento, i.revision, i.motivo
				FROM indicadores i
				JOIN empleados e
				ON e.id = i.id_responsable
				JOIN puestos p
				ON p.id = e.id_puesto
				JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE i.id = ?
			");
			$sth->setFetchMode(PDO::FETCH_INTO, $this);
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$sth->fetch();

	  		return $this;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoIndicadores() {
		try {
			$datosVista = array();

			$sth = $this->_db->prepare("
				SELECT i.id, i.procedimiento, i.indicador, i.medicion, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, p.nombre AS puesto, d.nombre AS departamento, i.revision
				FROM indicadores i
				JOIN empleados e
				ON e.id = i.id_responsable
				JOIN puestos p
				ON p.id = e.id_puesto
				JOIN departamentos d
				ON d.id = e.id_departamento
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

			while ($datos = $sth->fetch()) {
				if ($datos['medicion'] == 1) {
					$medicion = 'SEMANAL';
				} elseif ($datos['medicion'] == 2) {
					$medicion = 'MENSUAL';
				}

				$arreglo = array(
					'id' => $datos['id'],
					'procedimiento' => $datos['procedimiento'],
					'indicador' => $datos['indicador'],
					'responsable' => $datos['responsable'],
					'medicion' => $medicion,
					'puesto' => $datos['puesto'],
					'departamento' => $datos['puesto'],
					'revision' => $datos['revision'],
				);

				$datosVista['activos'][] = $arreglo;
				$x++;
			}

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
}