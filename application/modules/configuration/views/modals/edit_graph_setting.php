<form action="" id="edit-graph-setting">
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">UPDATE SETTING</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Min</label>
                            <input type="number" class="form-control mb-1" name="min" value="<?= $min ?>"
                                <?= (int)$non_conditional === 0 ?: "disabled" ?>
                                <?= (int)$non_conditional === 1 ?: "data-validation='required'" ?>>
                            <span class="m-form__help text-muted m--regular-font-size-sm1"
                                  style="text-transform: none;">days old</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Max</label>
                            <input type="number" class="form-control mb-1" name="max" value="<?= $max ?>"
                                <?= (int)$non_conditional === 0 ?: "disabled" ?>
                                <?= (int)$non_conditional === 1 ?: "data-validation='required'" ?>>
                            <span class="m-form__help text-muted m--regular-font-size-sm1"
                                  style="text-transform: none;">days old</span>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="">Description</label>
                    <input type="text" name="description" autocomplete="off" class="form-control"
                           value="<?= $description ?>"
                        <?= (int)$non_conditional === 0 ?: "disabled" ?>
                        <?= (int)$non_conditional === 1 ?: "data-validation='required'" ?>>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group mt-3">
                            <label for="">Order</label>
                            <input type="number" name="order" value="<?= $order ?>" class="form-control mb-1"
                                   min="1"
                                <?= (int)$non_conditional === 0 ?: "disabled" ?>
                                <?= (int)$non_conditional === 1 ?: "data-validation='required'" ?>>
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
                                   id="color-picker"
                                   value="<?= $color ?>" data-validation="required">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnSave">Save Changes</button>
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