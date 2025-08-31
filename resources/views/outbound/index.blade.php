@extends('layouts.template', ['title' => 'Outbound', 'breadcumbs' => ['Outbound']])

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
                            <th>Number</th>
                            <th>Date</th>
                            <th>Karyawan Name</th>
                            <th>Desc</th>
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

    @include('outbound.modal')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    <script src="{{ asset('kai/lib/select2/dist/js/select2.full.min.js') }}"></script>


    <script src="{{ asset('kai/lib/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


    <script>
        const URL_INDEX = "{{ route('outbounds.index') }}"
    </script>
    <script>
        $(document).ready(function() {

            $('#karyawan_id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_form')
            });

            $('#date').daterangepicker({
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
                data: 'number',
                className: "text-start",
            }, {
                data: 'date',
                className: "text-start",
            }, {
                data: 'karyawan.name',
                className: "text-start",
            }, {
                data: 'desc',
                className: "text-start",
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

        $('#table tbody').on('click', 'tr .btn-scan', function() {
            row = $(this).parents('tr')[0];
            id = table.row(row).data().id
            window.location.href = `${URL_INDEX}/${id}/scan`
        });

        $('#table tbody').on('click', 'tr .btn-edit', function() {
            clear_validate('form')
            row = $(this).parents('tr')[0];
            id = table.row(row).data().id
            $.get(URL_INDEX + '/' + id).done(function(result) {
                $('#karyawan_id').val(result.data.karyawan_id).change()
                $('#number').val(result.data.number)
                $('#desc').val(result.data.desc)
                $("#date").data('daterangepicker').setStartDate(result.data.date);
                $("#date").data('daterangepicker').setEndDate(result.data.date);
                $('#div_number').show()
                $('#date').prop('disabled', true)
                $('#date').prop('readonly', true)
                $('#karyawan_id').prop('disabled', true)

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
            // $('#date').focus();
            $('#karyawan_id').select2('open')
            clear_validate('form')
        })

        $('#form').submit(function(e) {
            e.preventDefault()
            send_ajax('form', $('#modal_form_submit').val())
        })

        // $('#karyawan_id').on('change', function() {
        //     let card = $(this).find(':selected').data('card');
        //     console.log("Card terpilih:", card);
        // });

        function modal_add() {
            $('#form').attr('action', URL_INDEX)
            $('#modal_form_submit').val('POST')
            $('#modal_form_title').html('Tambah Data')
            $('#modal_form').modal('show')

            $('#karyawan_id').val('').change()
            $('#karyawan_id').prop('disabled', false)
            $('#number').val('')
            $('#date').val('')
            $('#desc').val('')
            $('#div_number').hide()
            $('#date').prop('disabled', false)
            $('#date').prop('readonly', false)
        }

        function search_data(barcode) {
            if (!barcode) {
                return;
            }
            let foundValue = null;
            $('#karyawan_id option').each(function() {
                console.log($(this).data('card') || 'nonoe');
                if ($(this).data('card') == barcode) {
                    foundValue = $(this).val();
                    return false;
                }
            });

            if (foundValue) {
                $('#karyawan_id').val(foundValue).trigger('change');
                show_toast('success', 'Karyawan dengan kode ' + barcode + ' Berhasil dipilih!');
            } else {
                show_toast('error', 'Karyawan dengan id_card ' + barcode + ' tidak ditemukan!');
            }
        }

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
                        // $('#barcode').val(barcodeBuffer);
                        search_data(barcodeBuffer)

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
                    // $('#barcode').val(barcodeBuffer);
                    search_data(barcodeBuffer)
                }

                // Clear buffer
                barcodeBuffer = '';
            }
        });
    </script>
@endpush
