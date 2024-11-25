<div class="modal fade" tabindex="-1" role="dialog"
     id="edit-email-setting-modal">
    <form action="" id="frm-edit-email-setting-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Email Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="label">Label</label>
                        <input type="text" id="label" name="label" class="form-control" data-validation="required" autocomplete="off">
                    </div>

                    <div class="alert alert-info m-alert--outline m--regular-font-size-sm2 mt-4">
                        For <b>two</b> or <b>more</b> emails, separate each email with <b>comma</b>.
                    </div>

                    <div class="form-group pt-2">
                        <label for="to">To</label>
                        <textarea style="text-transform: none;"
                                  name="to" id="to" class="form-control" data-validation="required"></textarea>
                    </div>
                    <div class="form-group pt-2">
                        <label for="cc">Cc</label>
                        <textarea style="text-transform: none;"
                                  name="cc" id="cc" class="form-control"></textarea>
                    </div>
                    <div class="form-group pt-2">
                        <label for="bcc">Bcc</label>
                        <textarea style="text-transform: none;"
                                  name="bcc" id="bcc" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnUpdate">Save changes</button>
                    <button type="button" class="btn btn-danger btnUpdate" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>