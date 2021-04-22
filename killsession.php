<?php

session_name("tc_shop");
session_start();
include('includes/connection.php');
include('includes/functions.php');

session_destroy();
 header( 'Location: index.php' ) ;
?>