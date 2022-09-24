<?php
final class Modelos_Dashboard extends Modelo {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

	public function __construct($db) {
		$this->iniciarDb($db);
	}

	public function dashboard() {
		$html = array();

		// Tipo de Cambio
    	$sth = $this->_db->query("SELECT tipo_cambio FROM tipos_cambio WHERE fecha = CURDATE()");
    	if(!$sth->execute()) throw New Exception();
    	$tipoCambio = $sth->fetchColumn();

		// Partes
		$sth = $this->_db->query("SELECT COUNT(id) FROM partes WHERE status = 1");
		if(!$sth->execute()) throw New Exception();
		$html['partes'] = number_format((float)$sth->fetchColumn(), 0, '.', ',');

		// Clientes Locales
		$sth = $this->_db->query("SELECT COUNT(id) FROM clientes WHERE status = 1 AND tipo = 1 AND pais = 1");
		if(!$sth->execute()) throw New Exception();
		$html['clientes_locales'] = number_format((float)$sth->fetchColumn(), 0, '.', ',');

		// Clientes Foraneos
		$sth = $this->_db->query("SELECT COUNT(id) FROM clientes WHERE status = 1 AND tipo = 1 AND pais = 2");
		if(!$sth->execute()) throw New Exception();
		$html['clientes_foraneos'] = number_format((float)$sth->fetchColumn(), 0, '.', ',');

		// Proveedores Locales
		$sth = $this->_db->query("SELECT COUNT(id) FROM clientes WHERE status = 1 AND tipo = 3 AND pais = 1");
		if(!$sth->execute()) throw New Exception();
		$html['proveedores_locales'] = number_format((float)$sth->fetchColumn(), 0, '.', ',');

		// Proveedores Foraneos
		$sth = $this->_db->query("SELECT COUNT(id) FROM clientes WHERE status = 1 AND tipo = 3 AND pais = 2");
		if(!$sth->execute()) throw New Exception();
		$html['proveedores_foraneos'] = number_format((float)$sth->fetchColumn(), 0, '.', ',');






















		// Inventario en IMS
		$partesSinCosto = 0;

		$sth = $this->_db->query("SELECT ic.total AS existencias, ocp.precio AS costo_oc, p.proveedor_costo1 AS costo_catalogo, p.moneda AS moneda_catalogo, oc.moneda AS moneda_oc
			FROM inventario_cedis ic
			JOIN partes p
			ON p.id = ic.id_parte
			LEFT JOIN ordenes_compra oc
			ON oc.id = ic.id_orden_compra
			LEFT JOIN ordenes_compra_partes ocp
			ON ocp.id_orden_compra = oc.id AND ocp.id_parte = p.id
			WHERE ic.id_almacen = 2 AND ic.total != 0
			ORDER BY ic.id DESC
			");
		if(!$sth->execute()) throw New Exception();

		$inventarioMn = 0;
		$inventarioDlls = 0;

		while ($datos = $sth->fetch()) {
			if ($datos['id_orden_compra']) {
				// OC

				if ($datos['moneda_oc'] != '0.00' && $datos['costo_oc'] != 0) {
					if ($datos['moneda_oc'] == 1) {
						$inventarioMn += $datos['existencias']*$datos['costo_oc'];
					} elseif ($datos['moneda_oc'] == 2) {
						$inventarioDlls += $datos['existencias']*$datos['costo_oc'];
					}
				} else {
					$partesSinCosto++;
				}
			} else {
				// Catalogo

				if ($datos['moneda_catalogo'] != '0.00' && $datos['costo_catalogo'] != 0) {
					if ($datos['moneda_catalogo'] == 1) {
						$inventarioMn += $datos['existencias']*$datos['costo_catalogo'];
					} elseif ($datos['moneda_catalogo'] == 2) {
						$inventarioDlls += $datos['existencias']*$datos['costo_catalogo'];
					}
				} else {
					$partesSinCosto++;
				}
			}

		}

		// Sumatoria en dolares
		$totalInventarioDlls = $inventarioDlls + ($inventarioMn/$tipoCambio);
		// Sumatoria en pesos
		$totalInventarioMn = $inventarioDlls*$tipoCambio;

		if ($partesSinCosto == 0) {
			$html['imsCardBg'] = 'bg-light-success';
			$html['imsTextColor'] = 'text-dark';
			$html['imsAdvertencia'] = '';
		} else {
			$html['imsCardBg'] = 'bg-danger';
			$html['imsTextColor'] = 'text-white';

			$html['imsAdvertencia'] = '<img src="' . STASIS . '/img/icono-alerta_amarillo.png" height="16" /> <span class="text-muted font-size-h6">' . $partesSinCosto . ' números de parte sin costo asignado</span>';
		}

		$html['imsTotalInventarioDlls'] = Modelos_Caracteres::formatearDinero('%#10n', $totalInventarioDlls);
		$html['imsTotalInventarioMn'] = Modelos_Caracteres::formatearDinero('%#10n', $totalInventarioMn);


























		// Inventario en Villamend
		$partesSinCosto = 0;

		$sth = $this->_db->query("SELECT ic.total AS existencias, ocp.precio AS costo_oc, p.proveedor_costo1 AS costo_catalogo, p.moneda AS moneda_catalogo, oc.moneda AS moneda_oc
			FROM inventario_cedis ic
			JOIN partes p
			ON p.id = ic.id_parte
			LEFT JOIN ordenes_compra oc
			ON oc.id = ic.id_orden_compra
			LEFT JOIN ordenes_compra_partes ocp
			ON ocp.id_orden_compra = oc.id AND ocp.id_parte = p.id
			WHERE ic.id_almacen = 1 AND ic.total != 0
			ORDER BY ic.id DESC
			");
		if(!$sth->execute()) throw New Exception();

		$inventarioMn = 0;
		$inventarioDlls = 0;

		while ($datos = $sth->fetch()) {
			if ($datos['id_orden_compra']) {
				// OC

				if ($datos['moneda_oc'] != '0.00' && $datos['costo_oc'] != 0) {
					if ($datos['moneda_oc'] == 1) {
						$inventarioMn += $datos['existencias']*$datos['costo_oc'];
					} elseif ($datos['moneda_oc'] == 2) {
						$inventarioDlls += $datos['existencias']*$datos['costo_oc'];
					}
				} else {
					$partesSinCosto++;
				}
			} else {
				// Catalogo

				if ($datos['moneda_catalogo'] != '0.00' && $datos['costo_catalogo'] != 0) {
					if ($datos['moneda_catalogo'] == 1) {
						$inventarioMn += $datos['existencias']*$datos['costo_catalogo'];
					} elseif ($datos['moneda_catalogo'] == 2) {
						$inventarioDlls += $datos['existencias']*$datos['costo_catalogo'];
					}
				} else {
					$partesSinCosto++;
				}
			}

		}

		// Sumatoria en dolares
		$totalInventarioDlls = $inventarioDlls + ($inventarioMn/$tipoCambio);
		// Sumatoria en pesos
		$totalInventarioMn = $inventarioDlls*$tipoCambio;

		if ($partesSinCosto == 0) {
			$html['villamendCardBg'] = 'bg-light-success';
			$html['villamendTextColor'] = 'text-dark';
			$html['villamendAdvertencia'] = '';
		} else {
			$html['villamendCardBg'] = 'bg-danger';
			$html['villamendTextColor'] = 'text-white';

			$html['villamendAdvertencia'] = '<img src="' . STASIS . '/img/icono-alerta_amarillo.png" height="16" /> <span class="text-muted font-size-h6">' . $partesSinCosto . ' números de parte sin costo asignado</span>';
		}

		$html['villamendTotalInventarioDlls'] = Modelos_Caracteres::formatearDinero('%#10n', $totalInventarioDlls);
		$html['villamendTotalInventarioMn'] = Modelos_Caracteres::formatearDinero('%#10n', $totalInventarioMn);


























		/////////////////////////////
		// Cuentas por Cobrar
		/////////////////////////////
		$totalFinalPesos = 0;
		$totalFinalDolares = 0;

		$sth = $this->_db->query("SELECT id AS id_cliente, razon_social FROM clientes WHERE status = 1 AND tipo = 1");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$totalPesos = 0;
			$totalDolares = 0;

			// Se suman las facturas de pesos
			$sth2 = $this->_db->prepare("SELECT SUM(f.total) AS total
				FROM facturas f
				WHERE f.moneda = 1 AND f.id_cliente = ? AND f.status = 1 AND f.timbrado = 1 AND (f.cobrado = 0 OR f.cobrado_cancelado = 1)");
			$sth2->bindParam(1, $datos['id_cliente']);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();
			if ($datos2['total'] != 0) {
				$totalPesos += $datos2['total'];
				$totalFinalPesos += $datos2['total'];
			}

			// Se suman las facturas de dolares
			$sth2 = $this->_db->prepare("SELECT SUM(f.total) AS total
				FROM facturas f
				WHERE f.moneda = 2 AND f.id_cliente = ? AND f.status = 1 AND f.timbrado = 1 AND (f.cobrado = 0 OR f.cobrado_cancelado = 1)");
			$sth2->bindParam(1, $datos['id_cliente']);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();
			if ($datos2['total'] != 0) {
				$totalDolares += $datos2['total'];
				$totalFinalDolares += $datos2['total'];
			}

			if ($totalPesos != '0') {
				$totalPesos = Modelos_Caracteres::formatearDinero('%#10n', $totalPesos);
			} else {
				$totalPesos = '---';
			}
			if ($totalDolares != '0') {
				$totalDolares = Modelos_Caracteres::formatearDinero('%#10n', $totalDolares);
			} else {
				$totalDolares = '---';
			}

			$arreglo = array(
				'totalPesos' => $totalPesos,
				'totalDolares' => $totalDolares
			);
			$datosVista[] = $arreglo;
		}

		$totalCCPesos = $totalFinalPesos;
		$totalCCDolares = $totalFinalDolares;
		$totalFinalCCPesos = $totalFinalPesos+($totalFinalDolares*$tipoCambio);
		$totalFinalCCDolares = ($totalFinalPesos/$tipoCambio)+$totalFinalDolares;

		$html['totalCCPesos'] = '$ ' . number_format((float)$totalFinalPesos, 0, '.', ',');
		$html['totalCCDolares'] = '$ ' . number_format((float)$totalFinalDolares, 0, '.', ',');
		$html['totalFinalCCPesos'] = '$ ' . number_format((float)$totalFinalPesos+($totalFinalDolares*$tipoCambio), 0, '.', ',');
		$html['totalFinalCCDolares'] = '$ ' . number_format((float)($totalFinalPesos/$tipoCambio)+$totalFinalDolares, 0, '.', ',');

































		/////////////////////////////
		// Cuentas por Pagar
		/////////////////////////////
		$totalFinalPesos = 0;
		$totalFinalDolares = 0;

		$sth = $this->_db->query("SELECT oc.id_proveedor, cl.razon_social
			FROM ordenes_compra oc
			JOIN clientes cl
			ON cl.id = oc.id_proveedor
			GROUP BY oc.id_proveedor
			ORDER BY cl.razon_social ASC
			");
		if(!$sth->execute()) throw New Exception();
		while ($datos = $sth->fetch()) {
			$totalPesos = 0;
			$totalDolares = 0;

			// Se suman las ordenes de compra en pesos
			$sth2 = $this->_db->prepare("SELECT SUM(oc.total-oc.importe_pagado) AS total
				FROM ordenes_compra oc
				WHERE oc.moneda = 1 AND oc.id_proveedor = ? AND oc.status = 1 AND (oc.cobrado = 0)");
			$sth2->bindParam(1, $datos['id_proveedor']);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();
			if ($datos2['total'] != 0) {
				$totalPesos += $datos2['total'];
				$totalFinalPesos += $datos2['total'];
			}

			// Se suman las ordenes de compra en dolares
			$sth2 = $this->_db->prepare("SELECT SUM(oc.total-oc.importe_pagado) AS total
				FROM ordenes_compra oc
				WHERE oc.moneda = 2 AND oc.id_proveedor = ? AND oc.status = 1 AND (oc.cobrado = 0)");
			$sth2->bindParam(1, $datos['id_proveedor']);
			if(!$sth2->execute()) throw New Exception();
			$datos2 = $sth2->fetch();
			if ($datos2['total'] != 0) {
				$totalDolares += $datos2['total'];
				$totalFinalDolares += $datos2['total'];
			}

			if ($totalPesos != '0') {
				$totalPesos = Modelos_Caracteres::formatearDinero('%#10n', $totalPesos);
			} else {
				$totalPesos = '---';
			}
			if ($totalDolares != '0') {
				$totalDolares = Modelos_Caracteres::formatearDinero('%#10n', $totalDolares);
			} else {
				$totalDolares = '---';
			}

			$arreglo = array(
				'id_proveedor' => $datos['id_proveedor'],
				'razon_social' => $datos['razon_social'],
				'totalPesos' => $totalPesos,
				'totalDolares' => $totalDolares
			);
			$datosVista[] = $arreglo;
		}

		$totalCPPesos = $totalFinalPesos;
		$totalCPDolares = $totalFinalDolares;
		$totalFinalCPPesos = $totalFinalPesos+($totalFinalDolares*$tipoCambio);
		$totalFinalCPDolares = ($totalFinalPesos/$tipoCambio)+$totalFinalDolares;

		$html['totalCPPesos'] = '$ ' . number_format((float)$totalFinalPesos, 0, '.', ',');
		$html['totalCPDolares'] = '$ ' . number_format((float)$totalFinalDolares, 0, '.', ',');
		$html['totalFinalCPPesos'] = '$ ' . number_format((float)$totalFinalPesos+($totalFinalDolares*$tipoCambio), 0, '.', ',');
		$html['totalFinalCPDolares'] = '$ ' . number_format((float)($totalFinalPesos/$tipoCambio)+$totalFinalDolares, 0, '.', ',');















		return $html;
	}
}