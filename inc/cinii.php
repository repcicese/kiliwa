<?php
//Consulta realizada a CiNii (National Institute of Informatics Scholarly and Academic Information Navigator)
if(isset($_POST['option'])){
	switch($_POST['option']){
		//CARGAMOS LOS DATOS DEL REGISTRO DE USUARIO APRA EDITARLOS
		case 'buscar-datos':{
				//Initialize fopen
				$fopen_feed = @fopen("http://ci.nii.ac.jp/opensearch/search?issn=".$_POST['issn']."&count=1&volume=".$_POST['volume']."&number=".$_POST['number']."&format=atom", "r");
				//Make sure fopen was successfull
				if ($fopen_feed) {
				//Store our data
					$data = "";
					while (!feof($fopen_feed)) {
						$data .= fread($fopen_feed, 8192);
					}
				}
				//Close fopen
				fclose($fopen_feed);
				
				$data=str_replace("<dc:publisher>","<publisher>",$data);
				$data=str_replace("</dc:publisher>","</publisher>",$data);
				
				$data=str_replace("<prism:publicationName>","<publicationName>",$data);
				$data=str_replace("</prism:publicationName>","</publicationName>",$data);
				
				$data=str_replace("<prism:issn>","<issn>",$data);
				$data=str_replace("</prism:issn>","</issn>",$data);
				
				$data=str_replace("<prism:volume>","<volume>",$data);
				$data=str_replace("</prism:volume>","</volume>",$data);
				
				$data=str_replace("<prism:number>","<number>",$data);
				$data=str_replace("</prism:number>","</number>",$data);
				
				$data=str_replace("<prism:startingPage>","<startingPage>",$data);
				$data=str_replace("</prism:startingPage>","</startingPage>",$data);
				
				$data=str_replace("<prism:endingPage>","<endingPage>",$data);
				$data=str_replace("</prism:endingPage>","</endingPage>",$data);
				
				$data=str_replace("<prism:publicationDate>","<publicationDate>",$data);
				$data=str_replace("</prism:publicationDate>","</publicationDate>",$data);
				
				$data=str_replace('<content type="text">',"<content>",$data);
				
				$salida="	forma.titulo.value=\"".value_in("title",value_in("entry",$data))."\";
							forma.autores.value=\"".value_in("name",value_in("author",$data))."\";
							forma.anno.value=\"".value_in("publicationDate",$data)."\";
							forma.volumen.value=\"".value_in("volume",$data)."\";
							forma.numero.value=\"".value_in("number",$data)."\";
							forma.paginas.value=\"".value_in("startingPage",$data)."-".value_in("endingPage",$data)."\";
							forma.resumen.value=\"".value_in("content",$data)."\";
				";
				
		} break;
		default:
	}
	
	echo $salida;
}

function value_in($element_name, $xml, $content_only = true) {
    if ($xml == false) {
        return false;
    }
    $found = preg_match('#<'.$element_name.'(?:\s+[^>]+)?>(.*?)'.
            '</'.$element_name.'>#s', $xml, $matches);
    if ($found != false) {
        if ($content_only) {
            return $matches[1];  //ignore the enclosing tags
        } else {
            return $matches[0];  //return the full pattern match
        }
    }
    // No match found: return false.
    return false;
}
?>
