<div class="m-content">
    <div class="row">
        <div class="col-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                EMAIL SETTINGS
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button class="btn btn-default m-btn m-btn--icon"
                                        data-toggle="modal" data-target="#archived-list-modal">
                                   <span>
                                       <i class="la la-archive"></i>
                                       <span>Archived</span>
                                   </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 order-2 order-xl-1">
                            <button class="btn btn-success m-btn m-btn--icon m-btn--square btnNew"
                                    data-toggle="modal" data-target="#add-email-setting-modal">
                                <span>
                                    <i class="fa fa-plus"></i>
                                    <span>New</span>
                                </span>
                            </button>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 offset-xl-6 offset-lg-6 offset-md-6 offset-sm-0 order-1 order-xl-2">
                            <div class="m-input-icon m-input-icon--left m-input-icon--right">
                                <input type="text" class="form-control m-input auto-height normal-case-placeholder clearable-search-box"
                                       placeholder="Search here..." id="search-email-setting">
                                <span class="m-input-icon__icon m-input-icon__icon--left">
                                    <span>
                                        <i class="la la-search"></i>
                                    </span>
                                </span>
                                <span class="m-input-icon__icon m-input-icon__icon--right clear-search">
                                    <span>
                                        <i class="la la-close"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive-sm pt-4">
                        <table class="table table-hover table-bordered" width="100%"
                               id="tbl-email-settings">
                            <thead>
                            <tr>
                                <th>Config For</th>
                                <th>To</th>
                                <th>Cc</th>
                                <th>Bcc</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("modals/edit_email_setting"); ?>
<?php $this->load->view("modals/add_email_setting"); ?>
<?php $this->load->view("modals/archive_confirmation_modal"); ?>
<?php $this->load->view("modals/archived_list_modal"); ?>

<script type="text/javascript" src="<?= base_url('assets/js/configuration/email_settings.script.js') ?>"></script>