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
								Contractors
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
							<table class="table table-striped table-bordered" id="table-contractors" width="100%">
								<col width="87%">
								<col width="5%">
								<col width="8%">
								<thead>
									<tr>
										<th>Contractor Name</th>
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

<!-- Contractor New Modal Begin -->
<div class="modal fade" id="modal-contractor-new" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="<?php echo site_url("contractors/add_contractor");?>" method="POST" id="form-contractor-new">
			<div class="modal-header"><h5 class="modal-title">New Contractor</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Contractor Name</label>
					<input type="text" name="name" class="form-control" data-validation="required" >
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

<div class="modal fade" id="modal-contractor-update" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="<?php echo site_url("contractors/update_contractor"); ?>" method="POST" id="form-contractor-update">
			<input type="hidden" name="id" v-model="item_update.id" />
			<div class="modal-header"><h5 class="modal-title">Update Contractor</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-control-label">Contractor Name</label>
					<input type="text" name="name" class="form-control" data-validation="required" v-model="item_update.name" />
				</div>
				<div class="form-group">
					<label class="form-control-label">Contractor Status</label>
					<div class="m-radio-inline">
						<label class="m-radio">
							<input type="radio" name="status" value="1" v-model="item_update.status">Active<span></span>
						</label>
						<label class="m-radio">
							<input type="radio" name="status" value="0" v-model="item_update.status">Inactive<span></span>
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary btn-submit btnUpdate">Save</button>
				<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-contractor-delete" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="<?php echo site_url("contractors/archive_contractor"); ?>" method="POST" id="form-contractor-delete">
			<input type="hidden" name="id" value="0" v-model="item_remove.id" />
			<div class="modal-header"><h5 class="modal-title">Remove Contractor</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to remove this contractor named `<strong id="contractor_name" v-text="item_remove.name">&nbsp;</strong>`?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-submit btnDelete btn-remove_contractor">Yes</button>
				<button class="btn btn-danger" data-dismiss="modal">No</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- Contractor New Modal End -->

<script type="text/javascript">
var _currentActions = <?php echo json_encode($actions); ?>;
var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
var _global_id = 0;

var _modalContractorUpdate = $("#modal-contractor-update");
var _modalContractorRemove = $("#modal-contractor-delete");

var _dtContractors = $("#table-contractors").DataTable({
	dom: '<"toolbar">frtlip',
	serverSide: true,
	processing: true,
	ajax: {
		url: "<?php echo base_url("contractors/get_contractors_list"); ?>",
		type: "post",
		dataType: "json",
		data: {  _csrf_token : _csrf_hash }
	}, columns: [
		{ data: "name"},
		{ data: "status", className: "text-center", orderable: false },
		{ data: ""},
	], columnDefs: [{
		data: null,
		defaultContent: "",
		targets: -1,
		orderable: false,
		render: function ( data, type, row, meta ) { return itemDatatableActions(row.id); },
	}, { 
		targets: "_all", 
		defaultContent: "", 
	}],
	order: [[ 0, "desc" ]],
	initComplete: function(settings, json){
		if(typeof roleActionUpdate == "function"){ roleActionUpdate(); }
	}
});

$("div.toolbar").html('<button type="button" id="contractor-new" class="m-portlet__nav-link btn m-btn--square btn-success btnNew"  data-toggle="modal" data-target="#modal-contractor-new"><i class="fa fa-plus"></i> New</button>');

function itemDatatableActions($id){
	if($id){
		var _actionButton ="";
		if(typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
			_actionButton += " <button type='button' class='btn btn-default m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEditContractor' data-id='"+$id+"'><i class='la la-edit'></i></button>";				
		}
		if(typeof _currentActions !== "undefined" && jQuery.inArray("delete", _currentActions) !== -1){
			_actionButton += " <button type='button' class='btn btn-default m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnRemoveContractor' data-id='"+$id+"'><i class='la la-trash'></i></button>";				
		}
		return _actionButton;
	}else{ return false; }
}

// validate new form
$.validate({
	form : '#form-contractor-new',
	lang: 'en',
	onSuccess : function(form) {
		$.ajax({
			url: form[0].action,
			type: "POST",
			data: $("#form-contractor-new").serialize(),
			beforeSend: function(){
				$(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
			},
			success: function(data){
				if(data.response){
					toastr.success(data.toastr_msg, "Added Contractor", 5000);
					$("#form-contractor-new").trigger("reset");
					setTimeout(function(){ _dtContractors.ajax.reload(); }, 500);
				}else{
					toastr.error(data.toastr_msg, "Error Contractor", 5000);
				}
				$(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");

			}
		});
		return false;
	},
});

$(document).on("click", ".btn-remove_contractor", function(){
	var formDelete = $("#form-contractor-delete");
	$.ajax({
		url: formDelete.attr("action"),
		type: formDelete.attr("method"),
		dataType: "json",
		data: formDelete.serialize(),
		success: function(json){
			if(json.response){
				_modalContractorRemove.modal("hide");
				toastr.success(json.toastr_msg, "Contractor");
				setTimeout(function(){ _dtContractors.ajax.reload(); }, 500);
			}else{
				toastr.error(json.toastr_msg, "Contractor");
			}
		}
	});
});
$(document).on("click", ".btnRemoveContractor", function(){
	var id = $(this).data("id");
	$.ajax({
		url: "<?php echo site_url("contractors/get_contractors_data"); ?>",
		type: "post",
		dataType: "json",
		data: { id : id },
		success: function(json){
			if(json.response){
				vmRemove.item_remove = json.value;
				_modalContractorRemove.modal("show");
			}
		}
	});
});

$(document).on("click", ".btnEditContractor", function(){
	var id = $(this).data("id");
	$.ajax({
		url: "<?php echo site_url("contractors/get_contractors_data"); ?>",
		type: "post",
		dataType: "json",
		data: { id : id },
		success: function(json){
			if(json.response){
				vm.item_update = json.value;
				_modalContractorUpdate.modal("show");
			}
		}
	});
});

var _items = { id: 0, name: "", status: 0 };
var vmRemove = new Vue({
	el: "#form-contractor-delete",
	data: { item_remove: _items },
});

var vm = new Vue({
	el: "#form-contractor-update",
	data: {	item_update: _items },
	mounted: function(){
		$.validate({
			form : '#form-contractor-update',
			lang: 'en',
			onSuccess : function(form) {
				$.ajax({
					url: form[0].action,
					type: "POST",
					data: $("#form-contractor-update").serialize(),
					beforeSend: function(){
						$(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
					},
					success: function(data){
						if(data.response){
							toastr.success(data.toastr_msg, "Update Contractor", 5000);
							$("#form-contractor-update").trigger("reset");
							_modalContractorUpdate.modal("hide");
							setTimeout(function(){ _dtContractors.ajax.reload(); }, 500);
						}else{
							toastr.error(data.toastr_msg, "Error Contractor", 5000);
						}
						$(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");

					}
				});
				return false;
			},
		});
	}
});
</script>

