<?php
	class NOTICIAS {
	
		var $obj;
		
		// ----------------------------------------------------------------------------------- //
		function principal() {
			$this -> obj -> inicializar('noticias', 'Noticias');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			$noticias	= array();
			$query		= "SELECT *, DATE_FORMAT(`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_noticias` ORDER BY `ultima_actualizacion_fecha` DESC LIMIT 0,30";
			$res		= mysql_query($query);
			
			while ($r = mysql_fetch_array($res)) {
				$id	= $r['id'];
				$ti	= $r['titulo'];
				$co	= $r['contenido'];
				$ui	= $r['id_usuario'];
				$fe	= $r['ultima_actualizacion_fecha'];
				
				$em	= $this -> obj -> sesion -> correo($ui);
				$us	= $this -> obj -> sesion -> nombre($ui, '[n1.] [a1]');
				$u2	= $this -> obj -> sesion -> nombre($ui, '[n] [a]');
				//$fe	= date('Y/m/d ', $fe).str_pad(date('G', $fe), 2, '0', 0).date(':i', $fe);
				
				$noticias[] = array('id' => $id, 'titulo' => $ti, 'contenido' => $co, 'email' => $em, 'usuarioc' => $u2, 'usuario' => $us, 'fecha' => $fe);
			}			
			
			$this -> asignar('noticias', $noticias);
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}

		// ----------------------------------------------------------------------------------- //
		function agregar() {
			$this -> obj -> inicializar('noticias-agregar', 'Agregar Noticia');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function procesarAgregar() {
			$tit	= trim($_POST['titulo']);
			$con	= trim(str_replace("'", "\'", $_POST['contenido']));
			$uid	= $_SESSION['uid'];
			$eti  	= trim($_POST['etiquetas']);
			$fec	= time();
			$tags_a	= ""; 
			
			$query	= "INSERT INTO `aRecurso_noticias`(`titulo`, `contenido`, `id_usuario`) VALUES('$tit', '$con', '$uid')";
			$res	= mysql_query($query); 
			$id 	= mysql_insert_id();
			
			//************
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
					
						//guardamos el id de la tag
						$tags_a.=$id_tag.",";
					
						//verificar que no se dupliquen las etiquetas en el diccionario 
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '5' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','5','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//Termina if tag = empty
				}
			}
			//************

			$summary = htmlspecialchars($con);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=noticias');

			$newentry = utf8_encode('<entry>'.
						'<title>'.$tit.' (Compartido)</title>'.
						'<link rel="alternate" type="text/html" href="'.$newURL.'" />'.
						'<issued>'.date('Y-m-d\TH:i:s-07:00').'</issued>'.
						'<modified>'.date('Y-m-d\TH:i:s-07:00').'</modified>'.
						'<author>'.
							'<name>'.($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]')).'</name>'.
						'</author>'.
						'<id>'.$id.'/'.time().'</id>'.
						'<summary type="text/html" mode="escaped">'.$summary.'</summary>'.
						'</entry>');
			
			if (!mysql_error()) {
				rss::newEntry($newentry);
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha compartido la nota",$tit,$newURL,$tags_a,"NOTICIA");
				$_SESSION['mensaje']	= "La noticia &laquo;$tit&raquo; se ha agregado correctamente.";
				header("Location: .?p=noticias");
			}
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function editar() {
			$this -> obj -> inicializar('noticias-editar', 'Editar Noticia');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			$id		= $_GET['id'];
			$query	= "SELECT * FROM `aRecurso_noticias` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			
			$this -> asignar('titulon', $r['titulo']);
			$this -> asignar('contenido', $r['contenido']);
			$this -> asignar('id', $id);
			
			$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='".$id."' AND `tipo_recurso`='5'";
			$res	= mysql_query($query);
			
			$etiqueta="";
			while ($r = mysql_fetch_array($res)) {
				if(getTagName($r['id_etiqueta'])!="")
					$etiqueta.=getTagName($r['id_etiqueta']).",";
			}
			$this -> asignar('etiquetas', $etiqueta);
			
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		
		
		// ----------------------------------------------------------------------------------- //
		function procesarEditar() {
			$id	= $_POST['id'];
			$ti	= trim($_POST['titulo']);
			$co	= str_replace("'", "\'", trim($_POST['contenido']));
			$uid	= $_SESSION['uid'];
			$fec	= time();
			
			$query	= "UPDATE `aRecurso_noticias` SET `titulo`='$ti', `contenido`='$co' WHERE `id`='$id'";
			$res	= mysql_query($query);
			
			//************
			$query	= "DELETE FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$id' AND `tipo_recurso`='5'";
			$res	= mysql_query($query);	echo mysql_error();
			//************
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
						
						//guardamos el id de la tag
						$tags_a.=$id_tag.",";
					
						//verificar que no se dupliquen las etiquetas en el diccionario 
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '5' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','5','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//termina validar if tag = nill
				}
			}
			//************
			
			$summary = htmlspecialchars($co);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=noticias');

			$newentry = utf8_encode('<entry>'.
						'<title>'.$ti.' (Modificado)</title>'.
						'<link rel="alternate" type="text/html" href="'.$newURL.'" />'.
						'<issued>'.date('Y-m-d\TH:i:s-07:00').'</issued>'.
						'<modified>'.date('Y-m-d\TH:i:s-07:00').'</modified>'.
						'<author>'.
							'<name>'.($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]')).'</name>'.
						'</author>'.
						'<id>'.$id.'/'.time().'</id>'.
						'<summary type="text/html" mode="escaped">'.$summary.'</summary>'.
						'</entry>');
			
			if (!mysql_error()) {
				rss::newEntry($newentry);
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha actualizado la nota",$ti,$newURL,$tags_a,"NOTICIA");
				$_SESSION['mensaje']	= "La noticia &laquo;$ti&raquo; se ha actualizado correctamente.".$_POST['etiquetas'];
				header("Location: .?p=noticias");
			}
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function eliminar() {
			$id		= $_GET['id'];
			$query	= "SELECT * FROM `aRecurso_noticias` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$ti		= mysql_result($res, 0, 'titulo');
			
			$query	= "DELETE FROM `aRecurso_noticias` WHERE `id`='$id'";
			$res	= mysql_query($query);
			
			$query	= "DELETE FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$id'";
			$res	= mysql_query($query);	echo mysql_error();
			
			$_SESSION['mensaje']	= "La noticia &laquo;$ti&raquo; se ha eliminado.";
			header("Location: .?p=noticias");
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function exponer() {
			$mensaje		= @$_SESSION['mensaje'];
			$mensajeError	= @$_SESSION['mensajeError'];
			
			if ($mensaje)		$this -> asignar('mensaje', $mensaje);
			if ($mensajeError)	$this -> asignar('mensajeError', $mensajeError);
			
			$_SESSION['mensaje']		= '';
			$_SESSION['mensajeError']	= '';
		}
		// ----------------------------------------------------------------------------------- //
		function NOTICIAS($obj) {
			$this -> obj		= $obj;
		}
		
		// ----------------------------------------------------------------------------------- //
		function asignar($cat, $val) {
			$this -> obj -> machote -> assign($cat, $val);
		}	
	}
?>