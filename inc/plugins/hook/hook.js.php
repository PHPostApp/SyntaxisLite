<?php 

$myFolderJS = $smarty->template_dir['js'];
$myRouteJS = $config['js'];
$pagew = $smarty->tpl_vars['tsPage']->value;

$time = time();

if($params['name'] == 'head' OR $params['name'] == 'header') {
	foreach($params['js'] as $k => $script) {
		if(file_exists($myFolderJS.$script)) {
			$sl2html .= "<script src=\"$myRouteJS/$script?$time\"></script>\n";
		}
	}
} elseif($params['name'] == 'footer') {
	$myCDN = 'https://cdn.jsdelivr.net/combine/';

	$isMember = $smarty->tpl_vars['tsUser']->value->is_member;
	$myKey = $smarty->tpl_vars['tsKeyPbulic']->value;
	
	foreach ($params['cdns'] as $key => $cdn) $addCDN[$key] = "npm/$cdn";
	$add = join(',', $addCDN);
	$sl2html .= "<script src=\"{$myCDN}{$add}\"></script>\n";
	foreach($params['js'] as $k => $js) {
		if(!empty($js)) {
			$sl2html .= "<script src=\"{$myRouteJS}/{$js}?$time\"></script>\n";
		}
	}
}