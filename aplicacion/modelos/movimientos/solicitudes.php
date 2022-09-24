<?php
final class Modelos_Movimientos_Solicitudes extends Modelo {
	protected $_db = null;
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
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

			// Responsable
			if ($_SESSION['login_tipo'] == 5) {
				if ($_SESSION['login_centro_trabajo'] == 51) {
					$qryRevision = 'AND so.id_responsable_cobranza = ' . $_SESSION['login_id'];
				} else {
					$qry = 'AND so.id_responsable = ' . $_SESSION['login_id'];
				}
			} elseif ($_SESSION['login_tipo'] == 2 && $_SESSION['login_id_departamento'] == 4 && $_SESSION['login_centro_trabajo'] == 'PROYECTOS Y OBRA') {
				$qryRevision = '';
				$qry = 'AND so.id_responsable = 1277';
			} else {
				$qry = '';
				$qryRevision = '';
			}

			// Pendientes
			$sth = $this->_db->query("
				SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.otro
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				WHERE so.status = 0 $qry
				ORDER BY so.id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

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

				$fechaFin = new DateTime($datos['fecha_creacion']);
				$fechaFin = $fechaFin->add(new DateInterval('P1D'));
				$fechaFin = $fechaFin->format('Y-m-d H:i:s');
				$horas = differenceInHours($fechaInicio, $fechaFin);

				if ($horas >= 13) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($horas >= 3 && $horas <= 11) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($horas <= 2) {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				if ($horas <= 0) {
					$tiempoRestante = 'VENCIDA';
				} else {
					$tiempoRestante = $horas . ' horas';
				}

				if (!$datos['servicio']) {
					$servicio = mb_strtoupper($datos['otro']);
				} else {
					$servicio = $datos['servicio'];
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'propietario' => $datos['propietario'],
					'lote' => $lote,
					'no_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT),
					'servicio' => $servicio,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'tiempo_restante' => $tiempoRestante,
					'icono' => $icono,
					'color' => $color,
				);

				if ($tiempoRestante != 'VENCIDA') {
					$datosVista['pendientes'][] = $arreglo;
					$x++;
				} else {
					$datosVista['noAtendidas'][] = $arreglo;
					$y++;
				}
			}
			$datosVista['nPendientes'] = $x;
			$datosVista['nNoAtendidas'] = $y;

			// En Revision
			$sth = $this->_db->prepare("
				SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.otro, so.fecha_revision, CONCAT(e.nombre, ' ', e.apellidos) AS responsable
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				JOIN empleados e
				ON e.id = so.id_responsable_cobranza
				WHERE so.status = 9
				ORDER BY so.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			$y = 0;

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

				$fechaFin = new DateTime($datos['fecha_creacion']);
				$fechaFin = $fechaFin->add(new DateInterval('P1D'));
				$fechaFin = $fechaFin->format('Y-m-d H:i:s');
				$horas = differenceInHours($fechaInicio, $fechaFin);

				if ($horas >= 13) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($horas >= 3 && $horas <= 11) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($horas <= 2) {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				if ($horas <= 0) {
					$tiempoRestante = 'VENCIDA';
				} else {
					$tiempoRestante = $horas . ' horas';
				}

				if (!$datos['servicio']) {
					$servicio = mb_strtoupper($datos['otro']);
				} else {
					$servicio = $datos['servicio'];
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'propietario' => $datos['propietario'],
					'responsable' => $datos['responsable'],
					'lote' => $lote,
					'no_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT),
					'servicio' => $servicio,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_revision' => Modelos_Fecha::formatearFechaHora($datos['fecha_revision']),
					'icono' => $icono,
					'color' => $color,
				);

				$datosVista['revision'][] = $arreglo;
				$x++;
			}
			$datosVista['nRevision'] = $x;

			// Autorizadas
			$sth = $this->_db->query("
				SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.otro, so.fecha_autorizada, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, so.id_propietario
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				JOIN empleados e
				ON e.id = so.id_responsable
				WHERE so.status = 1 $qry
				ORDER BY so.id DESC
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

				$fechaFin = new DateTime($datos['fecha_autorizada']);
				$fechaFin = $fechaFin->add(new DateInterval('P1D'));
				$fechaFin = $fechaFin->format('Y-m-d H:i:s');
				$horas = differenceInHours($fechaInicio, $fechaFin);

				if ($horas >= 13) {
					$icono = 'icono-activar.png';
					$color = '#AFE5AF';
				} elseif ($horas >= 3 && $horas <= 11) {
					$icono = 'icono-alerta_amarillo.png';
					$color = '#FFFAC1';
				} elseif ($horas <= 2) {
					$icono = 'icono-advertencia.png';
					$color = '#FFB4AA';
				}

				if ($horas <= 0) {
					$tiempoRestante = 'VENCIDA';
				} else {
					$tiempoRestante = $horas . ' horas';
				}

				if (!$datos['servicio']) {
					$servicio = mb_strtoupper($datos['otro']);
				} else {
					$servicio = $datos['servicio'];
				}

				$query_ok=0;
				$data_id_propietario=$datos['id_propietario'];

				$sth_cotizaciones = $this->_db->query("
					SELECT c.id, c.id_agente, c.id_cliente, c.id_solicitante, c.id_integracion, c.telefono1, c.telefono2, c.correo, c.moneda, c.vigencia, c.subtotal
					FROM cotizaciones c
					WHERE c.id_cliente = $data_id_propietario
					ORDER BY c.id DESC
				");
				if(!$sth_cotizaciones->execute()){
					$query_ok=0;
					throw New Exception();
				}else{
					$query_ok=1;
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'propietario' => $datos['propietario'],
					'responsable' => $datos['responsable'],
					'lote' => $lote,
					'no_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT),
					'servicio' => $servicio,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_autorizada' => Modelos_Fecha::formatearFechaHora($datos['fecha_autorizada']),
					'tiempo_restante' => $tiempoRestante,
					'icono' => $icono,
					'color' => $color,
					'data_cotizaciones' => $query_ok
				);

				$datosVista['autorizadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nAutorizadas'] = $x;

			// Proceso
			$sth = $this->_db->query("
				SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.otro, so.fecha_autorizada, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, so.fecha_compromiso
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				JOIN empleados e
				ON e.id = so.id_responsable
				WHERE so.status = 2 $qry
				ORDER BY so.id DESC
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

				$fechaVencimiento = new DateTime($datos['fecha_compromiso']);
				$diasVencidos = $fechaActual->diff($fechaVencimiento);
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

				if (!$datos['servicio']) {
					$servicio = mb_strtoupper($datos['otro']);
				} else {
					$servicio = $datos['servicio'];
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'propietario' => $datos['propietario'],
					'responsable' => $datos['responsable'],
					'lote' => $lote,
					'no_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT),
					'servicio' => $servicio,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_autorizada' => Modelos_Fecha::formatearFechaHora($datos['fecha_autorizada']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'dias_restantes' => $status,
					'icono' => $icono,
					'color' => $color,
				);

				$datosVista['procesando'][] = $arreglo;
				$x++;
			}
			$datosVista['nProcesando'] = $x;

			// Finalizadas
			$sth = $this->_db->query("
				SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.otro, so.fecha_autorizada, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, so.fecha_finalizada
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				JOIN empleados e
				ON e.id = so.id_responsable
				WHERE so.status = 3 $qry
				ORDER BY so.id DESC
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

				if (!$datos['servicio']) {
					$servicio = mb_strtoupper($datos['otro']);
				} else {
					$servicio = $datos['servicio'];
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'propietario' => $datos['propietario'],
					'responsable' => $datos['responsable'],
					'lote' => $lote,
					'no_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT),
					'servicio' => $servicio,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_autorizada' => Modelos_Fecha::formatearFechaHora($datos['fecha_autorizada']),
					'fecha_finalizada' => Modelos_Fecha::formatearFechaHora($datos['fecha_finalizada']),
				);

				$datosVista['finalizadas'][] = $arreglo;
				$x++;
			}
			$datosVista['nFinalizadas'] = $x;

			// Atendidas
			$sth = $this->_db->query("
				SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.otro, so.fecha_autorizada, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, so.fecha_finalizada, so.fecha_atendida, so.fecha_compromiso
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				JOIN empleados e
				ON e.id = so.id_responsable
				WHERE so.status = 4 $qry
				ORDER BY so.id DESC
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

				if (!$datos['servicio']) {
					$servicio = mb_strtoupper($datos['otro']);
				} else {
					$servicio = $datos['servicio'];
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'propietario' => $datos['propietario'],
					'responsable' => $datos['responsable'],
					'lote' => $lote,
					'no_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT),
					'servicio' => $servicio,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_autorizada' => Modelos_Fecha::formatearFechaHora($datos['fecha_autorizada']),
					'fecha_compromiso' => Modelos_Fecha::formatearFecha($datos['fecha_compromiso']),
					'fecha_finalizada' => Modelos_Fecha::formatearFechaHora($datos['fecha_finalizada']),
					'fecha_atendida' => Modelos_Fecha::formatearFechaHora($datos['fecha_atendida']),
				);

				$datosVista['atendidas'][] = $arreglo;
				$x++;
			}
			$datosVista['nAtendidas'] = $x;

			// Canceladas
			$sth = $this->_db->query("
				SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.otro, so.fecha_cancelada
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				WHERE so.status = -1 $qry
				ORDER BY so.id DESC
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

				$fechaVencimiento = new DateTime($datos['fecha_compromiso']);
				$diasVencidos = $fechaActual->diff($fechaVencimiento);
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

				if (!$datos['servicio']) {
					$servicio = mb_strtoupper($datos['otro']);
				} else {
					$servicio = $datos['servicio'];
				}

				$arreglo = array(
					'id' => $datos['id'],
					'uniqueid' => $datos['uniqueid'],
					'propietario' => $datos['propietario'],
					'responsable' => $datos['responsable'],
					'lote' => $lote,
					'no_solicitud' => $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT),
					'servicio' => $servicio,
					'fecha_creacion' => Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']),
					'fecha_cancelada' => Modelos_Fecha::formatearFechaHora($datos['fecha_cancelada']),
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
				SELECT so.id, p.nombre AS propietario, p.lote, so.tipo, se.nombre AS servicio, so.fecha_creacion, so.descripcion, so.otro
				FROM solicitudes so
				LEFT JOIN servicios se
				ON se.id = so.id_servicio
				JOIN propietarios p
				ON p.id = so.id_propietario
				WHERE so.id = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

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

			$datosArray['id'] = $id;
			$datosArray['no_solicitud'] = $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT);
			$datosArray['propietario'] = $datos['propietario'];
			$datosArray['lote'] = str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
			$datosArray['tipo'] = $tipo;
			$datosArray['servicio'] = $servicio;
			$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);
			$datosArray['descripcion'] = $datos['descripcion'];

			$archivosArray = [];
			$sth = $this->_db->prepare("
				SELECT archivo
				FROM solicitudes_archivos
				WHERE id_solicitud = ?
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			while ($datos = $sth->fetch()) {
				$archivosArray[] = $datos['archivo'];
			}
			$datosArray['archivos'] = $archivosArray;

			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function autorizar() {
		try {
			$id = $_POST['id'];
			$id_responsable = $_POST['id_responsable'];
			$id_cobranza_responsable = $_POST['id_cobranza_responsable'];
			$corriente = $_POST['corriente'];

			if ($corriente == 1) {
				$sth = $this->_db->prepare("UPDATE solicitudes SET status = 1, fecha_autorizada = NOW(), id_responsable = ?, id_autorizado = ? WHERE id = ?");
				$sth->bindParam(1, $id_responsable);
				$sth->bindParam(2, $_SESSION['login_id']);
				$sth->bindParam(3, $id);
				if(!$sth->execute()) throw New Exception();

		  		header('Location: ' . STASIS . '/movimientos/solicitudes/reporte/1');
		  	} else {
		  		$sth = $this->_db->prepare("UPDATE solicitudes SET status = 9, fecha_revision = NOW(), id_responsable_cobranza = ? WHERE id = ?");
				$sth->bindParam(1, $id_cobranza_responsable);
				$sth->bindParam(2, $id);
				if(!$sth->execute()) throw New Exception();

		  		header('Location: ' . STASIS . '/movimientos/solicitudes/reporte/6');
		  	}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function finalizar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE solicitudes SET status = 3, fecha_finalizada = NOW() WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

	  		header('Location: ' . STASIS . '/movimientos/solicitudes/reporte/4');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function liberar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE solicitudes SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

	  		header('Location: ' . STASIS . '/movimientos/solicitudes/reporte/7');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function procesar() {
		try {
			$id = $_POST['id'];
			$id_responsable = $_POST['id_responsable'];

			$fecha_compromiso = DateTime::createFromFormat('d/m/Y', $_POST['fecha_entrega']);
			$fecha_compromiso = $fecha_compromiso->format('Y-m-d');

			$sth = $this->_db->prepare("UPDATE solicitudes SET status = 2, fecha_compromiso = ? WHERE id = ?");
			$sth->bindParam(1, $fecha_compromiso);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();

	  		header('Location: ' . STASIS . '/movimientos/solicitudes/reporte/2');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function comentario() {
		try {
			$id = $_POST['id'];
			$comentario = $_POST['comentario'];

			$sth = $this->_db->prepare("INSERT INTO solicitudes_comentarios (id_solicitud, id_usuario, comentario, fecha) VALUES (?, ?, ?, NOW())");
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
				$sth = $this->_db->prepare("UPDATE solicitudes_comentarios SET archivo = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}


			// Enviar Correo
			$correo = Modelos_Contenedor::crearModelo('Correo');
			$nombrePdf = $this->pdfCorreo($id);
			$correo->solicitudComentario($id, $comentario, $nombrePdf);

	  		header('Location: ' . STASIS . '/movimientos/solicitudes/reporte/3');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cerrar() {
		try {
			$id = $_POST['id'];
			$comentario = $_POST['comentario'];

			$sth = $this->_db->prepare("UPDATE solicitudes SET conclusion = ?, fecha_atendida = NOW(), status = 4 WHERE id = ?");
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
				$sth = $this->_db->prepare("UPDATE solicitudes SET conclusion_archivo = ? WHERE id = ?");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

	  		header('Location: ' . STASIS . '/movimientos/solicitudes/reporte/5');
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
			SELECT so.id, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.fecha_autorizada, so.fecha_compromiso, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, d.nombre AS departamento, e.email, e.telefono, so.descripcion, e.foto, so.fecha_atendida, so.conclusion, CONCAT(a.nombre, ' ', a.apellidos) AS administrador, so.motivo_cancelacion, so.otro, so.conclusion_archivo
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
			WHERE so.uniqueid = ?
			ORDER BY so.id DESC
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		if (!$datos) die;

		$id = $datos['id'];
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

		$no_solicitud = $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT);
		$propietario = $datos['propietario'];
		$lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
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
			$fechaAtendidaFormatted = utf8_encode(ucfirst(strftime("%A %d de %B del %Y a las %H:%M hrs", $fechaAtendidaDateTime)));
		} else {
			$fecha_atendida = '';
		}

		$descripcion = $datos['descripcion'];

		// Archivos de solicitud
		$solicitudArchivos = '';
		$sth2 = $this->_db->prepare("SELECT archivo FROM solicitudes_archivos WHERE id_solicitud = ?");
		$sth2->bindParam(1, $id);
		if(!$sth2->execute()) throw New Exception();

		$x = 0;
		while ($datos2 = $sth2->fetch()) {
			if ($x == 0) $solicitudArchivos .= '<br /><br />';
			$solicitudArchivos .= 'Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/atencion/data/privada/archivos/' . $datos2['archivo'] . '">' . $datos2['archivo'] . '</a><br />';
			$x++;
		}
		
		$descripcion .= $solicitudArchivos;
		// Fin archivos descripcion

		$status = $datos['status'];
		
		$responsable = $datos['responsable'];
		$departamento = $datos['departamento'];
		$email = $datos['email'];
		$telefono = $datos['telefono'];

		if (!$datos['foto']) {
			$foto = 'img/prop.png';
		} else {
			$foto = 'data/f/' . $datos['foto'];
		}
		$conclusion = $datos['conclusion'];
		$administrador = $datos['administrador'];
		$conclusion_archivo = $datos['conclusion_archivo'];

		// Comentarios
		$sth = $this->_db->prepare("
			SELECT COUNT(s.id)
			FROM solicitudes_comentarios s
			LEFT JOIN empleados e
			ON e.id = s.id_usuario
			WHERE s.id_solicitud = ?
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
				<div style="text-align: center; font-size: 9px;">
					<span style="font-weight: bold; text-align: center; font-size: 10px;">BITÁCORA DE SEGUIMIENTO</span><br />
				</div>
				<table style="text-align: left; font-size: 8px;" cellpadding="0" border="0">
			';

			$sth = $this->_db->prepare("
				SELECT s.comentario, s.fecha, CONCAT(e.nombre, ' ', e.apellidos) AS usuario, s.fecha, e.foto, p.nombre AS puesto, s.archivo
				FROM solicitudes_comentarios s
				LEFT JOIN empleados e
				ON e.id = s.id_usuario
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				WHERE s.id_solicitud = ?
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
						$archivo = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/atencion/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a>';
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
			if (!empty($responsable)) {
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

						<div style="text-align: center; font-size: 9px;">
							<span style="font-weight: bold; text-align: center; font-size: 10px;">CONCLUSIÓN</span>
						</div>

						<div style="background-color: #DBDECE; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">' . $conclusion . '</span><br /><br /><img src="' . STASIS . '/img/guirnalda.png" height="20" /><br />Atentamente:<b><br />' . $administrador . '<br />' . $fechaAtendidaFormatted . '</b>' . $archivoConclusion . '<br /></div>
					';
				// Si hay fecha compromiso
				} else {
					if (!empty($fecha_compromiso)) {
						$fechaCompromisoDateTime = new DateTime($fecha_compromiso);
						$fechaCompromisoDateTime = $fechaCompromisoDateTime->getTimestamp();
						$fechaCompromisoFormatteada = ucfirst(utf8_encode(strftime("%A %d de %B, %Y", $fechaCompromisoDateTime)));

						$htmlCompromiso = '
							<div style="background-color: #7FAA41; color: #FFF; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFranciscoBold\';">Fecha Estimada de Entrega:</span><br />' . $fechaCompromisoFormatteada . '<br /></div>
						';
					} else {
						$htmlCompromiso = '<div style="background-color: #C4DEED; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">Está por determinarse la fecha estimada de entrega por el reponsable acorde a lo solicitado.<br />Asignaremos la fecha en un periodo máximo de 24 horas.</span><br /></div>';
					}
				}

				$htmlResponsable = '
					<br />
					<table style="border: 2px solid #DDDCDD;">
					</table>
					<br />
					
					<div style="text-align: center; font-size: 9px;">
						<span style="font-weight: bold; text-align: center; font-size: 10px;">NOMBRE DEL RESPONSABLE</span>
					</div>

					<table>
						<tr>
							<td style="width: 15%; text-align: center;">
								<img src="' . STASIS . '/' . $foto . '" height="60" />
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
										<td style="text-align: center;">' . $responsable . '</td>
										<td style="text-align: center;">' . $departamento . '</td>
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
										<td style="text-align: center;">' . $telefono . '</td>
										<td style="text-align: center;">' . $email . '</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<br />
					' . $htmlCompromiso . '
					' . $htmlComentarios . '
				';
			} else {
				$htmlResponsable = '
					<div style="background-color: #C4DEED; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">La solicitud será revisada en un periodo máximo de 24 horas a partir del momento de su creación.</span><br /></div>
				';
			}
		} else {
			$htmlResponsable = '
				<div style="background-color: #FFBCC6; width: 300px; text-align: center;"><br /><span style="font-family: \'SanFrancisco\';">Solicitud cancelada por propietario con el siguiente motivo de cancelación:<br/><br/>' . $motivo_cancelacion . '</span><br /></div>
			';
		}

		switch($status) {
			case 0: $statusHtml = '<img src="' . STASIS . '/img/s-success.png" height="7" /> Pendiente'; break;
			case 1: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Autorizada'; break;
			case 2: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Procesando'; break;
			case 3: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Procesando'; break;
			case 4: $statusHtml = '<img src="' . STASIS . '/img/s-info.png" height="7" /> Atendida'; break;
			case -1: $statusHtml = '<img src="' . STASIS . '/img/s-danger.png" height="7" /> Cancelada'; break;
			case 9: $statusHtml = '<img src="' . STASIS . '/img/s-warning.png" height="7" /> En Revisión'; break;
		}

		$stasis = STASIS;
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Bold.ttf', 'TrueTypeUnicode', '', 96);
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Regular.ttf', 'TrueTypeUnicode', '', 96);

		$html = <<<EOF
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 250px; color: #444;">
						<img src="$stasis/img/rtecate.png" height="64" />
					</td>
					<td style="width: 213px; text-align: right; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">SOLICITUD DE PROPIETARIO</span><br /><br />
						<span style="font-size: 9px;">No. Solicitud: $no_solicitud<br />Fecha: $fecha_creacion</span><br />
						<span style="font-size: 9px;">$statusHtml</span>
					</td>
					<td style="width: 75px; text-align: right;">
						<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=https://saevalcas.mx/movimientos/solicitudes/visualizar/$uniqueId&chld=H|0" height="65">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />

			<table style="text-align: left; font-size: 8px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Propietario:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 10%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Lote:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 15%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Solicitud:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 40%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Servicio:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$propietario</td>
					<td style="text-align: center;">$lote</td>
					<td style="text-align: center;">$tipo</td>
					<td style="text-align: center;">$servicio</td>
				</tr>
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 537px">
						<span style="text-align: center; font-family: \'SanFranciscoBold\';">Descripción Detallada y Observaciones del Servicio:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$descripcion</td>
				</tr>
			</table>
			<br />

			$htmlResponsable
EOF;
		$fechaPdf = date('d-m-Y');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();
		$pdf->Output("Solicitud_{$no_solicitud}_{$fechaPdf}.pdf", 'I');
	}

	public function pdfCorreo($id) {
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

		$sth = $this->_db->prepare("
			SELECT so.id, so.uniqueid, p.nombre AS propietario, p.lote, p.manzana, p.seccion, so.tipo, se.nombre AS servicio, so.status, so.fecha_creacion, so.fecha_autorizada, so.fecha_compromiso, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, d.nombre AS departamento, e.email, e.telefono, so.descripcion, e.foto, so.fecha_atendida, so.conclusion, CONCAT(a.nombre, ' ', a.apellidos) AS administrador, so.motivo_cancelacion, so.otro, so.conclusion_archivo
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
		
		$uniqueId = $datos['uniqueid'];

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

		$no_solicitud = $datos['tipo'] . '-' . str_pad($datos['id'], 5, '0', STR_PAD_LEFT);
		$propietario = $datos['propietario'];
		$lote = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
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
			$fechaAtendidaFormatted = utf8_encode(ucfirst(strftime("%A %d de %B del %Y a las %H:%M hrs", $fechaAtendidaDateTime)));
		} else {
			$fecha_atendida = '';
		}

		$descripcion = $datos['descripcion'];

		// Archivos de solicitud
		$solicitudArchivos = '';
		$sth2 = $this->_db->prepare("SELECT archivo FROM solicitudes_archivos WHERE id_solicitud = ?");
		$sth2->bindParam(1, $id);
		if(!$sth2->execute()) throw New Exception();

		$x = 0;
		while ($datos2 = $sth2->fetch()) {
			if ($x == 0) $solicitudArchivos .= '<br /><br />';
			$solicitudArchivos .= 'Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/atencion/data/privada/archivos/' . $datos2['archivo'] . '">' . $datos2['archivo'] . '</a><br />';
			$x++;
		}
		
		$descripcion .= $solicitudArchivos;

		$status = $datos['status'];
		$responsable = $datos['responsable'];
		$departamento = $datos['departamento'];
		$email = $datos['email'];
		$telefono = $datos['telefono'];

		if (!$datos['foto']) {
			$foto = 'img/prop.png';
		} else {
			$foto = 'data/f/' . $datos['foto'];
		}
		$conclusion = $datos['conclusion'];
		$administrador = $datos['administrador'];
		$conclusion_archivo = $datos['conclusion_archivo'];

		// Comentarios
		$sth = $this->_db->prepare("
			SELECT COUNT(s.id)
			FROM solicitudes_comentarios s
			LEFT JOIN empleados e
			ON e.id = s.id_usuario
			WHERE s.id_solicitud = ?
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
				<div style="text-align: center; font-size: 9px;">
					<span style="font-weight: bold; text-align: center; font-size: 10px;">BITÁCORA DE SEGUIMIENTO</span><br />
				</div>
				<table style="text-align: left; font-size: 8px;" cellpadding="0" border="0">
			';

			$sth = $this->_db->prepare("
				SELECT s.comentario, s.fecha, CONCAT(e.nombre, ' ', e.apellidos) AS usuario, s.fecha, e.foto, p.nombre AS puesto, s.archivo
				FROM solicitudes_comentarios s
				LEFT JOIN empleados e
				ON e.id = s.id_usuario
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				WHERE s.id_solicitud = ?
				ORDER BY s.fecha DESC
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			while ($datos = $sth->fetch()) {
				$fechaComentario = Modelos_Fecha::formatearFechaHora($datos['fecha']);
				if (!$datos['usuario']) {
					$usuario = $propietario . ' (PROPIETARIO)';
					$fotoComentario = '<img src="' . STASIS . '/img/prop.png" height="30" />';

					if ($datos['archivo']) {
						$archivo = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/atencion/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a>';
					} else {
						$archivo = '';
					}
				} else {
					$fotoComentario = '<img src="' . STASIS . '/' . $foto . '" height="30" />';
					$usuario = $datos['usuario'] . ' (' . $datos['puesto'] . ')';

					if ($datos['archivo']) {
						$archivo = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $datos['archivo'] . '">' . $datos['archivo'] . '</a>';
					} else {
						$archivo = '';
					}
				}

				$htmlComentarios .= '
					<tr>
						<td style="width: 9%; text-align: center;" rowspan="2">' . $fotoComentario . '</td>
						<td style="background-color: #EAEAEA; color: #000; width: 91%"><span style="line-height: 2; font-family: \'\';">' . $usuario . '</span> | ' . $fechaComentario . '</td>
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
			if (!empty($responsable)) {
				// Si ya se atendio
				if (!empty($fecha_atendida)) {

					if ($conclusion_archivo) {
						$archivoConclusion = '<br /><br />Archivo adjunto: <img src="' . STASIS . '/img/link.png" height="7" />&nbsp;<a href="https://saevalcas.mx/data/privada/archivos/' . $conclusion_archivo . '">' . $conclusion_archivo . '</a>';
					} else {
						$archivoConclusion = '';
					}

					$htmlCompromiso = '
						<br />
						<table style="border: 2px solid #DDDCDD;">
						</table>
						<br />

						<div style="text-align: center; font-size: 9px;">
							<span style="font-weight: bold; text-align: center; font-size: 10px;">CONCLUSIÓN</span>
						</div>

						<div style="background-color: #DBDECE; width: 300px; text-align: center;"><br /><span style="font-family: \'\';">' . $conclusion . '</span>' . $archivoConclusion . '<br /><br /><img src="' . STASIS . '/img/guirnalda.png" height="20" /><br />Atentamente:<b><br />' . $administrador . '<br />' . $fechaAtendidaFormatted . '</b><br /></div>
					';
				// Si hay fecha compromiso
				} else {
					if (!empty($fecha_compromiso)) {
						$fechaCompromisoDateTime = new DateTime($fecha_compromiso);
						$fechaCompromisoDateTime = $fechaCompromisoDateTime->getTimestamp();
						$fechaCompromisoFormatteada = ucfirst(utf8_encode(strftime("%A %d de %B, %Y", $fechaCompromisoDateTime)));

						$htmlCompromiso = '
							<div style="background-color: #7FAA41; color: #FFF; width: 300px; text-align: center;"><br /><span style="font-family: \'\';">Fecha Estimada de Entrega:</span><br />' . $fechaCompromisoFormatteada . '<br /></div>
						';
					} else {
						$htmlCompromiso = '<div style="background-color: #C4DEED; width: 300px; text-align: center;"><br /><span style="font-family: \'\';">Está por determinarse la fecha estimada de entrega por el reponsable acorde a lo solicitado.<br />Asignaremos la fecha en un periodo máximo de 24 horas.</span><br /></div>';
					}
				}

				$htmlResponsable = '
					<br />
					<table style="border: 2px solid #DDDCDD;">
					</table>
					<br />
					
					<div style="text-align: center; font-size: 9px;">
						<span style="font-weight: bold; text-align: center; font-size: 10px;">NOMBRE DEL RESPONSABLE</span>
					</div>

					<table>
						<tr>
							<td style="width: 15%">
								<img src="' . STASIS . '/' . $foto . '" height="55" />
							</td>
							<td style="width: 85%">
								<table style="text-align: left; font-size: 8px;" cellpadding="2" cellspacing="1">
									<tr>
										<td style="background-color: #00436C; color: #FFF; width: 50%">
											<span style="text-align: center; font-family: \'\';">Nombre:</strong>
										</td>
										<td style="background-color: #00436C; color: #FFF; width: 50%">
											<span style="text-align: center; font-family: \'\';">Departamento:</strong>
										</td>
									</tr>
									<tr>
										<td style="text-align: center;">' . $responsable . '</td>
										<td style="text-align: center;">' . $departamento . '</td>
									</tr>
									<tr>
										<td style="background-color: #00436C; color: #FFF; width: 50%">
											<span style="text-align: center; font-family: \'\';">Teléfono:</strong>
										</td>
										<td style="background-color: #00436C; color: #FFF; width: 50%">
											<span style="text-align: center; font-family: \'\';">Correo:</strong>
										</td>
									</tr>
									<tr>
										<td style="text-align: center;">' . $telefono . '</td>
										<td style="text-align: center;">' . $email . '</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<br />
					' . $htmlCompromiso . '
					' . $htmlComentarios . '
				';
			} else {
				$htmlResponsable = '
					<div style="background-color: #C4DEED; width: 300px; text-align: center;"><br /><span style="font-family: \'\';">La solicitud será revisada en un periodo máximo de 24 horas a partir del momento de su creación.</span><br /></div>
				';
			}
		} else {
			$htmlResponsable = '
				<div style="background-color: #FFBCC6; width: 300px; text-align: center;"><br /><span style="font-family: \'\';">Solicitud cancelada por propietario con el siguiente motivo de cancelación:<br/><br/>' . $motivo_cancelacion . '</span><br /></div>
			';
		}

		switch($status) {
			case 0: $statusHtml = '<img src="' . STASIS . '/img/s-success.png" height="7" /> Pendiente'; break;
			case 1: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Autorizada'; break;
			case 2: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Procesando'; break;
			case 3: $statusHtml = '<img src="' . STASIS . '/img/s-primary.png" height="7" /> Procesando'; break;
			case 4: $statusHtml = '<img src="' . STASIS . '/img/s-info.png" height="7" /> Atendida'; break;
			case -1: $statusHtml = '<img src="' . STASIS . '/img/s-danger.png" height="7" /> Cancelada'; break;
			case 9: $statusHtml = '<img src="' . STASIS . '/img/s-danger.png" height="7" /> Rechazada'; break;
		}

		$stasis = STASIS;
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Bold.ttf', 'TrueTypeUnicode', '', 96);
		TCPDF_FONTS::addTTFfont(APP . '/plugins/tcpdf/fonts/Roboto-Regular.ttf', 'TrueTypeUnicode', '', 96);

		$html = <<<EOF
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 250px; color: #444;">
						<img src="$stasis/img/rtecate.png" height="64" />
					</td>
					<td style="width: 213px; text-align: right; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">SOLICITUD DE PROPIETARIO</span><br /><br />
						<span style="font-size: 9px;">No. Solicitud: $no_solicitud<br />Fecha: $fecha_creacion</span><br />
						<span style="font-size: 9px;">$statusHtml</span>
					</td>
					<td style="width: 75px; text-align: right;">
						<img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=https://saevalcas.mx/movimientos/solicitudes/visualizar/$uniqueId&chld=H|0" height="65">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="border: 2px solid #DDDCDD;">
			</table>
			<br /><br />

			<table style="text-align: left; font-size: 8px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 35%">
						<span style="text-align: center; font-family: '';">Propietario:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 10%">
						<span style="text-align: center; font-family: '';">Lote:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 15%">
						<span style="text-align: center; font-family: '';">Solicitud:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 40%">
						<span style="text-align: center; font-family: '';">Servicio:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$propietario</td>
					<td style="text-align: center;">$lote</td>
					<td style="text-align: center;">$tipo</td>
					<td style="text-align: center;">$servicio</td>
				</tr>
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 537px">
						<span style="text-align: center; font-family: \'\';">Descripción Detallada y Observaciones del Servicio:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$descripcion</td>
				</tr>
			</table>
			<br />

			$htmlResponsable
EOF;
		$fechaPdf = date('d-m-Y');

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		$nombrePdf = "Solicitud_{$no_solicitud}_{$fechaPdf}.pdf";
		$archivo = $pdf->Output(ROOT_DIR . "/data/tmp/$nombrePdf", 'F');
		return $nombrePdf;
	}
}