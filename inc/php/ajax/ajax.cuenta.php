<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.cuenta.php
 * @author  Miguel92
*/


$files = [
   'cuenta-guardar' => ['n' => 2, 'p' => ''],
   'cuenta-desvincular' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.cuenta.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

// CLASE
require_once TS_CLASS . "c.cuenta.php";
$tsCuenta = new tsCuenta();

// CODIGO
switch($action){
	case 'cuenta-guardar':
		echo json_encode($tsCuenta->savePerfil());
	break;
	case 'cuenta-desvincular':
		include TS_CLASS . 'c.socials.php';
		$social = new tsSocials;

		echo $social->desvincular();
	break;
}