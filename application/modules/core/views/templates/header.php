<!DOCTYPE html>
<?php $session = $this->session;
$logged_in = $session->userdata("logged_in");
if(!$logged_in){
	$this->session->sess_destroy();
	$redirect = base_url(); header("location: {$redirect}");
}
?>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title><?php echo ($this->core_layout->getPageTitle())? "GCC Inventory System | ".$this->core_layout->getPageTitle(): "GCC Inventory System | Dashboard"; ?></title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url("assets/favicon.ico"); ?>">

		<?php /**** ?>
			<script src="<?php echo base_url('assets/js/webfont/webfont.js'); ?>"></script>
			<script>
			  WebFont.load({
				google: {"families":["Montserrat:300,400,500,600,700","Roboto:300,400,500,600,700"]},
				active: function() {
					sessionStorage.fonts = true;
				}
			  });
			</script>
		<?php ****/ ?>
		<script src="<?php echo base_url('assets/js/jquery-3.3.1.min.js'); ?>"></script>

		<!--begin::Base Scripts -->
		<script src="<?php echo base_url("assets/vendors/base/vendors.bundle.js"); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url("assets/demo/demo3/base/scripts.bundle.js"); ?>" type="text/javascript"></script>
		<!--end::Base Scripts -->

		<script src="<?php echo base_url('assets/plugins/bootstrap/bootstrap.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>

		<script src="<?php echo base_url('assets/js/dataTables.bootstrap4.min.js'); ?>"></script>

		<script src="<?php echo base_url('assets/js/form-validator/jquery.form-validator.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/vue.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/currency.js'); ?>"></script>
		<?php echo $this->core_layout->getStoredJs(); ?>

		<script src="<?php echo base_url('assets/js/custom.js'); ?>"></script>

		<!--begin::Query Builder start -->
		<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/queryBuilder/query-builder.standalone.min.js'); ?>"></script>
		<link href="<?php echo base_url("assets/js/queryBuilder/query-builder.default.min.css"); ?>" rel="stylesheet" type="text/css" />
		<!-- Query Builder end -->

		<!-- dt btn export -->
		<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/external/dataTables.buttons.min.js'); ?>"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/external/jszip.min.js'); ?>"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/external/buttons.html5.min.js'); ?>"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/external/pdfmake.min.js'); ?>"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/external/vfs_fonts.js'); ?>"></script>

		<!--end::Web font -->
        <!--begin::Base Styles -->
		<link href="<?php echo base_url("assets/fonts/montserrat/montserrat.css"); ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url("assets/fonts/roboto/roboto.css"); ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url("assets/plugins/bootstrap/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url("assets/vendors/custom/datatables/datatables.bundle.css"); ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url("assets/vendors/base/vendors.bundle.css"); ?>" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url("assets/demo/demo3/base/style.bundle.css"); ?>" rel="stylesheet" type="text/css" />
		<?php echo $this->core_layout->getStoredCss(); ?>

		<link href="<?php echo base_url("assets/css/custom.css"); ?>" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->

		<!-- lightbox start -->
		<script type="text/javascript" language="javascript" src="<?php echo base_url('assets/plugins/lightbox/js/lightbox.js'); ?>"></script>
		<link href="<?php echo base_url("assets/plugins/lightbox/css/lightbox.css"); ?>" rel="stylesheet" type="text/css" media="screen" />
		<!-- lightbox end -->

        <script>
            var base_url="<?php echo base_url()?>";
        </script>

	</head>
	<!-- end::Head -->
	<!-- start::Body -->
	<body class="<?php echo $this->core_layout->getBodyClass(); ?>
	m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile 
	m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas 
	m-footer--push m-aside--offcanvas-default">
		<?php $this->load->view('core/templates/nav'); ?>