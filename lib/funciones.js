function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function validarPerfilUsuario() {
	var forma = document.getElementById("articulos");
	var perfil	= forma.lista_perfiles.value;

	if (perfil == '0') {
		alert("El perfil es obligatorio.");
		forma.lista_perfiles.focus();
		return false;
	}
	
	return true;
}

function validarArticulo() {
	var forma = document.getElementById("articulo");
	var archivo = forma.archivo.value;
	var autores	= forma.autores.value;
	var titulo	= forma.titulo.value;
	var anno = forma.anno.value;

	if (archivo == "") {
		alert("Debe especificar un archivo.");
		forma.archivo.focus();
		return false;
	}
	
	if (autores == "") {
		alert("Debe especificar al menos un autor.");
		forma.autores.focus();
		return false;
	}

	if (titulo == "") {
		alert("El título del artículo es obligatorio.");
		forma.titulo.focus();
		return false;
	}
	
	if (anno == "") {
		alert("Debe especificar el año del artículo.");
		forma.anno.focus();
		return false;
	}	
	
	return true;
}

function validarSoftware() {
	var forma = document.getElementById("articulo");
	var archivo = forma.archivo.value;
	var titulo	= forma.titulo.value;

	if (archivo == "") {
		alert("Debe especificar un archivo.");
		forma.archivo.focus();
		return false;
	}

	if (titulo == "") {
		alert("La descripción es obligatoria.");
		forma.titulo.focus();
		return false;
	}
	
	return true;
}

function validarSoftwareEdicion() {
	var forma = document.getElementById("articulo");
	var titulo	= forma.titulo.value;

	if (titulo == "") {
		alert("La descripción es obligatoria.");
		forma.titulo.focus();
		return false;
	}
	
	return true;
}

function validarArticuloEdicion() {
	var forma = document.getElementById("articulo");
	var autores	= forma.autores.value;
	var titulo	= forma.titulo.value;
	var anno = forma.anno.value;

	if (autores == "") {
		alert("Debe especificar al menos un autor.");
		forma.autores.focus();
		return false;
	}

	if (titulo == "") {
		alert("El título del artículo es obligatorio.");
		forma.titulo.focus();
		return false;
	}
	
	if (anno == "") {
		alert("Debe especificar el año del artículo.");
		forma.anno.focus();
		return false;
	}	
	
	return true;
}

function validarGrupoTrabajo() {
	var forma = document.getElementById("grupos");
	var grupo	= forma.nombre_grupo.value;
	var etiquetas	= forma.etiquetas.value;

	if (grupo == "") {
		alert("Debe especificar el nombre del grupo.");
		forma.nombre_grupo.focus();
		return false;
	}

	if (etiquetas == "") {
		alert("Las etiquetas son obligatorias.");
		forma.etiquetas.focus();
		return false;
	}
	
	return true;
}

function validarPerfil(){
	var forma = document.getElementById("perfiles");
	var perfil	= forma.nombre_perfil.value;

	if (perfil == "") {
		alert("Debe especificar el nombre del perfil.");
		forma.nombre_perfil.focus();
		return false;
	}
	
	return true;
}

function validarBusquedaISSN(){
	var forma = document.getElementById("articulo");
	var issn	= forma.issn.value;

	if (issn == "") {
		alert("Debe especificar el ISSN del art\u00edculo.");
		forma.issn.focus();
		return false;
	}
	
	return true;
}

function validarEliminar() {
	var res = 0;

	for (i = 0; i < document.forms.length; i++)
		for (j = 0; j < document.forms[i].elements.length; j++)
			if (document.forms[i].elements[j].type == 'checkbox')
				if (document.forms[i].elements[j].checked)
					res++;
					
	if (res == 0) alert('Seleccione los archivos y/o carpetas a eliminar.');
					
	return res > 0;
}

function validarVinculo() {
	var forma = document.getElementById("articulo");
	var url = forma.url.value;
	var descr	= forma.descripcion.value;

	if (url == "") {
		alert("Debe especificar la dirección Web.");
		forma.url.focus();
		return false;
	}

	if (descr == "") {
		alert("La descripción es obligatoria.");
		forma.descripcion.focus();
		return false;
	}
	
	return true;
}

function nuevoAjax(){
	var xmlhttp=false; 
	try{ 
		// No IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}catch(e){ 
		try{ 
			// IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		}catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") { xmlhttp=new XMLHttpRequest(); } 
	return xmlhttp; 
}

function loadISSNContent(option){
	if(validarBusquedaISSN()){
		var forma = document.getElementById("articulo");
		var ajax3=nuevoAjax();
		ajax3.open("POST", "inc/cinii.php", true);
		ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				
		var v;
		var n;
		if(forma.volumen.value=="") v=0;	else v = forma.volumen.value;
		if(forma.numero.value=="")	n=0;	else n = forma.numero.value;
				
		//alert("option="+option+"&issn="+forma.issn.value+"&volume="+v+"&number="+n)
		ajax3.send("option="+option+"&issn="+forma.issn.value+"&volume="+v+"&number="+n);
				
		display("lgt",true);
		display("lga",true);
		display("lgv",true);
		display("lgn",true);
		display("lgp",true);
		display("lgr",true);
		display("lgau",true);
		//forma.lgl.style.display="";
				
		ajax3.onreadystatechange=function()	{
			if (ajax3.readyState==4){
				eval(ajax3.responseText);
				display("lgt",false);
				display("lga",false);
				display("lgv",false);
				display("lgn",false);
				display("lgp",false);
				display("lgr",false);
				display("lgau",false);
			}
		}
	}//fin de validar ISSN
}

function loadContent(option){
	var ajax3=nuevoAjax();
	ajax3.open("POST", "inc/simple_responses.php", true);
	ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			
	switch(option){
		//CARGAR TABLA CON ETIQUETA DE GRUPO DE TRABAJO
		case 'tabla-etiquetas-con-reload-combobox':{
			var forma = document.getElementById("grupos");
			
			ajax3.send("option="+option+"&idg="+forma.lista_grupos_trabajo.value);
			display("grupos_trabajo_tabla",true);
			document.getElementById("grupos_trabajo_tabla").innerHTML='<img src="tpl/simple/img/loading_animation.gif" width="128" height="128" border="0"/>'
			
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					document.getElementById("grupos_trabajo_tabla").innerHTML=ajax3.responseText;
				}
			}
		} break;
		//CARGAR COMBOBOX DE GRUPOS DE TRABAJO
		case 'combobox-grupos':{
			var forma = document.getElementById("grupos");
			
			resetPropiedades('textfields-grupos-trabajo');
			resetPropiedades('divs-grupos-trabajo');
			
			ajax3.send("option="+option);		
			display("imagen_combobox_grupo_trabajo",true);
		
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					document.getElementById("lista_grupos_trabajo").innerHTML=ajax3.responseText;
					display("imagen_combobox_grupo_trabajo",false);
				}
			}
		} break;
		//CARGAR TABLA CON ETIQUETA DE GRUPO DE TRABAJO
		case 'tabla-etiquetas-sin-reload-combobox':{
			var forma = document.getElementById("grupos");
			
			ajax3.send("option=tabla-etiquetas-con-reload-combobox&idg="+forma.lista_grupos_trabajo.value);
			
			display("grupos_trabajo_tabla",true);
			document.getElementById("grupos_trabajo_tabla").innerHTML='<img src="tpl/simple/img/loading_animation.gif" width="128" height="128" border="0"/>'
			
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					document.getElementById("grupos_trabajo_tabla").innerHTML=ajax3.responseText;
				}
			}
		} break;
		//CARGAR COMBOBOX DE PERFILES
		case 'combobox-perfiles':{
			resetPropiedades('textfields-perfiles');
			resetPropiedades('divs-perfiles');
			
			ajax3.send("option="+option);
			
			display("imagen_combobox_perfiles",true);
			
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					document.getElementById("lista_perfiles").innerHTML=ajax3.responseText;
					display("imagen_combobox_perfiles",false);
				}
			}
		} break;
		//CARGAR TABLA CON PERFILES 
		case 'tabla-perfiles-con-reload-combobox':{
			var forma = document.getElementById("perfiles");
		
			ajax3.send("option="+option+"&idp="+forma.lista_perfiles.value);
			
			display("perfiles_trabajo_tabla",true);
			document.getElementById("perfiles_trabajo_tabla").innerHTML='<img src="tpl/simple/img/loading_animation.gif" width="128" height="128" border="0"/>'
			
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					document.getElementById("perfiles_trabajo_tabla").innerHTML=ajax3.responseText;
				}
			}
		} break;
		//CARGAR TABLA CON PERFILES
		case 'tabla-perfiles-sin-reload-combobox':{
			var forma = document.getElementById("perfiles");
			
			resetPropiedades('textfields-perfiles');
			
			ajax3.send("option=tabla-perfiles-con-reload-combobox&idp="+forma.lista_perfiles.value);
			
			display("perfiles_trabajo_tabla",true);
			document.getElementById("perfiles_trabajo_tabla").innerHTML='<img src="tpl/simple/img/loading_animation.gif" width="128" height="128" border="0"/>'
			
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					document.getElementById("perfiles_trabajo_tabla").innerHTML=ajax3.responseText;
				}
			}
		} break;
		//CARGAR COMBOBOX DE PRINCIPAL
		case 'combobox-perfiles-principal':{
			if(document.getElementById("imagen_combobox_perfiles")){
				ajax3.send("option="+option);
			
				display("imagen_combobox_perfiles",true);
			
				ajax3.onreadystatechange=function()	{
					if (ajax3.readyState==4){
						document.getElementById("lista_perfiles").innerHTML=ajax3.responseText;
						display("imagen_combobox_perfiles",false);
					}
				}
			}
		} break;
		default:
	}//Termina switch
}//Termina loadContent

function services(option,id,exp1){
	var ajax3=nuevoAjax();
	ajax3.open("POST", "inc/simple_responses.php", true);
	ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			
	switch(option){
		//GENERAR Y ENVIAR CLAVE DE ACCESO NUEVA
		case 'generar-password':{
			var forma = document.getElementById("sesion");
									
			ajax3.send("option="+option+"&email="+forma.email.value);
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					alert("Se ha generado y enviado una nueva clave de acceso.");
				}
			}
		} break;
		//GUARDAR REGISTRO DE GRUPO DE TRABAJO
		case 'guardar-grupo-trabajo':{
			var forma = document.getElementById("grupos");
			
			validarGrupoTrabajo();
						
			ajax3.send("option="+option+"&nombre_grupo="+forma.nombre_grupo.value+"&etiquetas="+forma.etiquetas.value);
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					loadContent('combobox-grupos');
					display("tabla_exitosa",true);
				}
			}
		} break;
		//BORRAR ETIQUETA Y CARGAR TABLA CON ETIQUETAS DE GRUPO DE TRABAJO
		case 'borrar-etiqueta':{
			var forma = document.getElementById("grupos");
			
			ajax3.send("option="+option+"&id="+id);
			
			display("grupos_trabajo_tabla",true);
			document.getElementById("grupos_trabajo_tabla").innerHTML='<img src="tpl/simple/img/loading_animation.gif" width="128" height="128" border="0"/>'
			
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					display("tabla_exitosa",true);
					loadContent('tabla-etiquetas-con-reload-combobox');//ACTUALIZAR TABLA DE ETIQUETAS
				}
			}
		} break;
		//BORRAR GRUPO DE TRABAJO
		case 'borrar-grupo-trabajo':{
			ajax3.send("option="+option+"&id="+id);
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					loadContent('combobox-grupos');
					display("tabla_exitosa",true);
				}
			}
		} break;
		//BORRAR PERFIL
		case 'borrar-perfil':{
			ajax3.send("option="+option+"&id="+id);
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					loadContent('combobox-perfiles');
					display("tabla_exitosa",true);
				}
			}
		} break;
		//ACTUALIZAR NOMBRE DE GRUPO DE TRABAJO Y AGREGAR ETIQUTAS CAPTURADAS
		case 'actualizar-grupo-trabajo':{
			var forma = document.getElementById("grupos");
			
			ajax3.send("option="+option+"&id_grupo="+forma.lista_grupos_trabajo.value+"&nombre_grupo="+forma.nombre_grupo.value+"&etiquetas="+forma.etiquetas.value);
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					loadContent('tabla-etiquetas-sin-reload-combobox');
					forma.etiquetas.value="";
					display("tabla_exitosa",true);
				}
			}
		} break;
		//GUARDAR REGISTRO DE PERFIL
		case 'guardar-perfil':{
			var forma = document.getElementById("perfiles");
			
			validarPerfil();
			
			ajax3.send("option="+option+"&nombre_perfil="+forma.nombre_perfil.value);
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					loadContent('combobox-perfiles');
					display("tabla_exitosa",true);
				}
			}
		} break;
		//ACTUALIZAR PERFIL
		case 'actualizar-perfil':{
			var forma = document.getElementById("perfiles");
			ajax3.send("option="+option+"&id_perfil="+forma.lista_perfiles.value+"&nombre_perfil="+forma.nombre_perfil.value+"&crear_usuarios="+validateCheckBox('crear_usuarios')+"&crear_recursos="+validateCheckBox('crear_recursos')+"&actualizar_recursos="+validateCheckBox('actualizar_recursos')+"&borrar_recursos="+validateCheckBox('borrar_recursos')+"&configuracion_perfil="+validateCheckBox('configuracion_perfil')+"&configuracion_grupos="+validateCheckBox('configuracion_grupos'));		
			ajax3.onreadystatechange=function()	{
				if (ajax3.readyState==4){
					loadContent('tabla-perfiles-sin-reload-combobox');
					display("tabla_exitosa",true);
				}
			}
		} break;
		default:
	}//Termina switch
}//Termina services

function onChangeOptions(option,value){
	ocultarMensajes();
	switch(option){
		//lista_grupos_trabajo
		case 'preparar-formulario-lista-grupos-trabajo': {
			switch(value){
				//seleccionar opción
				case '0':{
					resetPropiedades('textfields-grupos-trabajo');
					resetPropiedades('divs-grupos-trabajo');
				}break;
				//registro nuevo
				case '-1':{
					resetPropiedades('textfields-grupos-trabajo');
					resetPropiedades('divs-grupos-trabajo');
				
					display("nombre_grupos",true);
					display("textfield_grupo",true);
					display("nombre_etiqueta",true);
					display("textfield_etiqueta",true);
					display("button_grupo_guardar",true);
				}break;
				//editar registro
				case '-2':{
					resetPropiedades('textfields-grupos-trabajo');
				
					display("nombre_grupos",true);
					display("textfield_grupo",true);
					display("nombre_etiqueta",true);
					display("textfield_etiqueta",true);
					display("button_grupo_actualizar",true);
				}break;
				default:{
					var forma = document.getElementById("grupos");
					
					display("nombre_grupos",true);
					display("textfield_grupo",true);
					display("nombre_etiqueta",true);
					display("textfield_etiqueta",true);
					display("button_grupo_guardar",false);
					display("button_grupo_actualizar",true);
					loadContent('tabla-etiquetas-con-reload-combobox');//CARGAR TABLA
					forma.nombre_grupo.value=getSelectedText('lista_grupos_trabajo');
				}
			}
		}break;
		//lista_perfiles
		case 'preparar-formulario-lista-perfiles': {
			switch(value){
				//seleccionar opción
				case '0':{
					resetPropiedades('textfields-perfiles');
					resetPropiedades('divs-perfiles');
				}break;
				//registro nuevo
				case '-1':{
					resetPropiedades('textfields-perfiles');
					resetPropiedades('divs-perfiles');
				
					display("nombre_perfiles",true);
					display("textfield_perfiles",true);
					display("button_perfil_guardar",true);
				}break;
				//editar registro
				case '-2':{
					resetPropiedades('textfields-perfiles');
				
					display("nombre_perfiles",true);
					display("textfield_perfiles",true);
					display("button_perfil_guardar",true);
				}break;
				default:{
					var forma = document.getElementById("perfiles");
					
					display("nombre_perfiles",true);
					display("textfield_perfiles",true);
					display("button_perfil_guardar",false);
					loadContent('tabla-perfiles-con-reload-combobox');//CARGAR TABLA
					forma.nombre_perfil.value=getSelectedText('lista_perfiles');
				}
			}
		}break;
		default:
	}
}

function ocultarMensajes(){
	display("tabla_exitosa",false);
	display("tabla_error",false);
}

function resetPropiedades(option){
	ocultarMensajes();
	switch(option){
		case 'textfields-grupos-trabajo':{
			var forma = document.getElementById("grupos");
			//-------- Limpiar campos
			forma.nombre_grupo.value="";
			forma.etiquetas.value="";
			//-------- Termina limpiar campos
		}break;
		case 'divs-grupos-trabajo':{
			//-------- Oculta divs/campos
			display("imagen_combobox_grupo_trabajo",false);
			
			display("nombre_grupos",false);
			display("textfield_grupo",false);
			
			display("nombre_etiqueta",false);
			display("textfield_etiqueta",false);
			
			display("button_grupo_guardar",false);
			display("button_grupo_actualizar",false);
			
			display("grupos_trabajo_tabla",false);
			//-------- Termina limpiar campos
		}break;
		case 'textfields-perfiles':{
			var forma = document.getElementById("perfiles");
			//-------- Limpiar campos
			forma.nombre_perfil.value="";
			//-------- Termina limpiar campos
		}break;
		case 'divs-perfiles':{
			//-------- Oculta divs/campos
			display("imagen_combobox_perfiles",false);
			
			display("nombre_perfiles",false);
			display("textfield_perfiles",false);
						
			display("button_perfil_guardar",false);
			
			display("perfiles_trabajo_tabla",false);
			//-------- Termina limpiar campos
		}break;
		default:
		}
	}
	
//Funciones varias	
function display(id,option){
	(option==true)?document.getElementById(id).style.display="":document.getElementById(id).style.display="none";
}

function getSelectedText(elementId) {
    var elt = document.getElementById(elementId);

    if (elt.selectedIndex == -1)
        return null;

    return elt.options[elt.selectedIndex].text;
}

function validateCheckBox(checkButtonName){
	if(eval("document.getElementById('"+checkButtonName+"').checked"))
		return 1;
	else
		return 0;
}

function mascara(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}