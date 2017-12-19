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
  
  function listaEditoriales()
  {  
    $mysql = conexionMySQL();
    $sql = "SELECT * FROM editorial";
    $resultado = $mysql->query($sql);

    //Obtendra la lista de las editoriales en la etiqueta "Select"
    $lista = "<select id='editorial' name = 'editorial_slc' required>";
    $lista .= "<option value=''>- - -</option>";
    // Traer los datos de la tabla de editoriales, llamandolos por su nombre de campo.
    while ($fila = $resultado->fetch_assoc())
    { 
      // $lista .= "<option value = '".$fila["id_editorial"]."'>".$fila["editorial"]."</option>"; 
      // Otra forma de hacerlo.
      // Este comenado lo convierte a formato de cadena para seguir la concatenacion.
      $lista .= sprintf("<option value = '%d'>%s</option>",$fila["id_editorial"],$fila["editorial"]);
    }
    $lista .= "</select>";
    
    $resultado->free();
    $mysql->close();

    // No se requiere con "printf" ya que esta formateada con el Formulario y esta si retorna
    // formateada la cadena.
    return $lista;
  }

  // Se inicia la captura para registrarlo en la Tabla de "super_heroes" de la Base De Datos
  function capturaHeroe()
  {
    // Se crea de forma dinámica el "formulario HTML" de la captura del Super Heroe.
    // Se agrega "data-insertar"; es un atributo de HTML, se debe anteponer la palabra "data"
    $form = "<form id = 'alta-heroe' class = 'formulario' data-insertar>";
      $form .= "<fieldset>";
        $form .= "<legend>Alta De Super Héroes : </legend>";
        $form .= "<div>";
          $form .= "<label for = 'nombre'>Nombre:</label>";
          // name = es para el backend es como lo renoce, en javascript es id para desencadenar prog.
          $form .= "<input type='text' id = 'nombre' name = 'nombre_txt' required />";
        $form .= "</div>";  
        $form .= "<div>";
          $form .= "<label for = 'imagen'>Imagen:</label>";
          $form .= "<input type='text' id = 'imagen' name = 'imagen_txt' required />";
        $form .= "</div>";  
        $form .= "<div>";
          $form .= "<label for = 'descripcion'>Descripcion:</label>";
          $form .= "<textarea id = 'descripcion' name = 'descripcion_txa' required ></textarea>";
        $form .= "</div>";  
        $form .= "<div>"; 
          $form .= "<label for = 'editorial'>Editorial:</label>";
          $form .= listaEditoriales(); // Se asigna el ComboBox(etiqueta Select).
        $form .= "</div>";  
        $form .= "<div>";
          
          
          // Este se envia al controlador, para ejecutar la instruccion .
          $form .= "<input type='submit' id='insertar-btn' name = 'insertar_btn' value= 'Insertar' />";  
          // El usuario no ve en la interfaz formulario., se envian parametro oculto al Backend PHP
          // value = 'insertar' se envia al Controlador, cuando el usuario oprime el boton Insertar
          // y ejecuta lo definido en la condicional "insertar". Se manda al controlador para que 
          // indique que accion tomar en este caso "transaccion=insertar"
          $form .= "<input type='hidden' id='transaccion' name = 'transaccion' value= 'insertar' />";                  
        $form .= "</div>";  
      $form .= "</fieldset>";
    $form .= "</form>";
    
    // Al retornar lo va a desplegar en pantalla.
    return printf($form);
  }
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
        // Para desplegar el nombre que le corresponde cuando se despliega en pantalla.
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
      $totalRegistros = mysqli_num_rows($resultado);

      if ($totalRegistros==0)
      {
        $respuesta ="<div class='error'>No existen registros de SuperHeroes </div>";
      }
      else
      {
        //Inicia la Paginacion.
        //1.- Limitar la consulta   
                $regXPag = 3; // Cuantos mostrar por página.        
                $pagina = false; // Cuando entra el usuario a la pagina  
                // Examinar la página a mostrar y el inicio del reg. a mostrar.        
                if (isset($_GET["p"]))        
                {        
                  $pagina = $_GET["p"];        
                }         
                if (!$pagina)
                {
                  $inicio = 0;
                  $pagina = 1; 
                }
                else // Moviendo a la pagina 2,3,4,...
                {
                  $inicio=($pagina-1)*$regXPag;
                }       

                // Calcular el total de páginas.
                $totalPaginas = ceil($totalRegistros/$regXPag); // sube valor fraccionario al sig. entero
               
                // Redifinir la consulta para que muestre solo los resultados.
                $sql .= " LIMIT ".$inicio.",".$regXPag;
                $resultado=$mysql->query($sql);
        
                //Escribiendo el codigo para mostrar los numeros de la paginacion.
                $paginacion = "<div class='paginacion'>";
                  $paginacion .= "<p>";
                    $paginacion .="Número de resultados : <b>$totalRegistros</b>"; 
                    $paginacion .= "Mostrando <b>$regXPag</b> resultados por página";
                    $paginacion .= "Página <b>$pagina</b> de <b>$totalPaginas</b>";
                  $paginacion .= "</p>";
                  if ($totalPaginas>1)
                  {
                    $paginacion .= "<p>";
                    // Para habilitar el boton de ir a al Izq.                      
                      $paginacion .= ($pagina !=1)?"<a href='?p=".($pagina-1)."'>&laquo</a>":""; // Despliega símbolo flechas izq.
                      // Generando los números de las páginas.
                      for ($i=1;$i<=$totalPaginas;$i++)
                      {
                        // Modificando para cuando se encuentre en la página actual y se selecciona de 
                        // nuevo no se recarga de nuevo, es decir se oprime 4 y se vuelve a oprimir 4
                        // no se recarga nuevamente.
                        // Si muestro el indice de la pagina actual, no se coloca enlace
                        $actual = "<span class='actual'>$pagina</span>";
                        // Si el indice no corresponde con la pagina mostrada actualmente, coloco
                        // el enlace para ir a esa pagina.
                        $enlace = "<a href='?p=$i'>$i</a>";// Despliega los números de página.
                        $paginacion .= ($pagina == $i)?$actual:$enlace;// "<a href='?p=$i'>$i</a>"; 
                      }
                      // Para habilitar el boton de ir a la Der.                                            
                      $paginacion .= ($pagina != $totalPaginas)?"<a href='?p=".($pagina+1)."'>&raquo</a>":"";// Despliega símbolo flechas derecha                    $paginacion .= "</p>";
                  } 
                $paginacion .= "</div>";
                
                //  echo $sql."<br/>".$totalPaginas;            
        
                // ************* Termina la paginacion.
        
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

              $tabla .= "<td><a href='#' class='editar' data-id='".$fila['id_heroe']."'>Editar</a></td>";
              // Modificacion para borrar registros 
              $tabla .= "<td><a href='#' class='eliminar' data-id='".$fila['id_heroe']."'>Eliminar</a></td>";
              // Se agrega "class" en lugar de "id" ya que se repiten y no puede ser único.
              // Adicionalmente se agrega un atributo "data-id" para asignar el "id_heroe" y 
              // poderlo hacer único.
              // Por buenas prácticas no se puede empezar con número una id en CSS, javascrip, HTML.
            $tabla .= "</tr>";
          }
          // Libera espacio de memoria de la variable de la clase conexion.
          // Se debe realizar cada vez que se termine de ejecutar.
          $resultado->free();

          $tabla .= "</tbody>";
        $tabla .= "</table>";

        // Se asigna la tabla creada dinámicamente para que la función la retorne.
        $respuesta = $tabla.$paginacion;

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

  // *************************************************************************
  // ACTUALIZAR REGISTROS .
  // *************************************************************************

  // Para extraer el nombre de la editorial y dejarlo seleccionado en el combobox "Select"
  function listaEditorialesEditada($idEditorial)
  {
    $mysql = conexionMySQL();
    $sql = "SELECT * FROM editorial";
    $resultado = $mysql->query($sql);

    //Obtendra la lista de las editoriales en la etiqueta "Select"
    $lista = "<select id='editorial' name = 'editorial_slc' required>";
    $lista .= "<option value=''>- - -</option>";
    // Traer los datos de la tabla de editoriales, llamandolos por su nombre de campo.
    while ($fila = $resultado->fetch_assoc())
    { 
      // Busca con el "id" el nombre de la editorial, y la modif. atributo "selected"
      $selected = ($idEditorial==$fila["id_editorial"])?"selected":''; 

      // Este comenado lo convierte a formato de cadena para seguir la concatenacion.
      $lista .= sprintf("<option value = '%d' $selected >%s</option>",$fila["id_editorial"],$fila["editorial"]);

      /*
      Otra forma de hacerlo:
        $lista = sprintf("<option value = '%d'",$fila["id_editorial"]);
        if ($idEditorial== $fila["id_editorial"])
        {
          $lista .=sprintf("selected");
        }
        $lista .= sprintf(">%s</option>",$fila["editorial"]);

      */
    }
    $lista .= "</select>";
    
    $resultado->free();
    $mysql->close();

    // No se requiere con "printf" ya que esta formateada con el Formulario y esta si retorna
    // formateada la cadena.
    return $lista;

  }
  // Editar Heroe para realizar la modificacion.
  function editarHeroe($idHeroe)
  {
    // Obtiene el registro del idHeroe.
    $mysql = conexionMySQL();
    $sql = "SELECT * FROM heroes WHERE id_heroe = $idHeroe ";

    if ($resultado = $mysql->query($sql))
    {
      // Se esta asignando a la variable "$fila"(Arreglo) el registro en formato para que se obtengan
      // los campos a traves de su nombre de campo.
      $fila = $resultado->fetch_assoc();

      // Muestro el "Form" con los datos del registro.
      // Se crea de forma dinámica el "formulario HTML" para la edicion del Super Heroe.
       // Se agrega "data-editar"; es un atributo de HTML, se debe anteponer la palabra "data"
       $form = "<form id = 'editar-heroe' class = 'formulario' data-editar>";
        $form .= "<fieldset>";
          $form .= "<legend>Edicion De Super Héroes : </legend>";
          $form .= "<div>";
            $form .= "<label for = 'nombre'>Nombre:</label>";
            // name = es para el backend es como lo renoce, en javascript es id para desencadenar prog.
            $form .= "<input type='text' id = 'nombre' name = 'nombre_txt' value='".$fila['nombre']."' required />";
          $form .= "</div>";  
          $form .= "<div>";
            $form .= "<label for = 'imagen'>Imagen:</label>";
            $form .= "<input type='text' id = 'imagen' name = 'imagen_txt' value='".$fila['imagen']."'required />";
          $form .= "</div>";  
          $form .= "<div>";
            $form .= "<label for = 'descripcion'>Descripcion:</label>";
            $form .= "<textarea id = 'descripcion' name = 'descripcion_txa' required >".$fila['descripcion']."</textarea>";
          $form .= "</div>";  
          $form .= "<div>"; 
            $form .= "<label for = 'editorial'>Editorial:</label>";
            $form .= listaEditorialesEditada($fila["editorial"]); // Se asigna el ComboBox(etiqueta Select).
          $form .= "</div>";  
          $form .= "<div>";
            
            
            // Este se envia al controlador, para ejecutar la instruccion .
            $form .= "<input type='submit' id='actualizar' name = 'actualizar_btn' value= 'Actualizar' />";  
            // El usuario no ve en la interfaz formulario., se envian parametro oculto al Backend PHP
            // value = 'insertar' se envia al Controlador, cuando el usuario oprime el boton Insertar
            // y ejecuta lo definido en la condicional "insertar". Se manda al controlador para que 
            // indique que accion tomar en este caso "transaccion=insertar"
            $form .= "<input type='hidden' id='transaccion' name = 'transaccion' value= 'actualizar' />";

            // Se adiciona y que se le indica que "id" del heroe se modificara.
            $form .= "<input type='hidden' id='idHeroe' name = 'idHeroe' value= '".$fila['id_heroe']."' />";
          $form .= "</div>";  
        $form .= "</fieldset>";
       $form .= "</form>";

       $resultado->free();
    }
    else
    {
      // Se muestra un mensaje de error.
      $form ="<div class='error'>Error : No se ejecuto la consulta a la Base De Datos </div>";
    }
    $mysql->close();
    
  
    // Al retornar lo va a desplegar en pantalla.
    return printf($form);    
  }

?>