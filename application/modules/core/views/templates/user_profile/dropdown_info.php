<?php $userProfile = $this->authenticate->getUserProfile(); ?>
<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
	<a href="#" class="m-nav__link m-dropdown__toggle">
		<span class="m-topbar__userpic">
			<img src="<?php echo (isset($userProfile["pic_filename"]) && $userProfile["pic_filename"])? $userProfile["pic_filename"]: base_url('uploads/papap.jpg'); ?>" alt=""/>
		</span>
	</a>
	<div class="m-dropdown__wrapper">
		<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="color: #233d6b; "></span>
		<div class="m-dropdown__inner">
			<div class="m-dropdown__header m--align-center" style="background-color: #233d6b;">
				<div class="m-card-user m-card-user--skin-dark">
					<div class="m-card-user__pic">
						<img src="<?php echo (isset($userProfile["pic_filename"]) && $userProfile["pic_filename"])? $userProfile["pic_filename"]: base_url('uploads/papap.jpg'); ?>" alt=""/>
					</div>
					<div class="m-card-user__details">
						<span class="m-card-user__name m--font-weight-500"><?php echo (isset($userProfile["display_name"]) && $userProfile["display_name"])? $userProfile["display_name"]: "No Assigned Name"; ?></span>
						<a href="javascript:void(0);" class="m-card-user__email m--font-weight-300 m-link" style="color: #ffffff; "><?php echo (isset($userProfile["email"]) && $userProfile["email"])? strtolower($userProfile["email"]): "noemail@gccph.com"; ?></a>
					</div>
				</div>
			</div>
			<div class="m-dropdown__body">
				<div class="m-dropdown__content">
					<ul class="m-nav m-nav--skin-light">
						<li class="m-nav__section m--hide">
							<span class="m-nav__section-text">
								Section
							</span>
						</li>
						<li class="m-nav__item">
							<a href="<?php echo base_url("core/profile"); ?>" class="m-nav__link">
								<i class="m-nav__link-icon flaticon-profile-1"></i>
								<span class="m-nav__link-title">
									<span class="m-nav__link-wrap">
										<span class="m-nav__link-text">My Profile</span>
										<?php if(isset($notifCount) && $notifCount): ?><span class="m-nav__link-badge"><span class="m-badge m-badge--success"><?php echo intval($notifCount); ?></span></span><?php endif; ?>
									</span>
								</span>
							</a>
						</li>
						<li class="m-nav__item">
							<a href="javascript:void(0);" class="m-nav__link" data-toggle="modal" data-target="#modal-update_password">
							<i class="m-nav__link-icon flaticon-lock"></i>
								<span class="m-nav__link-text">Update Password</span>
							</a>
						</li>
						<li class="m-nav__separator m-nav__separator--fit"></li>
						<li class="m-nav__item">
							<a href="<?php echo base_url("login/logout"); ?>" class="btn btn--custom-logout text-dark m-btn m-btn--bolder">Logout</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<style>
		.m-topbar__user-profile .m-dropdown__body,
		a.btn.btn--custom-logout.text-dark.m-btn.m-btn--bolder,
		.m-portlet.m-portlet--full-height{ border: 1px solid #00000052; }
	</style>
</li>

