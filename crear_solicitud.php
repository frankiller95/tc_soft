
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
                            <h4 class="text-themecolor"><?=str_replace('_', ' ', ucwords($page))?></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                                    <li class="breadcrumb-item active"><?=str_replace('_', ' ', ucwords($page))?></li>
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
                                    <h4 class="card-title">Buscar Cliente</h4>
                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Busqueda por nombre o cedula:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <select  class="form-control select2Class" name="cliente_nombre" id="cliente_nombre" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                        <option value="placeholder" disabled selected>Seleccionar Cliente</option>
                                                        <?php
                                                        $query = "select id_cliente, cliente_nombre, cliente_apellidos, cliente_cedula from clientes left join cliente_detalles on cliente_detalles.id_cliente = clientes.id where clientes.del = 0 order by cliente_apellidos";
                                                        $result = qry($query);
                                                        while($row = mysqli_fetch_array($result)) {
                                                        ?>
                                                            <option value="<?= $row['id_cliente']?>"><?= $row['cliente_apellidos'].' , '.$row['cliente_nombre'].' - '.$row['cliente_cedula']?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">     
                                                <div class="input-group" style="top: 25px;">
                                                    <label>No Existe?</label>&nbsp;
                                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: left" onclick="addCliente()">Agregar Cliente</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;" onclick="iniciarSolicitud()">Crear solicitud</button>
                                        </div>
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
        <div id="nuevo-cliente-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Registrar Cliente</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarNuevoClienteForm" method="post">
                        <div class="modal-body">
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
                            <div id="zone-busqueda-lista">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="javascript:void(0)"> <img class="img-thumbnail img-responsive" alt="attachment" src="./assets/images/big/img1.jpg"> </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="javascript:void(0)"> <img class="img-thumbnail img-responsive" alt="attachment" src="./assets/images/big/img2.jpg"> </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="javascript:void(0)"> <img class="img-thumbnail img-responsive" alt="attachment" src="./assets/images/big/img3.jpg"> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Cedula:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="cedula_cliente" id="cedula_cliente" placeholder="Cedula del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" placeholder="nombre del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="apellidos_cliente" id="apellidos_cliente" placeholder="Apellidos del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input class="form-control phoneNumber" name="contacto_cliente" id="contacto_cliente" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Dirección:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" placeholder="Dirección del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de nacimiento:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control todayColor" name="dob_cliente" id="dob_cliente" placeholder="Fecha de nacimiento" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div id="ciudad-cliente-zone">
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
                                    <label>Ciudad de residencia:</label>
                                    <div class="input-group">
                                        <select  class="form-control select2Class" name="ciudad_cliente" id="ciudad" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                            <option value="placeholder" disabled>Seleccionar Ciudad</option>
                                            <option disabled>Selecciona un departamento</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <input type="hidden" name="id_cliente" id="id_cliente" value="">
                            <input type="hidden" name="action" id="action_cliente" value="">
                            <input type="hidden" name="id_usuario" value="<?=$id_usuario?>">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->


<script>

    
    function addCliente(){
        $("#file").val('');
        $("#zone-busqueda-lista").hide();
        $("#ciudad-cliente-zone").hide();
        $("#departamento").val('placeholder');
        $("#departamento").trigger('change');
        $("#ciudad").val('placeholder');
        $("#ciudad").trigger('change');
        $("#zone-busqueda-lista").empty();
        $("#action_cliente").val('insertar_img_cliente');
        $("#id_cliente").val('');
        $("#nuevo-cliente-modal").modal("show");
    }

    $("#registrarNuevoClienteForm").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        const action = document.querySelector("#action_cliente").value;

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("registrarNuevoClienteForm"));

        if(action == 'insertar_img_cliente'){

            insertImgClienteDB(formData);

        }else{

            insertClientDB(formData);

        }
        
    });

    function insertImgClienteDB(dates){
        //console.log(...dates);
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
                        title: 'Imagenes cargadas correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    var zone1 = $("#zone-busqueda-lista");

                    var zone2 = $("#ciudad-cliente-zone");

                    var inputs = `<div class="form-group">
                                    <div class="row">`;

                    var imagenesRow = '';

                    respuesta[0].forEach(function(imagenes, index) {
                        imagenesRow += `<div class="col-md-4"><a href="./documents/clients/${respuesta.id_cliente}/${imagenes.filename}.${imagenes.extension}" class="image-complete" data-toggle="tooltip" data-placement="top" title="Ver imagen"><img class="img-thumbnail img-responsive" alt="attachment" src="./documents/clients/${respuesta.id_cliente}/${imagenes.filename}.${imagenes.extension}" width="140" height="140"></a>
                            </div>`;
                    });

                    inputs += imagenesRow;

                    inputs += `</div>
                                </div>
                                <div class="form-group">
                                    <label>Cedula:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="cedula_cliente" id="cedula_cliente" placeholder="Cedula del cliente" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="apellidos_cliente" id="apellidos_cliente" placeholder="Apellidos del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input class="form-control phoneNumber" name="contacto_cliente" id="contacto_cliente" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" name="email_cliente" id="email_cliente" placeholder="Email del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
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
                                <div class="form-group">
                                    <label>Dirección:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" placeholder="Dirección del cliente" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de nacimiento:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control todayColor" name="dob_cliente" id="dob_cliente" placeholder="Fecha de nacimiento" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>`;

                        zone1.html(inputs);

                        zone1.show();
                        zone2.show();

                        var config = {
                            type: 'image',
                            callbacks: { }
                        };

                        var cssHeight = '800px';// Add some conditions here

                        config.callbacks.open = function () {
                            $(this.container).find('.mfp-content').css('height', cssHeight);
                        };

                        $('.image-complete').magnificPopup(config);

                        jQuery('.todayColor').datepicker({
                            autoclose: true,
                            todayHighlight: true
                        });

                        if ($('#contacto_cliente').length) {
                            const inputElement = document.getElementById('contacto_cliente');
                            inputElement.addEventListener('keydown',enforceFormat);
                            inputElement.addEventListener('keyup',formatToPhone);
                        }

                        $("#action_cliente").val('insertar_cliente');

                        $("#id_cliente").val(respuesta.id_cliente);

                }else if(respuesta.response == "3_img"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Deben ser 3 imagenes',
                        showConfirmButton: false,
                        timer: 2500

                    });

                }else if(respuesta.response == "tipo_incorrecto"){

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Formato incorrecto en una o varias imagenes',
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

    function insertClientDB(dates){
        //console.log(...dates);
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

                    var data = {
                        id: respuesta.id_cliente,
                        text: respuesta.apellido_cliente+' , '+respuesta.nombre_cliente+' - '+respuesta.cedula_cliente
                    };

                    var newOption = new Option(data.text, data.id, false, false);

                    $('#cliente_nombre').append(newOption).trigger('change');
                    $('#cliente_nombre').val(respuesta.id_cliente); 
                    $('#cliente_nombre').trigger('change');

                    $("#nuevo-cliente-modal").modal("hide");

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Cliente registrado correctamente',
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
                    })

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

               data:  parameters,
               url:   'ajax/solicitudAjax.php',
               type:  'post',
               success:  function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if(respuesta.response == "success"){

                        setTimeout(function(){location.href="?page=solicitud&id_solicitud="+respuesta.id_solicitud} , 0);

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

</script>
