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
      alert ("WIIIIIII");
      console.log(ajax);
    }
    else
    {
      alert ("NOOOOOOOOOOOOOOOO");
      console.log(ajax);
    }
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

