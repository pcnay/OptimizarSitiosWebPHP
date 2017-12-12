<?php
require_once "conexion.php";

function insertarHeroe($nombre,$imagen,$descripcion,$editorial)
{
  // Tabla "heroe" con sus respectivos campos
  // Si se coloca "0" en VALUES, MySQL automaticamente agrega el que sigue del número 
  // del id que esta en la tabla 
  $sql = "INSERT INTO heroes (id_heroe,nombre,imagen,descripcion,editorial) VALUES (0,'$nombre','$imagen','$descripcion',$editorial)";
  $mysql = conexionMySQL();
  if ($resultado=$mysql->query($sql)) // Si se ejecuto la consulta.
  {
    $respuesta = "<div class='exito' data-recargar>Se inserto con éxito el registro del SuperHeroe <b>$nombre</b></div>";
  }
  else
  {
    $respuesta = "<div class='error' >Ocurrio un error NO se inserto el registro del SuperHeroe <b>$nombre</b></div>";
  }
  $mysql->close();

  return printf($respuesta);
}
?>