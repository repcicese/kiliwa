<?php
require "../cfg/general.php";

if(isset($_POST['option'])){
      
  global $mysql_servidor, $mysql_usuario, $mysql_pword, $mysql_bdatos;
  mysql_pconnect($mysql_servidor, $mysql_usuario, $mysql_pword);
  mysql_select_db($mysql_bdatos);   
  $salida="";

  switch($_POST['option']){
    case 'mostrar_etiquetas':{
      //CONSTRUCCION DE ETIQUETAS
      $salida .= '<ul id="DragWordList">';
      $salida .= "<table>";
      //Obtener grupos de trabajo             
      $query="SELECT * FROM `zSistema_grupos_trabajo` ORDER BY RAND()";
      $res=mysql_query($query); echo mysql_error();
      while ($r = mysql_fetch_array($res)) {
        $count = 1;
        $salida .= '<tr><td><span>'.$r['nombre'].':</span>';
        $idg = $r['id'];
        //Obtener etiquetas por grupos de trabajo
        $query="SELECT `t2`.`nombre` AS `etiqueta` FROM `kControl_diccionario_etiquetas_grupos_trabajo` AS `t1` LEFT JOIN `kControl_etiquetas` AS `t2` ON `t1`.`id_etiqueta` = `t2`.`id` WHERE `t1`.`id_grupo`='$idg' ORDER BY RAND()";
        $res2=mysql_query($query); echo mysql_error();
        while($r = mysql_fetch_array($res2) and $count <= 8) {
          $salida .= '<li class="etiquetas"><a>'.$r['etiqueta'].'</a></li>';
          $count ++;
        }
        $salida .= "</td></tr>";
      }

      //Obtener etiquetas sin grupo de trabajo
      $salida .= '<tr><td><span>Sin grupo de trabajo:</span>';
      $query="SELECT `t1`.`nombre` AS `etiqueta` FROM `kControl_etiquetas` AS `t1` LEFT JOIN `kControl_diccionario_etiquetas_grupos_trabajo` AS `t2` ON `t2`.`id_etiqueta`=`t1`.`id` WHERE `t2`.`id_grupo` IS NULL ORDER BY RAND();";
      $res=mysql_query($query); echo mysql_error();
      $count = 1;
      while ($r = mysql_fetch_array($res) and $count <= 8) {
        $salida .= '<li class="etiquetas"><a>'.$r['etiqueta'].'</a></li>';
        $count ++;
      }
      $salida .= "</td></tr>";
      $salida .= "</table>";   
      $salida .='</ul>';        
    } break;
    case 'buscar_etiquetas' :{
      $salida .= '<ul>';
      $query="SELECT `t1`.`nombre` AS `etiqueta` FROM `kControl_etiquetas` AS `t1` LEFT JOIN `kControl_diccionario_etiquetas_grupos_trabajo` AS `t2` ON `t2`.`id_etiqueta`=`t1`.`id` WHERE `t2`.`id_grupo` IS NULL ORDER BY RAND();";
      $res=mysql_query($query); echo mysql_error();
      while ($r = mysql_fetch_array($res)) {
        $salida .= '<li class="etiquetas"><a>'.$r['etiqueta'].'</a></li>';
      }
      $salida .='</ul>';        
    } break;
    default:    
  }
  echo $salida;
}
?>