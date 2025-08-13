<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <form action="" id="form">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modal_form_title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="code">Product Code</label>
                        <input type="text" name="code" class="form-control" id="code"
                            placeholder="Enter Code" maxlength="100" required>
                        <span class="error invalid-feedback err_code" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                            maxlength="100" required>
                        <span class="error invalid-feedback err_name" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea name="desc" id="desc" class="form-control" maxlength="200" placeholder="Enter Desc"></textarea>
                        <span class="error invalid-feedback err_desc" style="display: hide;"></span>
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
    </form>
</div>


<div class="modal fade" id="modal_import" tabindex="-1" aria-labelledby="modal_importLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_importLabel">Import From File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                <a href="{{ asset('master_import/onescania.xlsx') }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-download mr-1" data-toggle="tooltip" title="Download Sample"></i>Download Sample
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" id="btn_import" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>
