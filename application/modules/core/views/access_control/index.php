<?php $actions = $this->core_layout->getCurrentActions(); ?>
<div class="m-content">
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Access Control</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools"></div>
	</div>
	<div class="m-portlet__body">
		<div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
			<table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="table-access_control" width="100%">
				<thead>
					<tr>
						<th>Name</th>
						<th>Label</th>
						<th>Url</th>
						<th>Identifier</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="modal-access_control" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url('core/access_control/add_acl')?>" method="POST" id="form-access_control">
			<div class="modal-header">
				<h5 class="modal-title">New Access Control</h5>
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
					<label class="form-control-label">Identifier *</label>
					<input type="text" name="identifier" class="form-control inptIdentifier" autocomplete=off data-validation="required" />
				</div>
				<div class="form-group">
					<label class="form-control-label">Url</label>
					<input type="text" name="url" class="form-control inptUrl" autocomplete=off />
				</div>
				<div class="form-group">
					<label class="form-control-label">Icon</label>
					<input type="text" name="icon" class="form-control inptIcon" autocomplete=off />
				</div>
				<div class="form-group">
					<label for="">Status</label>
					<div class="m-radio-inline">
						<label class="m-radio"><input id="status1" type="radio" name="is_active" value="1" checked>Active<span></span></label>
						<label class="m-radio"><input id="status0" type="radio" name="is_active" value="0">Inactive<span></span></label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btnSave btn-submit">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-access_control-edit" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url('core/access_control/update_acl')?>" method="POST" id="form-access_control-edit">
			<input type="hidden" class="inptId" name="id" />
			<div class="modal-header">
				<h5 class="modal-title">Edit Access Control</h5>
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
					<label class="form-control-label">Identifier *</label>
					<input type="text" name="identifier" class="form-control inptIdentifier" autocomplete=off data-validation="required" />
				</div>
				<div class="form-group">
					<label class="form-control-label">Url</label>
					<input type="text" name="url" class="form-control inptUrl" autocomplete=off />
				</div>
				<div class="form-group">
					<label class="form-control-label">Icon</label>
					<input type="text" name="icon" class="form-control inptIcon" autocomplete=off />
				</div>
				<div class="form-group">
					<label for="">Status</label>
					<div class="m-radio-inline">
						<label class="m-radio"><input id="status1" type="radio" name="is_active" value="1" checked>Active<span></span></label>
						<label class="m-radio"><input id="status0" type="radio" name="is_active" value="0">Inactive<span></span></label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btnUpdate btn-submit">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-access_control-delete" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content">
			<input type="hidden" id="removeAcl" value="0" />
			<div class="modal-header">
				<h5 class="modal-title">Delete Access Control</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<i class="la la-warning"></i>  Are you sure you want to delete this access control? 
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary btnDelete btn-submit-delete">Delete</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-access_control-list" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content"></div>
	</div>
</div>
<div class="modal fade" id="modal-access_control-actions" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-m" role="document">
		<div class="modal-content"></div>
	</div>
</div>

<script type="text/javascript">
	var _currentActions = <?php echo json_encode($actions); ?>;
	var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
	var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
	
	var _tableAcl = $("#table-access_control");
	var _modalAccessControl = $("#modal-access_control");
	var _modalAccessControlEdit = $("#modal-access_control-edit");
	var _modalAccessControlDelete = $("#modal-access_control-delete");
	var _modalAccessControlList = $("#modal-access_control-list");
	var _modalAccessControlAction = $("#modal-access_control-actions");

	var _dtAccessControl = $("#table-access_control").DataTable({
		dom: '<"toolbar">frtlip',
		serverSide: true,
		processing: true,
		ajax: {
			url: "<?php echo base_url("core/access_control/get_acl_list"); ?>",
			type: "post",
			dataType: "json",
			data: {  _csrf_token : _csrf_hash }
		}, columns: [
			{ data: "name", width: "25%" },
			{ data: "label", width: "30%" },
			{ data: "url", width: "15%" },
			{ data: "identifier", width: "15%" },
			{ data: "is_active", width: "5%"},
			{ data: null, width: "10%"},
		], columnDefs: [{
			data: null,
			defaultContent: "",
			targets: -1,
			orderable: false,
			render: function ( data, type, row, meta ) { return aclDatatableActions(row.id); },
		},{
			data: "is_active",
			defaultContent: "",
			targets: 4,
			orderable: false,
			className: "dt-column-center",
			render: function ( data, type, row, meta ) { return aclDatatableStatus(row.is_active); },
		}, { 
			targets: "_all", 
			defaultContent: "", 
		}], initComplete: function(settings, json){
			if(typeof aclActionUpdate == "function"){ aclActionUpdate(); }
		}
	});
	var _htmlContent = '<button id="access_control-new" type="button" class="m-portlet__nav-link btn m-btn--square btn-success btnNew"  data-toggle="modal" data-target="#modal-access_control"><i class="fa fa-plus"></i> New </button>';
	_htmlContent += ' <button id="access_control-list" type="button" class="m-portlet__nav-link btn m-btn--square btn-info btnAssign"><i class="fa fa-list"></i> Tree View </button>';
	$("div.toolbar").html(_htmlContent);
	
	jQuery(document).on("click", "#access_control-list", function(){
		var _self = $(this);
		$.ajax({
			url: "<?php echo base_url("core/access_control/list_acl"); ?>",
			beforeSend: function(){ if(typeof _self !== "undefined"){ _self.addClass("m-btn--custom m-loader m-loader--light m-loader--right"); } },
			success: function(json){
				if(json.response){
					_modalAccessControlList.find(".modal-content").empty().append(json.html);
					var treeAcl = _modalAccessControlList.find("#tree_acl-list");
					$(treeAcl).jstree({	
						core: {
							data: json.data,
							check_callback : true
						},
						types: {
							root: { icon : "fa fa-folder" },
							child: { icon : "fa fa-file" },
						},
						plugins: [ "dnd", "types" ],
					}).on('ready.jstree', function(){
						$(this).jstree('open_all');
					});
					
					jQuery(_modalAccessControlList).modal("show");
				}
				if(typeof _self !== "undefined"){ _self.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
			}
		});
	});
	
	jQuery(document).on("click", "#modal-access_control-list .btn-submit-list", function(){
		var _self = $(this);
		var treeAcl = _modalAccessControlList.find("#tree_acl-list");
		if(typeof treeAcl !== "undefined"){
			var jsonData = jQuery(treeAcl).jstree(true).get_json("#", {flat: true});
			var _string = JSON.stringify(jsonData);
			
			$.ajax({
				url: "<?php echo base_url("core/access_control/get_json_acl"); ?>",
				type: "post",
				data: { nodes: _string },
				beforeSend: function(){ if(typeof _self !== "undefined"){ _self.addClass("m-btn--custom m-loader m-loader--light m-loader--right"); } },
				success: function(json){
					if(json.response){
						toastr.success(json.toastr_msg, "Access Control List", 5000);
						$(_modalAccessControlList).modal("hide");
						setTimeout(function(){ window.location.reload(); }, 1000);
					}else{
						toastr.error(json.toastr_msg, "Error Access Control List", 5000);
					}
					if(typeof _self !== "undefined"){ _self.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
				}
			});
		}
	});
	
	jQuery(document).on("click", "#access_control-new", function(){
			var inputs = _modalAccessControl.find("input[type=text]");
			if(typeof inputs !== "undefined"){
				jQuery.each(inputs, function(index, object){
					if(index == 0){ setTimeout(function(){ jQuery(object).focus(); }, 500); }
					jQuery(object).val("");
				});
			}
	});
	
	$.validate({
		form : '#form-access_control',
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
						_dtAccessControl.draw();
						toastr.success(data.toastr_msg, "Added Access Control", 5000);
						$("#modal-access_control").modal("hide");
						setTimeout(function(){ window.location.reload(); }, 1000);
					}else{
						toastr.error(data.toastr_msg, "Error Access Control", 5000);
					}
	    			if(typeof _btnSubmit !== "undefined"){ _btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
	    		}
	    	});
			return false;
	    }
	});
	
	$.validate({
		form : '#form-access_control-edit',
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
						_dtAccessControl.draw();
						toastr.success(data.toastr_msg, "Update Access Control", 5000);
						$("#modal-access_control-edit").modal("hide");
						setTimeout(function(){ window.location.reload(); }, 1000);
					}else{ toastr.error(data.toastr_msg, "Error Access Control", 5000); }
					if(typeof _btnSubmit !== "undefined"){ _btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
	    		}
	    	});
			return false;
	    }
	});
	
	function aclDatatableActions($id){
		if($id){
			var _actionButton ="";
			if(typeof _currentActions !== "undefined" && jQuery.inArray("assign", _currentActions) !== -1){
				_actionButton += "<button type='button' class='btn btn-default m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill btn-sm btnAssign btnAclAction' data-id='"+$id+"'><i class='la la-key'></i></button>";				
			}
			if(typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
				_actionButton += " <button type='button' class='btn btn-default m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-sm btnEdit btnEditAcl' data-id='"+$id+"'><i class='la la-edit'></i></button>";				
			}
			if(typeof _currentActions !== "undefined" && jQuery.inArray("delete", _currentActions) !== -1){
				_actionButton += " <button type='button' class='btn btn-default m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btn-sm btnDelete btnRemoveAcl' data-id='"+$id+"'><i class='la la-trash'></i></button>";				
			}
			
			if(!_actionButton){ _actionButton = "---"; }
			return _actionButton;
		}else{ return false; }
	}
	function aclDatatableStatus($isActive){
		var _html = "";
		if($isActive == 1){ _html = "<span class='btn btn-info m-btn m-btn--icon m-btn--icon-only btn-sm'><i class='la la-check'></i></span>"; }
		else{ _html = "<span class='btn btn-danger m-btn m-btn--icon m-btn--icon-only btn-sm'><i class='la la-remove'></i></span>"; }
		return _html;
	}
	function aclActionUpdate(){
		var _editAcl = _tableAcl.find(".btnEditAcl");
		if(typeof _editAcl !== "undefined"){
			jQuery(document).on("click", ".btnEditAcl", function(){
				var _self = $(this);
				var dataId = _self.data("id");
				if(typeof dataId !== "undefined"){
					$.ajax({
						url: "<?php echo base_url("core/access_control/get_acl_data"); ?>",
						type: "post",
						data: { id: dataId },
						success: function(data){
							if(data.response){
								_modalAccessControlEdit.find("input.inptId").val(data.value["id"]);
								_modalAccessControlEdit.find("input.inptName").val(data.value["name"]);
								_modalAccessControlEdit.find("input.inptLabel").val(data.value["label"]);
								_modalAccessControlEdit.find("input.inptUrl").val(data.value["url"]);
								_modalAccessControlEdit.find("input.inptIdentifier").val(data.value["identifier"]);
								_modalAccessControlEdit.find("input.inptIcon").val(data.value["icon"]);
								
								var _checkedActive = _modalAccessControlEdit.find("input#status1");
								var _checkedInactive = _modalAccessControlEdit.find("input#status0");
								if(typeof _checkedActive !== "undefined" && typeof _checkedInactive !== "undefined"){
									if(data.value["is_active"] == 1){
										_checkedActive.prop("checked", true);
										_checkedInactive.prop("checked", false);
									}else{
										_checkedActive.prop("checked", false);
										_checkedInactive.prop("checked", true);
									}
								}
								$(_modalAccessControlEdit).modal("show");
							}
						}
					});
				}
			});
		}
	}
	jQuery(document).on("click", "#table-access_control .btnRemoveAcl", function(){
		var _self = $(this);
		var dataId = _self.data("id");
		var _inptAcl = _modalAccessControlDelete.find("#removeAcl");
		if(typeof _inptAcl !== "undefined"){
			_inptAcl.val(dataId);
			$(_modalAccessControlDelete).modal("show");
		}
	});
	jQuery(document).on("click", ".btn-submit-delete", function(){
		var _btnSubmit = jQuery(this);
		var _dataId = jQuery(this).parent(".modal-footer").parent(".modal-content").children("input#removeAcl").val();
		if(typeof _dataId !== "undefined"){
			jQuery.ajax({
				url: "<?php echo base_url("core/access_control/remove_acl"); ?>",
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
						_dtAccessControl.draw();
						toastr.success(json.toastr_msg, "Remove Access Control", 5000);
						$(_modalAccessControlDelete).modal("hide");
						setTimeout(function(){ window.location.reload(); }, 1000);
					}else{ toastr.error(json.toastr_msg, "Error Access Control", 5000); }
					if(typeof _btnSubmit !== "undefined"){
						if(_btnSubmit.hasClass("m-btn--custom m-loader m-loader--light m-loader--right")){
							_btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right");							
						}
					}
				}
			});			
		}
	});
	jQuery(document).on("click", "#table-access_control .btnAclAction", function(){
		var _self = $(this);
		var dataId = _self.data("id");
		$.ajax({
			url: "<?php echo base_url("core/access_control/get_acl_actions"); ?>",
			type: "POST",
			data: { id: dataId },
			beforeSend: function(){
				if(typeof _self !== "undefined"){
					if(!_self.hasClass("m-btn--custom m-loader m-loader--light m-loader--right")){
						_self.addClass("m-btn--custom m-loader m-loader--light m-loader--right");
					}
				}
			},
			success: function(json){
				if(json.response){
					var _modalContent = _modalAccessControlAction.find(".modal-content");
					if(typeof _modalContent !== "undefined"){
						_modalContent.empty().append(json.html);
						var treeAclAction = _modalAccessControlAction.find("#tree_acl-action");
						$(treeAclAction).jstree({	
							core: {
								data: json.data,
								check_callback : true
							},
							types: {
								root: { icon : "la la-pencil-square" },
							},
							plugins: [ "checkbox", "wholerow", "types" ],
						}).on('ready.jstree', function(){
							console.log(json.acl_id);
							var _current = $(this);
							var _treeItem = _current.find("li[role=treeitem]");
							if(typeof _treeItem !== "undefined"){
								_treeItem.each(function(i, v){
									var _id = $(v).attr("id");
									_id = parseInt(_id);
									if(jQuery.inArray( _id, json.acl_id ) !== -1){
										_current.jstree("select_node", this)
									}
								});
							}
						});
					
						$(_modalAccessControlAction).modal("show");
					}
				}
				if(typeof _self !== "undefined"){
						if(_self.hasClass("m-btn--custom m-loader m-loader--light m-loader--right")){
							_self.removeClass("m-btn--custom m-loader m-loader--light m-loader--right");							
						}
					}
			}
		});
	});
	
	jQuery(document).on("click", "#modal-access_control-actions .btn-submit-list", function(){
		var _self = $(this);
		var dataId = $(this).data("id");
		var _jsTree = _modalAccessControlAction.find("#tree_acl-action");
		if(typeof _jsTree !== "undefined"){
			var _jsonData = $(_jsTree).jstree(true).get_json("#", {flat: true});
			var _string = JSON.stringify(_jsonData);
			
			$.ajax({
				url: "<?php echo base_url("core/access_control/set_acl_json_actions"); ?>",
				type: "POST",
				data: { nodes: _string, id: dataId },
				beforeSend: function(){
					if(typeof _self !== "undefined"){
						if(!_self.hasClass("m-btn--custom m-loader m-loader--light m-loader--right")){
							_self.addClass("m-btn--custom m-loader m-loader--light m-loader--right");
						}
					}
				},
				success: function(json){
					if(json.response){
						toastr.success(json.toastr_msg, "Access Control - Actions", 5000);
						$(_modalAccessControlAction).modal("hide");
					}else{
						toastr.error(json.toastr_msg, "Error Access Control - Actions", 5000);
					}
					if(typeof _self !== "undefined"){ _self.removeClass("m-btn--custom m-loader m-loader--light m-loader--right"); }
				}
			});
		}
	});
</script>