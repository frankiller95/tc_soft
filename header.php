<?php

$nombre = execute_scalar("SELECT nombre FROM usuarios WHERE id = $id_usuario");

$apellidos = execute_scalar("SELECT apellidos FROM usuarios WHERE id = $id_usuario");

$nombre_completo = $nombre . ' ' . $apellidos;

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
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/logo_title.png">
    <title>TCSHOP - CRM</title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="./assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="./assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="./assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css">
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
    <!-- Switchery -->
    <link href="./assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <!--alerts CSS -->
    <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!--Magnifique pop-up CSS -->
    <link href="assets/node_modules/magnific-popup/dist/magnific-popup.css" rel="stylesheet">
    <!-- ImgAreaSelect -->
    <link rel='stylesheet' href='./assets/node_modules/imgareaselect/css/imgareaselect-animated.css'>
    <link rel='stylesheet' href='./assets/node_modules/imgareaselect/css/imgareaselect-default.css'>
    <!-- font awesome 5.15.3 -->
    <link rel="stylesheet" href="./assets/icons/fontawesome-5.15.3/css/all.css">
    <!-- dropify -->
    <link rel="stylesheet" href="./assets/node_modules/dropify/dist/css/dropify.min.css">
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

    <script>
        var getBrowserInfo = function() {
            var ua = navigator.userAgent,
                tem,
                M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
            if (/trident/i.test(M[1])) {
                tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
                return 'IE ' + (tem[1] || '');
            }
            if (M[1] === 'Chrome') {
                tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
                if (tem != null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
            }
            M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
            if ((tem = ua.match(/version\/(\d+)/i)) != null) M.splice(1, 1, tem[1]);
            return M.join(' ');
        };

        console.log(getBrowserInfo());

        $(document).ready(function() {
            var config = {
                type: 'image',
                callbacks: {}
            };

            var cssHeight = '800px'; // Add some conditions here

            config.callbacks.open = function() {
                $(this.container).find('.mfp-content').css('height', cssHeight);
            };

            $('.image-complete').magnificPopup(config);
        });
    </script>
    <style>
        .mfp-wrap {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1600 !important;
            position: fixed;
            outline: none !important;
            /*-webkit-backface-visibility: hidden;*/
        }

        @import url('https://fonts.googleapis.com/css?family=Roboto+Mono&display=swap');

        * {
            box-sizing: border-box;
        }

        .container {
            background-color: #fff;
            border: 3px #000 solid;
            border-radius: 10px;
            padding: 30px;
            max-width: 1000px;
            text-align: center;
        }

        .code-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 30px;
        }

        .code {
            border-radius: 5px;
            font-size: 75px;
            height: 120px;
            width: 100px;
            border: 1px solid #eee;
            margin: 1%;
            text-align: center;
            font-weight: 300;
            -moz-appearance: textfield;
        }

        .code::-webkit-outer-spin-button,
        .code::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .code:valid {
            border-color: #3498db;
            box-shadow: 0 10px 10px -5px rgba(0, 0, 0, 0.25);
        }

        .skin-default-dark .topbar {
            background: #00b2c8 !important;
        }

        .skin-default-dark .sidebar-nav>ul>li.active>a {
            color: #00b2c8 !important;
            border-left: 3px solid #1ca0b8 !important;
        }

        .skin-default-dark .left-sidebar .user-pro-body a.link {
            color: #ffffff !important;
        }

        .sidebar-nav ul li a {
            color: #ffffff !important;
        }

        .sidebar-nav ul li a.active,
        .sidebar-nav ul li a:hover {
            color: #00b2c8 !important;
        }

        .sidebar-nav ul li a.active i,
        .sidebar-nav ul li a:hover i {
            color: #00b2c8 !important;
        }

        .skin-default-dark .page-titles .breadcrumb .breadcrumb-item.active {
            color: #00b2c8 !important;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #00b2c8 !important;
            border-color: #1ca0b8 !important;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #323232;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-link:hover {
            z-index: 2;
            color: #323232 !important;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .custom-control-input:checked~.custom-control-label::before {
            color: #fff;
            border-color: #00b2c8 !important;
            background-color: #00b2c8 !important;
        }

        .dt-buttons .dt-button {
            padding: 5px 15px;
            border-radius: 0.25rem;
            background: #00b2c8 !important;
            color: #fff;
            margin-right: 3px;
        }

        .btn-primary {
            color: #212529;
            background-color: #00b2c8 !important;
            border-color: #1ca0b8 !important;
        }

        .dt-buttons .dt-button:hover {
            background: #04bcb4 !important;
        }

        .icono-primary-tc {
            color: #00b2c8 !important;
        }

        .link-href {
            color: #8d97ad !important;
        }

        .info {
            background-color: #eaeaea;
            display: inline-block;
            padding: 10px;
            line-height: 20px;
            max-width: 400px;
            color: #777;
            border-radius: 5px;
        }

        @media (max-width: 600px) {
            .code-container {
                flex-wrap: wrap;
            }

            .code {
                font-size: 60px;
                height: 80px;
                max-width: 70px;
            }
        }

        .solicitud-rechazada {
            background-color: #d9534f !important;
        }

        .solicitud-aprobada {
            background-color: #5cb85c !important;
        }

        .solicitud-informacion {
            background-color: #5bc0de !important;
        }

        .solicitud-asignada {
            background-color: #5bc0de !important;
        }

        .solicitud-asignada-servientrega {
            background-color: #28a745 !important;
        }

        .solicitud-asignada-tienda {
            background-color: #5bc0de !important;
        }

        .tarjetacontenido {
            width: 400px;
            height: 300px;
            line-height: 300px;
            color: #333;
            position: relative;
            background: #FFF;
            border: 1px solid #333;
            text-align: center;
            overflow-y: scroll;
        }

        .tarjeta-texto {
            display: inline-block;
            vertical-align: middle;
            line-height: 1.3em;
            text-align: center;
            height: auto;
        }

        .mitexto {
            font-family: 'arial', serif;
            font-size: 14pt;
        }


        .topbar .top-navbar .navbar-nav>.nav-item>.nav-link {
            padding-left: 30px;
            padding-right: 35px;
            font-size: 18px;
            line-height: 50px;
        }

        .notify {
            position: relative;
            top: -28px;
            right: -6px;
        }

        .notify .heartbit {
            position: absolute;
            top: -31px;
            right: -31px;
            height: 48px;
            width: 48px;
            z-index: 10;
            border: 5px solid #e46a76;
            border-radius: 70px;
        }

        .notify .point {
            width: 6px;
            height: 6px;
            -webkit-border-radius: 30px;
            -moz-border-radius: 30px;
            border-radius: 0px;
            background-color: #2b2b2b !important;
            position: absolute;
            right: 0px;
            bottom: 27px !important;
            left: 18px !important;
            top: -30px;
            color: #e46a76;
            font-size: 14px;
            font-weight: 700;
        }
        
    </style>

</head>

<?
if (validateScreen($id_usuario) == 1 || $validate_home == 1) {
?>

    <body class="skin-default-dark fix-header single-column card-no-border fix-sidebar">
    <? } else { ?>

        <body class="skin-default-dark fixed-layout">
        <? } ?>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">NOA10 - CRM</p>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper">
            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            <?
            if (validateScreen($id_usuario) == 0 || $validate_home == 1) {
            ?>
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
                                    <img src="./assets/images/logo-white.png" alt="homepage" class="dark-logo" />
                                    <!-- Light Logo icon -->
                                    <img src="./assets/images/logo-white.png" alt="homepage" class="light-logo" />
                                </b>
                                <!--End Logo icon -->
                                <!-- Logo text -->
                                <!--<span>TCSHOP
                                    <!-- dark Logo text -->
                                <!--<img src="./assets/images/logo-text.png" alt="homepage" class="dark-logo" />-->
                                <!-- Light Logo text -->
                                <!--<img src="./assets/images/logo-light-text.png" class="light-logo" alt="homepage" />-->
                                <!--</span>-->
                            </a>
                        </div>
                        <!-- ============================================================== -->
                        <!-- End Logo -->
                        <!-- ============================================================== -->
                        <div class="navbar-collapse" style="background-color: #2b2b2b;">
                            <!-- ============================================================== -->
                            <!-- toggle and nav items -->
                            <!-- ============================================================== -->
                            <ul class="navbar-nav mr-auto">
                                <!-- This is  -->
                                <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                                <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                                <!--<li><h1>SOFTWARE DE PRUEBAS&nbsp;<?= $hora_actual ?>&nbsp;&nbsp;<?= $id_usuario ?></h1></li>-->
                            </ul>
                            <ul class="navbar-nav my-lg-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="showBandejaRecords(<?= $id_usuario ?>)"><i class="fas fa-bell"></i>
                                        <div class="notify"><span class="heartbit"></span> <span class="point" id="counter-alerts">0</span></div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" id="menu-recordatorios">
                                        <ul>
                                            <li>
                                                <div class="drop-title">Recordatorios</div>
                                            </li>
                                            <li>
                                                <div class="message-center" id="bandeja_recordatorios">
                                                    <!-- Message -->
                                                    <a href="javascript:void(0);">
                                                        <div class="btn btn-info btn-circle"><i class="fas fa-envelope"></i></div>
                                                        <div class="mail-contnet">
                                                            <h5>20/04/2022</h5> <span class="mail-desc">PEDRO PABLO LEGUIZAMO</span> <span class="time">cada una en su lugar</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </li>
                                            <!--<li class="tus_recordatorios_class">
                                                <div class="drop-title">Tus Recordatorios</div>
                                            </li>
                                            <li class="tus_recordatorios_class">
                                                <div class="message-center" id="bandeja_tus_recordatorios">
                                                    <a href="javascript:void(0);">
                                                        <div class="btn btn-info btn-circle"><i class="fas fa-envelope"></i></div>
                                                        <div class="mail-contnet">
                                                            <h5>20/04/2022</h5> <span class="mail-desc">PEDRO PABLO LEGUIZAMO</span> <span class="time">cada una en su lugar</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </li>-->
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                            <!-- ============================================================== -->
                            <!-- User profile and search -->
                            <!-- ============================================================== -->
                            <ul class="navbar-nav my-lg-0">
                                <!-- ============================================================== -->
                                <!-- cerrar sesión -->
                                <!-- ============================================================== -->
                                <li class="nav-item"> <a class="nav-link waves-effect waves-light" href="javascript:void(0);" onclick="killSession(<?= $id_usuario ?>);"><i class="fas fa-sign-out-alt"></i></a></li>
                                <!-- ============================================================== -->
                                <!-- End cerrar sesión -->
                                <!-- ============================================================== -->
                            </ul>
                        </div>
                    </nav>
                </header>
            <? } ?>
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
                            <!--<div><img src="./assets/images/users/2.jpg" alt="user-img" class="img-circle"></div>-->
                            <div class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"><?= $nombre_completo?></span></a>
                                <div class="dropdown-menu animated flipInY">
                                    <!-- text-->
                                    <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> Mi Perfil</a>
                                    <a href="javascript:void(0);" onclick="killSession(<?= $id_usuario ?>);" class="dropdown-item"><i class="fas fa-power-off"></i>Cerrar Sesión</a>
                                    <!-- text-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar navigation-->
                    <nav class="sidebar-nav">
                        <ul id="sidebarnav">
                            <? if (profile(13, $id_usuario) == 1) { ?>
                                <?
                                if (validateScreen($id_usuario) == 1 || $validate_home == 1) {
                                ?>
                                    <li>
                                        <a class="waves-effect waves-dark <? if ($page == "dashboard_IE") { ?>active<? } ?> icono-primary-tc" href="?page=dashboard" aria-expanded="false">
                                            <i class="fas fa-home"></i>&nbsp;
                                            <span class="hide-menu link-href">Inicio</span>
                                        </a>
                                    </li>
                                <? } else { ?>
                                    <li>
                                        <a class="waves-effect waves-dark <? if ($page == "dashboard") { ?>active<? } ?>" href="?page=dashboard" aria-expanded="false">
                                            <i class="fas fa-home"></i>&nbsp;
                                            <span class="hide-menu">Inicio</span>
                                        </a>
                                    </li>
                                <? } ?>
                            <? }
                            if (profile(1, $id_usuario) == 1 || profile(17, $id_usuario) == 1 || profile(18, $id_usuario) == 1 || profile(19, $id_usuario) == 1) { ?>
                                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-warehouse"></i><span class="hide-menu">&nbsp;Productos</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <? if (profile(1, $id_usuario) == 1) { ?>
                                            <li>
                                                <a class="waves-effect waves-dark" href="?page=productos" aria-expanded="false">
                                                    <i class="ti-shopping-cart-full"></i>
                                                    <span class="hide-menu">Inventarios</span>
                                                </a>
                                            </li>
                                        <? }
                                        if (profile(17, $id_usuario) == 1) {
                                        ?>
                                            <li>
                                                <a class="waves-effect waves-dark" href="?page=marcas" aria-expanded="false">
                                                    <i class="fas fa-mobile"></i>
                                                    <span class="hide-menu">Crear Marcas</span>
                                                </a>
                                            </li>
                                        <?
                                        }
                                        if (profile(18, $id_usuario) == 1) {
                                        ?>
                                            <li>
                                                <a class="waves-effect waves-dark" href="?page=modelos" aria-expanded="false">
                                                    <i class="fas fa-mobile"></i>
                                                    <span class="hide-menu">Crear Modelos</span>
                                                </a>
                                            </li>
                                        <?
                                        }
                                        if (profile(19, $id_usuario) == 1) {
                                        ?>
                                            <li>
                                                <a class="waves-effect waves-dark" href="?page=proveedores" aria-expanded="false">
                                                    <i class="fas fa-store-alt"></i>
                                                    <span class="hide-menu">Crear Proveedores</span>
                                                </a>
                                            </li>
                                        <? } ?>
                                    </ul>
                                </li>
                            <? }
                            if (profile(2, $id_usuario) == 1) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=clientes" aria-expanded="false">
                                        <i class="fas fa-user-plus"></i>&nbsp;
                                        <span class="hide-menu">Clientes</span>
                                    </a>
                                </li>
                            <?
                            }
                            if (profile(12, $id_usuario) == 1) {
                            ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=prospectos" aria-expanded="false">
                                        <i class="fas fa-user-plus"></i>&nbsp;
                                        <span class="hide-menu">Prospectos</span>
                                    </a>
                                </li>
                            <? }
                            if (profile(43, $id_usuario) == 1) { ?>

                                <li>
                                    <a class="waves-effect waves-dark" href="?page=validaciones" aria-expanded="false">
                                        <i class="fas fa-money-bill"></i>&nbsp;
                                        <span class="hide-menu">Validaciones</span>
                                    </a>
                                </li>

                            <? }
                            if (profile(11, $id_usuario) == 1) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=clientes_gane" aria-expanded="false" id="buttonGane">
                                        <i class="fas fa-user-plus"></i>&nbsp;
                                        <span class="hide-menu">Prospectos Gane</span>
                                    </a>
                                </li>
                            <? }
                            if (profile(3, $id_usuario) == 1) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=solicitudes" aria-expanded="false">
                                        <i class="fas fa-money-bill-alt"></i>&nbsp;
                                        <span class="hide-menu">Solicitudes</span>
                                    </a>
                                </li>
                            <? }
                            if (profile(4, $id_usuario) == 1) { ?>
                                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Bibliotecas</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <? if (profile(27, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=ciudades"><i class="fas fa-city"></i>&nbsp;<span class="hide-menu">Ciudades</span></a></li>
                                        <? }
                                        if (profile(29, $id_usuario)) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=colores"><i class="fas fa-palette"></i>&nbsp;<span class="hide-menu">Colores Dispostivos</span></a></li>
                                        <? }
                                        if (profile(28, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=arandelas"><i class="fas fa-dollar-sign"></i>&nbsp;<span class="hide-menu">Definir Arandelas</span></a></li>
                                        <? }
                                        if (profile(30, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=puntos_gane"><i class="fas fa-store"></i>&nbsp;<span class="hide-menu">Puntos Gane</span></a></li>
                                        <? }
                                        if (profile(31, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=configuraciones"><i class="fas fa-tools"></i>&nbsp;<span class="hide-menu">Configuraciones</span></a></li>
                                        <? }
                                        if (profile(43, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=cargos"><i class="fas fa-tools"></i>&nbsp;<span class="hide-menu">Cargos</span></a></li>
                                        <? }
                                        ?>
                                    </ul>
                                </li>
                            <? }
                            if (profile(5, $id_usuario) == 1 || profile(9, $id_usuario) == 1) { ?>
                                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-mobile"></i>&nbsp;<span class="hide-menu">Dispositivos</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <? if (profile(5, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=nuovo"><i class="fab fa-android"></i>&nbsp;<span class="hide-menu">NUOVO</span></a></li>
                                        <? }
                                        if (profile(9, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=trustonic"><i class="fab fa-android"></i>&nbsp;<span class="hide-menu">TRUSTONIC</span></a></li>
                                        <? } ?>
                                    </ul>
                                </li>
                            <? }
                            if (profile(10, $id_usuario) == 1) {
                            ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=crediavales" aria-expanded="false">
                                        <i class="fas fa-user-plus"></i>&nbsp;
                                        <span class="hide-menu">CREDIAVALES</span>
                                    </a>
                                </li>
                            <? }
                            if (profile(15, $id_usuario) == 1) { ?>
                                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-clipboard-check"></i>&nbsp;<span class="hide-menu">Reportes</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <? if (profile(16, $id_usuario) == 1) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=reporte_puntos_gane"><i class="far fa-clipboard"></i>&nbsp;<span class="hide-menu">Puntos Gane</span></a></li>
                                        <? }
                                        if (profile(21, $id_usuario)) {
                                        ?>
                                            <li><a class="waves-effect waves-dark" href="?page=clientes_adelantos"><i class="far fa-clipboard"></i>&nbsp;<span class="hide-menu">Clientes Adelantos</span></a></li>
                                        <? }
                                        if (profile(22, $id_usuario)) {
                                        ?>
                                            <li><a class="waves-effect waves-dark" href="?page=clientes_crediminutos"><i class="far fa-clipboard"></i>&nbsp;<span class="hide-menu">Clientes Crediminuto</span></a></li>
                                        <? }
                                        if (profile(37, $id_usuario)) { ?>
                                            <li><a class="waves-effect waves-dark" href="?page=entregas_pdtes"><i class="fas fa-motorcycle"></i>&nbsp;<span class="hide-menu">ENTREGAS PDTES</span></a></li>
                                        <? }
                                        if (profile(38, $id_usuario)) { ?>
                                            <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-dollar-sign"></i>&nbsp;<span class="hide-menu">Equipos Entregados</span></a>
                                                <ul aria-expanded="false" class="collapse">
                                                    <li>
                                                        <a class="waves-effect waves-dark" href="?page=equipos_entregados_resumen"><i class="fas fa-dollar-sign"></i>&nbsp;<span class="hide-menu">Resumen</span></a>
                                                    </li>
                                                    <li>
                                                        <a class="waves-effect waves-dark" href="?page=equipos_entregados_detallado"><i class="fas fa-dollar-sign"></i>&nbsp;<span class="hide-menu">Detallado</span></a>
                                                    </li>
                                                </ul>
                                            </li>
                                        <? }
                                        if (profile(39, $id_usuario)) { ?>
                                            <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-user-plus"></i>&nbsp;<span class="hide-menu">Reporte Prospectos</span></a>
                                                <ul aria-expanded="false" class="collapse">
                                                    <li>
                                                        <a class="waves-effect waves-dark" href="?page=reporte_prospectos_resumen"><i class="fas fa-user-plus"></i>&nbsp;<span class="hide-menu">Resumen</span></a>
                                                    </li>
                                                    <li>
                                                        <a class="waves-effect waves-dark" href="?page=reporte_prospectos_detallado"><i class="fas fa-user-plus"></i>&nbsp;<span class="hide-menu">Detallado</span></a>
                                                    </li>
                                                </ul>
                                            </li>
                                        <? }
                                        if (profile(40, $id_usuario)) { ?>
                                            <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-clipboard-check"></i>&nbsp;<span class="hide-menu">Reporte Servientrega</span></a>
                                                <ul aria-expanded="false" class="collapse">
                                                    <li>
                                                        <a class="waves-effect waves-dark" href="?page=reporte_servientrega_resumen"><i class="fas fa-truck"></i>&nbsp;<span class="hide-menu">Resumen</span></a>
                                                    </li>
                                                    <li>
                                                        <a class="waves-effect waves-dark" href="?page=reporte_servientrega_detallado"><i class="fas fa-truck"></i>&nbsp;<span class="hide-menu">Detallado</span></a>
                                                    </li>
                                                </ul>
                                            </li>
                                        <? } ?>
                                        <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-clipboard-check"></i>&nbsp;<span class="hide-menu">Reporte Aprobados</span></a>
                                            <ul aria-expanded="false" class="collapse">
                                                <li>
                                                    <a class="waves-effect waves-dark" href="?page=reporte_aprobados"><i class="fas fa-truck"></i>&nbsp;<span class="hide-menu">Resumen</span></a>
                                                </li>
                                                <li>
                                                    <a class="waves-effect waves-dark" href="?page=reporte_aprobados"><i class="fas fa-truck"></i>&nbsp;<span class="hide-menu">Aprobados</span></a>
                                                </li>
                                            </ul>
                                        </li>
                                        ?>
                                    </ul>
                                </li>
                            <?
                            }
                            if (profile(32, $id_usuario)) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=rutas_gane" aria-expanded="false">
                                        <i class="fas fa-route"></i>&nbsp;
                                        <span class="hide-menu">Rutas Gane</span>
                                    </a>
                                </li>
                            <? }
                            if (profile(35, $id_usuario) == 1) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=cotizaciones" aria-expanded="false">
                                        <i class="fas fa-money-check-alt"></i>&nbsp;
                                        <span class="hide-menu">Cotizaciones</span>
                                    </a>
                                </li>
                            <? }
                            if (profile(42, $id_usuario) == 1) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=perfiles" aria-expanded="false">
                                        <i class="fas fa-users"></i>&nbsp;
                                        <span class="hide-menu">Perfiles</span>
                                    </a>
                                </li>
                            <? }

                            if (profile(6, $id_usuario) == 1) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=usuarios" aria-expanded="false">
                                        <i class="fas fa-users"></i>&nbsp;
                                        <span class="hide-menu">Usuarios</span>
                                    </a>
                                </li>
                            <? }
                            if (profile(7, $id_usuario) == 1) { ?>
                                <li>
                                    <a class="waves-effect waves-dark" href="?page=laboratorio" aria-expanded="false">
                                        <i class="fas fa-vials"></i>&nbsp;
                                        <span class="hide-menu">laboratorio</span>
                                    </a>
                                </li>
                            <? } ?>
                            <li>
                                <a class="waves-effect waves-dark" href="javascript:void(0);" onclick="killSession(<?= $id_usuario ?>);" aria-expanded="false">
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

            <script>
                // detect the type of mobile device being used.
                var isMobile = {
                    Android: function() {
                        return navigator.userAgent.match(/Android/i);
                    },
                    BlackBerry: function() {
                        return navigator.userAgent.match(/BlackBerry/i);
                    },
                    iOS: function() {
                        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                    },
                    Opera: function() {
                        return navigator.userAgent.match(/Opera Mini/i);
                    },
                    Windows: function() {
                        return navigator.userAgent.match(/IEMobile/i);
                    },
                    any: function() {
                        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                    }
                };

                function error401() {

                    Swal.fire({

                        type: 'error',
                        title: 'Oops...',
                        text: 'No se puede acceder desde dispositivos mobiles',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        timer: 2500

                    });
                }

                const mobileStyles = matchMedia("(min-width: 1024px)");

                const ganeBoton = $("#buttonGane");
                ganeBoton.removeAttr('onclick');

                $(document).ready(function() {

                    const changeSize = mql => {

                        if (mql.matches) {

                            // laptop

                            ganeBoton.attr('href', '?page=clientes_gane');
                            ganeBoton.removeAttr('onclick');

                        } else {

                            if (isMobile.iOS() || isMobile.Android()) {

                                ganeBoton.attr('href', 'javascript:void(0);');

                                ganeBoton.attr('onClick', 'error401()');

                            }

                        }

                    }

                    mobileStyles.addListener(changeSize);
                    changeSize(mobileStyles);

                });
            </script>