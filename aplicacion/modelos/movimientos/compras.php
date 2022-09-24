<?php
final class Modelos_Movimientos_Compras extends Modelo {
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

			// Jefe Directo
			if ($_SESSION['login_tipo'] == 1) {
				$qry = 'AND ej.id = ' . $_SESSION['login_id'];
			// Colaborador
			} elseif ($_SESSION['login_tipo'] == 2) {
				$qry = 'AND r.id_usuario = ' . $_SESSION['login_id'];
			// Administrador
			} elseif ($_SESSION['login_tipo'] == 3) {
				$qry = '';
			}

			// Pendientes
			$sth = $this->_db->query("
				SELECT r.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, r.id_usuario, d.nombre AS departamento, ej.id AS id_jefe, CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
				FROM requisiciones r
				JOIN empleados e
				ON e.id = r.id_usuario
				JOIN departamentos d
				ON d.id = r.id_departamento
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE r.status = 1 $qry
				ORDER BY r.id DESC
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT fecha_creacion FROM requisiciones_partes WHERE id_requisicion = ? LIMIT 1");
				$sth2->bindParam(1, $datos['id']);
				if(!$sth2->execute()) throw New Exception();
				$datos2 = $sth2->fetch();

				$sth3 = $this->_db->prepare("
					SELECT CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
					FROM empleados e
					JOIN empleados ej
					ON ej.id = e.id_jefe
					WHERE e.id = ?");
				$sth3->bindParam(1, $datos['id_usuario']);
				if(!$sth3->execute()) throw New Exception();
				$datos3 = $sth3->fetch();

				$fechaTimeStamp = new DateTime($datos2['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'solicita' => $datos['solicita'],
					'jefe' => $datos3['jefe'],
					'departamento' => $datos['departamento'],
					'fecha' => Modelos_Fecha::formatearFecha($datos2['fecha_creacion']),
					'fechaTimeStamp' => $fechaTimeStamp,
				);
				$datosVista['pendientes'][] = $arreglo;

				$x++;
			}
			$datosVista['nPendientes'] = $x;

			// Autorizadas
			$sth = $this->_db->query("
				SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, rp.fecha_creacion, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, rp.fecha_autorizacion
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				JOIN empleados e
				ON e.id = rp.id_solicita
				JOIN empleados es
				ON es.id = rp.id_autoriza
				JOIN requisiciones r
				ON r.id = rp.id_requisicion
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE rp.status = 1 $qry
				ORDER BY rp.id_requisicion DESC
				#LIMIT 10
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$fechaTimeStamp = new DateTime($datos['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();
				$fechaAutorizacionTimeStamp = new DateTime($datos['fecha_autorizacion']);
				$fechaAutorizacionTimeStamp = $fechaAutorizacionTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'id_requisicion' => $datos['id_requisicion'],
					'solicita' => $datos['solicita'],
					'autoriza' => $datos['autoriza'],
					'departamento' => $datos['departamento'],
					'producto' => $datos['producto'],
					'tipo' => $datos['tipo'],
					'cantidad' => $datos['cantidad'],
					'um' => $datos['um'],
					'fecha' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
					'fecha_autorizacion' => Modelos_Fecha::formatearFecha($datos['fecha_autorizacion']),
					'fechaTimeStamp' => $fechaTimeStamp,
					'fechaAutorizacionTimeStamp' => $fechaAutorizacionTimeStamp,
				);
				$datosVista['autorizadas'][] = $arreglo;

				$x++;
			}
			$datosVista['nAutorizadas'] = $x;

			$sth = $this->_db->query("
				SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, DATE(rp.fecha_creacion) AS fecha_creacion, DATE(rp.fecha_procesa) AS fecha_procesa, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, rp.fecha_autorizacion, rp.dias_entrega, rp.oc
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				JOIN empleados e
				ON e.id = rp.id_solicita
				JOIN empleados es
				ON es.id = rp.id_autoriza
				JOIN requisiciones r
				ON r.id = rp.id_requisicion
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE rp.status = 2 $qry
				ORDER BY rp.id_requisicion DESC
				#LIMIT 10
			");
			if(!$sth->execute()) throw New Exception();

			$xAtrasadas = 0;
			$xProcesando = 0;
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

				$fechaTimeStamp = new DateTime($datos['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();
				$fechaProcesaTimeStamp = new DateTime($datos['fecha_procesa']);
				$fechaProcesaTimeStamp = $fechaProcesaTimeStamp->getTimestamp();
				$fechaVencimientoTimeStamp = new DateTime($datos['fecha_procesa']);
				$fechaVencimientoTimeStamp = $fechaVencimiento->getTimestamp();
				$fechaAutorizacionTimeStamp = new DateTime($datos['fecha_autorizacion']);
				$fechaAutorizacionTimeStamp = $fechaAutorizacionTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'id_requisicion' => $datos['id_requisicion'],
					'solicita' => $datos['solicita'],
					'autoriza' => $datos['autoriza'],
					'departamento' => $datos['departamento'],
					'producto' => $datos['producto'],
					'tipo' => $datos['tipo'],
					'cantidad' => $datos['cantidad'],
					'um' => $datos['um'],
					'oc' => $datos['oc'],
					'dias_entrega' => $datos['dias_entrega'],
					'dias_vencidos' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
					'fecha_procesa' => Modelos_Fecha::formatearFecha($datos['fecha_procesa']),
					'fecha_vencimiento' => Modelos_Fecha::formatearFecha($fechaVencimiento->format('Y-m-d')),
					'fecha_autorizacion' => Modelos_Fecha::formatearFecha($datos['fecha_autorizacion']),
					'fechaTimeStamp' => $fechaTimeStamp,
					'fechaProcesaTimeStamp' => $fechaProcesaTimeStamp,
					'fechaVencimientoTimeStamp' => $fechaVencimientoTimeStamp,
					'fechaAutorizacionTimeStamp' => $fechaAutorizacionTimeStamp,
				);

				if ($status == 'ATRASADA') {
					$datosVista['atrasadas'][] = $arreglo;
					$xAtrasadas++;
				} else {
					$datosVista['procesando'][] = $arreglo;
					$xProcesando++;
				}
			}

			$datosVista['nProcesando'] = $xProcesando;
			$datosVista['nAtrasadas'] = $xAtrasadas;

			// Entregadas
			$sth = $this->_db->query("
				SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, DATE(rp.fecha_creacion) AS fecha_creacion, DATE(rp.fecha_procesa) AS fecha_procesa, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, rp.fecha_autorizacion, rp.dias_entrega, rp.oc
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				JOIN empleados e
				ON e.id = rp.id_solicita
				JOIN empleados es
				ON es.id = rp.id_autoriza
				JOIN requisiciones r
				ON r.id = rp.id_requisicion
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE rp.status = 4 $qry
				ORDER BY rp.id_requisicion DESC
				#LIMIT 10
			");
			if(!$sth->execute()) throw New Exception();

			$xEntregadas = 0;
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

				$fechaTimeStamp = new DateTime($datos['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();
				$fechaProcesaTimeStamp = new DateTime($datos['fecha_procesa']);
				$fechaProcesaTimeStamp = $fechaProcesaTimeStamp->getTimestamp();
				$fechaVencimientoTimeStamp = new DateTime($datos['fecha_procesa']);
				$fechaVencimientoTimeStamp = $fechaVencimiento->getTimestamp();
				$fechaAutorizacionTimeStamp = new DateTime($datos['fecha_autorizacion']);
				$fechaAutorizacionTimeStamp = $fechaAutorizacionTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'id_requisicion' => $datos['id_requisicion'],
					'solicita' => $datos['solicita'],
					'autoriza' => $datos['autoriza'],
					'departamento' => $datos['departamento'],
					'producto' => $datos['producto'],
					'tipo' => $datos['tipo'],
					'cantidad' => $datos['cantidad'],
					'um' => $datos['um'],
					'oc' => $datos['oc'],
					'dias_entrega' => $datos['dias_entrega'],
					'dias_vencidos' => $status,
					'icono' => $icono,
					'color' => $color,
					'fecha' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
					'fecha_procesa' => Modelos_Fecha::formatearFecha($datos['fecha_procesa']),
					'fecha_vencimiento' => Modelos_Fecha::formatearFecha($fechaVencimiento->format('Y-m-d')),
					'fecha_autorizacion' => Modelos_Fecha::formatearFecha($datos['fecha_autorizacion']),
					'fechaTimeStamp' => $fechaTimeStamp,
					'fechaProcesaTimeStamp' => $fechaProcesaTimeStamp,
					'fechaVencimientoTimeStamp' => $fechaVencimientoTimeStamp,
					'fechaAutorizacionTimeStamp' => $fechaAutorizacionTimeStamp,
				);

				$datosVista['entregadas'][] = $arreglo;
				$xEntregadas++;
			}
			$datosVista['nEntregadas'] = $xEntregadas;

			// Recibidas
			$sth = $this->_db->query("
				SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, rp.fecha_creacion, CONCAT(es.nombre, ' ', es.apellidos) AS recibe, rp.fecha_recibo, rp.dias_entrega, rp.oc
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				JOIN empleados e
				ON e.id = rp.id_solicita
				JOIN empleados es
				ON es.id = rp.id_recibe
				JOIN requisiciones r
				ON r.id = rp.id_requisicion
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE rp.status = 3 $qry
				ORDER BY rp.id_requisicion DESC
				#LIMIT 10
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$fechaTimeStamp = new DateTime($datos['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();
				$fechaReciboTimeStamp = new DateTime($datos['fecha_recibo']);
				$fechaReciboTimeStamp = $fechaReciboTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'id_requisicion' => $datos['id_requisicion'],
					'solicita' => $datos['solicita'],
					'recibe' => $datos['recibe'],
					'departamento' => $datos['departamento'],
					'producto' => $datos['producto'],
					'tipo' => $datos['tipo'],
					'cantidad' => $datos['cantidad'],
					'um' => $datos['um'],
					'oc' => $datos['oc'],
					'dias_entrega' => $datos['dias_entrega'],
					'fecha' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
					'fecha_recibo' => Modelos_Fecha::formatearFecha($datos['fecha_recibo']),
					'fechaTimeStamp' => $fechaTimeStamp,
					'fechaReciboTimeStamp' => $fechaReciboTimeStamp,
				);
				$datosVista['recibidas'][] = $arreglo;

				$x++;
			}
			$datosVista['nRecibidas'] = $x;

			// Rechazadas
			$sth = $this->_db->query("
				SELECT r.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, CONCAT(er.nombre, ' ', er.apellidos) AS rechaza, r.id_usuario, d.nombre AS departamento, r.fecha_rechazo, r.motivo_rechazo, r.tipo_rechazo
				FROM requisiciones r
				JOIN empleados e
				ON e.id = r.id_usuario
				JOIN departamentos d
				ON d.id = r.id_departamento
				JOIN empleados er
				ON er.id = r.id_usuario_rechaza
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE r.status = 3 $qry
				ORDER BY r.id DESC
				#LIMIT 10
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$sth3 = $this->_db->prepare("
					SELECT CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
					FROM empleados e
					JOIN empleados ej
					ON ej.id = e.id_jefe
					WHERE e.id = ?");
				$sth3->bindParam(1, $datos['id_usuario']);
				if(!$sth3->execute()) throw New Exception();
				$datos3 = $sth3->fetch();

				switch($datos['tipo_rechazo']) {
					case 1: $tipoRechazo = 'INTERNO'; break;
					case 2: $tipoRechazo = 'EXTERNO'; break;
				}

				$fechaTimeStamp = new DateTime($datos['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();
				$fechaRechazoTimeStamp = new DateTime($datos['fecha_rechazo']);
				$fechaRechazoTimeStamp = $fechaRechazoTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'solicita' => $datos['solicita'],
					'jefe' => $datos3['jefe'],
					'departamento' => $datos['departamento'],
					'rechaza' => $datos['rechaza'],
					'fecha' => Modelos_Fecha::formatearFecha($datos['fecha_creacion']),
					'fecha_rechazo' => Modelos_Fecha::formatearFecha($datos['fecha_rechazo']),
					'tipoRechazo' => $tipoRechazo,
					'motivo_rechazo' => $datos['motivo_rechazo'],
					'fechaTimeStamp' => $fechaTimeStamp,
					'fechaRechazoTimeStamp' => $fechaRechazoTimeStamp,
				);
				$datosVista['rechazadas'][] = $arreglo;

				$x++;
			}
			$datosVista['nRechazadas'] = $x;

			// Canceladas
			$sth = $this->_db->query("
				SELECT r.id AS id_requisicion, rp.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, r.id_usuario, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um
				FROM requisiciones r
				JOIN requisiciones_partes rp
				ON rp.id_requisicion = r.id
				JOIN empleados e
				ON e.id = r.id_usuario
				JOIN departamentos d
				ON d.id = r.id_departamento
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE r.status = 0 $qry
				ORDER BY r.id DESC
				#LIMIT 10
			");
			if(!$sth->execute()) throw New Exception();

			$x = 0;
			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT fecha_creacion FROM requisiciones_partes WHERE id_requisicion = ? LIMIT 1");
				$sth2->bindParam(1, $datos['id_requisicion']);
				if(!$sth2->execute()) throw New Exception();
				$datos2 = $sth2->fetch();

				$sth3 = $this->_db->prepare("
					SELECT CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
					FROM empleados e
					JOIN empleados ej
					ON ej.id = e.id_jefe
					WHERE e.id = ?");
				$sth3->bindParam(1, $datos['id_usuario']);
				if(!$sth3->execute()) throw New Exception();
				$datos3 = $sth3->fetch();

				$fechaTimeStamp = new DateTime($datos2['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'id_requisicion' => $datos['id_requisicion'],
					'solicita' => $datos['solicita'],
					'jefe' => $datos3['jefe'],
					'departamento' => $datos['departamento'],
					'producto' => $datos['producto'],
					'tipo' => $datos['tipo'],
					'cantidad' => $datos['cantidad'],
					'um' => $datos['um'],
					'fecha' => Modelos_Fecha::formatearFecha($datos2['fecha_creacion']),
					'fechaTimeStamp' => $fechaTimeStamp,
				);
				$datosVista['canceladas'][] = $arreglo;

				$x++;
			}

			// Canceladas Partes
			$sth = $this->_db->query("
				SELECT r.id AS id_requisicion, rp.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, r.id_usuario, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um
				FROM requisiciones_partes rp
				JOIN requisiciones r
				ON r.id = rp.id_requisicion
				JOIN empleados e
				ON e.id = r.id_usuario
				JOIN departamentos d
				ON d.id = r.id_departamento
				JOIN empleados ej
				ON ej.id = e.id_jefe
				WHERE rp.status = 0 AND rp.fecha_autorizacion != '0000-00-00 00:00:00' $qry
				ORDER BY r.id DESC
				#LIMIT 10
			");
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				$sth2 = $this->_db->prepare("SELECT fecha_creacion FROM requisiciones_partes WHERE id_requisicion = ? LIMIT 1");
				$sth2->bindParam(1, $datos['id_requisicion']);
				if(!$sth2->execute()) throw New Exception();
				$datos2 = $sth2->fetch();

				$sth3 = $this->_db->prepare("
					SELECT CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
					FROM empleados e
					JOIN empleados ej
					ON ej.id = e.id_jefe
					WHERE e.id = ?");
				$sth3->bindParam(1, $datos['id_usuario']);
				if(!$sth3->execute()) throw New Exception();
				$datos3 = $sth3->fetch();

				$fechaTimeStamp = new DateTime($datos2['fecha_creacion']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'id_requisicion' => $datos['id_requisicion'],
					'solicita' => $datos['solicita'],
					'jefe' => $datos3['jefe'],
					'departamento' => $datos['departamento'],
					'producto' => $datos['producto'],
					'tipo' => $datos['tipo'],
					'cantidad' => $datos['cantidad'],
					'um' => $datos['um'],
					'fecha' => Modelos_Fecha::formatearFecha($datos2['fecha_creacion']),
					'fechaTimeStamp' => $fechaTimeStamp,
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
	
	public function nueva() {
		try {
			$datosArray = array();

			// Folio
			$sth = $this->_db->query("SELECT id FROM requisiciones ORDER BY id DESC LIMIT 1");
			$datosArray['folio'] = $sth->fetchColumn()+1;

			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificar($id) {
		try {
			$datosArray = array();

			// Datos de cotizacion
			$sth = $this->_db->prepare("
				SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, rp.fecha_creacion, rp.justificacion, rp.observaciones, rp.oc, rp.dias_entrega, rp.cuenta_contable, rp.id_proveedor
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				JOIN empleados e
				ON e.id = rp.id_solicita
				WHERE rp.id_requisicion = ?
				ORDER BY id DESC
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			if (!$datos) {
				$sth = $this->_db->prepare("
					SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, rp.fecha_creacion, rp.justificacion, rp.observaciones, rp.oc, rp.dias_entrega, rp.cuenta_contable, rp.id_proveedor
					FROM requisiciones_partes rp
					JOIN departamentos d
					ON d.id = rp.id_departamento
					JOIN empleados e
					ON e.id = rp.id_solicita
					WHERE rp.id = ?
				");
				$sth->bindParam(1, $id);
				if(!$sth->execute()) throw New Exception();
				$datos = $sth->fetch();

				$datosArray['folio'] = $id;
				$datosArray['id_requisicion'] = $datos['id_requisicion'];
				$datosArray['solicita'] = $datos['solicita'];
				$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);
				$datosArray['departamento'] = $datos['departamento'];
				$datosArray['producto'] = $datos['producto'];
				$datosArray['tipo'] = $datos['tipo'];
				$datosArray['cantidad'] = $datos['cantidad'];
				$datosArray['um'] = $datos['um'];
				$datosArray['justificacion'] = $datos['justificacion'];
				$datosArray['observaciones'] = $datos['observaciones'];
				$datosArray['oc'] = $datos['oc'];
				$datosArray['dias_entrega'] = $datos['dias_entrega'];
				$datosArray['cuenta_contable'] = $datos['cuenta_contable'];
				$datosArray['id_proveedor'] = $datos['id_proveedor'];
			} else {
				$datosArray['folio'] = $id;
				$datosArray['id_requisicion'] = $datos['id_requisicion'];
				$datosArray['solicita'] = $datos['solicita'];
				$datosArray['fecha_creacion'] = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);
				$datosArray['departamento'] = $datos['departamento'];
				$datosArray['producto'] = $datos['producto'];
				$datosArray['tipo'] = $datos['tipo'];
				$datosArray['cantidad'] = $datos['cantidad'];
				$datosArray['um'] = $datos['um'];
				$datosArray['justificacion'] = $datos['justificacion'];
				$datosArray['observaciones'] = $datos['observaciones'];
				$datosArray['oc'] = $datos['oc'];
				$datosArray['dias_entrega'] = $datos['dias_entrega'];
				$datosArray['cuenta_contable'] = $datos['cuenta_contable'];
				$datosArray['id_proveedor'] = $datos['id_proveedor'];

				// Partes
				$sth = $this->_db->prepare("SELECT * FROM requisiciones_partes WHERE id_requisicion = ? ORDER BY id ASC");
				$sth->bindParam(1, $id);
				if(!$sth->execute()) throw New Exception();
				
				$partesArray = array();
				$x = 1;
				while ($datos = $sth->fetch()) {
					// Tipos
					$html = '';
					$sth2 = $this->_db->query("SELECT nombre
						FROM tipos
						WHERE status = 1
						ORDER BY nombre ASC");
					if(!$sth2->execute()) throw New Exception();
					while ($datos2 = $sth2->fetch()) {
						if (isset($datos['tipo'])) {
							if ($datos['tipo'] == $datos2['nombre']) {
								$html .= '<option value="' . $datos2['nombre'] . '" selected>' . $datos2['nombre'] . '</option>';
							} else {
								$html .= '<option value="' . $datos2['nombre'] . '">' . $datos2['nombre'] . '</option>';
							}
						} else {
							$html .= '<option value="' . $datos2['nombre'] . '">' . $datos2['nombre'] . '</option>';
						}
					}

					$partesArray[$x]['producto'] = $datos['producto'];
					$partesArray[$x]['tipo'] = $html;
					$partesArray[$x]['cantidad'] = $datos['cantidad'];
					$partesArray[$x]['um'] = $datos['um'];
					$partesArray[$x]['justificacion'] = $datos['justificacion'];
					$partesArray[$x]['observaciones'] = $datos['observaciones'];

					$x++;
				}
				$datosArray['partes'] = $partesArray;
				$datosArray['conteoPartes'] = $x;
			}

			return $datosArray;
		} catch (Exception $e) {
			echo Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function generar() {
		try {
			$idGenerado = $_SESSION['login_id'];
			$idDepartamento = $_POST['id_departamento'];
			$centro_costo = mb_strtoupper($_POST['centro_costo'], 'UTF-8');

			$sth = $this->_db->prepare("INSERT INTO requisiciones (id_usuario, id_departamento, centro_costo) VALUES (?, ?, ?)");
			$sth->bindParam(1, $idGenerado);
			$sth->bindParam(2, $idDepartamento);
			$sth->bindParam(3, $centro_costo);
			if(!$sth->execute()) throw New Exception();
			$idRequisicion = $this->_db->lastInsertId();

			for($x=1; $x<=50; $x++) {
				$producto = mb_strtoupper($_POST["producto$x"], 'UTF-8');
				$tipo = mb_strtoupper($_POST["tipo$x"], 'UTF-8');
				$cantidad = mb_strtoupper($_POST["cantidad$x"], 'UTF-8');
				$um = mb_strtoupper($_POST["um$x"], 'UTF-8');
				$justificacion = mb_strtoupper($_POST["justificacion$x"], 'UTF-8');
				$observaciones = $_POST["observaciones$x"];

				if ($producto) {
					$sth = $this->_db->prepare("INSERT INTO requisiciones_partes (id_requisicion, id_solicita, id_departamento, producto, tipo, cantidad, um, justificacion, observaciones, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
					$arregloDatos = array($idRequisicion, $idGenerado, $idDepartamento, $producto, $tipo, $cantidad, $um, $justificacion, $observaciones);
					if(!$sth->execute($arregloDatos)) throw New Exception();
				}
			}

			if (!empty($idRequisicionModificada)) {
				header('Location:' . STASIS. '/movimientos/compras/modificar/' . $idRequisicionModificada . '/1');
			} else {
				header('Location:' . STASIS. '/movimientos/compras/1');
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function generarModificar() {
		try {
			$idRequisicion = $_POST['id'];
			$idGenerado = $_SESSION['login_id'];
			$idDepartamento = $_POST['id_departamento'];
			$centro_costo = mb_strtoupper($_POST['centro_costo'], 'UTF-8');

			$sth = $this->_db->prepare("
				UPDATE requisiciones SET
				id_usuario = ?,
				id_departamento = ?,
				centro_costo = ?
				WHERE id = ?");
			$sth->bindParam(1, $idGenerado);
			$sth->bindParam(2, $idDepartamento);
			$sth->bindParam(3, $centro_costo);
			$sth->bindParam(4, $idRequisicion);
			if(!$sth->execute()) throw New Exception();

			$sth = $this->_db->prepare("DELETE FROM requisiciones_partes WHERE id_requisicion = ?");
			$sth->bindParam(1, $idRequisicion);
			if(!$sth->execute()) throw New Exception();

			for($x=1; $x<=50; $x++) {
				$producto = mb_strtoupper($_POST["producto$x"], 'UTF-8');
				$tipo = mb_strtoupper($_POST["tipo$x"], 'UTF-8');
				$cantidad = mb_strtoupper($_POST["cantidad$x"], 'UTF-8');
				$um = mb_strtoupper($_POST["um$x"], 'UTF-8');
				$justificacion = mb_strtoupper($_POST["justificacion$x"], 'UTF-8');
				$observaciones = $_POST["observaciones$x"];

				if ($producto) {
					$sth = $this->_db->prepare("INSERT INTO requisiciones_partes (id_requisicion, id_solicita, id_departamento, producto, tipo, cantidad, um, justificacion, observaciones, comentarios, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
					$arregloDatos = array($idRequisicion, $idGenerado, $idDepartamento, $producto, $tipo, $cantidad, $um, $justificacion, $observaciones, $comentarios);
					if(!$sth->execute($arregloDatos)) throw New Exception();
				}
			}

			header('Location:' . STASIS. '/movimientos/compras/modificar/' . $idRequisicion . '/1');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function visualizar($id) {
		$this->pdf($id,0,1);
	}

	public function visualizarParte($id) {
		$this->pdfParte($id,0,1);
	}

	public function visualizarRecibida($id) {
		$this->pdfRecibida($id,0,1);
	}

	public function pdf($id, $descargar = null, $visualizar = null) {
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new TCPDF('LANDSCAPE', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Grupo Valcas');
		$pdf->SetSubject('Grupo Valcas');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('Roboto', '', 10);
		$pdf->SetMargins(10, 10, 10, 10);
		$pdf->AddPage();

		// Encabezados
		$sth = $this->_db->prepare("
			SELECT r.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, r.id_usuario, d.nombre AS departamento, r.fecha_cancelacion, r.fecha_rechazo, CONCAT(er.nombre, ' ', er.apellidos) AS rechaza, r.status, r.centro_costo, r.motivo_rechazo, r.tipo_rechazo
			FROM requisiciones r
			JOIN empleados e
			ON e.id = r.id_usuario
			JOIN departamentos d
			ON d.id = r.id_departamento
			LEFT JOIN empleados er
			ON er.id = r.id_usuario_rechaza
			WHERE r.id = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();
		$statusRequisicionGlobal = $datos['status'];
		$statusRequisicion = $datos['status'];

		if (!empty($datos['fecha_cancelacion'])) {
			$fechaCancelacion = Modelos_Fecha::formatearFechaHora($datos['fecha_cancelacion']);
		}
		if (!empty($datos['fecha_rechazo'])) {
			$fechaRechazo = Modelos_Fecha::formatearFechaHora($datos['fecha_rechazo']);
			$rechaza = $datos['rechaza'];

			if ($datos['tipo_rechazo'] == 1) {
				$tipoRechazo = 'INTERNO';
			} else {
				$tipoRechazo = 'EXTERNO';
			}

			$rechazoTexto = '
				<br />
				<table style="background-color: #FFAFAF; text-align: center; font-size: 9px;" cellpadding="7">
					<tr>
						<td>MOTIVO DE RECHAZO <b>' . $tipoRechazo . '</b>: ' . $datos['motivo_rechazo'] . '</td>
					</tr>
				</table>
				<br />
			';
		}

		$sth2 = $this->_db->prepare("SELECT fecha_creacion FROM requisiciones_partes WHERE id_requisicion = ? LIMIT 1");
		$sth2->bindParam(1, $datos['id']);
		if(!$sth2->execute()) throw New Exception();
		$datos2 = $sth2->fetch();

		$solicita = $datos['solicita'];
		$departamento = $datos['departamento'];
		$centro_costo = $datos['centro_costo'];
		$fechaCreacion = Modelos_Fecha::formatearFecha($datos2['fecha_creacion']);
		$fechaHoraCreacion = Modelos_Fecha::formatearFechaHora($datos2['fecha_creacion']);

		$sth3 = $this->_db->prepare("
			SELECT CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
			FROM empleados e
			JOIN empleados ej
			ON ej.id = e.id_jefe
			WHERE e.id = ?");
		$sth3->bindParam(1, $datos['id_usuario']);
		if(!$sth3->execute()) throw New Exception();
		$datos3 = $sth3->fetch();

		// Partes
		$sth = $this->_db->prepare("
			SELECT rp.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, CONCAT(er.nombre, ' ', er.apellidos) AS recibe, CONCAT(ep.nombre, ' ', ep.apellidos) AS procesa, d.nombre AS departamento, rp.fecha_creacion, rp.fecha_autorizacion, rp.fecha_recibo, rp.comentarios, rp.fecha_procesa
			FROM requisiciones_partes rp
			JOIN departamentos d
			ON d.id = rp.id_departamento
			JOIN empleados e
			ON e.id = rp.id_solicita
			LEFT JOIN empleados es
			ON es.id = rp.id_autoriza
			LEFT JOIN empleados er
			ON er.id = rp.id_recibe
			LEFT JOIN empleados ep
			ON ep.id = rp.id_procesa
			WHERE rp.id_requisicion = ?
			LIMIT 1
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		if (!empty($fechaCancelacion)) {
			$autorizaHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;
			$reciboHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;
			$procesaHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;

			$firmasHtml = '
				<tr>
					<td style="width:50%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Solicita:</span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
					<td style="width:50%; text-align: center; color: #F00;"><span style="font-family: \'SanFranciscoBold\';">Cancela:</span>' . $autorizaHtml . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
				<tr>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
				</tr>
				<tr>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
				</tr>
			';
		} elseif (!empty($fechaRechazo)) {
			$autorizaHtml = '<br />' . $rechaza . '<br />' . $fechaRechazo;

			$firmasHtml = '
				<tr>
					<td style="width:50%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Solicita:</span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
					<td style="width:50%; text-align: center; color: #F00;"><span style="font-family: \'SanFranciscoBold\';">Rechaza:</span>' . $autorizaHtml . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
				<tr>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
				</tr>
				<tr>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
				</tr>
			';
		} else {
			if (!empty($datos['autoriza'])) {
				$autorizaHtml = '<br />' . $datos['autoriza'] . '<br />' . Modelos_Fecha::formatearFechaHora($datos['fecha_autorizacion']);
			} else {
				$autorizaHtml = '';
			}

			// Procesa
			$sth2 = $this->_db->prepare("
				SELECT CONCAT(ep.nombre, ' ', ep.apellidos) AS procesa, rp.fecha_procesa
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				JOIN empleados e
				ON e.id = rp.id_solicita
				LEFT JOIN empleados es
				ON es.id = rp.id_autoriza
				LEFT JOIN empleados er
				ON er.id = rp.id_recibe
				LEFT JOIN empleados ep
				ON ep.id = rp.id_procesa
				WHERE rp.id_requisicion = ? AND ep.nombre != '' AND fecha_procesa != '0000-00-00 00:00:00' LIMIT 1
			");
			$sth2->bindParam(1, $id);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();
			
			if (!empty($datos2['procesa'])) {
				$procesaHtml = '<br />' . $datos2['procesa'] . '<br />' . Modelos_Fecha::formatearFechaHora($datos2['fecha_procesa']);
			} else {
				$procesaHtml = '';
			}

			// Recibe
			$sth2 = $this->_db->prepare("
				SELECT CONCAT(er.nombre, ' ', er.apellidos) AS recibe, rp.fecha_recibo
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				LEFT JOIN empleados er
				ON er.id = rp.id_recibe
				WHERE rp.id_requisicion = ? AND er.nombre != '' AND fecha_recibo != '0000-00-00 00:00:00' LIMIT 1
			");
			$sth2->bindParam(1, $id);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();

			if (!empty($datos2['recibe'])) {
				$reciboHtml = '<br />' . $datos2['recibe'] . '<br />' . Modelos_Fecha::formatearFechaHora($datos2['fecha_recibo']);
			} else {
				$reciboHtml = '';
			}


			$firmasHtml = '
				<tr>
					<td style="width:20%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';"><b>Solicita:</b></span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
					<td style="width:20%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';"><b>Autoriza:</b></span>' . $autorizaHtml . '</td>
					<td style="width:20%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';"><b>Procesa:</b></span>' . $procesaHtml . '</td>
					<td style="width:20%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';"><b>Entrega:</b></span>' . $entregaHtml . '</td>
					<td style="width:20%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';"><b>Recibe:</b></span>' . $reciboHtml . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
			';
		}
		
		$sth = $this->_db->prepare("
			SELECT producto, tipo, cantidad, um, justificacion, observaciones, rp.status
			FROM requisiciones_partes rp
			WHERE rp.id_requisicion = ?");
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

			if ($statusRequisicion == 2) {
				if ($datos['status'] == 1) {
					$status = 'AUTORIZADA';
				} elseif ($datos['status'] == 2) {
					$status = 'PROCESANDO';
				} elseif ($datos['status'] == 3) {
					$status = '<span style="color: #2E7E2E;">RECIBIDA</span>';
				} elseif ($datos['status'] == 4) {
					$status = '<span style="color: #2E7E2E;">ENTREGADA</span>';
				} elseif ($datos['status'] == 0) {
					$status = '<span style="color: #F00;">CANCELADA</span>';
				}
			} else {
				$status = 'PENDIENTE';
			}

			if (filter_var($datos['observaciones'], FILTER_VALIDATE_URL)) {
				$observaciones = '<a href="' . $datos['observaciones'] . '" target="_blank">PRODUCTO REQUERIDO</a>';
			} else {
				$observaciones = $datos['observaciones'];
			}

			$htmlPartidas .= '<tr>';
			$htmlPartidas .= '<td style="text-align: center;">' . $status . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['producto'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['tipo'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['cantidad'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['um'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['justificacion'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $observaciones . '</td>';
			$htmlPartidas .= '</tr>';

			$x++;
		}

		$stasis = STASIS;

		// Si es un administrador
		if ($_SESSION['login_tipo'] == 1 && $statusRequisicionGlobal == 1) {
			$autorizacionHtml = '
			    <br /><br />
			    <table style="text-align: center; font-size: 11px;" cellpadding="6" cellspacing="1">
					<tr>
					    <td style="width: 40%"></td>
						<td style="background-color: #358405; color: #FFF; width: 20%; text-align: center;"><a style="color: #FFF; font-family: \'SanFranciscoBold\';" href="' . $stasis . '/movimientos/compras/autorizar/' . $id . '"><img src="' . $stasis . '/img/icono-activar.png" height="8" />Autorizar Requisición</a></td>
					</tr>
				</table>
			';
		} else {
			$autorizacionHtml = '';
		}

		$html = <<<EOF
			<table style="font-size: 9px; text-align: left;" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 515px; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">GRUPO VALCAS</span><br />

						<span style="font-size: 9px;">CALLE ESCUADRÓN 201, NO.3110 - INT D<br />
							COLONIA AVIACIÓN<br />
							TIJUANA, BAJA CALIFORNIA, MÉXICO<br />
							CEL. (664) 127-7175
						</span>
					</td>
					<td style="width: 270px; color: #444; text-align: right;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">REQUISICIÓN INTERNA</span><br />
						<img src="$stasis/img/gvalcas.png" height="43">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="text-align: left; font-size: 9px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 15%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Fecha:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 25%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Solicitante:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 25%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Departamento:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 25%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Centro de Costo:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 10%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Folio:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$fechaCreacion</td>
					<td style="text-align: center;">$solicita</td>
					<td style="text-align: center;">$departamento</td>
					<td style="text-align: center;">$centro_costo</td>
					<td style="text-align: center;">$id</td>
				</tr>
			</table>
			<br /><br />

			<table style="font-size: 8px; text-align: left;" border="0" cellpadding="2" cellspacing="1">
				<tbody>
					<tr>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Status</td>
						<td style="width: 30%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Producto y/o Servicio</td>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Tipo</td>
						<td style="width: 7%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Cantidad</td>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">UM</td>
						<td style="width: 21%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Especificación<br />Justificación</td>
						<td style="width: 12%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Observaciones / Link Producto</td>
					</tr>
				</tbody>
				$htmlPartidas
			</table>
			<br />

			$rechazoTexto
			$observacionesTexto

			<br />
			<table style="border: 2px solid #DDDCDD;">
		    </table>
		    <br /><br />

			<table style="font-size: 9px; width: 100%;">
				$firmasHtml
			</table><br /><br />

			$autorizacionHtml
EOF;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if ($descargar == 1) {
			$pdf->Output('F-' . $fechaCreacionPdf . '-' . $folioPdf . '_' . $clientePdf . '.pdf', 'D');
		} elseif ($visualizar == 1) {
			$pdf->Output($id . '-Requisicion_Interna.pdf', 'I');
		}
	}

	public function pdfParte($id, $descargar = null, $visualizar = null) {
		
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new TCPDF('LANDSCAPE', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Grupo Valcas');
		$pdf->SetSubject('Grupo Valcas');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('Roboto', '', 10);
		$pdf->SetMargins(10, 10, 10, 10);	
		$pdf->AddPage();

		// Partes
		$sth = $this->_db->prepare("
			SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, CONCAT(er.nombre, ' ', er.apellidos) AS recibe, CONCAT(ep.nombre, ' ', ep.apellidos) AS procesa, d.nombre AS departamento, rp.fecha_creacion, rp.fecha_autorizacion, rp.fecha_recibo, rp.comentarios, rp.fecha_procesa, rp.fecha_cancelacion, CONCAT(ec.nombre, ' ', ec.apellidos) AS cancela
			FROM requisiciones_partes rp
			JOIN departamentos d
			ON d.id = rp.id_departamento
			JOIN empleados e
			ON e.id = rp.id_solicita
			LEFT JOIN empleados es
			ON es.id = rp.id_autoriza
			LEFT JOIN empleados er
			ON er.id = rp.id_recibe
			LEFT JOIN empleados ep
			ON ep.id = rp.id_procesa
			LEFT JOIN empleados ec
			ON ec.id = rp.id_cancela
			WHERE rp.id = ?
			LIMIT 1
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();
		$idRequisicion = $datos['id_requisicion'];
		$fechaCancelacion = $datos['fecha_cancelacion'];
		$fechaCreacion = Modelos_Fecha::formatearFecha($datos['fecha_creacion']);
		$fechaHoraCreacion = Modelos_Fecha::formatearFechaHora($datos['fecha_creacion']);

		// Encabezados
		$sth = $this->_db->prepare("
			SELECT r.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, r.id_usuario, d.nombre AS departamento, r.fecha_cancelacion, r.fecha_rechazo, CONCAT(er.nombre, ' ', er.apellidos) AS rechaza, r.status
			FROM requisiciones r
			JOIN empleados e
			ON e.id = r.id_usuario
			JOIN departamentos d
			ON d.id = r.id_departamento
			LEFT JOIN empleados er
			ON er.id = r.id_usuario_rechaza
			WHERE r.id = ?
		");
		$sth->bindParam(1, $idRequisicion);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		$statusRequisicion = $datos['status'];
		$solicita = $datos['solicita'];
		$departamento = $datos['departamento'];

		$sth3 = $this->_db->prepare("
			SELECT CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
			FROM empleados e
			JOIN empleados ej
			ON ej.id = e.id_jefe
			WHERE e.id = ?");
		$sth3->bindParam(1, $datos['id_usuario']);
		if(!$sth3->execute()) throw New Exception();
		$datos3 = $sth3->fetch();

		if ($fechaCancelacion != '0000-00-00 00:00:00') {
			$fechaCancelacion = Modelos_Fecha::formatearFechaHora($fechaCancelacion);
		}
		if (!empty($datos['fecha_rechazo'])) {
			$fechaRechazo = Modelos_Fecha::formatearFechaHora($datos['fecha_rechazo']);
			$rechaza = $datos['rechaza'];
		}

		$sth2 = $this->_db->prepare("SELECT fecha_creacion FROM requisiciones_partes WHERE id_requisicion = ? LIMIT 1");
		$sth2->bindParam(1, $datos['id']);
		if(!$sth2->execute()) throw New Exception();
		$datos2 = $sth2->fetch();
		
		$sth = $this->_db->prepare("
			SELECT producto, tipo, cantidad, um, justificacion, observaciones, rp.status
			FROM requisiciones_partes rp
			WHERE rp.id = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();

		if ($fechaCancelacion == '0000-00-00 00:00:00') {
			$firmasHtml = '
				<tr>
					<td style="width:50%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Solicita:</span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
				<tr>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
				</tr>
				<tr>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
				</tr>
			';
		} else {
			$autorizaHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCreacion;
			$reciboHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;
			$procesaHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;

			$firmasHtml = '
				<tr>
					<td style="width:50%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Solicita:</span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
				<tr>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
				</tr>
				<tr>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
				</tr>
			';
		}

		$htmlPartidas = '';
		$x = 0;
		while ($datos = $sth->fetch()) {
			if ($x % 2 == 0) {
				$background = '#FFF';
			} else {
				$background = '#EAEAEA';
			}

			if ($statusRequisicion == 2) {
				if ($datos['status'] == 1) {
					$status = 'AUTORIZADA';
				} elseif ($datos['status'] == 2) {
					$status = 'PROCESANDO';
				} elseif ($datos['status'] == 3) {
					$status = '<span style="color: #2E7E2E;">RECIBIDA</span>';
				} elseif ($datos['status'] == 0) {
					$status = '<span style="color: #F00;">CANCELADA</span>';
				}
			} else {
				$status = 'PENDIENTE';
			}

			$htmlPartidas .= '<tr>';
			$htmlPartidas .= '<td style="text-align: center;">' . $status . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['producto'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['tipo'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['cantidad'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['um'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['justificacion'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['observaciones'] . '</td>';
			$htmlPartidas .= '</tr>';

			$x++;
		}

		$stasis = STASIS;

		$html = <<<EOF
			<table style="font-size: 9px; text-align: left;" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 515px; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">GRUPO VALCAS</span><br />

						<span style="font-size: 9px;">CALLE ESCUADRÓN 201, NO.3110 - INT D<br />
							COLONIA AVIACIÓN<br />
							TIJUANA, BAJA CALIFORNIA, MÉXICO<br />
							CEL. (664) 127-7175
						</span>
					</td>
					<td style="width: 270px; color: #444; text-align: right;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">REQUISICIÓN INTERNA</span><br />
						<img src="$stasis/img/gvalcas.png" height="43">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="text-align: left; font-size: 9px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 20%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Fecha:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 30%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Solicitante:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 30%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Departamento:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 20%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Folio:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$fechaCreacion</td>
					<td style="text-align: center;">$solicita</td>
					<td style="text-align: center;">$departamento</td>
					<td style="text-align: center;">$idRequisicion</td>
				</tr>
			</table>
			<br /><br />

			<table style="font-size: 8px; text-align: left;" border="0" cellpadding="2" cellspacing="1">
				<tbody>
					<tr>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Status</td>
						<td style="width: 15%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Producto y/o Servicio</td>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Tipo</td>
						<td style="width: 7%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Cantidad</td>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">UM</td>
						<td style="width: 26%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Especificación/Justificación</td>
						<td style="width: 22%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Observaciones / Link Producto</td>
					</tr>
				</tbody>
				$htmlPartidas
			</table>
			<br />


			$observacionesTexto

			<table style="font-size: 9px; width: 100%;">
				$firmasHtml
			</table>
EOF;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if ($descargar == 1) {
			$pdf->Output('F-' . $fechaCreacionPdf . '-' . $folioPdf . '_' . $clientePdf . '.pdf', 'D');
		} elseif ($visualizar == 1) {
			$pdf->Output($id . '-Requisicion_Interna.pdf', 'I');
		}
	}

	public function pdfRecibida($id, $descargar = null, $visualizar = null) {
		
		require_once(APP . 'plugins/tcpdf/tcpdf.php');
		$pdf = new TCPDF('LANDSCAPE', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('Grupo Valcas');
		$pdf->SetSubject('Grupo Valcas');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('Roboto', '', 10);
		$pdf->SetMargins(10, 10, 10, 10);	
		$pdf->AddPage();

		$idRecibo = $id;

		$sth = $this->_db->prepare("SELECT id_requisicion FROM requisiciones_partes WHERE id = ?");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$id = $sth->fetchColumn();

		// Encabezados
		$sth = $this->_db->prepare("
			SELECT r.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, r.id_usuario, d.nombre AS departamento, r.fecha_cancelacion, r.fecha_rechazo, CONCAT(er.nombre, ' ', er.apellidos) AS rechaza, r.status
			FROM requisiciones r
			JOIN empleados e
			ON e.id = r.id_usuario
			JOIN departamentos d
			ON d.id = r.id_departamento
			LEFT JOIN empleados er
			ON er.id = r.id_usuario_rechaza
			WHERE r.id = ?
		");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();
		$statusRequisicion = $datos['status'];

		if (!empty($datos['fecha_cancelacion'])) {
			$fechaCancelacion = Modelos_Fecha::formatearFechaHora($datos['fecha_cancelacion']);
		}
		if (!empty($datos['fecha_rechazo'])) {
			$fechaRechazo = Modelos_Fecha::formatearFechaHora($datos['fecha_rechazo']);
			$rechaza = $datos['rechaza'];
		}

		$sth2 = $this->_db->prepare("SELECT fecha_creacion FROM requisiciones_partes WHERE id_requisicion = ? LIMIT 1");
		$sth2->bindParam(1, $datos['id']);
		if(!$sth2->execute()) throw New Exception();
		$datos2 = $sth2->fetch();

		$solicita = $datos['solicita'];
		$departamento = $datos['departamento'];
		$fechaCreacion = Modelos_Fecha::formatearFecha($datos2['fecha_creacion']);
		$fechaHoraCreacion = Modelos_Fecha::formatearFechaHora($datos2['fecha_creacion']);

		$sth3 = $this->_db->prepare("
			SELECT CONCAT(ej.nombre, ' ', ej.apellidos) AS jefe
			FROM empleados e
			JOIN empleados ej
			ON ej.id = e.id_jefe
			WHERE e.id = ?");
		$sth3->bindParam(1, $datos['id_usuario']);
		if(!$sth3->execute()) throw New Exception();
		$datos3 = $sth3->fetch();

		// Partes
		$sth = $this->_db->prepare("
			SELECT rp.id, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, CONCAT(er.nombre, ' ', er.apellidos) AS recibe, CONCAT(ep.nombre, ' ', ep.apellidos) AS procesa, d.nombre AS departamento, rp.fecha_creacion, rp.fecha_autorizacion, rp.fecha_recibo, rp.comentarios, rp.fecha_procesa
			FROM requisiciones_partes rp
			JOIN departamentos d
			ON d.id = rp.id_departamento
			JOIN empleados e
			ON e.id = rp.id_solicita
			LEFT JOIN empleados es
			ON es.id = rp.id_autoriza
			LEFT JOIN empleados er
			ON er.id = rp.id_recibe
			LEFT JOIN empleados ep
			ON ep.id = rp.id_procesa
			WHERE rp.id_requisicion = ?
			LIMIT 1");
		$sth->bindParam(1, $id);
		if(!$sth->execute()) throw New Exception();
		$datos = $sth->fetch();

		if (!empty($fechaCancelacion)) {
			$autorizaHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;
			$reciboHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;
			$procesaHtml = '<br />' . $datos['solicita'] . '<br />' . $fechaCancelacion;

			$firmasHtml = '
				<tr>
					<td style="width:50%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Solicita:</span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
					<td style="width:50%; text-align: center; color: #F00;"><span style="font-family: \'SanFranciscoBold\';">Cancela:</span>' . $autorizaHtml . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
				<tr>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
				</tr>
				<tr>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
				</tr>
			';
		} elseif (!empty($fechaRechazo)) {
			$autorizaHtml = '<br />' . $rechaza . '<br />' . $fechaRechazo;

			$firmasHtml = '
				<tr>
					<td style="width:50%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Solicita:</span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
					<td style="width:50%; text-align: center; color: #F00;"><span style="font-family: \'SanFranciscoBold\';">Rechaza:</span>' . $autorizaHtml . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
				<tr>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:50%; text-align: center; font-family: \'Roboto\';">___________________________</td>
				</tr>
				<tr>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:50%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
				</tr>
			';
		} else {
			if (!empty($datos['autoriza'])) {
				$autorizaHtml = '<br />' . $datos['autoriza'] . '<br />' . Modelos_Fecha::formatearFechaHora($datos['fecha_autorizacion']);
			} else {
				$autorizaHtml = '';
			}

			// Procesa
			$sth2 = $this->_db->prepare("
				SELECT CONCAT(ep.nombre, ' ', ep.apellidos) AS procesa, rp.fecha_procesa
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				JOIN empleados e
				ON e.id = rp.id_solicita
				LEFT JOIN empleados es
				ON es.id = rp.id_autoriza
				LEFT JOIN empleados er
				ON er.id = rp.id_recibe
				LEFT JOIN empleados ep
				ON ep.id = rp.id_procesa
				WHERE rp.id_requisicion = ? AND ep.nombre != '' AND fecha_procesa != '0000-00-00 00:00:00' LIMIT 1
			");
			$sth2->bindParam(1, $id);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();
			
			if (!empty($datos2['procesa'])) {
				$procesaHtml = '<br />' . $datos2['procesa'] . '<br />' . Modelos_Fecha::formatearFechaHora($datos2['fecha_procesa']);
			} else {
				$procesaHtml = '';
			}

			// Recibe
			$sth2 = $this->_db->prepare("
				SELECT CONCAT(er.nombre, ' ', er.apellidos) AS recibe, rp.fecha_recibo
				FROM requisiciones_partes rp
				JOIN departamentos d
				ON d.id = rp.id_departamento
				LEFT JOIN empleados er
				ON er.id = rp.id_recibe
				WHERE rp.id = ?
			");
			$sth2->bindParam(1, $idRecibo);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();

			if (!empty($datos2['recibe'])) {
				$reciboHtml = '<br />' . $datos2['recibe'] . '<br />' . Modelos_Fecha::formatearFechaHora($datos2['fecha_recibo']);
			} else {
				$reciboHtml = '';
			}


			$firmasHtml = '
				<tr>
					<td style="width:25%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Solicita:</span><br />' . $solicita . '<br />' . $fechaHoraCreacion . '</td>
					<td style="width:25%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Autoriza:</span>' . $autorizaHtml . '</td>
					<td style="width:25%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Procesa:</span>' . $procesaHtml . '</td>
					<td style="width:25%; text-align: center;"><span style="font-family: \'SanFranciscoBold\';">Recibe:</span>' . $reciboHtml . '</td>
				</tr>
				<tr>
					<td colspan="3" style="height: 0px;"></td>
				</tr>
				<tr>
					<td style="width:25%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:25%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:25%; text-align: center; font-family: \'Roboto\';">___________________________</td>
					<td style="width:25%; text-align: center; font-family: \'Roboto\';">___________________________</td>
				</tr>
				<tr>
					<td style="width:25%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:25%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:25%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
					<td style="width:25%; font-family: \'SanFrancisco\'; text-align: center;">Firma</td>
				</tr>
			';
		}
		
		$sth = $this->_db->prepare("
			SELECT producto, tipo, cantidad, um, justificacion, observaciones, rp.status
			FROM requisiciones_partes rp
			WHERE rp.id_requisicion = ?");
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

			if ($statusRequisicion == 2) {
				if ($datos['status'] == 1) {
					$status = 'AUTORIZADA';
				} elseif ($datos['status'] == 2) {
					$status = 'PROCESANDO';
				} elseif ($datos['status'] == 3) {
					$status = '<span style="color: #2E7E2E;">RECIBIDA</span>';
				} elseif ($datos['status'] == 4) {
					$status = '<span style="color: #2E7E2E;">ENTREGADA</span>';
				} elseif ($datos['status'] == 0) {
					$status = '<span style="color: #F00;">CANCELADA</span>';
				}
			} else {
				$status = 'PENDIENTE';
			}

			$htmlPartidas .= '<tr>';
			$htmlPartidas .= '<td style="text-align: center;">' . $status . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['producto'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['tipo'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['cantidad'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['um'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['justificacion'] . '</td>';
			$htmlPartidas .= '<td style="text-align: center;">' . $datos['observaciones'] . '</td>';
			$htmlPartidas .= '</tr>';

			$x++;
		}

		$stasis = STASIS;

		$html = <<<EOF
			<table style="font-size: 9px; text-align: left;" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width: 515px; color: #444;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">GRUPO VALCAS</span><br />

						<span style="font-size: 9px;">CALLE ESCUADRÓN 201, NO.3110 - INT D<br />
							COLONIA AVIACIÓN<br />
							TIJUANA, BAJA CALIFORNIA, MÉXICO<br />
							CEL. (664) 127-7175
						</span>
					</td>
					<td style="width: 270px; color: #444; text-align: right;">
						<span style="font-size: 14px; font-family: 'Roboto Bold';">REQUISICIÓN INTERNA</span><br />
						<img src="$stasis/img/gvalcas.png" height="43">
					</td>
				</tr>
			</table>
			<br /><br />

			<table style="text-align: left; font-size: 9px;" cellpadding="2" cellspacing="1">
				<tr>
					<td style="background-color: #00436C; color: #FFF; width: 20%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Fecha:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 30%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Solicitante:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 30%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Departamento:</strong>
					</td>
					<td style="background-color: #00436C; color: #FFF; width: 20%">
						<span style="text-align: center; font-family: 'SanFranciscoBold';">Folio:</strong>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;">$fechaCreacion</td>
					<td style="text-align: center;">$solicita</td>
					<td style="text-align: center;">$departamento</td>
					<td style="text-align: center;">$id</td>
				</tr>
			</table>
			<br /><br />

			<table style="font-size: 8px; text-align: left;" border="0" cellpadding="2" cellspacing="1">
				<tbody>
					<tr>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Status</td>
						<td style="width: 15%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Producto y/o Servicio</td>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Tipo</td>
						<td style="width: 7%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Cantidad</td>
						<td style="width: 10%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">UM</td>
						<td style="width: 26%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Especificación/Justificación</td>
						<td style="width: 22%; text-align: center; background-color: #0573BA; font-family: 'Roboto Bold'; color: #FFF;">Observaciones / Link Producto</td>
					</tr>
				</tbody>
				$htmlPartidas
			</table>
			<br /><br />

			<br /><br />

			$observacionesTexto

			<table style="font-size: 9px; width: 100%;">
				$firmasHtml
			</table><br /><br />
EOF;

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if ($descargar == 1) {
			$pdf->Output('F-' . $fechaCreacionPdf . '-' . $folioPdf . '_' . $clientePdf . '.pdf', 'D');
		} elseif ($visualizar == 1) {
			$pdf->Output($id . '-Requisicion_Interna.pdf', 'I');
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
	  		header('Location: ' . STASIS . '/movimientos/compras/historial/1');
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

	  		header('Location: ' . STASIS . '/movimientos/compras/historial/4');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cancelarRequisicion($id) {
		try {
			$sth = $this->_db->prepare("UPDATE requisiciones SET status = 0, fecha_cancelacion = NOW() WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/compras/historial/6');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cancelarParte($id) {
		try {
			$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 0, fecha_cancelacion = NOW(), id_rechaza = ? WHERE id = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/compras/historial/5');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function recibir($id) {
		try {
			$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 3, id_recibe = ?, fecha_recibo = NOW() WHERE id = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/compras/historial/3');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function entregar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 4, id_entrega = ?, fecha_entrega = NOW() WHERE id = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			$sth->bindParam(2, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/compras/historial/7');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function entregarMultiples() {
		try {
			$ids = $_POST['ids'];
			$ids = explode(',', $ids);

			foreach($ids as $id) {
				$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 4, id_entrega = ?, fecha_entrega = NOW() WHERE id = ?");
				$sth->bindParam(1, $_SESSION['login_id']);
				$sth->bindParam(2, $id);
				if(!$sth->execute()) throw New Exception();
			}

	  		header('Location: ' . STASIS . '/movimientos/compras/historial/7');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function recibirMultiples() {
		try {
			$ids = $_POST['ids'];
			$ids = explode(',', $ids);

			foreach($ids as $id) {
				$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 3, id_recibe = ?, fecha_recibo = NOW() WHERE id = ?");
				$sth->bindParam(1, $_SESSION['login_id']);
				$sth->bindParam(2, $id);
				if(!$sth->execute()) throw New Exception();
			}

	  		header('Location: ' . STASIS . '/movimientos/compras/historial/3');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function procesar() {
		try {
			$id = $_POST['id'];
			$oc = strtoupper($_POST['oc']);
			$dias_entrega = $_POST['dias_entrega'];
			$proveedor = $_POST['proveedor'];
			$cuenta_contable = $_POST['cuenta_contable'];

			$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 2, oc = ?, dias_entrega = ?, id_procesa = ?, fecha_procesa = NOW(), id_proveedor = ?, cuenta_contable = ? WHERE id = ?");
			$sth->bindParam(1, $oc);
			$sth->bindParam(2, $dias_entrega);
			$sth->bindParam(3, $_SESSION['login_id']);
			$sth->bindParam(4, $proveedor);
			$sth->bindParam(5, $cuenta_contable);
			$sth->bindParam(6, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/compras/historial/2');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function procesarAplicarCambios() {
		try {
			$id = $_POST['id'];
			$oc = strtoupper($_POST['oc']);
			$proveedor = $_POST['proveedor'];
			$cuenta_contable = $_POST['cuenta_contable'];

			$sth = $this->_db->prepare("UPDATE requisiciones_partes SET oc = ?, id_proveedor = ?, cuenta_contable = ? WHERE id = ?");
			$sth->bindParam(1, $oc);
			$sth->bindParam(2, $proveedor);
			$sth->bindParam(3, $cuenta_contable);
			$sth->bindParam(4, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/movimientos/compras/historial/8');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function procesarMultiples() {
		try {
			$ids = $_POST['ids'];
			$oc = strtoupper($_POST['oc']);
			$dias_entrega = $_POST['dias_entrega'];
			$proveedor = $_POST['proveedor'];
			$ids = explode(',', $ids);

			foreach($ids as $id) {
				$sth = $this->_db->prepare("UPDATE requisiciones_partes SET status = 2, oc = ?, dias_entrega = ?, id_procesa = ?, fecha_procesa = NOW(), id_proveedor = ? WHERE id = ?");
				$sth->bindParam(1, $oc);
				$sth->bindParam(2, $dias_entrega);
				$sth->bindParam(3, $_SESSION['login_id']);
				$sth->bindParam(4, $proveedor);
				$sth->bindParam(5, $id);
				if(!$sth->execute()) throw New Exception();
			}

	  		header('Location: ' . STASIS . '/movimientos/compras/historial/2');
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
		$objPHPExcel->getProperties()->setCreator("Grupo Valcas")->setTitle("Reporte de Requisiciones Autorizadas")->setSubject("Reporte de Requisiciones");
		$objPHPExcel->setActiveSheetIndex(0);

		// Facturas
    	$objPHPExcel->getActiveSheet()->setTitle("Requisiciones");
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Grupo Valcas');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Reporte de Requisiciones');
		$objPHPExcel->getActiveSheet()->getStyle("A1:O1")->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle("A1:O1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A1:O1")->getFill()->getStartColor()->setARGB('256BB3');
		$objPHPExcel->getActiveSheet()->getStyle("A1:O1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle("A1:O1")->getFont()->setBold(true);
    	
    	$i++;
    	$letra = 'A';

    	$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Folio Requisición'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Solicita'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Departamento'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Producto'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Tipo'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Cantidad'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Unidad de Medida'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Explicación y/o Justificación'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Observaciones / Link Producto'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Autoriza'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Fecha de Creación'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Fecha de Autorización');

		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->getStartColor()->setARGB('748F2C');
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		
		// Listado de facturas
		$i++;
    	$sth = $this->_db->query("
    		SELECT rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, rp.fecha_creacion, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, rp.fecha_autorizacion, rp.justificacion, rp.observaciones
			FROM requisiciones_partes rp
			JOIN departamentos d
			ON d.id = rp.id_departamento
			JOIN empleados e
			ON e.id = rp.id_solicita
			JOIN empleados es
			ON es.id = rp.id_autoriza
			WHERE rp.status = 1
			ORDER BY rp.id DESC
		");
    	if(!$sth->execute()) throw New Exception();
    	while ($datos = $sth->fetch()) {
			// $diasEntrega = $datos['dias_entrega'];
			// $fechaVencimiento = new DateTime($datos['fecha_creacion']);
			// $fechaVencimiento->modify("+$diasEntrega days");
			// $diasVencidos = $fechaActual->diff($fechaVencimiento);
			// $diasVencidos = (int)$diasVencidos->format("%r%a")+1;
			// if ($diasVencidos <= 0) {
			// 	$diasVencidos = 'ATRASADA';
			// }

			$letra = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['id_requisicion']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['solicita']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['departamento']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['producto']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['tipo']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['cantidad']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['um']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['justificacion']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['observaciones']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['autoriza']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", Modelos_Fecha::formatearFechaHora($datos['fecha_creacion'])); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", Modelos_Fecha::formatearFechaHora($datos['fecha_autorizacion']));

			// $objPHPExcel->getActiveSheet()->getStyle("I$i")->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
			$i++;
    	}

    	for ($col='A';$col!='K';$col++) $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setAutoFilter('A2:K2');

    	// Final de Excel
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="gvalcas_reporte_requisiciones.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	public function excelGlobal() {
		// Inicializadores
		ini_set('memory_limit', '1024M');
		set_time_limit(0);
		require_once(APP . 'inc/phpexcel/phpexcel.php');

		// Variables iniciales
		$fechaActual = new DateTime();

		// Inicializador Excel
		$i = 1;
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Grupo Valcas")->setTitle("Reporte de Requisiciones")->setSubject("Reporte de Requisiciones");
		$objPHPExcel->setActiveSheetIndex(0);

		// Facturas
    	$objPHPExcel->getActiveSheet()->setTitle("Requisiciones");
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Grupo Valcas');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Reporte Global de Requisiciones');
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFill()->getStartColor()->setARGB('256BB3');
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
		$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->setBold(true);
    	
    	$i++;
    	$letra = 'A';

    	$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Status'); $letra++;
    	$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Folio Requisición'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Solicita'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Departamento'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Producto'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Tipo'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Cantidad'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Unidad de Medida'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Explicación y/o Justificación'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Observaciones / Link Producto'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Autoriza'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Comprador'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Fecha Creación'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Fecha Autorización'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Fecha Procesando'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Fecha Entregada'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Fecha Recibida'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Tiempo de Autorización a Entrega'); $letra++;
		$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", 'Tiempo de Procesando a Entrega');

		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFill()->getStartColor()->setARGB('748F2C');
		$objPHPExcel->getActiveSheet()->getStyle("A$i:$letra$i")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

		if (!empty($_POST['fechaInicio']) && !empty($_POST['fechaFin'])) {
			$fechaInicio = DateTime::createFromFormat('d/m/Y', $_POST['fechaInicio']);
			$fechaInicio = $fechaInicio->format('Y-m-d');
			$fechaFin = DateTime::createFromFormat('d/m/Y', $_POST['fechaFin']);
			$fechaFin = $fechaFin->format('Y-m-d');

			$qry = "WHERE rp.fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin'";
		} else {
			$qry = '';
		}
		
		// Listado de facturas
		$i++;
    	$sth = $this->_db->query("
    		SELECT r.status AS status_requisicion, rp.status AS status_parte, r.id, rp.id, rp.id_requisicion, CONCAT(e.nombre, ' ', e.apellidos) AS solicita, CONCAT(ea.nombre, ' ', ea.apellidos) AS autoriza, CONCAT(ep.nombre, ' ', ep.apellidos) AS procesa, d.nombre AS departamento, rp.producto, rp.tipo, rp.cantidad, rp.um, rp.fecha_creacion, CONCAT(es.nombre, ' ', es.apellidos) AS recibe, rp.fecha_recibo, rp.dias_entrega, rp.oc, CONCAT(es.nombre, ' ', es.apellidos) AS autoriza, rp.fecha_autorizacion, rp.justificacion, rp.observaciones, rp.fecha_entrega, rp.fecha_procesa
    		FROM requisiciones_partes rp
    		LEFT JOIN departamentos d
    		ON d.id = rp.id_departamento
    		LEFT JOIN empleados e
    		ON e.id = rp.id_solicita
    		LEFT JOIN empleados es
    		ON es.id = rp.id_recibe
    		JOIN requisiciones r
    		ON r.id = rp.id_requisicion
    		LEFT JOIN empleados ej
    		ON ej.id = e.id_jefe
    		LEFT JOIN empleados ea
    		ON ea.id = rp.id_autoriza
    		LEFT JOIN empleados ep
    		ON ep.id = rp.id_procesa
    		$qry
    		ORDER BY rp.id_requisicion DESC
		");

    	if(!$sth->execute()) throw New Exception();
    	while ($datos = $sth->fetch()) {
    		$nStatusRequisicion = $datos['status_requisicion'];
    		$nStatusParte = $datos['status_parte'];

    		$status = '';
    		switch ($nStatusRequisicion) {
    			case 1:
    			$status = 'PENDIENTE';
    			break;

    			case 2:
    			if ($nStatusParte == 1) {
	    			$status = 'AUTORIZADA';
    			} elseif ($nStatusParte == 2) {
	    			$status = 'PROCESANDO';
    			} elseif ($nStatusParte == 3) {
	    			$status = 'RECIBIDA';
    			} elseif ($nStatusParte == 4) {
	    			$status = 'ENTREGADA';
    			} elseif ($nStatusParte == 0) {
	    			$status = 'RECHAZADA/CANCELADA';
    			}
    			break;

    			case 0:
    			$status = 'CANCELADA';
    			break;

    		}

			// $fechaActual = new DateTime(date('Y-m-d 00:00:00'));
			// $diasEntrega = $datos['dias_entrega'];

			// $fechaVencimiento = new DateTime($datos['fecha_procesa']);
			// $fechaVencimiento->modify("+$diasEntrega days");

			// $diasVencidos = $fechaActual->diff($fechaVencimiento);
			// $diasVencidos = $diasVencidos->format("%r%a");

			// if ($diasVencidos >= 1) {
			// 	$status = $diasVencidos;
			// } elseif ($diasVencidos == 0) {
			// 	$status = 'HOY';
			// } else {
			// 	$status = 'ATRASADA';
			// }

			if ($datos['fecha_autorizacion'] != '0000-00-00 00:00:00') {
				$fechaAutorizacion = Modelos_Fecha::formatearFechaHora($datos['fecha_autorizacion']);
			} else {
				$fechaAutorizacion = '';
			}

			if ($datos['fecha_procesa'] != '0000-00-00 00:00:00') {
				$fechaProcesa = Modelos_Fecha::formatearFechaHora($datos['fecha_procesa']);
			} else {
				$fechaProcesa = '';
			}

			if ($datos['fecha_entrega'] != '0000-00-00 00:00:00') {
				$fechaEntrega = Modelos_Fecha::formatearFechaHora($datos['fecha_entrega']);
			} else {
				$fechaEntrega = '';
			}

			if ($datos['fecha_recibo'] != '0000-00-00 00:00:00') {
				$fechaRecibo = Modelos_Fecha::formatearFechaHora($datos['fecha_recibo']);
			} else {
				$fechaRecibo = '';
			}

			$diasDiferenciaUno = '';
			if ($fechaAutorizacion && $fechaEntrega) {
				$fechaAutorizacionCheck = new DateTime($datos['fecha_autorizacion']);
				$fechaEntregaCheck = new DateTime($datos['fecha_entrega']);
				$diasDiferenciaUno = $fechaAutorizacionCheck->diff($fechaEntregaCheck);
				$diasDiferenciaUno = $diasDiferenciaUno->format("%r%a");
			}

			$diasDiferenciaDos = '';
			if ($fechaProcesa && $fechaEntrega) {
				$fechaProcesaCheck = new DateTime($datos['fecha_procesa']);
				$fechaEntregaCheck = new DateTime($datos['fecha_entrega']);
				$diasDiferenciaDos = $fechaProcesaCheck->diff($fechaEntregaCheck);
				$diasDiferenciaDos = $diasDiferenciaDos->format("%r%a");
			}

			$letra = 'A';
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $status); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['id_requisicion']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['solicita']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['departamento']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['producto']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['tipo']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['cantidad']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['um']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['justificacion']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['observaciones']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['autoriza']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $datos['procesa']); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", Modelos_Fecha::formatearFechaHora($datos['fecha_creacion'])); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $fechaAutorizacion); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $fechaProcesa); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $fechaEntrega); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $fechaRecibo); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $diasDiferenciaUno); $letra++;
			$objPHPExcel->getActiveSheet()->setCellValue("$letra$i", $diasDiferenciaDos);
			$i++;
    	}

		$objPHPExcel->getActiveSheet()->setAutoFilter('A2:S2');

    	// Final de Excel
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="gvalcas_reporte_requisiciones.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

}