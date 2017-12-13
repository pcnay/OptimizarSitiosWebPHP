<?php
  // Con estas inclusiones ya se tiene idea de que función tiene el archivo, segun en el 
  // diagrama.
  require "vistas.php";
  require "modelo.php";
  
  // Esta viene desde : ajax.open("POST","controlador.php");
  $transaccion = $_POST["transaccion"];

  // Esta retornando en el "response" del Ajax
  // echo $transaccion."Texto de prueba agregado";


  /*
  Aplicacion CRUD = Create, Read, Updata, Delete
  PHP tiene dos métodos (GET=envio URL, POST=envio por el encabezado) de envío de información 
  Create    Afecta BD       INSERT    (SQL)   POST      Modelo.php
  Read      No afecta BD    SELECT    (SQL)   GET       Vista.php
  Update    Afecta BD       UPDATE    (SQL)   POST      Modelo.php
  Delete    Afecta BD       DELETE    (SQL)   POST      Modelo.php
  */
  // Esta función ejecutará las vistas o modelos de la aplicación.
  function ejecutarTransaccion($transaccion)
  {
    if ($transaccion == "alta")
    {
      // Mostrar el formulario de alta.
      // Se llama la funcion desde este archivo"controlador.php" ya que es el que indica
      // a donde se debe ir, en este caso en el archivo "vistas.php" se define esta función.
      capturaHeroe();
    }
    else if ($transaccion == "insertar")
    {
      // Procesar los datos del formulario de Alta e insertarlos en MySQL
      // La variable del Input "hidden" de vistas.php se utilizo para esta condicion.
      // Esta funcion se define en "modelo.php".
      insertarHeroe(
        $_POST["nombre_txt"],
        $_POST["imagen_txt"],
        $_POST["descripcion_txa"],
        $_POST["editorial_slc"]
      );
    }
    else if ($transaccion == "eliminar")
    {
      // Eliminar de MySQL el registro solicitado.
      eliminarHeroe($_POST["idHeroe"]);
    }
    else if ($transaccion == "editar")
    {
      // Traer los datos del reg. a modificar en un formulario.
    }
    else if ($transaccion == "actualizar")
    {
      // Modificar en MySQL los datos del regisro modificado.
    }
  }

  ejecutarTransaccion($transaccion); // Es necesaria esta función para que retorne informacion
  // en el AJAX (responseText).

?>
