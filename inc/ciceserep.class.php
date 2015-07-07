<?php

	// --------------------------------------------------------------------------------------- //
	//	CICESEREP - Repositorio Compartido                                                     //
	// --------------------------------------------------------------------------------------- //
	//  Clase general del sitio.                                                               //
	//  Versión 1.0, 2005/09/25 @ 17:43                                                        //
	// ======================================================================================= //

	require "lib/powertemplate/src/php/PowerTemplate.php";
	require "lib/constantes.php";
	require "inc/sesion.class.php";
	require "inc/articulos.class.php";
	require "inc/archivos.class.php";
	require "inc/software.class.php";
	require "inc/noticias.class.php";
	require "inc/tutoriales.class.php";
	require "inc/vinculos.class.php";
	require "inc/herramientas.class.php";
	require "inc/configuracion.class.php";
	require "inc/rss.class.php";
	require "inc/buscar.class.php";
	require "cfg/general.php";
	
	ini_set("arg_separator.input", "&#38;");
	ini_set("arg_separator.output", "&#38;");
	ini_set("register_globals", "off");
	ini_set("memory_limit", 300 * 1024 * 1024);
	ini_set("max_execution_time", 30);
	ini_set("upload_max_filesize", pow(1024, 3));
	 
	
	error_reporting(E_ALL);
	//error_reporting(-1); ini_set('display_errors', 1);
	
	class CICESEREP {
	
		var $machote, $sesion, $tema, $titulo, $articulos, $archivos, $software,
		    $tutoriales, $vinculos, $herramientas, $configuracion, $buscar;
	
		// ----------------------------------------------------------------------------------- //
		function CICESEREP() {
			global $tema_sitio;
			$this -> machote		= new PowerTemplate();
			$this -> tema			= $tema_sitio;
			$this -> sesion			= new SESION($this);
			$this -> archivos		= new ARCHIVOS($this);
			$this -> software		= new SOFTWARE($this);
			$this -> noticias		= new NOTICIAS($this);
			$this -> tutoriales		= new TUTORIALES($this);
			$this -> vinculos		= new VINCULOS($this);
			$this -> configuracion	= new CONFIGURACION($this);
			$this -> articulos		= new ARTICULOS($this);
			$this -> herramientas	= new HERRAMIENTAS($this);
			$this -> buscar = new BUSCAR($this);
			$this -> titulo			= "CICESE-Rep";
			$this -> conectarBD();
		}
		
		// ----------------------------------------------------------------------------------- //
		function conectarBD() {
			global $mysql_servidor, $mysql_usuario, $mysql_pword, $mysql_bdatos;
			mysql_pconnect($mysql_servidor, $mysql_usuario, $mysql_pword);
			mysql_select_db($mysql_bdatos);
		}

		// ----------------------------------------------------------------------------------- //
		function inicializar($arch, $titulo, $dir = "") {
			header("Content-type: text/html");
			$this -> machote -> loadWithContentsOfFile("{$dir}tpl/{$this -> tema}/$arch.tpl");
			$this -> machote -> assign('titulo', "CICESE-Rep / ".$titulo);
		}
		
		// ----------------------------------------------------------------------------------- //
		function enviarPagina($resaltar = false, $dir = "") {
			if (isset($_SESSION['email'])) $this -> machote -> assign('email', $_SESSION['email']);
			$contenido	= $this -> machote -> parse();
			$contenido	= str_replace("src=\"img", "src=\"{$dir}tpl/{$this -> tema}/img", $contenido);
			$contenido	= str_replace("background=\"", "background=\"{$dir}tpl/{$this -> tema}/", $contenido);
			$contenido	= str_replace("estilo.css", "{$dir}tpl/{$this -> tema}/estilo.css", $contenido);
			$contenido	= str_replace("'img/", "'{$dir}tpl/{$this -> tema}/img/", $contenido);
			$contenido	= str_replace("url('img", "url('{dir}tpl/{$this -> tema}/img", $contenido);
			$contenido	= str_replace("href=\"?", "href=\"{$dir}?", $contenido);
			if ($resaltar) $contenido = str_replace(str_pad($resaltar, 3, '0', 0).".gif", str_pad($resaltar + 1, 3, '0', 0).'.gif', $contenido);
			echo $contenido;
			exit();
		}
		
		
		// ----------------------------------------------------------------------------------- //
		function principal() {
			$this -> inicializar('principal', 'Inicio');
			
			$mensaje		= @$_SESSION['mensaje'];
			$mensajeError	= @$_SESSION['mensajeError'];
			
			if ($mensaje)		$this -> machote -> assign('mensaje', $mensaje);
			if ($mensajeError)	$this -> machote -> assign('mensajeError', $mensajeError);
			
			$_SESSION['mensaje']		= '';
			$_SESSION['mensajeError']	= '';
			
			$nots	= array();
			$query	= "SELECT *, DATE_FORMAT(`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_noticias` ORDER BY `ultima_actualizacion_fecha` DESC LIMIT 0,3";
			$res	= mysql_query($query);
			while ($r = mysql_fetch_array($res)) {
				$f	= $r['ultima_actualizacion_fecha'];
				$t	= $r['titulo'];
				$c	= $r['contenido'];
				$nots[] = array('fecha' => $f, 'titulo' => $t, 'texto' => $c);
			}
			$this -> machote -> assign('noticias', $nots);

			$archs		= array();
			$query		= "SELECT * FROM `aRecurso_archivos` ORDER BY `id` DESC LIMIT 0,10";
			$res		= mysql_query($query);
			while ($r	= mysql_fetch_array($res)) {
				$id		= $r['id'];
				$pa		= $this -> archivos -> pagina($id);
				$ic		= $this -> archivos -> tipo($r['nombre']);
				$no		= $r['nombre'];
				$ti		= $this -> archivos -> descripcion($id);
				$em		= $this -> archivos -> usuario($id);
				$us		= $this -> archivos -> usuario($id);
				$u2		= $this -> archivos -> usuario($id);
				$fe		= $this -> archivos -> fecha($id);
				$se		= $this -> archivos -> seccion($id);
				$id		= $this -> archivos -> realId($id);
				
				$lim = 65;
				if (strlen($ti) > $lim)
					$ti = '<span title="'.$ti.'">'.trim(substr($ti, 0, $lim)).'&hellip;</span>';
				
				if ($id) $archs[]	= array('id' => $id, 'pagina' => $pa, 'icono' => $ic, 'nombre' => $no, 'titulo' => $ti, 'email' => $em, 'usuarioc' => $us, 'usuario' => $u2, 'fecha' => $fe, 'seccion' => $se);
			}
			$this -> machote -> assign('archivos', $archs);
			
			$nuevs		= array();
			$query		= "SELECT * FROM `aRecurso_usuarios` WHERE `activo`='0' ORDER BY `nombres`";
			$res		= mysql_query($query);	echo mysql_error();
			while ($r	= mysql_fetch_array($res)) {
				$id		= $r['id'];
				$nom	= $r['nombres'];
				$ape	= $r['apellidos'];
				$cor	= $r['email'];
				$nuevs[] = array('id' => $id, 'nombre' => "$nom $ape", 'correo' => $cor);
			}
			if ($nuevs)
			$this -> machote -> assign('nuevos', $nuevs);


			$this -> machote -> assign('email', $_SESSION['email']);
			tengoPermiso('crear_usuarios',@$_SESSION['perfil'])?		$this -> machote -> assign('crear_usuarios', true):"";
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> machote -> assign('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> machote -> assign('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> machote -> assign('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> machote -> assign('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> machote -> assign('configuracion_grupos', true):"";
			$this -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function ejecutar() {

			if (!isset($_GET['p'])) $_GET['p'] = 'principal';
		
			if (!($this -> sesion -> activa($this))) {
				switch ($_GET['p']) {
					case 'iniciar-sesion':					$this -> sesion -> formulario();					break;
					case 'nuevo-registro':					$this -> sesion -> nuevoRegistro(); 				break;
					case 'procesar-sesion':					$this -> sesion -> iniciar();						break;
					case 'procesar-registro':				$this -> sesion -> registrar();						break;
					default:								$this -> sesion -> formulario();					break;
				}
			}
			
			switch ($_GET['p']) {
				case 'cerrar-sesion':						$this -> sesion -> cerrar();						break;
				
				case 'articulos':							$this -> articulos -> principal();					break;
				case 'articulos-subir':						$this -> articulos -> subirArticulo();				break;
				case 'articulos-ver':						$this -> articulos -> ver();						break;
				case 'articulos-editar':					$this -> articulos -> editar();						break;
				case 'articulos-bajar':						$this -> archivos -> bajar();						break;
				
				case 'procesar-articulos-subir':			$this -> articulos -> procesarSubirArticulo();		break;
				case 'procesar-articulos-editar':			$this -> articulos -> procesarEditarArticulo();		break;
				case 'procesar-articulos-eliminar':			$this -> articulos -> eliminar();					break;
				
				case 'software':							$this -> software -> principal();					break;
				case 'software-subir':						$this -> software -> subirArticulo();				break;
				case 'software-ver':						$this -> software -> ver();							break;
				case 'software-editar':						$this -> software -> editar();						break;
				case 'software-bajar':						$this -> archivos -> bajar();						break;
				
				case 'procesar-software-subir':				$this -> software -> procesarSubirArticulo();		break;
				case 'procesar-software-editar':			$this -> software -> procesarEditarArticulo();		break;
				case 'procesar-software-eliminar':			$this -> software -> eliminar();					break;
				
				case 'noticias':							$this -> noticias -> principal();					break;
				case 'noticias-agregar':					$this -> noticias -> agregar();						break;
				case 'noticias-editar':						$this -> noticias -> editar();						break;
				case 'noticias-eliminar':					$this -> noticias -> eliminar();					break;
				
				case 'procesar-noticias-agregar':			$this -> noticias -> procesarAgregar();				break;
				case 'procesar-noticias-editar':			$this -> noticias -> procesarEditar();				break;
				
				case 'tutoriales':							$this -> tutoriales -> principal();					break;
				case 'tutoriales-subir':					$this -> tutoriales -> subirArticulo();				break;
				case 'tutoriales-ver':						$this -> tutoriales -> ver();						break;
				case 'tutoriales-editar':					$this -> tutoriales -> editar();					break;
				case 'tutoriales-bajar':					$this -> archivos -> bajar();						break;
				
				case 'procesar-tutoriales-subir':			$this -> tutoriales -> procesarSubirArticulo();		break;
				case 'procesar-tutoriales-editar':			$this -> tutoriales -> procesarEditarArticulo();	break;
				case 'procesar-tutoriales-eliminar':		$this -> tutoriales -> eliminar();					break;
				
				case 'vinculos':							$this -> vinculos -> principal();					break;
				case 'vinculos-subir':						$this -> vinculos -> subirArticulo();				break;
				case 'vinculos-ver':						$this -> vinculos -> ver();							break;
				case 'vinculos-editar':						$this -> vinculos -> editar();						break;
				case 'vinculos-bajar':						$this -> archivos -> bajar();						break;
				
				case 'procesar-vinculos-subir':				$this -> vinculos -> procesarSubirArticulo();		break;
				case 'procesar-vinculos-editar':			$this -> vinculos -> procesarEditarArticulo();		break;
				case 'procesar-vinculos-eliminar':			$this -> vinculos -> eliminar();					break;
				
				case 'herramientas':						$this -> herramientas -> principal();				break;
				case 'herramientas-bst':					$this -> herramientas -> bst();						break;
				case 'herramientas-latex':					$this -> herramientas -> latex();					break;
				case 'herramientas-html':					$this -> herramientas -> html();					break;
				
				case 'configuracion':						$this -> configuracion -> principal();				break;
				case 'grupos-trabajo':						$this -> configuracion -> grupos();					break;
				case 'perfiles':							$this -> configuracion -> perfiles();				break;
				
				case 'procesar-configuracion':				$this -> configuracion -> procesarConfiguracion();	break;
				
				case 'usuario-autorizar':					$this -> sesion -> autorizar();						break;
				case 'usuario-negar':						$this -> sesion -> negar();							break;

				case 'buscar' : 								$this -> buscar -> busqueda();					break;			
			}
			
			$this -> principal();
		}

	}

	// ----------------------------------------------------------------------------------- //
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
	
	function tengoPermiso($servicio,$idperfil){
		$query	= "SELECT `".$servicio."` FROM `zSistema_perfil` WHERE `".$servicio."`='".$idperfil."' LIMIT 1";
		$res	= mysql_query($query);	echo mysql_error();
		$r 		= mysql_fetch_array($res);
		return($r[0]==1)?true:false;
	}
	
	function getTagName($id){
		$query	= "SELECT `nombre` FROM `kControl_etiquetas` WHERE `id`='$id'";
		$res	= mysql_query($query);
		$r		= mysql_fetch_array($res);
		return $r[0];	
	}
	function FechaFormateada($FechaStamp){
		$ano = date('Y',$FechaStamp); //<-- Año
		$mes = date('m',$FechaStamp); //<-- número de mes (01-31)
		$dia = date('d',$FechaStamp); //<-- Día del mes (1-31)
		$dialetra = date('w',$FechaStamp);  //Día de la semana(0-7)
		switch($dialetra){
			case 0: $dialetra="domingo"; break;
			case 1: $dialetra="lunes"; break;
			case 2: $dialetra="martes"; break;
			case 3: $dialetra="miércoles"; break;
			case 4: $dialetra="jueves"; break;
			case 5: $dialetra="viernes"; break;
			case 6: $dialetra="sábado"; break;
		}
		
		switch($mes) {
			case '01': $mesletra="enero"; break;
			case '02': $mesletra="febrero"; break;
			case '03': $mesletra="marzo"; break;
			case '04': $mesletra="abril"; break;
			case '05': $mesletra="mayo"; break;
			case '06': $mesletra="junio"; break;
			case '07': $mesletra="julio"; break;
			case '08': $mesletra="agosto"; break;
			case '09': $mesletra="septiembre"; break;
			case '10': $mesletra="octubre"; break;
			case '11': $mesletra="noviembre"; break;
			case '12': $mesletra="diciembre"; break;
		}    
	
	return "$dialetra, $dia de $mesletra de $ano";
}
?>