<!doctype html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="description" content="linea de credito para celulares y electrodomesticos.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />

    <!-- Theme stylesheets -->
    <link rel="stylesheet" href="assets/modules_incoming2/mod_tiny_transfer/assets/css/iconfont.css">
    <link rel="stylesheet" href="assets/modules_incoming2/mod_tiny_transfer/assets/css/theme.css">
    <link rel="stylesheet" href="assets/modules_incoming2/mod_tiny_transfer/assets/css/scrollbar.css">
    <link rel="stylesheet" href="assets/modules_incoming2/mod_tiny_transfer/assets/css/dropdown.css">
    <link rel="stylesheet" href="assets/modules_incoming2/mod_tiny_transfer/assets/css/tiny-transfer-form.css">
    <link rel="stylesheet" href="assets/modules_incoming2/mod_tiny_transfer/assets/css/main.css">
    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <title>NOA10 - CRM</title>

    <link rel="apple-touch-icon" sizes="180x180" href="./assets/images/logo_title.png">
    <link rel="icon" type="image/png" href="./assets/images/logo_title.png" sizes="32x32">
    <link rel="icon" type="image/png" href="./assets/images/logo_title.png" sizes="16x16">

    <style>
        .content .section-heading {
            padding-left: 440px;
            width: 100%;
            position: relative;
            top: -10% !important;
            padding-right: 40px;
        }

        .content .section-heading .section-title {
            color: #0fddd8 !important;
            font-size: 4rem;
            background-image: -webkit-gradient( linear, 0 0, 0 bottom, from(#3a8ffe), to(#95eaff));
        }

        .section-heading2 {
            padding-left: 440px;
            width: 100%;
            position: relative;
            top: -8% !important;
            padding-right: 40px;
        }

        .form-control::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #0fddd8 !important;
            opacity: 1; /* Firefox */
        }

        .form-control:-ms-input-placeholder { /* Internet Explorer 10-11 */
                color: #0fddd8 !important;
        }

        .form-control::-ms-input-placeholder { /* Microsoft Edge */
                color: #0fddd8 !important;
        }

        .form-material .form-control, .form-material .form-control.focus, .form-material .form-control:focus {
            background-image: linear-gradient(#0fddd8,#0fddd8),linear-gradient(#e9ecef,#e9ecef);
            border: 0;
            border-radius: 0;
            box-shadow: none;
            float: none;
        }

    </style>

</head>

<body>
    
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">NOA10</p>
        </div>
    </div>
    
    <main class="main-area">

        <!-- tinyTransfer start 
        <div class="tiny-transfer" data-type="upload">
        </div>
        tinyTransfer end -->

        <!-- content start -->
        <div class="content flex flex-middle">

            <div class="section-heading">
                <h2 class="section-title flex flex-center flex-middle">
                    <i class="logo"></i>
                    <span>NOA10 CRM</span>
                </h2>
                <p>Línea de credito.</p>
                <div class="flex flex-center flex-middle">
                    <form class="form-horizontal form-material" id="loginform" action="" method="post">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" name="email" placeholder="email o cedula" style="color: #ffffff">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" name="password" placeholder="Password" style="color: #ffffff">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <label class="text-warning"><?= $errorMsg ?></label>
                                    <!--<br>
                                    <label class="text-info"><?=$dispositivo?></label>-->
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-info btn-rounded" style="font-size: 19px !important; height: 55px !important;" type="submit">INICIAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- content end -->
    </main>

    <!-- Javascript Plugins -->
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!--<script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/lib/jquery.min.js"></script>-->
    <script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/lib/template.js"></script>
    <script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/lib/ui/core.js"></script>
    <script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/lib/ui/touch.js"></script>
    <script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/lib/ui/scrollbar.js"></script>
    <!--<script src="./assets/module/mod_tiny_transfer/assets/js/lib/ui/uploader.js"></script>-->
    <script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/lib/ui/dropdown.js"></script>
    <!--<script src="./assets/module/mod_tiny_transfer/assets/js/tinyTransfer.js"></script>-->

    <!-- Javascript Plugins -->
    <script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/lib/underscore-min.js"></script>
    <script src="assets/modules_incoming2/mod_tiny_transfer/assets/js/main.js"></script>

    
    <!--Custom JavaScript -->
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
        });

        /*
        $("#loginform").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("loginform"));



            const action = document.querySelector("#login_validate").value;

            if (action == "insertar_punto_gane") {

                insertPuntoGaneDB(formData);

            } else {

                updatePuntoGaneDB(formData);

            }

        });
        */
    </script>

</body>

</html>