<?php $actions = $this->core_layout->getCurrentActions(); ?>
<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Masterfile - Warehouse
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools"></div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right m--margin-bottom-30">
                        <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-12">
                                        <a href="javascript:void(0);"
                                            class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btnNew" id="btnNewWarehouse">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    New Warehouse
                                                </span>
                                            </span>
                                        </a>
                                        <button class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btnNew" id="btnSync" disabled>
                                            <span>
                                                <i class="la la-download"></i>
                                                <span>
                                                    Sync Data
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                                <div class="m-input-icon m-input-icon--left" style="border: 1px solid #c3c3c3;">
                                    <input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span> <i class="la la-search"></i> </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
                        <table class="table table-striped table-bordered" id="table-warehouse" width="100%">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddWarehouse" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div id="modalWarehouseContainer" class="modal-content">
            <form action="<?=base_url('warehouse/add_warehouse') ?>" id="warehouse-form" class="form-group" method="POST" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Warehouse</h5>
                    <button type="button" class="close modalClose" aria-label="Close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" id="code" class="form-control" name="code" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" class="form-control" name="name" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label for="path">Path</label>
                        <input type="text" id="path" class="form-control" name="path" data-validation="required">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-submit btnSave">Save</button>
                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditWarehouse" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div id="modalWarehouseContainer" class="modal-content">
            <form action="<?=base_url('warehouse/edit_warehouse') ?>" id="warehouse-form-edit" class="form-group" method="POST" autocomplete="off">
                <input type="hidden" id="id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Warehouse</h5>
                    <button type="button" class="close modalClose" aria-label="Close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="code-edit">Code</label>
                        <input type="text" id="code-edit" class="form-control" name="code" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label for="name-edit">Name</label>
                        <input type="text" id="name-edit" class="form-control" name="name" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label for="path-edit">Path</label>
                        <input type="text" id="path-edit" class="form-control" name="path" data-validation="required">
                    </div>
                    <div class="form-group">
						<label class="form-control-label">Status</label>
						<div class="m-radio-inline">
							<label class="m-radio">
								<input type="radio" name="status" id="active" value="1">
									Active
								<span></span>
							</label>
							<label class="m-radio">
								<input type="radio" name="status" id="disable" value="0">
									Disable
								<span></span>
							</label>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-submit btnSave">Save</button>
                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var _currentActions = <?php echo json_encode($actions); ?>;
    var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
    var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var table;
    var search_val = "";

    table = $("#table-warehouse").DataTable({
        dom: "rtlip",
        serverSide: true,
        processing: true,
        ajax: {
            url: "<?=base_url('warehouse/get_datatable_list') ?>",
            type: "post",
            dataType: "json",
            data: function(d){
                d.csrf_token = _csrf_hash;
                d.search['value'] = search_val;
            },
            global: false
        },
        order: [[1, 'desc']],
        columns: [
            { title: `<label class="m-checkbox m-checkbox--air m-checkbox--state-success"><input type="checkbox" id="cb-select-all"><span></span></label>`, data: null, width: '2%', orderable: false,
                render: function(data, type, row, meta){
                    var html = '';

                    var disabled = row.status == 1 ? '' : 'disabled';

                    html = `<label class="m-checkbox m-checkbox--air m-checkbox--state-info cb${row.id}">
                                <input type="checkbox" class="selectedWarehouse" id="selectedWarehouse" name="selected[]" value="${row.id}" warehouse="${row.name}"id="cb${row.id}" ${disabled}>
                                <span></span>
                            </label>`

                    return html;
                }
            },
            { title: 'Name', data: 'name', width: '30%' },
            { title: 'Path', data: 'path', width: '40%' },
            { title: 'Status', data: 'status', width: '10%',
                render: function(data){
                    return data == 1 ? '<span class="m-badge m-badge--success m-badge--wide">Active</span>' : '<span class="m-badge m-badge--danger m-badge--wide">Inactive</span>';
                }
            },
            { title: 'Actions', data: null, width: '10%',
                render: function(data, type, row, meta){
                    var html = "";

                    if (typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
                        html += "<button type='button' " +
                                "        data-toggle='m-tooltip'" +
                                "        data-original-title='Edit'" +
                                "        data-skin='dark'" +
                                "        id='btnEditWarehouse' " +
                                "        class='btn btn-default btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEditItem' " +
                                "        data-id='" + row.id + "'>" +
                                "           <i class='la la-edit'></i>" +
                                "</button>";
                    }

                    if (typeof _currentActions !== "undefined" && jQuery.inArray("sync", _currentActions) !== -1){
                        html += "<button type='button' " +
                                "        data-toggle='m-tooltip'" +
                                "        data-original-title='Edit'" +
                                "        data-skin='dark'" +
                                "        class='btn btn-default btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEditItem' " +
                                "        data-id='" + row.id + "'>" +
                                "           <i class='la la-sync'></i>" +
                                "</button>";
                    }

                    return html;
                }
            },
        ]
    });

    $(document).on("click", "#cb-select-all", function(){
        var isChecked = $(this).is(':checked');
        $(".selectedWarehouse").each(function(){
            if(typeof $(this).attr('disabled') == 'undefined'){
                $(this).prop('checked', isChecked);
            }else{
                $(this).prop('checked', false);
            }
        });

        if($('.selectedWarehouse:checked').length > 0){
            $("#btnSync").prop('disabled', false);
        }else{
            $("#btnSync").prop('disabled', true);
        }
    });

    $(document).on('click', "#selectedWarehouse", function(){
        if($('.selectedWarehouse:checked').length !== $('.selectedWarehouse').length){
            $("#cb-select-all").prop('checked', false);
        }else{
            $("#cb-select-all").prop('checked', true);
        }

        if($('.selectedWarehouse:checked').length > 0){
            $("#btnSync").prop('disabled', false);
        }else{
            $("#btnSync").prop('disabled', true);
        }
    });

    $("#btnNewWarehouse").on('click', function(){
        $("#modalAddWarehouse").modal();

        $("#warehouse-form").trigger('reset');
    });

    $.validate({
        form: '#warehouse-form',
        lang: 'en',
        onSuccess: function (form) {
            var currentForm = form[0];
            var formUrl = currentForm.action;
            var formData = $(currentForm).serialize();

            $.ajax({
                url: formUrl,
                type: "POST",
                data: formData,
                beforeSend: function(){
                    $(currentForm).find(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
                },
                success: function(response){
                    if(response.state){
                        toastr.success(response.msg, "New Warehouse");
                        $("#modalAddWarehouse").modal('hide');
                        table.ajax.reload();
                    }else{
                        toastr.error(response.msg, "New Warehouse");
                    }

                    $(currentForm).find(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
                }
            })

            return false;
        }
    });

    $(document).on('click', "#btnEditWarehouse", function(){
        var id = $(this).data('id');
        var url = '<?=base_url('warehouse/get_warehouse_data/') ?>' + id + '';

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response){
                if(response.state){
                    var data = response.data;

                    $("#id").val(data.id);
                    $("#code-edit").val(data.code);
                    $("#name-edit").val(data.name);
                    $("#path-edit").val(data.path);

                    if(data.status == 1){
                        $("#active").prop('checked', true);
                    }else{
                        $("#disable").prop('checked', true);
                    }

                    $("#modalEditWarehouse").modal();
                }else{
                    toastr.error("No Data Found.", "Edit Warehouse");
                }
            }
        })

        $("#warehouse-form-edit").trigger('reset');
    });

    $.validate({
        form: '#warehouse-form-edit',
        lang: 'en',
        onSuccess: function (form) {
            var currentForm = form[0];
            var formUrl = currentForm.action;
            var formData = $(currentForm).serialize();

            $.ajax({
                url: formUrl,
                type: "POST",
                data: formData,
                beforeSend: function(){
                    $(currentForm).find(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
                },
                success: function(response){
                    if(response.state){
                        toastr.success(response.msg, "Update Warehouse");
                        $("#modalEditWarehouse").modal('hide');
                        table.ajax.reload();
                    }else{
                        toastr.error(response.msg, "Update Warehouse");
                    }

                    $(currentForm).find(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
                }
            })

            return false;
        }
    });

    $(document).on('click', "#btnSync", function(){
        var data = [];

        var time = 500;
        $("#selectedWarehouse:checked").each( function(){
            var id = $(this).val();
            var warehouse = $(this).attr('warehouse').toUpperCase();

            setTimeout( function(){
                $.ajax({
                    url: '<?=base_url('warehouse/check_available') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _csrf_token: _csrf_hash,
                        id: id
                    },
                    global: false,
                    success: function(response){
                        if(response.state){
                            toastr.success(`Warehouse "${warehouse}" Available for Sync`, 'Sync', 15000);

                            $.ajax({
                                url: '<?=base_url('warehouse/sync_warehouse_data') ?>/' + id,
                                type: 'GET',
                                dataType: 'JSON',
                                global: false,
                                success: function(data){
                                    if(data.state){
                                        toastr.success(data.msg, 'Sync');
                                    }else{
                                        toastr.error(data.msg, 'Sync');
                                    }
                                }
                            })
                        }else{
                            toastr.error(`Warehouse "${warehouse}" is unavailable for syncing data.`, 'Sync', 15000);
                        }
                    }
                });
            }, time);

            time += 500;
        });
    });

    $('#generalSearch').donetyping(function(callback) {
        search_val = $(this).val();
        table.ajax.reload();
    });

</script>