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

  return printf($respuesta); // Valor nuevo retornado para "ajax.responseText". 
      // Para cuando lo lea la función "enviarDatos()" ejecute la siguiente condición.
    
}

function eliminarHeroe($id)
{
  $sql = "DELETE FROM heroes WHERE id_heroe=$id";
  $mysql = conexionMySQL();
  if ($resultado=$mysql->query($sql)) // Si se ejecuto la consulta.
  {
    $respuesta = "<div class='exito' data-recargar>Se elimino con exito e reg. del super heroe con id: <b>$id</b></div>";
  }
  else
  {
    $respuesta = "<div class='error' >Ocurrio un error no se elimino el reg. del super heroe con id: <b>$id</b></div>";
  }
  $mysql->close();

  return printf($respuesta); // Valor nuevo retornado para "ajax.responseText". 
      // Para cuando lo lea la función "enviarDatos()" ejecute la siguiente condición.
    
}
?>