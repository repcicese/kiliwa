function loadInfo(option){
  switch(option){
  //OBTENER ETIQUETAS
    case 'mostrar_etiquetas':{
      var ajax3=nuevoAjax();
      ajax3.open("POST", "inc/etiquetas.php", true);
      ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");        
      ajax3.send("option="+option);
      
      ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4){
          //document.getElementById("grupos_trabajo_tabla").innerHTML='<img src="tpl/simple/img/loading_animation.gif" width="128" height="128" border="0"/>'
          document.getElementById("draggable_etiquetas").innerHTML=ajax3.responseText;
          //onChangeOptions('preparar-formulario-lista-grupos-trabajo','-2');
          initdraggable();
        }
      }
    } break;
    case 'buscar_etiquetas':{
      var ajax3=nuevoAjax();
      ajax3.open("POST", "inc/etiquetas.php", true);
      ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");        
      ajax3.send("option="+option);

      ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4){
          //document.getElementById("grupos_trabajo_tabla").innerHTML='<img src="tpl/simple/img/loading_animation.gif" width="128" height="128" border="0"/>'
          document.getElementById("resultado_busqueda").innerHTML=ajax3.responseText;
          //onChangeOptions('preparar-formulario-lista-grupos-trabajo','-2');
        }
      }

    } break;
    default:
  }
}

//ETIQUETAS ARRASTRABLES
  function initdraggable() {
    $("#DragWordList li").draggable({helper: 'clone'});
    $(".txtDropTarget").droppable({
      accept: "#DragWordList li",
      drop: function(ev, ui) {
        $(this).insertAtCaret(ui.draggable.text() + ', ');
      }
    });
  }

//ETIQUETAS ARRASTRABLES
  $.fn.insertAtCaret = function (myValue) {
    return this.each(function(){
    //IE support
    if (document.selection) {
      this.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    //MOZILLA / NETSCAPE support
    else if (this.selectionStart || this.selectionStart == '0') {
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+ myValue+ this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
    });
  }
