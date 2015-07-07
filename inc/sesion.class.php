<?php
	class SESION {
	
		var $obj;
	
		// ----------------------------------------------------------------------------------- //
		function SESION($obj) {
			$this -> obj	= $obj;
		}
	
		// ----------------------------------------------------------------------------------- //
		function activa() {
			return isset($_SESSION['uid']);
		}
		
		// ----------------------------------------------------------------------------------- //
		function cerrar() {
			session_destroy();
			header("Location: ?p=iniciar-sesion&auto=no");
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function autorizar() {
			$id		= $_GET['id'];
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			$query	= "UPDATE `aRecurso_usuarios` SET `id_perfil`='".$_GET['idp']."', `activo`='1' WHERE `id`='$id'";
			$res	= mysql_query($query);
			
			if (!mysql_error()) {
				$_SESSION['mensaje']	= 'Se ha <strong>concedido</strong> autorizaci&oacute;n al usuario &laquo;'.$r['email'].'&raquo;';
				rss::newUserMAILTO($r['email'],"Autorizaci&oacute;n de usuario",2);
			}
			
			header("Location: .");
			exit();
		}

		// ----------------------------------------------------------------------------------- //
		function negar() {
			$id		= $_GET['id'];
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			$query	= "DELETE FROM `aRecurso_usuarios` WHERE `id`='$id'";
			$res	= mysql_query($query);
			
			if (!mysql_error()) {
				$_SESSION['mensaje']	= 'Se ha <strong>negado</strong> autorizaci&oacute;n al usuario &laquo;'.$r['email'].'&raquo;';
				rss::newUserMAILTO($r['email'],"Autorizaci&oacute;n de usuario",3);
			}
			
			header("Location: .");
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function formulario() {
				
			if (isset($_COOKIE['auto-CICESERep']) && !isset($_GET['auto'])) {
				$uid	= base64_decode($_COOKIE['auto-CICESERep']);
				$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$uid'";
				$res	= mysql_query($query);
				$r		= mysql_fetch_array($res);
			
				$_SESSION['uid']		= $r['id'];
				$_SESSION['nombres']	= $r['nombres'];
				$_SESSION['apellidos']	= $r['apellidos'];
				$_SESSION['email']		= $r['email'];
				$_SESSION['sexo']		= $r['sexo'];
				$_SESSION['website']	= $r['website'];
				$_SESSION['tipo']		= $r['perfil'];
			
				header("Location: ?p=principal");			
				exit();
			}
		
			$this -> obj -> inicializar('sesion-iniciar', 'Iniciar Sesión');
			
			@$email	= $_SESSION['email'];
			@$merro	= $_SESSION['mensajeError'];
			@$mensa	= $_SESSION['mensaje'];
			
			$merro	= ($merro)? '<div class="mensajeError">'.$merro.'</div>': '';
			$mensa	= ($mensa)? '<div class="mensaje">'.$mensa.'</div>': '';
			
			if ($email)	$this -> obj -> machote -> assign('email', $email);
			if ($merro)	$this -> obj -> machote -> assign('mensajeError', $merro);
			if ($mensa)	$this -> obj -> machote -> assign('mensaje', $mensa);
			
			$_SESSION['mensaje']		= '';
			$_SESSION['mensajeError']	= '';
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function nuevoRegistro() {
			$this -> obj -> inicializar('nuevo-registro', 'Nuevo Registro');
			
			@$email	= $_SESSION['email'];
			@$nombr	= $_SESSION['nombres'];
			@$apell	= $_SESSION['apellidos'];
			@$websi	= $_SESSION['website'];
			@$merro	= $_SESSION['mensajeError'];
			
			$merro	= ($merro)? '<div class="mensajeError">'.$merro.'</div>': '';
			
			if ($email)	$this -> obj -> machote -> assign('email', $email);
			if ($nombr)	$this -> obj -> machote -> assign('nombres', $nombr);
			if ($apell)	$this -> obj -> machote -> assign('apellidos', $apell);
			if ($websi)	$this -> obj -> machote -> assign('website', $websi);
			if ($merro)	$this -> obj -> machote -> assign('mensajeError', $merro);

			$_SESSION['mensaje']		= '';
			$_SESSION['mensajeError']	= '';
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
		function registrar() {
			$email	= strtolower(trim($_POST['email']));
			$passw	= trim($_POST['password']);
			$pass2	= trim($_POST['password2']);
			$nombr	= trim($_POST['nombres']);
			$apell	= trim($_POST['apellidos']);
			$websi	= trim($_POST['website']);
			$sexo	= trim($_POST['sexo']);
			
			$_SESSION['email'] 		= $email;
			$_SESSION['nombres']	= $nombr;
			$_SESSION['apellidos']	= $apell;
			$_SESSION['website']	= $websi;
			
			if (!$email) $this -> reportarVacio($email, 'Correo Electrónico', 'nuevo-registro', 'm');
			if (!$passw) $this -> reportarVacio($passw, 'Contraseña', 'nuevo-registro', 'f');
			if (!$nombr) $this -> reportarVacio($nombr, 'Nombre', 'nuevo-registro', 'm');
			if (!$apell) $this -> reportarVacio($apell, 'Apellido', 'nuevo-registro', 'm');

			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `email`='$email'";
			$res	= mysql_query($query);
			$n		= mysql_num_rows($res);
			
			if ($n > 0) {
				$_SESSION['mensajeError'] = "Ya existe un usuario registrado con la dirección $email.";
				header("Location: ?p=nuevo-registro");
				exit();
			}
			
			if ($passw != $pass2) {
				$_SESSION['mensajeError'] = "Las contraseñas no coinciden." ;
				header("Location: ?p=nuevo-registro");
				exit();
			}
			
			$passw	= crypt($passw, 'crp');
			
			$query	= "INSERT INTO `aRecurso_usuarios` (`nombres`, `apellidos`, `email`, `password`, `website`, `id_perfil`, `sexo`, `fecha_registro`) VALUES('$nombr', '$apell', '$email', '$passw', '$websi', '0','$sexo', NOW())";
			$res	= mysql_query($query);
			$id	= mysql_insert_id();
			
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
			
			//----------
			
			if (!mysql_error()) {
				$_SESSION['mensaje']	= "La cuenta ha sido creada exitosamente. Podr&aacute; iniciar sesi&oacute;n cuando un usuario autorice su ingreso.";
				$_SESSION['mensajeError']	= "";
				rss::newUserMAILTO($email,"Registro de usuario",1);
			}
			
			setcookie('auto-CICESERep', '');
			header("Location: ?p=iniciar-sesion");
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function iniciar() {
		
			$usua	= trim($_POST['email']);
			$pass	= $_POST['password'];
			$reco	= @$_POST['recordar'];

			$pass	= crypt($pass, 'crp');
			
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `email`='$usua' AND `password`='$pass'";
			$res	= mysql_query($query);
			$n		= mysql_num_rows($res);
			
			if ($n == 0) {
				$_SESSION['email']			= $usua;
				$_SESSION['mensajeError']	= "El correo electrónico o la contraseña son incorrectos.";
				header("Location: ?p=inicio-sesion");			
				exit();			
			}
			
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `email`='$usua'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			
			$act	= $r['activo'];
			
			if (!$act) {
				$_SESSION['email']			= $usua;
				$_SESSION['mensajeError']	= "Su cuenta a&uacute;n no ha sido autorizada.";
				header("Location: ?p=inicio-sesion");			
				exit();			
			}
			
			$_SESSION['uid']		= $r['id'];
			$_SESSION['nombres']	= $r['nombres'];
			$_SESSION['apellidos']	= $r['apellidos'];
			$_SESSION['email']		= $r['email'];
			$_SESSION['website']	= $r['website'];
			$_SESSION['sexo']		= $r['sexo'];
			$_SESSION['perfil']		= $r['id_perfil'];
			
			if ($reco) {
				setcookie("auto-CICESERep", base64_encode($r['id']), time() + 9999999);
				session_cache_expire(99999);
			} else {
				setcookie("auto-CICESERep", '');
				session_cache_expire(180);
			}
			
			header("Location: ?p=principal");			
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function exponer() {
			$this -> obj -> machote -> assign('email', $_SESSION['email']);
		}
		
		// ----------------------------------------------------------------------------------- //
		function correo($uid) {
			$query	= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$uid'";
			$res	= mysql_query($query);
			$n		= mysql_num_rows($res);
			
			if (!$n)	return 'desconocido';
			$r		= mysql_fetch_array($res);
			return $r['email'];
		}
		
		// ----------------------------------------------------------------------------------- //
		function nombre($uid, $formato) {
			$query		= "SELECT * FROM `aRecurso_usuarios` WHERE `id`='$uid'";
			$res		= mysql_query($query);
			$n			= mysql_num_rows($res);
			
			if (!$n) return 'desconocido';
			$r			= mysql_fetch_array($res);
			$nombres	= trim($r['nombres']);
			$apellidos	= trim($r['apellidos']);
			$nombresa	= explode(' ', $nombres);
			$apellidosa	= explode(' ', $apellidos);
			
			$n	= trim($nombres);
			$n1	= @$nombresa[0];
			$n2	= @$nombresa[1];
			$a	= trim($apellidos);
			$a1	= @$apellidosa[0];
			$a2	= @$apellidosa[1];
			
			$r	= $formato;
			
			$r	= str_replace('[n]', $n, $r);
			$r	= str_replace('[n1.]', $n1[0].".", $r);
			$r	= str_replace('[n2.]', $n2[0].".", $r);
			$r	= str_replace('[n1]', $n1, $r);
			$r	= str_replace('[n2]', $n2, $r);
			$r	= str_replace('[a]', $a, $r);
			$r	= str_replace('[a1.]', $a1[0].".", $r);
			$r	= str_replace('[a2.]', $a2[0].".", $r);
			$r	= str_replace('[a1]', $a1, $r);
			$r	= str_replace('[a2]', $a2, $r);
			
			return $r;
		}	
	}
?>