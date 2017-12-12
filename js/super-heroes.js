// DECLARACION DE CONSTANTES

// Los estados del Ajax.
var READY_STATE_COMPLETE = 4;
// DECLARACION DE VARIABLES
var ajax = null;
var btnInsertar = document.querySelector("#insertar");
// Este DIV se colocara la imagen de precarga (archivo.gif) cuando se haga pa peticion al servidor
var precarga = document.querySelector("#precarga");
// Es donde se colocara las respuesta por parte del Despachador Ajax
var respuesta = document.querySelector("#respuesta");

// DECLARACION DE OBJETOS

// DECLARACION DE FUNCIONES


// En esta funcion se inicia a trabajar con la lógica de la aplicación.
// Se ejecutaran las operaciones CRUD,

function insertarHeroe(evento)
{
  evento.preventDefault();
  // alert("Procesa Formulario ");
  
  // Se obtiene la información del formulario de captura.
  // Para obtener las etiquetas hijos del formulario, se utiliza el parámetro "evento"
  // dado que este es generado por el "addEventListener" y evento "submit"
  //console.log(evento);
  // Si se inspecciona el objeto, en el apartado "target" se mostrara las etiquetas hijos
  // "Form", que este caso es "evento.target" que es el evento que lo origino. 
  //console.log(evento.target); // El evento que origina el objeto "Form"
  //console.log(evento.target.length); // Las etiquetas hijos del "Form "
  //console.log(evento.target[0]); // Muestra la primer etiqueta hija del formulario.
// Ahora se obtendra la etiqueta y valor de los componentes hijos de "form".
// Que esta información se obtuvo de "evento.target->target".

    var nombre = new Array();
    var valor = new Array();
    var datos = "";
    var hijosForm = evento.target;

    // Se omite la etiqueta "field", por lo que se comienza en 1.
    for (var i=1;i<hijosForm.length;i++)
    {
      nombre[i] = hijosForm[i].name; // Solo se obtiene el atibuto "name" de la etiqueta.
      valor[i] = hijosForm[i].value; // Solo se obtiene el "valor" de la etiqueta.
      datos += nombre[i]+"="+valor[i]+"&";
      //console.log(datos);
    }

  ejecutarAJAX(datos);
}

function enviarDatos()
{
  precarga.style.display = "block"; // Oculta.
  precarga.innerHTML = "<img src='img/loader.gif' />";
  
  // Revisa el estado del Ajax si se ejecuto correctamente.
  // cuando hay terminado la comunicacion con el servidor.
  if  (ajax.readyState == READY_STATE_COMPLETE)
  {
    // En esta línea viene la mágia de Ajax.
    // Ahora compara el estado del HTTP.
    if (ajax.status == 200)
    {
      // alert ("WIIIIIII");
      precarga.innerHTML = null; // Para que desaparezca el "loader.gif".
      precarga.style.display = "none"; // Desaparezca el "DIV" del "loder.gif"
      // Ahora es indicar al DIV de respuesta que se encuentra en el archivo "index.php"
      // que se muestre el DIV y se le asigna el la "form" que se creo en el Ajax.
      respuesta.style.display = "block"; // Que se vea el "DIV" respuesta.
      // Asigna el valor retornado por AJAX, es decir lo que retorna la funcion "capturaHeroe"
      // del archivo "vistas.php"
      respuesta.innerHTML = ajax.responseText; // Desplega la tabla.

      
      // Se inicia con la definicion de las funciones CRUD  
      // Se inicia con la insercion del los super heroes a la tabla de la Base de datos.
      // indexOf() = Si se encuentra en la cadena de "responseText", si no lo encuentra retorna -1
      if (ajax.responseText.indexOf("data-insertar")>-1)      
      {
        // Es el nombre del formulario de captura. Para agregar la funcion de "submit" que
        // tiene por defecto los formularios cuando se oprimen el boton de "submit", pero
        // en esta ocacion se agrega a través de JavaScript.
        document.querySelector("#alta-heroe").addEventListener("submit",insertarHeroe);
      }
      // Una vez que haya insertado el registro
      // Tendrá que desplegar el mensaje esperar unos segundos y recarga la página.
      // Se tiene otra respuesta ya que se ejecuta de nuevo la funcion "ejecutarAJAX" 
      if (ajax.responseText.indexOf("data-recargar")>-1)
      {
       // Espera 3 segundos antes de recargar la página.
        setTimeout(function(){window.location.reload()},3000);
      } 

    }
    else
    {
      // "\n" porque en Alert no se puede agregar código HTML
      alert ("El servidor No contesto \n Error "+ajax.status+": "+ajax.statusText);      
    }
    // console.log(ajax);
  }
}

// Con esta funcion se inicia el AJAX. Siempre se debe ejecutar para empezar.
function objetoAJAX()
{
  if (window.XMLHttpRequest) // Para todos los demas navegadores.
  {
    return new XMLHttpRequest();
  }
  else if(window.ActiveXObject) // Detecta el navegador de Internet Explorer
  {
    return new ActiveXObject("Microsoft.XMLHTTP");
  }
  
}

function ejecutarAJAX(datos)
{
  // Determinar con cual AJAX trabajar de Internet Explorer o los demás.
  ajax = objetoAJAX();
  // Se ejecuta cuando existe un cambio de estado en el AJAX y ejecutara la funcion.
  // Se ejecuta si se encuentra el archivo "controlador.php" en el Backend ( PHP )
  ajax.onreadystatechange = enviarDatos;
  // Que archivo se abrira, en el backend que se va interactuar.
  // POST= Se va oculto por la cabecera, GET = SE va por la URL.
  // Este archivo "controlador.php" se le pasaran los datos de "transaccion=alta"
  ajax.open("POST","controlador.php");
  // Como se manejara información a través de un formulario
  // https://es.wikipedia.org/wiki/Multipurpose_Internet_Mail_Extensions
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  ajax.send(datos);

}

function altaHeroe(evento)
{
  var datos;
  // Previene la ejecucion por defecto del evento en cuestion, en este caso es el "click"
  // No se posicion en 0,0 de la pantalla
  // No despliega el símbolo "#" en la URL.
  evento.preventDefault(); 

  // Se refiere a lo que se realizara en la base de datos.
  datos = "transaccion=alta";
  ejecutarAJAX(datos);
}

function alCargarDocumento()
{
  btnInsertar.addEventListener("click",altaHeroe)
}

// DECLARACION DE ENVENTOS
window.addEventListener("load",alCargarDocumento);

