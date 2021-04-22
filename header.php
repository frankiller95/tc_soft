<?php

$nombre = execute_scalar("SELECT nombre FROM usuarios WHERE id = $id_usuario");

$apellidos = execute_scalar("SELECT apellidos FROM usuarios WHERE id = $id_usuario");

$nombre_completo = $nombre.' '.$apellidos;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/logo_title.png">
    <title>TC-SHOP CRM</title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="./assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="./assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="./assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css"
        href="./assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css">
    <!-- date pickers zone -->
    <link href="./assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="./assets/node_modules/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Color picker plugins css -->
    <link href="./assets/node_modules/jquery-asColorPicker-master/dist/css/asColorPicker.css" rel="stylesheet">
    <!-- Date picker plugins css -->
    <link href="./assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="./assets/node_modules/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="./assets/node_modules/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Select 2 -->
    <link href="./assets/node_modules/select2_4.1.0/css/select2.min.css" rel="stylesheet" />
    <!--alerts CSS -->
    <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!--Magnifique pop-up CSS -->
    <link href="assets/node_modules/magnific-popup/dist/magnific-popup.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="dist/css/pages/dashboard1.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>

    <style>
        .mfp-wrap {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1600 !important;
            position: fixed;
            outline: none !important;
            -webkit-backface-visibility: hidden;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">TC-SHOP CRM</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="./assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="./assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>TC SHOP
                         <!-- dark Logo text -->
                         <!--<img src="./assets/images/logo-text.png" alt="homepage" class="dark-logo" />-->
                         <!-- Light Logo text -->    
                         <!--<img src="./assets/images/logo-light-text.png" class="light-logo" alt="homepage" />--></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- cerrar sesión -->
                        <!-- ============================================================== -->
                        <li class="nav-item"> <a class="nav-link waves-effect waves-light" href="killsession.php"><i class="fas fa-sign-out-alt"></i></a></li>
                        <!-- ============================================================== -->
                        <!-- End cerrar sesión -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        <div><img src="./assets/images/users/2.jpg" alt="user-img" class="img-circle"></div>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"><?=$nombre_completo?></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <!-- text-->
                                <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> Mi Perfil</a>
                                <a href="killsession.php" class="dropdown-item"><i class="fas fa-power-off"></i>Cerrar Sesión</a>
                                <!-- text-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li>
                            <a class="waves-effect waves-dark" href="?page=dashboard" aria-expanded="false">
                                <i class="fas fa-home"></i>&nbsp;
                                <span class="hide-menu">Inicio</span>
                            </a>
                        </li>
						
                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-warehouse"></i><span class="hide-menu">Inventario</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=productos" aria-expanded="false">
                                        <i class="ti-shopping-cart-full"></i>
                                        <span class="hide-menu">Productos</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=marcas" aria-expanded="false">
                                        <i class="fas fa-mobile"></i>
                                        <span class="hide-menu">Marcas</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fas fa-user-plus"></i>&nbsp;
                                <span class="hide-menu">Clientes</span>
                            </a>
                        </li>
                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fas fa-money-bill-alt"></i>&nbsp;
                                <span class="hide-menu">Solicitudes</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=crear_solicitud" aria-expanded="false">
                                        <i class="fas fa-plus-circle"></i>
                                        <span class="hide-menu">Crear Solicitud</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=solicitudes" aria-expanded="false">
                                        <i class="far fa-folder-open"></i>
                                        <span class="hide-menu">Ver Solicitudes</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect waves-dark" href="?page=usuarios" aria-expanded="false">
                                <i class="fas fa-users"></i>&nbsp;
                                <span class="hide-menu">Usuarios</span>
                            </a>
                        </li>
                        <?php if ($id_usuario == 18) { ?>
                        <li>
                            <a class="waves-effect waves-dark" href="?page=laboratorio" aria-expanded="false">
                                <i class="fas fa-vials"></i>&nbsp;
                                <span class="hide-menu">laboratorio</span>
                            </a>
                        </li>
                        <?php }?>
                        <li>
                            <a class="waves-effect waves-dark" href="killsession.php" aria-expanded="false">
                                <i class="far fa-circle text-danger"></i>
                                <span class="hide-menu">Cerrar Sesión</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->