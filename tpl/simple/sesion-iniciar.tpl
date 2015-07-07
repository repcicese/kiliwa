<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>[titulo]</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript" src="lib/funciones.js"></script>
<link href="http://feeds.feedburner.com/cicese/tnXQ" rel="alternate" title="Actualizaciones en Numenor" type="application/atom+xml"/>
<script language="javascript">
	function autoEmail() {
		var s = sesion.email.value;
		s = s.split(' ');
		s = s.join('');
		sesion.email.value = s;
		sesion.email.focus();
		sesion.email.select();
	}
</script>
</head>

<body onload="autoEmail()">
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="img/pixel.gif" width="1" height="24" /></td>
  </tr>
</table>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" background="img/im002.gif" bgcolor="#FFFFFF">
  <tr>
    <td width="250" align="left"><img src="img/im003.gif" width="250" height="93" border="0" usemap="#Map2" /></td>
    <td align="left" valign="bottom"><h3 style="position:relative; left:-35px;">inicio
        de sesi&oacute;n</h3></td>
    <td width="200" align="right"><img src="img/im004.gif" width="200" height="93" border="0" usemap="#Map" /></td>
  </tr>
</table>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="7" background="img/im005.gif">&nbsp;</td>
    <td width="18" valign="top" bgcolor="#FFFFFF"><table width="170" border="0" cellpadding="0" cellspacing="0" background="img/im009.gif">
      <tr>
        <td><div class="comentario">[if siMensaje -s mensaje][mensaje][endif siMensaje] [if siMensajeError -s mensajeError][mensajeError][endif siMensajeError] Para acceder al repositorio
            introduzca su correo electr&oacute;nico y su
          contrase&ntilde;a. Si a&uacute;n no la tiene <a href="?p=nuevo-registro">haga
          click aqu&iacute;</a> para crear
          una cuenta. Para cualquier duda o comentario escr&iacute;banos a <a href="mailto:alex.bdiego@gmail.com">alex.bdiego@gmail.com</a><br /><br />
          <div style="cursor:pointer" onClick="if(document.getElementById('email').value==''){ alert('Capture su correo electr\u00f3nico.'); }else { if(confirm('Desea generar una nueva clave de acceso para el usuario con correo electr\u00f3nico: \n '+document.getElementById('email').value+'?\n\nIMPORTANTE: El sistema generar\u00e1 y enviar\u00e1 una nueva clave de acceso al correo electr\u00f3nico correspondiente.')) services('generar-password',0,0); }">&iquest;Olvido su constrase&ntilde;a?</div>
        </div></td>
      </tr>
    </table>
    <img src="img/im011.gif" width="178" height="8" /></td>
    <td align="left" valign="top" bgcolor="#FFFFFF" class="margen30id"><form id="sesion" name="sesion" method="post" action="?p=procesar-sesion">
      <table border="0" cellpadding="0" cellspacing="0" class="tabla">
        <tr>
          <th width="120">Correo Electr&oacute;nico </th>
          <td><input name="email" type="text" id="email" [if siEmail -s email] value="[email]" [endif siEmail] size="25" />
            <br />
            <a href="?p=nuevo-registro">&iquest;A&uacute;n no se registra?</a>
            </td>
        </tr>
        <tr>
          <th>Contrase&ntilde;a</th>
          <td><input name="password" type="password" id="password" size="25" />
            <br />
            <input name="recordar" type="checkbox" id="recordar" value="ok" /> 
            Recordar contrase&ntilde;a </td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td><input type="submit" name="Submit" value="Acceder" /></td>
        </tr>
      </table>
        </form>
    </td>
    <td width="9" background="img/im006.gif">&nbsp;</td>
  </tr>
</table>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" background="img/im007.gif" bgcolor="#FFFFFF">
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
