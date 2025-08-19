@extends('layouts.template', ['title' => 'Purchase', 'breadcumbs' => ['Purchase', $data->po_no]])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kai/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kai/lib/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css') }}">
@endpush

@section('contents')
    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-4">
                <h5 class="card-title mb-0">Information Purchase <span class="text-danger">{{ $data->po_no }}</span></h5>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-text">Vendor</div>
                        <div class="fs-5">[<b>{{ $data->vendor->vendor_id }}</b>] {{ $data->vendor->name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-text">PO NO</div>
                        <div class="fs-5">{{ $data->po_no }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-text">RIT</div>
                        <div class="fs-5">{{ $data->rit }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-text">Delivery Date</div>
                        <div class="fs-5">{{ $data->delv_date }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-text">DN NO</div>
                        <div class="fs-5">{{ $data->dn_no }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-text">Status</div>
                        @if ($data->isClose())
                            <div class="fs-5">
                                <span class="badge badge-danger">{{ $data->status }}</span>
                            </div>
                        @else
                            <div class="fs-5">
                                <span class="badge badge-warning">{{ $data->status }}</span>
                                {{-- <button class="btn btn-sm btn-primary">Mark As Close</button> --}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <h5 class="card-title mb-3">Detail Product</h5>
            <div class="border rounded">
                <table id="table" class="table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th class="text-center">Lot</th>
                            <th class="text-end">Qty KBN</th>
                            <th class="text-center">Barcode</th>
                            <th class="text-center">Outstanding </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @include('purchase.modal_item')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('kai/lib/select2/dist/js/select2.full.min.js') }}"></script>


    <script>
        const URL_INDEX = "{{ route('purchase-items.index') }}"
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
                url: URL_INDEX + '?purchase_id={{ $data->id }}',
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
                data: 'lot',
                className: "text-center",

            }, {
                data: 'qty_kbn',
                className: "text-center",

            }, {
                data: 'barcodes_count',
                className: "text-center",
                searchable: false,
            }, {
                data: 'id',
                className: "text-center",
                render: function(data, type, row, meta) {
                    let out = parseInt(row.qty_kbn) - parseInt(row.barcodes_count);
                    return out;
                }

            }, {
                data: 'id',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type == 'display') {
                        return `
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-info btn-sm btn-view"><i class="fas fa-eye"></i></button>
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

        $('#table tbody').on('click', 'tr .btn-view', function() {
            row = $(this).parents('tr')[0];
            id = table.row(row).data().id
            $('#table_item').DataTable().clear().destroy();

            table_item = $("#table_item").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('barcodes.index') }}" + "?purchase_item_id=" + id,
                dom: "<'dt--top-section'<'row mb-2'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-0'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<i class="fas fa-chevron-left"></i>',
                        sNext: '<i class="fas fa-chevron-right"></i>'
                    },
                    sSearch: '',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_",
                },
                lengthChange: false,
                searching: false,
                paging: false,
                info: false,
                columnDefs: [],
                order: [],
                columns: [{
                    data: 'barcode',
                    className: "text-start",
                }, {
                    data: 'input_date',
                    className: "text-start",
                }, ],
                buttons: [],
            });

            $('#modal_detail').modal('show')
        });

        $('#table tbody').on('click', 'tr .btn-edit', function() {
            clear_validate('form')
            row = $(this).parents('tr')[0];
            id = table.row(row).data().id
            $.get(URL_INDEX + '/' + id).done(function(result) {
                $('#product_id').val(result.data.product_id).change()
                $('#lot').val(result.data.lot)
                $('#qty_kbn').val(result.data.qty_kbn)

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
            $('#lot').focus();
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
            $('#lot').val(1)
            $('#qty_kbn').val(1)
        }
    </script>
@endpush
