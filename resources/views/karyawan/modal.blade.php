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
                        <label for="id_card">ID Card</label>
                        <input type="text" name="id_card" class="form-control" id="id_card"
                            placeholder="Enter ID Card" required maxlength="100">
                        <span class="error invalid-feedback err_id_card" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="id_absen">ID Absen</label>
                        <input type="text" name="id_absen" class="form-control" id="id_absen"
                            placeholder="Enter ID Absen" required maxlength="100">
                        <span class="error invalid-feedback err_id_absen" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                            required maxlength="100">
                        <span class="error invalid-feedback err_name" style="display: hide;"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button id="modal_form_submit" type="submit" class="btn btn-primary"><i
                            class="fas fa-save me-1"></i>Save changes</button>
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="{{ asset('master/sample-karyawan.xlsx') }}" class="btn btn-info" target="_blank">
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
