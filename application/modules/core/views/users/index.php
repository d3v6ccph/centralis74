<?php $actions = $this->core_layout->getCurrentActions(); ?>
<div class="m-content">
	<div class="row">
		<div class="col-lg-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--mobile">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">Manage Users</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item"></li>
						</ul>
					</div>
				</div>
				<div class="m-portlet__body">
					<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                        <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">&nbsp;</div>
							<div class="col-xl-4 order-1 order-xl-2 m--align-right">
                                <div class="m-input-icon m-input-icon--left" style="border: 1px solid #c3c3c3;">
                                    <input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span>
                                            <i class="la la-search"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
						</div>
					<!--begin: Datatable -->
						<div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
							<table class="table table-striped table-bordered" id="table-users" style="width:100%;">
								<thead>
									<tr>
										<th>Biometric No</th>
										<th>Lastname</th>
										<th>Firstname</th>
										<th>Middlename</th>
										<th>Email</th>
										<th>Assigned Role</th>
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
	
	<div class="modal fade" id="modal_form_user">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form_user" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <input type="hidden" name="csrf_token" value="f2875ce7221124103f7be6eff0c21d7a">

                    <div class="form-group" id="employee">
                        <label class="control-label col-md-2">Employee</label>
                        <div class="col-md-12">
                            <select id="select2_employee" name="emp_id" data-validation="required">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Email</label>
                        <div class="col-md-12">
                            <input type="text" name="email" class="form-control" data-validation="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Username</label>
                        <div class="col-md-12">
                            <input type="text" name="username" class="form-control" data-validation="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Password</label>
                        <div class="col-md-12">
                            <input type="password" name="password" class="form-control" data-validation="required">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Group</label>
                        <div class="col-md-12">
                            <select id="select2_group" name="group_id" data-validation="required">

                            </select>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">

                <button type="submit" id="btnSave" onclick="save_user()" class="btn btn-success m-btn m-btn--custom m-btn--icon  btnNew">Save</button>
                <button type="button" class="btn btn-danger m-btn m-btn--custom m-btn--icon  btnNew" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
		</div>
		<!--begin::Modal-->
<div class="modal fade" id="modal-user_role-assign">
	<div class="modal-dialog modal-m">
		<div class="modal-content"></div>
	</div>
</div>

<script>
var _modalAssignRole = $("#modal-user_role-assign");

var _currentActions = <?php echo json_encode($actions); ?>;
var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
var search_val = "";

$("#select2_employee").select2({
    placeholder: 'SELECT AN OPTION',
    width: '100%',
    ajax: {
        url: '<?php echo base_url("users/get_employee"); ?>',
        delay: 250,
        processResults: function (data) {
            return data;
        }
    }
});
	
$("#select2_group").select2({
    placeholder: 'SELECT AN OPTION',
    width: '100%',
    ajax: {
        url: '<?php echo  base_url("users/get_group"); ?>',
        delay: 250,
        processResults: function (data) {
            return data;
        }

    }
});
var _dtUsers = $("#table-users").DataTable({
	dom: '<"toolbar">frtlip',
	paging: false,
	serverSide: true,
	processing: true,
	searching: false,
	ajax: {
		url: "<?php echo base_url("core/users/get_user_list"); ?>",
		type: "post",
		dataType: "json",
		global: false,
		data: function (d) {
            d.csrf_token = _csrf_hash;
            d.search['value'] = search_val;
            return d;
        },
		dataFilter: function(response){
			var _result = JSON.parse(response);
			var _data = _result.data;
			if(typeof _data !== "undefined"){
				_data.forEach(function(row, index){
					if(typeof row.biometricno !== "undefined"){ var _bio = (row.biometricno)? row.biometricno: "---"; }
					if(typeof row.description !== "undefined"){ var _desc = (row.description)? row.description.ucWords(): "Unassigned"; }
					if(typeof row.lastname !== "undefined"){ var _lname = row.lastname.ucWords(); }
					if(typeof row.firstname !== "undefined"){ var _fname = row.firstname.ucWords(); }
					if(typeof row.middlename !== "undefined"){ var _mname = row.middlename.ucWords(); }
					if(typeof row.email !== "undefined"){ var _email = (row.email)? row.email.toLowerCase(): "none"; }
					
					if(typeof _result.data[index].biometricno !== "undefined" && _bio !== "undefined"){ _result.data[index].biometricno = _bio; }
					if(typeof _result.data[index].description !== "undefined" && _desc !== "undefined"){ _result.data[index].description = _desc; }
					if(typeof _result.data[index].lastname !== "undefined" && _lname !== "undefined"){ _result.data[index].lastname = _lname; }
					if(typeof _result.data[index].firstname !== "undefined" && _fname !== "undefined"){ _result.data[index].firstname = _fname; }
					if(typeof _result.data[index].middlename !== "undefined" && _mname !== "undefined"){ _result.data[index].middlename = _mname; }
					if(typeof _result.data[index].email !== "undefined" && _email !== "undefined"){ _result.data[index].email = _email; }
				});
			}
			
			var _response = JSON.stringify(_result);
			return _response;
		},
		error: function (xhr, error, thrown) {
			console.log( error );
		},
	}, columns: [
		{ data: "biometricno", width: "10%" },
		{ data: "lastname", width: "15%" },
		{ data: "firstname", width: "15%" },
		{ data: "middlename", width: "15%" },
		{ data: "email", width: "15%" },
		{ data: "description", width: "20%" },
		{ data: "employee_status", width: "5%"},
		{ data: null, width: "5%"},
	], columnDefs: [{
		data: null,
		defaultContent: "",
		targets: -1,
		orderable: false,
		render: function ( data, type, row, meta ) { return userDatatableActions(row.id); },
	},{
		data: "employee_status",
		defaultContent: "",
		targets: 6,
		orderable: false,
		className: "dt-column-center",
		render: function ( data, type, row, meta ) { return userDatatableStatus(row.employee_status); },
	}, { 
		targets: "_all", 
		defaultContent: "", 
	}], 
	scrollY: '55vh',
	scrollCollapse: true,
	initComplete: function(settings, json){
		if(typeof aclActionUpdate == "function"){ aclActionUpdate(); }
	}
});

function userDatatableActions($id){
	if($id){
		var _actionButton ="";
		if(typeof _currentActions !== "undefined" && jQuery.inArray("edit", _currentActions) !== -1){
			_actionButton += "<button type='button' class='btn btn-default m-btn m-btn--hover-accent btn-sm m-btn--icon m-btn--icon-only m-btn--pill btnAssignUser' data-id='"+$id+"'><i class='la la-edit'></i></button>";			
		}
		return _actionButton;
	}else{ return false; }
}
function userDatatableStatus($status){
	var _html = "";
	if($status == "Active"){
		_html = "<span class='btn btn-success m-btn m-btn--icon m-btn--icon-only btn-sm' data-toggle='m-popover' data-placement='top' data-content='"+$status+"'><i class='la la-user'></i></span>";
	}else{
		$status = ($status==null)? "Development": $status; 
		_html = "<span class='btn btn-danger m-btn m-btn--icon m-btn--icon-only btn-sm' data-toggle='m-popover' data-placement='top' data-content='"+$status+"'><i class='la la-user'></i></span>";
	}
	return _html;
}

jQuery(document).on("click", "#table-users .btnAssignUser", function(){
	var dataId = $(this).data("id");
	console.log(dataId);
	$.ajax({
		url: "<?php echo site_url("core/users/get_user_data"); ?>",
		type: "POST",
		dataType: "JSON",
		data: { id: dataId },
		success: function(json){
			if(json.response){
				var _modalContent = _modalAssignRole.find(".modal-content");
				if(typeof _modalContent !== "undefined"){
					_modalContent.empty().append(json.html);
					$(_modalAssignRole).modal("show");
				}
			}
		}
	});
	
});

jQuery(document).on("click", "#modal-user_role-assign #form-users-assign .btn-submit", function(){
	var _self = $(this);
	var _form = _self.parent(".modal-footer").parent("#form-users-assign");
	if(typeof _form !== "undefined"){
		$.ajax({
			url: _form.attr("action"),
			type: "POST",
			dataType: "JSON",
			data: _form.serialize(),
			success: function(json){
				if(json.response){
					toastr.success(json.message, "Assign User Role", 5000);
					$(_modalAssignRole).modal("hide");
					_dtUsers.ajax.reload(null, false);
				}else{
					toastr.error(json.message, "Assign User Role",  5000);
				}
			}
		});
	}
});
	
function open_user() {
    save_method = 'add';
    document.getElementById('employee').style.removeProperty('display');
    $('#form_user')[0].reset();
    $('#modal_form_user').modal('show'); // show bootstrap modal
    $('.modal-title').text('New User'); // Set Title to Bootstrap modal title

}
	function save_user() {
    var url;

    if (save_method == 'add') {
        url = '<?php echo base_url("users/add_user/"); ?>';
    } else {
        url = '<?php echo base_url("users/update_user/"); ?>';
    }


    $.validate({
        form: '#form_user',
        lang: 'en',
        onSuccess: function (form) {
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_user').serialize(),
                dataType: "JSON",

                success: function (data) {
                    if (data.status) {
                        _dtUsers.ajax.reload();
                        $("#modal_form_user").modal("hide");
                        toastr.success("User data updated!", "Success", 10000);
                    } else {
                        toastr.error("Failed updating data!", "Failed", 10000);
                    }

                }
            });
            return false;
        },
    });
}

$('#generalSearch').donetyping(function (callback) {
    search_val = $(this).val();
    _dtUsers.ajax.reload(null, false);
});

</script>
<!--end::Modal-->
