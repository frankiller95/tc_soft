
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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarProductoModal()">Nuevo Producto</button>
                                    <h4 class="card-title">Productos</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableProductos" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>MARCA</th>
                                                               <th>MODELO</th>
                                                               <th>COSTO</th>
                                                               <th>PRECIO VENTA</th>
                                                               <th>STOCK</th>
                                                               <th>ESTADO</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT productos.id, marcas.marca_producto, productos.modelo_producto, inventario.costo, inventario.precio_venta, inventario.stock, productos.del FROM inventario LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN marcas ON productos.id_marca = marcas.id WHERE productos.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_producto = $row1['id'];
                                                            $marca = $row1['marca_producto'];
                                                            $modelo_producto = $row1['modelo_producto'];
                                                            $costo = $row1['costo'];
                                                            $precio_venta = $row1['precio_venta'];
                                                            $stock = $row1['stock'];
                                                            $del = $row1['del'];

                                                    ?>
                                                               <tr class="row-<?=$id_producto?>">
                                                               <td><?=$id_producto?></td>
                                                               <td><?=$marca?></td>
                                                               <td><?=$modelo_producto?></td>
                                                               <td><?=number_format($costo, 2)?></td>
                                                               <td><?=number_format($precio_venta, 2)?></td>
                                                               <td><?=$stock?></td>
                                                                <?php if ($del == 0) {?>
                                                                <td><span class="label label-success">Activo</span></td>
                                                                <?php }else{ ?>
                                                                <td><span class="label label-danger">Inactivo</span></td>    
                                                                <?php } ?>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarProducto(<?= $id_producto ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarProducto(<?= $id_producto ?>)"><i class="fas fa-cart-arrow-down"></i></a>
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
   

        <!-- Add producto modal -->
        <div id="registrar-producto-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titulo_productos"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarProdutoForm" method="post">
                        <div class="modal-body">
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Marca:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="marca_producto" id="marca_producto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                <option value="placeholder" disabled>Seleccionar Marca</option>
                                                <?php
                                                $query = "select id, marca_producto from marcas where del = 0 order by marca_producto";
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
                                        <label>Modelo:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="modelo_producto" id="modelo_producto" placeholder="Modelo del producto" required autocomplete="ÑÖcompletes">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Costo:</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="costo_producto" id="costo_producto" placeholder="Costo del producto" autocomplete="ÑÖcompletes" maxlength="12">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Precio de venta:</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="precio_venta_producto" id="precio_venta_producto" placeholder="precio de venta del producto" autocomplete="ÑÖcompletes">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Stock Inicial:<span class="text-danger">&nbsp;</span></label></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="stock_producto" id="stock_producto" placeholder="Ingrese el stock" autocomplete="ÑÖcompletes" value="0" maxlength="16" required onkeypress="return validaNumerics(event)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_producto" id="id_producto" value="">
                            <input type="hidden" name="action" id="action_producto" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->


<script>


function registrarProductoModal(){
    $("#titulo_productos").html("Ingresar Producto");
    $("#marca_producto").val("placeholder");
    $("#marca_producto").trigger("change");
    $("#modelo_producto").val('');
    $("#costo_producto").val('');
    $("#precio_venta_producto").val('');
    $("#stock_producto").val(0);
    $("#id_producto").val('');
    $("#action_producto").val('insertar');
    $("#registrar-producto-modal").modal("show");
}

$("#registrarProdutoForm").on("submit", function(e){

    // evitamos que se envie por defecto
    e.preventDefault();

     // create FormData with dates of formulary       
    var formData = new FormData(document.getElementById("registrarProdutoForm"));

    const action = document.querySelector("#action_producto").value;

    if(action == "insertar"){

        insertProductosDB(formData);

    }else{

        updateProductosDB(formData);

    }

});

function insertProductosDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/productosAjax.php', true);
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
                    title: 'Producto registrado correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaProductos.row.add([
                    respuesta.id_producto, respuesta.marca_nombre, respuesta.modelo_producto, respuesta.costo, respuesta.precio_venta, respuesta.stock, `<span class="label label-success">Activo</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarProducto(${respuesta.id_producto})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarProducto(${respuesta.id_producto})"><i class="fas fa-cart-arrow-down"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_producto);

                $("#registrar-producto-modal").modal("hide");

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


function editarProducto(idProducto){

    var parameters = {
        "id_producto": idProducto,
        "action": "select_productos"
    };

    $.ajax({

        data:  parameters,
        url:   'ajax/productosAjax.php',
        type:  'post',
        success:  function (response) {
            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            $("#titulo_productos").html("Editar Producto");
            $("#marca_producto").val(respuesta[0].id_marca);
            $("#marca_producto").trigger("change");
            $("#modelo_producto").val(respuesta[0].modelo_producto);
            $("#costo_producto").val(respuesta[0].costo);
            $("#precio_venta_producto").val(respuesta[0].precio_venta);
            $("#stock_producto").val(respuesta[0].stock);
            $("#id_producto").val(respuesta[0].id_producto);
            $("#action_producto").val('editar');
            $("#registrar-producto-modal").modal("show");

        }

    });

}


function updateProductosDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/productosAjax.php', true);
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
                    title: 'Inventario Actualizado Correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaProductos.row(".row-"+respuesta.id_producto).remove().draw(false);

                tablaProductos.row.add([
                    respuesta.id_producto, respuesta.marca_nombre, respuesta.modelo_producto, respuesta.costo, respuesta.precio_venta, respuesta.stock, `<span class="label label-success">Activo</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarProducto(${respuesta.id_producto})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarProducto(${respuesta.id_producto})"><i class="fas fa-cart-arrow-down"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_producto);

                $("#registrar-producto-modal").modal("hide");

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


function eliminarProducto(idProducto){

    Swal.fire({
        title: 'Estas seguro?',
        text: "Por favor confirmar para eliminar este producto!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {
        if (result.value) {
            var parameters = {
                "id_producto": idProducto,
                "action": "eliminar"
            };

            $.ajax({
                data:  parameters,
                url:   'ajax/productosAjax.php',
                type:  'post',
                success:  function (response) {

                console.log(response);
                const respuesta = JSON.parse(response);

                console.log(respuesta);

                if (respuesta.response == "success") {

                    tablaProductos.row(".row-"+respuesta.id_producto).remove().draw(false);

                    $.toast({
                            heading: 'Genial!',
                            text: 'Producto Eliminado Correctamente.',
                            position: 'top-center',
                            loaderBg:'#00c292',
                            icon: 'success',
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

</script>