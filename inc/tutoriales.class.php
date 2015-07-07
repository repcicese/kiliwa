<?php

	class TUTORIALES {
	
		var $obj, $carpeta;
	
		// ----------------------------------------------------------------------------------- //
		function TUTORIALES($obj) {
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
			if (!$c) return '<strong><a href="?p=tutoriales&c='.$c.'">Tutoriales</a></strong>';
			return '...';
		}

		// ----------------------------------------------------------------------------------- //
		function ruta($c) {
			$cy	= $c;
			if (!$c) return '<strong><a href="?p=tutoriales&c='.$c.'">Tutoriales</a></strong>';
			
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
			
			if($this -> carpeta != "Tutoriales"){
				$this -> asignar('ruta', $this -> ruta($this -> carpeta));
				$this -> asignar('ubicacion', $this -> ruta($this -> carpeta));
			}
			
			$this -> asignar('carpeta', $this -> carpeta);
			if ($this -> carpeta)
				$this -> asignar('padre', $this -> padre($this -> carpeta));
		}
		
		// ----------------------------------------------------------------------------------- //
		function subirArticulo() {
			$this -> obj -> inicializar('tutoriales-subir', 'Subir Tutorial');
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
		function principal() {
			$this -> obj -> inicializar('tutoriales', 'Tutoriales');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			// carpetas
			$c			= $this -> carpeta;
			$carpetas	= array();
			if(!$c){
				$query		= "SELECT DISTINCT `t1`.* FROM `kControl_etiquetas` AS `t1`, `kControl_diccionario_etiquetas` AS `t2` WHERE `t1`.`id`=`t2`.`id_etiqueta` AND `t2`.`tipo_recurso`='3' ORDER BY nombre";
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
				$query		= "SELECT `t1`.*, DATE_FORMAT(`t1`.`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_tutoriales` AS `t1` LEFT JOIN `aRecurso_archivos` AS `t2` ON `t2`.`id`=`t1`.`id_archivo` LEFT JOIN `kControl_diccionario_etiquetas` AS `t3` ON `t3`.`id_recurso` = `t1`.`id` AND `t3`.`tipo_recurso`='3' WHERE `t3`.`id_recurso` IS NULL;";
			}else{
				$query		= "SELECT `t1`.*, DATE_FORMAT(`t1`.`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_tutoriales` AS `t1`, `aRecurso_archivos` AS `t2` , `kControl_diccionario_etiquetas` AS `t3` WHERE `t1`.`id_archivo` = `t2`.`id` AND `t1`.`id` = `t3`.`id_recurso` AND `t3`.`tipo_recurso`='3' AND `t3`.`id_etiqueta`='$c' ORDER BY `t1`.`titulo`;";		
			}

			$res		= mysql_query($query);
				
			while ($r = mysql_fetch_array($res)) {
				$id	= $r['id'];
				$no = $this -> obj -> archivos -> nombre($r['id_archivo']);
				$ic	= $this -> obj -> archivos -> tipo($no);
				$ti	= $r['titulo'];
				$u2	= $this -> obj -> sesion -> nombre($r['id_usuario'], '[n] [a]');
				$us	= $this -> obj -> sesion -> nombre($r['id_usuario'], '[n1.] [a1]');
				$em	= $this -> obj -> sesion -> correo($r['id_usuario']);
				$fe	= $r['ultima_actualizacion_fecha'];
				
				$software[] = array('id' => $id, 'icono' => $ic, 'nombre' => $no, 'titulo' => $ti, 'usuario' => $us, 'usuarioc' => $u2, 'email' => $em, 'fecha' => $fe);
			}
			
			$this -> asignar('software', $software);
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			
			if($this -> carpeta != "Software")
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
				$uptime = @$_POST['uptime'];

				foreach (glob("tmp/files/" . $uptime . "_*") as $filename) {
			
					$arch	= $this -> obj -> archivos -> subir($filename);
					$this -> obj -> archivos -> vistaPrevia($arch);
					$titu	= @$_POST['titulo'];
					$url	= @$_POST['url'];
					$resu	= @$_POST['resumen'];
					$uid	= $_SESSION['uid'];
					
					$query	= "INSERT INTO `aRecurso_tutoriales`(`id_archivo`, `titulo`, `url`, `id_usuario`, `resumen`) VALUES(".
							  "'$arch', '$titu', '$url', '$uid', '$resu')";
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
								$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '3' LIMIT 1";
								$res	= mysql_query($query);	echo mysql_error();
								$r		= mysql_fetch_array($res);
								$n		= mysql_num_rows($res);
						
								//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
								if($n==0){
									//guardar los datos en el diccionario de etiquetas
									$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','3','".$id_tag."')";
									$res	= mysql_query($query);	echo mysql_error();			
								}
							}//termina validar if tag = nill
						}
					}
					//************

				$tutoriales_anuncio .= preg_replace('/^[0-9]+_/', '', basename($filename)) . ',';	
			}
			
			$summary = htmlspecialchars($resu);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=tutoriales&id='.$id);

			$newentry = utf8_encode('<entry>'.
						'<title>'.$titu.' (Compartido)</title>'.
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
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha compartido el tutorial",$tutoriales_anuncio,$newURL,$tags_a,"TUTORIALES");
				$_SESSION['mensaje']	= "Los tutoriales &laquo;".$tutoriales_anuncio."&raquo; se han subido con &eacute;xito.";
				header("Location: ?p=tutoriales&c=$carp");
			}
			exit();
		}
			
		// ----------------------------------------------------------------------------------- //
		function procesarEditarArticulo() {
			$id		= $_POST['id'];
			$query	= "SELECT `id_archivo` FROM `aRecurso_tutoriales` WHERE `id`='$id'";
			$res	= mysql_query($query);	echo mysql_error();
			$aid	= mysql_result($res, 0, 'id_archivo');
			
			$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$aid'";
			$res	= mysql_query($query);
			$nomb	= mysql_result($res, 0, 'nombre');
			
			if ($_FILES['archivo']['error'] == 0) {
				$nomb	= $_FILES['archivo']['name'];
					
				$query	= "DELETE FROM `aRecurso_archivos` WHERE `id`='$aid'";
				$res	= mysql_query($query);	echo mysql_error();
				
				$arch	= $this -> obj -> archivos -> subir($_FILES['archivo']);
				
				$query	= "UPDATE `aRecurso_tutoriales` SET `id_archivo`='$arch' WHERE `id`='$id'";
				$res	= mysql_query($query);	echo mysql_error();
			}

			$titu	= @$_POST['titulo'];
			$url	= @$_POST['url'];
			$resu	= @$_POST['resumen'];
			
			$query	= "UPDATE `aRecurso_tutoriales` SET `titulo`='$titu', `url`='$url', `resumen`='$resu' WHERE `id`='$id'";
			$res	= mysql_query($query);	echo mysql_error();

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
						
						//guardamos el id de la tag
						$tags_a.=$id_tag.",";
					
						//verificar que no se dupliquen las etiquetas en el diccionario 
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '3' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','3','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//termina validar if tag = nill
				}
				$tutoriales_anuncio .= preg_replace('/^[0-9]+_/', '', basename($filename)) . ',';	
			}
			//************
			
			$summary = htmlspecialchars($resu);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=tutoriales&id='.$id);

			$newentry = utf8_encode('<entry>'.
						'<title>'.$titu.' (Modificado)</title>'.
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
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha actualizado el tutorial",$titu,$newURL,$tags_a,"TUTORIALES");			
				$_SESSION['mensaje']	= "El archivo &laquo;$nomb&raquo; se ha actualizado con &eacute;xito.";
				header("Location: ?p=tutoriales&c=$carp");
			}
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function ver() {
			$this -> obj -> inicializar('tutoriales-ver', 'Ver Tutorial');
			
			$aid	= $_GET['id'];
			$vid	= $aid;
			
			$query	= "SELECT * FROM `aRecurso_tutoriales` WHERE `id`='$aid'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			
			$tip	= $this -> obj -> archivos -> tipo($this -> obj -> archivos -> nombre($r['id_archivo']));
			$nom	= $this -> obj -> archivos -> nombre($r['id_archivo']);
			$tam	= $this -> obj -> archivos -> tamano($r['id_archivo']);
			$tit	= $r['titulo'];
			$url	= $r['url'];			
			$car	= "carpeta";//$r['carpeta'];
			$aid	= $r['id_archivo'];
			$res	= $r['resumen'];
			
			$this -> asignar('imagen', $this -> obj -> archivos -> vistaPrevia($aid));
			
			$this -> asignar('id',		$aid);
			$this -> asignar('aid',		$vid);
			$this -> asignar('tipo',	$tip);
			$this -> asignar('nombre',	$nom);
			$this -> asignar('tamano',	$tam);
			$this -> asignar('tituloa',	$tit);
			if ($url)	$this -> asignar('url',		$url);	
			if ($res)	$this -> asignar('resumen',	$res);
			
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
		function editar() {
			$this -> obj -> inicializar('tutoriales-editar', 'Editar Tutorial');
			
			$aid	= $_GET['id'];
			$vid	= $aid;
			
			$query	= "SELECT * FROM `aRecurso_tutoriales` WHERE `id`='$aid'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			
			$tip	= $this -> obj -> archivos -> tipo($this -> obj -> archivos -> nombre($r['id_archivo']));
			$nom	= $this -> obj -> archivos -> nombre($r['id_archivo']);
			$tam	= $this -> obj -> archivos -> tamano($r['id_archivo']);
			$tit	= $r['titulo'];
			$url	= $r['url'];			
			$car	= "carpeta";//$r['carpeta'];
			$res	= $r['resumen'];
			
			$this -> asignar('id',		$aid);
			$this -> asignar('aid',		$vid);
			$this -> asignar('tipo',	$tip);
			$this -> asignar('nombre',	$nom);
			$this -> asignar('tamano',	$tam);
			$this -> asignar('tituloa',	$tit);
			if ($url)	$this -> asignar('url',		$url);	
			if ($res)	$this -> asignar('resumen',	$res);

			//*******
			$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$aid' AND `tipo_recurso`='3' ";
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
					
					$query	= "SELECT * FROM `aRecurso_tutoriales` WHERE `id`='$id'";
					$res	= mysql_query($query);
					$aid	= mysql_result($res, 0, 'id_archivo');
					
					$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$aid'";
					$res	= mysql_query($query);
					$nomb	= mysql_result($res, 0, 'nombre');
					
					$archs[]	= $nomb;
					
					$this -> ejecutar("DELETE FROM `aRecurso_archivos` WHERE `id`='$aid'");
					$this -> ejecutar("DELETE FROM `aRecurso_tutoriales` WHERE `id`='$id'");
					$this -> ejecutar("DELETE FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$id' AND `tipo_recurso`='3'");
					@unlink('pub/'.$aid);
					@unlink("tmp/vp/$aid.jpg");
				}
			
			if (count($archs) > 0)	
				if (count($archs) == 1)
					$_SESSION['mensaje'] .= 'El archivo &laquo;'.implode(', ', $archs).'&raquo; ha sido eliminado. ';
				else $_SESSION['mensaje'] .= 'Los archivos &laquo;'.implode(', ', $archs).'&raquo; han sido eliminados. ';
			
			header("Location: .?p=tutoriales&c=".$_POST['regresar']);
			exit();
		}
	}
?>