<?php
// Incluir inicializador y framework
require 'inicializador.php';
require(APP .  'config/config.php');
require(ROOT_DIR . 'sistema/modelo.php');
require(ROOT_DIR . 'sistema/vista.php');
require(ROOT_DIR . 'sistema/controlador.php');
require(ROOT_DIR . 'sistema/fw.php');

// Definir URL Base
global $config;
define('STASIS', $config['base_url']);

fw();