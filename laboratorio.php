<?
/*
//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('./includes/connection.php');
include('./includes/functions.php');
*/
?>  
  <!-- ============================================================== -->
            <div class="page-wrapper">
                <!-- ============================================================== -->
                <!-- Container fluid  -->
                <!-- ============================================================== -->
                <div class="container-fluid">
                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor"><?=ucwords($page)?></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                                    <li class="breadcrumb-item active"><?=ucwords($page)?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <?php

                                    /*

                                    $password = 1234;

                                    $passHash = password_hash($password, PASSWORD_BCRYPT);

                                    qry("UPDATE usuarios SET password = '$passHash' WHERE id = 33");

                                    $query = "SELECT confirmacion_prospectos.id, numero_contacto_confirmacion, prospectos.id_responsable_interno, prospectos.id_usuario_responsable, usuarios.cliente_gane, usuarios.numero_contacto FROM confirmacion_prospectos LEFT JOIN prospectos ON prospectos.id_confirmacion = confirmacion_prospectos.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id WHERE usuarios.cliente_gane = 1 AND numero_contacto_confirmacion <> '' AND numero_contacto_confirmacion <> '3177247772' AND numero_contacto_confirmacion <> '3166862920' AND numero_contacto_confirmacion <> '3152061220' AND numero_contacto_confirmacion <> '3232528662'";

                                    $result = qry($query);

                                    while($row = mysqli_fetch_array($result)){
                                        $id = $row['id'];
                                        $numero_contacto_confirmacion = $row['numero_contacto_confirmacion'];
                                        $id_responsable_interno = $row['id_responsable_interno'];
                                        $id_usuario_responsable = $row['id_usuario_responsable'];
                                        $cliente_gane = $row['cliente_gane'];
                                        $numero_contacto = $row['numero_contacto'];
                                        $total_caracteres = strlen($numero_contacto);

                                        if(empty($numero_contacto)){
                                            $numero_contacto = 0;
                                        }
                                        
                                        if($numero_contacto == 0 || $total_caracteres != 10){
                                            $query2 = "UPDATE usuarios SET numero_contacto = '$numero_contacto_confirmacion' WHERE id = $id_usuario_responsable";
                                            $result2 = qry($query2);
                                            if($result2){
                                                echo 'ok';
                                            }else{
                                                echo 'vaya';
                                            }
                                        }
                                        
                                        
                                        //print_r($row);
                                    }

                                    */


                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>