<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$Lang_short}" xml:lang="{$Lang_short}">
<head>
<title>{$tsTitle}</title>
{meta facebook=true twitter=true}
{hook 
	name="head" 
	fonts=["Roboto"] 
	css=['SyntaxisLite.min.css', "$tsPage.css"] 
	js=['jquery.min.js', 'jquery.plugins.js', 'acciones.js', "$tsPage.js"] 
	wysibb=true
}
{jsdelivr type='styles' sources=['driver.js', 'croppr'] combine=true}
{hook name="global"}
</head>

<body name="superior" id="body">
{if $tsUser->is_admod == 1}{$tsConfig.install}{/if}
<div id="swf"></div>
<div id="js" style="display:none"></div>
<div id="mydialog" class="background__level--3" style="display:none"></div>
<div class="UIBeeper" id="BeeperBox"></div>
<div id="brandday">

	{if !$tsMobile && !empty({$tsConfig.news})}
	<div id="mensaje-top">
	   <ul id="top_news" class="msgtxt">
	      {foreach from=$tsConfig.news key=i item=n}
	        <li id="new_{$i+1}" class="p-2">{$n.not_body}</li>
	      {/foreach}
	   </ul>
	</div>
	{/if}
 	<header class="background position-relative" data-bg-multi="url({$tsStyleAdmin.url})" style="background-color:#232323;{$tsStyleAdmin.css}{if $tsMobile}padding: 3rem 0;{/if}">
 		<div class="background__level--3 position-absolute w-100 h-100" style="top: 0;
 		left: 0;"></div>
 		<div class="container position-relative d-flex justify-content-{if !$tsMobile}between{else}center{/if} align-items-center">
 			<a href="{$tsConfig.url}/" rel="internal" title="{$tsConfig.titulo} - {$tsConfig.slogan}">
 				<h1>{$tsConfig.titulo}</h1>
 				<h5>{$tsConfig.slogan}</h5>
 			</a>
 			{if $tsUser->is_member && !$tsMobile}
 				{include file='sections/head_user.tpl'}
 			{/if}
 		</div>
 	</header>
 	<!-- Menu -->
 	{include file='sections/head_menu.tpl'}
	<div class="wrapper{if $tsPage == 'perfil'} bg-light{else} container{if $tsPage == 'admin' || $tsPage == 'moderacion' || $tsPage == 'posts' || $tsPage == 'files'}-fluid{/if}{/if}{if $tsMobile} pt-2{/if}">