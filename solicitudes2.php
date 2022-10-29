
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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="nuevaSolicitud()">Nueva Solicitud</button>
                                    <h4 class="card-title">Solicitudes</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableSolicitudes" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>CLIENTE</th>
                                                               <th>PRODUCTO</th>
                                                               <th>CONTACTO</th>
                                                               <th>CORREO</th>
                                                               <th>INICIAL</th>
                                                               <th>ESTADO</th>
                                                               <th></th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT solicitudes.id AS id_solicitud, cliente_detalles.cliente_nombre, cliente_detalles.cliente_apellidos, productos.modelo_producto, cliente_detalles.cliente_numero_contacto, cliente_detalles.cliente_email, solicitudes.inicial, solicitudes.id_estado_solicitud,estados_solicitudes.texto_estado FROM solicitudes LEFT JOIN clientes ON solicitudes.id_cliente = clientes.id LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN productos ON solicitudes.id_producto = productos.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.del = 0 ORDER BY solicitudes.id DESC";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $texto_estado = $row1['texto_estado'];

                                                            $id_solicitud = $row1['id_solicitud'];
                                                            $cliente_nombre = $row1['cliente_nombre'].' '.$row1['cliente_apellidos'];
                                                            $modelo_producto = $row1['modelo_producto'];
                                                            $cliente_numero_contacto = $row1['cliente_numero_contacto'];
                                                            $contacto = '('.substr($cliente_numero_contacto, 0,3).') '.substr($cliente_numero_contacto, 3,3).'-'.substr($cliente_numero_contacto, 6,4);
                                                            $cliente_email = $row1['cliente_email'];
                                                            $inicial = $row1['inicial'];
                                                            if($texto_estado == 9){
                                                                $modelo_producto = 'N/A';
                                                                $inicial = 'N/A';
                                                            }
                                                            $id_estado_solicitud = $row1['id_estado_solicitud'];

                                                    ?>
                                                               <tr class="row-<?=$id_solicitud?>">
                                                               <td><?=$id_solicitud?></td>
                                                               <td><?=$cliente_nombre?></td>
                                                               <td><?=$modelo_producto?></td>
                                                               <td><?=$contacto?></td>
                                                               <td><?=$cliente_email?></td>
                                                               <td><?=number_format($inicial, 2)?></td>
                                                                <?php if($id_estado_solicitud == 9){?>
                                                                <td><span class="label label-info">Sin producto</span></td>
                                                                <?php }else if($id_estado_solicitud == 1){ ?>
                                                                <td><span class="label label-info">Inicial</span></td>
                                                                <?php }else if($id_estado_solicitud == 2){ ?>
                                                                <td><span class="label label-info">Pdte. Verificación</span></td>
                                                                <? } ?>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="?page=solicitud&id_solicitud=<?=$id_solicitud?>" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Detalles solicitud"><i class="mdi mdi-pencil"></i></a>
                                                                <?php if($id_estado_solicitud == 1){?>
                                                                    <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación" onclick="selecMedio(<?=$id_solicitud?>)"><i class="far fa-paper-plane"></i></a>
                                                                <?php }else if($id_estado_solicitud == 2){ ?>
                                                                    <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resultado Cifin" onclick="selecCifin(<?=$id_solicitud?>)"><i class="fas fa-credit-card"></i></a>
                                                                <? }else if($id_estado_solicitud == 11){ ?>
                                                                    <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación" onclick="openCode(<?=$id_solicitud?>)"><i class="fas fa-comment-alt"></i></a>
                                                                <? } ?>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(<?= $id_solicitud ?>)"><i class="fas fa-trash-alt"></i></a>
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
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->    
                </div>
            </div>

<!-- Nuevo Cliente modal -->
        <div id="solicitudes-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Crear Solicitud</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="crearSolicitudForm" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <h2 id="time-expire"></h2>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                      <h4 id="explication"></h4>
                                    </div>
                                      <div class="code-container">
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                      </div>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="id_solicitud_codigo" id="id_solicitud_codigo" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->

<!-- Nuevo Cliente modal -->
        <div id="confirmacion-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Enviar confirmación</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="seleccionarConfirmForm" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Medio de envio:<span class="text-danger">&nbsp;*</span></label>
                                <div class="input-group">
                                    <fieldset class="controls">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" value="1" name="medio_envio" id="medio_email" class="custom-control-input" required>
                                            <label class="custom-control-label" for="medio_email">Enviar por Email</label>
                                        </div>
                                    </fieldset>
                                    &nbsp; &nbsp;
                                    <fieldset>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" value="2" name="medio_envio" id="medio_sms" class="custom-control-input">
                                        <label class="custom-control-label" for="medio_sms">Enviar por SMS</label>
                                    </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="id_solicitud" id="id_solicitud" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->

<!-- Nuevo Cliente modal -->
        <div id="codigo-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirmar Codigo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="confirmCodigoModal" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-center">
                                        <h2 id="time-expire"></h2>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                      <h4 id="explication"></h4>
                                    </div>
                                      <div class="code-container">
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                        <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                      </div>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="id_solicitud_codigo" id="id_solicitud_codigo" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->


<!-- resultado cifin modal -->

        <div id="resultadosc-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Resultado Estudio en cifin</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="resultadosCifinForm" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Estados Cifin:<span class="text-danger">&nbsp;*</span></label>
                                <div class="input-group">
                                    <select  class="form-control select2Class" name="resultados_cifin" id="resultados_cifin" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                        <option value="placeholder" disabled>Seleccionar Estado</option>
                                        <?php
                                        $query = "select id, estado from resultados_cifin order by estado";
                                        $result = qry($query);
                                        while($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <option value="<?= $row['id']?>"><?= $row['estado']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="id_solicitud_cifin" id="id_solicitud_cifin" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->



<script>

    $(document).ready(function() {

        const codes = document.querySelectorAll('.code')

        codes[0].focus()

        codes.forEach((code, idx) => {
           code.addEventListener('keydown', (e) => {
               if(e.key >= 0 && e.key <=9) {
                   codes[idx].value = ''
                   setTimeout(() => codes[idx + 1].focus(), 10)
               } else if(e.key === 'Backspace') {
                   setTimeout(() => codes[idx - 1].focus(), 10)
               }
           })
        })

    });

    function nuevaSolicitud(){

    };

    function selecMedio(idSolicitud){
        $("medio_email").prop('checked', false);
        $("medio_sms").prop('checked', false);
        $("#id_solicitud").val(idSolicitud);
        $("#confirmacion-modal").modal("show");
    }

    $("#seleccionarConfirmForm").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        const action = "confirmacion";

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("seleccionarConfirmForm"));
        formData.append("action", action);

        confirmacionDB(formData);
        
    });

    function confirmacionDB(dates){
        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                        title: 'Codigo enviado',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    $("#confirmacion-modal").modal("hide");

                    tablaSolicitudes.row(".row-"+respuesta.id_solicitud).remove().draw(false);

                    tablaSolicitudes.row.add([
                        respuesta.id_solicitud, respuesta.nombre, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">Inicial</span>`, `<div class="jsgrid-align-center"><a href="?page=solicitud&id_solicitud=${respuesta.id_solicitud}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Detalles solicitud"><i class="mdi mdi-pencil"></i></a>
                            <a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación" onclick="openCode(${respuesta.id_confirm_solicitud})"><i class="fas fa-comment-alt"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                    ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_solicitud);

                    openCode(respuesta.id_confirm_solicitud);
                    
                }else{

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Error en el proceso',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    $("#confirmacion-modal").modal("hide");

                }

                

            }

        }

        // send dates
        xhr.send(dates)
    }


    function openCode(idConfirmSolicitud){

        var parameters = {
            "id_confirmacion_solicitud": idConfirmSolicitud,
            "action": "select_code"
        };

        $.ajax({

            data:  parameters,
            url:   'ajax/solicitudesAjax.php',
            type:  'post',
           success:  function (response) {
                console.log(response);
                const respuesta = JSON.parse(response);
                console.log(respuesta);

                $("#id_solicitud_codigo").val(respuesta[0].id);

                let explicacion = '';

                if (respuesta[0].id_medio_envio  == 1) {

                    explicacion = `Escribe el codigo enviado por Email al cliente.`;

                }else{

                    explicacion = `Escribe el codigo enviado por SMS al cliente.`;

                }

                $("#explication").html(explicacion);

                $('#time-expire').countdown(respuesta[0].fecha_vencimiento, {elapse: true})
                .on('update.countdown', function(event) {
                  var $this = $(this);
                  if (event.elapsed) {
                        //$this.html(event.strftime('<span>%H:%M:%S</span>'));
                        $('#time-expire').countdown('stop');

                        let timerInterval
                        Swal.fire({
                            title: 'Se agoto el Tiempo!',
                            html: 'Vuelve a enviar un codigo nuevamente.',
                            timer: 6000,
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        }).then((result) => {
                            
                    });

                    } else {
                        $this.html(event.strftime('<span>%H:%M:%S</span>'));
                    }
                });

                $("#codigo-modal").modal("show");
            }

        });

        

    }


    $("#confirmCodigoModal").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        const action = "confirmar_codigo";

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("confirmCodigoModal"));
        formData.append("action", action);

        confirmarCodigoDB(formData);
        
    });


    function confirmarCodigoDB(dates){

        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                        title: 'Codigo Confirmado',
                        showConfirmButton: false,
                        timer: 2500

                    });


                    tablaSolicitudes.row(".row-"+respuesta.id_solicitud).remove().draw(false);

                    tablaSolicitudes.row.add([
                        respuesta.id_solicitud, respuesta.nombre, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">Pendiente Verificación</span>`, `<div class="jsgrid-align-center"><a href="?page=solicitud&id_solicitud=${respuesta.id_solicitud}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Detalles solicitud"><i class="mdi mdi-pencil"></i></a>
                            <a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación" onclick="openCode(${respuesta.id_confirm_solicitud})"><i class="fas fa-comment-alt"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                    ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_solicitud);


                   $("#codigo-modal").modal("hide");


                }else if(respuesta.response == "error_codigo"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Codigo Incorrecto',
                        showConfirmButton: false,
                        timer: 2500

                    });


                    $(".code").val('');

                    
                }else{

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Error en el proceso',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    $("#codigo-modal").modal("hide");


                }

                

            }

        }

        // send dates
        xhr.send(dates)

    }

    function selecCifin(idSolicitud){
        $("#resultados_cifin").val('placeholder');
        $("#resultados_cifin").trigger("change");
        $("#id_solicitud_cifin").val(idSolicitud);
        $("#resultadosc-modal").modal("show");
    }


    $("#resultadosCifinForm").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        const action = "insertar_cifin";

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("resultadosCifinForm"));
        formData.append("action", action);

        registrarCifinDB(formData);
        
    });

    function registrarCifinDB(dates){
        
    }

</script>