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
                        <label for="satuan">Product Satuan</label>
                        <input type="text" name="satuan" class="form-control" id="satuan"
                            placeholder="Enter Satuan" maxlength="10" required>
                        <span class="error invalid-feedback err_satuan" style="display: hide;"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button id="modal_form_submit" type="submit" class="btn btn-primary"><i
                            class="fas fa-save me-1"></i>Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
