<?php

	class CONFIGURACION {
	
		var $obj, $llaves;
	
		// ----------------------------------------------------------------------------------- //
		function CONFIGURACION($obj) {
			$this -> obj		= $obj;
			$this -> llaves		= array();
		}

		// ----------------------------------------------------------------------------------- //
		function principal() {
			$this -> obj -> inicializar('configuracion', 'Configuraci&oacute;n');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			$id		= $_SESSION['uid'];
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			$nomb	= $r['nombres'];
			$apel	= $r['apellidos'];
			$webs	= $r['website'];
			$noti	= $r['notificar'];
			
			$this -> asignar('nombres', $nomb);
			$this -> asignar('apellidos', $apel);
			$this -> asignar('website', $webs);
			
			//*****Tags
			$query	= "SELECT * FROM `kControl_diccionario_etiquetas_usuarios` WHERE `id_usuario`='$id'";
			$res	= mysql_query($query);
			
			$etiqueta="";
			while ($r = mysql_fetch_array($res)) {
				if(getTagName($r['id_etiqueta'])!="")
					$etiqueta.=getTagName($r['id_etiqueta']).",";
			}
			
			$this -> asignar('etiquetas', $etiqueta);
			//*****Tags
			
			if ($noti) $this -> asignar('notificar', 1);
			
			
			$this -> asignar('sexo', 'm');
			
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function grupos() {
			$this -> obj -> inicializar('configuracion-grupos', 'Grupos de trabajo');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			$id		= $_SESSION['uid'];
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);

			$this -> asignar('idp', $r['id_perfil']);
	
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function perfiles() {
			$this -> obj -> inicializar('configuracion-perfiles', 'Perfiles de usuario');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			$id		= $_SESSION['uid'];
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);

			
			$this -> asignar('idp', $r['id_perfil']);
			
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function reportarVacio($e, $s, $r, $g) {
			if (!trim($e)) {
				$_SESSION['mensajeError']	= (($g == 'm')? 'El': 'La')." $s es obligatori".(($g == 'm')? 'o': 'a').".";
				header("Location: ?p=$r");
				exit();
			}
		}
		
		// ----------------------------------------------------------------------------------- //
		function reportarError($s) {
			$_SESSION['mensajeError'] = $s;
			header("Location: ?p=configuracion");
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function procesarConfiguracion() {
			$id		= $_SESSION['uid'];
			$email	= trim($_POST['email']);
			$passw	= $_POST['password'];
			$pass2	= $_POST['password2'];
			$nombr	= trim($_POST['nombres']);
			$apell	= trim($_POST['apellidos']);
			$notif	= @$_POST['notificar'];
			$sexo	= $_POST['sexo'];
			$websi	= trim($_POST['website']);
			
			if (!$email) $this -> reportarVacio($email, 'Correo Electrónico', 'configuracion', 'm');
			if (!$nombr) $this -> reportarVacio($nombr, 'Nombre', 'configuracion', 'm');
			if (!$apell) $this -> reportarVacio($apell, 'Apellido', 'configuracion', 'm');

			if ($passw != $pass2) $this -> reportarError('Las contraseñas no coinciden.');
			
			if ($_SESSION['email'] != $email) {
				$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `email`='$email'";
				$res	= mysql_query($query);	echo mysql_error();
				$n		= mysql_num_rows($res);
				
				if ($n > 0) $this -> reportarError("Ya hay un usuario registrado con el correo electr&oacute;nico $email.");
			}
			
			if (strtolower(substr($websi, 0, 7)) != 'http://')
				$websi = "http://$websi";
				
			if (!$notif) $notif = 0;
			
			if ($passw) {
				$passw	= crypt($passw, 'crp');
				$query	= "UPDATE `aRecurso_usuarios` SET `email`='$email', `password`='$passw', `nombres`='$nombr', `apellidos`='$apell', `notificar`='$notif', `sexo`='$sexo', `website`='$websi' WHERE `id`='$id'";
			} else $query	= "UPDATE `aRecurso_usuarios` SET `email`='$email', `nombres`='$nombr', `apellidos`='$apell', `notificar`='$notif', `sexo`='$sexo', `website`='$websi' WHERE `id`='$id'";
			$res	= mysql_query($query);	echo mysql_error();
			
			//************
			$query	= "DELETE FROM `kControl_diccionario_etiquetas_usuarios` WHERE `id_usuario`='$id'";
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
						$query	= "SELECT * FROM `kControl_diccionario_etiquetas_usuarios` WHERE `id_etiqueta` LIKE '".$id_tag."' AND `id_usuario` LIKE '".$id."' LIMIT 1";
						$res	= mysql_query($query);	echo mysql_error();
						$r		= mysql_fetch_array($res);
						$n		= mysql_num_rows($res);
					
						//si no hay etiquetas duplicadas, entonces incrementamos las etiquetas del grupo de trabajo
						if($n==0){
							//guardar los datos en el diccionario de etiquetas
							$query	= "INSERT INTO `kControl_diccionario_etiquetas_usuarios` (`id_usuario`,`id_etiqueta`) VALUES('".$id."','".$id_tag."')";
							$res	= mysql_query($query);	echo mysql_error();			
						}
					}//termina validación if tag = nil
				}
			}
			//************
			
			$_SESSION['nombres']	= $nombr;
			$_SESSION['apellidos']	= $apell;
			$_SESSION['email']		= $email;
			$_SESSION['website']	= $websi;
			$_SESSION['sexo']		= $sexo;
			
			$_SESSION['mensaje']	= "Sus datos han sido correctamente almacenados.";
			header("Location: .?p=configuracion");
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
		function asignar($cat, $val) {
			$this -> obj -> machote -> assign($cat, $val);
		}
	}
?>