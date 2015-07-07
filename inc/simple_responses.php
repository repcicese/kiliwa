<?php
require "../cfg/general.php";

if(isset($_POST['option'])){
			
	global $mysql_servidor, $mysql_usuario, $mysql_pword, $mysql_bdatos;
	mysql_pconnect($mysql_servidor, $mysql_usuario, $mysql_pword);
	mysql_select_db($mysql_bdatos);		
	$salida="";
	
	switch($_POST['option']){
		//CARGAR TABLA CON ETIQUETAS DEL GRUPO
		case 'tabla-etiquetas-con-reload-combobox':{
			$etiquetas	='';
			//Buscar nombre del grupo de trabajo
			$query		= "SELECT * FROM `zSistema_grupos_trabajo` WHERE `id` = '".$_POST['idg']."';";
			$res		= mysql_query($query);	echo mysql_error();
			$r			= mysql_fetch_array($res);
			$nombre 	= $r['nombre'];
			$id_grupo 	= $r['id'];
			
			
			//Buscar etiquetas
			$query="SELECT `kControl_etiquetas`.`nombre`, `kControl_diccionario_etiquetas_grupos_trabajo`.`id` 
					FROM `kControl_etiquetas` `kControl_etiquetas` 
					INNER JOIN `kControl_diccionario_etiquetas_grupos_trabajo` `kControl_diccionario_etiquetas_grupos_trabajo` ON `kControl_etiquetas`.`id`=`kControl_diccionario_etiquetas_grupos_trabajo`.`id_etiqueta`
					WHERE `kControl_diccionario_etiquetas_grupos_trabajo`.`id_grupo` = '".$_POST['idg']."' ORDER BY `kControl_etiquetas`.`nombre` ASC";
			$res=mysql_query($query);	echo mysql_error();
			
			while ($r = mysql_fetch_array($res)) {
				$etiquetas .= '<li><a>'.$r['nombre'].'</a><img src="tpl/simple/img/bullet_delete.png" border="0" style="cursor:pointer" onClick="if(confirm(\'Desea eliminar la etiqueta?\')) services(\'borrar-etiqueta\','.$r['id'].',0);"/></li>';
			}
			
			$salida='
	<table border="0" cellpadding="0" cellspacing="0" class="tabla2">
	    <tr>
          <th width="160"><img style="cursor:pointer" src="tpl/simple/img/delete.png" width="16" height="16" border="0" onClick="if(confirm(\'Desea eliminar el grupo de trabajo?\')) services(\'borrar-grupo-trabajo\','.$id_grupo.',0);"/> '.$nombre.'</th>
          <td width="895">
    	    <div>
	        	<ul class="etiquetas">
                	'.$etiquetas.'
                </ul>
        	</div>
          </td>
        </tr>
	</table>
			';				
		} break;
		//CARGAR COMBOBOX
		case 'combobox-grupos':{
			$content="";
			
			$query		= "SELECT * FROM `zSistema_grupos_trabajo` ORDER BY `nombre` ASC;";
			$res		= mysql_query($query);
						
			while ($r = mysql_fetch_array($res)) {
				$content .= '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
			}
			
			$salida='
				<select name="lista_grupos_trabajo" id="lista_grupos_trabajo" onchange="onChangeOptions(\'preparar-formulario-lista-grupos-trabajo\',this.value)">
					<option value="0" selected="selected">Seleccione una opción</option>
					'.$content.'
					<option value="-1">-- Registrar grupo nuevo --</option>
				</select>
				';
				
		} break;
		//ALTA DE GRUPO DE TRABAJO
		case 'guardar-grupo-trabajo':{
			$query	= "INSERT INTO `zSistema_grupos_trabajo` (`nombre`) VALUES('".$_POST['nombre_grupo']."')";
			$res	= mysql_query($query);	echo mysql_error();
			$id 	= mysql_insert_id();
			
			$arreglo = explode(',', $_POST['etiquetas']);
			for($j=0;$j<count($arreglo);$j++){
				if(formatoTag($arreglo[$j])!=""){
					//buscar si la etiqueta ya existe
					$query	= "SELECT `id` FROM `kControl_etiquetas` WHERE `nombre` LIKE '".formatoTag($arreglo[$j])."' LIMIT 1";
					$res	= mysql_query($query);	echo mysql_error();
					$r		= mysql_fetch_array($res);
					$n		= mysql_num_rows($res);
				
					$id_tag=$r['id'];

					//si no existe, guardarla en kControl_etiquetas y tomar el id
					if($n==0){
						$query	= "INSERT INTO `kControl_etiquetas` (`nombre`) VALUES('".formatoTag($arreglo[$j])."')";
						$res	= mysql_query($query);	echo mysql_error();	
						$id_tag	= mysql_insert_id();	
					}
				
					//guardar los datos en el diccionario de etiquetas
					$query	= "INSERT INTO `kControl_diccionario_etiquetas_grupos_trabajo` (`id_grupo`,`id_etiqueta`) VALUES('".$id."','".$id_tag."')";
					$res	= mysql_query($query);	echo mysql_error();			
				}//termina validar tag = nill
			}

			$salida='OK';
		} break;
		//BORRAR ETIQUETA
		case 'borrar-etiqueta':{
			$query	= "DELETE FROM `kControl_diccionario_etiquetas_grupos_trabajo` WHERE `id`='".$_POST['id']."'";
			$res	= mysql_query($query);	echo mysql_error();
			$salida='OK';
		} break;
		//BORRAR GRUPO DE TRABAJO
		case 'borrar-grupo-trabajo':{
			$query	= "DELETE FROM `kControl_diccionario_etiquetas_grupos_trabajo` WHERE `id_grupo`='".$_POST['id']."'";
			$res	= mysql_query($query);	echo mysql_error();
			
			$query	= "DELETE FROM `zSistema_grupos_trabajo` WHERE `id`='".$_POST['id']."'";
			$res	= mysql_query($query);	echo mysql_error();
			$salida='OK';
		} break;
		//ACTUALIZAR NOMBRE DE GRUPO DE TRABAJO Y AGREGAR ETIQUETAS
		case 'actualizar-grupo-trabajo':{
			$id=$_POST['id_grupo'];
			if($_POST['nombre_grupo']!=""){
				$query	= "UPDATE `zSistema_grupos_trabajo` SET `nombre`='".$_POST['nombre_grupo']."' WHERE `id`='".$id."'";
				//$query	= "UPDATE INTO `zSistema_grupos_trabajo` (`nombre`) VALUES('".$_POST['nombre_grupo']."')";
				$res	= mysql_query($query);	echo mysql_error();
				//$id 	= mysql_insert_id();
			}
			
			if($_POST['etiquetas']!=""){
				$arreglo = explode(',', $_POST['etiquetas']);
				for($j=0;$j<count($arreglo);$j++){
					if(formatoTag($arreglo[$j])!=""){
						//buscar si la etiqueta ya existe
						$query	= "SELECT `id` FROM `kControl_etiquetas` WHERE `nombre` LIKE '".formatoTag($arreglo[$j])."' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						$id_tag=$r['id'];
					
						//si no existe, guardarla en kControl_etiquetas y tomar el id
						if($n==0){
							$query	= "INSERT INTO `kControl_etiquetas` (`nombre`) VALUES('".formatoTag($arreglo[$j])."')";
							$res	= mysql_query($query);	echo mysql_error();	
							$id_tag	= mysql_insert_id();	
						}
					
						//verificar que no se dupliquen las etiquetas en el diccionario del grupo
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas_grupos_trabajo` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_grupo` LIKE '".$id."' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas_grupos_trabajo` (`id_grupo`,`id_etiqueta`) VALUES('".$id."','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//termina valida if tag = nill
				}
			}

			$salida='OK';
		} break;
		//CARGAR COMBOBOX DE PERFILES
		case 'combobox-perfiles':{
			$content="";
			
			$query		= "SELECT * FROM `zSistema_perfil` ORDER BY `nombre` ASC;";
			$res		= mysql_query($query);
						
			while ($r = mysql_fetch_array($res)) {
				$content .= '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
			}
			
			$salida='
				<select name="lista_perfiles" id="lista_perfiles" onchange="onChangeOptions(\'preparar-formulario-lista-perfiles\',this.value)">
					<option value="0" selected="selected">Seleccione una opción</option>
					'.$content.'
					<option value="-1">-- Registrar grupo nuevo --</option>
				</select>
				';
		} break;
		case 'tabla-perfiles-con-reload-combobox':{
			$perfil="";
			//Buscar perfiles
			$query		= "SELECT * FROM `zSistema_perfil` WHERE `id` LIKE '".$_POST['idp']."';";
			$res		= mysql_query($query);	echo mysql_error();

			while ($r = mysql_fetch_array($res)) {
				
				$perfil .='<tr>
						  	<th width="160"><img style="cursor:pointer" '.puedoborrar(validad_uso('zSistema_perfil',$r['id']),'src="tpl/simple/img/delete.png" onClick="if(confirm(\'Desea eliminar el perfil?\')) services(\'borrar-perfil\','.$r['id'].',0);"','src="tpl/simple/img/delete_off.png" onClick="alert(\'El perfil esta siendo utilizado por alg\u00fan registro, por lo que no es posible eliminar.\')"').' width="16" height="16" border="0" />'.$r['nombre'].'</th>
						  	<td align="center"><input type="checkbox" name="crear_usuarios" id="crear_usuarios" '.validateCheckBox($r['crear_usuarios']).'/></td>
							<td align="center"><input type="checkbox" name="crear_recursos" id="crear_recursos" '.validateCheckBox($r['crear_recursos']).'/></td>
						  	<td align="center"><input type="checkbox" name="actualizar_recursos" id="actualizar_recursos" '.validateCheckBox($r['actualizar_recursos']).'/></td>
						  	<td align="center"><input type="checkbox" name="borrar_recursos" id="borrar_recursos" '.validateCheckBox($r['borrar_recursos']).'/></td>
						  	<td align="center"><input type="checkbox" name="configuracion_perfil" id="configuracion_perfil" '.validateCheckBox($r['configuracion_perfil']).'/></td>
						  	<td align="center"><input type="checkbox" name="configuracion_grupos" id="configuracion_grupos" '.validateCheckBox($r['configuracion_grupos']).'/></td>
						   </tr>';
			}
			
			$salida='<table border="0" cellpadding="0" cellspacing="0" class="tabla2">
						<tr>
						  <th><strong>Nombre</strong></th>
						  <th ><strong>Crear usuarios</strong></th>
						  <th ><strong>Crear recursos</strong></th>
						  <th ><strong>Actualizar recursos</strong></th>
						  <th ><strong>Borrar recursos</strong></th>
						  <th ><strong>Configurar perfiles</strong></th>
						  <th "><strong>Configurar grupos</strong></th>
						</tr>
						'.$perfil.'
						<tr>
						  <th>&nbsp;</th>
						  <td colspan="5"><input type="button" name="button_perfil_actualizar" id="button_perfil_actualizar" value="Actualizar" onclick="services(\'actualizar-perfil\',0,0)"/></td>
						</tr>
					</table>';

		} break;
		//ALTA DE PERFIL
		case 'guardar-perfil':{
			$query	= "INSERT INTO `zSistema_perfil` (`nombre`) VALUES('".$_POST['nombre_perfil']."')";
			$res	= mysql_query($query);	echo mysql_error();
			$salida='OK';
		} break;
		//ACTUALIZAR PRIVILEGIOS DE PERFIL
		case 'actualizar-perfil':{
			if($_POST['nombre_perfil']!=""){
				$query	= "UPDATE `zSistema_perfil` SET `nombre`='".$_POST['nombre_perfil']."' WHERE `id`='".$_POST['id_perfil']."'";
				$res	= mysql_query($query);	echo mysql_error();	
			}
			$query	= "UPDATE `zSistema_perfil` SET `crear_usuarios`='".$_POST['crear_usuarios']."', `crear_recursos`='".$_POST['crear_recursos']."', `actualizar_recursos`='".$_POST['actualizar_recursos']."', `borrar_recursos`='".$_POST['borrar_recursos']."', `configuracion_perfil`='".$_POST['configuracion_perfil']."', `configuracion_grupos`='".$_POST['configuracion_grupos']."' WHERE `id`='".$_POST['id_perfil']."'";
			$res	= mysql_query($query);	echo mysql_error();
			$salida='OK';
		} break;
		//BORRAR PERFIL
		case 'borrar-perfil':{
			$query	= "DELETE FROM `zSistema_perfil` WHERE `id`='".$_POST['id']."'";
			$res	= mysql_query($query);	echo mysql_error();
			$salida='OK';
		} break;
		//CARGAR COMBOBOX DE PERFILES PARA PRINCIPAL
		case 'combobox-perfiles-principal':{
			$content="";
			
			$query		= "SELECT * FROM `zSistema_perfil` ORDER BY `nombre` ASC;";
			$res		= mysql_query($query);
						
			while ($r = mysql_fetch_array($res)) {
				$content .= '<option value="'.$r['id'].'">'.$r['nombre'].'</option>';
			}
			
			$salida='
				<select name="lista_perfiles" id="lista_perfiles">
					<option value="0" selected="selected">SELECCIONE UN PERFIL</option>
					'.$content.'
				</select>
				';
		} break;
		//GENERAR Y ENVIAR CLAVE DE ACCESO NUEVA
		case 'generar-password':{
			$content="";
			
			//GENERAR CLAVE DE ACCESO ALEATORIA
			$psswtemp	=	generatePassword();
			$password	= 	crypt($psswtemp, 'crp');
			
			//BUSCAR REGISTRO CON CORREO $_POST['email']
			$query	= "UPDATE `aRecurso_usuarios` SET `password`='".$password."' WHERE `email`='".$_POST['email']."'";
			$res	= mysql_query($query);	echo mysql_error();
						
			//ENVIAR NOTIFICACIÓN Y CONTRASEÑA NUEVA
			//para el envío en formato HTML
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=utf-8\r\n"; 

			//dirección del remitente 
			$headers .= "From: CICESE-REP <ciceserep@cicese.mx>\r\n";
		
			$content="<html> 
						<head> 
							<title>Clave de acceso</title>
						</head> 
						<body>
							<strong>CICESE-REP</strong>
							<p align='justify'>Se ha generado una nueva clave de acceso con base en su solicitud del día.</p>
							<p><strong>Clave de acceso: <i>".$psswtemp."</i></strong></p> 
							<p>http://numenor.cicese.mx/repositorio</p>
							<p align='justify' style='color:#003399'>AVISO DE CONFIDENCIALIDAD: Este correo electr&oacute;nico es confidencial y para uso exclusivo de la(s) persona(s) a quien(es) se dirige. Si el lector de esta transmisi&oacute;n electr&oacute;nica no es el destinatario, se le notifica que cualquier distribuci&oacute;n o copia de la misma est&aacute; estrictamente prohibida. Si ha recibido este correo por error le suplicamos notificar inmediatamente a la persona que lo envi&oacute; y borrarlo definitivamente de su sistema. Gracias.</p>
							<p align='justify' style='color:#003399'>CONFIDENTIALITY NOTICE: This electronic mail transmission is confidential, may be privileged and should be read or retained only by the intended recipient. If the reader of this transmission is not the intended recipient, you are hereby notified that any distribution or copying hereof is strictly prohibited. If you have received this transmission in error, please immediately notify the sender and delete it from your system. Thank you.</p>
						</body> 
						</html>";
						
			mail($_POST['email'],"Clave de acceso CICESE-REP",$content,$headers);
			
			$salida='OK';
		} break;
		default:
	}
	
	echo $salida;
}

//------------------------ FUNCIONE VARIAS
function formatoTag($text){
		$text = trim($text);
		$text = htmlentities($text, ENT_QUOTES, 'UTF-8');
		$text = strtolower($text);
		$patron = array (
			// Espacios, puntos y comas por guion
			'/[\., ]+/' => '-',
 
			// Vocales
			'/&agrave;/' => 'a',
			'/&egrave;/' => 'e',
			'/&igrave;/' => 'i',
			'/&ograve;/' => 'o',
			'/&ugrave;/' => 'u',
 
			'/&aacute;/' => 'a',
			'/&eacute;/' => 'e',
			'/&iacute;/' => 'i',
			'/&oacute;/' => 'o',
			'/&uacute;/' => 'u',
 
			'/&acirc;/' => 'a',
			'/&ecirc;/' => 'e',
			'/&icirc;/' => 'i',
			'/&ocirc;/' => 'o',
			'/&ucirc;/' => 'u',
 
			'/&atilde;/' => 'a',
			'/&etilde;/' => 'e',
			'/&itilde;/' => 'i',
			'/&otilde;/' => 'o',
			'/&utilde;/' => 'u',
 
			'/&auml;/' => 'a',
			'/&euml;/' => 'e',
			'/&iuml;/' => 'i',
			'/&ouml;/' => 'o',
			'/&uuml;/' => 'u',
 
			'/&auml;/' => 'a',
			'/&euml;/' => 'e',
			'/&iuml;/' => 'i',
			'/&ouml;/' => 'o',
			'/&uuml;/' => 'u',
 
			// Otras letras y caracteres especiales
			'/&aring;/' => 'a',
			'/&ntilde;/' => 'n',
 
			// Agregar aqui mas caracteres si es necesario
 
		);
 
		$text = preg_replace(array_keys($patron),array_values($patron),$text);
		return $text;
}

function validateCheckBox($value){
	if($value=='1')
		$cheked="checked='checked'";
	else
		$cheked=" ";
	return $cheked;
}

//busca registros dentro de las tablas en las puede haberse utilizado el catalogo correspondiente
function validad_uso($nombreCatalogo,$idRow){
	$n=1;
	switch($nombreCatalogo){
		case 'zSistema_perfil':{			$table="aRecurso_usuarios"; 								$columna="id_perfil";				}break;
		default:
	}
	$query		= "SELECT * FROM `".$table."` WHERE `".$columna."`='".$idRow."' LIMIT 1";
	$res		= mysql_query($query);	echo mysql_error();
	$n			= mysql_num_rows($res);
	return($n==0)?true:false;
}
	
function puedoborrar($value,$yes,$nop){
	return($value)?$yes:$nop;
}

function generatePassword(){
	    $password="";
    	$ptrn1=$ptrn2=$ptrn3="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ1234567890";
	    $length=strlen($ptrn3);
    	$contador=0;
	    $lenght_p3 = strlen($ptrn3);
    	while($contador<$lenght_p3){
        	$largo = strlen($ptrn1);
	        $matriz[$contador]=substr($ptrn1,0,1);
    	    $ptrn1=substr($ptrn1,1,$largo-1);
        	$contador++;
	    }
    	$lenght_p3 = strlen($ptrn3);
	    $i=0;
    	while($i<8){
	        $largo=strlen($ptrn2);
    	    $contador=0;
        	$value= substr($ptrn2,rand(0,$largo-1),1);
        while($contador<$lenght_p3){
            if($value == $matriz[$contador])
                $nro=$contador;
            $contador++;
        }
	    $valor=$largo-($nro);
        $ptrn2=substr($ptrn2,0,$nro).substr($ptrn2,$nro+1,$valor);
        $ptrn4=$ptrn2;
        $password.=$value;
        $c=0;
        while($c<$largo){
            $matriz[$c]=substr($ptrn4,0,1);
            $ptrn4=substr($ptrn4,1,$largo-1);
            $c++;
        }
        $i++;
    	}
		return $password;
	}	
?>
