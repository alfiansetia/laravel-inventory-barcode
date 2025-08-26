<div class="modal fade" id="modal_qty" tabindex="-1" role="dialog" aria-labelledby="modal_qtyLabel" aria-hidden="true">
    <form action="" id="form_qty">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modal_qty_title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="qty_id" value="0">
                    <div class="form-group">
                        <label for="qty_in">Qty IN <span class="text-danger" id="qty_help"></span></label>
                        <input type="number" name="qty_in" class="form-control" id="qty_in"
                            placeholder="Enter Qty IN" min="1" required>
                        <span class="error invalid-feedback err_qty_in" style="display: hide;"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save
                        changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
