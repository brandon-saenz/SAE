<?php
final class Modelos_Empleados_Privilegios extends Modelo {
	protected $_db = null;
	public $nombre;
	public $apellidos;
	public $integraciones = array();
	public $integracionesSubmodulos = array();
	public $privilegios = array();
	public $empleados = array();
	public $mensajes = array();

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function __construct() {
    	$this->integraciones = array('Smk', 'Teleflex', 'Thermofisher', 'MediMexico');
		$this->integracionesSubmodulos = array('AjustesInventario' => 'Ajustes de inventario',
											   'Cotizaciones' => 'Cotizaciones',
											   'Ereq' => 'eReq',
											   'Existencias' => 'Existencias',
											   'InTransit' => 'In-Transit',
											   'Locaciones' => 'Locaciones',
											   'OrdenesCompra' => 'Ordenes de compra',
											   'RecibosMaterial' => 'Recibos de material',
											   'Requisiciones' => 'Requisiciones',
											   'SolicitarTraspasos' => 'Solicitar traspasos',
											   'VerTraspasos' => 'Ver traspasos');
    }

	public function obtenerDatos() {
		try {
			$qryContador = "SELECT COUNT(id) FROM usuarios WHERE status = 1";
			$qry = "SELECT usuarios.id, usuarios.nombre, usuarios.apellidos, sitios.nombre AS sitio FROM usuarios JOIN sitios ON usuarios.sitio = sitios.id WHERE usuarios.status = 1 ORDER BY sitios.nombre, usuarios.nombre ASC";
			$limite = 500;
			$adyacentes = 6;
			$paginaLink = STASIS . '/empleados/privilegios/?p=';

			$paginacion = Modelos_Contenedor::crearModelo('paginacion');
			$paginacion->crear($qryContador, $qry, $limite, $adyacentes, $paginaLink);
			$this->paginacionHtml = $paginacion->mostrar();

			$sth = $this->_db->query($paginacion->query());
			if(!$sth->execute()) throw New Exception();

			$datosVista = array();
			while ($datos = $sth->fetch()) {
				$arreglo = array('id' => $datos['id'],
								 'nombre' => mb_strtoupper($datos['nombre'], 'UTF-8'),
								 'apellidos' => mb_strtoupper($datos['apellidos'], 'UTF-8'),
								 'sitio' => mb_strtoupper($datos['sitio'], 'UTF-8'));
				$datosVista[] = $arreglo;
			}

	  		$this->empleados = $datosVista;
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function obtenerNombre($id) {
		try {
			$sth = $this->_db->prepare("SELECT nombre, apellidos FROM usuarios WHERE id = ? LIMIT 1");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			$datos = $sth->fetch();
			$this->nombre = $datos['nombre'];
			$this->apellidos = $datos['apellidos'];
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}

	public function obtenerPrivilegios($id) {
		try {
			$sth = $this->_db->prepare("SELECT tipo, valor FROM usuarios_privilegios WHERE id_usuario = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();
			while ($datos = $sth->fetch()) {
				$this->privilegios[$datos['tipo']] = $datos['valor'];
			}
			
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
	
	public function modificar($id, $datos) {
		try {
			$privilegios = array('catalogoClientes',
								'catalogoProveedores',
								'catalogoAlmacenes',
								'catalogoPartes',
								'catalogoBaselineSmk', 
								'catalogoBaselineTeleflex',
								'catalogoBaselineThermofisher',
								'catalogoBaselineMediMexico',
								'cotizaciones',
								'requerimientoCotizacionMx', 
								'requerimientoCotizacionUsa', 
								'ordenCompraMx',
								'ordenCompraUsa',
								'ordenCompraInt', 
								'traspasos',
								'importExport',
								'facturacion',
								'integracionSmk',
								'integracionTeleflex', 
								'integracionThermofisher',
								'integracionMediMexico', 
								'horasTrabajo',
								'privilegios',
								'nomina',
								'desempenoLaboral',
								'pagoCheques',
								'reportesCotizaciones', 
								'reportesOrdenesCompra',
								'reportesVentas',
								'reportesFacturas',
								'reportesDevoluciones',
								'reportesImportExport',
								'finanzasUtilidades',
								'finanzasPuntoEquilibrio',
								'finanzasFlujoEfectivo',
								'finanzasCapitalVariable',
								'finanzasUtilidadEstimada',
								'finanzasGastos',
								'estadisticasDesempenoLaboral',
								'estadisticasFinanzas',
								'estadisticasClientesProveedores',
								'estadisticasOrdenesCompra',
								'estadisticasEnvios',
								'estadisticasDistribucionMonetaria',
								'estadisticasFacturacion',
								'estadisticasEfectividadVendedor',
								'estadisticasEfectividadCliente');
		
			foreach($this->integracionesSubmodulos as $submodulo => $label) {
				foreach($this->integraciones as $integracion) {
					array_push($privilegios, $integracion.$submodulo);
				}
			}

			$sth = $this->_db->prepare("DELETE FROM usuarios_privilegios WHERE id_usuario = ?");
			$sth->bindParam(1, $id);
			if(!$sth->execute()) throw New Exception();

			$privilegiosData = array();
			foreach ($privilegios as $privilegio) {
				if ($datos[$privilegio] == 1) {
					$privilegiosData[$privilegio] = 1;
				} else {
					$privilegiosData[$privilegio] = 0;
				}
			}

			foreach ($privilegiosData as $privilegio => $valor) {
				$arregloDatos = array($id, $privilegio, $valor);
				$sth = $this->_db->prepare("INSERT INTO usuarios_privilegios (id_usuario, tipo, valor) VALUES (?, ?, ?)");
				if(!$sth->execute($arregloDatos)) throw New Exception();
			}

			header("Location:" . STASIS. "/empleados/privilegios/$id/1");
		} catch (Exception $e) {
			$this->mensajes[] = Modelos_Sistema::status(0, $e->getMessage());
		}
	}
}