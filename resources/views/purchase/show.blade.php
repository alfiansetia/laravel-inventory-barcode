@extends('layouts.template', ['title' => 'Purchase'])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
@endpush

@section('contents')
    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-4">
                <h5 class="card-title mb-0">Informasi Transaksi</h5>
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
                </div>
            </div>

            <h5 class="card-title mb-3">Detail Barang</h5>
            <div class="table-responsive border rounded">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th width="150">Product Code</th>
                            <th>Product Name</th>
                            <th width="100" class="text-center">Lot</th>
                            <th width="150" class="text-end">Qty KBN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->items as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $item->product->code }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->lot }}</td>
                                <td class="text-center">{{ $item->qty_kbn }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang-masuk/8/edit"
                    class="btn btn-primary me-2">
                    <i class="ti ti-pencil"></i> Edit
                </a>


                <!-- Print PO Button - Always available -->
                <a href="https://demo.codenul.com/laravel/persediaan-barang/public/barang-masuk/print/8?type=po"
                    class="btn btn-success me-2" target="_blank">
                    <i class="ti ti-file-invoice"></i> Cetak Purchase Order
                </a>

                <!-- Approve and Reject Buttons - Only for PO status -->
                <form action="https://demo.codenul.com/laravel/persediaan-barang/public/barang-masuk/8/approve"
                    method="POST" style="display: inline;" class="me-2">
                    <input type="hidden" name="_token" value="mOpZyJ7R2wN6hoBE2NV43wnU3qYK5mKJN6NrTpZe"
                        autocomplete="off"> <input type="hidden" name="_method" value="PATCH"> <button type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini?')">
                        <i class="ti ti-check"></i> Approve
                    </button>
                </form>
                <form action="https://demo.codenul.com/laravel/persediaan-barang/public/barang-masuk/8/reject"
                    method="POST" style="display: inline;">
                    <input type="hidden" name="_token" value="mOpZyJ7R2wN6hoBE2NV43wnU3qYK5mKJN6NrTpZe"
                        autocomplete="off"> <input type="hidden" name="_method" value="PATCH"> <button type="submit"
                        class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak transaksi ini?')">
                        <i class="ti ti-x"></i> Reject
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('purchase.modal')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    <script>
        const URL_INDEX = "{{ route('purchases.index') }}"
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
                data: 'vendor.name',
            }, {
                data: 'po_no',
            }, {
                data: 'dn_no',
            }, {
                data: 'delv_date',
            }, {
                data: 'rit',
            }, {
                data: 'id',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row, meta) {
                    if (type == 'display') {
                        return `
                        <button type="button" class="btn btn-info btn-sm btn-view"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-edit"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>`;
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

        const old_up = $('#btn_import').html()

        $('#btn_import').on('click', function() {
            var formData = new FormData($('#form_import')[0]);
            $.ajax({
                url: "{{ route('purchases.import') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btn_import').prop('disabled', true).html('Uploading...');
                },
                success: function(res) {
                    table.ajax.reload();
                    $('#btn_import').prop('disabled', false).html(old_up);
                    $('#modal_import').modal('hide');
                    show_toast('success', 'Import berhasil!')
                },
                error: function(xhr) {
                    $('#btn_import').prop('disabled', false).html(old_up);
                    show_toast('error', xhr.responseJSON.message || 'Terjadi kesalahan saat import.')
                }
            });
        });
    </script>
@endpush
