<?php
  // Contiene la pantallas a mostrar en la aplicación Web de Super Heroes.
  require_once "conexion.php";


  /*
  Pasos para conectarse a una base de datos en PHP
  1.- Objeto de Conexio : $mysql = conexionMySQL();
  2.- Sentencia para consulta SQL : $sql = "SELECT * FROM heroes ORDER BY id_heroe DESC";
  3.- Ejecutar la Consulta : $resultado=$mysql->query($sql)
  4.- Mostrar Resultados : $fila = $resultado->fetch_assoc

  */
  // Extraer las editoriales dependiendo del "id" editorial.
  function catalogoEditoriales()
  {
    $editoriales = Array();

    $mysql = conexionMySQL();
    $sql = "SELECT *FROM editorial";

    if ($resultado = $mysql->query($sql))
    {
      while($fila = $resultado->fetch_assoc())
      {
        // Asigna el valor de acuerdo al "id" de la editorial.
        $editoriales[$fila["id_editorial"]] =  $fila["editorial"];
      }
      $resultado->free();

    }
    $mysql->close();
    // Se utiliza para analizar como esta estructurado un objeto en PHP
    // print_r($editoriales);
    return $editoriales;
  }

  function mostrarHeroes()
  {
    // Se asigna a una variable el arreglo donde la funcion obtiene las editoriales con la 
    // consulta a la base de datos.
    $editorial = catalogoEditoriales();

    $mysql = conexionMySQL();
    $sql = "SELECT * FROM heroes ORDER BY id_heroe DESC";
    // Si se ejecuta devuelve un verdadero. Es solo una ejecución de línea de código. 
    if($resultado=$mysql->query($sql))
    { 
      // Determina si hay registros en la tabla de SuperHeroes  
      if (mysqli_num_rows($resultado)==0)
      {
        $respuesta ="<div class='error'>No existen registros de SuperHeroes </div>";
      }
      else
      {
        // Se creara una tabla de forma dinámica, donde muetra la información del SuperHeroe.
        $tabla = "<table id='tabla-heroes' class='tabla'>";
        // Definiendo la cabecera de la tabla.
          $tabla .= "<thead>";
            // Definiendo el primer renglon del encabezado de la tabla.      
            $tabla .= "<tr>";
            // Definiendo la columna del encabezado de la tabla.
              $tabla .= "<th>Id Heroé</th>";
              $tabla .= "<th>Nombre</th>";
              $tabla .= "<th>Imagén</th>";
              $tabla .= "<th>Descripcion</th>";
              $tabla .= "<th>Editorial</th>";
              $tabla .= "<th></th>";
              $tabla .= "<th></th>";
            $tabla .= "</tr>";
          $tabla .= "</thead>";
          // En esta seccion del programa se desplegaran los registros de los Super Heroes.
          $tabla .= "<tbody>";
          // Se genera otra fila por cada registro de super Heroe.
          // "fetch_assoc()" = Permite extraer el contenido de cada reg. por su nombre de campo.
          // "fetch_row()" = Arreglo posiciones, es la posicion que ocupa el campo del registro.
          // "fila[0]", en lugar de "fila['id_heroe']". 
          // "fila" = Es un arreglo de datos de la fila en cuestion de cada ciclo del while
          
          while ($fila = $resultado->fetch_assoc()) 
          {
            $tabla .= "<tr>";
              $tabla .= "<td>".$fila['id_heroe']."</td>";
              $tabla .= "<td><h2>".$fila['nombre']."</h2></td>";
              // Se agrega la etiqueta "img" para mostrar la imagen, ya que se le escribe el 
              // nombre de la imagen.
              $tabla .= "<td><img src='img/".$fila['imagen']."'/></td>";
              $tabla .= "<td><p>".$fila['descripcion']."</p></td>";
              // Se agrega una funcion para extraer el nombre de la editorial en lugar del "id"
              $tabla .= "<td><h3>".$editorial[$fila['editorial']]."</h3></td>";
              $tabla .= "<td>Boton Editar</td>";
              $tabla .= "<td>Boton Eliminar</td>";
            $tabla .= "</tr>";
          }
          // Libera espacio de memoria de la variable de la clase conexion.
          // Se debe realizar cada vez que se termine de ejecutar.
          $resultado->free();

          $tabla .= "</tbody>";
        $tabla .= "</table>";

        // Se asigna la tabla creada dinámicamente para que la función la retorne.
        $respuesta = $tabla;

      }
    }
    else
    {
      $respuesta ="<div class='error'>Error : No se ejecuto la consulto a la Base De Datos </div>";
    }

    // Una vez terminado de ejecutar cerrar la conexion.
    $mysql->close();

    // No se requiere sustitucion de un valor alguno. 
    return printf($respuesta);
  }
?>