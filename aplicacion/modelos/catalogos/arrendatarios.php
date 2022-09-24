<?php
final class Modelos_Catalogos_Arrendatarios extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function listado() {
		try {
			$datosVista = array();

			// Activos
			$sth = $this->_db->query("
				SELECT *
				FROM arrendatarios
				ORDER BY id DESC
			");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$fechaTimeStamp = new DateTime($datos['fecha_alta']);
				$fechaTimeStamp = $fechaTimeStamp->getTimestamp();

				$arreglo = array(
					'id' => $datos['id'],
					'id_arrendatario' => $datos['id_arrendatario'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'rfc' => $datos['rfc'],
					'fecha_alta' => Modelos_Fecha::formatearFechaHora($datos['fecha_alta']),
					'fechaTimeStamp' => $fechaTimeStamp,
					'tipo' => mb_strtoupper($datos['tipo'], 'UTF-8'),
					'lote' => $datos['lote'],
					'asignador' => mb_strtoupper($datos['asignador'], 'UTF-8'),
				);

				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			// $sth = $this->_db->query("SELECT *
			// 	FROM campanas
			// 	WHERE status = 0
			// 	ORDER BY nombre ASC");
			// if(!$sth->execute()) throw New Exception();
			
			// $inactivos = array();
			// while ($datos = $sth->fetch()) {
			// 	$arreglo = array(
			// 		'id' => $datos['id'],
			// 		'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8')
			// 	);
			// 	$inactivos[] = $arreglo;
			// }

			// $datosVista['inactivos'] = $inactivos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function nuevo($datos) {
		try {
			$nombre = strtoupper($datos['nombre']);

			$arregloDatos = array($nombre);
			$sth = $this->_db->prepare("INSERT INTO campanas (nombre) VALUES (?)");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Campaña agregada exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificarGuardar($datos) {
		try {
			$id = $datos['id'];
			$nombre = strtoupper($datos['nombre']);
			
			$arregloDatos = array($nombre, $id);
			$sth = $this->_db->prepare("UPDATE campanas SET
										nombre = ?
										WHERE id = ?");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Campaña modificada exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM campanas WHERE id = ?");
			$sth->bindParam(1, $id);
			$sth->setFetchMode(PDO::FETCH_INTO, $this);
			if(!$sth->execute()) throw New Exception();
			$sth->fetch();

	  		return $this;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function inactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE campanas SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/campanas');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE campanas SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/campanas');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadocampanas($idEmpleado = null) {
		try {
			if (isset($idEmpleado)) {
				$sth = $this->_db->prepare("SELECT id_departamento FROM empleados WHERE id = ?");
				$sth->bindParam(1, $idEmpleado);
				if(!$sth->execute()) throw New Exception();
				$idDepartamento = $sth->fetchColumn();
			}

			$sth = $this->_db->query("SELECT id, nombre
				FROM campanas
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idDepartamento)) {
					if ($idDepartamento == $datos['id']) {
						$html .= '<option value="' . $datos['nombre'] . '" selected>' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['nombre'] . '">' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['nombre'] . '">' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function cuotas($id) {
		try {
			$fechaActual = new DateTime();
			$datosVista = array();

			// ID Contrato
			$sth = $this->_db->prepare("SELECT id_arrendatario FROM arrendatarios WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$idArrendatario = $sth->fetchColumn();

			$sth = $this->_db->prepare("
				SELECT a.lote, am.periodo, am.fecha_creacion, am.fecha_pagado, am.moneda, am.importe, am.status
				FROM cobranza_mantenimientos am
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				WHERE am.id_arrendatario = ?
				ORDER BY am.id DESC
			");
			$sth->bindParam(1, $idArrendatario);
			if(!$sth->execute()) throw New Exception();

			$activos = array();
			while ($datos = $sth->fetch()) {
				if ($datos['status'] == 1) {

				}

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = '';

				$fechaCreacion = new DateTime($datos['fecha_creacion']);
				$mesCreacion = $fechaCreacion->format('m');
				$anoCreacion = $fechaCreacion->format('Y');

				switch ($mesCreacion) {
					case '01': $mes = 'ENERO'; break;
					case '02': $mes = 'FEBRERO'; break;
					case '03': $mes = 'MARZO'; break;
					case '04': $mes = 'ABRIL'; break;
					case '05': $mes = 'MAYO'; break;
					case '06': $mes = 'JUNIO'; break;
					case '07': $mes = 'JULIO'; break;
					case '08': $mes = 'AGOSTO'; break;
					case '09': $mes = 'SEPTIEMBRE'; break;
					case '10': $mes = 'OCTUBRE'; break;
					case '11': $mes = 'NOVIEMBRE'; break;
					case '12': $mes = 'DICIEMBRE'; break;
				}
				$fechaVencimiento = '10' . '/' . $mesCreacion . '/' . $anoCreacion;
				$fechaVencimientoSql = $anoCreacion . '-' . $mesCreacion . '-10';

				$earlier = new DateTime();
				$later = new DateTime($fechaVencimientoSql);

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
                    $status = '<div class="badge badge-lg badge-danger">Vencido</div>';

                    // if ($fechaCreacion >= new DateTime('2022-09-01')) {
                    if ($datos['fecha_pagado'] == '0000-00-00 00:00:00') {
	                	$penalidad = '<span class="text-danger">$ ' . number_format($datos['importe']*.1, 2, '.', ',') . ' ' . $moneda . '</span>';
	                    $total = $datos['importe'] + $datos['importe']*.1;
	                } else {
	                	$penalidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    	$total = $datos['importe'];
	                }
                } else if ($abs_diff <= 16) {
                    $status = '<div class="badge badge-lg badge-warning">Próximo a Vencer</div>';
                    $penalidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $total = $datos['importe'];
                } else {
                    $status = '<div class="badge badge-lg badge-success">Al Corriente</div>';
                    $penalidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $total = $datos['importe'];
				}

				if ($datos['fecha_pagado'] == '0000-00-00 00:00:00') {
					$fechaPagado = '';
				} else {
					$fechaPagado = Modelos_Fecha::formatearFecha($datos['fecha_pagado']);
					$status = '<div class="badge badge-lg badge-primary">Pagado</div>';
				}

				$arreglo = array(
					'periodo' => $datos['periodo'],
					'lote' => $datos['lote'],
					'concepto' => $mes . ' ' . $anoCreacion,
					'fecha_pagado' => $fechaPagado,
					'fecha_vencimiento' => $fechaVencimiento,
					'status' => $status,
					'penalidad' => $penalidad,
					'total' => '$ ' . number_format($total, 2, '.', ',') . ' ' . $moneda,
					'importe' => '$ ' . number_format($datos['importe'], 2, '.', ',') . ' ' . $moneda,
				);

				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function amortizacion($id) {
		try {
			$fechaActual = new DateTime();
			$datosVista = array();

			// ID Arendatario
			$sth = $this->_db->prepare("SELECT id_arrendatario FROM arrendatarios WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$idArrendatario = $sth->fetchColumn();

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, am.id_contrato, c.inicio_vig
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE am.id_arrendatario = ? AND tipo_doc = 'V'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idArrendatario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
                    $status = '<div class="badge badge-lg badge-danger">Vencido</div>';
                    $penalidad = '<span class="text-danger">$ ' . number_format($datos['importe']*.1, 2, '.', ',') . ' ' . $moneda . '</span>';
                    $morosidad = '<span class="text-danger">$ ' . number_format($datos['importe']*.01, 2, '.', ',') . ' ' . $moneda . '</span>';

                    $total = $datos['importe'] + $datos['importe']*.1 + $datos['importe']*.01;
                } else if ($abs_diff <= 16) {
                    $status = '<div class="badge badge-lg badge-warning">Próximo a Vencer</div>';
                    $penalidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $morosidad = '<span class="text-danger">$ ' . number_format($datos['importe']*.01, 2, '.', ',') . ' ' . $moneda . '</span>';
                    $total = $datos['importe'];
                } else {
                    $status = '<div class="badge badge-lg badge-success">Al Corriente</div>';
                    $penalidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $morosidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $total = $datos['importe'];
                }

				if ($datos['fecha_pagado'] == '0000-00-00 00:00:00') {
					$fechaPagado = '';
				} else {
					$fechaPagado = Modelos_Fecha::formatearFecha($datos['fecha_pagado']);
					$status = '<div class="badge badge-lg badge-primary">Pagado</div>';
				}

				$arreglo = array(
					'lote' => $datos['lote'],
					'periodo' => $datos['periodo'],
					'concepto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'fecha_pagado' => $fechaPagado,
					'fecha_vencimiento' => $fechaVencimientoFormatted,
					'status' => $status,
					'penalidad' => $penalidad,
					'morosidad' => $morosidad,
					'total' => '$ ' . number_format($total, 2, '.', ',') . ' ' . $moneda,
					'importe' => '$ ' . number_format($datos['importe'], 2, '.', ',') . ' ' . $moneda,
				);

				$arrayDatos[] = $arreglo;
			}
	  		$datosVista['mensualidades'] = $arrayDatos;

	  		// Enganches
	  		$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, am.id_contrato, c.inicio_vig
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE am.id_arrendatario = ? AND tipo_doc = 'P'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idArrendatario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
                    $status = '<div class="badge badge-lg badge-danger">Vencido</div>';
                    $penalidad = '<span class="text-danger">$ ' . number_format($datos['importe']*.1, 2, '.', ',') . ' ' . $moneda . '</span>';
                    $morosidad = '<span class="text-danger">$ ' . number_format($datos['importe']*.01, 2, '.', ',') . ' ' . $moneda . '</span>';

                    $total = $datos['importe'] + $datos['importe']*.1 + $datos['importe']*.01;
                } else if ($abs_diff <= 16) {
                    $status = '<div class="badge badge-lg badge-warning">Próximo a Vencer</div>';
                    $penalidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $morosidad = '<span class="text-danger">$ ' . number_format($datos['importe']*.01, 2, '.', ',') . ' ' . $moneda . '</span>';
                    $total = $datos['importe'];
                } else {
                    $status = '<div class="badge badge-lg badge-success">Al Corriente</div>';
                    $penalidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $morosidad = '<span class="text-muted">$ 0.00 ' . $moneda . '</span>';
                    $total = $datos['importe'];
                }

				if ($datos['fecha_pagado'] == '0000-00-00 00:00:00') {
					$fechaPagado = '';
				} else {
					$fechaPagado = Modelos_Fecha::formatearFecha($datos['fecha_pagado']);
					$status = '<div class="badge badge-lg badge-primary">Pagado</div>';
				}

				$arreglo = array(
					'lote' => $datos['lote'],
					'periodo' => $datos['periodo'],
					'concepto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'fecha_pagado' => $fechaPagado,
					'fecha_vencimiento' => $fechaVencimientoFormatted,
					'status' => $status,
					'penalidad' => $penalidad,
					'morosidad' => $morosidad,
					'total' => '$ ' . number_format($total, 2, '.', ',') . ' ' . $moneda,
					'importe' => '$ ' . number_format($datos['importe'], 2, '.', ',') . ' ' . $moneda,
				);

				$arrayDatos[] = $arreglo;
			}
	  		$datosVista['enganches'] = $arrayDatos;

	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiVerificacion($idContrato) {
		try {
			$json = array();

			$sth = $this->_db->prepare("
				SELECT c.id_contrato, c.id_arrendatario, c.id_edificio, c.tipo, a.nombre AS empresa
				FROM contratos c
				JOIN arrendadoras a
				ON a.id_arrendadora = c.id_arrendadora
				WHERE c.id_arrendatario = ?
			");
			$sth->bindParam(1, $idContrato);
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id_contrato' => $datos['id_contrato'],
					'id_arrendatario' => $datos['id_arrendatario'],
					'id_edificio' => $datos['id_edificio'],
					'tipo' => $datos['tipo'],
					'empresa' => $datos['empresa'],
				);

				$json[] = $arreglo;
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiCuotas($idContrato) {
		try {
			$json = array();
			$vencidos = 0;
			$proximosVencer = 0;
			$corriente = 0;

			$sth = $this->_db->prepare("
				SELECT hr.id, a.lote, hr.periodo AS concepto, hr.fecha_pagado, hr.moneda, hr.importe
				FROM historial_recibos hr
				JOIN arrendatarios a
				ON a.id_arrendatario = hr.id_arrendatario
				WHERE hr.id_arrendatario = ? AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY hr.id ASC
			");
			$sth->bindParam(1, $idContrato);
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$conceptoSplit = explode(' ', $datos['concepto']);
				$fechaVencimiento = '';
				if (count($conceptoSplit) == 2) {
					switch ($conceptoSplit[0]) {
						case 'ENERO': $mes = '01'; break;
						case 'FEBRERO': $mes = '02'; break;
						case 'MARZO': $mes = '03'; break;
						case 'ABRIL': $mes = '04'; break;
						case 'MAYO': $mes = '05'; break;
						case 'JUNIO': $mes = '06'; break;
						case 'JULIO': $mes = '07'; break;
						case 'AGOSTO': $mes = '08'; break;
						case 'SEPTIEMBRE': $mes = '09'; break;
						case 'OCTUBRE': $mes = '10'; break;
						case 'NOVIEMBRE': $mes = '11'; break;
						case 'DICIEMBRE': $mes = '12'; break;
					}
					$fechaVencimientoSql = $conceptoSplit[1] . '-' . $mes . '-10';

					$earlier = new DateTime();
					$later = new DateTime($fechaVencimientoSql);

					$abs_diff = $later->diff($earlier)->format("%a");
					$total = 0;

					if ($earlier > $later) {
						$vencidos++;
	                    $status = 'Vencido';
	                    $penalidad = number_format($datos['importe']*.1, 2, '.', ',');
	                    $total = $datos['importe'] + $datos['importe']*.1;
	                } else if ($abs_diff <= 16) {
	                	$proximosVencer++;
	                    $status = 'Próximo a Vencer';
	                    $penalidad = '0.00';
	                    $total = $datos['importe'];
	                } else {
	                	$corriente++;
	                    $status = 'Al Corriente';
	                    $penalidad = '0.00';
	                    $total = $datos['importe'];
	                }
				}

				$arreglo = array(
					'id' => $datos['id'],
					'lote' => $datos['lote'],
					'concepto' => $datos['concepto'],
					'fecha_vencimiento' => $fechaVencimientoSql,
					'status' => $status,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => $penalidad,
					'total' => number_format($total, 2, '.', ','),
				);

				$json['cuotas'][] = $arreglo;
			}

			if ($vencidos != 0) {
				$json['mensaje'] = "¡Oops! Parece que se te pasó el pago, tienes $vencidos pagos vencidos.";
			} elseif ($proximosVencer != 0) {
				$json['mensaje'] = "¡Que no se te olvide! Tienes $proximosVencer pagos próximos a vencer.";
			} else {
				$json['mensaje'] = "¡Excelente! Estás al corriente con tus pagos de cuotas de mantenimiento.";
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiMensualidades($idPropietario) {
		try {
			$json = array();
			$vencidos = 0;
			$mesesVencidos = 0;
			$proximosVencer = 0;
			$corriente = 0;

			$fechaActual = new DateTime();
			$datosVista = array();

			$sth = $this->_db->prepare("SELECT id_contrato, inicio_vig FROM contratos WHERE id_arrendatario = ? AND tipo = 'V'");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();
			
			$idContrato = $datos['id_contrato'];
			$inicioVigencia = $datos['inicio_vig'];

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				WHERE am.id_arrendatario = ? AND tipo_doc = 'V' AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$periodo = $datos['periodo']-1;

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				if ($earlier > $later) {
					$mesesVencidos++;
                }
			}

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				WHERE am.id_arrendatario = ? AND tipo_doc = 'V' AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			$mesesVencidosRev = $mesesVencidos;
			while ($datos = $sth->fetch()) {
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('10/m/Y');
				$fechaVencimientoSql = $fechaVencimiento->format('Y-m-10');
				$fechaVencimientoMes = $fechaVencimiento->format('m');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
					$mesesVencidosFor = $datos['importe']*($mesesVencidosRev/100);

					$vencidos++;
                    $status = 'Vencido';
                    $penalidad = number_format($datos['importe']*.1, 2, '.', '');
                    $morosidad = number_format($mesesVencidosFor, 2, '.', '');
                    $total = $datos['importe'] + $penalidad + $mesesVencidosFor;

                    $mesesVencidosRev--;
                } else if ($abs_diff <= 16) {
                	$proximosVencer++;
                    $status = 'Próximo a Vencer';
                    $penalidad = '0.00';
                    $morosidad = '0.00';
                    $total = $datos['importe'];
                } else {
                	$corriente++;
                    $status = 'Al Corriente';
                    $penalidad = '0.00';
                    $morosidad = '0.00';
                    $total = $datos['importe'];
                }

				if ($datos['fecha_pagado'] == '0000-00-00 00:00:00') {
					$fechaPagado = '';
				} else {
					$fechaPagado = Modelos_Fecha::formatearFecha($datos['fecha_pagado']);
					$status = '<div class="badge badge-lg badge-primary">Pagado</div>';
				}

				switch ($fechaVencimientoMes) {
                    case '01': $mesFormatted = 'ENERO'; break;
                    case '02': $mesFormatted = 'FEBRERO'; break;
                    case '03': $mesFormatted = 'MARZO'; break;
                    case '04': $mesFormatted = 'ABRIL'; break;
                    case '05': $mesFormatted = 'MAYO'; break;
                    case '06': $mesFormatted = 'JUNIO'; break;
                    case '07': $mesFormatted = 'JULIO'; break;
                    case '08': $mesFormatted = 'AGOSTO'; break;
                    case '09': $mesFormatted = 'SEPTIEMBRE'; break;
                    case '10': $mesFormatted = 'OCTUBRE'; break;
                    case '11': $mesFormatted = 'NOVIEMBRE'; break;
                    case '12': $mesFormatted = 'DICIEMBRE'; break;
                }

				$arreglo = array(
					'id' => $datos['id'],
					'lote' => $datos['lote'],
					'concepto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'fecha_vencimiento' => $fechaVencimientoSql,
					'fecha_formatteada' => $fechaVencimientoFormatted,
					'mes_formatteado' => $mesFormatted,
					'ano_formatteado' => $fechaVencimiento->format('Y'),
					'status' => $status,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => number_format($penalidad, 2, '.', ','),
					'morosidad' => number_format($morosidad, 2, '.', ','),
					'total' => number_format($total, 2, '.', ','),
				);

				$json['mensualidades'][] = $arreglo;
			}

			if ($vencidos != 0) {
				$json['mensaje'] = "¡Oops! Parece que se te pasó el pago, tienes $vencidos pagos vencidos.";
			} elseif ($proximosVencer != 0) {
				$json['mensaje'] = "¡Que no se te olvide! Tienes $proximosVencer pagos próximos a vencer.";
			} else {
				$json['mensaje'] = "¡Excelente! Estás al corriente con tus pagos mensuales.";
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiEnganches($idPropietario) {
		try {
			$json = array();
			$vencidos = 0;
			$mesesVencidos = 0;
			$proximosVencer = 0;
			$corriente = 0;

			$fechaActual = new DateTime();
			$datosVista = array();

			$sth = $this->_db->prepare("SELECT id_contrato, inicio_vig FROM contratos WHERE id_arrendatario = ? AND tipo = 'P'");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();
			
			$idContrato = $datos['id_contrato'];
			$inicioVigencia = $datos['inicio_vig'];

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				WHERE am.id_arrendatario = ? AND tipo_doc = 'P' AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$periodo = $datos['periodo']-1;

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				if ($earlier > $later) {
					$mesesVencidos++;
                }
			}

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				WHERE am.id_arrendatario = ? AND tipo_doc = 'P' AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			$mesesVencidosRev = $mesesVencidos;
			while ($datos = $sth->fetch()) {
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('10/m/Y');
				$fechaVencimientoSql = $fechaVencimiento->format('Y-m-10');
				$fechaVencimientoMes = $fechaVencimiento->format('m');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
					$mesesVencidosFor = $datos['importe']*($mesesVencidosRev/100);

					$vencidos++;
                    $status = 'Vencido';
                    $penalidad = number_format($datos['importe']*.1, 2, '.', '');
                    $morosidad = number_format($mesesVencidosFor, 2, '.', '');
                    $total = $datos['importe'] + $penalidad + $mesesVencidosFor;

                    $mesesVencidosRev--;
                } else if ($abs_diff <= 16) {
                	$proximosVencer++;
                    $status = 'Próximo a Vencer';
                    $penalidad = '0.00';
                    $morosidad = '0.00';
                    $total = $datos['importe'];
                } else {
                	$corriente++;
                    $status = 'Al Corriente';
                    $penalidad = '0.00';
                    $morosidad = '0.00';
                    $total = $datos['importe'];
                }

				if ($datos['fecha_pagado'] == '0000-00-00 00:00:00') {
					$fechaPagado = '';
				} else {
					$fechaPagado = Modelos_Fecha::formatearFecha($datos['fecha_pagado']);
					$status = '<div class="badge badge-lg badge-primary">Pagado</div>';
				}

				switch ($fechaVencimientoMes) {
                    case '01': $mesFormatted = 'ENERO'; break;
                    case '02': $mesFormatted = 'FEBRERO'; break;
                    case '03': $mesFormatted = 'MARZO'; break;
                    case '04': $mesFormatted = 'ABRIL'; break;
                    case '05': $mesFormatted = 'MAYO'; break;
                    case '06': $mesFormatted = 'JUNIO'; break;
                    case '07': $mesFormatted = 'JULIO'; break;
                    case '08': $mesFormatted = 'AGOSTO'; break;
                    case '09': $mesFormatted = 'SEPTIEMBRE'; break;
                    case '10': $mesFormatted = 'OCTUBRE'; break;
                    case '11': $mesFormatted = 'NOVIEMBRE'; break;
                    case '12': $mesFormatted = 'DICIEMBRE'; break;
                }

				$arreglo = array(
					'id' => $datos['id'],
					'lote' => $datos['lote'],
					'concepto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'fecha_vencimiento' => $fechaVencimientoSql,
					'fecha_formatteada' => $fechaVencimientoFormatted,
					'mes_formatteado' => $mesFormatted,
					'ano_formatteado' => $fechaVencimiento->format('Y'),
					'status' => $status,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => number_format($penalidad, 2, '.', ','),
					'morosidad' => number_format($morosidad, 2, '.', ','),
					'total' => number_format($total, 2, '.', ','),
				);

				$json['mensualidades'][] = $arreglo;
			}

			if ($vencidos != 0) {
				$json['mensaje'] = "¡Oops! Parece que se te pasó el pago, tienes $vencidos pagos vencidos.";
			} elseif ($proximosVencer != 0) {
				$json['mensaje'] = "¡Que no se te olvide! Tienes $proximosVencer pagos próximos a vencer.";
			} else {
				$json['mensaje'] = "¡Excelente! Estás al corriente con tus pagos mensuales.";
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiConceptos() {
		try {
			$ids = $_POST['ids'];
			$json = array();

			$sth = $this->_db->query("
				SELECT hr.id, periodo AS concepto, importe, moneda, hr.lote
				FROM historial_recibos hr
				JOIN arrendatarios a
				ON a.id_arrendatario = hr.id_arrendatario
				WHERE hr.id IN(" . $ids . ")
				ORDER BY hr.id ASC
			");
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$conceptoSplit = explode(' ', $datos['concepto']);
				$fechaVencimiento = '';
				if (count($conceptoSplit) == 2) {
					switch ($conceptoSplit[0]) {
						case 'ENERO': $mes = '01'; break;
						case 'FEBRERO': $mes = '02'; break;
						case 'MARZO': $mes = '03'; break;
						case 'ABRIL': $mes = '04'; break;
						case 'MAYO': $mes = '05'; break;
						case 'JUNIO': $mes = '06'; break;
						case 'JULIO': $mes = '07'; break;
						case 'AGOSTO': $mes = '08'; break;
						case 'SEPTIEMBRE': $mes = '09'; break;
						case 'OCTUBRE': $mes = '10'; break;
						case 'NOVIEMBRE': $mes = '11'; break;
						case 'DICIEMBRE': $mes = '12'; break;
					}
					$fechaVencimientoSql = $conceptoSplit[1] . '-' . $mes . '-10';

					$earlier = new DateTime();
					$later = new DateTime($fechaVencimientoSql);

					$abs_diff = $later->diff($earlier)->format("%a");
					$total = 0;

					if ($earlier > $later) {
	                    $penalidad = number_format($datos['importe']*.1, 2, '.', ',');
	                    $total = $datos['importe'] + $datos['importe']*.1;
	                } else if ($abs_diff <= 16) {
	                    $penalidad = 0.00;
	                    $total = $datos['importe'];
	                } else {
	                    $penalidad = 0.00;
	                    $total = $datos['importe'];
	                }
				}

				$arreglo = array(
					'id' => $datos['id'],
					'lote' => $datos['lote'],
					'concepto' => 'CUOTA DE MANTENIMIENTO ' . $datos['concepto'],
					'concepto_corto' => $datos['concepto'],
					'fecha_vencimiento' => $fechaVencimientoSql,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => $penalidad,
					'morosidad' => '0.00',
					'total' => number_format($total, 2, '.', ','),
				);

				$json[] = $arreglo;
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiConceptosMensualidades() {
		try {
			$ids = $_POST['ids'];
			$json = array();
			$mesesVencidos = 0;

			$fechaActual = new DateTime();
			$datosVista = array();

			// Numero de contrato
			$sth = $this->_db->query("
				SELECT c.id_arrendatario
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE am.id IN(" . $ids . ") AND fecha_pagado = '0000-00-00 00:00:00'
				LIMIT 1
			");
			if(!$sth->execute()) throw New Exception();
			$idContrato = $sth->fetchColumn();

			// Meses vencidos
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE a.id_arrendatario = ? AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idContrato);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				if ($earlier > $later) {
					$mesesVencidos++;
                }
			}

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig, ar.nombre AS empresa
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				JOIN arrendadoras ar
				ON ar.id_arrendadora = am.id_arrendadora
				WHERE am.id IN(" . $ids . ") AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			$mesesVencidosRev = $mesesVencidos;

			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');
				$fechaVencimientoSql = $fechaVencimiento->format('Y-m-d');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
					$mesesVencidosFor = $datos['importe']*($mesesVencidosRev/100);

			        $penalidad = number_format($datos['importe']*.1, 2, '.', '');
			        $morosidad = number_format($mesesVencidosFor, 2, '.', '');
			        $total = $datos['importe'] + $penalidad + $mesesVencidosFor;

			        $mesesVencidosRev--;
			    } else if ($abs_diff <= 16) {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                } else {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                }

                if ($datos['empresa'] == 'INMOBILIARIA RANCHO TECATE, S DE RL. DE CV.') {
					$empresa = 'IRT';
				} else if ($datos['empresa'] == 'RGR GLOBAL BUSINESS, S DE RL. DE CV') {
					$empresa = 'RGR';
				} else {
					$empresa = '';
				}

				$arreglo = array(
					'id' => $datos['id'],
					'lote' => $datos['lote'],
					'empresa' => $empresa,
					'concepto' => 'MENSUALIDAD ' . mb_strtoupper($datos['concepto'], 'UTF-8'),
					'concepto_corto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'fecha_vencimiento' => $fechaVencimientoSql,
					'fecha_formatteada' => $fechaVencimientoFormatted,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => number_format($penalidad, 2, '.', ','),
					'morosidad' => number_format($morosidad, 2, '.', ','),
					'total' => number_format($total, 2, '.', ','),
				);

				$json[] = $arreglo;
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiConceptosEnganches() {
		try {
			$ids = $_POST['ids'];
			$json = array();
			$mesesVencidos = 0;

			$fechaActual = new DateTime();
			$datosVista = array();

			// Numero de contrato
			$sth = $this->_db->query("
				SELECT c.id_arrendatario
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE am.id IN(" . $ids . ") AND fecha_pagado = '0000-00-00 00:00:00'
				LIMIT 1
			");
			if(!$sth->execute()) throw New Exception();
			$idContrato = $sth->fetchColumn();

			// Meses vencidos
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE a.id_arrendatario = ? AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idContrato);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				if ($earlier > $later) {
					$mesesVencidos++;
                }
			}

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig, ar.nombre AS empresa
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				JOIN arrendadoras ar
				ON ar.id_arrendadora = am.id_arrendadora
				WHERE am.id IN(" . $ids . ") AND fecha_pagado = '0000-00-00 00:00:00'
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idPropietario);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			$mesesVencidosRev = $mesesVencidos;

			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');
				$fechaVencimientoSql = $fechaVencimiento->format('Y-m-d');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
					$mesesVencidosFor = $datos['importe']*($mesesVencidosRev/100);

			        $penalidad = number_format($datos['importe']*.1, 2, '.', '');
			        $morosidad = number_format($mesesVencidosFor, 2, '.', '');
			        $total = $datos['importe'] + $penalidad + $mesesVencidosFor;

			        $mesesVencidosRev--;
			    } else if ($abs_diff <= 16) {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                } else {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                }

                if ($datos['empresa'] == 'INMOBILIARIA RANCHO TECATE, S DE RL. DE CV.') {
					$empresa = 'IRT';
				} else if ($datos['empresa'] == 'RGR GLOBAL BUSINESS, S DE RL. DE CV') {
					$empresa = 'RGR';
				} else {
					$empresa = '';
				}

				$arreglo = array(
					'id' => $datos['id'],
					'lote' => $datos['lote'],
					'empresa' => $empresa,
					'concepto' => 'ENGANCHE ' . mb_strtoupper($datos['concepto'], 'UTF-8'),
					'concepto_corto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'fecha_vencimiento' => $fechaVencimientoSql,
					'fecha_formatteada' => $fechaVencimientoFormatted,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => number_format($penalidad, 2, '.', ','),
					'morosidad' => number_format($morosidad, 2, '.', ','),
					'total' => number_format($total, 2, '.', ','),
				);

				$json[] = $arreglo;
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiAplicarPago() {
		try {
			$moneda_pago = $_POST['moneda_pago'];
			$tc_pago = $_POST['tc_pago'];
			$metodo_pago = $_POST['metodo_pago'];
			$id = $_POST['id'];

			$arregloDatos = array($moneda_pago, $tc_pago, $metodo_pago, $id);
			$sth = $this->_db->prepare("
				UPDATE historial_recibos SET
				fecha_pagado = NOW(),
				moneda_pago = ?,
				tc_pago = ?,
				metodo_pago = ?
				WHERE id = ?
			");
			if(!$sth->execute($arregloDatos)) throw New Exception();
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiAplicarPagoMensualidad() {
		try {
			$moneda_pago = $_POST['moneda_pago'];
			$tc_pago = $_POST['tc_pago'];
			$metodo_pago = $_POST['metodo_pago'];
			$id = $_POST['id'];

			$arregloDatos = array($moneda_pago, $tc_pago, $metodo_pago, $id);
			$sth = $this->_db->prepare("
				UPDATE amortizaciones SET
				fecha_pagado = NOW(),
				moneda_pago = ?,
				tc_pago = ?,
				metodo_pago = ?
				WHERE id = ?
			");
			if(!$sth->execute($arregloDatos)) throw New Exception();
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiAplicarPagoEnganche() {
		try {
			$moneda_pago = $_POST['moneda_pago'];
			$tc_pago = $_POST['tc_pago'];
			$metodo_pago = $_POST['metodo_pago'];
			$id = $_POST['id'];

			$arregloDatos = array($moneda_pago, $tc_pago, $metodo_pago, $id);
			$sth = $this->_db->prepare("
				UPDATE amortizaciones SET
				fecha_pagado = NOW(),
				moneda_pago = ?,
				tc_pago = ?,
				metodo_pago = ?
				WHERE id = ?
			");
			if(!$sth->execute($arregloDatos)) throw New Exception();
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiInfoPago($id) {
		try {
			$json = array();

			$sth = $this->_db->prepare("
				SELECT hr.id, periodo AS concepto, importe, moneda, hr.lote, hr.importe, hr.tipo_doc
				FROM historial_recibos hr
				JOIN arrendatarios a
				ON a.id_arrendatario = hr.id_arrendatario
				WHERE hr.id = ?
				ORDER BY hr.id ASC
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

			while ($datos = $sth->fetch()) {
				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$conceptoSplit = explode(' ', $datos['concepto']);
				$fechaVencimiento = '';
				if (count($conceptoSplit) == 2) {
					switch ($conceptoSplit[0]) {
						case 'ENERO': $mes = '01'; break;
						case 'FEBRERO': $mes = '02'; break;
						case 'MARZO': $mes = '03'; break;
						case 'ABRIL': $mes = '04'; break;
						case 'MAYO': $mes = '05'; break;
						case 'JUNIO': $mes = '06'; break;
						case 'JULIO': $mes = '07'; break;
						case 'AGOSTO': $mes = '08'; break;
						case 'SEPTIEMBRE': $mes = '09'; break;
						case 'OCTUBRE': $mes = '10'; break;
						case 'NOVIEMBRE': $mes = '11'; break;
						case 'DICIEMBRE': $mes = '12'; break;
					}
					$fechaVencimientoSql = $conceptoSplit[1] . '-' . $mes . '-10';

					$earlier = new DateTime();
					$later = new DateTime($fechaVencimientoSql);

					$abs_diff = $later->diff($earlier)->format("%a");
					$total = 0;

					if ($earlier > $later) {
	                    $penalidad = number_format($datos['importe']*.1, 2, '.', ',');
	                    $total = $datos['importe'] + $datos['importe']*.1;
	                } else if ($abs_diff <= 16) {
	                    $penalidad = 0.00;
	                    $total = $datos['importe'];
	                } else {
	                    $penalidad = 0.00;
	                    $total = $datos['importe'];
	                }
				}

				switch ($datos['tipo_doc']) {
					case 'T': $tipo = 'Mantenimiento'; break;
					case 'V': $tipo = 'Mensualidad'; break;
					case 'P': $tipo = 'Enganche'; break;
				}

				$loteSplit = explode('-', $datos['lote']);

				$arreglo = array(
					'id' => $datos['id'],
					
					'nombre_lote' => $datos['lote'],
					'seccion' => $loteSplit[0],
					'manzana' => $loteSplit[1],
					'lote' => $loteSplit[2],

					'concepto' => 'CUOTA DE MANTENIMIENTO ' . $datos['concepto'],
					'concepto_corto' => $datos['concepto'],
					'tipo' => $tipo,
					'fecha_vencimiento' => $fechaVencimientoSql,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => $penalidad,
					'morosidad' => '0.00',
					'total' => number_format($total, 2, '.', ','),
				);

				$json[] = $arreglo;
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiInfoPagoMensualidad($id) {
		try {
			$json = array();
			$mesesVencidos = 0;

			$fechaActual = new DateTime();
			$datosVista = array();

			// Numero de contrato
			$sth = $this->_db->prepare("
				SELECT c.id_arrendatario
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE am.id = ?
				LIMIT 1
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$idContrato = $sth->fetchColumn();

			// Meses vencidos
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE a.id_arrendatario = ?
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idContrato);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				if ($earlier > $later) {
					$mesesVencidos++;
                }
			}

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig, am.tipo_doc, ar.nombre AS empresa
                FROM amortizaciones am
                JOIN arrendatarios a
                ON a.id_arrendatario = am.id_arrendatario
                JOIN contratos c
                ON c.id_contrato = am.id_contrato
                JOIN arrendadoras ar
                ON ar.id_arrendadora = c.id_arrendadora
				WHERE am.id = ?
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			$mesesVencidosRev = $mesesVencidos;

			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');
				$fechaVencimientoSql = $fechaVencimiento->format('Y-m-d');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
					$mesesVencidosFor = $datos['importe']*($mesesVencidosRev/100);

			        $penalidad = number_format($datos['importe']*.1, 2, '.', '');
			        $morosidad = number_format($mesesVencidosFor, 2, '.', '');
			        $total = $datos['importe'] + $penalidad + $mesesVencidosFor;

			        $mesesVencidosRev--;
			    } else if ($abs_diff <= 16) {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                } else {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                }

				switch ($datos['tipo_doc']) {
					case 'T': $tipo = 'Mantenimiento'; break;
					case 'V': $tipo = 'Mensualidad'; break;
					case 'P': $tipo = 'Enganche'; break;
				}

				$loteSplit = explode('-', $datos['lote']);

				if ($datos['empresa'] == 'INMOBILIARIA RANCHO TECATE, S DE RL. DE CV.') {
					$empresa = 'IRT';
				} else if ($datos['empresa'] == 'RGR GLOBAL BUSINESS, S DE RL. DE CV') {
					$empresa = 'RGR';
				} else {
					$empresa = '';
				}

				$arreglo = array(
					'id' => $datos['id'],
					
					'nombre_lote' => $datos['lote'],
					'seccion' => $loteSplit[0],
					'manzana' => $loteSplit[1],
					'lote' => $loteSplit[2],

					'concepto' => 'MENSUALIDAD ' . mb_strtoupper($datos['concepto'], 'UTF-8'),
					'concepto_corto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'tipo' => $tipo,
					'empresa' => $empresa,
					'fecha_vencimiento' => $fechaVencimientoSql,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => $penalidad,
					'morosidad' => '0.00',
					'total' => number_format($total, 2, '.', ','),
				);

				$json[] = $arreglo;
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function apiInfoPagoEnganche($id) {
		try {
			$json = array();
			$mesesVencidos = 0;

			$fechaActual = new DateTime();
			$datosVista = array();

			// Numero de contrato
			$sth = $this->_db->prepare("
				SELECT c.id_arrendatario
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE am.id = ?
				LIMIT 1
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$idContrato = $sth->fetchColumn();

			// Meses vencidos
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig
				FROM amortizaciones am
				JOIN arrendatarios a
				ON a.id_arrendatario = am.id_arrendatario
				JOIN contratos c
				ON c.id_contrato = am.id_contrato
				WHERE a.id_arrendatario = ?
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $idContrato);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				if ($earlier > $later) {
					$mesesVencidos++;
                }
			}

			// Mensualidades
			$sth = $this->_db->prepare("
				SELECT DISTINCT(am.num_recibo), am.id, a.lote, am.periodo, am.concepto, am.fecha_pagado, am.moneda, am.importe, c.inicio_vig, am.tipo_doc, ar.nombre AS empresa
                FROM amortizaciones am
                JOIN arrendatarios a
                ON a.id_arrendatario = am.id_arrendatario
                JOIN contratos c
                ON c.id_contrato = am.id_contrato
                JOIN arrendadoras ar
                ON ar.id_arrendadora = c.id_arrendadora
				WHERE am.id = ?
				ORDER BY LENGTH(am.periodo) ASC
			");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

			$arrayDatos = array();
			$mesesVencidosRev = $mesesVencidos;

			while ($datos = $sth->fetch()) {
				$inicioVigencia = $datos['inicio_vig'];
				$periodo = $datos['periodo']-1;

				switch ($datos['moneda']) {
					case 'D': $moneda = 'USD'; break;
					case 'P': $moneda = 'MXN'; break;
					default: $moneda = ''; break;
				}

				$fechaVencimiento = new DateTime($inicioVigencia);
				$fechaVencimiento->add(new DateInterval("P{$periodo}M"));
				$fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');
				$fechaVencimientoSql = $fechaVencimiento->format('Y-m-d');

				$earlier = new DateTime();
				$later = $fechaVencimiento;

				$abs_diff = $later->diff($earlier)->format("%a");
				$total = 0;

				if ($earlier > $later) {
					$mesesVencidosFor = $datos['importe']*($mesesVencidosRev/100);

			        $penalidad = number_format($datos['importe']*.1, 2, '.', '');
			        $morosidad = number_format($mesesVencidosFor, 2, '.', '');
			        $total = $datos['importe'] + $penalidad + $mesesVencidosFor;

			        $mesesVencidosRev--;
			    } else if ($abs_diff <= 16) {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                } else {
                    $penalidad = 0.00;
                    $morosidad = 0.00;
                    $total = $datos['importe'];
                }

				switch ($datos['tipo_doc']) {
					case 'T': $tipo = 'Mantenimiento'; break;
					case 'V': $tipo = 'Mensualidad'; break;
					case 'P': $tipo = 'Enganche'; break;
				}

				$loteSplit = explode('-', $datos['lote']);

				if ($datos['empresa'] == 'INMOBILIARIA RANCHO TECATE, S DE RL. DE CV.') {
					$empresa = 'IRT';
				} else if ($datos['empresa'] == 'RGR GLOBAL BUSINESS S DE RL. DE CV') {
					$empresa = 'RGR';
				} else {
					$empresa = '';
				}

				$arreglo = array(
					'id' => $datos['id'],
					
					'nombre_lote' => $datos['lote'],
					'seccion' => $loteSplit[0],
					'manzana' => $loteSplit[1],
					'lote' => $loteSplit[2],

					'concepto' => 'ENGANCHE ' . mb_strtoupper($datos['concepto'], 'UTF-8'),
					'concepto_corto' => mb_strtoupper($datos['concepto'], 'UTF-8'),
					'tipo' => $tipo,
					'empresa' => $empresa,
					'fecha_vencimiento' => $fechaVencimientoSql,
					'moneda' => $moneda,
					'importe' => number_format($datos['importe'], 2, '.', ','),
					'penalidad' => $penalidad,
					'morosidad' => '0.00',
					'total' => number_format($total, 2, '.', ','),
				);

				$json[] = $arreglo;
			}

	  		echo json_encode($json);
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}