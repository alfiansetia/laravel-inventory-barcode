@extends('layouts.template', ['title' => 'Product'])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
@endpush

@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="rounded mb-4">
                <table id="table" class="table-sm table-hover mb-0" style="width: 100%;cursor: pointer;">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Product Code</th>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Description</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('product.modal')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    <script>
        const URL_INDEX = "{{ route('products.index') }}"
    </script>
    <script>
        var table = $("#table").DataTable({
            processing: true,
            serverSide: true,
            rowId: 'id',
            ajax: URL_INDEX,
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
                data: 'DT_RowIndex',
                className: "text-center",
                searchable: false,
                orderable: false,
            }, {
                data: 'code',
            }, {
                data: 'name',
            }, {
                data: 'stock',
                className: 'text-center',
            }, {
                data: 'desc',
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
                         </div>`;
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
                $('#name').val(result.data.name)
                $('#code').val(result.data.code)
                $('#desc').val(result.data.desc)

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
            $('#code').focus();
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
            $('#name').val('')
            $('#code').val('')
            $('#desc').val('')
        }
    </script>
@endpush
