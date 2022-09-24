<?php
final class Modelos_Sistema {
	protected $_db = null;

	public function iniciarDb($db) {
    	if (!$this->_db) {
			$this->_db = $db;
        }
    }

    public function actualizarTipoCambio() {
    	$fechaActual = date('Y-m-d');

    	// Se checa si el dia de hoy ya tiene tipo de cambio
    	$sth = $this->_db->query("SELECT tipo_cambio FROM tipos_cambio WHERE fecha = CURDATE()");
    	if(!$sth->execute()) throw New Exception();
    	$tipoCambio = $sth->fetchColumn();

    	// Si no hay tipo de cambio
    	if (empty($tipoCambio)) {
    		// Jalar el dato del DOF
    		$xml=simplexml_load_file("http://dof.gob.mx/indicadores.xml");
			$tipoCambio = $xml->channel->item[0]->description;

    		// Actualizar el tipo de cambio al actual
    		$sth = $this->_db->prepare("INSERT INTO tipos_cambio (fecha, tipo_cambio) VALUES (?, ?)");
    		$sth->bindParam(1, $fechaActual);
    		$sth->bindParam(2, $tipoCambio);
    		if(!$sth->execute()) throw New Exception();
    	}
    }

	static public function status($valor, $mensaje) {
		switch ($valor) {
			// Error interno
			case 0:
			return '<div class="alert alert-custom alert-danger" role="alert"> <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div> <div class="alert-text">Error interno del sistema: ' . $mensaje . '</div> </div>';
			break;
			
			// Error general
			case 1:
			return '<div class="alert alert-custom alert-danger" role="alert"> <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div> <div class="alert-text">' . $mensaje . '</div> </div>';
			break;

			// Confirmacion general
			case 2:
			return '<div class="alert alert-custom alert-primary" role="alert"> <div class="alert-icon"><i class="fa fa-check"></i></div> <div class="alert-text">' . $mensaje . '</div> </div>';
			break;
			
			// Informacion general
			case 3:
			return '<div class="alert alert-custom alert-info" role="alert"> <div class="alert-icon"><i class="fa fa-info-circle"></i></div> <div class="alert-text">' . $mensaje . '</div> </div>';
			break;

			// Alerta/advertencia general
			case 4:
			return '<div class="alert alert-custom alert-warning" role="alert"> <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div> <div class="alert-text">' . $mensaje . '</div> </div>';
			break;
		}
	}

	static public function tipoCambio() {
		$date = date('Y-m-d') . '/' . date('Y-m-d');
		$token = '06446a3aafea0815b77e08dc2681d31c6fe069e21163072854579c34d3cce69d';
		$catalogs = ['SF60653'];
		$series = implode($catalogs, ',');
		$query = 'https://www.banxico.org.mx/SieAPIRest/service/v1/series/'.$series.'/datos/' . $date . '?token='.$token;
		$json = json_decode(file_get_contents($query), true);
		return $json['bmx']['series'][0]['datos'][0]['dato'];
	}
}