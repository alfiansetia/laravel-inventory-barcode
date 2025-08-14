<div class="modal fade" id="modalScanner" tabindex="-1" role="dialog" aria-labelledby="modalScannerLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalScanner_title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div id="scanner-container">
                        <div id="interactive" class="viewport">
                            <!-- Scanner Frame Overlay -->
                            <div class="scanner-frame">
                                <div class="corner-bottom-left"></div>
                                <div class="corner-bottom-right"></div>
                                <div class="scan-line"></div>
                            </div>
                            <!-- Scanner Instructions -->
                            <div class="scanner-instructions">
                                <i class="ti ti-scan me-1"></i>
                                Arahkan barcode ke dalam frame merah
                            </div>
                            <video autoplay="true" preload="auto" src="" muted="true"
                                playsinline="true"></video><canvas class="drawingBuffer" width="800"
                                height="600"></canvas><br clear="all">
                        </div>
                    </div>
                    <div id="scan-status" class="mt-2"><span class="text-success">Camera ready. Point to a
                            barcode.</span></div>

                    <!-- Scanner Controls -->
                    <div class="scanner-controls mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" id="btnToggleFlash">
                            <i class="ti ti-bulb"></i> Flash
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info me-2" id="btnManualFocus">
                            <i class="ti ti-focus"></i> Focus
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="btnRestartScanner">
                            <i class="ti ti-refresh"></i> Restart
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" id="btnManualInput">
                            <i class="ti ti-keyboard"></i> Manual
                        </button>
                    </div>

                    <!-- Manual Input Section (Hidden by default) -->
                    <div id="manualInputSection" class="mt-3" style="display: none;">
                        <div class="alert alert-warning">
                            <i class="ti ti-edit me-2"></i>
                            <strong>Manual Input:</strong> Ketik barcode secara manual jika scanner tidak berfungsi
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="manualBarcodeInput"
                                placeholder="Ketik barcode di sini...">
                            <button type="button" class="btn btn-primary" id="btnSubmitManual">
                                <i class="ti ti-check"></i> Proses
                            </button>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>Tips:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Arahkan barcode ke dalam <strong class="text-danger">frame merah</strong> yang bergerak</li>
                        <li>Pastikan barcode dalam pencahayaan yang cukup (gunakan Flash jika perlu)</li>
                        <li>Tahan ponsel dengan stabil pada jarak 10-20 cm dari barcode</li>
                        <li>Pastikan barcode tidak terpotong atau buram</li>
                        <li>Scanner akan otomatis mendeteksi barcode di dalam frame</li>
                        <li>Coba restart scanner jika tidak responsif</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button id="modal_form_submit" type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


{{-- <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">SCAN BARCODE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reader" style="width:100%"></div>
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>Tips:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Arahkan barcode ke dalam <strong class="text-danger">frame</strong></li>
                        <li>Pastikan barcode dalam pencahayaan yang cukup</li>
                        <li>Tahan ponsel dengan stabil pada jarak 10-20 cm dari barcode</li>
                        <li>Pastikan barcode tidak terpotong atau buram</li>
                        <li>Scanner akan otomatis mendeteksi barcode di dalam frame</li>
                        <li>Coba restart scanner jika tidak responsif</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div> --}}


<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <video id="preview" autoplay playsinline
                    style="width:100%; max-height:250px; object-fit:cover; transform: rotate(0deg);"></video>
                <div class="mt-3">
                    <button id="captureBtn" class="btn btn-primary">📸 Capture & Scan</button>
                    <button id="retakeBtn" class="btn btn-secondary d-none">🔄 Retake</button>
                </div>
            </div>
        </div>
    </div>
</div>
