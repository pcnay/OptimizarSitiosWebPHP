<?php 
/*
  include = Muestra un mensaje de "Warning", y continua la ejecución del programa.
  include_once = Solamente invoca una sola vea el archivo.
  require = Muestra error fatal y no continua la ejecución del programa. Es mejor esta opcion.
  require_once = Solamente invoca una sola vea el archivo, para este caso se aplica.
*/

require_once "config.php";

// Funcion para conectarse a la base de datos.
function conexionMySQL()
{
  // Utilizando la clase "mysqli"
  $conexion = new mysqli(SERVER,USER,PASS,BD);

  // Ejecutando un método de la clase "mysqli"
  if ($conexion->connect_error)
  {
    // No mezclar código PHP con HTML.
    $error = "<div class = 'error' ></div>";
      $error .= "Error de Conexion No. <b> ".$conexion->connect_errno." </b> Mensaje del Error : <mark>".$conexion->connect_error."</mark>";
      $error .= "</div>";

      // Parar la ejecución del programa.
    die ($error);
  }
  else
  {
    
    // http://php.net/manual/es/class.mysqli.php
    // Evitando el uso del comando "echo".
    // $formato = "<div class 'mensaje'>Conexion exitosa".$conexion->host_info."</div>"
    // echo $formato;

    // Funciones para imprimir formatos de PHP.
    // http://www.php.net/manual/es/function.printf.php
    // http://www.php.net/manual/es/function.sprintf.php
    // $formato = "<div class 'mensaje'>Conexion exitosa <b>%s</b></div>";
    // printf($formato,$conexion->host_info);

  }

  // Para desplegar acentos, ñ, Ñ, 
  $conexion->query("SET CHARACTER SET UTF8");

  return $conexion;

}


// conexionMySQL();

?>
