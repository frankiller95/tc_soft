<?

if (profile(13,$id_usuario)==1){

?>
 <style>
     
    #audio{
        display: none;
    }

 </style>
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
                <?if($id_usuario != 39){?>
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Inicio</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="index.php">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <?}else{?>
                <br>
                <br>
                <?}?>
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <a href="javascript:void(0);" onclick="verProspectos()" id="boton-prospectos">
                                    <div class="d-flex flex-row">
                                        <!--<div class="round align-self-center round-success" style="width: 170px; height: 110px;"><i class="fas fa-users fa-7x"></i></div>-->
                                        <div class="m-l-10 align-self-center align-items-center">
                                        <? $total_prospectos = execute_scalar("SELECT COUNT(id) FROM prospectos WHERE prospectos.del = 0 AND prospectos.id_responsable_interno = 0"); ?>
                                            <p class="m-b-0 align-items-center" id="contador_prospectos" style="font-size: 130px;"><?=$total_prospectos?></p>
                                            <h1 class="text-muted m-b-0">PROSPECTOS</h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!--<audio id="audio" controls>
                            <source type="audio/wav" src="./assets/notifications/videoplayback.wav">
                        </audio>-->
                        <!-- HTML -->
                        <audio id="MyAudioElement" autoplay>
                        <source src="./assets/notifications/videoplayback.ogg" type="audio/ogg">
                        <source src="./assets/notifications/videoplayback.mp3" type="audio/mpeg">
                        Your browser does not support the audio element.
                        </audio>
                        <div id="sound"></div>
                        <input type="hidden" id="total_prospectos" value="<?=$total_prospectos?>">
                    </div>
                </div>

                <div class="row" id="zone-row-prospectos" style="display: none;">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">PROSPECTOS</h4>
                                <div class="table-responsive m-t-40" id="zone-tab-prospectos">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
                <input type="hidden" id="id_usuario" value="<?=$id_usuario?>">
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    </div>

<script>

var pause = 1;

var filename = "./assets/notifications/videoplayback.mp3";

var totalProspectos = 0;

var formulario = new FormData();

var formulario2 = new FormData();

var zone1 = document.getElementById('zone-row-prospectos');

var zone2 = document.getElementById('zone-tab-prospectos');

var boton1 = document.getElementById('boton-prospectos');

var theTable1 = '', tableTr1 = '';

formulario.append("action", "count_prospectos2");

function iniciar(){

    pause = 0;

    setInterval(function(){

        totalProspectos = document.getElementById("total_prospectos").value;
        formulario.append("total_prospectos", totalProspectos);
        conexionPHP(totalProspectos);

        //document.getElementById("contador").innerHTML='<span>'+numero+'</span>';
  
    }, 1000);
    
  }

  /*
  function playSound(filename){
    var mp3Source = '<source src="./assets/notifications/' + filename + '.mp3" type="audio/mpeg">';
    var oggSource = '<source src="./assets/notifications/' + filename + '.ogg" type="audio/ogg">';
    var embedSource = '<embed hidden="true" autostart="true" loop="false" src="./assets/notifications/' + filename +'.mp3">';
    document.getElementById("sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
  }
  */
 
    function playSound(url) {
	  const audio = new Audio(url);
      //audio.muted = true;
	  audio.play();
	}
    
/*
function playSound(url){

    var audio = document.createElement("AUDIO")
    document.body.appendChild(audio);
    audio.src = url;

    document.body.addEventListener("mousemove", function () {
        audio.play();
    });

}
*/

// JavaScript
// Wrap the native DOM audio element play function and handle any autoplay errors
/*
Audio.prototype.play = (function(play) {
return function () {
    console.log('se ejecuto');
  var audio = this,
      args = arguments,
      promise = play.apply(audio, args);
  if (promise !== undefined) {
    promise.catch(_ => {
      // Autoplay was prevented. This is optional, but add a button to start playing.
      var el = document.createElement("button");
      el.innerHTML = "Play";
      el.addEventListener("click", function(){play.apply(audio, args);});
      this.parentNode.insertBefore(el, this.nextSibling)
    });
  }
};
})(Audio.prototype.play);
*/

// Try automatically playing our audio via script. This would normally trigger and error.




  function conexionPHP(dates){

       /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/dashboardAjax.php', true);
        // pass Info
        xhr.onload = function(){
            if (this.status === 200) {
                console.log(xhr.responseText);
                var respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);
                //document.getElementById("contador_prospectos").innerHTML = respuesta;
                //document.getElementById("total_prospectos").value = respuesta;
                if(respuesta.total_prospectos > respuesta.total_prospectos_old){
                    console.log('entra');
                    totalProspectos = document.getElementById("total_prospectos").value;
                    document.getElementById("contador_prospectos").innerHTML = respuesta.total_prospectos;
                    document.getElementById("total_prospectos").value = respuesta.total_prospectos;
                    //playSound(filename);
                    document.getElementById('MyAudioElement').play();
                }

                if(respuesta.cambio == 1){
                    document.getElementById('zone-tab-prospectos').innerHTML = "";
                    verProspectos();
                    document.getElementById("contador_prospectos").innerHTML = respuesta.total_prospectos;
                    document.getElementById("total_prospectos").value = respuesta.total_prospectos;
                }

            }
        }

        // send dates
        xhr.send(formulario)
  }

    function verProspectos(){

        formulario2.append("action", "select_prospectos");

        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/dashboardAjax.php', true);
        // pass Info
        xhr.onload = function(){
            if (this.status === 200) {
                //console.log(xhr.responseText);
                var respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                zone1.style.display = "block";
                zone2.innerHTML = '';

                theTable1 = "<table class=\"table table-bordered\">";
                theTable1 += "<thead>";
                theTable1 += "<tr>";
                theTable1 += "<th>ID</th>";
                theTable1 += "<th>CC</th>";
                theTable1 += "<th>NOMBRE</th>";
                theTable1 += "<th>CREADO EN</th>";
                theTable1 += "<th>FECHA</th>";
                theTable1 += "<tr>";
                theTable1 += "</thead>";
                theTable1 += "<tbody>";

    
                
                respuesta.forEach(function(prospectos, index) { 


                        tableTr1 += "<tr class=\"row-"+prospectos.id_prospecto+"\">";
                        tableTr1 += "<td>"+prospectos.id_prospecto+"</td>";
                        tableTr1 += "<td>"+prospectos.prospecto_cedula+"</td>";
                        tableTr1 += "<td>"+prospectos.prospecto_nombre_full+"</td>";
                        tableTr1 += "<td>"+prospectos.creado_en+"</td>";
                        tableTr1 += "<td>"+prospectos.fecha_registro+"</td>";
                        tableTr1 += "</tr>";

                });
                

                theTable1 += tableTr1;
                theTable1 += "</tbody>"; 
                theTable1 += "</table>";

                zone2.innerHTML = theTable1;

                theTable1 = '';

                tableTr1 = '';

            }
        }

        // send dates
        xhr.send(formulario2)
    }

  iniciar();
  verProspectos();

</script>

<? }else{

    include_once '401error.php';
    
} ?>