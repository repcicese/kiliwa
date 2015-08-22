# kiliwa
cicese 

MI PRIMERA MODIFICASION
mis acciones

INSTALACIÓN DE LA BASE DE DATOS POR MEDIO DE PHPMYADMIN:

a.	Crear una base de datos con el nombre que usted guste.

b.	Una vez estando en la base de datos que ha creado en  PhpMyAdmin, se muestran varias pestañas, seleccionar la pestaña que dice importar.

c.	Al hacer esto se desplegara un formulario, donde dice Archivo a Importar, dar clic en el botón examinar y buscar el archivo sql y seleccionarlo.
d.	Para terminar dar clic en Continuar y esperar a que se realice la importación de la base datos, este proceso suele durar algunos minutos.

ARCHIVOS A MODIFICAR CUANDO SE REALICE UN CAMBIO DE SERVIDOR DEL SISTEMA:

1.	Descarga el archivo en github y descomprimir con winrar o cualquier otro descompresor  de archivos; para ver las carpetas de kiliwa por de faul el sistema lo descarga así kiliwa-master hay que eliminar la terminación (-master).
 
2.	Localizar la carpeta cfg y abrir para ver el contenido de dicha carpeta.

3.	Dentro de la carpeta debe haber los siguientes archivos: general. Abrir el archivo general.php con un editor de HTML, código PHP o editor de texto de formato básico.

4.	Localizar las líneas 3,4 y 5.

5.	Línea 3 contiene: define(' $mysql_usuario', 'Nombre de Usuario de la Base de Datos'); de igual manera se modifica el segundo par de comillas simples por el nombre del Usuario que se le ha asignado a la base de datos.

6.	Línea 6 contiene: define(' $mysql_pword', 'Contraseña del usuario de Base de Datos'); modificar el contenido del segundo par de comillas simples por la contraseña asignada al usuario de la base datos.
7.	Línea 5 contiene: define (' $mysql_bdatos', 'Nombre de Base de Datos'); se modificara el contenido del segundo par de comillas simples por el nombre de la base de datos.

8.	Guardar el archivo general.php.

9.	Salir de la Carpeta cfg.


