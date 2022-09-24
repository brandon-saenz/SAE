<?php
final class Modelos_Acceso extends Modelo {
    protected $_db 					= null;
    private $_loggeado 				= false;
    private $_nombreUsuario 		= '';
    
    public function __construct($db) {
		$this->iniciarDb($db);

		if ($this->loggearConDataSesion()) {
			$this->_loggeado = true;
		} elseif ($this->loggearConDataPost()) {
			$this->_loggeado = true;
		}
    }

    public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    private function loggearConDataSesion() {
        if (!empty($_SESSION['login_id']) && ($_SESSION['login_flag']==1)) {
			if (empty($_SESSION['sId'])) {
				if (isset($_COOKIE['sId'])) {
					$sId = $_COOKIE['sId'];
				} else {
					$sId = session_id();
					setcookie('sId', $sId, time()+60*60*24*365);
				}
				
				$_SESSION['sId'] = $sId; 
			}

			$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE id = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
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
			
			$_SESSION['login_id'] = $datos['id'];
			$_SESSION['login_manzana'] = $datos['manzana'];
			$_SESSION['login_seccion'] = $datos['seccion'];
			$_SESSION['login_lote'] = $prefijo . '-' . str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
			$_SESSION['login_nombreUsuario'] = $datos['nombre'];
			$_SESSION['login_nombre'] = $datos['nombre'];
			$_SESSION['login_email'] = $datos['email'];
			$_SESSION['login_telefono1'] = $datos['telefono1'];
			$_SESSION['login_telefono2'] = $datos['telefono2'];

			// Clave catastral
			$_SESSION['clave_catastral_cuenta'] = $datos['clave_catastral_cuenta'];
			$_SESSION['adeudo'] = $datos['adeudo'];
			$_SESSION['clave_catastral'] = $datos['clave_catastral'];

			// Enviadas
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM solicitudes WHERE id_propietario = ? AND (status = 0 OR status = 9)");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['solicitudes_enviadas'] = $sth->fetchColumn();

			// Autorizadas
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM solicitudes WHERE id_propietario = ? AND status = 1");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['solicitudes_autorizadas'] = $sth->fetchColumn();

			// En Proceso
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM solicitudes WHERE id_propietario = ? AND (status = 2 OR status = 3)");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['solicitudes_proceso'] = $sth->fetchColumn();

			// Atendidas
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM solicitudes WHERE id_propietario = ? AND status = 4");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['solicitudes_atendidas'] = $sth->fetchColumn();

			// Rechazads
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM solicitudes WHERE id_propietario = ? AND status = -2");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['solicitudes_rechazadas'] = $sth->fetchColumn();

			return true;
        } else {
            return false;
        }
    }

    private function loggearConDataPost() {
        if(isset($_POST["login"]) && !empty($_POST['lote']) && !empty($_POST['contrasena'])) {
			try {
				$this->_nombreUsuario = $_POST['contrasena'];
				
				$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE lote = ? AND manzana = ? AND seccion = ? AND nombreUsuario = ? AND status = 1 LIMIT 1");
				$sth->bindParam(1, $_POST['lote']);
				$sth->bindParam(2, $_POST['manzana']);
				$sth->bindParam(3, $_POST['seccion']);
				$sth->bindParam(4, $this->_nombreUsuario);
				if(!$sth->execute()) throw New Exception();
				$datos = $sth->fetch();

				if (hash("sha256", $_POST["contrasena"].$datos['salt']) == $datos['contrasena']) {
					$_SESSION['login_flag'] = 1;
					$_SESSION['login_id'] = $datos['id'];
					$_SESSION['login_manzana'] = str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT);
					$_SESSION['login_lote'] = str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
					$_SESSION['login_nombreUsuario'] = $datos['nombre'];
					$_SESSION['login_nombre'] = $datos['nombre'];
					$_SESSION['login_correo'] = $datos['correo'];

					return true;
				} else {
					$seccionRgr = $_POST['seccion'] . ' (RGR)';
					$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE lote = ? AND manzana = ? AND seccion = ? AND nombreUsuario = ? AND status = 1 LIMIT 1");
					$sth->bindParam(1, $_POST['lote']);
					$sth->bindParam(2, $_POST['manzana']);
					$sth->bindParam(3, $seccionRgr);
					$sth->bindParam(4, $this->_nombreUsuario);
					if(!$sth->execute()) throw New Exception();
					$datos = $sth->fetch();

					if (hash("sha256", $_POST["contrasena"].$datos['salt']) == $datos['contrasena']) {
						$_SESSION['login_flag'] = 1;
						$_SESSION['login_id'] = $datos['id'];
						$_SESSION['login_manzana'] = str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT);
						$_SESSION['login_lote'] = str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
						$_SESSION['login_nombreUsuario'] = $datos['nombre'];
						$_SESSION['login_nombre'] = $datos['nombre'];
						$_SESSION['login_correo'] = $datos['correo'];

						return true;
					} else {
						// Ceros
						$lote = ltrim($_POST['lote'], "0");
						$manzana = ltrim($_POST['manzana'], "0");

						$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE lote = ? AND manzana = ? AND seccion = ? AND nombreUsuario = ? AND status = 1 LIMIT 1");
						$sth->bindParam(1, $lote);
						$sth->bindParam(2, $manzana);
						$sth->bindParam(3, $_POST['seccion']);
						$sth->bindParam(4, $this->_nombreUsuario);
						if(!$sth->execute()) throw New Exception();
						$datos = $sth->fetch();

						if (hash("sha256", $_POST["contrasena"].$datos['salt']) == $datos['contrasena']) {
							$_SESSION['login_flag'] = 1;
							$_SESSION['login_id'] = $datos['id'];
							$_SESSION['login_manzana'] = str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT);
							$_SESSION['login_lote'] = str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
							$_SESSION['login_nombreUsuario'] = $datos['nombre'];
							$_SESSION['login_nombre'] = $datos['nombre'];
							$_SESSION['login_correo'] = $datos['correo'];

							return true;
						} else {
							$seccionRgr = $_POST['seccion'] . ' (RGR)';
							$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE lote = ? AND manzana = ? AND seccion = ? AND nombreUsuario = ? AND status = 1 LIMIT 1");
							$sth->bindParam(1, $lote);
							$sth->bindParam(2, $manzana);
							$sth->bindParam(3, $seccionRgr);
							$sth->bindParam(4, $this->_nombreUsuario);
							if(!$sth->execute()) throw New Exception();
							$datos = $sth->fetch();

							if (hash("sha256", $_POST["contrasena"].$datos['salt']) == $datos['contrasena']) {
								$_SESSION['login_flag'] = 1;
								$_SESSION['login_id'] = $datos['id'];
								$_SESSION['login_manzana'] = str_pad($datos['manzana'], 2, '0', STR_PAD_LEFT);
								$_SESSION['login_lote'] = str_pad($datos['lote'], 2, '0', STR_PAD_LEFT);
								$_SESSION['login_nombreUsuario'] = $datos['nombre'];
								$_SESSION['login_nombre'] = $datos['nombre'];
								$_SESSION['login_correo'] = $datos['correo'];

								return true;
							} else {
								$this->mensaje = "Datos incorrectos, favor de verificar.<br /><br />En caso de que sus datos sean válidos, favor de comunicarse al departamento de post venta para actualizar su información.";
								return false;
							}
						}
					}
				}
			} catch (Exception $e) {
				$this->mensaje = $e->getMessage();
			}
        } elseif (isset($_POST["login"]) && !empty($_POST['nombreUsuario']) && empty($_POST['contrasena'])) {
            $this->mensaje = "Campos obligatorios";
        }
    }
    
    public function cerrarSesion() {
		setcookie('sId', '', time()-60*60*24*365);
	
        $_SESSION = array();
        session_regenerate_id(); 
        session_destroy();
        return true;
    }
    
    public function estaLoggeado() {
        return $this->_loggeado;
    }
}