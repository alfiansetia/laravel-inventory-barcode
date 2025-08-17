@extends('layouts.template', ['title' => 'Scan'])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('contents')
    <div class="card mb-4">
        <div class="card-body">
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
                    </div>
                    <div id="emailHelp" class="form-text">Format Barcode : SEBANGO+PO_NO+QTY_KBN.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4" style="display: none" id="div_detail">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h5 class="card-title mb-0">Information Barcode <span class="text-danger" id="d_barcode"></span>
                        </h5>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-text">VENDOR</div>
                                <div class="fs-5" id="d_vendor">Loading....</div>
                            </div>
                            <div class="mb-2">
                                <div class="form-text">PO NO</div>
                                <div class="fs-5" id="d_po_no">Loading....</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-text">DELIVERY DATE</div>
                                <div class="fs-5" id="d_delv_date">Loading....</div>
                            </div>
                            <div class="mb-2">
                                <div class="form-text">DN NO</div>
                                <div class="fs-5" id="d_dn_no">Loading....</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-text">RIT</div>
                                <div class="fs-5" id="d_rit">Loading....</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-text">PRODUCT</div>
                                <div class="fs-5" id="d_product">Loading....</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-text">LOT</div>
                                <div class="fs-5" id="d_lot">Loading....</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="mb-2">
                                <div class="form-text">QTY KBN</div>
                                <div class="fs-5" id="d_qty_kbn">Loading....</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="mb-2">
                                <div class="form-text">STATUS BARCODE</div>
                                <div class="fs-5" id="d_status">Loading....</div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-warning" id="btn_close">
                                <i class="fas fa-times"></i> CLOSE
                            </button>
                            <button class="btn btn-danger" id="save_barcode">
                                <i class="far fa-save"></i> SAVE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger" role="alert" style="display: none" id="div_alert">
        <h4 id="alert_content"></h4>
    </div>

    <form action="" id="form_save">
        @csrf
        <input type="hidden" name="purchase_item_id" id="input_purchase_item_id">
        <input type="hidden" name="barcode" id="input_barcode">
        <input type="hidden" name="product_id" id="input_product_id">
    </form>
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const URL_INDEX = "{{ route('purchase-items.index') }}"
    </script>
    <script>
        $(document).ready(function() {

            $('#btnScanBarcode').click(function() {
                $('#hasil').text('Loading...');
                $('#qrScannerModal').modal('show');
            });

            function search_data() {
                $('#div_alert').hide()
                $('#div_detail').hide()
                $('#save_barcode').show()
                let barcode = $('#barcode').val()
                if (barcode == null || barcode == '') {
                    return
                }
                $.get("{{ route('barcodes.get') }}/?barcode=" + encodeURIComponent(barcode)).done(function(result) {
                    $('#d_vendor').html(
                        `<b>[${result.data.purchase.vendor.vendor_id}]</b> ${result.data.purchase.vendor.name}`
                    )
                    $('#d_po_no').text(result.data.purchase.po_no)
                    $('#d_rit').text(result.data.purchase.rit)
                    $('#d_delv_date').text(result.data.purchase.delv_date)
                    $('#d_dn_no').text(result.data.purchase.dn_no)
                    $('#d_product').text(`[${result.data.product.code}] ${result.data.product.name}`)
                    $('#d_lot').text(result.data.lot)
                    $('#d_qty_kbn').text(result.data.qty_kbn)
                    let t_status = '<span class="badge bg-warning fs-6">Belum Ada</span>'
                    if (result.state) {
                        t_status = '<span class="badge bg-success fs-6">Tersimpan</span>'
                        $('#save_barcode').hide()
                    } else {
                        $('#save_barcode').show()
                    }
                    $('#d_status').html(t_status)

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

            $('#save_barcode').click(function() {
                let barcode = $('#input_barcode').val()
                swal({
                        title: 'Are you sure?',
                        text: `Save Barcode ${barcode}?`,
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            ajax_setup()
                            let data = new FormData($('#form_save')[0])
                            $.ajax({
                                url: "{{ route('barcodes.store') }}",
                                method: 'POST',
                                processData: false,
                                contentType: false,
                                data: data,
                                success: function(result) {
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


            })

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
