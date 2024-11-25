<form action="" id="new-graph-setting">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADD SETTING</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Min</label>
                            <input type="number" class="form-control mb-1" name="min" data-validation="required"
                                   min="0" value="<?= $prev->max + 1 ?>">
                            <span class="m-form__help text-muted m--regular-font-size-sm1"
                                  style="text-transform: none;">days old</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Max</label>
                            <input type="number" class="form-control mb-1" name="max" data-validation="required"
                                   min="0" value="0">
                            <span class="m-form__help text-muted m--regular-font-size-sm1"
                                  style="text-transform: none;">days old</span>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="">Description</label>
                    <input type="text" name="description" autocomplete="off" class="form-control"
                           data-validation="required">
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group mt-3">
                            <label for="">Order</label>
                            <input type="number" name="order" class="form-control mb-1"
                                   min="1" data-validation="required" value="<?= $prev->order + 1 ?>">
                            <span class="m-form__help text-muted m--regular-font-size-sm1"
                                  style="text-transform: none;">
                                Execute condition in this order.
                            </span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group mt-3">
                            <label for="">Color</label>
                            <input type="text" name="color" autocomplete="off" class="form-control"
                                   id="color-picker" value="#000000"
                                   data-validation="required">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnSave" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnSave">Save</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('#color-picker')
        .spectrum({
            type: "text",
        });
</script>