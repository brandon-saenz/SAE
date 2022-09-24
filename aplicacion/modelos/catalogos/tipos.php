<?php
final class Modelos_Catalogos_Tipos extends Modelo {
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
			$sth = $this->_db->query("SELECT *
				FROM tipos
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();
			
			$activos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'cuenta_contable' => mb_strtoupper($datos['cuenta_contable'], 'UTF-8'),
				);
				$activos[] = $arreglo;
			}

	  		$datosVista['activos'] = $activos;

	  		// Inactivos
			$sth = $this->_db->query("SELECT *
				FROM tipos
				WHERE status = 0
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();
			
			$inactivos = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array(
					'id' => $datos['id'],
					'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
					'cuenta_contable' => mb_strtoupper($datos['cuenta_contable'], 'UTF-8'),
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
			$cuenta_contable = strtoupper($datos['cuenta_contable']);

			$arregloDatos = array($nombre, $cuenta_contable);
			$sth = $this->_db->prepare("INSERT INTO tipos (nombre, cuenta_contable) VALUES (?, ?)");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Tipo de producto agregado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function modificarGuardar() {
		try {
			$id = $_POST['id'];
			$nombre = strtoupper($_POST['nombre']);
			$cuenta_contable = strtoupper($_POST['cuenta_contable']);
			$ano = $_POST['ano'];
			
			$ene = $_POST['ene'];
			$feb = $_POST['feb'];
			$mar = $_POST['mar'];
			$abr = $_POST['abr'];
			$may = $_POST['may'];
			$jun = $_POST['jun'];
			$jul = $_POST['jul'];
			$ago = $_POST['ago'];
			$sep = $_POST['sep'];
			$oct = $_POST['oct'];
			$nov = $_POST['nov'];
			$dic = $_POST['dic'];

			$ene_just = $_POST['ene_just'];
			$feb_just = $_POST['feb_just'];
			$mar_just = $_POST['mar_just'];
			$abr_just = $_POST['abr_just'];
			$may_just = $_POST['may_just'];
			$jun_just = $_POST['jun_just'];
			$jul_just = $_POST['jul_just'];
			$ago_just = $_POST['ago_just'];
			$sep_just = $_POST['sep_just'];
			$oct_just = $_POST['oct_just'];
			$nov_just = $_POST['nov_just'];
			$dic_just = $_POST['dic_just'];

			// $arregloDatos = array($nombre, $cuenta_contable, $id_unidad, $ano, $ene, $feb, $mar, $abr, $may, $jun, $jul, $ago, $sep, $oct, $nov, $dic, $ene_just, $feb_just, $mar_just, $abr_just, $may_just, $jun_just, $jul_just, $ago_just, $sep_just, $oct_just, $nov_just, $dic_just, $id);
			// $sth = $this->_db->prepare("
			// 	UPDATE tipos SET
			// 	nombre = ?,
			// 	cuenta_contable = ?,
			// 	id_unidad = ?,
			// 	ano = ?,
			// 	ene = ?,
			// 	feb = ?,
			// 	mar = ?,
			// 	abr = ?,
			// 	may = ?,
			// 	jun = ?,
			// 	jul = ?,
			// 	ago = ?,
			// 	sep = ?,
			// 	oct = ?,
			// 	nov = ?,
			// 	dic = ?,
			// 	ene_just = ?,
			// 	feb_just = ?,
			// 	mar_just = ?,
			// 	abr_just = ?,
			// 	may_just = ?,
			// 	jun_just = ?,
			// 	jul_just = ?,
			// 	ago_just = ?,
			// 	sep_just = ?,
			// 	oct_just = ?,
			// 	nov_just = ?,
			// 	dic_just = ?
			// 	WHERE id = ?
			// ");
			$arregloDatos = array($nombre, $cuenta_contable, $id);
			$sth = $this->_db->prepare("
				UPDATE tipos SET
				nombre = ?,
				cuenta_contable = ?
				WHERE id = ?
			");
			if($sth->execute($arregloDatos)) {
				$this->mensajes[] = Modelos_Sistema::status(2, 'Tipo de producto modificado exitosamente.');
			} else {
				throw New Exception();
			}
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id) {
		try {
			$sth = $this->_db->prepare("SELECT * FROM tipos WHERE id = ?");
			$sth->bindParam(1, $id);
			$sth->setFetchMode(PDO::FETCH_INTO, $this);
			if(!$sth->execute()) throw New Exception();
			$sth->fetch();

			$sth = $this->_db->query("SELECT tc FROM config");
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$this->tc = $datos['tc'];

	  		return $this;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function inactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE tipos SET status = 0 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/tipos');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function reactivar($id) {
		try {
			$sth = $this->_db->prepare("UPDATE tipos SET status = 1 WHERE id = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
	  		header('Location: ' . STASIS . '/catalogos/tipos');
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function listadoTipos($id = null) {
		try {
			$sth = $this->_db->query("SELECT id, nombre, cuenta_contable
				FROM tipos
				WHERE status = 1
				ORDER BY nombre ASC");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($id)) {
					if ($id == $datos['id']) {
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

	public function listadoCuentasContables($id = null) {
		try {
			$sth = $this->_db->query("
				SELECT id, nombre, cuenta_contable
				FROM tipos
				WHERE status = 1
				ORDER BY cuenta_contable ASC
			");
			if(!$sth->execute()) throw New Exception();

			$html = '';

			while ($datos = $sth->fetch()) {
				if (isset($id)) {
					if ($id == $datos['id']) {
						$html .= '<option value="' . $datos['id'] . '" selected>' . $datos['cuenta_contable'] . ' - ' . $datos['nombre'] . '</option>';
					} else {
						$html .= '<option value="' . $datos['id'] . '">' . $datos['cuenta_contable'] . ' - ' . $datos['nombre'] . '</option>';
					}
				} else {
					$html .= '<option value="' . $datos['id'] . '">' . $datos['cuenta_contable'] . ' - ' . $datos['nombre'] . '</option>';
				}
			}

	  		return $html;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

}