<?php
final class Modelos_Catalogos_Conceptos extends Modelo {
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
			$sth = $this->_db->query("SELECT c.id, c.nombre, c.area, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, c.clasificacion, c.empresa
				FROM conceptos c
				JOIN empleados e
				ON e.id = c.id_responsable
				WHERE c.status = 1
				ORDER BY c.nombre DESC
			");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => $datos['nombre'],
					'area' => $datos['area'],
					'responsable' => $datos['responsable'],
					'clasificacion' => $datos['clasificacion'],
					'empresa' => $datos['empresa'],
				);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("
				SELECT c.id, c.nombre, c.area, CONCAT(e.nombre, ' ', e.apellidos) AS responsable, c.clasificacion, c.empresa
				FROM conceptos c
				JOIN empleados e
				ON e.id = c.id_responsable
				WHERE c.status = 0
				ORDER BY c.nombre DESC
			");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => $datos['nombre'],
					'area' => $datos['area'],
					'responsable' => $datos['responsable'],
					'clasificacion' => $datos['clasificacion'],
					'empresa' => $datos['empresa'],
				);
				$inactivos[] = $arreglo;
			}

			$datosVista['inactivos'] = $inactivos;
			
	  		return $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function nuevo($datos) {
		try {
			$nombre = strtoupper($datos['nombre']);
			$area = strtoupper($datos['area']);
			$usuario = strtoupper($datos['usuario']);
			$clasificacion = strtoupper($datos['clasificacion']);
			$empresa = strtoupper($datos['empresa']);
			$direccion = strtoupper($datos['direccion']);
			$rfc = strtoupper($datos['rfc']);
			$iva = strtoupper($datos['iva']);
			$moneda = strtoupper($datos['moneda']);
			$ieps = strtoupper($datos['ieps']);
			$um = strtoupper($datos['um']);
			$clave_prodserv = strtoupper($datos['clave_prodserv']);

			switch ($empresa) {
				case 'EL ENCANTO RESORT CLUB, S DE RL DE CV': $idEmpresa = 7; break;
				case 'COBROPLAN, SC': $idEmpresa = 3; break;
				case 'ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC': $idEmpresa = 6; break;
				case 'ENCINO DE PIEDRA DE BC, S DE RL DE CV': $idEmpresa = 8; break;
				case 'LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV': $idEmpresa = 2; break;
				case 'MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV': $idEmpresa = 1; break;
				case 'INMOBILIARIA RANCHO TECATE S DE RL DE CV': $idEmpresa = 4; break;
				case 'RGR-GLOBAL-BUSINESS': $idEmpresa = 5; break;
				case 'CONSTRUCTORA RANCHO TECATE': $idEmpresa = 3; break;
			}

			$arregloDatos = array($nombre, $area, $usuario, $clasificacion, $empresa, $direccion, $rfc, $iva, $moneda, $ieps, $idEmpresa, $um, $clave_prodserv);
			$sth = $this->_db->prepare("INSERT INTO conceptos (nombre, area, id_responsable, clasificacion, empresa, direccion, rfc, iva, moneda, ieps, id_empresa_cobroplan, um, clave_prodserv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Concepto agregado exitosamente.');
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
			$area = strtoupper($datos['area']);
			$usuario = strtoupper($datos['usuario']);
			$clasificacion = strtoupper($datos['clasificacion']);
			$empresa = strtoupper($datos['empresa']);
			$direccion = strtoupper($datos['direccion']);
			$rfc = strtoupper($datos['rfc']);
			$iva = strtoupper($datos['iva']);
			$moneda = strtoupper($datos['moneda']);
			$ieps = strtoupper($datos['ieps']);
			$um = strtoupper($datos['um']);
			$clave_prodserv = strtoupper($datos['clave_prodserv']);

			switch ($empresa) {
				case 'EL ENCANTO RESORT CLUB, S DE RL DE CV': $idEmpresa = 7; break;
				case 'COBROPLAN, SC': $idEmpresa = 3; break;
				case 'ASOCIACION DE USUARIOS DE RANCHO TECATE RESORT-SECCION LOMAS AC': $idEmpresa = 6; break;
				case 'ENCINO DE PIEDRA DE BC, S DE RL DE CV': $idEmpresa = 8; break;
				case 'LAS OLAS CONSTRUCCION Y TURISMO, SA DE CV': $idEmpresa = 2; break;
				case 'MANTENIMIENTO Y ADMINISTRACION PROFESIONAL, SA DE CV': $idEmpresa = 1; break;
				case 'INMOBILIARIA RANCHO TECATE S DE RL DE CV': $idEmpresa = 4; break;
				case 'RGR-GLOBAL-BUSINESS': $idEmpresa = 5; break;
				case 'CONSTRUCTORA RANCHO TECATE': $idEmpresa = 3; break;
			}
			
			$arregloDatos = array($nombre, $area, $usuario, $clasificacion, $empresa, $direccion, $rfc, $iva, $moneda, $ieps, $idEmpresa, $um, $clave_prodserv, $id);
			$sth = $this->_db->prepare("
				UPDATE conceptos SET
				nombre = ?,
				area = ?,
				id_responsable = ?,
				clasificacion = ?,
				empresa = ?,
				direccion = ?,
				rfc = ?,
				iva = ?,
				moneda = ?,
				ieps = ?,
				id_empresa_cobroplan = ?,
				um = ?,
				clave_prodserv = ?
				WHERE id = ?
			");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Concepto modificado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM conceptos WHERE id = ?");
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
			$sth = $this->_db->prepare("UPDATE conceptos SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/conceptos');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE conceptos SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/conceptos');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoServicios($idEmpleado) {
		try {
			if (isset($idEmpleado)) {
				$sth = $this->_db->prepare("SELECT id_departamento FROM empleados WHERE id = ?");
				$sth->bindParam(1, $idEmpleado);
				if(!$sth->execute()) throw New Exception();
				$idDepartamento = $sth->fetchColumn();
			}

			$sth = $this->_db->query("SELECT id, nombre
				FROM conceptos
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idDepartamento)) {
					if ($idDepartamento == $datos['id']) {
						$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoServiciosNombre() {
		try {
			if ($_SESSION['login_id'] == 1) {
				$sth = $this->_db->query("
					SELECT id, nombre
					FROM conceptos
					WHERE status = 1
					ORDER BY nombre ASC
				");
			} else {
				$sth = $this->_db->prepare("
					SELECT id, nombre
					FROM conceptos
					WHERE status = 1 AND id_responsable = ?
					ORDER BY nombre ASC
				");
				$sth->bindParam(1, $_SESSION['login_id']);
			}
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($idDepartamento)) {
					if ($idDepartamento == $datos['id']) {
						$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['id'] . '">' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function infoConcepto() {
		try {
			$idConcepto = $_POST['idConcepto'];

			$sth = $this->_db->prepare("SELECT * FROM conceptos WHERE id = ?");
			$sth->bindParam(1, $idConcepto);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			if ($datos['moneda'] == 1) {
				$moneda = 'PESOS';
			} elseif ($datos['moneda'] == 2) {
				$moneda = 'DÓLARES';
			}

			if ($datos['iva']) {
				$iva = number_format($datos['iva']*100, 2, '.', ',');
			} else {
				$iva = '';
			}

			echo "
			    <script>
			        $('#moneda1').val('" . $moneda . "');
			        $('#porImpuesto').val('" . $iva . "');
		        </script>
	        ";
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}