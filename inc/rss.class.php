<?php
class rss {	
	public static function newEntry($content){
		
		$fname = 'rss/serialize';
		$atomfname = 'rss/atom.xml';
		
		$entries = array();
		$entries2 = array();
		$entries3 = array();

		$atomheader = 	'<?xml version="1.0" encoding="utf-8"?>'.
					'<feed xmlns="http://www.w3.org/2005/Atom">'.
					'<title>Actualizaciones en Numenor</title>'.
					'<link rel="alternate" type="text/html" href="http://numenor.cicese.mx/repositorio/" />'.
					'<modified>'. date('Y-m-d\TH:i:s-07:00').'</modified>'.
					'<author>'.
						'<name>olea@cicese.mx</name>'.
					'</author>';
		
		$atomfooter = 	'</feed>';

		$entries[] = $content;

		/*Si hay entradas previas las combina con la nueva. Si es la primera entrada
		solamente despliega esa*/
		$filecontent = @file_get_contents($fname);
		if($filecontent == ''){
			$result = $entries;
		} else {
			$entries2 = unserialize($filecontent);
			$entries3 = array_slice($entries2,0,13);
			$result = array_merge($entries,$entries3);
		}

		//Serializa el nuevo arreglo
		$fhandle = fopen($fname,'w') or die("No se puede abrir el archivo de serialización para escritura".$fname);
		fwrite($fhandle,serialize($result));
		fclose($fhandle);
		
		//Escribe los nuevos datos al archivo atom.xml 
		$fhandle = fopen($atomfname,'w') or die("No se puede abrir el archivo atom.xml para escritura");
		//Primero el encabezado
		fwrite($fhandle,$atomheader);
		//Ahora cada una de las entradas + la nueva
		foreach($result as $e => $value){
			fwrite($fhandle,$value);
		}
		//Para cerrar...
		fwrite($fhandle,$atomfooter);
		fclose($fhandle);
		
	}
	
	public static function newEntryMAILTO($usuario,$msg,$msg2,$msg3,$id_tags,$tipo){
		//para el envío en formato HTML
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=utf-8\r\n"; 

		//dirección del remitente 
		$headers .= "From: CICESE-REP <ciceserep@cicese.mx>\r\n";
		
		$content="<html> 
						<head> 
							<title>Actualizaciones en Numenor</title>
						</head> 
						<body>
							<strong>".$tipo." CICESE-REP</strong>
							<p align='justify'>Publicaci&oacute;n realizada el ".FechaFormateada(time())." por el usuario ".$usuario.".</p>
							<p><strong>Actividad:</strong></p> 
							<p align='justify'>".$msg." <i>'".$msg2."'.</i></p> 
							<p>Ver recurso en ".$msg3."</p>
							<p align='justify' style='color:#003399'>AVISO DE CONFIDENCIALIDAD: Este correo electr&oacute;nico es confidencial y para uso exclusivo de la(s) persona(s) a quien(es) se dirige. Si el lector de esta transmisi&oacute;n electr&oacute;nica no es el destinatario, se le notifica que cualquier distribuci&oacute;n o copia de la misma est&aacute; estrictamente prohibida. Si ha recibido este correo por error le suplicamos notificar inmediatamente a la persona que lo envi&oacute; y borrarlo definitivamente de su sistema. Gracias.</p>
							<p align='justify' style='color:#003399'>CONFIDENTIALITY NOTICE: This electronic mail transmission is confidential, may be privileged and should be read or retained only by the intended recipient. If the reader of this transmission is not the intended recipient, you are hereby notified that any distribution or copying hereof is strictly prohibited. If you have received this transmission in error, please immediately notify the sender and delete it from your system. Thank you.</p>
						</body> 
						</html>";
		
		//construir query para las n etiquetas
		$arreglo = explode(',', $id_tags);
		for($j=0;$j<count($arreglo)-1;$j++){
			$q_etiquetas	.=	" `kControl_diccionario_etiquetas_usuarios`.`id_etiqueta` = '".$arreglo[$j]."' OR";
		}
		$q_etiquetas = substr($q_etiquetas, 0, -2);
		
		//SELECCIONAR LOS MAILS DE TODOS LOS USUARIOS QUE TENGAN INTERES EN $tags
		$query="SELECT `aRecurso_usuarios`.`email` AS `email`, `aRecurso_usuarios`.`notificar` AS `notificar` 
					FROM `aRecurso_usuarios` `aRecurso_usuarios` 
					INNER JOIN `kControl_diccionario_etiquetas_usuarios` `kControl_diccionario_etiquetas_usuarios` ON `kControl_diccionario_etiquetas_usuarios`.`id_usuario`=`aRecurso_usuarios`.`id`
					WHERE (".$q_etiquetas.") GROUP BY `id_usuario`";
		
		$res=mysql_query($query);
		
		while($r = mysql_fetch_array($res)){
			if($r['notificar']==1)
				mail($r['email'],"Actualizaciones en Numenor",$content,$headers);
		}
	}
	
	public static function newUserMAILTO($email,$tipo,$option){
		//para el envío en formato HTML
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=utf-8\r\n"; 

		//dirección del remitente 
		$headers .= "From: CICESE-REP <ciceserep@cicese.mx>\r\n";
		
		switch($option){
			case 1:
				$text="Gracias por registrarse al Repositorio de Información para Grupos de Investigaci&oacute;n. <strong>Su registro esta en proceso de autorizaci&oacute;n.</strong>";
			break;
			case 2:
				$text="Gracias por registrarse al Repositorio de Información para Grupos de Investigaci&oacute;n. <strong>Su registro ha sido autorizado.</strong>";
			break;
			case 3:
				$text="Gracias por registrarse al Repositorio de Información para Grupos de Investigaci&oacute;n. <strong>Su registro no ha sido autorizado.</strong>";
			break;
			default:	
		}
		
		$content="<html> 
						<head> 
							<title>Registro de Usuario</title>
						</head> 
						<body>
							<strong>".$tipo." CICESE-REP</strong>
							<p align='justify'>".$text."</p>
							<p>Sitio oficial http://numenor.cicese.mx/repositorio/</p>
							<p align='justify' style='color:#003399'>AVISO DE CONFIDENCIALIDAD: Este correo electr&oacute;nico es confidencial y para uso exclusivo de la(s) persona(s) a quien(es) se dirige. Si el lector de esta transmisi&oacute;n electr&oacute;nica no es el destinatario, se le notifica que cualquier distribuci&oacute;n o copia de la misma est&aacute; estrictamente prohibida. Si ha recibido este correo por error le suplicamos notificar inmediatamente a la persona que lo envi&oacute; y borrarlo definitivamente de su sistema. Gracias.</p>
							<p align='justify' style='color:#003399'>CONFIDENTIALITY NOTICE: This electronic mail transmission is confidential, may be privileged and should be read or retained only by the intended recipient. If the reader of this transmission is not the intended recipient, you are hereby notified that any distribution or copying hereof is strictly prohibited. If you have received this transmission in error, please immediately notify the sender and delete it from your system. Thank you.</p>
						</body> 
						</html>";
		
		//SELECCIONAR LOS MAILS DE TODOS LOS USUARIOS QUE TENGAN INTERES EN $tags	
		mail($email,"Actualizaciones en Numenor",$content,$headers);
	}
}
?> 