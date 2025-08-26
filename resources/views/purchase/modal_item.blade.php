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
                    <input type="hidden" name="purchase_id" value="{{ $data->id }}">
                    <div class="form-group">
                        <label for="product_id">Product</label>
                        <select name="product_id" id="product_id" class="form-control" style="width: 100%;" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $item)
                                <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="error invalid-feedback err_product_id" style="display: hide;"></span>
                    </div>

                    <div class="form-group">
                        <label for="lot">Lot</label>
                        <input type="number" name="lot" class="form-control" id="lot" placeholder="Enter Lot"
                            min="1" required>
                        <span class="error invalid-feedback err_lot" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="qty_kbn">Qty KBN</label>
                        <input type="number" name="qty_kbn" class="form-control" id="qty_kbn"
                            placeholder="Enter Qty KBN" min="1" required>
                        <span class="error invalid-feedback err_qty_kbn" style="display: hide;"></span>
                    </div>
                    <div class="form-group">
                        <label for="qty_ord">Qty Order</label>
                        <input type="number" name="qty_ord" class="form-control" id="qty_ord"
                            placeholder="Enter Qty Order" min="1" required>
                        <span class="error invalid-feedback err_qty_ord" style="display: hide;"></span>
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


<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="modal_detailLabel"
    aria-hidden="true">
    <form action="" id="form">
        <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modal_detail_title">Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table-sm table-hover text-nowrap" id="table_item"
                        style="width: 100%;cursor: pointer;">
                        <thead>
                            <tr>
                                <th>Input Date</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
