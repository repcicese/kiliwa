<?php

	class HERRAMIENTAS {
	
		var $obj, $llaves;
	
		// ----------------------------------------------------------------------------------- //
		function HERRAMIENTAS($obj) {
			$this -> obj		= $obj;
			$this -> llaves		= array();
		}
		
		// ----------------------------------------------------------------------------------- //
		function llaveAutores($au, $an) {
			$au	= $this -> obj -> articulos -> formatoAutores($au);
			$au	= strip_tags($au);
			$au	= str_replace(' ', '', $au);
			$au	= str_replace('&', '', $au);
			$au	= str_replace('.', '', $au);
			$au	= strtolower($au);
			$au	= "$au$an";
			
			if (!isset($this -> llaves[$au])) {
				$this -> llaves[$au] = 1;
				return $au;
			}
			
			$n	= $this -> llaves[$au];
			$this -> llaves[$au] = $n + 1;
			$au	= $au.'_'.($n + 1);
			
			return $au;
		}

		// ----------------------------------------------------------------------------------- //		
		function refAutores($au, $an) {
			$au	= $this -> obj -> articulos -> formatoAutores($au);
			$au	= trim($au);
			$au	= "$au, $an";
			return $au;
		}

		
		// ----------------------------------------------------------------------------------- //
		function formatoAutores($au) {
			$au	= trim($au);
			if (substr($au, -1) == ';') $au = substr($au, 0, -1);
			$au	= explode(';', $au);
			foreach ($au as $k => $v)
				$au[$k] = trim($v);
			$au	= implode(' and ', $au);
			return $au;
		}
		
		// ----------------------------------------------------------------------------------- //
		function formatoAutores2($au) {
			$au	= explode(' and ', $au);
			$au	= implode(', ', $au);
			$i	= strrpos($au, ', ');
			if ($i !== false)
				$au[$i] = '&';
			$au	= str_replace('&', ' &', $au);
			return $au;
		}
		
		// ----------------------------------------------------------------------------------- //
		function principal() {
			$this -> obj -> inicializar('herramientas', 'Herramientas');
			$this -> obj -> sesion -> exponer();
			$this -> exponer();
			
			$latx	= array();
			$html	= array();
			
			$query	= "SELECT * FROM `aRecurso_articulos` ORDER BY `ultima_actualizacion_fecha` ASC";
			$res	= mysql_query($query);
			while ($r = mysql_fetch_array($res)) {
				$au	= $r['autores'];
				$an	= $r['anno'];
				$a2	= $r['id_archivo'];
				$ti	= $r['titulo'];
				$re	= $r['revista'];
				$lu	= $r['lugar'];
				$vo	= $r['volumen'];
				$nu	= $r['numero'];
				$pa	= $r['paginas'];
				$ur = $r['url'];
				
				$ll	= $this -> llaveAutores($au, $an);
				$a2	= $this -> formatoAutores($a2);
				$pa	= str_replace('-', '--', trim($pa));
				
				$latex	=	"@article{{$ll},\n".
							"\tauthor  = \"$a2\",\n".
							"\ttitle   = \"$ti\",\n".
							"\tyear    = \"$an\",\n".
					(($re)? "\tjournal = \"$re".(($lu)? ". $lu": '')."\",\n": '').
					(($vo)? "\tvolume  = \"$vo\",\n": '').
					(($nu)? "\tnumber  = \"$nu\",\n": '').
					(($pa)? "\tpages   = \"$pa\",\n": '').
					(($ur)?	"\tnote    = \"Disponible en: $ur\",\n": '');
				$latex	= substr($latex, 0, -2)."\n}\n\n";
				$latx[] = $latex;
				
				$ll	= $this -> refAutores($au, $an);
				$a2	= $this -> formatoAutores2($a2);
				$pa	= str_replace('--', '-', $pa);
				
				$htm	=	"<li>[$ll] ".
							"$a2. ".
							"$an. ".
							"$ti. ".
					(($re)? "<em>$re".(($lu)? ". $lu": '')."</em>. ": '').
					(($vo)? " $vo".(($nu)? "($nu).": ''): '').
					(($pa)? " pp. $pa.": '').
					(($ur)? " Disponible en: $ur": '');
				$htm	=	"$htm<br><br></li>";
				$html[]	= $htm;
				
			}
			
			asort($latx);
			$latex = implode('', $latx);
			
			$latex	= str_replace("\n", '<br>', $latex);
			$latex	= str_replace(" ", '&nbsp;', $latex);
			$latex	= str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $latex);
			$latex	= str_replace("and&nbsp;", "and ", $latex);
			$latex	= str_replace("da&nbsp;", "da ", $latex);
			$latex	= str_replace(".&nbsp;", ". ", $latex);
			$latex	= str_replace(",&nbsp;", ", ", $latex);
			$latex	= str_replace("of&nbsp;", "of ", $latex);
			$latex	= str_replace("for&nbsp;", "for ", $latex);
			$latex	= str_replace("on&nbsp;", "on ", $latex);
			
			asort($html);
			$html	= implode('', $html);
			$html	= "<ul>$html</ul>";
			
			$this -> asignar('latex', $latex);
			$this -> asignar('html', $html);
			
			tengoPermiso('crear_recursos',@$_SESSION['perfil'])?		$this -> asignar('crear_recursos', true):"";
			tengoPermiso('actualizar_recursos',@$_SESSION['perfil'])?	$this -> asignar('actualizar_recursos', true):"";
			tengoPermiso('borrar_recursos',@$_SESSION['perfil'])?		$this -> asignar('borrar_recursos', true):"";
			tengoPermiso('configuracion_perfil',@$_SESSION['perfil'])?	$this -> asignar('configuracion_perfil', true):"";
			tengoPermiso('configuracion_grupos',@$_SESSION['perfil'])?	$this -> asignar('configuracion_grupos', true):"";
			$this -> obj -> enviarPagina();
		}
		
		// ----------------------------------------------------------------------------------- //
		function latex() {
			$latx	= array();
			$query	= "SELECT * FROM `aRecurso_articulos` ORDER BY `ultima_actualizacion_fecha` ASC";
			$res	= mysql_query($query);
			while ($r = mysql_fetch_array($res)) {
				$au	= $r['autores'];
				$an	= $r['anno'];
				$a2	= $r['id_archivo'];
				$ti	= $r['titulo'];
				$re	= $r['revista'];
				$lu	= $r['lugar'];
				$vo	= $r['volumen'];
				$nu	= $r['numero'];
				$pa	= $r['paginas'];
				$ur = $r['url'];
				
				$ll	= $this -> llaveAutores($au, $an);
				$a2	= $this -> formatoAutores($a2);
				$pa	= str_replace('-', '--', trim($pa));
				
				$latex	=	"@article{{$ll},\n".
							"\tauthor  = \"$a2\",\n".
							"\ttitle   = \"$ti\",\n".
							"\tyear    = \"$an\",\n".
					(($re)? "\tjournal = \"$re".(($lu)? ". $lu": '')."\",\n": '').
					(($vo)? "\tvolume  = \"$vo\",\n": '').
					(($nu)? "\tnumber  = \"$nu\",\n": '').
					(($pa)? "\tpages   = \"$pa\",\n": '').
					(($ur)?	"\tnote    = \"Disponible en: $ur\",\n": '');
				$latex	= substr($latex, 0, -2)."\n}\n\n";
				$latx[] = $latex;
			}
			
			asort($latx);
			$latex = implode('', $latx);
			$latex	= str_replace("\n", "\r\n", $latex);
			
			$this -> obj -> archivos -> forzarDescarga($latex, 'cicese.bib', 'plain/text', strlen($latex));
		}

		// ----------------------------------------------------------------------------------- //
		function html() {
			$html	= array();
			$query	= "SELECT * FROM `aRecurso_articulos` ORDER BY `ultima_actualizacion_fecha` ASC";
			$res	= mysql_query($query);
			while ($r = mysql_fetch_array($res)) {
				$au	= $r['autores'];
				$an	= $r['anno'];
				$a2	= $r['autores'];
				$ti	= $r['titulo'];
				$re	= $r['revista'];
				$lu	= $r['lugar'];
				$vo	= $r['volumen'];
				$nu	= $r['numero'];
				$pa	= $r['paginas'];
				$ur = $r['url'];
				
				$a2	= $this -> formatoAutores($a2);
				$ll	= $this -> refAutores($au, $an);
				$a2	= $this -> formatoAutores2($a2);
				$pa	= str_replace('--', '-', $pa);
				
				$htm	=	"<li>[$ll] ".
							"$a2. ".
							"$an. ".
							"$ti. ".
					(($re)? "<em>$re".(($lu)? ". $lu": '')."</em>. ": '').
					(($vo)? " $vo".(($nu)? "($nu).": ''): '').
					(($pa)? " pp. $pa.": '').
					(($ur)? " Disponible en: $ur": '');
				$htm	=	"$htm<br><br></li>";
				$html[]	= $htm;
			}
			
			asort($html);
			$html	= implode('', $html);
			$html	= str_replace("</li>", "</li>\r\n\r\n", $html);
			$html	= "<html>\r\n<head><title>Referencias</title></head>\r\n\r\n<body>\r\n<ul>\r\n\r\n$html</ul>\r\n</body>\r\n</html>";
			
			$this -> obj -> archivos -> forzarDescarga($html, 'cicese.html', 'plain/html', strlen($html));
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
		
		// ----------------------------------------------------------------------------------- //
		function bst() {
			$datos	= implode('', file('cnt/cicese.bst'));
			$this -> obj -> archivos -> forzarDescarga($datos, 'cicese.bst', 'plain/text', strlen($datos));
		}	
	}
?>