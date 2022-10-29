<? if (isset($_GET['id_cliente'])){ 


    $id_cliente = $_GET['id_cliente'];

    $query = "SELECT clientes.cliente_cedula, cliente_detalles.cliente_nombre, cliente_detalles.cliente_apellidos, cliente_detalles.cliente_numero_contacto, cliente_detalles.cliente_email, cliente_detalles.cliente_sexo, cliente_detalles.cliente_direccion, DATE_FORMAT(cliente_detalles.cliente_dob, '%m-%d-%Y') AS cliente_dob_format, cliente_detalles.cliente_dob, ciudades.id_departamento AS departamento_cli, cliente_detalles.ciudad_id, ciudades.id_departamento AS departamento_cli_exp, cliente_detalles.id_ciudad_exp FROM `clientes` LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN ciudades ON cliente_detalles.ciudad_id = ciudades.id WHERE clientes.id = $id_cliente";

    $result = qry($query);

    while ($row = mysqli_fetch_array($result)) {
        
        $cliente_cedula = $row['cliente_cedula'];
        $cliente_nombre = $row['cliente_nombre'];
        $cliente_apellidos = $row['cliente_apellidos'];
        $cliente_full_name = $cliente_nombre.' '.$cliente_apellidos;
        $cliente_numero_contacto = $row['cliente_numero_contacto'];
        $cliente_numero_contacto = '('.substr($cliente_numero_contacto, 0,3).') '.substr($cliente_numero_contacto, 3,3).'-'.substr($cliente_numero_contacto, 6,4);
        $cliente_email = $row['cliente_email'];
        $cliente_sexo = $row['cliente_sexo'];
        $cliente_direccion = $row['cliente_direccion'];
        $cliente_dob_format = $row['cliente_dob_format'];
        $cliente_dob = $row['cliente_dob'];
        $departamento_cli = $row['departamento_cli'];
        $ciudad_id = $row['ciudad_id'];
       
        $id_ciudad_exp = $row['id_ciudad_exp'];

        $departamento_cli_exp = execute_scalar("SELECT ciudades.id_departamento FROM ciudades WHERE ciudades.id = $id_ciudad_exp");

    }


    //


?>
            <!-- ============================================================== -->
            <!-- Page wrapper  -->
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
                            <h4 class="text-themecolor"><?=ucwords($cliente_full_name)?></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                                    <li class="breadcrumb-item"><a href="?page=clientes">Clientes</a></li>
                                    <li class="breadcrumb-item active"><?=ucwords(str_replace('_', ' ', $page))?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <div class="row" id="cliente-zone">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Actualizar Cliente</h4>
                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos<?=$cliente_sexo?>.</h6>
                                        </div>
                                    </div>
                                    <br>
                                    <form action="" method="post" class="smart-form" id="registrarNuevoClienteForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Cedula:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="cedula_cliente" id="cedula_cliente" placeholder="Cedula del cliente" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="16" <?if($cliente_cedula != ''){?>value="<?=$cliente_cedula?>"<?}?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del cliente" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;" <?if($cliente_nombre != ''){?>value="<?=$cliente_nombre?>"<?}?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="apellidos_cliente" id="apellidos_cliente" placeholder="Apellidos del cliente" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;" <?if($cliente_apellidos != ''){?>value="<?=$cliente_apellidos?>"<?}?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input class="form-control phoneNumber" name="contacto_cliente" id="contacto_cliente" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16" <?if($cliente_numero_contacto != ''){?>value="<?=$cliente_numero_contacto?>"<?}?> required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Email:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="email" class="form-control" name="email_cliente" id="email_cliente" placeholder="Email del cliente" <?if($cliente_email != ''){?>value="<?=$cliente_email?>"<?}?> autocomplete="ÑÖcompletes" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Sexo:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <fieldset class="controls">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="M" name="sexo_cliente" id="cliente_m" class="custom-control-input" required <?if($cliente_sexo == "M"){?>checked<?}?>>
                                                                <label class="custom-control-label" for="cliente_m">Masculino</label>
                                                            </div>
                                                        </fieldset>
                                                        &nbsp; &nbsp;
                                                        <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="F" name="sexo_cliente" id="cliente_f" class="custom-control-input" <?if($cliente_sexo == "F"){?>checked<?}?>>
                                                            <label class="custom-control-label" for="cliente_f">Femenino</label>
                                                        </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Dirección:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" placeholder="Dirección del cliente" autocomplete="ÑÖcompletes" <?if($cliente_direccion != ''){?>value="<?=$cliente_direccion?>"<?}?> required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Fecha de nacimiento:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" name="dob_cliente" id="dob_cliente" placeholder="Fecha de nacimiento" autocomplete="ÑÖcompletes" <?if($cliente_dob_format != ''){?>value="<?=$cliente_dob?>"<?}?> required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Departamento de residencia:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <select  class="form-control select2Class" name="departamento_cliente" id="departamento" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                            <option value="placeholder" disabled>Seleccionar Departamento</option>
                                                            <?php
                                                            $query = "select id, departamento from departamentos order by departamento";
                                                            $result = qry($query);
                                                            while($row = mysqli_fetch_array($result)) {
                                                            ?>
                                                                <option value="<?= $row['id']?>" <?if($row['id'] == $departamento_cli){?>selected<?}?>><?= $row['departamento']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">     
                                                    <div class="input-group">
                                                        <label>Otro?</label>&nbsp;
                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left" onclick="addOtro('departamento')">ADD. Departamento</button>
                                                    </div>
                                                </div>
                                            </div>                               
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Ciudad de residencia:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <select  class="form-control select2Class" name="ciudad_cliente" id="ciudad" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                            <option value="placeholder" disabled>Seleccionar Ciudad</option>
                                                            <?php
                                                            $query = "select id, ciudad from ciudades order by ciudad";
                                                            $result = qry($query);
                                                            while($row = mysqli_fetch_array($result)) {
                                                            ?>
                                                            <option value="<?= $row['id']?>" <?if($row['id'] == $ciudad_id){?>selected<?}?>><?= $row['ciudad']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">     
                                                    <div class="input-group">
                                                        <label>Otra?</label>&nbsp;
                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left" onclick="addOtro('ciudad')">ADD. Ciudad</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Departamento de expedición:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <select  class="form-control select2Class" name="departamento_exp" id="departamento2" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                            <option value="placeholder" disabled>Seleccionar Departamento</option>
                                                            <?php
                                                            $query = "select id, departamento from departamentos order by departamento";
                                                            $result = qry($query);
                                                            while($row = mysqli_fetch_array($result)) {
                                                            ?>
                                                                <option value="<?= $row['id']?>" <?if($row['id'] == $departamento_cli_exp){?>selected<?}?>><?= $row['departamento']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">     
                                                    <div class="input-group">
                                                        <label>Otro?</label>&nbsp;
                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left" onclick="addOtro('departamento')">ADD. Departamento</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Ciudad de expedición:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <select  class="form-control select2Class" name="ciudad_exp" id="ciudad2" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                            <option value="placeholder" disabled>Seleccionar Ciudad</option>
                                                            <?php
                                                            $query = "select id, ciudad from ciudades order by ciudad";
                                                            $result = qry($query);
                                                            while($row = mysqli_fetch_array($result)) {
                                                            ?>
                                                            <option value="<?= $row['id']?>" <?if($row['id'] == $id_ciudad_exp){?>selected<?}?>><?= $row['ciudad']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">     
                                                    <div class="input-group">
                                                        <label>Otra?</label>&nbsp;
                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left" onclick="addOtro('ciudad')">ADD. Ciudad</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="id_usuario" id="<?=$id_usuario?>">
                                                <input type="hidden" name="id_cliente" id="id_cliente">
                                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="cancelar2()">Cancelar</button>
                                                <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;" id="submitBtnCliente">Actualizar Cliente</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->    
                </div>
            </div>      
   
<? }else{ ?>

    <!-- ============================================================== -->
            <!-- Page wrapper  -->
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
                            <h4 class="text-themecolor"><?=ucwords($cliente_full_name)?></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                                    <li class="breadcrumb-item"><a href="?page=clientes">Clientes</a></li>
                                    <li class="breadcrumb-item active"><?=ucwords(str_replace('_', ' ', $page))?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <div class="row" id="cliente-zone">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <h1>FALTA LA INFORMACIÓN DEL CLIENTE</h1>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

        </div>

<? } ?>