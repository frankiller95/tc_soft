<?php

if (isset($_GET['id_solicitud'])) {

    $id_solicitud = $_GET['id_solicitud'];
    $id_cliente = execute_scalar("SELECT id_cliente FROM solicitudes WHERE id = $id_solicitud");

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
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Solicitud de credito</h4>
                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                        </div>
                                    </div>
                                    <br>
                                    <form class="smart-form" id="solicitudForm" method="post" action="">
                                    <div class="row">
                                        <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Marca del dispositivo:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <select  class="form-control select2Class" name="solicitud_marca" id="marca" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                        <option value="placeholder" disabled selected>Seleccionar Marca</option>
                                                        <?php
                                                        $query = "SELECT id, marca_producto FROM marcas WHERE  del = 0 ORDER BY marca_producto ASC";
                                                        $result = qry($query);
                                                        while($row = mysqli_fetch_array($result)) {
                                                        ?>
                                                            <option value="<?= $row['id']?>"><?= $row['marca_producto']?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Producto:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <select  class="form-control select2Class" name="solicitud_producto" id="producto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" readonly required>
                                                        <option value="placeholder" disabled selected>Seleccionar Dispositivo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Precio del modelo:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <input type="number" step="0.00" class="form-control" name="precio_producto" id="precio_producto" placeholder="Precio del producto" autocomplete="ÑÖcompletes">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="zone2" style="display: none">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>Terminos del prestamo:<span class="text-danger">*</span></h5>
                                                <div id="zone-terminos">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Frecuencia:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <select  class="form-control select2Class" name="frecuencia_pago" id="frecuencia_pago" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                        <option value="placeholder" disabled selected>Seleccionar Frecuencia de pago</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>Pago Inicial:<span class="text-danger">*</span></h5>
                                                <div id="zone-pagos">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?=$id_solicitud?>">
                                            <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;">Crear Soliictud</button>
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
   

<script>

    $('#producto').change(function() {

        let idProducto = $("#producto").val();

        var parameters = {
            "id_producto": idProducto,
            "action": "select_precio_producto"
        };

        $.ajax({

               data:  parameters,
               url:   'ajax/solicitudAjax.php',
               type:  'post',
               success:  function (response) {
                    console.log(response);
                    var respuesta = JSON.parse(response);

                    console.log(respuesta);

                    $("#precio_producto").val(respuesta.precio_producto);

                    $("#zone2").show();

                    var zoneTerminos = $("#zone-terminos");

                    zoneTerminos.empty();

                    var md = '';

                    var first = '';

                    var newOption = '';

                    //$("#ciudad_usuario").val(null).trigger('change');
                    var zoneFrecuencias = $('#frecuencia_pago');

                    zoneFrecuencias.empty();

                    var zoneIniciales = $("#zone-pagos");

                    zoneIniciales.empty();

                    respuesta[0].forEach(function(terminos, index) {

                        console.log(terminos);

                        if(index == 0){

                            first = `class="controls"`;
                        }


                        md += `<fieldset ${first}>
                                    <div class="custom-control custom-radio">
                                            <input type="radio" value="${terminos.id_termino}" name="termino_prestamo" required id="termino${terminos.numero_meses}" class="custom-control-input">
                                            <label class="custom-control-label" for="termino${terminos.numero_meses}">${terminos.numero_meses}&nbsp;Meses</label>
                                        </div>
                                </fieldset>`;

                    });

                    zoneTerminos.append(md);

                    respuesta[1].forEach(function(frecuencias, index) {
                        //console.log(producto);

                        newOption = new Option(frecuencias.frecuencia, frecuencias.id_frecuencia_pago, false, false);
                        zoneFrecuencias.append(newOption).trigger('change');
                        
                    });

                    md = '';

                    respuesta[2].forEach(function(iniciales, index) {

                        console.log(iniciales);

                        if(index == 0){

                            first = `class="controls"`;
                        }


                        md += `<fieldset ${first}>
                                    <div class="custom-control custom-radio">
                                            <input type="radio" value="${iniciales.id_porcentaje}" name="porcentaje_inicial" required id="inicial${iniciales.porcentaje}" class="custom-control-input">
                                            <label class="custom-control-label" for="inicial${iniciales.porcentaje}"><span class="text-danger">$</span>${iniciales.valor_porcentaje}&nbsp;(${iniciales.porcentaje}%)</label>
                                        </div>
                                </fieldset>`;

                    });

                    zoneIniciales.append(md);

               }

        });

    });

    $("#solicitudForm").on("submit", function(e){

                // evitamos que se envie por defecto
                e.preventDefault();
    
                 // create FormData with dates of formulary       
                var formData = new FormData(document.getElementById("solicitudForm"));

                const action = "inicial";

                formData.append('action', action);

                solicitudInicialDB(formData);
    });

    function solicitudInicialDB(dates){
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
                        title: 'Solicitud registrada correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

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
        }

        // send dates
        xhr.send(dates) 
    }

</script>

<?php 

}else{

?>


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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        No hay un cliente seleccionado
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

}

?>