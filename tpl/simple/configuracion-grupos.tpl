<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>[titulo]</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript" src="lib/funciones.js"></script>
<link href="http://feeds.feedburner.com/cicese/tnXQ" rel="alternate" title="Actualizaciones en Numenor" type="application/atom+xml"/>
</head>

<body onload="MM_preloadImages('img/b01on.gif','img/b02on.gif','img/b03on.gif','img/b04on.gif','img/b05on.gif','img/b06on.gif','img/b07on.gif','img/b09on.gif','img/b08on.gif','img/b10on.gif','img/b11on.gif');loadContent('combobox-grupos');">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="img/pixel.gif" width="1" height="24" />
    <div align="right" style="padding-right:10px">
	<a href="http://feeds.feedburner.com/cicese/tnXQ" title="Suscripción Atom" target="_blank"><img src="img/feed-icon-28x28.png" width="14" height="14" border="0"/></a>
	</div></td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" background="img/im002.gif" bgcolor="#FFFFFF">
  <tr>
    <td width="250" align="left"><img src="img/im003.gif" width="250" height="93" border="0" usemap="#Map2" /></td>
    <td align="left" valign="bottom"><h3 style="position:relative; left:-35px;">grupos de trabajo</h3></td>
    <td width="200" align="right"><img src="img/im004.gif" width="200" height="93" border="0" usemap="#Map" /></td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="7" background="img/im005.gif">&nbsp;</td>
    <td width="178" align="left" valign="top" bgcolor="#FFFFFF"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b01','','img/b01on.gif',1)"></a>
      <table width="178" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><a href="." onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b01','','img/b01on.gif',1)"><img src="img/b01off.gif" alt="Inicio" name="b01" width="170" height="36" border="0" id="b01" /></a></td>
        </tr>
        <tr>
          <td><a href="?p=articulos" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b02','','img/b02on.gif',1)"><img src="img/b02off.gif" alt="Art&iacute;culos" name="b02" width="170" height="36" border="0" id="b02" /></a></td>
        </tr>
        <tr>
          <td><a href="?p=software" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b03','','img/b03on.gif',1)"><img src="img/b03off.gif" alt="Software" name="b03" width="170" height="36" border="0" id="b03" /></a></td>
        </tr>
        <tr>
          <td><a href="?p=noticias" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b04','','img/b04on.gif',1)"><img src="img/b04off.gif" alt="Noticias" name="b04" width="170" height="36" border="0" id="b04" /></a></td>
        </tr>
        <tr>
          <td><a href="?p=tutoriales" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b05','','img/b05on.gif',1)"><img src="img/b05off.gif" alt="Tutoriales" name="b05" width="170" height="36" border="0" id="b05" /></a></td>
        </tr>
        <tr>
          <td><a href="?p=vinculos" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b06','','img/b06on.gif',1)"><img src="img/b06off.gif" alt="V&iacute;nculos" name="b06" width="170" height="36" border="0" id="b06" /></a></td>
        </tr>
        <tr>
          <td><a href="?p=herramientas" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b07','','img/b07on.gif',1)"><img src="img/b07off.gif" alt="Herramientas" name="b07" width="170" height="36" border="0" id="b07" /></a></td>
        </tr>
        <tr>
          <td><a href="?p=configuracion" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b08','','img/b08on.gif',1)"><img src="img/b08off.gif" alt="Configuraci&oacute;n" name="b08" width="170" height="36" border="0" id="b08" /></a></td>
        </tr>
        [if siConfiguracion_grupos -s configuracion_grupos]
        <tr>
          <td><a href="?p=grupos-trabajo" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b10','','img/b10on.gif',1)"><img src="img/b10off.gif" alt="Grupos de trabajo" name="b10" width="170" height="36" border="0" id="b10" /></a></td>
        </tr>
        [endif siConfiguracion_grupos]
        [if siConfiguracion_perfil -s configuracion_perfil]
        <tr>
          <td><a href="?p=perfiles" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b11','','img/b11on.gif',1)"><img src="img/b11off.gif" alt="Perfiles de usuario" name="b11" width="170" height="36" border="0" id="b11" /></a></td>
        </tr>
        [endif siConfiguracion_perfil]
        <tr>
          <td background="img/im009.gif"><img src="img/im010.gif" width="178" height="32" />
            <form id="busqueda" name="busqueda" method="get" action="?p=buscar">
				<input type="text" name="q" class="tbBusqueda" />
            </form>
          </td>
        </tr>
        <tr>
          <td background="img/im009.gif"><img src="img/im011.gif" width="178" height="8" /></td>
        </tr>
    </table></td>
    <td valign="top" bgcolor="#FFFFFF">
	
	[if siMensaje -s mensaje]
            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mensaje">
              <tr>
                <td>[mensaje]</td>
              </tr>
            </table>
        [endif siMensaje]
        [if siMensajeError -s mensajeError]
            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mensajeError">
              <tr>
                <td>[mensajeError]</td>
              </tr>
            </table>
        [endif siMensajeError]
        
	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mensaje" id="tabla_exitosa" style="display:none">
    	<tr>
        	<td>Solicitud exitosa</td>
        </tr>
    </table>
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="mensajeError" id="tabla_error" style="display:none">
    	<tr>
        	<td>Verifique la informaci&oacute;n</td>
        </tr>
    </table>	
	<table width="95%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td align="center"><form id="grupos" name="grupos" method="post" action="#">
	  <table border="0" cellpadding="0" cellspacing="0" class="tabla2">
	    <tr>
	      <th width="160"><input type="hidden" name="idp" id="idp" value="[idp]"/>
	        Grupo de trabajo</th>
	      <td width="895"><div align="left"><select name="lista_grupos_trabajo" id="lista_grupos_trabajo" onchange="onChangeOptions('preparar-formulario-lista-grupos-trabajo',this.value)">
	        <option value="0" selected="selected">Seleccione una opción</option>
            <option value="-1">-- Registrar grupo nuevo --</option>
	        </select>
	        <img id="imagen_combobox_grupo_trabajo" style="display:none" src="img/loading.gif" width="16" height="16" border="0"/></div></td>
	      </tr>
	    <tr height="0">
	      <th height="0"><div id="nombre_grupos" style="display:none">Nombre del grupo</div></th>
	      <td><div id="textfield_grupo" style="display:none"><input type="text" name="nombre_grupo" id="nombre_grupo" /></div></td>
	      </tr>
	    <tr height="0">
	      <th height="0"><div id="nombre_etiqueta" style="display:none">Etiquetas</div></th>
	      <td><div id="textfield_etiqueta" style="display:none"><input type="text" name="etiquetas" id="etiquetas" /> 
	        (Separe las etiquetas con coma)</div></td>
	      </tr>
        [if siConfiguracion_grupos -s configuracion_grupos]
	    <tr height="0">
	      <th height="0"></th>
	      <td height="0">
          <input type="button" name="button_grupo_guardar" id="button_grupo_guardar" value="Guardar" style="display:none" onclick="services('guardar-grupo-trabajo',0,0)" />
          <input type="button" name="button_grupo_actualizar" id="button_grupo_actualizar" value="Actualizar" style="display:none" onclick="services('actualizar-grupo-trabajo',0,0)"/>
          </td>
	    </tr>
        [endif siConfiguracion_grupos]  
	    </table>
        </form>

        
<div id="grupos_trabajo_tabla" align="center" style="display:none">
</div>

    </td></tr></table></td>
    <td width="9" background="img/im006.gif">&nbsp;</td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" background="img/im007.gif" bgcolor="#FFFFFF">
  <tr>
    <td width="200" align="left"><img src="img/im008.gif" width="200" height="59" /></td>
    <td align="left" valign="top" class="correo">[email]</td>
    <td width="180" align="right"><a href="?p=cerrar-sesion" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('b09','','img/b09on.gif',1)"><img src="img/b09off.gif" alt="Cerrar Sesi&oacute;n" name="b09" width="180" height="59" border="0" id="b09" /></a></td>
  </tr>
</table>

<div align="right" style="padding-right:40px">
<img src="img/feed-atom-icon.gif" width="80" height="15" border="0"/>
</div>
<p>
  <map name="Map" id="Map">
    <area shape="rect" coords="17,21,172,45" href="http://www.cicese.mx/" target="_blank" alt="Centro de Investigaci&oacute;n Cient&iacute;fica y de Educaci&oacute;n Superior de la ciudad de Ensenada, B.C." />
  </map>
  <map name="Map2" id="Map2">
    <area shape="rect" coords="27,30,162,70" href="." />
  </map>
</p>
</body>
</html>
