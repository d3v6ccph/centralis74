<!-- end:: Page -->	    
<!-- begin::Scroll Top -->
<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
	<i class="la la-arrow-up"></i>
</div>
<!-- end::Scroll Top -->
<!-- end::update password modal -->
<?php
	$privilegeName = $this->core_layout->getPrivilegeName();
	$_actions = array();
	if($privilegeName){
		$actions = $this->core_layout->generatePrivilegeAction();
		if(isset($actions[$privilegeName]) && $actions[$privilegeName]){
			$_actions = $actions[$privilegeName];
		}
		if(isset($actions["global_privileges"]) && $actions["global_privileges"]){
			foreach($actions["global_privileges"] as $privilege){
				if(!in_array($privilege, $_actions)){
					$_actions[] = $privilege;
				}
			}
		}
		
		$defaultPrivileges = $this->core_layout->listXml();
		if($defaultPrivileges){
			foreach($defaultPrivileges as $privilege){
				$nPrivilege = "btn".ucwords(strtolower($privilege));
				if(!in_array($nPrivilege, $_actions)){
					$_actions[] = $nPrivilege;
				}
			}
		}
	}
?>
<script>
	var _actions = <?php echo json_encode($_actions); ?>;
	
	var _currentPage = $(".m-content");
	/* var _buttons = _currentPage.find("button, a.btn"); */
	var _buttons = _currentPage.find("button[type=button].btn, a.btn");
	
	var _modalPage = $(".modal");
	var _modalButtons = _modalPage.find("button[type=submit], button[type=button].btn, a.btn");
	
	var _currentTable = _currentPage.find("table.dataTable");
	if(typeof _buttons !== "undefined"){
		_buttons.each(function(ii, vv){
			var found = false;
			var currentAction = $(vv);
			_actions.forEach(function(value, key){
				currentClass = currentAction.hasClass(value);
				if(currentClass){ found = true; }
			});
			
			if(found == false){ currentAction.remove(); }
		});
	}

	//init validate upload form
    $.validate({
        form: '#form-update_password',
        lang: 'en',
        onSuccess: function (form) {
            $.ajax({
                url: form[0].action,
                type: "POST",
                data: $("#form-update_password").serialize(),
                beforeSend: function () {
                    $(".btn-submit").addClass("m-btn--custom m-loader m-loader--light m-loader--right");
                },
                success: function (data) {
                    if (data.status) {
                        toastr.success(data.msg, "Password update.", 5000);
                    } else {
                        toastr.error(data.msg, "Error updating password.", 5000);
                    }
                    $(".btn-submit").removeClass("m-btn--custom m-loader m-loader--light m-loader--right");

                }
            });
            return false;
        },
    });
	
	if(typeof _modalButtons !== "undefined"){
		_modalButtons.each(function(ii, vv){
			var found = false;
			var currentAction = $(vv);
			_actions.forEach(function(value, key){
				currentClass = currentAction.hasClass(value);
				if(currentClass){ found = true; }
			});
			
			if(found == false){ currentAction.remove(); }
		});
	}
</script>
</body>
<!-- end::Body -->
</html>
