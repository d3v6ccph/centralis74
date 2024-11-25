<?php $actions = $this->core_layout->getCurrentActions(); ?>
<div class="m-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="m-portlet m-portlet--mobile">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
                                Email Template
							</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item">
								<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
									<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary btnSave m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
										<i class="la la-ellipsis-h m--font-brand"></i>
									</a>
									<div class="m-dropdown__wrapper">
										<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 21.4931px;"></span>
										<div class="m-dropdown__inner">
											<div class="m-dropdown__body">
												<div class="m-dropdown__content">
													<ul class="m-nav">
														<li class="m-nav__section m-nav__section--first">
															<span class="m-nav__section-text">
																Quick Actions
															</span>
														</li>
														<li class="m-nav__item">
															<a href="javascript:void(0);" id="m_email_protocol" class="m-nav__link">
																<em class="m-nav__link-icon flaticon-cogwheel"></em>
																<span class="m-nav__link-text">
																	Protocol Settings
																</span>
															</a>
														</li>
														<li class="m-nav__separator m-nav__separator--fit m--hide"></li>
														<li class="m-nav__item m--hide">
															<a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
																Submit
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                        <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-4">
                                        <button id="add_new" data-toggle='modal' data-target='#new_modal' class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btnNew" stype="button">
                                            <span> 
                                                <em class="la la-plus"></em>
                                                <span>
                                                    New
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                            <div class="row align-items-center">
                                <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="colms-12">
                                        <div class="btn-group m-btn-group" role="group" aria-label="...">
                                            <button type="button" class="btn btn-primary" id="reload_dtTbl">
                                                <em class="la la-refresh"></em>
                                            </button>
                                            <div class="m-btn-group btn-group" role="group">
                                                <button id="tbl-btn-share" type="button" class="btn btn-success m-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <em class="la la-table"></em>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item" href="#">
                                                        Dropdown link
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        Dropdown link
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        Dropdown link
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        Dropdown link
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="m-btn-group btn-group" role="group">
                                                <button id="tbl-btn-share" type="button" class="btn btn-info m-btn m-btn--pill-last dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <em class="la la-share"></em>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop2" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item" href="#">
                                                        Excel
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
                        <table class="table table-striped table-bordered" id="table-email-template" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Send To</th>
                                    <th>CC To</th>
                                    <th>BCC to</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>	
                            </tbody>
                        </table>
                    </div>
				</div>
			</div>
			<!--end::Portlet-->
		</div>
	</div>
</div>

<div class="modal fade" id="modalProtocolSettings" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Update Protocol Settings
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						×
					</span>
				</button>
			</div>
			<form id="edit_email_protocol" action="<?php echo site_url("email/update_protocol");?>">
			<input type="hidden" name="id" />
			<div class="col-12 modal-body">
				<div class="form-group m-form__group row has-error">
					<label class="col-6 col-form-label form-control-label">
						Server Name *
					</label>
					<div class="col-12">
						<input type="text" class="form-control error" name="server_name" data-validation="required" autocomplete="off">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label class="col-6 col-form-label form-control-label">
						Protocol *
					</label>
					<div class="col-12">
						<input type="text" class="form-control" name="protocol" data-validation="required" autocomplete="off">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label class="col-6 col-form-label form-control-label">
						Host *
					</label>
					<div class="col-12">
						<input type="text" class="form-control" name="smtp_host" data-validation="required" autocomplete="off">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label class="col-6 col-form-label form-control-label">
						Port *
					</label>
					<div class="col-12">
						<input type="text" class="form-control" name="smtp_port" data-validation="required" autocomplete="off">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label class="col-6 col-form-label form-control-label">
						Crypto *
					</label>
					<div class="col-12">
						<input type="text" class="form-control" name="smtp_crypto" data-validation="required" autocomplete="off">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label class="col-6 col-form-label form-control-label">
						Username *
					</label>
					<div class="col-12">
						<input type="text" class="form-control" name="smtp_user" data-validation="required" autocomplete="off">
					</div>
				</div>
				<div class="form-group m-form__group row">
					<label class="col-6 col-form-label form-control-label">
						Password *
					</label>
					<div class="col-12">
						<input type="password" class="form-control" name="smtp_pass" data-validation="required" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="modal-footer">
			<button type="submit" class="btn btn-primary btnSave">
					Save changes
				</button>
				<button type="button" class="btn btn-danger btnClose" data-dismiss="modal">
					Close
				</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="new_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Add Data
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
            <form id="new_form" action="<?php echo site_url("email/create_new");?>">
                <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="col-12 modal-body">
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Name:
                        </label>
                        <div class="col-12">
                            <input class="form-control" name="name" data-validation="required"/>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Description:
                        </label>
                        <div class="col-12">
                            <input class="form-control" name="description"  data-validation="required"/>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Send To:
                        </label>
                        <div class="col-12">
                            <select id="send_to" name="send_to[]"  data-validation="required">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Cc to:
                        </label>
                        <div class="col-12">
                            <select id="cc_to" name="cc_to[]">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            BCC to:
                        </label>
                        <div class="col-12">
                            <select id="bcc_to" name="bcc_to[]">
                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btnSave btn-submit btn-primary ">
                        Save
                    </button>
                    <button type="button" class="btn btn-danger btnClose" data-dismiss="modal">
                        Close
                    </button>   
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Add Data
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
            <form id="edit_form" action="<?php echo site_url("email/update_email");?>">
                <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id">
                <div class="col-12 modal-body">
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Name:
                        </label>
                        <div class="col-12">
                            <input class="form-control" name="name" data-validation="required"/>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Description:
                        </label>
                        <div class="col-12">
                            <input class="form-control" name="description"  data-validation="required"/>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Send To:
                        </label>
                        <div class="col-12">
                            <select id="send_to" name="send_to[]"  data-validation="required">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            Cc to:
                        </label>
                        <div class="col-12">
                            <select id="cc_to" name="cc_to[]">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-6 col-form-label form-control-label">
                            BCC to:
                        </label>
                        <div class="col-12">
                            <select id="bcc_to" name="bcc_to[]">
                                
                            </select>
                        </div>
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
                    <button type="submit" class="btn btnSave btn-submit btn-primary ">
                        Save
                    </button>
                    <button type="button" class="btn btn-danger btnClose" data-dismiss="modal">
                        Close
                    </button>   
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var _modalEdit = $("#edit_modal");
var _currentActions = <?php echo json_encode($actions); ?>;
var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";

var _tableEmail = $("#table-email-template").DataTable({
	dom: '<"toolbar">frtlip',
	ajax: {
		url: "<?php echo base_url("email/get_email_collection"); ?>",
		type: "post",
		dataType: "json",
		data: {  _csrf_token : _csrf_hash, limit : "All" }
	}, columns: [
		{ data: "name"},
		{ data: "description"},
		{ data: "send_to"},
		{ data: "cc_to"},
		{ data: "cc_to"},
		{ data: "status"},
		{ data: null},
	], columnDefs: [{
		data: null,
		defaultContent: "",
		targets: -1,
		orderable: false,
		render: function ( data, type, row, meta ) { return itemDatatableActions(row.id); },
	},{ 
		targets: "_all", 
		defaultContent: "", 
	}],
	order: [[ 0, "desc" ]],
	initComplete: function(settings, json){
		if(typeof roleActionUpdate == "function"){ roleActionUpdate(); }
	}
});

function itemDatatableActions($id){
	if($id){
		var _actionButton ="";
		if(typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
			_actionButton += " <button type='button' class='btn btn-default m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEditItem' data-id='"+$id+"'><i class='la la-edit'></i></button>";				
		}

		if(typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
			_actionButton += " <button type='button' class='btn btn-default m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEmail' data-id='"+$id+"'><i class='fa fa-envelope'></i></button>";				
		}

        if(typeof _currentActions !== "undefined" && jQuery.inArray("archive", _currentActions) !== -1){
			_actionButton += " <button type='button' class='btn btn-default m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnArchiveItem' data-id='"+$id+"'><i class='la la-archive'></i></button>";				
		}
		return _actionButton;
	}else{ return false; }
}

$("#new_form #send_to").select2({
    placeholder: 'Select. .',
    width: '100%',
    multiple: true,
    dataType: "json",
    delay: 250,
    ajax: {
      url: "<?php echo site_url("configuration/email_lookup"); ?>",
      processResults: function (data) {
        return data;
      }
    }
}); 

$("#new_form #cc_to").select2({
    placeholder: 'Select. .',
    width: '100%',
    multiple: true,
    dataType: "json",
    delay: 250,
    ajax: {
      url: "<?php echo site_url("configuration/email_lookup"); ?>",
      processResults: function (data) {
        return data;
      }
    }
}); 

$("#new_form #bcc_to").select2({
    placeholder: 'Select. .',
    width: '100%',
    multiple: true,
    dataType: "json",
    delay: 250,
    ajax: {
      url: "<?php echo site_url("configuration/email_lookup"); ?>",
      processResults: function (data) {
        return data;
      }
    }
}); 

$("#edit_modal #send_to").select2({
    placeholder: 'Select. .',
    width: '100%',
    multiple: true,
    dataType: "json",
    delay: 250,
    ajax: {
      url: "<?php echo site_url("configuration/email_lookup"); ?>",
      processResults: function (data) {
        return data;
      }
    }
}); 

$("#edit_modal #cc_to").select2({
    placeholder: 'Select. .',
    width: '100%',
    multiple: true,
    dataType: "json",
    delay: 250,
    ajax: {
      url: "<?php echo site_url("configuration/email_lookup"); ?>",
      processResults: function (data) {
        return data;
      }
    }
}); 

$("#edit_modal #bcc_to").select2({
    placeholder: 'Select. .',
    width: '100%',
    multiple: true,
    dataType: "json",
    delay: 250,
    ajax: {
      url: "<?php echo site_url("configuration/email_lookup"); ?>",
      processResults: function (data) {
        return data;
      }
    }
}); 

$.validate({
	form : '#new_form',
	lang: 'en',
	onSuccess : function(form) {
		$.ajax({
			url: form[0].action,
			type: "POST",
			data: $("#new_form").serialize(),
			beforeSend: function(){
				$(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
			},
			success: function(data){
				if(data.response){
					toastr.success(data.toastr_msg, "Notice", 5000);
				}else{
					toastr.error(data.toastr_msg, "Notice", 5000);
				}
				$(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
				$("#new_form").modal("hide");
				//_tableCategory.ajax.reload();

			}
		});
		return false;
	},
});

$.validate({
	form : '#edit_form',
	lang: 'en',
	onSuccess : function(form) {
		$.ajax({
			url: form[0].action,
			type: "POST",
			data: $("#edit_form").serialize(),
			beforeSend: function(){
				$(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
			},
			success: function(data){
				if(data.response){
					toastr.success(data.toastr_msg, "Notice", 5000);
				}else{
					toastr.error(data.toastr_msg, "Notice", 5000);
				}
				$(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
				$("#new_form").modal("hide");
				//_tableCategory.ajax.reload();

			}
		});
		return false;
	},
});

$.validate({
	form : '#edit_email_protocol',
	lang: 'en',
	onSuccess : function(form) {
		$.ajax({
			url: form[0].action,
			type: "POST",
			data: $("#edit_email_protocol").serialize(),
			beforeSend: function(){
				$(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
			},
			success: function(data){
				if(data.response){
					toastr.success(data.toastr_msg, "Notice", 5000);
					$("#modalProtocolSettings").modal("hide");
				}else{
					toastr.error(data.toastr_msg, "Notice", 5000);
				}
				$(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");

			}
		});
		return false;
	},
});

$("#m_email_protocol").on("click",function(){
	$("#modalProtocolSettings").modal("show");
	var _dataId = $("#m_email_protocol input[name=id]").val();
	$.ajax({
		url: "<?php echo site_url("email/get_protocol_setting"); ?>",
		type: "post",
		dataType: "json",
		data: { id: _dataId },
		success: function(response){
			$("#edit_email_protocol input[name=id]").val(response.id);
			$("#edit_email_protocol input[name=server_name]").val(response.server_name);
			$("#edit_email_protocol input[name=protocol]").val(response.protocol);
			$("#edit_email_protocol input[name=smtp_host]").val(response.smtp_host);
			$("#edit_email_protocol input[name=smtp_port]").val(response.smtp_port);
			$("#edit_email_protocol input[name=smtp_crypto]").val(response.smtp_crypto);
			$("#edit_email_protocol input[name=smtp_user]").val(response.smtp_user);
			$("#edit_email_protocol input[name=smtp_pass]").val(response.smtp_pass);
		}
	});	
});

jQuery(document).on("click", ".btnEditItem", function(){
	var _dataId = $(this).data("id");
	
	$.ajax({
		url: "<?php echo site_url("email/get_email"); ?>",
		type: "post",
		dataType: "json",
		data: { id: _dataId },
		success: function(json){
			if(json.response){
				_modalEdit.modal("show");

				$("#edit_modal input[name=description]").val(json.data.description);
				$("#edit_modal input[name=name]").val(json.data.name);
				$("#edit_modal input[name=id]").val(json.data.id);

				$('#edit_modal #send_to').val([]).trigger('change');
				$('#edit_modal #cc_to').val([]).trigger('change');
				$('#edit_modal #bcc_to').val([]).trigger('change');


				for (i = 0; i < json.data.send_to.length; i++) {
					var edit_send_to = new Option(json.data.send_to[i], json.data.send_to[i], true, true);
					$('#edit_modal #send_to').append(edit_send_to).trigger('change');  
				}
				for (i = 0; i < json.data.cc_to.length; i++) {
					var edit_cc_to = new Option(json.data.cc_to[i], json.data.cc_to[i], true, true);
					$('#edit_modal #cc_to').append(edit_cc_to).trigger('change');
				}
				for (i = 0; i < json.data.bcc_to.length; i++) {
					var edit_bcc_to = new Option(json.data.bcc_to[i], json.data.bcc_to[i], true, true);
					$('#edit_modal #bcc_to').append(edit_bcc_to).trigger('change');  
				}


				if(json.data.status == 1){
					$("#active").prop("checked", true);
				}else{
					$("#disable").prop("checked", true);
				}
			}else{
				toastr.error(json.toastr_msg, "Error ", 5000);
			}
		}
		
	});	
});

$(document).on("click",".btnEmail",function(){
	var _dataId = $(this).data("id");

	$.ajax({
		url: "<?php echo site_url("email/send_email"); ?>",
		type: "post",
		dataType: "json",
		data: { id: _dataId },
		success: function(json){
			if(json.status){

			}else{
				
			}
		}

	});
})


</script>
