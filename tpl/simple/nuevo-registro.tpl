<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>[titulo]</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />

<script type="text/JavaScript" src="lib/jquery/jquery-1.8.2.js"></script>
<script type="text/JavaScript" src="lib/jquery/jquery-ui-1.9.1.custom.js"></script>
<script type="text/JavaScript" src="lib/jquery/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/JavaScript" src="lib/funciones.js"></script>
<script type="text/JavaScript" src="lib/etiquetas.js"></script>

<link href="http://feeds.feedburner.com/cicese/tnXQ" rel="alternate" title="Actualizaciones en Numenor" type="application/atom+xml"/>
</head>

<body onload="sesion.email.focus();loadInfo('mostrar_etiquetas');">
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
    <td align="left" valign="bottom"><h3 style="position:relative; left:-35px;">nuevo
    registro </h3></td>
    <td width="200" align="right"><img src="img/im004.gif" width="200" height="93" border="0" usemap="#Map" /></td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="7" background="img/im005.gif">&nbsp;</td>
    <td width="18" valign="top" bgcolor="#FFFFFF"><table width="170" border="0" cellpadding="0" cellspacing="0" background="img/im009.gif">
      <tr>
        <td><div class="comentario">[if siMensajeError -s mensajeError][mensajeError][endif siMensajeError] Para acceder al repositorio
            introduzca su correo electr&oacute;nico y su
          contrase&ntilde;a. Si a&uacute;n no la tiene <a href="?p=nuevo-registro">haga
          click aqu&iacute;</a> para crear
          una cuenta. Para cualquier duda o comentario escr&iacute;banos a <a href="mailto:alex.bdiego@gmail.com">alex.bdiego@gmail.com</a></div></td>
      </tr>
    </table>
    <img src="img/im011.gif" width="178" height="8" /></td>
    <td align="left" valign="top" bgcolor="#FFFFFF" class="margen30id"><form id="sesion" name="sesion" method="post" action="?p=procesar-registro">
      <table border="0" cellpadding="0" cellspacing="0" class="tabla2">
        <tr>
          <th width="171">Correo Electr&oacute;nico </th>
          <td width="867"><input name="email" type="text" id="email"[if siEmail -s email] value="[email]"[endif siEmail] size="30" /></td>
        </tr>
        <tr>
          <th>Contrase&ntilde;a </th>
          <td><input name="password" type="password" id="password" size="25" /></td>
        </tr>
        <tr>
          <th>Confirmaci&oacute;n de Contrase&ntilde;a </th>
          <td><input name="password2" type="password" id="password2" size="25" /></td>
        </tr>
      </table>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla2">
          <tr>
            <th width="171">Nombre(s) </th>
            <td width="867"><input name="nombres" type="text" id="nombres"[if siNombres -s nombres] value="[nombres]"[endif siNombres] size="30" /></td>
          </tr>
          <tr>
            <th>Apellido(s) </th>
            <td><input name="apellidos" type="text" id="apellidos"[if siApellidos -s apellidos] value="[apellidos]"[endif siApellidos] size="30" /></td>
          </tr>
          <tr>
            <th>Sexo: </th>
            <td><select name="sexo" id="sexo">
              <option value="m" selected="selected">Masculino</option>
              <option value="f">Femenino</option>
            </select>
            </td>
          </tr>
          <tr>
            <th>P&aacute;gina Web: </th>
            <td><input name="website" type="text" id="website"[if siWebsite -s website] value="[website]"[endif siWebsite] size="30" /></td>
          </tr>
          <tr>
            <th>Temas de inter&eacute;s: </th>
            <td><textarea name="etiquetas" id="etiquetas" class="txtDropTarget ui-droppable" cols="50" rows="3"></textarea>    
            <div id="draggable_etiquetas">
            </div></td>
          </tr>
          <tr>
            <th>&nbsp;</th>
            <td><input type="submit" name="Submit" value="Enviar" />
            <input type="button" name="Submit2" value="Cancelar" style="font-weight: normal" onclick="window.location = '.'" /></td>
          </tr>
        </table>
        </form>
    </td>
    <td width="9" background="img/im006.gif">&nbsp;</td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" background="img/im007.gif" bgcolor="#FFFFFF">
  <tr>
    <td align="left"><img src="img/im008.gif" width="200" height="59" /></td>
    <td align="right"><img src="img/im012.gif" width="180" height="59" /></td>
  </tr>
</table>

<map name="Map" id="Map"><area shape="rect" coords="17,21,172,45" href="http://www.cicese.mx/" target="_blank" alt="Centro de Investigaci&oacute;n Cient&iacute;fica y de Educaci&oacute;n Superior de la ciudad de Ensenada, B.C." />
</map>
<map name="Map2" id="Map2"><area shape="rect" coords="27,30,162,70" href="." />
</map></body>
</html>
