<?php

	class VINCULOS {
	
		var $obj, $carpeta;
	
		// ----------------------------------------------------------------------------------- //
		function VINCULOS($obj) {
			$this -> obj		= $obj;
			$this -> carpeta	= @intval($_GET['c']);
		}
		
		// ----------------------------------------------------------------------------------- //
		function asignar($cat, $val) {
			$this -> obj -> machote -> assign($cat, $val);
		}
		
		// ----------------------------------------------------------------------------------- //
		function ubicacion() {
			$c	= $this -> carpeta;
			if (!$c) return '<strong><a href="?p=vinculos&c='.$c.'">V&iacute;nculos</a></strong>';
			return '...';
		}

		// ----------------------------------------------------------------------------------- //
		function ruta($c) {
			$cy	= $c;
			if (!$c) return '<strong><a href="?p=vinculos&c='.$c.'">V&iacute;nculos</a></strong>';
			
			$query	= "SELECT * FROM `kControl_etiquetas` WHERE `id`='".$c."'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			$c		= $r['nombre'];
			
			return ($c=="")?"":"<a>".$c."</a>";
			
		}

		// ----------------------------------------------------------------------------------- //
		function padre($c) {
			if (!$c) return '0';
			return 0;//$c;
		}
		
		// ----------------------------------------------------------------------------------- //
		function exponer() {
			$mensaje		= @$_SESSION['mensaje'];
			$mensajeError	= @$_SESSION['mensajeError'];
			
			if ($mensaje)		$this -> asignar('mensaje', $mensaje);
			if ($mensajeError)	$this -> asignar('mensajeError', $mensajeError);
			
			$_SESSION['mensaje']		= '';
			$_SESSION['mensajeError']	= '';
			
			if($this -> carpeta != "Vínculos"){
				$this -> asignar('ruta', $this -> ruta($this -> carpeta));
				$this -> asignar('ubicacion', $this -> ruta($this -> carpeta));
			}
			
			$this -> asignar('carpeta', $this -> carpeta);
			if ($this -> carpeta)
				$this -> asignar('padre', $this -> padre($this -> carpeta));
		}
		
		// ----------------------------------------------------------------------------------- //
		function subirArticulo() {
			$this -> obj -> inicializar('vinculos-subir', 'Subir Software');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			$this -> asignar('cid', $_GET['c']);
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function principal() {
			$this -> obj -> inicializar('vinculos', 'vinculos');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			// carpetas
			$c			= $this -> carpeta;
			$carpetas	= array();
			
			if(!$c){
				$query		= "SELECT DISTINCT `t1`.* FROM `kControl_etiquetas` AS `t1`, `kControl_diccionario_etiquetas` AS `t2` WHERE `t1`.`id`=`t2`.`id_etiqueta` AND `t2`.`tipo_recurso`='4' ORDER BY nombre";
				$res		= mysql_query($query);
				
				while ($r = mysql_fetch_array($res)) {
					$id	= $r['id'];
					$no	= $r['nombre'];
			
					$carpetas[]	= array('id' => $id, 'nombre' => $no, 'descripcion' => "", 'email' => "", 'usuario' => "", 'fecha' => "", 'usuarioc' => "");
				}
			}

			$this -> asignar('carpetas', $carpetas);
			
			// articulos
			$c			= $this -> carpeta;
			$software	= array();
			if(!$c){
				$query		= "SELECT `t1`.*, DATE_FORMAT(`t1`.`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_vinculos` AS `t1` LEFT JOIN `kControl_diccionario_etiquetas` AS `t3` ON `t3`.`id_recurso` = `t1`.`id` AND `t3`.`tipo_recurso`='4' WHERE `t3`.`id_recurso` IS NULL;";
			}else{
				$query		= "SELECT `t1`.*, DATE_FORMAT(`t1`.`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_vinculos` AS `t1`, `kControl_diccionario_etiquetas` AS `t3` WHERE `t1`.`id` = `t3`.`id_recurso` AND `t3`.`tipo_recurso`='4' AND `t3`.`id_etiqueta`='$c' ORDER BY `t1`.`url`;";		
			}
		
			$res		= mysql_query($query);
			
			while ($r = mysql_fetch_array($res)) {
				$id	= $r['id'];
				$ur	= $r['url'];
				
				$uc	= (strlen($ur) > 45)? trim(substr($ur, 0, 45)).'...': $ur;
				
				$de	= $r['descripcion'];
				$u2	= $this -> obj -> sesion -> nombre($r['id_usuario'], '[n] [a]');
				$us	= $this -> obj -> sesion -> nombre($r['id_usuario'], '[n1.] [a1]');
				$em	= $this -> obj -> sesion -> correo($r['id_usuario']);
				$fe	= $r['ultima_actualizacion_fecha'];
				
				$software[] = array('id' => $id, 'usuario' => $us, 'usuarioc' => $u2, 'email' => $em, 'fecha' => $fe, 'url' => $ur, 'urlc' => $uc, 'descripcion' => $de);
			}
			
			$this -> asignar('software', $software);
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			
			if($this -> carpeta != "Vínculos")
				tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
				
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function formatoAutores($s) {
			$r	= '';
			$s	= trim($s);
			$s	= (substr($s, -1) == ";")? substr($s, 0, -1): $s;
			$s	= explode(';', $s);
			
			if (count($s) == 1) {
				$s	= $s[0];
				$s	= explode(' ', $s);
				$s	= $s[count($s) - 1];
				$r	= $s;
			}
			
			if (count($s) == 2) {
				foreach ($s as $v) {
					$v	= explode(' ', $v);
					$r	.= ' & '.$v[count($v) - 1];
				}
				$r	= substr($r, 2);
			}
			
			if (count($s) > 2) {
				$s	= $s[0];
				$s	= explode(' ', $s);
				$s	= $s[count($s) - 1];
				$s	= $s." <em>et al.</em>";
				$r	= $s;
			}
			
			return $r;
		}
		
		// ----------------------------------------------------------------------------------- //
		function procesarSubirArticulo() {
			$carp	= @$_POST['carpeta'];
			
			$url	= @$_POST['url'];
			$desc	= @$_POST['descripcion'];
			$uid	= $_SESSION['uid'];
			
			$url	= trim($url);
			if (strtolower(substr($url, 0, 7)) != 'http://')
				$url	= "http://$url";
			
			$query	= "INSERT INTO `aRecurso_vinculos`(`url`, `descripcion`, `id_usuario`) VALUES(".
			          "'$url', '$desc', '$uid')";
			$res	= mysql_query($query);	echo mysql_error(); $id = mysql_insert_id();
			
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
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '4' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
				
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','4','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//termina validar if tag = nill
				}
			}
			//************

			$url	= (strlen($url) > 45)? trim(substr($url, 0, 45)).'...': $url;
			
			$summary = htmlspecialchars($desc);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=vinculos&id='.$id);

			$newentry = utf8_encode('<entry>'.
						'<title>'.$url.' (Compartido)</title>'.
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
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha compartido el v&iacute;nculo",$summary,$newURL,$tags_a,"V&Iacute;NCULO");
				$_SESSION['mensaje']	= "El v&iacute;nculo &laquo;$url&raquo; se ha agregado exitosamente.";
				header("Location: ?p=vinculos&c=$carp");
			}
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function procesarEditarArticulo() {
			$id		= $_POST['id'];
			$url	= @$_POST['url'];
			$resu	= @$_POST['descripcion'];
			
			$url	= trim($url);
			if (strtolower(substr($url, 0, 7)) != 'http://')
				$url	= "http://$url";
			
			$query	= "UPDATE `aRecurso_vinculos` SET `url`='$url', `descripcion`='$resu' WHERE `id`='$id'";
			$res	= mysql_query($query);	echo mysql_error();
			
			$url	= (strlen($url) > 45)? trim(substr($url, 0, 45)).'...': $url;

			//************
			$query	= "DELETE FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$id' AND `tipo_recurso`='1'";
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
					
						//verificar que no se dupliquen las etiquetas en el diccionario 
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '4' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','4','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//termina validar if tag = nill
				}
			}
			//************
			
			$summary = htmlspecialchars($desc);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=vinculos&id='.$id);

			$newentry = utf8_encode('<entry>'.
						'<title>'.$url.' (Modificado)</title>'.
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
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha actualizado el v&iacute;nculo",$desc,$newURL,$tags_a,"V&Iacute;NCULO");
				$_SESSION['mensaje']	= "El v&iacute;nculo &laquo;$url&raquo; se ha actualizado con &eacute;xito.";
				header("Location: ?p=vinculos&c=$carp");
			}
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function editar() {
			$this -> obj -> inicializar('vinculos-editar', 'Editar Software');
			$aid	= $_GET['id'];
			
			$query	= "SELECT * FROM `aRecurso_vinculos` WHERE `id`='$aid'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			
			$url	= $r['url'];			
			$car	= "carpeta";//$r['carpeta'];
			$des	= $r['descripcion'];
			
			$this -> asignar('id',		$aid);
			if ($url)	$this -> asignar('url',		$url);
			if ($des)	$this -> asignar('descripcion', $des);

			//*******
			$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$aid' AND `tipo_recurso`='4' ";
			$res	= mysql_query($query);
			
			$etiqueta="";
			while ($r = mysql_fetch_array($res)) {
				if(getTagName($r['id_etiqueta'])!="")
					$etiqueta.=getTagName($r['id_etiqueta']).",";
			}
			$this -> asignar('etiquetas', $etiqueta);
			//*********
			
			$this -> carpeta	= $car;		
			
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
		function ejecutar($query) {
			$res	= mysql_query($query);	echo mysql_error();
		}

		// ----------------------------------------------------------------------------------- //
		function eliminar() {
		
			$_SESSION['mensaje'] = $_SESSION['mensajeError'] = '';
		
			$archs	= array();
			foreach ($_POST as $k => $v)
				if (substr($k, 0, 1) == 'a') {
					$id	= substr($k, 1);
					
					$query	= "SELECT * FROM `aRecurso_vinculos` WHERE `id`='$id'";
					$res	= mysql_query($query);
					$nomb	= mysql_result($res, 0, 'url');
					
					$nomb		= (strlen($nomb) > 45)? trim(substr($nomb, 0, 45)).'...': $nomb;
					$archs[]	= $nomb;
					
					$this -> ejecutar("DELETE FROM `aRecurso_vinculos` WHERE `id`='$id'");
					$this -> ejecutar("DELETE FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$id' AND `tipo_recurso`='4'");
				}
			
			if (count($archs) > 0)	
				if (count($archs) == 1)
					$_SESSION['mensaje'] .= 'El v&iacute;nculo &laquo;'.implode(', ', $archs).'&raquo; ha sido eliminado. ';
				else $_SESSION['mensaje'] .= 'Los v&iacute;nculos &laquo;'.implode(', ', $archs).'&raquo; han sido eliminados. ';
				
			$carps		= array();
			$nocarps	= array();
			foreach ($_POST as $k => $v)
				if (substr($k, 0, 1) == 'c') {
					$id		= substr($k, 1);
					$this -> ejecutar("DELETE FROM carpetas WHERE id='$id'");
				}

			header("Location: .?p=vinculos&c=".$_POST['regresar']);
			exit();
		}
	}
?>