<?php 
// Ejemplo: http://localhost/
$lista = array('localhost', '127.0.0.1');
if(in_array($_SERVER['HTTP_HOST'], $lista)) {
	$config['base_url']				= 'http://localhost/gvalcasprop';
	setlocale(LC_MONETARY, '');
} else {
	$config['base_url']				= 'https://saevalcas.mx/atencion';
	setlocale(LC_MONETARY, 'en_US');
}

$config['controlador_default']	= 'login';
$config['controlador_error']	= 'error';