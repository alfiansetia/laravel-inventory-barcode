@extends('layouts.template', ['title' => 'Outbound', 'breadcumbs' => ['Outbound', $data->number]])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kai/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kai/lib/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css') }}">
@endpush

@section('contents')
    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-4">
                <h5 class="card-title mb-0">
                    <a href="{{ route('outbounds.index') }}" class="btn btn-sm btn-info"><i
                            class="fas fa-arrow-left"></i>Back</a> Information Outbound <span
                        class="text-danger">{{ $data->number }}</span>
                </h5>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-text">Number</div>
                        <div class="fs-5">{{ $data->number }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-text">Description</div>
                        <div class="fs-5">{{ $data->desc }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-text">Date</div>
                        <div class="fs-5">{{ $data->date }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-2">
                    <label for="barcode" class="form-label">Barcode Barang</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="barcode" placeholder="Scan atau ketik ID barang"
                            value="BAH-MTL-034+76862-1+1" autofocus>
                        <button type="button" class="btn btn-primary" id="btn_search">
                            <i class="ti ti-search"></i>
                            <span>Cari</span>
                        </button>
                        {{-- <button type="button" class="btn btn-secondary" id="btn_pilih">
                            <i class="ti ti-list"></i>
                            <span>Pilih</span>
                        </button> --}}
                    </div>
                    <div id="emailHelp" class="form-text">Format Barcode : ITEM_CODE+PO_NO</div>
                    {{-- <div id="emailHelp" class="form-text">Format Barcode : SEBANGO+PO_NO+QTY_KBN.</div> --}}
                </div>
            </div>

        </div>
    </div>

    <div class="alert alert-danger" role="alert" style="display: none" id="div_alert">
        <h4 id="alert_content"></h4>
    </div>

    <div class="alert alert-success" role="alert" style="display: none" id="div_alert_success">
        <h4 id="alert_content_success"></h4>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Detail Product</h5>
            <div class="border rounded">
                <table id="table" class="table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center"># </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('outbound.modal_item')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    <script src="{{ asset('kai/lib/select2/dist/js/select2.full.min.js') }}"></script>


    <script>
        const URL_INDEX = "{{ route('outbound-items.index') }}"
    </script>
    <script>
        $(document).ready(function() {
            $('#product_id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_form')
            });

        })
        var table = $("#table").DataTable({
            processing: true,
            serverSide: false,
            rowId: 'id',
            ajax: {
                url: URL_INDEX + '?outbound_id={{ $data->id }}',
                error: function(xhr, error, code) {
                    $("#table_processing").hide()
                    $(".dt-empty").text('Error, Please Refresh!')
                }
            },
            dom: "<'dt--top-section'<'row mb-2'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-0'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            oLanguage: {
                sSearch: '',
                sSearchPlaceholder: "Search...",
                sLengthMenu: "Results :  _MENU_",
            },
            lengthMenu: [
                [10, 50, 100, 500, 1000],
                ['10 rows', '50 rows', '100 rows', '500 rows', '1000 rows']
            ],
            pageLength: 10,
            lengthChange: true,
            columnDefs: [],
            order: [],
            columns: [{
                data: 'id',
                className: "text-center",
                render: function(data, type, row, meta) {
                    return parseInt(meta.row) + 1;
                }
            }, {
                data: 'product.code',
            }, {
                data: 'product.name',
            }, {
                data: 'qty',
                className: "text-center",

            }, {
                data: 'id',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type == 'display') {
                        return `
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-warning btn-sm btn-edit"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                        </div>
                        `;
                    } else {
                        return data
                    }
                }
            }],
            buttons: [{
                text: '<i class="fa fa-plus me-1"></i>Add',
                className: 'btn btn-sm btn-primary bs-tooltip',
                attr: {
                    'data-toggle': 'tooltip',
                    'title': 'Add Data'
                },
                action: function(e, dt, node, config) {
                    modal_add()
                }
            }, {
                extend: "colvis",
                attr: {
                    'data-toggle': 'tooltip',
                    'title': 'Column Visible'
                },
                className: 'btn btn-sm btn-primary'
            }, {
                extend: "pageLength",
                attr: {
                    'data-toggle': 'tooltip',
                    'title': 'Page Length'
                },
                className: 'btn btn-sm btn-info'
            }, {
                text: '<i class="fas fa-retweet me-1"></i>Refresh',
                className: 'btn btn-sm btn-primary bs-tooltip',
                attr: {
                    'data-toggle': 'tooltip',
                    'title': 'Refresh'
                },
                action: function(e, dt, node, config) {
                    table.ajax.reload()
                }
            }, ],
            initComplete: function() {
                $('#table').DataTable().buttons().container().appendTo(
                    '#tableData_wrapper .col-md-6:eq(0)');
            },
            drawCallback: function(settings) {
                // 
            },
            headerCallback: function(e, a, t, n, s) {
                // 
            },
        });

        $.fn.dataTable.ext.errMode = 'none';

        $('#table tbody').on('click', 'tr .btn-delete', function() {
            row = $(this).parents('tr')[0];
            id = table.row(row).data().id
            send_delete(URL_INDEX + "/" + id)
        });

        $('#table tbody').on('click', 'tr .btn-edit', function() {
            clear_validate('form')
            row = $(this).parents('tr')[0];
            id = table.row(row).data().id
            $.get(URL_INDEX + '/' + id).done(function(result) {
                $('#product_id').val(result.data.product_id).change()
                $('#qty').val(result.data.qty)

                $('#form').attr('action', URL_INDEX + '/' + id)
                $('#modal_form_title').html('Edit Data')
                $('#modal_form_submit').val('PUT')
                $('#modal_form_password_help').show()
                $('#modal_form').modal('show')
            }).fail(function(xhr) {
                show_toast('error', xhr.responseJSON.message || 'server Error!')
            })
        });

        $('#modal_form').on('shown.bs.modal', function() {
            $('#qty').focus();
            clear_validate('form')
        })

        $('#form').submit(function(e) {
            e.preventDefault()
            send_ajax('form', $('#modal_form_submit').val())
        })

        function modal_add() {
            $('#form').attr('action', URL_INDEX)
            $('#modal_form_submit').val('POST')
            $('#modal_form_title').html('Tambah Data')
            $('#modal_form').modal('show')

            $('#product_id').val('').change()
            $('#qty').val(1)
        }

        function search_data() {
            $('#div_alert').hide()
            $('#div_detail').hide()
            $('#div_alert_success').hide()
            $('#alert_content_success').html("")
            $('#save_barcode').show()
            let barcode = $('#barcode').val()
            barcode = barcode.trim()
            if (barcode == null || barcode == '') {
                return
            }
            $.get("{{ route('outbounds.scan', $data->id) }}?barcode=" + encodeURIComponent(barcode))
                .done(function(result) {
                    table.ajax.reload()
                    $('#barcode').val('')
                }).fail(function(xhr) {
                    $('#barcode').val('')
                    // show_toast('error', xhr.responseJSON.message || "Server Error!")
                    $('#div_alert').show()
                    $('#alert_content').html(xhr.responseJSON.message || "Server Error!")
                })

        }


        $('#btn_search').click(function() {
            search_data()
        })

        $('#barcode').change(function() {
            search_data()
        })

        $('#btn_close').click(function() {
            $('#div_detail').hide()
        })


        let barcodeBuffer = '';
        let barcodeTimeout = null;

        $(document).on('keypress', function(e) {
            // Only process if we're not in an input field and modal is not open
            if (!$(e.target).is('input, textarea, select') && !$('.modal').hasClass('show')) {
                const char = String.fromCharCode(e.which);

                // Add character to buffer
                barcodeBuffer += char;

                // Clear existing timeout
                if (barcodeTimeout) {
                    clearTimeout(barcodeTimeout);
                }

                // Set timeout to process buffer (hardware scanners are typically very fast)
                barcodeTimeout = setTimeout(function() {
                    if (barcodeBuffer.length >= 4) { // Minimum barcode length
                        console.log('Hardware barcode detected:', barcodeBuffer);

                        // Set the barcode and search
                        $('#barcode').val(barcodeBuffer);
                        search_data()

                    }

                    // Clear buffer
                    barcodeBuffer = '';
                }, 100); // 100ms timeout for hardware scanners
            }
        });

        // Clear buffer on Enter key (common for hardware scanners)
        $(document).on('keydown', function(e) {
            if (e.which === 13 && barcodeBuffer.length > 0) { // Enter key
                e.preventDefault();

                if (barcodeTimeout) {
                    clearTimeout(barcodeTimeout);
                }

                if (barcodeBuffer.length >= 4) {
                    console.log('Hardware barcode detected (Enter):', barcodeBuffer);

                    // Set the barcode and search
                    $('#barcode').val(barcodeBuffer);
                    search_data()
                }

                // Clear buffer
                barcodeBuffer = '';
            }
        });
    </script>
@endpush
