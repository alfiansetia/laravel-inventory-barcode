@extends('layouts.template', ['title' => 'Purchase', 'breadcumbs' => ['Purchase']])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kai/assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kai/lib/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kai/lib/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="rounded mb-4">
                <table id="table" class="table-sm table-hover mb-0" style="width: 100%;cursor: pointer;">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Vendor</th>
                            <th>PO No</th>
                            <th>DN No</th>
                            <th>DELV Date</th>
                            <th>RIT</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('purchase.modal')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    <script src="{{ asset('kai/lib/select2/dist/js/select2.full.min.js') }}"></script>


    <script src="{{ asset('kai/lib/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


    <script>
        const URL_INDEX = "{{ route('purchases.index') }}"
    </script>
    <script>
        $(document).ready(function() {
            $('#vendor_id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_form')
            });

            $('#delv_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            });

        })

        var table = $("#table").DataTable({
            processing: true,
            serverSide: true,
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
                data: 'DT_RowIndex',
                className: "text-center",
                searchable: false,
                orderable: false,
            }, {
                data: 'vendor.name',
                className: "text-start",
            }, {
                data: 'po_no',
                className: "text-start",
            }, {
                data: 'dn_no',
                className: "text-start",
            }, {
                data: 'delv_date',
                className: "text-start",
            }, {
                data: 'rit',
                className: "text-center",
                searchable: false,
            }, {
                data: 'status',
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type == 'display') {
                        if (data != 'open') {
                            return `<span class="badge badge-danger">${data}</span>`;
                        } else {
                            return `<span class="badge badge-warning">${data}</span>`;
                        }
                    } else {
                        return data
                    }
                }
            }, {
                data: 'items_count',
                className: "text-center",
                searchable: false,
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
                text: '<i class="far fa-file-excel me-1"></i>Import',
                className: 'btn btn-sm btn-primary bs-tooltip',
                attr: {
                    'data-toggle': 'tooltip',
                    'title': 'Import Data'
                },
                action: function(e, dt, node, config) {
                    $('#modal_import').modal('show')
                }
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
            window.location.href = `${URL_INDEX}/${id}`
        });

        $('#table tbody').on('click', 'tr .btn-edit', function() {
            clear_validate('form')
            row = $(this).parents('tr')[0];
            id = table.row(row).data().id
            $.get(URL_INDEX + '/' + id).done(function(result) {
                $('#vendor_id').val(result.data.vendor_id).change()
                $('#po_no').val(result.data.po_no)
                $('#dn_no').val(result.data.dn_no)
                // $('#delv_date').val(result.data.delv_date)
                $("#delv_date").data('daterangepicker').setStartDate(result.data.delv_date);
                $("#delv_date").data('daterangepicker').setEndDate(result.data.delv_date);
                $('#rit').val(result.data.rit)
                if (result.data.status == 'open') {
                    $('#status_open').prop('checked', true)
                } else {
                    $('#status_close').prop('checked', true)
                }

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
            $('#po_no').focus();
            clear_validate('form')
        })

        $('#modal_import').on('shown.bs.modal', function() {
            $('#form_import')[0].reset();
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

            $('#vendor_id').val('').change()
            $('#po_no').val('')
            $('#dn_no').val('')
            $('#delv_date').val('')
            $('#rit').val('')
            $('#status_open').prop('checked', true)
        }


        $('#btn_import').on('click', function() {
            var formData = new FormData($('#form_import')[0]);
            $.ajax({
                url: "{{ route('purchases.import') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // 
                },
                success: function(res) {
                    table.ajax.reload();
                    show_toast('success', 'Import berhasil!')
                },
                error: function(xhr) {
                    show_toast('error', xhr.responseJSON.message || 'Terjadi kesalahan saat import.')
                }
            });
        });
    </script>
@endpush
