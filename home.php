<?php


/* insertando el header y la navbar */

require_once 'header.php';


if (!strpos($page, '.') && !strpos($page, '/')) // valida si se encuentra algun caracter especial en la variable, en caso de no tener, indicaria que la pagina es correcta.
{
    // si la variable tiene un valor que exista en los archivos creados.
  if (file_exists($page.'.php'))
  {
     // incluye la extensión .php
      include ($page.'.php');
  }
  else{
      include ('error.php'); //caso contrario despliega error 404.
  }
  
}
else {
    include ('error.php'); //caso contrario despliega error 404.
}




require_once 'footer.php'; // incluyendo el pie de pagina en todas las paginas.


?>