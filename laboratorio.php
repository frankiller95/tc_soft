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
                                       

                                    $query = "INSERT INTO usuarios (nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto)VALUES ('francisco', 'restrepo', '1151958486', 'francisco@95media.de', '$2y$10$EVZ51kkM.gFFCDf8ov72OuSoQDPkNGLqK7gCAk./cXH9NZgaTEJYy', 2, 5, '2021-03-31', '3166862920')";

                                    $result = qry($query);

                                    if ($result) {
                                        echo "ok";
                                    }else{
                                        echo "errror";
                                    }

                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>