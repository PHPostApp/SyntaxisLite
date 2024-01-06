<?php 
/**
 * Controlador
 *
 * @name    cuenta.php
 * @author  PHPost Team
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "cuenta";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 2;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT
	
/*++++++++ = ++++++++*/

	include "../../header.php"; // INCLUIR EL HEADER

	$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL

/*++++++++ = ++++++++*/
	
	// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1){	
		$tsPage = 'aviso';
		$tsAjax = 0;
		$smarty->assign("tsAviso",$tsLevelMsg);
		//
		$tsContinue = false;
	}
	//
	if($tsContinue){
/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	$action = $_GET['action'];
	//
	include("../class/c.cuenta.php");
	$tsCuenta = new tsCuenta();

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/

	if(empty($action)){
		include('../ext/datos.php');
		include('../ext/geodata.php');
		// SOLO MENORES DE 100 A�OS xD Y MAYORES DE...
		$now_year = date("Y",time());
		$max_year = 100 - $tsCore->settings['c_allow_edad'];
		$end_year = $now_year - $tsCore->settings['c_allow_edad'];
		$smarty->assign("tsMax",$max_year);
		$smarty->assign("tsEndY",$end_year);
		// PERFIL INFO
      $tsPerfil = $tsCuenta->loadPerfil();
		$smarty->assign("tsPerfil",$tsPerfil);		
		$smarty->assign("tsRedes", $tsCuenta->redes);
		// PERFIL DATA
		$smarty->assign("tsPData",$tsPerfilData);
        $smarty->assign("tsPrivacidad",$tsPrivacidad);
		// DATOS
		$smarty->assign("tsPaises",$tsPaises);
		$smarty->assign("tsEstados",$estados[$tsPerfil['user_pais']]);
		$smarty->assign("tsMeses",$tsMeses);
      // BLOQUEOS
      $smarty->assign("tsBlocks",$tsCuenta->loadBloqueos());
   } elseif($action == 'desactivate'){
		if(!empty($_POST['validar'])) echo $tsCuenta->desCuenta();
	}
	if($_GET['accion'] === 'avatar') {
   	require('../class/c.upload.php');
		$tsUpload = new tsUpload();
		//
      $sex = $tsUpload->sexuser();
      $Carpeta_Avatar = ($sex["user_sexo"] == 1) ? 'm' : 'f';
		$smarty->assign('tsCarpeta', "{$tsCore->settings['avatares']}/$Carpeta_Avatar");
		$Avatar = array_diff(scandir(TS_AVATARES . $Carpeta_Avatar), ['.', '..', 'Thumbs.db']);
		foreach($Avatar as $k => $ava) 
			$Avatar[$k] = [
				'img' => $ava,
				'name' => str_replace('.jpg', '', $ava)
			];
		$smarty->assign('tsAvatares', $Avatar);
	}
	$smarty->assign("tsAccion", $_GET["accion"]); 
	$smarty->assign("tsTab", $_GET["tab"]);
	
/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL

	/*++++++++ = ++++++++*/
	include("../../footer.php");
	/*++++++++ = ++++++++*/
}