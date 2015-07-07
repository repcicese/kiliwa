//LLAVE PARA ENVIO DE ARCHIVOS
  uptime = new Date().getTime();
  function obtenerUptime(){
    document.getElementById('uptime').value = uptime;  
  }
//BOTON UPLOADIFY
  $(function() {
    $('#file_upload').uploadify({
      'swf'      : 'lib/uploadify/uploadify.swf',
      'uploader' : 'lib/uploadify/uploadify.php',
      'onUploadError' : function(file, errorCode, errorMsg, errorString) { 
          alert('El archivo ' + file.name + ' no se ha podido enviar: ' + errorString);
      },
      'onUploadStart' : function(file) {
        $("#file_upload").uploadify("settings", 'formData',{"size": file.size, "timestamp" : uptime})
      },
      'onQueueComplete' : function(queueData) {
        $('#submit_data').removeAttr('disabled');
      }
    });
  });