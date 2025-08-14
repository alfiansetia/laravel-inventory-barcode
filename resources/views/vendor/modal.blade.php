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
                        <label for="vendor_id">Vendor ID</label>
                        <input type="text" name="vendor_id" class="form-control" id="vendor_id"
                            placeholder="Enter Vendor ID" required maxlength="100">
                        <span class="error invalid-feedback err_vendor_id" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Vendor Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                            required maxlength="100">
                        <span class="error invalid-feedback err_name" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" maxlength="200" placeholder="Enter Address"></textarea>
                        <span class="error invalid-feedback err_address" style="display: hide;"></span>
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
