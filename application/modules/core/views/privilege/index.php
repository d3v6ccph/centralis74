<?php $actions = $this->core_layout->getCurrentActions(); ?>
<div class="m-content">
	<div class="row">
		<div class="col-lg-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--mobile">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">Manage Privilege</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item"></li>
						</ul>
					</div>
				</div>
				<div class="m-portlet__body">
					<!--begin: Datatable -->
						<div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
							<table class="table table-striped table-bordered" id="table-privilege" width="100%">
								<thead>
									<tr>
										<th>Name</th>
										<th>Label</th>
										<th>Description</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>	
								</tbody>
							</table>
						</div>
					<!--end: Datatable -->
				</div>
			</div>
			<!--end::Portlet-->
		</div>
	</div>
</div>

<div class="modal fade" id="modal-privilege-new" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url('core/privilege/add_privilege')?>" method="POST" id="form-privilege">
			<div class="modal-header">
				<h5 class="modal-title">New Privilege</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Name *</label>
					<input type="text" name="name" class="form-control inptName" autocomplete=off data-validation="required" />
				</div>
				<div class="form-group">
					<label class="form-control-label">Label *</label>
					<input type="text" name="label" class="form-control inptLabel" autocomplete=off data-validation="required" />
				</div>
				<div class="form-group">
					<label class="form-control-label">Description *</label>
					<input type="text" name="description" class="form-control inptDescription" autocomplete=off data-validation="required" />
				</div>
				<div class="form-group">
					<label for="">Status</label>
					<div class="m-radio-inline">
						<label class="m-radio"><input id="status1" type="radio" name="status" value="1" checked>Active<span></span></label>
						<label class="m-radio"><input id="status0" type="radio" name="status" value="0">Inactive<span></span></label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btn-submit btnSave">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-privilege-edit" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url('core/privilege/update_privilege')?>" method="POST" id="form-privilege-edit">
			<input type="hidden" class="inptId" name="id" />
			<div class="modal-header">
				<h5 class="modal-title">Edit Privilege</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Name</label>
					<input type="text" class="form-control inptName" readonly="readonly" />
				</div>
				<div class="form-group">
					<label class="form-control-label">Label *</label>
					<input type="text" name="label" class="form-control inptLabel" autocomplete=off data-validation="required" />
				</div>
				<div class="form-group">
					<label class="form-control-label">Description *</label>
					<input type="text" name="description" class="form-control inptDescription" autocomplete=off data-validation="required" />
				</div>
				<div class="form-group">
					<label for="">Status</label>
					<div class="m-radio-inline">
						<label class="m-radio"><input id="status1" type="radio" name="status" value="1" checked>Active<span></span></label>
						<label class="m-radio"><input id="status0" type="radio" name="status" value="0">Inactive<span></span></label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btn-submit btnUpdate">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-privilege-delete" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<input type="hidden" id="removePrivilege" value="0" />
			<div class="modal-header">
				<h5 class="modal-title">Delete Privilege</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<i class="la la-warning"></i>  Are you sure you want to delete this privilege? 
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-danger btn-submit-delete btnDelete">Delete</button>
			</div>
		</div>
	</div>
</div>
<script>
var _currentActions = <?php echo json_encode($actions); ?>;
var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";

var _tablePrivilege = $("#table-privilege");
var _modalPrivigeEdit = $("#modal-privilege-edit");
var _modalPrivigeDelete = $("#modal-privilege-delete");

var _dtPrivilege = $("#table-privilege").DataTable({
		dom: '<"toolbar">frtlip',
		serverSide: true,
		processing: true,
		ajax: {
			url: "<?php echo base_url("core/privilege/get_privilege_list"); ?>",
			type: "post",
			dataType: "json",
			data: {  _csrf_token : _csrf_hash }
		}, columns: [
			{ data: "name", width: "20%" },
			{ data: "label", width: "30%" },
			{ data: "description", width: "30%" },
			{ data: "status", width: "10%"},
			{ data: null, width: "10%"},
		], columnDefs: [{
			data: null,
			defaultContent: "",
			targets: -1,
			orderable: false,
			render: function ( data, type, row, meta ) { return privDatatableActions(row.id); },
		},{
			data: "status",
			defaultContent: "",
			targets: 3,
			orderable: false,
			className: "dt-column-center",
			render: function ( data, type, row, meta ) { return privDatatableStatus(row.status); },
		}, { 
			targets: "_all", 
			defaultContent: "", 
		}], initComplete: function(settings, json){
			if(typeof privActionUpdate == "function"){ privActionUpdate(); }
		}
	});

var _htmlContent = '<button id="privilege-new" type="button" class="m-portlet__nav-link btn m-btn--square btn-success btnNew"  data-toggle="modal" data-target="#modal-privilege-new"><i class="fa fa-plus"></i> New </button>';
$("div.toolbar").html(_htmlContent);

$.validate({
	form : '#form-privilege',
	lang: 'en',
	onSuccess : function(form) {
		var _url = form[0].action;
		var _data = jQuery(form[0]).serialize();
		var _btnSubmit = $(form[0]).find(".btn-submit");
		
		$.ajax({
			url: _url,
			type: "POST",
			data: _data,
			beforeSend: function(){
				if(typeof _btnSubmit !== "undefined"){ _btnSubmit.addClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
			},
			success: function(data){
				if(data.response){
					_dtPrivilege.draw();
					toastr.success(data.toastr_msg, "Added Privilege", 5000);
					$("#modal-privilege-new").modal("hide");
					setTimeout(function(){ window.location.reload(); }, 1000);
				}else{
					toastr.error(data.toastr_msg, "Error Privilege", 5000);
				}
				if(typeof _btnSubmit !== "undefined"){ _btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
			}
		});
		return false;
	}
});

$.validate({
	form : '#form-privilege-edit',
	lang: 'en',
	onSuccess : function(form) {
		var _url = form[0].action;
		var _data = jQuery(form[0]).serialize();
		var _btnSubmit = $(form[0]).find(".btn-submit");
		
		$.ajax({
			url: _url,
			type: "POST",
			data: _data,
			beforeSend: function(){
				if(typeof _btnSubmit !== "undefined"){ _btnSubmit.addClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
			},
			success: function(data){
				if(data.response){
					_dtPrivilege.draw();
					toastr.success(data.toastr_msg, "Update Privilege", 5000);
					$("#modal-privilege-edit").modal("hide");
					setTimeout(function(){ window.location.reload(); }, 1000);
				}else{ toastr.error(data.toastr_msg, "Error Privilege", 5000); }
				if(typeof _btnSubmit !== "undefined"){ _btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
			}
		});
		return false;
	}
});

function privDatatableActions($id){
	if($id){
		var _actionButton ="";
		if(typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
			_actionButton += "<button type='button' class='btn btn-default m-btn btn-sm m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEditPriv' data-id='"+$id+"'><i class='la la-edit'></i></button>";			
		}
		if(typeof _currentActions !== "undefined" && jQuery.inArray("delete", _currentActions) !== -1){
			_actionButton += " <button type='button' class='btn btn-default m-btn btn-sm m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnRemovePriv' data-id='"+$id+"'><i class='la la-trash'></i></button>";			
		}
		return _actionButton;
	}else{ return false; }
}
function privDatatableStatus($isActive){
	var _html = "";
	if($isActive == 1){ _html = "<span class='btn btn-info m-btn m-btn--icon m-btn--icon-only btn-sm'><i class='la la-check'></i></span>"; }
	else{ _html = "<span class='btn btn-danger m-btn m-btn--icon m-btn--icon-only btn-sm'><i class='la la-remove'></i></span>"; }
	return _html;
}

function privActionUpdate(){
	var _editPrivilege = _tablePrivilege.find(".btnEditPriv");
	if(typeof _editPrivilege !== "undefined"){
		jQuery(document).on("click", ".btnEditPriv", function(){
			var _self = $(this);
			var dataId = _self.data("id");
			if(typeof dataId !== "undefined"){
				$.ajax({
					url: "<?php echo base_url("core/privilege/get_privilege_data"); ?>",
					type: "post",
					data: { id: dataId },
					success: function(data){
						if(data.response){
							_modalPrivigeEdit.find("input.inptId").val(data.value["id"]);
							_modalPrivigeEdit.find("input.inptName").val(data.value["name"]);
							_modalPrivigeEdit.find("input.inptLabel").val(data.value["label"]);
							_modalPrivigeEdit.find("input.inptDescription").val(data.value["description"]);
							
							var _checkedActive = _modalPrivigeEdit.find("input#status1");
							var _checkedInactive = _modalPrivigeEdit.find("input#status0");
							if(typeof _checkedActive !== "undefined" && typeof _checkedInactive !== "undefined"){
								if(data.value["status"] == 1){
									_checkedActive.prop("checked", true);
									_checkedInactive.prop("checked", false);
								}else{
									_checkedActive.prop("checked", false);
									_checkedInactive.prop("checked", true);
								}
							}
							$(_modalPrivigeEdit).modal("show");
						}
					}
				});
			}
		});
	}
}

jQuery(document).on("click", "#table-privilege .btnRemovePriv", function(){
	var _self = $(this);
	var dataId = _self.data("id");
	var _inptPriv = _modalPrivigeDelete.find("#removePrivilege");
	if(typeof _inptPriv !== "undefined"){
		_inptPriv.val(dataId);
		$(_modalPrivigeDelete).modal("show");
	}
});
jQuery(document).on("click", ".btn-submit-delete", function(){
	var _btnSubmit = jQuery(this);
	var _dataId = jQuery(this).parent(".modal-footer").parent(".modal-content").children("input#removePrivilege").val();
	if(typeof _dataId !== "undefined"){
		jQuery.ajax({
			url: "<?php echo base_url("core/privilege/remove_privilege"); ?>",
			type: "post",
			data: { id: _dataId },
			beforeSend: function(){
				if(typeof _btnSubmit !== "undefined"){
					if(!_btnSubmit.hasClass("m-btn--custom m-loader m-loader--light m-loader--right")){
						_btnSubmit.addClass("m-btn--custom m-loader m-loader--light m-loader--right");
					}
				}
			},
			success: function(json){
				if(json.response){
					_dtPrivilege.draw();
					toastr.success(json.toastr_msg, "Remove Privilege", 5000);
					$(_modalPrivigeDelete).modal("hide");
					setTimeout(function(){ window.location.reload(); }, 1000);
				}else{ toastr.error(json.toastr_msg, "Error Privilege", 5000); }
				if(typeof _btnSubmit !== "undefined"){
					if(_btnSubmit.hasClass("m-btn--custom m-loader m-loader--light m-loader--right")){
						_btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right");							
					}
				}
			}
		});			
	}
});
</script>