<div class="m-content">
    <div class="row">
        <div class="col-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                INVENTORY GRAPH SETTING
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row mb-3">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <button type="button" class="btn btn-success btnNew m-btn m-btn--icon"
                                    onclick="openNewSettingModal(this)">
                                <span>
                                    <i class="fa fa-plus"></i>
                                    <span>NEW</span>
                                </span>
                            </button>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered"
                               id="tbl-inventory-graph-setting" width="100%">
                            <thead>
                            <tr>
                                <th>Min.</th>
                                <th>Max.</th>
                                <th>Order</th>
                                <th>Description</th>
                                <th>Color</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-container" tabindex="-1" role="dialog">
    </div>

    <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action=""
                  id="frm-delete-setting">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Are you sure to delete this setting?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btnDelete" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary btnDelete">YES</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
    const modalContainer = $(".modal-container");

    const tblInventoryGraphSetting = $("#tbl-inventory-graph-setting")
        .DataTable({
            dom: '<"toolbar">frtlip',
            serverSide: true,
            processing: true,
            searching: false,
            ajax: {
                url: "<?php echo base_url("configuration/inventory_graph_setting/get_inventory_graph_setting_list"); ?>",
                type: "post",
                dataType: "json",
                data: {
                    csrf_token: _csrf_hash
                }
            },
            columns: [
                {data: "min"},
                {data: "max"},
                {data: "order"},
                {data: "description"},
                {
                    data: "color",
                    width: "20%",
                    render: function (data, type, row) {
                        return "<div " +
                            "       style='position: relative; display: inline-block;" +
                            "              padding: 6px 16px 4px 16px; border-radius: 1em; " +
                            "              background-color: " + data + "'>" + data +
                            "   </div>";
                    }
                },
                {data: null, width: "8%", className: "text-center"},
            ],
            columnDefs: [
                {
                    data: null,
                    defaultContent: "",
                    targets: -1,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        let btn = "" +
                            "<button " +
                            "   title='Edit'" +
                            "   class='btn btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--hover-accent'" +
                            "   onclick='open_edit_modal(" + row.id + ")'>" +
                            "   <i class='fa fa-edit'></i>" +
                            "</button>";

                        btn += " " +
                            "<button " + (parseInt(row.non_conditional) === 1 ? " disabled " : "") +
                            (parseInt(row.non_conditional) === 0 ? " title='Delete' " : "") +
                            "   class='btn btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--hover-accent'" +
                            "   onclick='open_confirm_delete(" + row.id + ")'>" +
                            "   <i class='fa fa-trash-o'></i>" +
                            "</button>";
                        return btn;
                    },
                }, {
                    targets: "_all",
                    defaultContent: "",
                }],
        });

    function open_edit_modal(id) {
        $.ajax({
            url: "<?php echo base_url('configuration/inventory_graph_setting/get_edit_modal'); ?>" + '/' + id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                modalContainer
                    .empty()
                    .append(response.html);
                modalContainer.modal("show");
            }
        })
    }

    function open_confirm_delete(id) {
        const modal = $("#confirm-delete-modal");
        const form = $("#frm-delete-setting");
        const url = "<?=base_url('configuration/inventory_graph_setting/delete_setting/')?>";
        form.attr('action', url + id);

        modal.modal("show");
    }

    $("#frm-delete-setting")
        .on("submit", function (e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');

            $.ajax({
                url,
                type: "GET",
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        toastr.success("Setting successfully deleted.", "Delete Setting", 10000);
                    } else {
                        toastr.error("Error occurred while deleting.", "Delete Error", 10000);
                    }

                    form.attr('action', "");
                    $("#confirm-delete-modal").modal("hide");
                    tblInventoryGraphSetting.ajax.reload();
                }
            })
        });

    const mContent = $(".m-content");
    mContent
        .on("submit", "#edit-graph-setting", function (e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);

            if (form.isValid()) {
                $.ajax({
                    url: "<?php echo base_url('configuration/inventory_graph_setting/edit_graph_setting'); ?>",
                    type: "POST",
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            toastr.success("Setting successfully updated.", "Update Setting", 10000);
                        } else {
                            toastr.error("Error occurred while updating.", "Update Error", 10000);
                        }

                        form.resetForm();
                        tblInventoryGraphSetting.ajax.reload();
                        modalContainer.modal("hide");
                    }
                })
            }
        });

    mContent
        .on("submit", "#new-graph-setting", function (e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);

            if (form.isValid()) {
                $.ajax({
                    url: "<?php echo base_url('configuration/inventory_graph_setting/add_graph_setting'); ?>",
                    type: "POST",
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            toastr.success("Setting successfully added.", "Add Setting", 10000);
                        } else {
                            toastr.error("Error occurred while adding.", "Add Error", 10000);
                        }

                        form.resetForm();
                        tblInventoryGraphSetting.ajax.reload();
                        modalContainer.modal("hide");
                    }
                })
            }
        });

    function openNewSettingModal(el) {
        $.ajax({
            url: "<?php echo base_url('configuration/inventory_graph_setting/get_add_setting_modal'); ?>",
            type: "GET",
            dataType: "json",
            success: function (response) {
                modalContainer
                    .empty()
                    .append(response.html);
                modalContainer.modal("show");
            }
        })
    }
</script>