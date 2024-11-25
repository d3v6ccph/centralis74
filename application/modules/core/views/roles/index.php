<?php $actions = $this->core_layout->getCurrentActions(); ?>
<div class="m-content">
	<div class="row">
		<div class="col-lg-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--mobile">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Manage Roles
							</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item">
								
							</li>
						</ul>
					</div>
				</div>
				<div class="m-portlet__body">
					<!--begin: Datatable -->
						<div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
							<table class="table table-striped table-bordered" id="table-roles" width="100%">
								<thead>
									<tr>
										<th>Name</th>
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

<!--begin::Modal-->
<div class="modal fade" id="modal-user_role-new" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url('core/roles/add_role'); ?>" method="POST" id="form-roles">
			<div class="modal-header"><h5 class="modal-title">New Role</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Name</label>
					<input type="text" name="name" class="form-control inptName" data-validation="required">
				</div>
				<div class="form-group">
					<label class="form-control-label">Description</label>
					<textarea class="form-control txtDescription" name="description" data-validation="required"></textarea>
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
				<button type="submit" class="btn btn-primary btn-submit btnSave">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-user_role-edit" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url('core/roles/update_role')?>" method="POST" id="form-roles-edit">
			<input type="hidden" class="inptId" name="id" />
			<div class="modal-header">
				<h5 class="modal-title" >Update Role</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Name</label>
					<input type="text" class="form-control inptName" readonly="readonly">
				</div>
				<div class="form-group">
					<label class="form-control-label">Description</label>
					<textarea class="form-control txtDescription" name="description" data-validation="required"></textarea>
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
<div class="modal fade" id="modal-user_role-delete" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<input type="hidden" id="removeRole" value="0" />
			<div class="modal-header">
				<h5 class="modal-title">Delete User Role</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body"><i class="la la-warning"></i>  Are you sure you want to delete this user role? </div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-danger btn-submit-delete btnDelete">Delete</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-user_role-assign" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content"></div>
	</div>
</div>
<div class="modal fade" id="modal-user_role-privilege" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content"></div>
	</div>
</div>
<!--end::Modal-->

<script type="text/javascript">
	(function ($, undefined) {
		"use strict";
		$.jstree.plugins.noclose = function () {
			this.close_node = $.noop;
		};
	})(jQuery);
	var _currentActions = <?php echo json_encode($actions); ?>;
	var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
	var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
	var _tableRole = $("#table-roles");
	var _modalUserRole = $("#modal-user_role-new");
	var _modalUserRoleEdit = $("#modal-user_role-edit");
	var _modalUserRoleDelete = $("#modal-user_role-delete");
	var _modalUserRoleAssign = $("#modal-user_role-assign");
	var _modalUserRolePrivilege = $("#modal-user_role-privilege");
	
	var _dtUserRole = $("#table-roles").DataTable({
		dom: '<"toolbar">frtlip',
		serverSide: true,
		processing: true,
		ajax: {
			url: "<?php echo base_url("core/roles/get_roles_list"); ?>",
			type: "post",
			dataType: "json",
			data: {  _csrf_token : _csrf_hash }
		}, columns: [
			{ data: "name", width: "25%" },
			{ data: "description", width: "50%" },
			{ data: "status", width: "10%" },
			{ data: null, width: "15%"},
		], columnDefs: [{
			data: null,
			defaultContent: "",
			targets: 2,
			orderable: false,
			className: "dt-column-center",
			render: function ( data, type, row, meta ) { return roleDatatableStatus(row.status); },
		},{
			data: null,
			defaultContent: "",
			targets: -1,
			orderable: false,
			render: function ( data, type, row, meta ) { return roleDatatableActions(row.id); },
		}, { 
			targets: "_all", 
			defaultContent: "", 
		}],
		initComplete: function(settings, json){
			if(typeof roleActionUpdate == "function"){ roleActionUpdate(); }
		}
	});
	
	jQuery(document).on("click", "#user_role-new", function(){
			var inputs = _modalUserRole.find("input[type=text]");
			if(typeof inputs !== "undefined"){
				jQuery.each(inputs, function(index, object){
					if(index == 0){ setTimeout(function(){ jQuery(object).focus(); }, 500); }
					jQuery(object).val("");
				});
			}
	});
	
	function roleDatatableActions($id){
		if($id){
			var _actionButton ="";
			if(typeof _currentActions !== "undefined" && jQuery.inArray("assign", _currentActions) !== -1){
				_actionButton += "<button type='button' class='btn btn-default m-btn btn-sm m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill btnAssignRole' data-id='"+$id+"'><i class='la la-tasks'></i></button>";
				_actionButton += " <button type='button' class='btn btn-default m-btn btn-sm  m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill btnRolePrivilege' data-id='"+$id+"'><i class='la la-key'></i></button>";
			}
			if(typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
				_actionButton += " <button type='button' class='btn btn-default m-btn btn-sm  m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEditRole' data-id='"+$id+"'><i class='la la-edit'></i></button>";				
			}
			if(typeof _currentActions !== "undefined" && jQuery.inArray("delete", _currentActions) !== -1){
				_actionButton += " <button type='button' class='btn btn-default m-btn btn-sm m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnRemoveRole' data-id='"+$id+"'><i class='la la-trash'></i></button>";				
			}
			return _actionButton;
		}else{ return false; }
	}
	function roleDatatableStatus($isActive){
		var _html = "";
		if($isActive == 1){ _html = "<span class='btn btn-info m-btn m-btn--icon m-btn--icon-only btn-sm'><i class='la la-check'></i></span>"; }
		else{ _html = "<span class='btn btn-danger m-btn m-btn--icon m-btn--icon-only btn-sm'><i class='la la-remove'></i></span>"; }
		return _html;
	}
	$("div.toolbar").html('<button type="button" id="user_role-new" class="m-portlet__nav-link btn m-btn--square btn-success btnNew"  data-toggle="modal" data-target="#modal-user_role-new"><i class="fa fa-plus"></i> New </button>');

	$.validate({
		form : '#form-roles',
	    lang: 'en',
	    onSuccess : function(form) {
	    	$.ajax({
	    		url: form[0].action,
	    		type: "POST",
	    		data: $("#form-roles").serialize(),
	    		beforeSend: function(){
	    			$(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
	    		},
	    		success: function(data){
					if(data.response){
						_dtUserRole.draw();
						toastr.success(data.toastr_msg, "Added User Role", 5000);
						$(_modalUserRole).modal("hide");
						setTimeout(function(){ window.location.reload(); }, 1000);
					}else{
						toastr.error(data.toastr_msg, "Error User Role", 5000);
					}
	    			$(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
	    		}
	    	});
	    	return false;
	    },
	});
	
	$.validate({
		form : '#form-roles-edit',
	    lang: 'en',
	    onSuccess : function(form) {
	    	$.ajax({
	    		url: form[0].action,
	    		type: "POST",
	    		data: $("#form-roles-edit").serialize(),
	    		beforeSend: function(){
	    			$(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
	    		},
	    		success: function(data){
					if(data.response){
						_dtUserRole.draw();
						toastr.success(data.toastr_msg, "Update User Role", 5000);
						$(_modalUserRoleEdit).modal("hide");
						setTimeout(function(){ window.location.reload(); }, 1000);
					}else{
						toastr.error(data.toastr_msg, "Error User Role", 5000);
					}
	    			$(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
	    		}
	    	});
	    	return false;
	    },
	});
	
	function roleActionUpdate(){
		var _editRole = _tableRole.find(".btnEditRole");
		if(typeof _editRole !== "undefined"){
			jQuery(document).on("click", ".btnEditRole", function(){
				var _self = $(this);
				var dataId = _self.data("id");
				if(typeof dataId !== "undefined"){
					$.ajax({
						url: "<?php echo base_url("core/roles/get_role_data"); ?>",
						type: "post",
						data: { id: dataId },
						success: function(data){
							if(data.response){
								_modalUserRoleEdit.find("input.inptId").val(data.value["id"]);
								_modalUserRoleEdit.find("input.inptName").val(data.value["name"]);
								_modalUserRoleEdit.find("textarea.txtDescription").val(data.value["description"]);
								
								var _checkedActive = _modalUserRoleEdit.find("input#status1");
								var _checkedInactive = _modalUserRoleEdit.find("input#status0");
								if(typeof _checkedActive !== "undefined" && typeof _checkedInactive !== "undefined"){
									if(data.value["status"] == 1){
										_checkedActive.prop("checked", true);
										_checkedInactive.prop("checked", false);
									}else{
										_checkedActive.prop("checked", false);
										_checkedInactive.prop("checked", true);
									}
								}
								$(_modalUserRoleEdit).modal("show");
							}
						}
					});
				}
			});
		}
	}
	
	jQuery(document).on("click", "#table-roles .btnRemoveRole", function(){
		var _self = $(this);
		var dataId = _self.data("id");
		var _inptAcl = _modalUserRoleDelete.find("#removeRole");
		if(typeof _inptAcl !== "undefined"){
			_inptAcl.val(dataId);
			$(_modalUserRoleDelete).modal("show");
		}
	});
	jQuery(document).on("click", ".btn-submit-delete", function(){
		var _btnSubmit = jQuery(this);
		var _dataId = jQuery(this).parent(".modal-footer").parent(".modal-content").children("input#removeRole").val();
		if(typeof _dataId !== "undefined"){
			jQuery.ajax({
				url: "<?php echo base_url("core/roles/remove_role"); ?>",
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
						_dtUserRole.draw();
						toastr.success(json.toastr_msg, "Remove User Role", 5000);
						$(_modalUserRoleDelete).modal("hide");
						setTimeout(function(){ window.location.reload(); }, 1000);
					}else{ toastr.error(json.toastr_msg, "Error User Role", 5000); }
					if(typeof _btnSubmit !== "undefined"){
						if(_btnSubmit.hasClass("m-btn--custom m-loader m-loader--light m-loader--right")){
							_btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right");							
						}
					}
				}
			});			
		}
	});
	jQuery(document).on("click", "#table-roles .btnAssignRole", function(){
		var _self = jQuery(this);
		var dataId = _self.data("id");
		jQuery.ajax({
			url: "<?php echo base_url("core/roles/get_assigned_role"); ?>",
			type: "post",
			data: { id: dataId },
			success: function(json){
				if(json.response){
					_modalUserRoleAssign.find(".modal-content").empty().append(json.html);
					var treeRole = _modalUserRoleAssign.find("#tree_role-list");
					$(treeRole).jstree({	
						core: {
							data: json.data,
							check_callback : true,
							themes: { icons: false },
						},
						checkbox: {
							cascade: "",
							three_state: false,
						},
						plugins: ["checkbox", "wholerow"],
					}).on('ready.jstree', function(){
						$(this).jstree('open_all');
						
						var _treeItem = $(this).find("li[role=treeitem]");
						if(typeof _treeItem !== "undefined"){
							_treeItem.each(function(i, v){
								var _id = $(v).attr("id");
								_id = parseInt(_id);
								if(jQuery.inArray( _id, json.role_id ) !== -1){	$(this).jstree("select_node", this) }
							});
						}
						var jsTreeCheckbox = $(this).find("i.jstree-icon.jstree-checkbox");
						var jsTreeOcl = $(this).find("i.jstree-icon.jstree-ocl");
						if(typeof jsTreeOcl !== "undefined" && jsTreeOcl.length > 0){ jsTreeOcl.remove(); }
						if(typeof jsTreeCheckbox !== "undefined" && jsTreeCheckbox.length > 0){ jsTreeCheckbox.css("margin-right", "15px"); }
					});
					
					$(_modalUserRoleAssign).modal("show");
				}
			}
		});
	});
	
	jQuery(document).on("click", "#modal-user_role-assign .btn-submit-save", function(){
		var _self = $(this);
		var _id = _self.data("id");
		var treeRole = _modalUserRoleAssign.find("#tree_role-list");
		if(typeof treeRole !== "undefined"){
			var jsonData = jQuery(treeRole).jstree(true).get_json("#", {flat: true});
			var _string = JSON.stringify(jsonData);
			
			$.ajax({
				url: "<?php echo base_url("core/roles/get_json_role"); ?>",
				type: "post",
				data: { nodes: _string, id: _id },
				beforeSend: function(){ if(typeof _self !== "undefined"){ _self.addClass("m-btn--custom m-loader m-loader--light m-loader--right"); } },
				success: function(json){
					if(json.response){
						toastr.success(json.toastr_msg, "Assigned User Role", 5000);
						$(_modalUserRoleAssign).modal("hide");
					}else{
						toastr.error(json.toastr_msg, "Error Assigned User Role", 5000);
					}
					if(typeof _self !== "undefined"){ _self.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
				}
			});
		}
	});
	
	jQuery(document).on("click", "#table-roles .btnRolePrivilege", function(){
		var _self = $(this);
		var _dataId = _self.data("id");
		
		$.ajax({
			url: "<?php echo base_url("core/roles/get_role_privilege_data"); ?>",
			type: "POST",
			data: { id: _dataId },
			beforeSend: function(){
				if(typeof _self !== "undefined"){
					_self.addClass("m-btn--custom m-loader m-loader--light m-loader--right");
				}
			},
			success: function(json){
				if(json.response){
					console.log(json.response);
					console.log(json.data);
					_modalUserRolePrivilege.find(".modal-content").empty().append(json.html);
					var treeRole = _modalUserRolePrivilege.find("#tree_role-actions");
					$(treeRole).jstree({
						core: {
							data: json.data,
							check_callback : true,
							themes: { icons: false },
						},
						checkbox: {
							cascade: "",
							three_state: false,
						},
						plugins: ["checkbox", "wholerow"],
					}).on('ready.jstree', function(){
						$(this).jstree('open_all');
						
						var _treeItem = $(this).find("li[role=treeitem]");
						if(typeof _treeItem !== "undefined"){
							_treeItem.each(function(i, v){
								var _id = $(v).attr("id");
								if(jQuery.inArray( _id, json.role_id ) !== -1){	$(this).jstree("select_node", this) }
							});
						}
						
						var jsTreeCheckbox = $(this).find("i.jstree-icon.jstree-checkbox");
						var jsTreeOcl = $(this).find("i.jstree-icon.jstree-ocl");
						if(typeof jsTreeOcl !== "undefined" && jsTreeOcl.length > 0){ jsTreeOcl.remove(); }
						if(typeof jsTreeCheckbox !== "undefined" && jsTreeCheckbox.length > 0){ jsTreeCheckbox.css("margin-right", "15px"); }
						
						var jsTreeParent = $(this).find(".no_checkbox");
						if(typeof jsTreeParent !== "undefined" && jsTreeParent.length > 0){
							jsTreeParent.find(".jstree-checkbox").remove();							
						}
					});
					
					$(_modalUserRolePrivilege).modal("show");
				}
				if(typeof _self !== "undefined"){ _self.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
			}
		});
	});
	
	jQuery(document).on("click", "#modal-user_role-privilege .btn-submit-save", function(){
		var _self = $(this);
		var _id = _self.data("id");
		
		var treeRoleActions = _modalUserRolePrivilege.find("#tree_role-actions");
		if(typeof treeRoleActions !== "undefined"){
			var jsonData = jQuery(treeRoleActions).jstree(true).get_json("#", {flat: true});
			var _string = JSON.stringify(jsonData);
			
			$.ajax({
				url: "<?php echo base_url("core/roles/get_json_role_actions"); ?>",
				type: "post",
				data: { nodes: _string, id: _id },
				beforeSend: function(){ if(typeof _self !== "undefined"){ _self.addClass("m-btn--custom m-loader m-loader--light m-loader--right"); } },
				success: function(json){
					if(json.response){
						toastr.success(json.toastr_msg, "Assigned User Role", 5000);
						$(_modalUserRoleAssign).modal("hide");
					}else{
						toastr.error(json.toastr_msg, "Error Assigned User Role", 5000);
					}
					if(typeof _self !== "undefined"){ _self.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
				}
			});
		}
	});
</script>