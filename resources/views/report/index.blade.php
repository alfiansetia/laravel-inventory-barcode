@extends('layouts.template', ['title' => 'Report', 'breadcumbs' => ['Report']])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('kai/lib/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('contents')
    <div class="row">
        <div class="col-md-6">
            <input type="text" class="form-control mb-2" placeholder="Range Date" id="range">
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-primary" id="btn_filter"><i class="fas fa-filter me-1"></i>Filter</button>
            <button type="button" class="btn btn-warning" id="btn_filter_close">
                <i class="fas fa-times me-1"></i>Clear</button>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="rounded mb-4">
                <table id="table" class="table-sm table-hover mb-0" style="width: 100%;cursor: pointer;">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Product Code</th>
                            <th>Name</th>
                            <th>Awal</th>
                            <th>IN</th>
                            <th>OUT</th>
                            <th>Akhir</th>
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
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}?v=2"></script>
    <script src="{{ asset('kai/lib/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        const URL_INDEX = "{{ route('reports.data') }}"
    </script>
    <script>
        $('#range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
                'Last 31 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment()],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')],
            },
            showDropdowns: true,
            startDate: moment().startOf('month'),
            endDate: moment(),
            parentEl: "#modal_export",
        });

        $("#range").data('daterangepicker').setStartDate("{{ date('d/m/Y') }}");
        $("#range").data('daterangepicker').setEndDate("{{ date('d/m/Y') }}");
        document.title = `Report Stok Product From : {{ date('d/m/Y') }} To : {{ date('d/m/Y') }}`

        var table = $("#table").DataTable({
            processing: true,
            serverSide: false,
            rowId: 'id',
            ajax: {
                url: URL_INDEX,
                data: function(d) {
                    d.from = $('#range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    d.to = $('#range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                },
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
                data: 'DT_RowIndex',
                className: "text-center",
                searchable: false,
            }, {
                data: 'code',
            }, {
                data: 'name',
            }, {
                data: 'stok_awal',
                className: 'text-center',
                searchable: false,
            }, {
                data: 'in',
                className: 'text-center',
                searchable: false,
            }, {
                data: 'out',
                className: 'text-center',
                searchable: false,
            }, {
                data: 'ending',
                className: 'text-center',
                searchable: false,
            }],
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
            }, {
                extend: "collection",
                text: '<i class="fas fa-download me-1"></i>Export',
                attr: {
                    'data-toggle': 'tooltip',
                    'title': 'Export Data'
                },
                className: 'btn btn-sm btn-info',
                buttons: [{
                    extend: 'copy',
                    exportOptions: {
                        columns: ':visible'
                    }
                }, {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':visible'
                    }
                }, {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                }, {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }, {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }],
            }],
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

        $('#btn_filter').click(function() {
            table.ajax.reload()
        })

        $('#btn_filter_close').click(function() {
            $("#range").data('daterangepicker').setStartDate("{{ date('d/m/Y') }}");
            $("#range").data('daterangepicker').setEndDate("{{ date('d/m/Y') }}");
            table.ajax.reload()
        })

        $('#range').change(function() {
            let from = $('#range').data('daterangepicker').startDate.format('DD/MM/YYYY');
            let to = $('#range').data('daterangepicker').endDate.format('DD/MM/YYYY');
            document.title = `Report Stok Product From : ${from} To : ${to}`
        })
    </script>
@endpush
