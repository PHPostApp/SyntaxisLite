<?php if ( ! defined('SYNTAXISLITEV3')) exit('No se permite el acceso directo al script');

/**
 * El footer permite mostrar la plantilla
 * Actualizacion Realizada: Smarty 3
 *
 * @copyright SyntaxisLite v3 - 2024
 * @author Miguel92
*/

// Página solicitada
$smarty->assign("tsPage", $tsPage);

// Añadimos una nueva ruta modules/{_pagina_}/
$smarty->addTemplateDir([
	'modules' => ROUTEMODULES . $tsPage . SEPARATOR
]);

$mytemplate = "t.$tsPage.tpl";
$myerr = "t.error.tpl";

// Comprobamos que exista la plantilla
$template = $smarty->templateExists( $mytemplate ) ? $mytemplate : $myerr;

// Cacheamos la plantilla [5hs]
$smarty->setCacheLifetime( (int)$tsCore->extras['smarty_lifetime'] * 3600 );

/**
 * Borra la versión compilada del recurso de plantilla especificado
 * @link https://www.smarty.net/docs/en/api.clear.compiled.tpl.tpl
*/
$smarty->clearCompiledTemplate( $template );

/**
 * Limpiamos todo el cache
 * @link https://www.smarty.net/docs/en/api.clear.all.cache.tpl
*/
$smarty->clearAllCache();

/**
 * Cargamos todo el contenido de las plantillas en HTML
 * @link https://www.smarty.net/docs/en/api.display.tpl
*/
$smarty->display( $template );