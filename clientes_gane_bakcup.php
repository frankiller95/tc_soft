<?

if (profile(11, $id_usuario) == 1) {

    $validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario");

    if ($validate_gane == 1) {
        $id_punto_gane = execute_scalar("SELECT id_punto_gane FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario");
        $nombre_punto = execute_scalar("SELECT AGENCIA FROM puntos_gane WHERE id = $id_punto_gane");
    } else {
        $nombre_punto = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) AS nombre_usuario  FROM usuarios WHERE id = $id_usuario");
    }

    $hoy = getdate();

    $zero = 0;

    if ($hoy['mon'] < 10) {
        $hoy['mon'] = $zero . $hoy['mon'];
    }

    if ($hoy['mday'] < 10) {
        $hoy['mday'] = $zero . $hoy['mday'];
    }

    $fecha_actual = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];

    $total_prospectos_cont = execute_scalar("SELECT COUNT(id) AS TOTAL FROM prospectos WHERE prospectos.del = 0 AND prospectos.id_usuario_responsable = $id_usuario AND prospectos.fecha_registro >= '$fecha_actual 00:00:00' AND prospectos.fecha_registro <= '$fecha_actual 23:59:00' ORDER BY prospectos.fecha_registro DESC");

    $id_confirmacion = execute_scalar("SELECT confirmacion_prospectos.id FROM confirmacion_prospectos WHERE confirmacion_prospectos.id_usuario = $id_usuario AND estado_confirmacion = 0 AND del = 0");

    $proceso = 1;

    $contacto = '';

    $foto_tipo = 0;

    $preview_img = '';

    if(!empty($id_confirmacion) || $id_confirmacion !== ''){

        $proceso = execute_scalar("SELECT proceso FROM confirmacion_prospectos WHERE id = $id_confirmacion");

        if($proceso == 2){
            $foto_tipo = 0;
        }else if($proceso == 3){
            $foto_tipo = 1;
        }else if($proceso == 4){
            $foto_tipo = 2;
        }

        $img_nombre_archivo = $id_confirmacion.'-'.$foto_tipo;

        $validate_preview = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND imagen_nombre_archivo = '$img_nombre_archivo' AND del = 0");
        

        if($validate_preview <> 0){
            $preview_img = '<br><br><img src="./documents/prospects/'.$id_confirmacion.'/'.$id_confirmacion.'-'.$foto_tipo.'.jpg"  id="img_preview" width="250" height="250" style="float: right"></img>';
        }

        $contacto = execute_scalar("SELECT numero_contacto_confirmacion FROM confirmacion_prospectos WHERE id = $id_confirmacion");
        $contacto = substr($contacto, 0,3).'-'.substr($contacto, 3,3).'-'.substr($contacto, 6,4);



    }

?>
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
    <div class="page-wrapper" id="pc-zone">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor"><?= str_replace('_', ' ', ucwords($page)) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item active"><?= str_replace('_', ' ', ucwords($page)) ?></li>
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
            <div class="row" id="cliente-zone" style="display: <?if($proceso == 5){?>block<?}else{?>none<?}?>;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Ingresa la información del cliente</h4>
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
                                                <input type="text" class="form-control" name="cedula_prospecto" id="cedula_prospecto" placeholder="Cedula del cliente" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="16">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nombre_prospecto" id="nombre_prospecto" placeholder="Nombre del cliente" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="apellidos_prospecto" id="apellidos_prospecto" placeholder="Apellidos del cliente" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input class="form-control phoneNumber" name="contacto_prospecto" id="contacto_prospecto" placeholder="123-456-7890" value="<?=$contacto?>" autocomplete="ÑÖcompletes" maxlength="16" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Numero de whatsaap(Opcional):</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control phoneNumber" name="contacto2_whatsaap" id="contacto2_whatsaap" placeholder="123-456-7890" onkeypress="return validaNumerics(event)" autocomplete="ÑÖcompletes" maxlength="16">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ciudad de residencia:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="ciudad_prospecto" id="ciudad_prospecto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                    <option value="placeholder" disabled selected>Seleccionar Ciudad</option>
                                                    <?php
                                                    $query = "select ciudades.id, ciudades.ciudad, departamento from ciudades left join departamentos on ciudades.id_departamento = departamentos.id order by ciudad";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {

                                                    ?>
                                                        <option value="<?= $row['id'] ?>" <? if ($row['ciudad'] == "CALI") { ?> selected <? } ?>><?= $row['ciudad'] . ' - ' . $row['departamento'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>MARCA:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="marca_prospecto" id="marca_prospecto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                    <option value="placeholder" disabled selected>Seleccionar Marca</option>
                                                    <?php
                                                    $query = "SELECT marcas.id AS id_marca, marcas.marca_producto, marcas.del FROM marcas WHERE (marca_producto = 'MOTOROLA' || marca_producto = 'SAMSUNG' || marca_producto = 'XIAOMI') AND marcas.del = 0 ORDER BY marca_producto";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?= $row['id_marca'] ?>"><?= $row['marca_producto'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario ?>">
                                        <input type="hidden" name="id_confirmacion3" id="id_confirmacion3" value="<?=$id_confirmacion?>">
                                        <input type="hidden" name=" action" id="action_prospecto" value="insertar_prospecto">
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="reiniciar(<?=$id_usuario?>)">Reiniciar</button>
                                        <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;" id="submitBtnCliente">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="tratamiento-zone" style="display: <?if($proceso == 1){?>block<?}else{?>none<?}?>;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Confirmar tratamiento de datos del cliente.</h4>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                </div>
                            </div>
                            <br>
                            <form action="" method="post" class="smart-form" id="confirmarTratamientoProspectoForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input class="form-control phoneNumber" name="contacto_prospecto_1" id="contacto_prospecto_1" placeholder="123-456-7890" autocomplete="ÑÖcompletes" maxlength="16" required>
                                                <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-left: 10px;" onclick="enviarCodigoSms(<?=$id_usuario?>)">Enviar Codigo</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Codigo:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input class="form-control" name="codigo_confirmacion" id="codigo_confirmacion" placeholder="Escribe el codigo del usuario" autocomplete="ÑÖcompletes" maxlength="6" onkeypress="return validaNumerics(event)" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                                        <input type="hidden" name="id_confirmacion" id="id_confirmacion" value="<?=$id_confirmacion?>">
                                        <input type="hidden" name=" action" value="mensaje_prospecto">
                                        <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;">SIGUIENTE</button>
                                        <p style="float: left; color: #0000ff; font-weight: 700;"><u>PROSPECTOS DEL DÍA&nbsp;<span id="contador_prospectos_2">(<?=$total_prospectos_cont?>)</span></u></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="fotos-zone" style="display: <?if($proceso == 2 || $proceso == 3 || $proceso == 4){?>block<?}else{?>none<?}?>;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Tomar Foto </h4>
                            <div id="zone-webcam">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h1 id="titulo_captura"></h1>
                                        <select name="listaDeDispositivos" id="listaDeDispositivos"></select>
                                        <button type="button" id="boton" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; font-size: 25px;">Tomar foto</button>
                                        <br>
                                        <br>
                                        <p id="estado" style="font-size: 20px; font-weight: 700;"><?if($proceso == 2){?>COLOCA LA PARTE <span class="text-danger">FRONTAL</span> DE LA CEDULA Y PULSA EL BOTON <span class="text-danger">TOMAR FOTO</span>.<?}else if($proceso == 3){?>COLOCA LA PARTE DE<span class="text-danger">&nbsp;ATRAS</span> DE LA CEDULA Y PULSA EL BOTON <span class="text-danger">TOMAR FOTO</span>.<?}else if($proceso == 4){?>FOTO DEL CLIENTE SOSTENIENDO EL DOCUMENTO DE IDENTIDAD EN LA MANO.<?}?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        <video muted="muted" id="video"></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                    </div>
                                    <div class="col-md-6" id="preview-zone">
                                        <?if($preview_img !== ''){
                                            echo $preview_img;
                                            }
                                        ?>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                                        <input type="hidden" id="foto_tipo" name="foto_tipo" value="<?=$foto_tipo?>">
                                        <input type="hidden" name="id_confirmacion_2" id="id_confirmacion_2" value="<?=$id_confirmacion?>">
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="reiniciar(<?=$id_usuario?>)">Reiniciar</button>
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;" onclick="newPicture(<?=$id_usuario?>)">SIGUIENTE</button>
                                    </div>
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


    <div class="page-wrapper" id="400-zone" style="display: none;">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <div class="row page-titles">
                <div class="col-md-5 align-self-center">

                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">

                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <section id="wrapper" class="error-page">
                                <div class="error-box">
                                    <div class="error-body text-center">
                                        <h1>400</h1>
                                        <h3 class="text-uppercase">Bad Request.</h3>
                                        <p class="text-muted m-t-30 m-b-30">No se puede acceder al contenido desde dispositivos moviles.</p>
                                        <a href="javascript:void(0);" class="btn btn-info btn-rounded waves-effect waves-light m-b-40" onclick="killSession(<?= $id_usuario ?>)">CERRAR SESIÓN</a>
                                    </div>

                                </div>
                            </section>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        console.log(navigator);

        const tieneSoporteUserMedia = () => //SOLO CHROME
            //!!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia)
            !!(navigator.getUserMedia || (navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia)
        //!!(navigator.getUserMedia)
        const _getUserMedia = (...arguments) =>
            (navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia).apply(navigator, arguments);

        // Declaramos elementos del DOM
        const $video = document.querySelector("#video"),
            $canvas = document.querySelector("#canvas"),
            $estado = document.querySelector("#estado"),
            $boton = document.querySelector("#boton"),
            $listaDeDispositivos = document.querySelector("#listaDeDispositivos");

        const limpiarSelect = () => {
            for (let x = $listaDeDispositivos.options.length - 1; x >= 0; x--)
                $listaDeDispositivos.remove(x);
        };
        const obtenerDispositivos = () => navigator
            .mediaDevices
            .enumerateDevices();

        // La función que es llamada después de que ya se dieron los permisos
        // Lo que hace es llenar el select con los dispositivos obtenidos
        const llenarSelectConDispositivosDisponibles = () => {

            limpiarSelect();
            obtenerDispositivos()
                .then(dispositivos => {
                    const dispositivosDeVideo = [];
                    dispositivos.forEach(dispositivo => {
                        const tipo = dispositivo.kind;
                        if (tipo === "videoinput") {
                            dispositivosDeVideo.push(dispositivo);
                        }
                    });

                    // Vemos si encontramos algún dispositivo, y en caso de que si, entonces llamamos a la función
                    if (dispositivosDeVideo.length > 0) {
                        // Llenar el select
                        dispositivosDeVideo.forEach(dispositivo => {
                            const option = document.createElement('option');
                            option.value = dispositivo.deviceId;
                            option.text = dispositivo.label;
                            $listaDeDispositivos.appendChild(option);
                        });
                    }
                });
        }


        var x1 = 0,
            y1 = 0,
            x2 = 0,
            y2 = 0,
            anchura = 0,
            altura = 0,
            ext = '',
            id_cliente = '',
            from = '',
            action = '',
            resultado = '',
            zone1 = $("#cliente-zone"),
            zone2 = $("#crop-zone"),
            zone3 = $("#zone-recortar"),
            zone4 = $("#solicitud-zone"),
            zone5 = $("#tableClientes-zone"),
            zone6 = $("#imagenes-zone"),
            zone7 = $("#fotos-zone"),
            zone8 = $("#zone-webcam"),
            zone9 = $("#prospectos-zone"),
            zone10 = $("#tratamiento-zone"),
            zone11 = $("#pc-zone"),
            zone12 = $("#400-zone");


        $(document).ready(function() {

            const changeSize = mql => {

                if (mql.matches) {

                    // laptop

                    zone11.show();
                    zone12.hide();

                } else {

                    if (isMobile.iOS() || isMobile.Android()) {

                        zone11.hide();
                        zone12.show();

                    }

                }

            }

            mobileStyles.addListener(changeSize);
            changeSize(mobileStyles);

        });

        (function() {
            // Comenzamos viendo si tiene soporte, si no, nos detenemos
            if (!tieneSoporteUserMedia()) {
                alert("Lo siento. Tu navegador no soporta esta característica");
                $estado.innerHTML = "Parece que tu navegador no soporta esta característica. Intenta actualizarlo, revisa si la url inicia por HTTPS://.";
                return;
            }
            //Aquí guardaremos el stream globalmente
            let stream;


            // Comenzamos pidiendo los dispositivos
            obtenerDispositivos()
                .then(dispositivos => {
                    // Vamos a filtrarlos y guardar aquí los de vídeo
                    const dispositivosDeVideo = [];

                    // Recorrer y filtrar
                    dispositivos.forEach(function(dispositivo) {
                        const tipo = dispositivo.kind;
                        if (tipo === "videoinput") {
                            dispositivosDeVideo.push(dispositivo);
                        }
                    });

                    // Vemos si encontramos algún dispositivo, y en caso de que si, entonces llamamos a la función
                    // y le pasamos el id de dispositivo
                    if (dispositivosDeVideo.length > 0) {
                        // Mostrar stream con el ID del primer dispositivo, luego el usuario puede cambiar
                        mostrarStream(dispositivosDeVideo[0].deviceId);
                    }
                });

            //console.log(_getUserMedia);

            const mostrarStream = idDeDispositivo => {
                _getUserMedia({
                        video: {
                            // Justo aquí indicamos cuál dispositivo usar
                            deviceId: idDeDispositivo,
                        }
                    },
                    (streamObtenido) => {
                        // Aquí ya tenemos permisos, ahora sí llenamos el select,
                        // pues si no, no nos daría el nombre de los dispositivos
                        llenarSelectConDispositivosDisponibles();

                        // Escuchar cuando seleccionen otra opción y entonces llamar a esta función
                        $listaDeDispositivos.onchange = () => {
                            // Detener el stream
                            if (stream) {
                                stream.getTracks().forEach(function(track) {
                                    track.stop();
                                });
                            }
                            // Mostrar el nuevo stream con el dispositivo seleccionado
                            mostrarStream($listaDeDispositivos.value);
                        }

                        // Simple asignación
                        stream = streamObtenido;

                        // Mandamos el stream de la cámara al elemento de vídeo
                        $video.srcObject = stream;
                        $video.play();

                        //Escuchar el click del botón para tomar la foto
                        //Escuchar el click del botón para tomar la foto
                        $boton.addEventListener("click", function() {

                            //Pausar reproducción
                            $video.pause();

                            //Obtener contexto del canvas y dibujar sobre él
                            let contexto = $canvas.getContext("2d");
                            $canvas.width = $video.videoWidth;
                            $canvas.height = $video.videoHeight;
                            contexto.drawImage($video, 0, 0, $canvas.width, $canvas.height);

                            let foto = $canvas.toDataURL(); //Esta es la foto, en base 64
                            //let idProspecto = $("#id_prospecto_img").val();
                            let fotoType = $("#foto_tipo").val();

                            let idConfirmacion = $("#id_confirmacion_2").val();

                            //var arrayImagenesProspecto = $("#imagenes_prospectos").val();

                            var parameters = {

                                "foto": foto,
                                "type": fotoType,
                                "id_confirmacion": idConfirmacion,
                                "action": "subir_img_prospecto"

                            };

                            console.log(parameters);
                            $.ajax('ajax/prospectosAjax.php', {
                                method: 'POST',
                                data: parameters,
                                success: function(data) {
                                    console.log(data);
                                    const respuesta = JSON.parse(data);
                                    console.log(respuesta);

                                    if (respuesta.response == "success") {

                                        Swal.fire({

                                            position: 'top-end',
                                            type: 'success',
                                            title: 'GENIAL!',
                                            text: 'Imagen capturada correctamente.',
                                            showConfirmButton: false,
                                            allowOutsideClick: false,
                                            timer: 4000

                                        });

                                        $("#preview-zone").empty();
                                        $("#preview-zone").html(`<br><br><img src="${respuesta.full_route}" id="img_preview" width="250" height="250" style="float: right"></img>`);

                                    } else {

                                        $.toast({

                                            heading: 'Error!',
                                            text: 'Error en el proceso.',
                                            position: 'top-center',
                                            loaderBg: '#e46a76',
                                            icon: 'error',
                                            hideAfter: 4500,
                                            stack: 6

                                        });

                                    }

                                },
                                error: function(data) {

                                    console.log(data);


                                }
                            });

                            //Reanudar reproducción
                            $video.play();
                        });

                    }, (error) => {
                        console.log("Permiso denegado o error: ", error);
                        $estado.innerHTML = "No se puede acceder a la cámara, o no diste permiso, verifica que la url del sistema comience por https://";
                    });
            }
        })();

        /*
        $(document).ready(function() {

            $video.stop();
        });
        */

        function newPicture(idUsuario) {

            if ($('#img_preview').length) {

                let fotoType = parseInt($("#foto_tipo").val());
                let idConfirmacion = $("#id_confirmacion_2").val();

                fotoType = fotoType + 1;

                var parameters = {
                    "id_confirmacion": idConfirmacion,
                    "id_usuario": idUsuario,
                    "foto_tipo": fotoType,
                    "action": "next_process" 
                };

                $.ajax({

                    data: parameters,
                    url: 'ajax/ganeAjax.php',
                    type: 'post',
                    success: function(response) {
                        console.log(response);
                    }
                });


                if (fotoType <= 2) {

                    $("#preview-zone").empty();
                    $("#foto_tipo").val(fotoType);
                    $("#estado").empty();
                    if (fotoType == 1) {
                        $("#estado").html(`COLOCA LA PARTE DE<span class="text-danger">&nbsp;ATRAS</span> DE LA CEDULA Y PULSA EL BOTON <span class="text-danger">TOMAR FOTO</span>.`);
                    } else if (fotoType == 2) {
                        $("#estado").html(`FOTO DEL CLIENTE SOSTENIENDO EL DOCUMENTO DE IDENTIDAD EN LA MANO.`);
                    }

                } else {

                    var parameters = {
                        "id_confirmacion": idConfirmacion,
                        "action": "select_contacto"
                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/ganeAjax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                $("#cedula_prospecto").val('');
                                $("#nombre_prospecto").val('');
                                $("#apellidos_prospecto").val('');
                                $("#contacto_prospecto").val(respuesta.celular_prospecto);
                                $("#contacto2_whatsaap").val('');
                                $("#ciudad_prospecto").val(1);
                                $("#ciudad_prospecto").trigger("change");
                                $("#marca_prospecto").val('placeholder');
                                $("#marca_prospecto").trigger('change');

                                zone1.show(); //cliente
                                zone2.hide(); //crop
                                zone3.empty(); //cut
                                zone6.hide(); //imagenes
                                zone4.hide(); //solicitud
                                zone7.hide(); //fotos

                                $("#id_confirmacion3").val(respuesta.id_confirmacion);

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'imagenes cargadas correctamente',
                                    showConfirmButton: false,
                                    timer: 3000

                                });

                                $video.pause();


                            }

                        }

                    });

                }

            } else {


                Swal.fire({

                    position: 'top-end',
                    type: 'error',
                    title: 'Oops...',
                    text: 'PRIMERO DEBES TOMAR LA FOTO',
                    showConfirmButton: false,
                    timer: 3000

                });

            }

        }



        function cancelar() {

            zone1.show();
            zone2.hide();
            zone3.empty();
            zone4.hide();
            zone6.hide(); //imagenes

        }


        function cancelar2() {

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
                zone6.hide(); //imagenes
                zone4.show(); //solicitud

            });

        }


        function cancelar3() {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Al cancelar el registro los datos y imagenes se perderan!",
                type: 'danger',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {

                idCliente = $("#id_cliente_imagenes").val();

                var parameters = {
                    "id_cliente": idCliente,
                    "from": "cancelar",
                    "action": "eliminar_cliente"
                };


                $.ajax({

                    data: parameters,
                    url: 'ajax/clientesAjax.php',
                    type: 'post',
                    success: function(response) {

                        console.log(response);

                        respuesta = JSON.parse(response);

                        console.log(respuesta);

                        tablaClientes.row(".row-" + respuesta.id_cliente).remove().draw(false);

                        zone1.hide(); //cliente
                        zone2.hide(); //crop
                        zone3.empty(); //cut
                        zone6.hide(); //imagenes
                        zone4.show(); //solicitud

                    }


                });


            });

        };


        $("#registrarNuevoClienteForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            const action = document.querySelector("#action_prospecto").value;

            //const action = "insertar_cliente";

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarNuevoClienteForm"));

            if (action == "insertar_prospecto") {

                insertProspectoDB(formData);

            } else {

                updateClientDB(formData);

            }


        });


        function insertProspectoDB(dates) {
            //console.log(...dates);
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectosAjax.php', true);
            // pass Info
            xhr.onload = function() {

                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {


                        zone1.hide(); //cliente
                        zone2.hide(); //crop
                        zone3.empty(); //cut
                        zone4.hide(); //solicitud
                        zone6.hide(); //imagenes
                        zone7.hide(); //imagenes
                        zone9.hide(); //prospectos
                        zone10.show(); //confirmacion

                        $("#id_prospecto_img").val(respuesta.id_prospecto_next);
                        $("#estado").empty();
                        $("#estado").html(`COLOCA LA PARTE <span class="text-danger">&nbsp;FRONTAL</span> DE LA CEDULA Y PULSA EL BOTON <span class="text-danger">TOMAR FOTO</span>.`);
                        $("#foto_tipo").val(0);

                        $("#preview-zone").empty();

                        $video.play();

                        $("#contacto_prospecto_1").val('');
                        $("#codigo_confirmacion").val('');

                        $("#id_confirmacion").val('');
                        $("#id_confirmacion2").val('');
                        $("#id_confirmacion3").val('');

                        var totalProspectos = $("#contador_prospectos_2");

                        totalProspectos.empty().html(`(${respuesta.total_prospectos})`);

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Prospecto Registrado Correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        $("#cedula_prospecto").val('');
                        $("#nombre_prospecto").val('');
                        $("#apellidos_prospecto").val('');
                        $("#contacto_prospecto").val('');
                        $("#contacto2_whatsaap").val('');
                        $("#ciudad_prospecto").val(1);
                        $("#ciudad_prospecto").trigger("change");
                        $("#marca_prospecto").val('placeholder');
                        $("#marca_prospecto").trigger('change');

                    } else if (respuesta.response == "faltan") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Oops...',
                            text: 'Falta Información del prospecto',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else if (respuesta.response == "falta_cc") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Oops...',
                            text: 'Ingresar cedula',
                            showConfirmButton: false,
                            timer: 3000

                        });


                    } else if (respuesta.response == "registrada") {


                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Oops...',
                            text: 'Esta Cedula ya esta registrada',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else if (respuesta.response == "celular_contacto") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Oops...',
                            text: 'El numero de contato es incorrecto',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else if (respuesta.response == "whatsaap") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Oops...',
                            text: 'El numero de whatsaap es incorrecto',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else {

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

        function reiniciar(idUsuario) {

            Swal.fire({
                title: '¿Estas seguro?',
                text: "¿Se perdera toda la información sin guardar?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                console.log(result.value);

                if (result.value == true) {

                    var parameters = { 
                        "id_usuario": idUsuario,
                        "action": "reset_proceso"
                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/ganeAjax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if(respuesta.response == "success"){

                                $("#estado").empty();
                                $("#estado").html(`COLOCA LA PARTE <span class="text-danger">FRONTAL</span> DE LA CEDULA Y PULSA EL BOTON <span class="text-danger">TOMAR FOTO</span>.`);
                                $("#foto_tipo").val(0);

                                $("#preview-zone").empty()

                                zone1.hide(); //cliente
                                zone2.hide(); //crop
                                zone3.empty(); //cut
                                zone4.hide(); //solicitud
                                zone6.hide(); //imagenes
                                zone7.hide(); //imagenes
                                zone9.hide(); //prospectos
                                zone10.show(); //confirmacion

                                $("#contacto_prospecto_1").val('');
                                $("#codigo_confirmacion").val('');

                                $("#id_confirmacion").val('');
                                $("#id_confirmacion_2").val('');
                                $("#id_confirmacion3").val('');

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Reinicio realizado Correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 3000

                                });

                            }else{

                                Swal.fire({

                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Eror en el proceso!',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 2000
                                });


                            }
                        }
                    });

                    


                } else {

                    return 0;

                }
            });

        }

        function enviarCodigoSms(idUsuario) {

            let celular = $("#contacto_prospecto_1").val();

            var parameters = {
                "celular_prospecto": celular,
                "id_usuario": idUsuario,
                "action": "enviar_sms"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/ganeAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            type: 'success',
                            title: 'Genial!',
                            text: 'Codigo enviado correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        $("#id_confirmacion").val(respuesta.id_confirmacion_prospecto);

                    } else if (respuesta.response == "celular") {

                        Swal.fire({

                            type: 'error',
                            title: 'Oops...',
                            text: 'Numero de celular incorrecto',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else {

                        Swal.fire({

                            type: 'error',
                            title: 'Oops...',
                            text: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }
                }
            });

        }

        $("#confirmarTratamientoProspectoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("confirmarTratamientoProspectoForm"));

            confirmacionTratamientoDB(formData);

        });


        function confirmacionTratamientoDB(dates) {

            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/ganeAjax.php', true);
            // pass Info
            xhr.onload = function() {

                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Genial!',
                            text: 'Codigo confirmado correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        //solo se preocupa por mostrar la zona 7

                        zone1.hide(); //cliente
                        zone2.hide(); //crop
                        zone3.empty(); //cut
                        zone4.hide(); //solicitud
                        zone6.hide(); //imagenes
                        zone7.show(); //imagenes
                        zone9.hide(); //prospectos
                        zone10.hide(); //confirmacion

                        $("#estado").empty();
                        $("#estado").html(`COLOCA LA PARTE <span class="text-danger">&nbsp;FRONTAL</span> DE LA CEDULA Y PULSA EL BOTON <span class="text-danger">TOMAR FOTO</span>.`);

                        $("#id_confirmacion_2").val(respuesta.id_confirmacion);

                    } else if (respuesta.response == "codigo_incorrecto") {

                        Swal.fire({

                            type: 'error',
                            title: 'Oops...',
                            text: 'Codigo incorrecto!',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        $("#codigo_confirmacion").val('');

                    } else if (respuesta.response == "no_codigo") {

                        Swal.fire({

                            type: 'error',
                            title: 'Oops...',
                            text: 'Aun no se envia el codigo al cliente o ya fue utilizado!',
                            showConfirmButton: false,
                            timer: 4000

                        });

                    } else if (respuesta.response == "falta_codigo") {

                        Swal.fire({

                            type: 'error',
                            title: 'Oops...',
                            text: 'Escribe el codigo enviado al cliente!',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else {

                        Swal.fire({

                            type: 'error',
                            title: 'Oops...',
                            text: 'Eror en el proceso!',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }

                }

            }

            // send dates
            xhr.send(dates)

        }
    </script>



    <!-- ============================================================== -->
<?
} else {

    include '401error.php';
}

?>