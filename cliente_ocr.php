<div class="response" style="
    width: 100%;
    height: 100%;
    top: 0;
    position: fixed;
    z-index: 99999;
    background: #fff;
    display: none;
    ">
    <div class="loader">
       <div class="loader__figure"></div>
        <p class="loader__label">POR FAVOR ESPERE...</p> 
    </div> 
</div>
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
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <div class="row" id="solicitud-zone">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="addCliente()">Nuevo Cliente</button>
                                    <h4 class="card-title">Clientes</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableClientes" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>C.C</th>
                                                               <th>NOMBRE</th>
                                                               <th>CONTACTO</th>
                                                               <th>EMAIL</th>
                                                               <th>UBICACIÓN</th>
                                                               <th></th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT clientes.id AS id_cliente, clientes.cliente_cedula, CONCAT(cliente_nombre, ' ', cliente_apellidos) AS full_cliente, cliente_numero_contacto, cliente_email, CONCAT(cliente_direccion, ', ',ciudades.ciudad) AS ubicacion FROM clientes LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN ciudades ON cliente_detalles.ciudad_id = ciudades.id WHERE clientes.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_cliente = $row1['id_cliente'];
                                                            $cliente_cedula = $row1['cliente_cedula'];
                                                            $full_cliente = $row1['full_cliente'];
                                                            $contacto = $row1['cliente_numero_contacto'];
                                                            $contacto = '('.substr($contacto, 0,3).') '.substr($contacto, 3,3).'-'.substr($contacto, 6,4);
                                                            $email = $row1['cliente_email'];
                                                            $ubicacion = $row1['ubicacion'];

                                                            $imagenes = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_clientes WHERE id_cliente = $id_cliente AND imagenes_clientes.del = 0");

                                                    ?>
                                                               <tr class="row-<?=$id_cliente?>">
                                                               <td><?=$id_cliente?></td>
                                                               <td><?=$cliente_cedula?></td>
                                                               <td><?=$full_cliente?></td>
                                                               <td><?=$contacto?></td>
                                                               <td><?=$email?></td>
                                                               <td><?=$ubicacion?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Cliente" onClick="editarCliente(<?= $id_cliente ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <? if($imagenes == 3){?>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Imagenes" onClick="verImagenesC(<?= $id_cliente ?>)"><i class="fas fa-eye"></i></a>
                                                                    <? }else{?>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagenes" onClick="subirImagenesC(<?= $id_cliente ?>)"><i class="fas fa-upload"></i></a>
                                                                    <? }?>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Cliente" onClick="eliminarCliente(<?= $id_cliente ?>)"><i class="fas fa-trash-alt"></i></a>
                                                                </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="row" id="cliente-zone" style="display: none;">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Registrar Cliente</h4>
                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                        </div>
                                    </div>
                                    <br>
                                    <form action="" method="post" class="smart-form" id="registrarNuevoClienteForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Cedula:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="cedula_cliente" id="cedula_cliente" placeholder="Cedula del cliente" onkeydown="return false;"
                style="background-color: #e9ecef !important;" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" data-readonly onkeyup="javascript:this.value=this.value.toUpperCase();">
                                                        <a href="javascript:void(0);" class="btn waves-effect waves-light btn-sm btn-info" style="margin-left: 5px" onclick="cutImage('cc')"><i class="fas fa-crosshairs"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del cliente" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                                        <a href="javascript:void(0);" class="btn waves-effect waves-light btn-sm btn-info" style="margin-left: 5px" onclick="cutImage('name')"><i class="fas fa-crosshairs"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="apellidos_cliente" id="apellidos_cliente" placeholder="Apellidos del cliente" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                                        <a href="javascript:void(0);" class="btn waves-effect waves-light btn-sm btn-info" style="margin-left: 5px" onclick="cutImage('last')"><i class="fas fa-crosshairs"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input class="form-control phoneNumber" name="contacto_cliente" id="contacto_cliente" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Email:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <input type="email" class="form-control" name="email_cliente" id="email_cliente" placeholder="Email del cliente" required autocomplete="ÑÖcompletes" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Sexo:<span class="text-danger">&nbsp;*</span></label>
                                                    <div class="input-group">
                                                        <fieldset class="controls">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="M" name="sexo_cliente" id="cliente_m" class="custom-control-input" required>
                                                                <label class="custom-control-label" for="cliente_m">Masculino</label>
                                                            </div>
                                                        </fieldset>
                                                        &nbsp; &nbsp;
                                                        <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="F" name="sexo_cliente" id="cliente_f" class="custom-control-input">
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
                                                    <label>Dirección:</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" placeholder="Dirección del cliente" autocomplete="ÑÖcompletes">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Fecha de nacimiento:</label>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" name="dob_cliente" id="dob_cliente" placeholder="Fecha de nacimiento" autocomplete="ÑÖcompletes">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Departamento de residencia:</label>
                                                    <div class="input-group">
                                                        <select  class="form-control select2Class" name="departamento_cliente" id="departamento" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                            <option value="placeholder" disabled>Seleccionar Departamento</option>
                                                            <?php
                                                            $query = "select id, departamento from departamentos order by departamento";
                                                            $result = qry($query);
                                                            while($row = mysqli_fetch_array($result)) {
                                                            ?>
                                                                <option value="<?= $row['id']?>"><?= $row['departamento']?></option>
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
                                                    <label>Ciudad de residencia:</label>
                                                    <div class="input-group">
                                                        <select  class="form-control select2Class" name="ciudad_cliente" id="ciudad" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                            <option value="placeholder" disabled>Seleccionar Ciudad</option>
                                                            <option disabled>Selecciona un departamento</option>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Imagenes de clientes</h4>
                                                        <div class="table-responsive m-t-40" id="tableClientes-zone">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="id_usuario" id="<?=$id_usuario?>">
                                                <input type="hidden" name="id_cliente" id="id_cliente">
                                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="cancelar2()">Cancelar</button>
                                                <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;">Crear Cliente</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="crop-zone" style="display: none;">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title" id="crop-title"></h4>
                                    <div class="row pt-3" id="zone-recortar">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->    
                </div>
            </div>      
   

<!-- cargar imagen modal -->
        <div id="cargar-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cargar imagen</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="uploadFrontalForm" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div id="zone-subir">
                                    <div class="form-group">
                                        <label>Cedula de ciudadania<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">Cargar</span>
                                            </div>
                                            <div class="custom-file">
                                            <input type="file" name="file" id="file" class="form-control" accept="jpg, png, jpeg">
                                            </div>
                                        </div>
                                        <br>
                                        <!--<p class="help-block">Solo acpeta formatos<span class="text-danger">JPG, PNG, JPEG</span>.</p>-->
                                        <p class="help-block"><span class="text-danger">IMPORTANTE</span>:&nbsp;Subir parte frontal de la cedula.</p>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="action" id="action_recorte">
                            <input type="hidden" name="id_cliente" id="id_cliente_recorte">
                            <input type="hidden" name="from" id="from_recorte">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary">Cargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->

<!-- cargar imagen modal (desde tabla)-->
        <div id="cargar-modal2" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cargar imagen</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="uploadFrontalForm2" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div id="zone-subir">
                                    <div class="form-group">
                                        <label>Cedula de ciudadania<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">Cargar</span>
                                            </div>
                                            <div class="custom-file">
                                            <input type="file" name="file2" id="file2" class="form-control" accept="jpg, png, jpeg">
                                            </div>
                                        </div>
                                        <br>
                                        <!--<p class="help-block">Solo acpeta formatos<span class="text-danger">JPG, PNG, JPEG</span>.</p>-->
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="from" id="from_imagen">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary">Cargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->

<!-- cargar imagen modal (desde tabla)-->
        <div id="otro_modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="otro_title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="otroForm" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12" id="zone-ciudad">
                                    <div class="form-group">
                                        <label>Ciudad:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ciudad_otra" id="ciudad_otra" placeholder="Dirección del cliente" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="zone-departamento">
                                    <div class="form-group">
                                        <label>Departamento:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="depar_otro" id="depar_otro" placeholder="Dirección del cliente" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="from" id="from_otro">
                            <input type="hidden" name="id_depart" id="id_depart">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


<!-- Ver imagenes -->
    <div id="ver-imagenes-modal" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Imagenes Cargadas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" id="table_lot">
                                    
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <!--<button type="submit" class="btn btn-primary" >Save</button>-->
                    </div>
            </div>
        </div>
    </div>
<!-- /.modal -->


<!-- subir imagenes -->
    <div id="subir-imgenes-modal" class="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cargar Imagenes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="uploadImagenesForm" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cedula de ciudadania<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cargar</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="file[]" id="file" class="form-control" multiple accept="jpg, png, jpeg">
                                        </div>
                                    </div>
                                    <br>
                                    <!--<p class="help-block">Solo acpeta formatos<span class="text-danger">JPG, PNG, JPEG</span>.</p>-->
                                    <p class="help-block"><span class="text-danger">IMPORTANTE</span>:&nbsp;Maximo 3 imagenes.
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id_cliente_img" name="id_cliente">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-primary" >Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- /.modal -->


<script>

 var x1 = 0, y1 = 0, x2 = 0, y2 = 0, anchura = 0, altura = 0, ext = '', id_cliente = '', from = '', action = '', resultado = '', zone1 = $("#cliente-zone"), zone2 = $("#crop-zone"), zone3 = $("#zone-recortar"), zone4 = $("#solicitud-zone"), zone5 = $("#tableClientes-zone");

    function addCliente(){

        var parameters = {
            "action": "eliminar_imagenes"
        };

        $.ajax({
            url:'ajax/solicitudAjax.php', 
            type:'POST', 
            data: parameters,
            success:function(response){

                $("#cedula_cliente").val('');
                $("#nombre_cliente").val('');
                $("#apellidos_cliente").val('');
                $("#contacto_cliente").val('');
                $("#email_cliente").val('');
                $("#cliente_m").prop('checked', false);
                $("#cliente_f").prop('checked', false);
                $("#direccion_cliente").val('');
                $("#dob_cliente").val('');
                $("#departamento").val('placeholder');
                $("#departamento").trigger('change');
                $("#ciudad").val('placeholder');
                $("#ciudad").trigger('change');
                $("#solicitud-zone").hide();
                $("#id_cliente").val('');
                zone5.empty();
                $("#cliente-zone").show();

                tableImagenesC = `<table id="dataTableImgClientes" class="table display table-bordered table-striped no-wrap">
                <thead>
                <tr>
                <th>IMAGEN</th>
                <th></th>
                </tr>
                </thead>
                <tbody>
                <tr class="row-frontal">
                <td>C.C. FRONTAL</td>
                <td>
                <a href="javascript:void(0);" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cargar Imagen" onclick="cargarImgenCli('frontal')"><i class="fas fa-upload"></i></a>
                </td>    
                </tr>
                <tr class="row-atras">
                <td>C.C. ATRAS</td>
                <td>

                <a href="javascript:void(0);" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cargar Imagen" onclick="cargarImgenCli('atras')"><i class="fas fa-upload"></i></a>
                </td>    
                </tr>
                <tr class="row-selfie">
                <td>C.C. SELFIE</td>
                <td>

                <a href="javascript:void(0);" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cargar Imagen" onclick="cargarImgenCli('selfie')"><i class="fas fa-upload"></i></a>
                </td>    
                </tr>
                </tbody>
                </table>`;


                zone5.append(tableImagenesC);

            }
        });

    }

    function cutImage(from){

        let idCliente = $("#id_cliente").val();

        var parameters = {
            "action": "validate",
            "id_cliente": idCliente
        };

        $.ajax({
            url:'ajax/solicitudAjax.php', 
            type:'POST', 
            data: parameters, 
            success:function(response){

                console.log(response);

                const respuesta = JSON.parse(response);
                console.log(respuesta);

                if (respuesta.validate == 0) {

                    console.log('entro por si');
                    $("#file").val('');
                    $("#from_recorte").val(from);
                    $("#cargar-modal").modal("show");

                }else{

                    console.log('entro por no');
                    filename2 = respuesta.id_cliente+'-0';
                    extension2 = 'jpg';

                    cargarImgOpt(respuesta.id_cliente, filename2, extension2, from);

                }
            }

        });
        
    }



    $("#uploadFrontalForm").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        action = "subir_img";

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("uploadFrontalForm"));
        formData.append('action', action);

        insertImgClienteDB(formData);

        
    });



    function insertImgClienteDB(dates){
        
        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/solicitudAjax.php', true);
        // pass Info
        xhr.onload = function(){

            //the conection is success
            if (this.status === 200) {

                console.log(xhr.responseText);
                const respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                if(respuesta.response == "success"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Imagen Cargada Correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    cargarImgOpt(respuesta.id_cliente, respuesta.filename, respuesta.extension, respuesta.from);

                    $(".row-frontal").empty();

                    $(".row-frontal").html(`<td>C.C. FRONTAL</td>
                                            <td><a href="./documents/clients/${respuesta.id_cliente}/${respuesta.filename}.${respuesta.extension}" class="btn btn-outline-info waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="C.C. FRONTAL"><i class="fas fa-eye"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" onclick="EliminarImagenCli(${respuesta.id_cliente}, 'frontal')"><i class="far fa-trash-alt"></i></a>
                                            </td>`);

                    var config = {
                        type: 'image',
                        callbacks: { }
                    };

                    var cssHeight = '800px';// Add some conditions here

                    config.callbacks.open = function () {
                        $(this.container).find('.mfp-content').css('height', cssHeight);
                    };

                    $('.image-complete').magnificPopup(config);    

                    $("#cargar-modal").modal("hide");
                    
                }else if(respuesta.response == "tipo_incorrecto"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Formato incorrecto de la imagen',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }else{

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Error en el proceso',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }

            }

        }

        // send dates
        xhr.send(dates)

    }


    function cargarImgOpt(idCliente2, filename2, extension2, from2){

        $("#file").val('');
        zone1.hide();
        zone2.show();

        zone3.empty();

        zone3.html(`<div id="thumbBox">
            <img src="documents/clients/${idCliente2}/${filename2}.${extension2}" id="thumb" />
            <br>
            <br>
            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left;" onclick="recortarImg()">Seleccionar</button>
            <button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: left; margin-left: 5px;" onclick="cambiarImg('${from2}')">Cambiar Img</button>
            <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: left; margin-left: 5px;" onclick="cancelar()">Cancelar</button>
            </div>`);

        $('#thumb').imgAreaSelect({
            handles: true,
            fadeSpeed: 300,
            onSelectEnd: function(img, sel){
                x1 = sel.x1;
                y1 = sel.y1;
                x2 = sel.x2;
                y2 = sel.y2;
                anchura = sel.width;
                altura = sel.height;
            }
        });

        id_cliente = idCliente2;
        from = from2; 
        ext = extension2;
    }


    function recortarImg(){

        console.log('entro');

        if (anchura == 0 || altura == 0) return;

        const action = "cargar_recorte";

        $.ajax({
            url:'ajax/solicitudAjax.php', 
            type:'POST', 
            data:{
                'x1':x1,
                'y1':y1,
                'x2':x2,
                'y2':y2,
                'anchura':anchura,
                'altura':altura,
                'id_cliente': id_cliente,
                'action' : action,
                'from': from,
                'ext': ext
            },
            success:function(response){
                console.log(response);
                const respuesta = JSON.parse(response);
                console.log(respuesta);

                var file = `./documents/clients/${respuesta.id_cliente}/${respuesta.id_cliente}-${respuesta.from}.${respuesta.extension}`;

                var lang = "spa";

                if (from == "cc") {
                    lang = "eng";
                }

                const corePath = window.navigator.userAgent.indexOf("Edge") > -1
                ? '/tc_soft/assets/node_modules/tesseract/js/tesseract-core.asm.js'
                : '/tc_soft/assets/node_modules/tesseract/js/tesseract-core.wasm.js';

                console.log(corePath);

                const worker = new Tesseract.TesseractWorker({
                    corePath,
                });

                worker.recognize(file,
                    lang
                )

                .progress(function(packet){
                    console.info(packet)
                    //progressUpdate(packet)
                    $('.response').show();

                })
                .then(function(data){

                    $('.response').hide();
                    console.log(data.text);
                    resultado = data.text;
                    
                    //progressUpdate({ status: 'done', data: data })
                    
                    zone2.hide();
                    zone3.empty();
                    zone4.hide();
                    zone1.show();

                    jQuery(".imgareaselect-border1,.imgareaselect-border2,.imgareaselect-border3,.imgareaselect-border4,.imgareaselect-border2,.imgareaselect-outer, .imgareaselect-handle").css('display', 'none');

                    if(respuesta.from == "cc"){

                        var result = cleanChar(resultado, '.');

                        result = cleanChar(result, ' ');

                    var parameters2 = {
                        "cedula": result,
                        "action": "validar_cedula"
                    };

                    $.ajax({
                        url:'ajax/solicitudAjax.php', 
                        type:'POST', 
                        data:parameters2, 
                        success:function(response2){

                            console.log(response2);

                            if (response2 == "repeat") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Cedula ya registrada',
                                    showConfirmButton: false,
                                    timer: 4000

                                });

                            }else{

                                $("#cedula_cliente").val(result);

                            }

                        }

                    });

                    }else if(respuesta.from == "name"){

                        $("#nombre_cliente").val(resultado);

                    }else if(respuesta.from == "last"){

                        $("#apellidos_cliente").val(resultado);

                    }
                });
            }
        });
    };

    

    function cleanChar(str, char) {
        console.log('cleanChar()'); // HACK: trace
        while (true) {
            var result_1 = str.replace(char, '');
            if (result_1 === str) {
                break;
            }
            str = result_1;
        }
        return str;
    }

    function cambiarImg(from){

        let idCliente = $("#id_cliente").val();

        jQuery(".imgareaselect-border1,.imgareaselect-border2,.imgareaselect-border3,.imgareaselect-border4,.imgareaselect-border2,.imgareaselect-outer, .imgareaselect-handle").css('display', 'none');

        $("#file").val('');
        $("#id_cliente_recorte").val(idCliente);
        $("#from_recorte").val(from);
        $("#cargar-modal").modal("show");

    }

    function cancelar(){

        zone1.show();
        zone2.hide();
        zone3.empty();
        zone4.hide();

    }


    function cancelar2(){

        Swal.fire({
            title: 'Estas seguro?',
            text: "Al cancelar el registro los datos y imagenes se perderan!",
            type: 'danger',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
        }).then((result) => {

            zone1.hide(); //cliente
            zone2.hide(); //crop
            zone3.empty(); //cut
            zone4.show(); //solicitud

        });

    }

    

    $("#registrarNuevoClienteForm").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        //const action = document.querySelector("#action_cliente").value;
        
        const action = "insertar_cliente";

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("registrarNuevoClienteForm"));
        formData.append('action', action);

        insertClientDB(formData);
        
    });


    function insertClientDB(dates){
        //console.log(...dates);
        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/clientesAjax.php', true);
        // pass Info
        xhr.onload = function(){

            //the conection is success
            if (this.status === 200) {

                console.log(xhr.responseText);
                const respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                if(respuesta.response == "success"){

                    tablaClientes.row.add([
                            respuesta.id_cliente, respuesta.cliente_cedula, respuesta.full_cliente, respuesta.contacto, respuesta.email, respuesta.ubicacion, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Cliente" onClick="editarCliente(${respuesta.id_cliente})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Imagenes" onClick="verImagenesC(${respuesta.id_cliente})"><i class="fas fa-eye"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Cliente" onClick="eliminarCliente(${respuesta.id_cliente})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_cliente);

                    zone1.hide(); //cliente
                    zone2.hide(); //crop
                    zone3.empty(); //cut
                    zone4.show(); //solicitud

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Cliente registrado correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }else if(respuesta.response == "falta_cc"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Ingresar cedula',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }else if(respuesta.response == "falta_img"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Faltan imagenes de la C.C.',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }else{

                    Swal.fire({

                        type: 'error',
                        title: 'Oops...',
                        text: 'Eror en el proceso!',
                        showConfirmButton: false,
                        timer: 2000
                    });

                }

                
            }

        }

        // send dates
        xhr.send(dates)
    }

    function iniciarSolicitud(){

        let idCliente = $("#cliente_nombre").val();

        console.log(idCliente);

        if(idCliente != null){

            var parameters = {
                "id_cliente": idCliente,
                "action": "creada"
            };

            $.ajax({

            data: parameters,
             url: 'ajax/solicitudAjax.php',
            type: 'post',
            success: function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if(respuesta.response == "success"){

                        setTimeout(function(){location.href="?page=solicitudes"} , 0);

                    }else{

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    }

                }

            });

        }else{

            Swal.fire({

                position: 'top-end',
                type: 'error',
                title: 'Debes seleccionar un cliente',
                showConfirmButton: false,
                timer: 2500

            });

        }

        

    }

    function cargarImgenCli(from){

        $("#file2").val('');
        $("#from_imagen").val(from);
        $("#cargar-modal2").modal("show");

    }

     $("#uploadFrontalForm2").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        action = "subir_img2";

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("uploadFrontalForm2"));
        formData.append('action', action);

        insertImgClienteDB2(formData);
        
    });

    function insertImgClienteDB2(dates){
        
        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/solicitudAjax.php', true);
        // pass Info
        xhr.onload = function(){

            //the conection is success
            if (this.status === 200) {

                console.log(xhr.responseText);
                const respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                if(respuesta.response == "success"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Imagen Cargada Correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    $(".row-"+respuesta.from).empty();

                    fromTitle = respuesta.from.toUpperCase(); 

                    $(".row-"+respuesta.from).html(`<td>C.C. ${fromTitle}</td>
                                            <td><a href="./documents/clients/${respuesta.id_cliente}/${respuesta.filename}.${respuesta.extension}" class="btn btn-outline-info waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="C.C. ${fromTitle}"><i class="fas fa-eye"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" onclick="EliminarImagenCli(${respuesta.id_cliente}, ${respuesta.from})"><i class="far fa-trash-alt"></i></a>
                                            </td>`);
                    var config = {
                        type: 'image',
                        callbacks: { }
                    };

                    var cssHeight = '800px';// Add some conditions here

                    config.callbacks.open = function () {
                        $(this.container).find('.mfp-content').css('height', cssHeight);
                    };

                    $('.image-complete').magnificPopup(config);

                    $("#cargar-modal2").modal("hide");
                    
                }else if(respuesta.response == "tipo_incorrecto"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Formato incorrecto de la imagen',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }else{

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Error en el proceso',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }

            }

        }

        // send dates
        xhr.send(dates)

    }


function verImagenesC(idCliente) {

    var parametros = {
        "id_cliente": idCliente,
        "action": "select_imagenes"
    };



    $.ajax({
        data: parametros,
        url: 'ajax/clientesAjax.php',
        type: 'post',
        success: function (response) {
            //document.getElementById("header_batch_documents").innerHTML = "Edit City";
            $('#ver-imagenes-modal').modal('show');
            console.log(response);
            const ToJson = JSON.parse(response);
            console.log(ToJson);
            
            var table1 = document.getElementById("table_lot");
            $(table1).empty();

            var theTable = `<table class="table-patient table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>NOMBRE DOCUMENTO</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
            var tableTr = '';
            var nombreImg = '';   
            //for (i = 0; i < ToJson.length; i++) { 
            ToJson.forEach(function(imagenes, index) {
            // Añadimos las propiedades value y label
            
            if (imagenes.imagen_nombre_archivo == imagenes.id_cliente+'-0') {
                nombreImg = "ADELANTE";
            }else if(imagenes.imagen_nombre_archivo == imagenes.id_cliente+'-1'){
                nombreImg = "ATRAS";
            }else if(imagenes.imagen_nombre_archivo == imagenes.id_cliente+'-2'){
                nombreImg = "SELFIE";
            }
            
            tableTr += `<tr>
                            <td>${nombreImg}</td>
                            <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                <a href="documents/clients/${imagenes.id_cliente}/${imagenes.imagen_nombre_archivo}.${imagenes.imagen_extension}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Descargar Imagen" download ><i class="fas fa-image"></i></a>
                            </td>
                        </tr>`;
            });

            theTable += tableTr;
            theTable += `</tbody> 
                            </table>
                            <br>
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: left" onclick="eliminarImagenesCliente(${idCliente})">Eliminar Imagenes</button>
                            <br>`;
            // Añadimos el option al select 
            table1.insertAdjacentHTML('beforeend', theTable);


            imagenesClientes =  $('#dataTableImagenesClientes').DataTable({
                dom: 'Bfrtip',
                buttons: [
                'copy', 'excel', 'pdf', 'print'
                ],
                "order": [[ 0, "desc" ]],
                responsive: true
            });
            imagenesClientes.column(0).visible(false);


            }
    });
}


function eliminarImagenesCliente(idCliente){
    Swal.fire({
            title: 'Estas seguro?',
            text: "Por favor confirmar para eliminar las imagenes de este cliente!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
        }).then((result) => {
            if (result.value) {
                var parameters = {
                    "id_cliente": idCliente,
                    "action": "eliminar_imagenes"
                };

                $.ajax({
    data: parameters,
    url: 'ajax/clientesAjax.php',
    type: 'post',
    success: function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);

                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        tablaClientes.row(".row-"+respuesta.id_cliente).remove().draw(false);

                        tablaClientes.row.add([
                            respuesta.id_cliente, respuesta.cliente_cedula, respuesta.full_cliente, respuesta.contacto, respuesta.email, respuesta.ubicacion, `<div class="jsgrid-cell jsgrid-control-field jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Cliente" onClick="editarCliente(${respuesta.id_cliente})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagenes" onClick="subirImagenesC(${respuesta.id_cliente})"><i class="fas fa-upload"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Cliente" onClick="eliminarCliente(${respuesta.id_cliente})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_cliente);

                        $.toast({
                                heading: 'Genial!',
                                text: 'Imagenes Eliminadas Correctamente.',
                                position: 'top-center',
                                loaderBg:'#00c292',
                                icon: 'success',
                                hideAfter: 4500, 
                                stack: 6
                        });

                        $("#ver-imagenes-modal").modal("hide");

                    }else{

                        $.toast({

                                heading: 'Error!',
                                text: 'Error en el proceso.',
                                position: 'top-center',
                                loaderBg:'#e46a76',
                                icon: 'error',
                                hideAfter: 4500, 
                                stack: 6
                        });

                    }
                }   

                });
            }
        });
}


function eliminarCliente(idCliente){

    Swal.fire({
            title: 'Estas seguro?',
            text: "Por favor confirmar para eliminar este cliente!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {

            if (result.value) {
                var parameters = {
                    "id_cliente": idCliente,
                    "action": "eliminar_cliente"
                };

                $.ajax({
    data: parameters,
    url: 'ajax/clientesAjax.php',
    type: 'post',
    success: function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);

                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        tablaClientes.row(".row-"+respuesta.id_cliente).remove().draw(false);

                        $.toast({
                                heading: 'Genial!',
                                text: 'Cliente Eliminado Correctamente.',
                                position: 'top-center',
                                loaderBg:'#00c292',
                                icon: 'success',
                                hideAfter: 4500, 
                                stack: 6
                        });

                    }else if(respuesta.response == "error_solicitudes"){

                        $.toast({
                                heading: 'Error!',
                                text: 'Cliente con solicitudes activas.',
                                position: 'top-center',
                                loaderBg:'#e46a76',
                                icon: 'error',
                                hideAfter: 4500, 
                                stack: 6
                        });

                    }else{

                        $.toast({

                                heading: 'Error!',
                                text: 'Error en el proceso.',
                                position: 'top-center',
                                loaderBg:'#e46a76',
                                icon: 'error',
                                hideAfter: 4500, 
                                stack: 6
                        });

                    }
                }   

                });
            }
    });

}


function subirImagenesC(idCliente){

    $("#file").val();
    $("#id_cliente_img").val(idCliente);
    $("#subir-imgenes-modal").modal("show");

}


$("#uploadImagenesForm").on("submit", function(e){

    // evitamos que se envie por defecto
    e.preventDefault();

    const action = "upload_imagenes";

     // create FormData with dates of formulary       
    var formData = new FormData(document.getElementById("uploadImagenesForm"));
    formData.append("action", action);

    subirImagenesDB(formData);
        
});

function subirImagenesDB(dates){
    //console.log(...dates);
    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/clientesAjax.php', true);
    // pass Info
    xhr.onload = function(){

        //the conection is success
        if (this.status === 200) {

            console.log(xhr.responseText);
            const respuesta = JSON.parse(xhr.responseText);
            console.log(respuesta);

            if(respuesta.response == "success"){

                Swal.fire({

                    position: 'top-end',
                    type: 'success',
                    title: 'Imagenes cargadas correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                var aImagenes = `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Imagenes" onClick="verImagenesC(${respuesta.id_cliente})"><i class="fas fa-eye"></i></a>`;

                tablaClientes.row(".row-"+respuesta.id_cliente).remove().draw(false);

                tablaClientes.row.add([
                        respuesta.id_cliente, respuesta.cliente_cedula, respuesta.full_cliente, respuesta.contacto, respuesta.email, respuesta.ubicacion, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Cliente" onClick="editarCliente(${respuesta.id_cliente})"><i class="mdi mdi-pencil"></i></a>${aImagenes}<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Cliente" onClick="eliminarCliente(${respuesta.id_cliente})"><i class="fas fa-trash-alt"></i></a></div>`
                    ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_cliente);

                $("#subir-imgenes-modal").modal("hide");

            }else{

                Swal.fire({

                    position: 'top-end',
                    type: 'error',
                    title: 'Error en el proceso',
                    showConfirmButton: false,
                    timer: 2500

                });

            }

        }

    }

    // send dates
    xhr.send(dates)
}

function editarCliente(idCliente) {

    var parameters = {
        "action": "select_cliente",
        "id_cliente": idCliente
    };

    $.ajax({

        url:'ajax/clientesAjax.php', 
        type:'POST', 
        data: parameters,
        success:function(response){

            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            $("#cedula_cliente").val(respuesta[0].cliente_cedula);
            $("#nombre_cliente").val(respuesta[0].cliente_nombre);
            $("#apellidos_cliente").val(respuesta[0].cliente_apellidos);
            $("#contacto_cliente").val(respuesta[0].cliente_numero_contacto);
            $("#email_cliente").val(respuesta[0].cliente_email);
            if (respuesta[0].cliente_sexo == "M") {
                $("#cliente_m").prop('checked', true);
                $("#cliente_f").prop('checked', false);
            }else{
                $("#cliente_f").prop('checked', true);
                $("#cliente_m").prop('checked', false);
            }
            $("#direccion_cliente").val(respuesta[0].cliente_direccion);
            $("#dob_cliente").val(respuesta[0].cliente_dob);
            $("#departamento").val(respuesta[0].id_departamento);
            $("#departamento").trigger('change');
            $("#ciudad").val(respuesta[0].ciudad_id);
            $("#ciudad").trigger('change');
            $("#id_cliente").val(idCliente);
            zone4.hide();
            zone5.empty();
            zone1.show();

            tableImagenesC = `<table id="dataTableImgClientes" class="table display table-bordered table-striped no-wrap">
            <thead>
            <tr>
            <th>IMAGEN</th>
            <th></th>
            </tr>
            </thead>
            <tbody>`;

            var nombreImg = '';
            var identified = '';

            var config = {
                type: 'image',
                callbacks: { }
            };

            var cssHeight = '800px';// Add some conditions here

            config.callbacks.open = function () {
                $(this.container).find('.mfp-content').css('height', cssHeight);
            };

            respuesta[1].forEach(function(imagenes, index) {

                    if (imagenes.img == idCliente+"-0") {

                        nombreImg = "C.C. FRONTAL";
                        identified = "frontal";

                    }else if(imagenes.img == idCliente+"-1"){

                        nombreImg = "C.C. ATRAS";
                        identified = "atras";

                    }else if(imagenes.img == idCliente+"-2"){

                        nombreImg = "C.C. SELFIE";
                        identified = "selfie";

                    }

                    tableImagenesC += `<tr class="row-${identified}">
                                        <td>${nombreImg}</td>
                                        <td><a href="./documents/clients/${idCliente}/${imagenes.img}.${imagenes.ext}" class="btn btn-outline-info waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="${nombreImg}"><i class="fas fa-eye"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" onclick="EliminarImagenCli(${idCliente}, ${identified})"><i class="far fa-trash-alt"></i></a>
                                        </td>   
                                        </tr>`;

            });

            tableImagenesC += `</tbody>
            </table>`;

            zone5.append(tableImagenesC);

            $('.image-complete').magnificPopup(config); 

        }
    });
}

function EliminarImagenCli(idCliente, from){

    Swal.fire({
            title: 'Estas seguro?',
            text: "Por favor confirmar para eliminar esta imagen!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {

            if (result.value) {
                var parameters = {
                    "id_cliente": idCliente,
                    "from": from,
                    "action": "eliminar_imagen"
                };

                $.ajax({
    data: parameters,
    url: 'ajax/solicitudAjax.php',
    type: 'post',
    success: function (response) {

                        console.log(response);
                        const respuesta = JSON.parse(response);

                        console.log(respuesta);

                        if (respuesta.response == "success") {

                            

                        }else{

                        }

                    }

                });
            }
    });
}

</script>