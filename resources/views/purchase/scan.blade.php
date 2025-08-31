@extends('layouts.template', ['title' => 'Purchase', 'breadcumbs' => ['Purchase', $data->po_no, 'Scan']])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
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
            <div class="row">
                <div class="col-12 mb-2">
                    <label for="barcode" class="form-label">Barcode Barang</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="barcode" placeholder="Scan atau ketik ID barang"
                            value="" autofocus>
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

    <form action="" id="form_save">
        @csrf
        <input type="hidden" name="purchase_item_id" id="input_purchase_item_id">
        <input type="hidden" name="barcode" id="input_barcode">
        <input type="hidden" name="product_id" id="input_product_id">
    </form>


    <div class="card mb-4">
        <div class="card-body">
            <h5>Daftar Barang</h5>
            <table id="table" class="table-sm table-hover mb-0" style="width: 100%;cursor: pointer;">
                <thead>
                    <tr>
                        {{-- <th width="30">No</th> --}}
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Qty Ord</th>
                        <th>Qty Kbn</th>
                        <th>Outstanding</th>
                        <th>Qty In</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('purchase.modal_scan')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>

    <script>
        const URL_INDEX = "{{ route('purchase-items.index') }}"
    </script>
    <script>
        $(document).ready(function() {

            $('#btnScanBarcode').click(function() {
                $('#hasil').text('Loading...');
                $('#qrScannerModal').modal('show');
            });

            var table = $("#table").DataTable({
                processing: true,
                serverSide: false,
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
                    data: 'product_code',
                    className: "text-start",
                }, {
                    data: 'product_name',
                    className: "text-start",
                }, {
                    data: 'qty_ord',
                    className: "text-center",
                }, {
                    data: 'qty_kbn',
                    className: "text-center",
                }, {
                    data: 'outstanding',
                    className: "text-center",
                }, {
                    data: 'qty_in',
                    className: "text-center",
                }, {
                    data: 'product_id',
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
                            `
                        } else {
                            return data
                        }
                    }
                }],
                buttons: [{
                    text: '<i class="fa fa-save me-1"></i>Simpan',
                    className: 'btn btn-sm btn-primary bs-tooltip',
                    attr: {
                        'data-toggle': 'tooltip',
                        'title': 'Simpan Data'
                    },
                    action: function(e, dt, node, config) {
                        save_data()
                    }
                }, {
                    extend: "pageLength",
                    attr: {
                        'data-toggle': 'tooltip',
                        'title': 'Page Length'
                    },
                    className: 'btn btn-sm btn-info'
                }, {
                    text: '<i class="fa fa-trash me-1"></i>Delete All',
                    className: 'btn btn-sm btn-danger bs-tooltip',
                    attr: {
                        'data-toggle': 'tooltip',
                        'title': 'Hapus Semua Data'
                    },
                    action: function(e, dt, node, config) {
                        table.clear().draw();
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
                id = table.row(row).remove().draw()
            });

            let editRow; // simpan row yg sedang diedit

            // Klik tombol edit
            $('#table tbody').on('click', '.btn-edit', function() {
                editRow = table.row($(this).parents('tr'));
                let data = editRow.data();

                console.log(data);

                // ambil outstanding dan step
                let outstanding = parseInt(data.outstanding);
                let step = parseInt(data.qty_ord) / parseInt(data.qty_kbn);

                // tampilkan info ke modal
                $('#qty_help').html(`(Isi dalam kelipatan ${step}, MAX : ${outstanding})`);
                $('#modal_qty_title').html(`[${data.product_code}] ${data.product_name}`);

                // set min, max, step
                $('#qty_in')
                    .prop('min', step)
                    .prop('max', outstanding)
                    .prop('step', step)
                    .val(data.qty_in || step); // default isi step kalau kosong

                $('#qty_id').val(data.item_id);

                $('#modal_qty').modal('show');
            });

            $('#modal_qty').on('shown.bs.modal', function() {
                $('#qty_in').focus();
            })

            $('#form_qty').submit(function(e) {
                e.preventDefault();

                let newQty = parseInt($('#qty_in').val());
                let min = parseInt($('#qty_in').prop('min'));
                let max = parseInt($('#qty_in').prop('max'));
                let step = parseInt($('#qty_in').prop('step'));

                // validasi kelipatan
                if (newQty < min || newQty > max || newQty % step !== 0) {
                    alert(`Qty harus kelipatan ${step}, antara ${min} dan ${max}`);
                    return;
                }

                let id = $('#qty_id').val();

                // update data di row DataTable
                let rowData = editRow.data();
                rowData.qty_in = newQty;
                editRow.data(rowData).draw(false);

                $('#modal_qty').modal('hide');
                console.log('Updated row:', rowData);
            });

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
                $.get("{{ route('purchases.scan', $data->id) }}?barcode=" + encodeURIComponent(barcode))
                    .done(function(result) {
                        let t_status = '<span class="badge bg-warning fs-6">Belum Ada</span>'
                        if (result.state) {
                            t_status = '<span class="badge bg-success fs-6">Tersimpan</span>'
                            $('#save_barcode').hide()
                        } else {
                            $('#save_barcode').show()
                        }
                        $('#d_status').html(t_status)
                        let data = table.rows().data().toArray()
                        let exist = data.some(item =>
                            item.product_id == result.data.product_id
                        )
                        if (result.data.qty_in >= result.data.qty_ord) {
                            $('#div_alert').show()
                            $('#alert_content').html(
                                `Item ${result.data.product.code} Outstanding : 0 !`)
                            return
                        }

                        if (!exist) {
                            table.row.add({
                                'item_id': result.data.id,
                                'product_id': result.data.product_id,
                                'product_code': result.data.product.code,
                                'product_name': result.data.product.name,
                                'qty_kbn': result.data.qty_kbn,
                                'qty_ord': result.data.qty_ord,
                                'qty_in': 0,
                                'outstanding': parseInt(result.data.qty_ord) - parseInt(result.data
                                    .qty_in),
                            }).draw()

                            $('#div_alert_success').show()
                            $('#alert_content_success').html(
                                `Product ${result.data.product.code} ditambahkan ke tabel!`)
                        } else {
                            $('#div_alert').show()
                            $('#alert_content').html("Barang sudah ada di tabel!")
                        }

                        $('#d_barcode').text(barcode)
                        $('#input_barcode').val(barcode)
                        $('#input_purchase_item_id').val(result.data.id)
                        $('#input_product_id').val(result.data.product_id)
                        $('#div_detail').show()
                        $('#barcode').val('')
                    }).fail(function(xhr) {
                        $('#barcode').val('')
                        // show_toast('error', xhr.responseJSON.message || "Server Error!")
                        $('#div_alert').show()
                        $('#alert_content').html(xhr.responseJSON.message || "Server Error!")
                    })

            }

            function save_data() {
                $('#div_alert').hide()
                $('#div_alert_success').hide()
                let data = table.rows().data().toArray()
                if (data.length < 1) {
                    show_toast('error', 'Tabel Kosong!')
                    return
                }

                let hasError = false;

                for (let item of data) {
                    if (item.qty_in < 1) {
                        show_toast('error', `Item ${item.product_code} Qty IN Belum Terisi!`);
                        hasError = true;
                        break;
                    }
                }

                if (hasError) return; //

                swal({
                        title: 'Are you sure?',
                        text: `Simpan Semua Barang dari Tabel?`,
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            ajax_setup()
                            let rows = table.rows().data().toArray()
                            let payload = {
                                purchase_item_id: rows.map(r => r.item_id),
                                product_id: rows.map(r => r.product_id),
                                qty_in: rows.map(r => r.qty_in),
                            }
                            console.log(payload);

                            $.ajax({
                                url: "{{ route('purchases.scan', $data->id) }}",
                                method: 'POST',
                                data: payload,
                                success: function(result) {
                                    table.clear().draw();
                                    show_toast('success', result.message || 'Success!')
                                    $('#div_detail').hide()
                                },
                                error: function(xhr, status, error) {
                                    show_toast('error', xhr.responseJSON.message ||
                                        'Server Error!')
                                }
                            })
                        }
                    });
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



        })
    </script>
@endpush
