        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            © <?=date("Y")?> TC SHOP.
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- Bootstrap popper Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="./assets/node_modules/raphael/raphael-min.js"></script>
    <script src="./assets/node_modules/morrisjs/morris.min.js"></script>
    <script src="./assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- Popup message jquery -->
    <script src="./assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- Chart JS -->
    <script src="dist/js/dashboard1.js"></script>
    <script src="./assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- jQuery peity -->
    <script src="./assets/node_modules/peity/jquery.peity.min.js"></script>
    <script src="./assets/node_modules/peity/jquery.peity.init.js"></script>
    <!-- This is data table -->
    <script src="./assets/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="./assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <!-- datepicker zone JavaScript -->
    <script src="./assets/node_modules/moment/moment.js"></script>
    <script src="./assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <!-- Clock Plugin JavaScript -->
    <script src="./assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <!-- Color Picker Plugin JavaScript -->
    <script src="./assets/node_modules/jquery-asColor/dist/jquery-asColor.js"></script>
    <script src="./assets/node_modules/jquery-asGradient/dist/jquery-asGradient.js"></script>
    <script src="./assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="./assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="./assets/node_modules/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="./assets/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- select2 -->
    <script src="./assets/node_modules/select2_4.1.0/js/select2.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <!-- Magnific pop-up JavaScript -->
    <script src="assets/node_modules/magnific-popup/dist/jquery.magnific-popup.js"></script>
<script>

    //datatables funciones
    $(function () {

        $('#myTable').DataTable();
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
            [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });

        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
        // responsive table
        $('#config-table').DataTable({
            responsive: true
        });
        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'copy', 'excel', 'pdf', 'print'
            ],
            "order": [[ 1, "asc" ]],
            responsive: true
        });
        tablaUsuarios =  $('#dataTableUsuarios').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'copy', 'excel', 'pdf', 'print'
            ],
            "order": [[ 0, "desc" ]],
            responsive: true
        });
        tablaUsuarios.column(0).visible(false);
        tablaProductos =  $('#dataTableProductos').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'copy', 'excel', 'pdf', 'print'
            ],
            "order": [[ 0, "desc" ]],
            responsive: true
        });
        tablaProductos.column(0).visible(false);
        tablaMarcas =  $('#dataTableMarcas').DataTable({
            dom: 'Bfrtip',
            buttons: [
            'copy', 'excel', 'pdf', 'print'
            ],
            "order": [[ 0, "desc" ]],
            responsive: true
        });
        tablaMarcas.column(0).visible(false);
        $('.buttons-copy, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');

    });

    $(document).on('keydown', '.select2', function(e) {
      if (e.originalEvent && e.which == 40) {
        e.preventDefault();
        $(this).siblings('select').select2('open');
      }
    });

    $(document).ready(function() {
        $('.select2Class').select2({selectOnClose: true});
    });

    jQuery('.todayColor').datepicker({
        autoclose: true,
        todayHighlight: true
    });

    const isNumericInput = (event) => {
        const key = event.keyCode;
        return ((key >= 48 && key <= 57) || // Allow number line
            (key >= 96 && key <= 105) // Allow number pad
        );
    };

    const isModifierKey = (event) => {
        const key = event.keyCode;
        return (event.shiftKey === true || key === 35 || key === 36) || // Allow Shift, Home, End
            (key === 8 || key === 9 || key === 13 || key === 46) || // Allow Backspace, Tab, Enter, Delete
            (key > 36 && key < 41) || // Allow left, up, right, down
            (
                // Allow Ctrl/Command + A,C,V,X,Z
                (event.ctrlKey === true || event.metaKey === true) &&
                (key === 65 || key === 67 || key === 86 || key === 88 || key === 90)
            )
    };
    
    const enforceFormat = (event) => {
        // Input must be of a valid number format or a modifier key, and not longer than ten digits
        if(!isNumericInput(event) && !isModifierKey(event)){
            event.preventDefault();
        }
    };

    const formatToPhone = (event) => {
        if(isModifierKey(event)) {return;}

        // I am lazy and don't like to type things more than once
        const target = event.target;
        const input = event.target.value.replace(/\D/g,'').substring(0,10); // First ten digits of input only
        const zip = input.substring(0,3);
        const middle = input.substring(3,6);
        const last = input.substring(6,10);

        if(input.length > 6){target.value = `(${zip}) ${middle} - ${last}`;}
        else if(input.length > 3){target.value = `(${zip}) ${middle}`;}
        else if(input.length > 0){target.value = `(${zip}`;}
    };

    if ($('#contacto_usuario').length) {
        const inputElement = document.getElementById('contacto_usuario');
        inputElement.addEventListener('keydown',enforceFormat);
        inputElement.addEventListener('keyup',formatToPhone);
    }

     if ($('#contacto_cliente').length) {
        const inputElement = document.getElementById('contacto_cliente');
        inputElement.addEventListener('keydown',enforceFormat);
        inputElement.addEventListener('keyup',formatToPhone);
    }


    function validaNumerics(event) {

        if(event.charCode >= 48 && event.charCode <= 57){
          return true;
         }
         return false;
                 
    }

    var config = {
        type: 'image',
        callbacks: { }
    };

    var cssHeight = '800px';// Add some conditions here

    config.callbacks.open = function () {
        $(this.container).find('.mfp-content').css('height', cssHeight);
    };

    $('.image-complete').magnificPopup(config);

    if ($('#departamento').length) {

        $('#departamento').change(function() {
            let idDepartamento = $("#departamento").val();
            console.log(idDepartamento);
            var parameters = {
                "id_departamento": idDepartamento,
                "action": "select_ciudades_departamento"
            };

            $.ajax({

               data:  parameters,
               url:   'ajax/select2Ajax.php',
               type:  'post',
               success:  function (response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var newOption = '';

                    //$("#ciudad_usuario").val(null).trigger('change');
                    $('#ciudad').empty();


                    //var placeholderOption = new Option('Seleccionar Ciudad', 'placeholder', true, true);
                    //$('#ciudad_usuario').append(placeholderOption).trigger('change');
                    //$('#ciudad_usuario').val('placeholder').prop("disabled", true);

                    respuesta.forEach(function(ciudades, index) {
                        console.log(ciudades);

                        newOption = new Option(ciudades.ciudad, ciudades.id, false, false);
                        $('#ciudad').append(newOption).trigger('change');
                    });
               }

            });
        });
    }

    if ($('#marca').length) {

        $('#marca').change(function() {
            let idMarca = $("#marca").val();
            //console.log(idMarca);
            var parameters = {
                "id_marca": idMarca,
                "action": "select_productos_marca"
            };

            $.ajax({

               data:  parameters,
               url:   'ajax/select2Ajax.php',
               type:  'post',
               success:  function (response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var newOption = '';

                    $('#producto').empty();


                    
                    respuesta.forEach(function(productos, index) {
                        //console.log(producto);

                        newOption = new Option(productos.modelo_producto, productos.id, false, false);
                        $('#producto').append(newOption).trigger('change');
                        
                    });
                    
               }

            });
        });
    }

</script>
</body>

</html>