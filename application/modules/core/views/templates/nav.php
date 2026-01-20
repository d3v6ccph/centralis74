<?php $config = ""; ?>
<?php $route = ""; ?>
<?php $Subheader = ""; ?>
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
<!-- BEGIN: Header -->
<header class="m-grid__item    m-header "  data-minimize-offset="200" data-minimize-mobile-offset="200" >
<div class="m-container m-container--fluid m-container--full-height">
<div class="m-stack m-stack--ver m-stack--desktop">
<!-- BEGIN: Brand -->
<div class="m-stack__item m-brand  m-brand--skin-dark ">
<div class="m-stack m-stack--ver m-stack--general">
	<div class="m-stack__item m-stack__item--middle m-stack__item--center m-brand__logo">
		<a href="<?php echo site_url(); ?>" class="m-brand__logo-wrapper">
			<img alt="" src="<?php echo base_url('assets/logo.png')?>" width="66px" width="44px"/>
		</a>
	</div>
	<div class="m-stack__item m-stack__item--middle m-brand__tools">
		<!-- BEGIN: Responsive Aside Left Menu Toggler -->
		<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
			<span></span>
		</a>
		<!-- END -->
<!-- BEGIN: Responsive Header Menu Toggler -->
		<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
			<span style="display: none;"></span>
		</a>
		<!-- END -->
<!-- BEGIN: Topbar Toggler -->
		<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
			<i class="flaticon-more"></i> 
		</a>
		<!-- BEGIN: Topbar Toggler -->
	</div>
</div>
</div>
<!-- END: Brand -->
<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
	<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
		<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
			<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
				<div class="m-header__title">
					<h4 class="m-header__title-text">CENTRAL INVENTORY SYSTEM</h4>
				</div>
			</li>
		</ul>
	</div>
<!-- BEGIN: Topbar -->
<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
	<div class="m-stack__item m-topbar__nav-wrapper">
		<ul class="m-topbar__nav m-nav m-nav--inline">
			<?php $this->load->view("core/templates/user_profile/dropdown_info"); ?>
		</ul>
	</div>
</div>
<!-- END: Topbar -->
</div>
</div>
</div>
</header>
<!-- END: Header -->
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
<i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
<!-- BEGIN: Aside Menu -->
<div 
id="m_ver_menu" 
class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark m-aside-menu--dropdown " 
data-menu-vertical="true"
data-menu-dropdown="true" data-menu-scrollable="true" data-menu-dropdown-timeout="500">

<?php
$includes = array("id", "name", "label", "url", "icon", "identifier");
echo $this->core_layout->getSidebarNavigation($includes, 1);
?>
</div>
<!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
<div class="m-grid__item m-grid__item--fluid m-wrapper">
