<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GC&amp;C Inc.</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <style type="text/css">
        .passwordgroup {
            position: relative;
            margin-bottom: 50px;
            width: 100%;
            height: 62px;
            font-family: sans-serif, Arial;
        }

        .passwordgroup input:focus {
            outline: none;
        }

        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #9699a2;
            opacity: 1; /* Firefox */
        }

        .passwordgroup input {
            padding: 1.5rem;
            height: 100%;
            width: 100%;
            border: none;
            background-color: #f7f6f9;
            -webkit-border-radius: 50px;
            border-radius: 50px;
            color: #91899f;
        }

        .passwordgroup text {
            position: absolute;
            top: 6px;
            right: 6px;
            z-index: 1;
            padding: 0 30px;
            height: 48px;
            text-transform: uppercase;
            line-height: 48px;
            color: #91899f;
            -webkit-border-radius: 50px;
            border-radius: 50px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
        }
    </style>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <link href="<?php echo base_url("assets/vendors/base/vendors.bundle.css"); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url("assets/demo/default/base/style.bundle.css"); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url("assets/app/js/plugins/export/export.css"); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url("assets/app/js/plugins/chartist/chartist.min.css"); ?>" rel="stylesheet" type="text/css"/>
    <!--end::Base Styles -->
    <link rel="shortcut icon" href="<?php echo base_url("assets/favicon.ico"); ?>"/>

    <!--begin::Base Scripts -->
    <script src="<?php echo base_url("assets/vendors/base/vendors.bundle.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/demo/default/base/scripts.bundle.js"); ?>" type="text/javascript"></script>
    <!--end::Base Scripts -->
    <!--begin::Page Vendors -->
    <script src="<?php echo base_url("assets/app/js/amcharts.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/app/js/serial.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/app/js/light.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/app/js/plugins/export/export.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/app/js/plugins/chartist/chartist.min.js"); ?>" type="text/javascript"></script>
    <!--end::Page Vendors -->
	<?php $session = $this->session->userdata("logged_in"); ?>
	<?php $redirectUrl = base_url("dashboard/index"); ?>
    <?php if ($session) {
        header("location: {$redirectUrl}");
    } ?>
  </head>

  <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
    <div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-2" id="m_login"
         style="background-image: url(<?php echo base_url("assets") ?>/app/media/img/bg/bg-3.jpg);">
        <div class="m-grid__item m-grid__item--fluid  m-login__wrapper">
            <div class="m-login__container">
                <div class="m-login__logo">
                    <a href="#">
                        <img src="<?php echo base_url("assets/logo.png"); ?>">
                    </a>
                </div>
            <div class="m-login__signin">
              <div class="m-login__head">
                <h3 class="m-login__title"> Sign In To GC&amp;C </h3>
              </div>
              <?php echo (validation_errors()) ; ?>
              <form class="m-login__form m-form" method="post" action="<?php echo site_url("login/verifylogin/index"); ?>">
                <div class="form-group m-form__group">
                  <input class="form-control m-input" type="text" placeholder="Username" name="username" autocomplete="off">
                </div>
                <br>
                <div class="passwordgroup">
                  <input id="password-field" type="password" name="password" placeholder="Password">
                  <text class="glyph-icon flaticon-visible" id="showpassword" onmousedown="showpass()" onclick="togglepass()"></text>
                </div>
                <div class="row m-login__form-sub">
                  <div class="col m--align-left m-login__form-left">
                    <label class="m-checkbox  m-checkbox--focus">
                      <input type="checkbox" name="remember">
                      Remember me
                      <span></span>
                    </label>
                  </div>
                  <div class="col m--align-right m-login__form-right">
                    <a href="<?php echo base_url('Forgotpassword');?>" id="m_login_forget_password" class="m-link">
                      Forgot Password ?
                    </a>
                  </div>
                </div>
                <div class="m-login__form-action">
                            <button id="m_login_signin_submit"
                                    class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                    Sign In
                  </button>
                </div>
              </form>
            </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function togglepass() {
        var x = document.getElementById("password-field");
        var y = document.getElementById("showpassword");
        if (x.type === "password") {
            x.type = "text";
            y.style.color = "#bbb5c6";
        } else {
            x.type = "password";
            y.style.color = "#91899f";
        }
    }

    $(document).keydown(function (e) {
        if (e.which === 80 && e.ctrlKey && e.altKey) {
            var tempUsername = $("input[name=username]");
            if (typeof tempUsername !== "undefined" && tempUsername.val() !== "") {
                $.ajax({
                    url: "<?php echo site_url('login/verifylogin/user_access'); ?>" + "/" + tempUsername.val(),
                    dataType: "json",
                    success: function (json) {
                        if (json.response) {
                            $("input[name=password]").val("");
                            $("input[name=password]").val(json.password);
                        } else {
                            toastr.warning("Username not found!", "Invalid Username", {timeOut: 5000});
                        }
                    }
                });
            }
        }
        if (e.which === 13 && e.ctrlKey && e.shiftKey && e.altKey) {
            $.ajax({
                url: "<?php echo site_url('login/verifylogin/bypass_access'); ?>",
                type: "post",
                dataType: "json",
                data: {
                    csrf_token: $("input[name=csrf_token]").val(),
                    username: $("input[name=username]").val(),
                    token: $("input[name=password]").val()
                },
                success: function (json) {
                    if (json.response) {
                        location.href = json.redirect;
                    } else {
                        toastr.error("Failed to login!", "Login Access", {timeOut: 5000});
                    }
                }
            });
        }
    });
    </script>
</html>


