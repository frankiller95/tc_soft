        <input type="hidden" name="id_usuario" id="id_usuario_expired" value="<?= $id_usuario ?>">
        <!-- footer -->
        <!-- ============================================================== -->
        <? if (validateScreen($id_usuario) == 0) { ?>
            <footer class="footer">
                © <?= date("Y") ?> TC SHOP.
            </footer>
        <? } ?>
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

        <script src="https://jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script>
        <script src="assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <!-- Clock Plugin JavaScript -->
        <script src="assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js"></script>
        <!-- Color Picker Plugin JavaScript -->
        <script src="assets/node_modules/jquery-asColor/dist/jquery-asColor.js"></script>
        <script src="assets/node_modules/jquery-asGradient/dist/jquery-asGradient.js"></script>
        <script src="assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
        <!-- Date Picker Plugin JavaScript -->
        <script src="assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <!-- Date range Plugin JavaScript -->
        <script src="assets/node_modules/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="assets/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!-- select2 -->
        <script src="./assets/node_modules/select2_4.1.0/js/select2.min.js"></script>
        <!-- Sweet-Alert  -->
        <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
        <!--switchery  -->
        <script src="./assets/node_modules/switchery/dist/switchery.min.js"></script>
        <!-- Magnific pop-up JavaScript -->
        <script src="assets/node_modules/magnific-popup/dist/jquery.magnific-popup.js"></script>
        <!-- moment & countdown -->
        <script src="./assets/node_modules/moment/moment.js"></script>
        <script src="./dist/js/jquery.countdown.min.js"></script>
        <!-- tesseract -->
        <!--<script src="./assets/node_modules/tesseract/js/tesseract-ocr.js"></script>-->
        <script src="./assets/node_modules/tesseract/js/tesseract.min.js"></script>
        <!-- ImgAreaSelect -->
        <script src="./assets/node_modules/imgareaselect/js/jquery.imgareaselect.js"></script>
        <!-- font awesome 5.15.3 -->
        <script src="./assets/icons/fontawesome-5.15.3/js/all.js"></script>

        <script type="text/javascript" src="./assets/node_modules/prin-this/printThis.js"></script>

        <script src="./assets/node_modules/dropify/dist/js/dropify.min.js"></script>

        <script>
            /* variables zone */

            var pause = 0;

            var idleTime = 0;

            var navegador = '';

            const today = new Date();

            const yesterday = new Date(today);

            const usuarioId = $("#id_usuario_expired").val();

            var parametersCount = {
                "id_usuario": usuarioId,
                "action": "select_alertas_count"
            };

            yesterday.setDate(yesterday.getDate() - 1);

            today.toDateString();
            yesterday.toDateString();

            function contadorPrincipal() {

                if (pause == 0) {

                    window.setInterval(function() {

                        contadorAlertas();

                    }, 1000);

                } else {
                    return 0;
                }


            }

            function contadorAlertas() {

                $.ajax({

                    data: parametersCount,
                    url: 'ajax/bandejaAjax.php',
                    type: 'post',

                    success: function(response) {

                        //console.log(response);
                        const respuesta = JSON.parse(response);
                        //console.log(respuesta);

                        $("#counter-alerts").empty();
                        $("#counter-alerts").text(respuesta.total_recordatorios);
                    }

                });

            }

            contadorPrincipal();

            $(document).ready(function() {
                // Basic
                $('.dropify').dropify();

                // Translated
                $('.dropify-fr').dropify({
                    messages: {
                        default: 'Glissez-déposez un fichier ici ou cliquez',
                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                        remove: 'Supprimer',
                        error: 'Désolé, le fichier trop volumineux'
                    }
                });

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element) {
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element) {
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element) {
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e) {
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });




            //datatables funciones
            $(function() {

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
                    "drawCallback": function(settings) {
                        var api = this.api();
                        var rows = api.rows({
                            page: 'current'
                        }).nodes();
                        var last = null;
                        api.column(2, {
                            page: 'current'
                        }).data().each(function(group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                last = group;
                            }
                        });
                    }
                });

                // Order by the grouping
                $('#example tbody').on('click', 'tr.group', function() {
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
                    "order": [
                        [1, "asc"]
                    ],
                    responsive: true
                });
                tablaUsuarios = $('#dataTableUsuarios').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaUsuarios.column(0).visible(false);

                tablaPerfiles = $('#dataTablePerfiles').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaPerfiles.column(0).visible(false);

                tablaProductos = $('#dataTableProductos').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaProductos.column(0).visible(false);
                tablaProducto = $('#dataTableProducto').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaProducto.column(0).visible(false);
                tablaMarcas = $('#dataTableMarcas').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaMarcas.column(0).visible(false);
                tablaSolicitudes = $('#dataTableSolicitudes').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaSolicitudes.column(0).visible(false);
                tablaClientes = $('#dataTableClientes').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaClientes.column(0).visible(false);
                tablaProveedores = $('#dataTableProveedores').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaProveedores.column(0).visible(false);
                tablaModelos = $('#dataTableModelos').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: false
                });
                tablaModelos.column(0).visible(false);
                tablaDepartamentos = $('#dataTableDepartamentos').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaDepartamentos.column(0).visible(false);
                tablaCiudades = $('#dataTableCiudades').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaCiudades.column(0).visible(false);
                tablaColores = $('#dataTableColores').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaColores.column(0).visible(false);
                tablaPuntosGane = $('#dataTablePuntosGane').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [1, "asc"]
                    ],
                    responsive: true
                });
                tablaPuntosGane.column(0).visible(false);
                tablaCrediavales = $('#dataTableCrediavales').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaCrediavales.column(0).visible(false);
                tablaProspectos = $('#dataTableProspectos').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaProspectos.column(0).visible(false);
                tablaImagenesProspectos = $('#dataTableImagenesProspectos').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaImagenesProspectos.column(0).visible(false);
                tablaImagenes = $('#dataTableImagenes').DataTable({
                    dom: 'Bfrtip',
                    "order": [
                        [0, "desc"]
                    ],
                    buttons: [{
                            extend: "excel",
                            className: "buttonsToHide"
                        },
                        {
                            extend: "pdf",
                            className: "buttonsToHide"
                        },
                        {
                            extend: "print",
                            className: "buttonsToHide"
                        }
                    ],
                    "responsive": true,
                    "paging": false,
                    "bFilter": false,
                    responsive: true
                });
                tablaImagenes.buttons('.buttonsToHide').nodes().css("display", "none");
                tablaPlataformas = $('#dataTablePlataformas').DataTable({
                    dom: 'Bfrtip',
                    "order": [
                        [0, "asc"]
                    ],
                    "responsive": true,
                    "paging": false,
                    "buttons": false,
                    responsive: true
                });
                tablaClientesAdelantos = $('#dataTableClientesAdelantos').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaClientesAdelantos.column(0).visible(false);

                tablaResultadosPlat = $('#dataTableResultadosPlat').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });

                tablaReportesDomicilios = $("#dataTableReportesDomicilios").DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaReportesDomicilios.column(0).visible(false);

                tablaClientesCrediminuto = $('#dataTableClienteCrediminuto').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaClientesCrediminuto.column(0).visible(false);

                tablePuntosGane = $('#dataTablePuntosProspectos').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'Reporte_puntos_gane '
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Reporte_puntos_gane'
                        },
                        'print',
                        {
                            extend: 'csvHtml5',
                            title: 'Reporte_puntos_gane',
                            fieldSeparator: "|",
                            text: "PLAIN TEXT"
                        }
                    ],
                    "order": [
                        [0, "asc"]
                    ],
                    responsive: true
                });
                tablePuntosGane.column(0).visible(false);

                tableVentasDia = $('#dataTableVentasDia').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'Reporte_ventas_dia '
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Reporte_ventas_dia'
                        },
                        'print',
                        {
                            extend: 'csvHtml5',
                            title: 'Reporte_ventas_dia',
                            text: "CSV"
                        }
                    ],
                    "order": [
                        [0, "asc"]
                    ],
                    responsive: true
                });
                tableVentasDia.column(0).visible(false);

                tableReporteProspectos = $('#dataTableProspectosReport').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'Reporte_prospectos'
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Reporte_prospectos'
                        },
                        'print',
                        {
                            extend: 'csvHtml5',
                            title: 'Reporte_prospectos',
                            text: "CSV"
                        }
                    ],
                    "order": [
                        [0, "asc"]
                    ],
                    responsive: true
                });
                tableReporteProspectos.column(0).visible(false);

                tablaReporteAprobados = $('#dataTableAprobados').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'Reporte_prospectos'
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Reporte_prospectos'
                        },
                        'print',
                        {
                            extend: 'csvHtml5',
                            title: 'Reporte_prospectos',
                            text: "CSV"
                        }
                    ],
                    "order": [
                        [7, "desc"]
                    ],
                    responsive: true
                });
                tablaReporteAprobados.column(0).visible(false);

                tableReporteServientregas = $('#dataTableServientregasReport').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'Reporte_servientregas'
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Reporte_servientregas'
                        },
                        'print',
                        {
                            extend: 'csvHtml5',
                            title: 'Reporte_servientregas',
                            text: "CSV"
                        }
                    ],
                    "order": [
                        [0, "asc"]
                    ],
                    responsive: true
                });
                tableReporteServientregas.column(0).visible(false);

                tablaEntregasPdtes = $('#dataTableDashEntregasPdtes').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    "paging": false
                });
                tablaEntregasPdtes.column(0).visible(false);

                tablaValidacionesPdtes = $('#dataTableDashValidacionesPdtes').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    "paging": false
                });
                tablaValidacionesPdtes.column(0).visible(false);

                tablaMisValidaciones = $('#dataTableDashMisValidaciones').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    "paging": false
                });
                tablaMisValidaciones.column(0).visible(false);

                tablaRutasGane = $('#dataTableRutasGane').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    "paging": false
                });
                tablaRutasGane.column(0).visible(false);

                tablaRutasPdtes = $('#dataTableRutasGaneDash').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    "paging": false,
                    "order": [
                        [3, "asc"]
                    ]
                });
                tablaRutasPdtes.column(0).visible(false);




                tablaCotizaciones = $('#dataTableCotizaciones').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    responsive: true
                });
                tablaCotizaciones.column(0).visible(false);

                $('.buttons-copy, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');

            });



            $(document).on('keydown', '.select2', function(e) {
                if (e.originalEvent && e.which == 40) {
                    e.preventDefault();
                    $(this).siblings('select').select2('open');
                }
            });

            $(document).ready(function() {
                $('.select2Class').select2({
                    selectOnClose: true
                });
            });


            $('.date-start').bootstrapMaterialDatePicker({
                weekStart: 0,
                format: 'HH:mm',
                shortTime: true,
                date: false
            }).on('change', function(e, date) {
                $('.date-end').bootstrapMaterialDatePicker('setMinDate', date);
            });

            $('.date_end_filter').bootstrapMaterialDatePicker({
                weekStart: 0,
                format: 'MM-DD-YYYY',
                time: false,
                clearButton: true
            });

            $('.date_start_filter').bootstrapMaterialDatePicker({
                weekStart: 0,
                format: 'MM-DD-YYYY',
                time: false,
                clearButton: true,
                maxDate: new Date()
            }).on('change', function(e, date) {
                $('.date_end_filter').bootstrapMaterialDatePicker('setMinDate', date);
            });

            $('.min-date').bootstrapMaterialDatePicker({
                format: 'MM-DD-YYYY',
                time: false,
                clearButton: true,
                minDate: new Date()
            });

            $('.min-date-yesterday').bootstrapMaterialDatePicker({
                format: 'MM-DD-YYYY',
                time: false,
                clearButton: true,
                minDate: yesterday
            });

            $('.max-date').bootstrapMaterialDatePicker({
                format: 'MM-DD-YYYY',
                time: false,
                clearButton: true,
                maxDate: new Date()
            });

            $('.date-end').bootstrapMaterialDatePicker({
                weekStart: 0,
                format: 'HH:mm',
                date: false
            });

            $('.date-start').bootstrapMaterialDatePicker({
                weekStart: 0,
                format: 'HH:mm',
                shortTime: true,
                date: false
            }).on('change', function(e, date) {
                $('.date-end').bootstrapMaterialDatePicker('setMinDate', date);
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
                if (!isNumericInput(event) && !isModifierKey(event)) {
                    event.preventDefault();
                }
            };

            const formatToPhone = (event) => {
                if (isModifierKey(event)) {
                    return;
                }

                // I am lazy and don't like to type things more than once
                const target = event.target;
                const input = event.target.value.replace(/\D/g, '').substring(0, 10); // First ten digits of input only
                const zip = input.substring(0, 3);
                const middle = input.substring(3, 6);
                const last = input.substring(6, 10);

                if (input.length > 6) {
                    target.value = `(${zip})${middle}-${last}`;
                } else if (input.length > 3) {
                    target.value = `(${zip})${middle}`;
                } else if (input.length > 0) {
                    target.value = `(${zip}`;
                }
            };

            const formatToPhone2 = (event) => {
                if (isModifierKey(event)) {
                    return;
                }

                // I am lazy and don't like to type things more than once
                const target = event.target;
                const input = event.target.value.replace(/\D/g, '').substring(0, 7); // First ten digits of input only
                const zip = input.substring(0, 3);
                const middle = input.substring(3, 5);
                const last = input.substring(5, 7);

                if (input.length > 5) {
                    target.value = `(${zip})${middle}-${last}`;
                } else if (input.length > 3) {
                    target.value = `(${zip})${middle}`;
                } else if (input.length > 0) {
                    target.value = `(${zip}`;
                }
            };

            const formatToPhone3 = (event) => {
                if (isModifierKey(event)) {
                    return;
                }

                // I am lazy and don't like to type things more than once
                const target = event.target;
                const input = event.target.value.replace(/\D/g, '').substring(0, 10); // First ten digits of input only
                const zip = input.substring(0, 3);
                const middle = input.substring(3, 6);
                const last = input.substring(6, 10);

                if (input.length > 6) {
                    target.value = `${zip}-${middle}-${last}`;
                } else if (input.length > 3) {
                    target.value = `${zip}-${middle}`;
                } else if (input.length > 0) {
                    target.value = `${zip}`;
                }
            };


            if ($('#contacto_usuario').length) {
                const inputElement = document.getElementById('contacto_usuario');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone);
            }

            if ($('#contacto_cliente').length) {
                const inputElement = document.getElementById('contacto_cliente');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone);
            }

            if ($('#contacto_proveedor').length) {
                const inputElement = document.getElementById('contacto_proveedor');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone);
            }

            if ($('#contacto_prospecto').length) {
                const inputElement = document.getElementById('contacto_prospecto');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone3);
            }

            if ($('#contacto_prospecto_1').length) {
                const inputElement = document.getElementById('contacto_prospecto_1');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone3);
            }

            if ($('#contacto_prospecto_edit').length) {
                const inputElement = document.getElementById('contacto_prospecto_edit');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone3);
            }

            if ($('#contacto2_whatsaap').length) {
                const inputElement = document.getElementById('contacto2_whatsaap');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone3);
            }

            if ($('#contacto2_whatsaap_edit').length) {
                const inputElement = document.getElementById('contacto2_whatsaap_edit');
                inputElement.addEventListener('keydown', enforceFormat);
                inputElement.addEventListener('keyup', formatToPhone3);
            }

            function validaNumerics(event) {

                if (event.charCode >= 4 && event.charCode <= 57) {
                    return true;
                }
                return false;

            }

            function validLetters(event) {
                //console.log(event.charCode);

                if (event.charCode >= 97 && event.charCode <= 122) {
                    return true;
                } else {
                    if (event.charCode == 32) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }

            // Switchery
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            /*
            // Switchery
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch2'));
            $('.js-switch2').each(function () {
                new Switchery($(this)[0], $(this).data());
            });
            */

            function filterFloat(evt, input, id) {
                // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
                var key = window.Event ? evt.which : evt.keyCode;
                var chark = String.fromCharCode(key);
                var tempValue = input.value + chark;
                if (key >= 48 && key <= 57) {
                    if (filter(tempValue) === false) {
                        return false;
                    } else {
                        var fnf = document.getElementById(id);
                        fnf.addEventListener('keyup', function(evt) {
                            var n2 = this.value;
                            if (n2 != '') {
                                var n = parseInt(n2.replace(/\D/g, ''), 10);
                                fnf.value = n.toLocaleString();
                            }
                        }, false);
                    }
                } else {
                    if (key == 8 || key == 13 || key == 0) {
                        var fnf = document.getElementById(id);
                        fnf.addEventListener('keyup', function(evt) {
                            var n2 = this.value;
                            if (n2 != '') {
                                var n = parseInt(n2.replace(/\D/g, ''), 10);
                                fnf.value = n.toLocaleString();
                            }
                        }, false);
                    } else if (key == 46) {
                        if (filter(tempValue) === false) {
                            return false;
                        } else {
                            var fnf = document.getElementById(id);
                            fnf.addEventListener('keyup', function(evt) {
                                var n2 = this.value;
                                if (n2 != '') {
                                    var n = parseInt(n2.replace(/\D/g, ''), 10);
                                    fnf.value = n.toLocaleString();
                                }
                            }, false);
                        }
                    } else {
                        return false;
                    }
                }
            }

            function filter(__val__) {
                /*
                console.log(__val__);
                var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
                if(console.log(preg.test(__val__)) === true){
                    return true;
                }else{
                   return false;
                }
                */
                return true;
            }

            if ($('#departamento').length) {

                $('#departamento').change(function() {
                    let idDepartamento = $("#departamento").val();
                    console.log(idDepartamento);
                    var parameters = {
                        "id_departamento": idDepartamento,
                        "action": "select_ciudades_departamento"
                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/select2Ajax.php',
                        type: 'post',
                        success: function(response) {
                            console.log(response);
                            if (response != '') {
                                const respuesta = JSON.parse(response);
                                console.log(respuesta);

                                var newOption = '';

                                //$("#ciudad_usuario").val(null).trigger('change');
                                $('#ciudad').empty();


                                //var placeholderOption = new Option('Seleccionar Ciudad', 'placeholder', true, true);
                                //$('#ciudad_usuario').append(placeholderOption).trigger('change');
                                //$('#ciudad_usuario').val('placeholder').prop("disabled", true);
                                if (respuesta != '') {
                                    respuesta.forEach(function(ciudades, index) {
                                        console.log(ciudades);

                                        newOption = new Option(ciudades.ciudad, ciudades.id, false, false);
                                        $('#ciudad').append(newOption).trigger('change');
                                    });
                                }

                            }
                        }

                    });
                });
            }

            if ($('#departamento2').length) {

                $('#departamento2').change(function() {
                    let idDepartamento = $("#departamento2").val();
                    console.log(idDepartamento);
                    var parameters = {
                        "id_departamento": idDepartamento,
                        "action": "select_ciudades_departamento"
                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/select2Ajax.php',
                        type: 'post',
                        success: function(response) {
                            console.log(response);
                            if (response != '') {
                                const respuesta = JSON.parse(response);
                                console.log(respuesta);

                                var newOption = '';

                                //$("#ciudad_usuario").val(null).trigger('change');
                                $('#ciudad2').empty();


                                //var placeholderOption = new Option('Seleccionar Ciudad', 'placeholder', true, true);
                                //$('#ciudad_usuario').append(placeholderOption).trigger('change');
                                //$('#ciudad_usuario').val('placeholder').prop("disabled", true);
                                if (respuesta != '') {
                                    respuesta.forEach(function(ciudades, index) {
                                        console.log(ciudades);

                                        newOption = new Option(ciudades.ciudad, ciudades.id, false, false);
                                        $('#ciudad2').append(newOption).trigger('change');
                                    });
                                }

                            }
                        }

                    });
                });
            }

            if ($('#marca').length) {

                $('#marca').change(function() {
                    let idMarca = $("#marca").val();
                    let resulCifin = $("#resultado_cifin").val();
                    //console.log(idMarca);
                    var parameters = {
                        "id_marca": idMarca,
                        "resultado_cifin": resulCifin,
                        "action": "select_productos_marca"
                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/select2Ajax.php',
                        type: 'post',
                        success: function(response) {
                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            var newOption = '';

                            $('#producto').empty();

                            newOption2 = new Option("Seleccionar Dispositivo", "placeholder", true, true);
                            $('#producto').append(newOption2);
                            //$('#producto').val('placeholder').prop('disabled', true);
                            $("#select2-producto-result-1zf2-placeholder").removeClass();
                            $("#select2-producto-result-1zf2-placeholder").addClass("select2-results__option select2-results__option--disabled");

                            respuesta.forEach(function(productos, index) {
                                //console.log(producto);
                                optionName = productos.modelo_producto + ' ' + productos.capacidad;

                                newOption = new Option(optionName, productos.id, false, false);
                                $('#producto').append(newOption);

                            });

                            $("#producto").val('placeholder');
                            $("#producto").trigger('change');

                        }

                    });
                });
            }

            function number_format(number, decimals, dec_point, thousands_sep) {
                // Strip all characters but numerical ones.
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function(n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }


            const validar = function(campo) {

                let valor = campo.value;

                // Verifica si el valor del campo (input) contiene numeros.
                if (/\d/.test(valor)) {

                    /* 
                     * Remueve los numeros que contiene el valor y lo establece
                     * en el valor del campo (input).
                     */
                    campo.value = valor.replace(/\d/g, '');

                }

            };


            function addOtro(from) {

                var zoneCiudad = $("#zone-ciudad");

                var zoneDepartamento = $("#zone-departamento");

                if (from == "ciudad") {

                    validate = $("#departamento").val();

                    console.log(validate);

                    if (validate == null) {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Selecciona un departamento',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        return;

                    } else {

                        zoneDepartamento.hide();
                        zoneCiudad.show();

                    }

                    $("#otro_title").html('Agregar Ciudad');

                    $("#id_depart").val(validate);

                } else if (from == "departamento") {

                    zoneDepartamento.show();
                    zoneCiudad.hide();

                    $("#otro_title").html('Agregar Departamento');

                }


                $("#ciudad_otra").val('');

                $("#depar_otro").val('');

                $("#from_otro").val(from);

                $("#otro_modal").modal("show");

            }

            $("#otroForm").on("submit", function(e) {

                // evitamos que se envie por defecto
                e.preventDefault();

                action = "otro";

                // create FormData with dates of formulary       
                var formData = new FormData(document.getElementById("otroForm"));
                formData.append('action', action);

                otroDB(formData);


            });

            function otroDB(dates) {

                /** Call to Ajax **/
                // create the object
                const xhr = new XMLHttpRequest();
                // open conection
                xhr.open('POST', 'ajax/select2Ajax.php', true);
                // pass Info
                xhr.onload = function() {

                    //the conection is success
                    if (this.status === 200) {

                        console.log(xhr.responseText);
                        const respuesta = JSON.parse(xhr.responseText);
                        console.log(respuesta);

                        if (respuesta.response == "success") {

                            if (respuesta.from == "ciudad") {

                                var data = {
                                    id: respuesta.id_ciudad,
                                    text: respuesta.ciudad
                                };

                                var newOption = new Option(data.text, data.id, false, false);

                                $('#ciudad').append(newOption).trigger('change');
                                $('#ciudad').val(respuesta.id_ciudad);
                                $('#ciudad').trigger('change');

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Ciudad registrada correctamente',
                                    showConfirmButton: false,
                                    timer: 2500

                                });

                                $("#otro_modal").modal("hide");

                            } else if (respuesta.from == "departamento") {

                                var data = {
                                    id: respuesta.id_departamento,
                                    text: respuesta.departamento
                                };

                                var newOption = new Option(data.text, data.id, false, false);

                                $('#departamento').append(newOption).trigger('change');
                                $('#departamento').val(respuesta.id_departamento);
                                $('#departamento').trigger('change');

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Departamento registrado correctamente',
                                    showConfirmButton: false,
                                    timer: 2500

                                });

                                $("#otro_modal").modal("hide");
                            }

                        } else if (respuesta.response == "ciudad_repeat") {

                            Swal.fire({

                                position: 'top-end',
                                type: 'error',
                                title: 'Ciudad ya esta registrada',
                                showConfirmButton: false,
                                timer: 3500

                            });


                        } else if (respuesta.response == "depart_repeat") {

                            Swal.fire({

                                position: 'top-end',
                                type: 'error',
                                title: 'Departamento ya esta registrado',
                                showConfirmButton: false,
                                timer: 3500

                            });

                        } else {

                            Swal.fire({

                                position: 'top-end',
                                type: 'error',
                                title: 'error en el proceso',
                                showConfirmButton: false,
                                timer: 3500

                            });

                        }

                    }

                }

                xhr.send(dates)
            }


            function killSession(idUsuario) {

                Swal.fire({
                    title: 'Estas seguro?',
                    text: "Por favor para cerrar sesión!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Estoy seguro!'
                }).then((result) => {

                    if (result.value == true) {

                        var parameters = {
                            "id_usuario": idUsuario,
                            "action": "cerrar_session"
                        };

                        $.ajax({
                            data: parameters,
                            url: 'ajax/killsessionAjax.php',
                            type: 'post',

                            success: function(response) {

                                console.log(response);

                                if (response == 1) {

                                    setTimeout(function() {
                                        location.href = "killsession.php"
                                    }, 500);

                                } else {

                                    return 0;

                                }

                            }

                        });

                    } else {

                        return 0;

                    }
                });

            }

            function showBandejaRecords(idUsuario) {

                if ($('#menu-recordatorios').is(':visible')) {

                    return 0;

                } else {

                    var parameters = {
                        "id_usuario": idUsuario,
                        "action": "show_bandeja"
                    };


                    $.ajax({
                        data: parameters,
                        url: 'ajax/bandejaAjax.php',
                        type: 'post',

                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                if (respuesta.pendientes_llamarArray.length != 0) {

                                    $("#bandeja_recordatorios").html('');

                                    /*
                                    <div class="col-md-12">
                                                <div class="card card-stats" style="background-color: #01c0c8;">
                                                    <div class="card-header card-header-warning card-header-icon">
                                                        <div class="card-icon">
                                                            <i class="material-icons">LLAMAR</i>
                                                        </div>
                                                        <p class="card-category font-weight-bold">${respuesta.pendientes_llamarArray[i].estado_mostrar}</p>
                                                        <h3 class="card-title">${respuesta.pendientes_llamarArray[i].prospecto_nombre} ${respuesta.pendientes_llamarArray[i].prospecto_apellidos}</h3>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="stats">
                                                            <i class="material-icons"><span class="font-weight-bold">fecha de llamada:</span></i> ${respuesta.pendientes_llamarArray[i].fecha_hora_llamada}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    */

                                    var asesor = '';

                                    var color = '#00c292';

                                    for (var i = 0; i < respuesta.pendientes_llamarArray.length; i++) {

                                        if(respuesta.validate_validador == 1){
                                            asesor = `<span style="font-size: 1.2rem; color: #343a40;"><span style="font-weight: 700;">Asesor: </span>${respuesta.pendientes_llamarArray[i].nombre_usuario}</span>`;
                                        }

                                        if (respuesta.pendientes_llamarArray[i].vencida == 1) {
                                            color = '#e46a76';
                                        }

                                        if(respuesta.pendientes_llamarArray[i].prospecto_validador == 1 && respuesta.pendientes_llamarArray[i].vencida == 0){
                                            color = '#fb9678';
                                        }

                                        $("#bandeja_recordatorios").append(`
                                            <a href="javascript:void(0);" style="background-color: ${color};">
                                                <div class="btn btn-info btn-circle"><i class="fas fa-envelope"></i></div>
                                                <div class="mail-contnet">
                                                    <h2><span style="font-size: 1.4rem; color: #343a40; font-weight: 600;">Pdte. Por Llamar</span></h2>
                                                    <h5><span style="font-weight: 700;">Cuando: </span>${respuesta.pendientes_llamarArray[i].fecha_hora_llamada}</h5><span class="mail-desc" style="font-weight: 700; font-size: 1.3rem">${respuesta.pendientes_llamarArray[i].prospecto_nombre} ${respuesta.pendientes_llamarArray[i].prospecto_apellidos}</span><span style="font-size: 1.2rem; color: #343a40;"><span style="font-weight: 700;">Motivo: </span>${respuesta.pendientes_llamarArray[i].estado_mostrar}</span>
                                                    ${asesor}
                                                </div>
                                            </a>
                                        `);

                                        asesor = '';
                                        color = '#00c292';
                                    }

                                } else {

                                    //$("#bandeja_recordatorios").empty();
                                    $("#bandeja_recordatorios").html('');
                                    $("#bandeja_recordatorios").append('<div class="alert alert-info" role="alert">No tienes recordatorios pendientes</div>');

                                }


                            } else {

                                return 0;

                            }

                        }

                    });
                }

            }
        </script>
        </body>

        </html>