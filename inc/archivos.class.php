<?php
	class ARCHIVOS {
	
		var $obj;
	
		// ----------------------------------------------------------------------------------- //
		function ARCHIVOS($obj) {
			$this -> obj	= $obj;
		}
		
		// ----------------------------------------------------------------------------------- //
		function tipo($arch) {
			$arch	= explode('.', $arch);
			$arch	= $arch[count($arch) - 1];
			
			$tipos	= array('pdf', 'doc', 'htm', 'ppt', 'zip');
			if (array_search($arch, $tipos) === false) $arch = 'arch';
			
			return $arch;
		}
		
		// ----------------------------------------------------------------------------------- //
		function nombre($aid) {
			$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$aid'";
			$res	= mysql_query($query);	echo mysql_error();
			$r		= mysql_fetch_array($res);
			return $r['nombre'];
		}
		
		// ----------------------------------------------------------------------------------- //
		function tamano($aid) {
			$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$aid'";
			$res	= mysql_query($query);	echo mysql_error();
			$r		= mysql_fetch_array($res);
			$r		= $r['tamano'];

			$r		= $r / 1024;
			$rd		= "KB";
			
			if ($r / 1024 > 1) {
				$r	= $r / 1024;
				$rd	= "MB";
			}
			
			if ($r / 1024 > 1) {
				$r	= $r / 1024;
				$rd	= "GB";
			}
			
			$r		= ($rd == 'KB')? floor($r): $r;
			$r		= number_format($r, ($rd == 'KB')? 0: 2);
			$r		= "$r $rd";
			return $r;
		}
		
		// ----------------------------------------------------------------------------------- //
		function subir($arch) {

			$no	= preg_replace('/^[0-9]+_/', '', basename($arch));
			$ti	= pathinfo($arch, PATHINFO_EXTENSION);
			$ta	= filesize($arch);
				
			$query	= "INSERT INTO `aRecurso_archivos` (`nombre`, `tipo`, `tamano`) VALUES ('$no', '$ti', '$ta')";
			$res	= mysql_query($query); echo mysql_error();
			$id		= mysql_insert_id();
		
			@rename($arch, 'pub/'.$id);
			
			return $id;
		}
		
		// ----------------------------------------------------------------------------------- //
		function bajar() {
			$id		= $_GET['id'];
			$usarArch = true;
			
			$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$id'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);

			if (mysql_num_rows($res) == 0) die("No se puede encontrar el archivo con id $id");

			$nombre	= $r["nombre"];
			$tamano	= $r["tamano"];
			$tipo	= $r["tipo"];
			
			if(!file_exists('pub/'.$id)){	
				$usarArch = false;
			}
	
			// Devolver el archivo
			ini_set('session.cache_limiter',"0");
			session_cache_limiter(0);

			if (PMA_USR_BROWSER_AGENT == 'IE') {	
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: public");
			} else header("Pragma: no-cache");
			header("Content-type: $tipo");
			header("Content-Disposition: attachment; filename=$nombre");

			if ($usarArch) readfile('pub/'.$id);
			else readfile('pub/empty'); //echo $datos;
		
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function forzarDescarga($datos, $nombre, $tipo, $tamano) {
			ini_set('session.cache_limiter',"0");
			session_cache_limiter(0);

			if (PMA_USR_BROWSER_AGENT == 'IE') {			
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: public");
			} else header("Pragma: no-cache");
			header("Content-type: $tipo");
			header("Expires: ".gmdate('D, d M Y H:i:s').' GMT');
			header("Content-length: $tamano");
			header("Content-Disposition: attachment; filename=$nombre");
			header("Content-Description: Recurso Compartido");
			echo $datos;
		
			exit();
		}
		
		// ----------------------------------------------------------------------------------- //
		function extraerArchivo($aid) {
			$query	= "SELECT * FROM `aRecurso_archivos` WHERE `id`='$aid'";
			$res	= mysql_query($query);
			$r		= mysql_fetch_array($res);
			if (mysql_num_rows($res) == 0) return readfile('pub/'.$id);

			$res	= mysql_query($query);
			$datos = "";
			while ($r = mysql_fetch_array($res))
				$datos .= base64_decode($r["datos"]);
			return $datos;
		}
		
		// ----------------------------------------------------------------------------------- //
		function agregarLog($s, $f) {
			$f	= fopen($f, "a");
			fputs($f, $s."\r\n");
			fclose($f);
		}
		
		// ----------------------------------------------------------------------------------- //
		function vistaPrevia($aid) {
			$arch	= new ARCHIVOS($this);
			$no		= $arch -> nombre($aid);
			$ic		= $arch -> tipo($no);
		
			if (!file_exists("tmp/vp/$aid.jpg")) {
				exec("convert 'pub/$aid'[0] -thumbnail 150x194 'tmp/vp/$aid.jpg' 2>> log/archivos.log");
		
				if (!file_exists("tmp/vp/$aid.jpg")) {
					$this -> agregarLog("no se genero vista previa $aid.", "log/archivos.log");
					copy("tmp/error-$ic.jpg", "tmp/vp/$aid.jpg");
				}	
			}
			
			return "$aid";
		}
		
		// ----------------------------------------------------------------------------------- //
		function tabla($aid) {	
			$query	= "SELECT `id` FROM `aRecurso_articulos` WHERE `id_archivo`='$aid'";
			$res	= mysql_query($query);
			$n		= mysql_num_rows($res);
			if ($n > 0) return 'aRecurso_articulos';
			
			$query	= "SELECT `id` FROM `aRecurso_software` WHERE `id_archivo`='$aid'";
			$res	= mysql_query($query);
			$n		= mysql_num_rows($res);
			if ($n > 0) return 'aRecurso_software';
			
			$query	= "SELECT `id` FROM `aRecurso_tutoriales` WHERE `id_archivo`='$aid'";
			$res	= mysql_query($query);
			$n		= mysql_num_rows($res);
			if ($n > 0) return 'aRecurso_tutoriales';
			
			return false;			
		}
		
		// ----------------------------------------------------------------------------------- //
		function descripcion($aid) {
			$des	= '&hellip;';
			$tab	= $this -> tabla($aid);
			$query	= "SELECT `titulo` FROM $tab WHERE `id_archivo`='$aid'";
			$res	= mysql_query($query); if (mysql_error()) return '&hellip;';
			return mysql_result($res, 0, 'titulo');
		}
		
		// ----------------------------------------------------------------------------------- //
		function realId($aid) {
			$des	= '&hellip;';
			$tab	= $this -> tabla($aid);
			$query	= "SELECT `id` FROM $tab WHERE `id_archivo`='$aid'";
			$res	= mysql_query($query); if (mysql_error()) return 0;
			return mysql_result($res, 0, 'id');
		}
		
		// ----------------------------------------------------------------------------------- //
		function seccion($aid) {
			$sec	= '&hellip;';
			$tab	= $this -> tabla($aid);
			
			switch ($tab) {
				case 'aRecurso_articulos':	$sec = 'Art&iacute;culos';	break;
				case 'aRecurso_software':	$sec = 'Software';			break;
				case 'aRecurso_tutoriales':	$sec = 'Tutoriales';		break;
			}
		
			return $sec;
		}
		
		// ----------------------------------------------------------------------------------- //
		function pagina($aid) {
			$sec	= '&hellip;';
			$tab	= $this -> tabla($aid);
			
			switch ($tab) {
				case 'aRecurso_articulos':	$sec = 'articulos';	break;
				case 'aRecurso_software':	$sec = 'software';			break;
				case 'aRecurso_tutoriales':	$sec = 'tutoriales';		break;
			}
		
			return $sec;
		}
		
		// ----------------------------------------------------------------------------------- //
		function usuario($aid) {
			$des = '&hellip;';
			return $des;
		}
		
		// ----------------------------------------------------------------------------------- //
		function fecha($aid) {
			$des	= '&hellip;';
			//$tab	= $this -> tabla($aid);
			$query	= "SELECT DATE_FORMAT(`ultima_actualizacion_fecha`, '%d-%m-%y %H:%i %p') AS `ultima_actualizacion_fecha` FROM `aRecurso_archivos` WHERE `id` LIKE '$aid'";
			$res	= mysql_query($query); if (mysql_error()) return time();
			return mysql_result($res, 0, 'ultima_actualizacion_fecha');
		}
		
		// ----------------------------------------------------------------------------------- //
	}
?>
