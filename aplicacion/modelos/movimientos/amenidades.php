<?php
final class Modelos_Movimientos_Amenidades extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function agregar() {
		try {
			$id_destinatario = $_POST['id_destinatario'];
			$titulo = $_POST['titulo'];
			$mensaje = $_POST['mensaje'];
			$prioridad = $_POST['prioridad'];
			$archivo = $_POST['archivo'];

			$bytes = random_bytes(10);
			$uniqueId = bin2hex($bytes);

			$sth = $this->_db->prepare("INSERT INTO interacciones (id_remitente, id_destinatario, titulo, mensaje, prioridad, fecha_creacion, uniqueid) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $id_destinatario);
			$sth->bindParam(3, $titulo);
			$sth->bindParam(4, $mensaje);
			$sth->bindParam(5, $prioridad);
			$sth->bindParam(6, $uniqueId);
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

	  		header('Location: ' . STASIS . '/movimientos/procesos/generar/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

    public function listado() {
		try {
			function differenceInHours($startdate,$enddate){
				$starttimestamp = strtotime($startdate);
				$endtimestamp = strtotime($enddate);
				$difference = ($endtimestamp - $starttimestamp)/3600;
				return (int)$difference;
			}

			$fechaActual = new DateTime(date('Y-m-d 00:00:00'));

			$fechaInicio = new DateTime();
			$fechaInicio = $fechaInicio->format('Y-m-d H:i:s');
			$datosVista = array();

			// Pendientes
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion
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
				$fechaFin = new DateTime($datos['fecha_creacion']);
				$fechaFin = $fechaFin->add(new DateInterval('P1D'));
				$fechaFin = $fechaFin->format('Y-m-d H:i:s');
				$horas = differenceInHours($fechaInicio, $fechaFin);

				switch ($datos['prioridad']) {
					case 1: $prioridad = 'BAJA'; break;
					case 2: $prioridad = 'MEDIA'; break;
					case 3: $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
				);

				$datosVista['pendientes'][] = $arreglo;
				$x++;
			}
			$datosVista['nPendientes'] = $x;

			// Proceso
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion
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
				$fechaFin = new DateTime($datos['fecha_creacion']);
				$fechaFin = $fechaFin->add(new DateInterval('P1D'));
				$fechaFin = $fechaFin->format('Y-m-d H:i:s');
				$horas = differenceInHours($fechaInicio, $fechaFin);

				switch ($datos['prioridad']) {
					case 1: $prioridad = 'BAJA'; break;
					case 2: $prioridad = 'MEDIA'; break;
					case 3: $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
				);

				$datosVista['procesando'][] = $arreglo;
				$x++;
			}
			$datosVista['nProcesando'] = $x;

			// Atendidas
			$sth = $this->_db->prepare("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion
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
				$fechaFin = new DateTime($datos['fecha_creacion']);
				$fechaFin = $fechaFin->add(new DateInterval('P1D'));
				$fechaFin = $fechaFin->format('Y-m-d H:i:s');
				$horas = differenceInHours($fechaInicio, $fechaFin);

				switch ($datos['prioridad']) {
					case 1: $prioridad = 'BAJA'; break;
					case 2: $prioridad = 'MEDIA'; break;
					case 3: $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'remitente' => $datos['remitente'],
					'destinatario' => $datos['destinatario'],
					'titulo' => $datos['titulo'],
					'prioridad' => $prioridad,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
				);

				$datosVista['completadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nCompletadas'] = $x;

			// Canceladas
			$sth = $this->_db->query("
				SELECT i.id, i.uniqueid, CONCAT(er.nombre, ' ', er.apellidos) AS remitente, CONCAT(ed.nombre, ' ', ed.apellidos) AS destinatario, i.titulo, i.prioridad, i.fecha_creacion
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
				$fechaFin = new DateTime($datos['fecha_creacion']);
				$fechaFin = $fechaFin->add(new DateInterval('P1D'));
				$fechaFin = $fechaFin->format('Y-m-d H:i:s');
				$horas = differenceInHours($fechaInicio, $fechaFin);

				switch ($datos['prioridad']) {
					case 1: $prioridad = 'BAJA'; break;
					case 2: $prioridad = 'MEDIA'; break;
					case 3: $prioridad = 'ALTA'; break;
				}

				$arreglo = array(
					'id' => $datos['id'],
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
}