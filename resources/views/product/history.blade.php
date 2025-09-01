@extends('layouts.template', ['title' => 'Product', 'breadcumbs' => ['Product', $data->code, 'History Move']])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
@endpush

@section('contents')
    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-4">
                <h5 class="card-title mb-0">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-info"><i
                            class="fas fa-arrow-left"></i>Back</a> Information Product <span
                        class="text-danger">[{{ $data->code }}] {{ $data->name }}</span>
                </h5>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-text">Code</div>
                        <div class="fs-5">{{ $data->code }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-text">Name</div>
                        <div class="fs-5">{{ $data->name }}</div>
                    </div>
                </div>
            </div>

            <h5 class="card-title mb-3">History Move</h5>
            <div class="border rounded">
                <table id="table" class="table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Reff</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>

    <script>
        const URL_INDEX = "{{ route('products.history', $data->id) }}"
    </script>
    <script>
        $(document).ready(function() {

        })
        var table = $("#table").DataTable({
            processing: true,
            serverSide: false,
            rowId: 'id',
            ajax: {
                url: URL_INDEX,
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
                data: 'date',
                className: "text-start",
            }, {
                data: 'qty',
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (row.type == 'in' && type == 'display') {
                        return data
                    }
                    return ''
                }
            }, {
                data: 'qty',
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (row.type == 'out' && type == 'display') {
                        return data
                    }
                    return ''
                }
            }, {
                data: 'reff',
                className: "text-start",
            }, ],
            buttons: [{
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
    </script>
@endpush
