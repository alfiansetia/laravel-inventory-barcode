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
                            value="BAH-MTL-034+76862-1+2" autofocus>
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <button type="button" class="btn btn-secondary" id="btnScanBarcode">
                        <i class="ti ti-camera"></i>
                        <span>Scan</span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn_search">
                        <i class="ti ti-search"></i>
                        <span>Cari Barcode</span>
                    </button>
                </div>


                <div class="col-12" style="display: none" id="div_detail">
                    <div class="mb-2">
                        <h5 class="card-title mb-0">Information Barcode <span id="d_barcode"></span></h5>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-text">Vendor</div>
                                <div class="fs-5" id="d_vendor">Loading....</div>
                            </div>
                            <div class="mb-2">
                                <div class="form-text">PO NO</div>
                                <div class="fs-5" id="d_po_no">Loading....</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-text">Delivery Date</div>
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
                                <div class="form-text">Product</div>
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
                        <div class="col-12">
                            <button class="btn btn-danger w-100">SAVE BARCODE</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    @include('barcode.modal')
@endsection
@push('js')
    <script src="{{ asset('kai/lib/datatable-new/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('kai/lib/html5-qrcode/html5-qrcode.min.js') }}"></script>

    <script>
        const URL_INDEX = "{{ route('purchase-items.index') }}"
    </script>
    <script>
        $(document).ready(function() {

            $('#btnScanBarcode').click(function() {
                $('#qrScannerModal').modal('show');
                // $('#modalScanner').modal('show');
                // initBarcodeScanner();
            });

            var html5QrCode;
            var qrCodeSuccessCallback = function(decodedText, decodedResult) {
                $('#barcode').val(decodedText)
                console.log(`Code matched = ${decodedText}`, decodedResult);
                alert(`Code matched = ${decodedText}`, decodedResult);
                $('#qrScannerModal').modal('hide');
                html5QrCode.stop().then(() => {
                    console.log("QR Code scanning stopped.");
                }).catch(err => {
                    console.error("Unable to stop scanning.", err);
                });
            };
            $('#qrScannerModal').on('shown.bs.modal', function() {
                html5QrCode = new Html5Qrcode("reader");

                // Tambahkan opsi formatsToSupport untuk scan barcode juga
                const config = {
                    fps: 10,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        // Biar memanjang, misalnya 80% dari lebar layar dan tinggi 1/3 lebar
                        const width = viewfinderWidth * 0.8;
                        const height = width / 3; // rasio 3:1 (lebar : tinggi)
                        return {
                            width: width,
                            height: height
                        };
                    },
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.EAN_8,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E
                    ]
                };
                html5QrCode.start({
                        facingMode: "environment"
                    },
                    config,
                    function(decodedText) {
                        qrCodeSuccessCallback(decodedText); // Panggil callback lama
                    }
                ).catch(err => {
                    show_toast('error', 'Unable to start scanning : ' + err)
                    console.error("Unable to start scanning.", err);
                });
            });

            $('#qrScannerModal').on('hidden.bs.modal', function() {
                $('body').addClass('modal-open');
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        console.log("Barcode scanning stopped.");
                    }).catch(err => {
                        console.error("Unable to stop scanning.", err);
                    });
                }
            });

            $('#btn_search').click(function() {
                $('#div_detail').hide()
                let barcode = $('#barcode').val()
                if (barcode == '' || barcode == null) {
                    show_toast('error', 'Isi Barcode!')
                    $('#barcode').focus()
                    return
                }
                search_data(barcode)
            })

            function search_data(barcode) {
                console.log(barcode);
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
                    // $('#d_po_no').text(result.data.purchase.po_no)
                    $('#d_barcode').text(barcode)
                    $('#div_detail').show()
                }).fail(function(xhr) {
                    show_toast('error', xhr.responseJSON.message || "Server Error!")
                })

            }




        })
    </script>
@endpush
