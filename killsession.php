<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();
include('includes/connection.php');
include('includes/functions.php');

session_destroy();
 header( 'Location: index.php' ) ;
 
?>