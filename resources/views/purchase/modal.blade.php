<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <form action="" id="form">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modal_form_title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="vendor_id">Vendor</label>
                        <select name="vendor_id" id="vendor_id" class="form-control" style="width: 100%;" required>
                            <option value="">Select Vendor</option>
                            @foreach ($vendors as $item)
                                <option value="{{ $item->id }}">[{{ $item->vendor_id }}] {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error invalid-feedback err_vendor_id" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="po_no">PO NO</label>
                        <input type="text" name="po_no" class="form-control" id="po_no"
                            placeholder="Enter PO NO" maxlength="100" required>
                        <span class="error invalid-feedback err_po_no" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="dn_no">DN NO</label>
                        <input type="text" name="dn_no" class="form-control" id="dn_no"
                            placeholder="Enter DN NO" maxlength="100" required>
                        <span class="error invalid-feedback err_dn_no" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="delv_date">Delivery Date</label>
                        <input type="text" name="delv_date" class="form-control" id="delv_date"
                            placeholder="Enter Delivery Date" maxlength="100" required>
                        <span class="error invalid-feedback err_delv_date" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="rit">RIT</label>
                        <input type="number" name="rit" class="form-control" id="rit" placeholder="Enter RIT"
                            min="1" required>
                        <span class="error invalid-feedback err_rit" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="rit">Status</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_open"
                                value="open" checked>
                            <label class="form-check-label" for="status_open">
                                <span class="badge badge-warning">Open</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_close"
                                value="close">
                            <label class="form-check-label" for="status_close">
                                <span class="badge badge-danger">Close</span>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button id="modal_form_submit" type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="modal fade" id="modal_import" tabindex="-1" aria-labelledby="modal_importLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_importLabel">Import From File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_import" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label" for="import_file">Pilih File :</label>
                        <div class="custom-file">
                            <input name="file" type="file" class="custom-file-input" id="import_file"
                                accept=".xlsx,.xls,.csv">
                            <label class="custom-file-label" for="import_file">Choose file</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="{{ asset('master/sample-purchase.xlsx') }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-download me-1" title="Download Sample"></i>Download Sample
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
                <button type="button" id="btn_import" class="btn btn-primary">
                    <i class="fas fa-upload me-1" title="Upload File"></i>Upload</button>
            </div>
        </div>
    </div>
</div>
