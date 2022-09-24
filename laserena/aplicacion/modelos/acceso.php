<?php
final class Modelos_Acceso extends Modelo {
    protected $_db 					= null;
    private $_loggeado 				= false;
    
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

			if (!$datos['foto']) {
				$_SESSION['login_foto'] = 'http://saevalcas.mx/img/sin-imagen.png';
			} else {
				$_SESSION['login_foto'] = 'http://saevalcas.mx/data/f/' . $datos['foto'];
			}

			$_SESSION['login_id'] = $datos['id'];
			$_SESSION['login_condominio'] = $datos['condominio'];
			$_SESSION['login_nombreUsuario'] = $datos['nombreUsuario'];
			$_SESSION['login_nombre'] = $datos['nombre'];
			$_SESSION['login_email'] = $datos['email'];
			$_SESSION['login_telefono1'] = $datos['telefono1'];
			$_SESSION['login_telefono2'] = $datos['telefono2'];

			return true;
        } else {
            return false;
        }
    }

    private function loggearConDataPost() {
        if(isset($_POST["login"]) && !empty($_POST['condominio']) && !empty($_POST['contrasena']) && !empty($_POST['nombreUsuario'])) {
			try {
				$sth = $this->_db->prepare("SELECT * FROM propietarios WHERE condominio = ? AND nombreUsuario = ? AND status = 1 LIMIT 1");
				$sth->bindParam(1, $_POST['condominio']);
				$sth->bindParam(2, $_POST['nombreUsuario']);
				if(!$sth->execute()) throw New Exception();
				$datos = $sth->fetch();

				if (hash("sha256", $_POST["contrasena"].$datos['salt']) == $datos['contrasena']) {
					$_SESSION['login_flag'] = 1;
					$_SESSION['login_id'] = $datos['id'];
					$_SESSION['login_condominio'] = $datos['condominio'];
					$_SESSION['login_nombreUsuario'] = $datos['nombreUsuario'];
					$_SESSION['login_nombre'] = $datos['nombre'];
					$_SESSION['login_correo'] = $datos['correo'];

					return true;
				} else {
					$this->mensaje = "Datos incorrectos, favor de verificar.<br /><br />En caso de que sus datos sean válidos, favor de comunicarse al departamento de post venta para actualizar su información.";
					return false;
				}

				$this->mensaje = "Datos incorrectos, favor de verificar.<br /><br />En caso de que sus datos sean válidos, favor de comunicarse al departamento de post venta para actualizar su información.";
				return false;
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