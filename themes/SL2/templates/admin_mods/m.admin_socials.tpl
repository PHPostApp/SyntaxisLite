<div class="boxy-title">
   <h3>Configurar redes sociales</h3>
</div>
<div id="res" class="boxy-content">
   {if $tsSave}<div class="alert alert-success">Configuraciones guardadas</div>{/if}
   {if $tsAct == ''}

   	{foreach from=$tsSocials item=social}
   		<div class="{$social.social_name}" id="red{$social.social_id}">
   			<div class="title" style="display: flex; justify-content: space-between; align-items: center;">
   				<h3 style="margin: 0;">{$social.social_name|ucfirst}</h3>
	   			<div class="actions">
						<a href="{$tsConfig.url}/admin/socials/?act=editar&id={$social.social_id}">Editar</a>
						<a href="javascript:redes.borrar({$social.social_id})">Borrar</a>
	   			</div>
   			</div>
            <p>Client ID: <strong>{$social.social_client_id}</strong></p>
            <p>Client Secret: <strong>{$social.social_client_secret}</strong></p>
   			<p>Redirect URL: <strong>{$social.social_redirect_uri}</strong></p>
   		</div>
   	{/foreach}
   	<hr>
   	<a href="{$tsConfig.url}/admin/socials?act=nueva" class="mBtn btnOk">Agregar Nueva Red social</a>
			
   {elseif $tsAct === 'nueva' || $tsAct === 'editar'}
	   <form action="" method="post" autocomplete="off">
	      <fieldset>
	         <legend>{$tsAct|ucfirst} red social</legend>
	         {if $tsAct === 'editar'}
	         	<input type="hidden" name="social_id" value="{$tsSocial.social_id}">
	         {else}
	         	<dl>
            		<dt><label for="social_name">Sitio:</label></dt>
            		<dd>
            			{html_options name='social_name' id='social_name' options=['github' => 'Github', 'google' => 'Google'] selected=$tsSocial.social_name class="form-select"}
            		</dd>
         		</dl>
	         {/if}
	         <dl>
            	<dt><label for="clientid">Client-ID:</label></dt>
            	<dd><input class="form-control" type="text" id="clientid" name="social_client_id" value="{$tsSocial.social_client_id}" /></dd>
         	</dl>
	         <dl>
            	<dt><label for="clientsecret">Client-Secret:</label></dt>
            	<dd><input class="form-control" type="text" id="clientsecret" name="social_client_secret" value="{$tsSocial.social_client_secret}" /></dd>
         	</dl>
            {if $tsAct !== 'editar'}
	         <dl>
            	<dt><label for="redirecturi">Redirect URL:</label></dt>
            	<dd><input class="form-control" type="text" id="redirecturi" disabled value="{$tsConfig.url}{$tsSocial.social_redirect_uri}" /></dd>
         	</dl>
            {/if}
	         <p><input type="submit" name="save" value="Guardar Cambios" class="btn btn-primary" /></p>
	      </fieldset>
	   </form>
	   <script>
	   	if($('#redirecturi').val() === global_data.url) 
	   		$('#redirecturi').val(global_data.url + '/github.php')
	   	$('#social_name').on('change', () => {
	   		var replace = global_data.url + '/' + $('#social_name option:selected').val() + '.php';
	   		$('#redirecturi').val(replace)
	   	})
	   </script>
	  {/if}
</div>