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
                        <label for="number">Number</label>
                        <input type="text" name="number" class="form-control" id="number"
                            placeholder="Enter Number" maxlength="100" required>
                        <span class="error invalid-feedback err_number" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" name="date" class="form-control" id="date"
                            placeholder="Enter Date" maxlength="100" required>
                        <span class="error invalid-feedback err_date" style="display: hide;"></span>
                    </div>

                    <div class="form-group">
                        <label for="desc">Desc</label>
                        <textarea name="desc" id="desc" class="form-control" maxlength="200" placeholder="Enter Desc"></textarea>
                        <span class="error invalid-feedback err_desc" style="display: hide;"></span>
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
                        <input class="form-control" name="file" type="file" id="import_file"
                            accept=".xlsx,.xls,.csv">
                        {{-- <div class="custom-file">
                            <input name="file" type="file" class="custom-file-input" id="import_file"
                                accept=".xlsx,.xls,.csv">
                            <label class="custom-file-label" for="import_file">Choose file</label>
                        </div> --}}
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
