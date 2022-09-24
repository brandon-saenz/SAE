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
        if (!empty($_SESSION['login_nombreUsuario']) && ($_SESSION['login_flag']==1)) {
			if (empty($_SESSION['sId'])) {
				if (isset($_COOKIE['sId'])) {
					$sId = $_COOKIE['sId'];
				} else {
					$sId = session_id();
					setcookie('sId', $sId, time()+60*60*24*365);
				}
				
				$_SESSION['sId'] = $sId; 
			}

			// Datos del empleado
			$sth = $this->_db->prepare("
				SELECT p.nombre AS puesto, ct.nombre AS centro_trabajo, d.nombre AS departamento, d.id AS id_departamento, e.autorizar, e.autorizar_proveedores, e.admin_global
				FROM empleados e
				LEFT JOIN puestos p
				ON p.id = e.id_puesto
				LEFT JOIN centros_trabajo ct
				ON ct.id = e.id_centro_trabajo
				LEFT JOIN departamentos d
				ON d.id = e.id_departamento
				WHERE e.id = ?
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();

			$_SESSION['login_puesto'] = $datos['puesto'];
			$_SESSION['login_centro_trabajo'] = $datos['departamento'];
			$_SESSION['login_id_departamento'] = $datos['id_departamento'];
			$_SESSION['login_departamento'] = $datos['centro_trabajo'];
			$_SESSION['login_autorizar'] = $datos['autorizar'];
			$_SESSION['login_adminglobal'] = $datos['admin_global'];
			$_SESSION['login_autorizar_proveedores'] = $datos['autorizar_proveedores'];

			// var_dump($_SESSION['adminglobal']);die;

			// IRT
			$sth = $this->_db->query("SELECT COUNT(id) FROM propietarios WHERE tipo = 'IRT' AND status = 1");
			if(!$sth->execute()) throw New Exception();
			$_SESSION['irt'] = $sth->fetchColumn();

			// RGR
			$sth = $this->_db->query("SELECT COUNT(id) FROM propietarios WHERE tipo = 'RGR' AND status = 1");
			if(!$sth->execute()) throw New Exception();
			$_SESSION['rgr'] = $sth->fetchColumn();

			// La Serena
			$sth = $this->_db->query("SELECT COUNT(id) FROM propietarios WHERE tipo = 'La Serena' AND status = 1");
			if(!$sth->execute()) throw New Exception();
			$_SESSION['serena'] = $sth->fetchColumn();

			// Interacciones Asignadas
			$sth = $this->_db->prepare("
				SELECT COUNT(i.id)
				FROM interacciones i
				JOIN empleados er
				ON er.id = i.id_remitente
				JOIN empleados ed
				ON ed.id = i.id_destinatario
				WHERE (i.status = 1 OR i.status = 2) AND ed.email = ?
				ORDER BY i.id DESC
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['interacciones_asignadas'] = $sth->fetchColumn();

			// Interacciones Asignadas Participantes
			$sth = $this->_db->prepare("
				SELECT COUNT(i.id)
				FROM interacciones i
				JOIN interacciones_usuarios iu
				ON iu.id_interaccion = i.id
				JOIN empleados e
				ON e.id = iu.id_usuario
				WHERE (i.status = 1 OR i.status = 2) AND e.email = ?
			");
			$sth->bindParam(1, $_SESSION['login_correo']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['interacciones_asignadas'] += $sth->fetchColumn();

			// Interacciones Generadas
			$sth = $this->_db->prepare("SELECT COUNT(id) FROM interacciones WHERE (status = 1 OR status = 2) AND id_remitente = ?");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['interacciones_generadas'] = $sth->fetchColumn();

			// Requisiciones con status de Entregadas
			$sth = $this->_db->prepare("
				SELECT COUNT(rp.id)
				FROM requisiciones_partes rp
				JOIN requisiciones r
				ON r.id = rp.id_requisicion
				WHERE rp.status = 4 AND r.id_usuario = ?
				ORDER BY rp.id_requisicion DESC
			");
			$sth->bindParam(1, $_SESSION['login_id']);
			if(!$sth->execute()) throw New Exception();
			$_SESSION['entregadas'] = $sth->fetchColumn();

			return true;
        } else {
            return false;
        }
    }

    private function loggearConDataPost() {
        if(isset($_POST["login"]) && !empty($_POST['nombreUsuario']) && !empty($_POST['contrasena'])) {
			try {
				$this->_nombreUsuario = $_POST['nombreUsuario'];
				
				$sth = $this->_db->prepare("SELECT id, nombre, apellidos, email, salt, contrasena, telefono, tipo, admin_global FROM empleados WHERE nombreUsuario = ? AND status = 1 LIMIT 1");
				$sth->bindParam(1, $this->_nombreUsuario);
				if(!$sth->execute()) throw New Exception();
				$datos = $sth->fetch();
				
				if (hash("sha256", $_POST["contrasena"].$datos['salt']) == $datos['contrasena']) {
					$_SESSION['login_flag'] = 1;
					$_SESSION['login_id'] = $datos['id'];
					$_SESSION['login_nombreUsuario'] = $_POST['nombreUsuario'];
					$_SESSION['login_nombre'] = $datos['nombre'];
					$_SESSION['login_apellidos'] = $datos['apellidos'];
					$_SESSION['login_correo'] = $datos['email'];
					$_SESSION['login_telefono'] = $datos['telefono'];
					$_SESSION['login_tipo'] = $datos['tipo'];
					$_SESSION['login_adminglobal'] = $datos['admin_global'];

					return true;
				} else {
					$this->mensajes[] = "Contraseña incorrecta";
					return false;                    
				}
			} catch (Exception $e) {
				$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
			}
        } elseif (isset($_POST["login"]) && !empty($_POST['nombreUsuario']) && empty($_POST['contrasena'])) {
            $this->mensajes[] = "Campo de contraseña vacio";
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