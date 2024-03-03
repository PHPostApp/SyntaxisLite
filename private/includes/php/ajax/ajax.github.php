<?php 

if ( ! defined('SYNTAXISLITEV3')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.github.php
 * @author  Miguel92
*/


$files = [
   'github-api' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.github.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

// CODIGO
switch($action){
	case 'github-api':

		$token = '11AGEWD2Y0KyNxbjoa8wXO_ENTCbIQyOiwwpHeX1Bmfp4BRhgkpne6cE3Rh5USETHg6WMG77BJjJ3e765v';

		$branch = isset($_POST['branch']) ? $tsCore->setSecure($_POST['branch']) : 'main';

		$url = "https://api.github.com/repos/PHPostApp/SyntaxisLite/commits/$branch";

		$ch = curl_init($url);

		// Configura la cabecera de autenticación con el token
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
		   'Authorization: token github_pat_' . $token,
		   'User-Agent: Syntaxis Lite'
		]);

		// Establece algunas opciones adicionales de cURL si es necesario
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// Ejecuta la solicitud y obtiene la respuesta
		$response = curl_exec($ch);

		// Verifica si hubo un error en la solicitud o La respuesta de la API se encuentra en $response
		echo (curl_errno($ch)) ? curl_error($ch) : $response;

		// Cierra la sesión cURL
		curl_close($ch);
	break;
}