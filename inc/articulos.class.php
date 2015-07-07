<?php

	class ARTICULOS {
	
		var $obj, $carpeta;
	
		// ----------------------------------------------------------------------------------- //
		function ARTICULOS($obj) {
			$this -> obj		= $obj;
			$this -> carpeta	= @intval($_GET['c']);
		}
		
		// ----------------------------------------------------------------------------------- //
		function asignar($cat, $val) {
			$val = str_replace('’', "'", $val);
			$this -> obj -> machote -> assign($cat, $val);
		}
		
		// ----------------------------------------------------------------------------------- //
		function ubicacion() {
			$c	= $this -> carpeta;
			if (!$c) return '<strong><a href="?p=articulos&c='.$c.'">Art&iacute;culos</a></strong>';
			return '';
		}

		// ----------------------------------------------------------------------------------- //
		function ruta($c) {
			$cy	= $c;
			if (!$c) return '<strong><a href="?p=articulos&c='.$c.'">Art&iacute;culos</a></strong>';
			
			$query	= "SELECT * FROM `kControl_etiquetas` WHERE `id`='".$c."'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			$c		= $r['nombre'];
			
			return ($c=="")?"":"<a>".$c."</a>";
			
		}

		// ----------------------------------------------------------------------------------- //
		function padre($c) {
			if (!$c) return '0';
			return 0;
		}
		
		// ----------------------------------------------------------------------------------- //
		function exponer() {
			$mensaje		= @$_SESSION['mensaje'];
			$mensajeError	= @$_SESSION['mensajeError'];
			
			if ($mensaje)		$this -> asignar('mensaje', $mensaje);
			if ($mensajeError)	$this -> asignar('mensajeError', $mensajeError);
			
			$_SESSION['mensaje']		= '';
			$_SESSION['mensajeError']	= '';
			
			$this -> asignar('carpeta', $this -> carpeta);
			
			if($this -> carpeta != "Artículos"){
				$this -> asignar('ruta', $this -> ruta($this -> carpeta));
				$this -> asignar('ubicacion', $this -> ruta($this -> carpeta));
			}
				
			if ($this -> carpeta)
				$this -> asignar('padre', $this -> padre($this -> carpeta));
			
			
		}
		
		// ----------------------------------------------------------------------------------- //
		function subirArticulo() {
			$this -> obj -> inicializar('articulos-subir', 'Subir Art&iacute;culo');
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
			$this -> obj -> inicializar('articulos', 'Artículos');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			// carpetas
			$c			= $this -> carpeta;
			$carpetas	= array();
			if(!$c){
				$query		= "SELECT DISTINCT `t1`.* FROM `kControl_etiquetas` AS `t1`, `kControl_diccionario_etiquetas` AS `t2` WHERE `t1`.`id`=`t2`.`id_etiqueta` AND `t2`.`tipo_recurso`='1' ORDER BY nombre";
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
			$articulos	= array();
			if(!$c){
				$query		= "SELECT `t1`.*, DATE_FORMAT(`t1`.`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_articulos` AS `t1` LEFT JOIN `aRecurso_archivos` AS `t2` ON `t2`.`id`=`t1`.`id_archivo` LEFT JOIN `kControl_diccionario_etiquetas` AS `t3` ON `t3`.`id_recurso` = `t1`.`id` AND `t3`.`tipo_recurso`='1' WHERE `t3`.`id_recurso` IS NULL;";
			}else{
				$query		= "SELECT `t1`.*, DATE_FORMAT(`t1`.`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_articulos` AS `t1`, `aRecurso_archivos` AS `t2` , `kControl_diccionario_etiquetas` AS `t3` WHERE `t1`.`id_archivo` = `t2`.`id` AND `t1`.`id` = `t3`.`id_recurso` AND `t3`.`tipo_recurso`='1' AND `t3`.`id_etiqueta`='$c' ORDER BY `t1`.`titulo`;";		
			}

			$res		= mysql_query($query);
						
			while ($r = mysql_fetch_array($res)) {
				$id	= $r['id'];
				$no = $this -> obj -> archivos -> nombre($r['id_archivo']);
				$ic	= $this -> obj -> archivos -> tipo($no);
				$ti	= $r['titulo'];
				$au	= $this -> formatoAutores($r['autores']);
				$a2	= $r['autores'];
				$u2	= $this -> obj -> sesion -> nombre($r['id_usuario'], '[n] [a]');
				$us	= $this -> obj -> sesion -> nombre($r['id_usuario'], '[n1.] [a1]');
				$em	= $this -> obj -> sesion -> correo($r['id_usuario']);
				$fe	= $r['ultima_actualizacion_fecha'];
				
				$articulos[] = array('id' => $id, 'icono' => $ic, 'nombre' => $no, 'titulo' => $ti, 'autores' => $au, 'autoresc' => $a2, 'usuario' => $us, 'usuarioc' => $u2, 'email' => $em, 'fecha' => $fe);
			}
			
			$this -> asignar('articulos', $articulos);
			
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			
			if($this -> carpeta != "Artículos")
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
				$auto	= @$_POST['autores'];
				$titu	= @$_POST['titulo'];
				$anno	= @$_POST['anno'];
				$revi	= @str_replace("'", "\'", $_POST['revista']);
				$luga	= @$_POST['lugar'];
				$volu	= @$_POST['volumen'];
				$nume	= @$_POST['numero'];
				$pagi	= @$_POST['paginas'];
				$url	= @$_POST['url'];
				$resu	= @str_replace("'", "\'", $_POST['resumen']);
				$uid	= $_SESSION['uid'];
		
				$query	= "INSERT INTO `aRecurso_articulos`(`id_archivo`, `isbn_diez`, `isbn_trece`, `issn`, `titulo`, `autores`, `anno`, `revista`, `lugar`, `volumen`, `numero`, `paginas`, `url`, `id_usuario`, `resumen`) VALUES(
				          '$arch', 'isbn', 'isbn', 'issn', '$titu', '$auto', '$anno', '$revi', '$luga', '$volu', '$nume', '$pagi', '$url', '$uid', '$resu')";
				$res	= mysql_query($query);	echo mysql_error(); $id = mysql_insert_id();
				
				$summary = htmlspecialchars(
				'<table border="0" cellpadding="0" cellspacing="0">'.
					'<tr>'.
						'<td width="150" align="right" valign="top">'.
							'<img src="http://numenor.cicese.mx/repositorio/tmp/vp/'.$arch.'.jpg" style="border: 1px solid gray; border-right-width: 2px; border-bottom-width: 2px;"/>'.
						'</td>'.
						'<td width="32">&nbsp;</td>'.
						'<td valign="top" align="left">'.
							'<table border="0" cellpadding="0" cellspacing="0">'.
							'<tr>'.
								'<th align="right" nowrap>Archivo&nbsp;&nbsp;</th>'.
								'<td><img src="http://numenor.cicese.mx/repositorio/tpl/simple/img/icono-'.$this -> obj -> archivos -> tipo($nomb).'.gif" align="absmiddle" /> <strong>'.$nomb.'</strong> ('.$this -> obj -> archivos -> tamano($arch).') </td>'.
							'</tr>'.
							'<tr>'.
								'<th align="right" nowrap>Autores&nbsp;&nbsp;</th>'.
								'<td>'.($auto).'</td>'.
							'</tr>'.
							'<tr>'.
								'<th align="right" nowrap>T&iuml;tulo&nbsp;&nbsp;</th>'.
								'<td>'.($titu).'</td>'.
							'</tr>'.
							'<tr>'.
								'<th align="right" nowrap>A&ntilde;o&nbsp;&nbsp;</th>'.
								'<td>'.$anno.'</td>'.
							'</tr>'.
							(($_POST['revista'])? '<tr>'.
								'<th align="right" nowrap>Revista / Conferencia&nbsp;&nbsp;</th>'.
								'<td>'.$_POST['revista'].'</td>'.
							'</tr>': '').
							(($luga)? '<tr>'.
								'<th align="right" nowrap>Lugar y Fecha&nbsp;&nbsp;</th>'.
								'<td>'.$luga.'</td>'.
							'</tr>': '').
							(($volu)? '<tr>'.
								'<th align="right" nowrap>Vol&uacute;men&nbsp;&nbsp;</th>'.
								'<td>'.$volu.'</td>'.
							'</tr>': '').
							(($nume)? '<tr>'.
								'<th align="right" nowrap>N&uacute;mero&nbsp;&nbsp;</th>'.
								'<td>'.$nume.'</td>'.
							'</tr>': '').
							(($pagi)? '<tr>'.
								'<th align="right" nowrap>P&aacute;ginas&nbsp;&nbsp;</th>'.
								'<td>'.str_replace('--', '&ndash;', $pagi).'</td>'.
							'</tr>': '').
							(($url)? '<tr>'.
								'<th align="right" nowrap>URL&nbsp;&nbsp;</th>'.
								'<td><a href="'.$url.'" target="_blank">'.$url.'</a></td>'.
							'</tr>': '').
							'<tr>'.
								'<th align="right" nowrap>Modificado por&nbsp;&nbsp;</th>'.
								'<td>'.($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]')).' el '.date('d/m/Y').' a las '.date('G:i').'</td>'.
							'</tr>'.
							(($resu)? '<tr><td colspan="2"><br><em>Resumen: </em>'.(str_replace("\'", "'", $resu)).'</td></tr>': '').
							'</table>'.
					'</td><td width="32">&nbsp;</td></tr>'.
				'</table>'
			);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=articulos-ver&id='.$id.'&t='.time());

			$newentry = utf8_encode('<entry>'.
						'<title>'.$titu.' (Compartido)</title>'.
						'<link rel="alternate" type="text/html" href="'.$newURL.'" />'.
						'<issued>'.date('Y-m-d\TH:i:s-07:00').'</issued>'.
						'<modified>'.date('Y-m-d\TH:i:s-07:00').'</modified>'.
						'<author><name>'.trim(htmlspecialchars(strip_tags($this -> formatoAutores($auto)))).'</name></author>'.
						'<id>'.$id.'/'.time().'</id>'.
						'<summary type="text/html" mode="escaped">'.$summary.'</summary>'.
					'</entry>');
				
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
							$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '1' LIMIT 1";
							$res	= mysql_query($query);	echo mysql_error();
							$r		= mysql_fetch_array($res);
							$n		= mysql_num_rows($res);
					
							//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
							if($n==0){
								//guardar los datos en el diccionario de etiquetas
								$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','1','".$id_tag."')";
								$res	= mysql_query($query);	echo mysql_error();			
							}
						}//termina validar if tag = nill
					}
				}
				//************

				$articulos_anuncio .= preg_replace('/^[0-9]+_/', '', basename($filename)) . ',';
			}
			
			if (!mysql_error()) {
				rss::newEntry($newentry);
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha compartido el art&iacute;culo",$articulos_anuncio,$newURL,$tags_a,"ART&Iacute;CULO");
				$_SESSION['mensaje']	= "Los art&iacute;culos &laquo;".$articulos_anuncio."&raquo; se han subido con &eacute;xito.";
				header("Location: ?p=articulos&c=$carp");
			}
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function procesarEditarArticulo(){
			$id		= $_POST['id'];
			$query	= "SELECT `id_archivo` FROM `aRecurso_articulos` WHERE `id`='$id'";
			$res	= mysql_query($query);	echo mysql_error();
			$arch	= mysql_result($res, 0, 'id_archivo');
			
			$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$arch'";
			$res	= mysql_query($query);
			$nomb	= mysql_result($res, 0, 'nombre');

			
			if ($_FILES['archivo']['error'] == 0) {
				$nomb	= $_FILES['archivo']['name'];
					
				$query	= "DELETE FROM `aRecurso_archivos` WHERE `id`='$arch'";
				$res	= mysql_query($query);	echo mysql_error();
				
				@unlink('pub/'.$arch);
				@unlink("tmp/vp/$arch.jpg");
				
				$arch	= $this -> obj -> archivos -> subir($_FILES['archivo']);
				
				$query	= "UPDATE `aRecurso_articulos` SET `id_archivo`='$arch' WHERE `id`='$id'";
				$res	= mysql_query($query);	echo mysql_error();
			}

			$auto	= @$_POST['autores'];
			$titu	= @$_POST['titulo'];
			$anno	= @$_POST['anno'];
			$revi	= str_replace("'", "\'", @$_POST['revista']);
			$luga	= @$_POST['lugar'];
			$volu	= @$_POST['volumen'];
			$nume	= @$_POST['numero'];
			$pagi	= @$_POST['paginas'];
			$url	= @$_POST['url'];
			$resu	= @str_replace("'", "\'", $_POST['resumen']);
			$uid	= $_SESSION['uid'];
			$eti  	= @$_POST['etiquetas'];
			
			$query	= "UPDATE `aRecurso_articulos` SET `isbn_diez`='isbn10', `isbn_trece`='isbn13', `issn`='isbn', titulo='$titu', autores='$auto', anno='$anno', revista='$revi', lugar='$luga', volumen='$volu', numero='$nume', paginas='$pagi', url='$url', resumen='$resu' WHERE `id`='$id'";
			$res	= mysql_query($query);	echo mysql_error();

			$summary = htmlspecialchars(
				'<table border="0" cellpadding="0" cellspacing="0">'.
					'<tr>'.
						'<td width="150" align="right" valign="top">'.
							'<img src="http://numenor.cicese.mx/repositorio/tmp/vp/'.$arch.'.jpg" style="border: 1px solid gray; border-right-width: 2px; border-bottom-width: 2px;"/>'.
						'</td>'.
						'<td width="32">&nbsp;</td>'.
						'<td valign="top" align="left">'.
							'<table border="0" cellpadding="0" cellspacing="0">'.
							'<tr>'.
								'<th align="right" nowrap>Archivo&nbsp;&nbsp;</th>'.
								'<td><img src="http://numenor.cicese.mx/repositorio/tpl/simple/img/icono-'.$this -> obj -> archivos -> tipo($nomb).'.gif" align="absmiddle" /> <strong>'.$nomb.'</strong> ('.$this -> obj -> archivos -> tamano($arch).') </td>'.
							'</tr>'.
							'<tr>'.
								'<th align="right" nowrap>Autores&nbsp;&nbsp;</th>'.
								'<td>'.($auto).'</td>'.
							'</tr>'.
							'<tr>'.
								'<th align="right" nowrap>T&iuml;tulo&nbsp;&nbsp;</th>'.
								'<td>'.($titu).'</td>'.
							'</tr>'.
							'<tr>'.
								'<th align="right" nowrap>A&ntilde;o&nbsp;&nbsp;</th>'.
								'<td>'.$anno.'</td>'.
							'</tr>'.
							(($_POST['revista'])? '<tr>'.
								'<th align="right" nowrap>Revista / Conferencia&nbsp;&nbsp;</th>'.
								'<td>'.$_POST['revista'].'</td>'.
							'</tr>': '').
							(($luga)? '<tr>'.
								'<th align="right" nowrap>Lugar y Fecha&nbsp;&nbsp;</th>'.
								'<td>'.$luga.'</td>'.
							'</tr>': '').
							(($volu)? '<tr>'.
								'<th align="right" nowrap>Vol&uacute;men&nbsp;&nbsp;</th>'.
								'<td>'.$volu.'</td>'.
							'</tr>': '').
							(($nume)? '<tr>'.
								'<th align="right" nowrap>N&uacute;mero&nbsp;&nbsp;</th>'.
								'<td>'.$nume.'</td>'.
							'</tr>': '').
							(($pagi)? '<tr>'.
								'<th align="right" nowrap>P&aacute;ginas&nbsp;&nbsp;</th>'.
								'<td>'.str_replace('--', '&ndash;', $pagi).'</td>'.
							'</tr>': '').
							(($url)? '<tr>'.
								'<th align="right" nowrap>URL&nbsp;&nbsp;</th>'.
								'<td><a href="'.$url.'" target="_blank">'.$url.'</a></td>'.
							'</tr>': '').
							'<tr>'.
								'<th align="right" nowrap>Modificado por&nbsp;&nbsp;</th>'.
								'<td>'.($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]')).' el '.date('d/m/Y').' a las '.date('G:i').'</td>'.
							'</tr>'.
							(($resu)? '<tr><td colspan="2"><br><em>Resumen: </em>'.(str_replace("\'", "'", $resu)).'</td></tr>': '').
							'</table>'.
					'</td><td width="32">&nbsp;</td></tr>'.
				'</table>'
			);

			$newURL = htmlspecialchars('http://numenor.cicese.mx/repositorio/?p=articulos-ver&id='.$id.'&t='.time());

			$newentry = utf8_encode('<entry>'.
						'<title>'.$titu.' (Modificado)</title>'.
						'<link rel="alternate" type="text/html" href="'.$newURL.'" />'.
						'<issued>'.date('Y-m-d\TH:i:s-07:00').'</issued>'.
						'<modified>'.date('Y-m-d\TH:i:s-07:00').'</modified>'.
						'<author><name>'.trim(htmlspecialchars(strip_tags($this -> formatoAutores($auto)))).'</name></author>'.
						'<id>'.$id.'/'.time().'</id>'.
						'<summary type="text/html" mode="escaped">'.$summary.'</summary>'.
					'</entry>');

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
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_recurso` LIKE '".$id."' AND `tipo_recurso` LIKE '1' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas` (`id_recurso`,`tipo_recurso`,`id_etiqueta`) VALUES('".$id."','1','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//termina validar if tag = nill
				}
			}
			//************
			
			if (!mysql_error()) {
				rss::newEntry($newentry);
				rss::newEntryMAILTO($this -> obj -> sesion -> nombre($uid,'[n1.] [a1]'),"Se ha actualizado el art&iacute;culo",$titu,$newURL,$tags_a,"ART&Iacute;CULO");	
				$_SESSION['mensaje']	= "El art&iacute;culo &laquo;$nomb&raquo; se ha actualizado con &eacute;xito.";
				header("Location: ?p=articulos&c=$carp");
			}
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function ver() {
			$this -> obj -> inicializar('articulos-ver', 'Ver Art&iacute;culo');
			
			$aid	= $_GET['id'];
			$vid	= $aid;
			
			$query	= "SELECT * FROM `aRecurso_articulos` WHERE `id`='$aid'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			
			$tip	= $this -> obj -> archivos -> tipo($this -> obj -> archivos -> nombre($r['id_archivo']));
			$nom	= $this -> obj -> archivos -> nombre($r['id_archivo']);
			$tam	= $this -> obj -> archivos -> tamano($r['id_archivo']);
			$aut	= $r['autores'];
			$tit	= $r['titulo'];
			$ann	= $r['anno'];
			$rev	= $r['revista'];
			$lug	= $r['lugar'];
			$vol	= $r['volumen'];
			$num	= $r['numero'];
			$pag	= $r['paginas'];
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
			$this -> asignar('autores', $aut);
			$this -> asignar('tituloa',	$tit);
			$this -> asignar('anno',	$ann);
			if ($rev)	$this -> asignar('revista',	$rev);
			if ($lug)	$this -> asignar('lugar',	$lug);
			if ($vol)	$this -> asignar('volumen',	$vol);
			if ($num)	$this -> asignar('numero',	$num);
			if ($pag)	$this -> asignar('paginas',	$pag);
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
			$this -> obj -> inicializar('articulos-editar', 'Editar Art&iacute;culo');
			
			$aid	= $_GET['id'];
			$vid	= $aid;
			
			$query	= "SELECT * FROM `aRecurso_articulos` WHERE `id`='$aid'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			
			$tip	= $this -> obj -> archivos -> tipo($this -> obj -> archivos -> nombre($r['id_archivo']));
			$nom	= $this -> obj -> archivos -> nombre($r['id_archivo']);
			$tam	= $this -> obj -> archivos -> tamano($r['id_archivo']);
			$aut	= $r['autores'];
			$tit	= $r['titulo'];
			$ann	= $r['anno'];
			$rev	= $r['revista'];
			$lug	= $r['lugar'];
			$vol	= $r['volumen'];
			$num	= $r['numero'];
			$pag	= $r['paginas'];
			$url	= $r['url'];			
			$car	= "carpeta";//$r['carpeta'];
			$res	= $r['resumen'];
			
			$this -> asignar('imagen', $this -> obj -> archivos -> vistaPrevia($aid));
			
			$this -> asignar('id',		$aid);
			$this -> asignar('aid',		$vid);
			$this -> asignar('tipo',	$tip);
			$this -> asignar('nombre',	$nom);
			$this -> asignar('tamano',	$tam);
			$this -> asignar('autores', $aut);
			$this -> asignar('tituloa',	$tit);
			$this -> asignar('anno',	$ann);
			if ($rev)	$this -> asignar('revista',	$rev);
			if ($lug)	$this -> asignar('lugar',	$lug);
			if ($vol)	$this -> asignar('volumen',	$vol);
			if ($num)	$this -> asignar('numero',	$num);
			if ($pag)	$this -> asignar('paginas',	$pag);
			if ($url)	$this -> asignar('url',		$url);	
			if ($res)	$this -> asignar('resumen',	$res);
			
			//*******
			$query	= "SELECT * FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$aid' AND `tipo_recurso`='1' ";
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
					
					$query	= "SELECT * FROM `aRecurso_articulos` WHERE `id`='$id'";
					$res	= mysql_query($query);
					$aid	= mysql_result($res, 0, 'id_archivo');
					
					$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$aid'";
					$res	= mysql_query($query);
					$nomb	= mysql_result($res, 0, 'nombre');
					
					$archs[]	= $nomb;
			
					$this -> ejecutar("DELETE FROM `aRecurso_archivos` WHERE `id`='$aid'");
					$this -> ejecutar("DELETE FROM `aRecurso_articulos` WHERE `id`='$id'");
					$this -> ejecutar("DELETE FROM `kControl_diccionario_etiquetas` WHERE `id_recurso`='$id' AND `tipo_recurso`='1'");
					@unlink('pub/'.$aid);
					@unlink("tmp/vp/$aid.jpg");
				}
			
			if (count($archs) > 0)	
				if (count($archs) == 1)
					$_SESSION['mensaje'] .= 'El archivo &laquo;'.implode(', ', $archs).'&raquo; ha sido eliminado. ';
				else $_SESSION['mensaje'] .= 'Los archivos &laquo;'.implode(', ', $archs).'&raquo; han sido eliminados. ';
			
			header("Location: .?p=articulos&c=".$_POST['regresar']);
			exit();
		}
	}
?>