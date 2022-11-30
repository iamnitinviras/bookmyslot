<?php
$domain = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

$domain = preg_replace('/index.php.*/', '', $domain); //remove everything after index.php
if (!empty($_SERVER['HTTPS'])) {
    $domain = 'https://' . $domain;
} else {
    $domain = 'http://' . $domain;
}
$new_domain = str_replace('install/', 'admin/', $domain);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="fairsketch">
        <link rel="icon" href="../assets/images/favicon.png" />
        <title>BookMySlot Installation</title>
        <link href="../assets/css/font-awesome.css" rel="stylesheet">
        <link href="../assets/css/bootstrap.css" rel="stylesheet">
        <link href="../assets/css/module/bookmyslot.css" rel="stylesheet">
        <link href="install.css" rel="stylesheet">
        <script src="../assets/js/jquery-3.2.1.min.js"></script>
        <script src="../assets/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="../assets/js/jquery.form.js" type="text/javascript"></script>
        <!--loader-->
        <link href="../assets/loader/css/preloader.css" rel="stylesheet">
        <script src="../assets/loader/js/jquery.preloader.min.js"></script>
        <script src="../assets/js/popper.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/module/bookmyslot.js"></script>

    </head>
    <body>
        <div class="loadingmessage" id="loadingmessage"></div>
        <input type="hidden" name="base_url" id="base_url" value="<?php echo isset($new_domain) ? $new_domain : ""; ?>" />
        <div class="install-box">

            <div class="panel panel-install">
                <div class="panel-heading text-center">                    
                    <h2> BookMySlot Installation </h2>
                    <div class="alert alert-danger" id="dashboard-error" style="display: none;">
                        <i class="fa fa-warning"></i>
                        Access denied for installation please check.
                    </div>
                </div>

                <div class="panel-body no-padding">
                    <div id="alert-container"></div>
                    <div class="steps-form-2">
                        <div class="steps-row-2 setup-panel-2 d-flex justify-content-between">
                            <div class="steps-step-2">
                                <a id="requirement_step" href="#step-1" type="button" class="btn btn-amber waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Basic Information">
                                    System Requirement
                                </a>
                            </div>
                            <div class="steps-step-2">
                                <a id="db_step"  href="#step-2" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title="Personal Data">
                                    Configuration
                                </a>
                            </div>
                            <div class="steps-step-2">
                                <a id="site_step" href="#step-3" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title="Terms and Conditions">
                                    Site Setting
                                </a>
                            </div>
                            <div class="steps-step-2">
                                <a id="email_step" href="#step-4" type="button" class="btn btn-blue-grey waves-effect mr-0" data-toggle="tooltip" data-placement="top" title="Finish">
                                    Email Setting
                                </a>
                            </div>
                        </div>
                    </div>
                    <form name="config-form" id="config-form" action="do_install.php" method="post" enctype="multipart/form-data">
                        <div class="row setup-content-2" id="step-1">
                            <div class="col-md-12">
                                <h3 class="font-bold pl-0 my-4"><strong> System Requirement</strong></h3>
                            </div>

                            <div class="section">
                                <p>1. Please configure your PHP settings to match following requirements:</p>
                                <hr />                                
                                <table>
                                    <thead>
                                        <tr>
                                            <th width="25%">PHP Settings</th>
                                            <th width="27%">Current Version</th>
                                            <th>Required Version</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PHP Version</td>
                                            <td><?php echo $current_php_version; ?></td>
                                            <td><?php echo $php_version_required; ?>+</td>
                                            <td class="text-center">
                                                <?php if ($php_version_success) { ?>
                                                    <i class="status fa fa-check-circle-o"></i>
                                                <?php } else { ?>
                                                    <i class="status fa fa-times-circle-o"></i>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="section">
                                <p>2. Please make sure the extensions/settings listed below are installed/enabled:</p>
                                <hr/>
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th width="25%">Extension/settings</th>
                                                <th width="27%">Current Settings</th>
                                                <th>Required Settings</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>MySQLi</td>
                                                <td> <?php if ($mysql_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($mysql_success) { ?>
                                                        <i class="status fa fa-check-circle-o"></i>
                                                    <?php } else { ?>
                                                        <i class="status fa fa-times-circle-o"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>cURL</td>
                                                <td> <?php if ($curl_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($curl_success) { ?>
                                                        <i class="status fa fa-check-circle-o"></i>
                                                    <?php } else { ?>
                                                        <i class="status fa fa-times-circle-o"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Mod Rewrite</td>
                                                <td> <?php if ($isEnabled) { ?>
                                                        Enabled
                                                    <?php } else { ?>
                                                        Disabled
                                                    <?php } ?>
                                                </td>
                                                <td>Enabled</td>
                                                <td class="text-center">
                                                    <?php if ($isEnabled) { ?>
                                                        <i class="status fa fa-check-circle-o"></i>
                                                    <?php } else { ?>
                                                        <i class="status fa fa-times-circle-o"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PHP Intl</td>
                                                <td> <?php if ($php_intl) { ?>
                                                        Enabled
                                                    <?php } else { ?>
                                                        Disabled <a href="https://stackoverflow.com/questions/33869521/how-can-i-enable-php-extension-intl" target="_blank">Click Here to know how to enable it.</a>
                                                    <?php } ?>
                                                </td>
                                                <td>Enabled</td>
                                                <td class="text-center">
                                                    <?php if ($php_intl) { ?>
                                                        <i class="status fa fa-check-circle-o"></i>
                                                    <?php } else { ?>
                                                        <i class="status fa fa-times-circle-o"></i>
                                                    <?php } ?>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section">
                                <p>3. Please make sure you have set the <strong>writable</strong> permission on the following folders/files:</p>
                                <hr />
                                <div class="table-responsive">
                                    <table>
                                        <tbody>
                                            <?php
                                            foreach ($writeable_directories as $value) {
                                                ?>
                                                <tr>
                                                    <td style="width:87%;"><?php echo $value; ?></td>  
                                                    <td class="text-center">
                                                        <?php if (is_writeable(".." . $value)) { ?>
                                                            <i class="status fa fa-check-circle-o"></i>
                                                            <?php
                                                        } else {
                                                            $all_requirement_success = false;
                                                            ?>
                                                            <i class="status fa fa-times-circle-o"></i>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button <?php
                                if (!$all_requirement_success) {
                                    echo "disabled=disabled";
                                }
                                ?> class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button">Next</button>
                            </div>
                        </div>
                        <div class="row setup-content-2" id="step-2">
                            <div class="col-md-12">
                                <h3 class="font-bold pl-0 my-4"><strong>Configuration</strong></h3>
                            </div>
                            <div class="section clearfix">
                                <p>1. Please enter your database connection details.</p>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="host">Database Host</label>
                                            <input autocomplete="off" type="text" required="required" value="" id="host"  name="host" class="form-control validate" />                                    
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="dbuser">Database User</label>
                                            <input autocomplete="off"  type="text" required="required" value="" name="dbuser" class="form-control validate" autocomplete="off"/>                                    
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="dbpassword">Password</label>
                                            <input autocomplete="off"  type="text"  value="" name="dbpassword" class="form-control" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="dbname">Database Name</label>
                                            <input autocomplete="off"  type="text" required="required" value="" name="dbname" class="form-control validate"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section clearfix">
                                <p>2. Please enter your account details for administration.</p>
                                <hr />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="first_name">First Name</label>
                                            <input  autocomplete="off" type="text" required="required" value=""  id="first_name"  name="first_name" class="form-control validate" />                                    
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="last_name">Last Name</label>
                                            <input autocomplete="off"  type="text" required="required"  value="" id="last_name"  name="last_name" class="form-control validate" />                                    
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="email">Email</label>
                                            <input autocomplete="off"  type="email" required="required" value="" name="email" class="form-control validate"  />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="password">Password</label>
                                            <input autocomplete="off"  type="text" required="required" value="" name="password" class="form-control validate" />
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button">Previous</button>
                                <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button">Next</button>
                            </div>
                        </div>
                        <div class="row setup-content-2" id="step-3">
                            <div class="col-md-12">
                                <h3 class="font-bold pl-0 my-4"><strong>Site Setting</strong></h3>
                            </div>
                            <div class="section clearfix">
                                <p>1. Please enter your site details.</p>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="sitename">Site Name</label>
                                            <input  autocomplete="off" type="text" required="required" value="" id="sitename"  name="sitename" class="form-control validate" />                                    
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mt-3">
                                            <label for="siteemail">Site Email</label>
                                            <input autocomplete="off"  type="email" required="required" value="" name="siteemail" class="form-control validate" autocomplete="off"/>                                    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mt-3">
                                            <h6 style="color: #757575;">Site Logo <strong>(Size must be minimum of 241*61)</strong></h6>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="file-field">
                                                        <div class="btn btn-primary btn-sm">
                                                            <span>Select Image</span>
                                                            <input onchange="readURL(this)" id="imageurl" required="required"  type="file" name="logo" accept="image/x-png,image/gif,image/jpeg,image/png"  extension="jpg|png|gif|jpeg" />
                                                        </div>
                                                        <div class="file-path-wrapper" >
                                                            <input class="file-path validate form-control" type="text" placeholder="Upload your file" >
                                                        </div>
                                                    </div>
                                                    <div class="error" id="logo_validate"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <img id="imageurl"  class="img-fluid d-none" src="../assets/images/no-image.png" alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button">Previous</button>
                                <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button">Next</button>
                            </div>
                        </div>
                        <div class="row setup-content-2" id="step-4">
                            <div class="col-md-12">
                                <h3 class="font-bold pl-0 my-4"><strong>Email Setting</strong></h3>
                            </div>
                            <div class="section clearfix">
                                <p>1. Please enter your email details.</p>
                                <hr>
                                <div class="form-group">
                                    <label style="color: #757575;" >Mail Type</label>
                                    <div class="form-group form-inline">
                                        <div class="form-group">
                                            <input name='mail_type' checked="" type='radio' onclick="func_smtp()" value='S' id='inactive'>
                                            <label for='inactive'>SMTP</label>
                                        </div>
                                        <div class="form-group">
                                            <input name='mail_type' value="P" type='radio' onclick="func_php()" id='active'>
                                            <label for="active">PHP Mailer</label>
                                        </div>

                                    </div>
                                </div>
                                <div id="php_block" style="display: none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email_from">From email</label>
                                                <input  autocomplete="off" type="email" required="required" value="" id="email_from"  name="email_from" class="form-control validate" />                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="smtp_block">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="smtp_host">SMTP Host</label>
                                                <input autocomplete="off"  type="text" required="required" value="" id="smtp_host"  name="smtp_host" class="form-control validate" />                                    
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="smtp_username">SMTP Username</label>
                                                <input  autocomplete="off" type="text" required="required" value="" name="smtp_username" class="form-control validate"/>                                    
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="smtp_password">SMTP Password</label>
                                                <input  autocomplete="off" type="password" required="required" value="" name="smtp_password" class="form-control validate" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="smtp_port">SMTP Port</label>
                                                <input  autocomplete="off" type="number" required="required" value="" name="smtp_port" class="form-control validate"/>                                    
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="md-form">
                                                <h6 style="color: #757575;">Select SMTP Secure</h6>
                                                <select name="smtp_secure" id="smtp_secure" class="form-control">
                                                    <option value="tls">TLS</option>
                                                    <option value="ssl">SSL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button">Previous</button>
                                <button class="btn btn-success btn-rounded float-right" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <script type="text/javascript">
            var onFormSubmit = function ($form) {

                $form.find('[type="submit"]').find(".button-text").addClass("hide");
                $("#alert-container").html("");
            };
            var onSubmitSussess = function ($form) {
                $form.find('[type="submit"]').removeAttr('disabled').find(".loader").addClass("hide");
                $form.find('[type="submit"]').find(".button-text").removeClass("hide");
            };
            $(document).ready(function () {
                if ($('.kb-select').length) {
                    $('.kb-select').material_select();
                }

                var navListItems = $('div.setup-panel-2 div a'),
                        allWells = $('.setup-content-2'),
                        allNextBtn = $('.nextBtn-2'),
                        allPrevBtn = $('.prevBtn-2');

                allWells.hide();

                navListItems.click(function (e) {
                    e.preventDefault();
                    var $target = $($(this).attr('href')),
                            $item = $(this);

                    if (!$item.hasClass('disabled')) {
                        navListItems.removeClass('btn-amber').addClass('btn-blue-grey');
                        $item.addClass('btn-amber');
                        allWells.hide();
                        $target.show();
                        $target.find('input:eq(0)').focus();
                    }
                });

                allPrevBtn.click(function () {
                    var curStep = $(this).closest(".setup-content-2"), curStepBtn = curStep.attr("id"),
                            prevStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().prev().children("a");
                    prevStepSteps.removeAttr('disabled').trigger('click');
                });

                allNextBtn.click(function () {
                    var curStep = $(this).closest(".setup-content-2"), curStepBtn = curStep.attr("id"),
                            nextStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                            curInputs = curStep.find("input[type='text'],input[type='password'], input[type='url'],input[type='email'],input[type='file'], textarea"),
                            isValid = true;

                    var form = $("#config-form");
                    if (form.valid() == false) {
                        isValid = false;
                    }

                    if (isValid)
                        nextStepSteps.removeAttr('disabled').trigger('click');
                });

                $('div.setup-panel-2 div a.btn-amber').trigger('click');
            });

            $(document).ready(function () {
                var $preInstallationTab = $("#pre-installation-tab"),
                        $configurationTab = $("#configuration-tab");

                $(".form-next").click(function () {
                    if ($preInstallationTab.hasClass("active")) {
                        $preInstallationTab.removeClass("active");
                        $configurationTab.addClass("active");
                        $("#pre-installation").find("i").removeClass("fa-circle-o").addClass("fa-check-circle");
                        $("#configuration").addClass("active");
                        $("#host").focus();
                    }
                });

                $("#config-form").submit(function () {
                    var $form = $(this);
                    if ($form.valid()) {
                        onFormSubmit($form);
                        $form.ajaxSubmit({
                            dataType: "json",
                            beforeSend: function () {
                                $('.loadingmessage').show();
                            },
                            success: function (result) {
                                $('.loadingmessage').hide();
                                onSubmitSussess($form, result);
                                if (result.success == true) {
                                    $configurationTab.removeClass("active");
                                    $("#configuration").find("i").removeClass("fa-circle-o").addClass("fa-check-circle");
                                    $("#finished").find("i").removeClass("fa-circle-o").addClass("fa-check-circle");
                                    $("#finished").addClass("active");
                                    $("#finished-tab").addClass("active");
                                    var base_url = $("#base_url").val();
                                    window.location.href = base_url;
                                    $('.loadingmessage').hide();
                                } else {
                                    $('.loadingmessage').hide();
                                    $("#dashboard-error").hide();
                                    $("#alert-container").html('<div class="alert alert-danger" role="alert">' + result.message + '</div>');
                                    $("input[type='submit']").removeAttr("disabled");
                                    $("#" + result.id).trigger("click");
                                }
                            },
                            error: function (result) {
                                $('.loadingmessage').hide();
                                $("#dashboard-error").show();
                                $("input[type='submit']").removeAttr("disabled");
                                $("#db_step").trigger("click");
                            }
                        });
                        return false;
                    } else {
                        isValid = false;
                    }
                });

            });
            function readURL(input) {
                var id = $(input).attr("id");
                var image = '#' + id;
                $('img' + image).removeClass("d-none");
                var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
                    var reader = new FileReader();
                reader.onload = function (e) {
                    $('img' + image).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }

            function func_php() {
                $("#php_block").show();
                $("#smtp_block").hide();
                $("#email_from").attr("required", true);
            }
            function func_smtp() {
                $("#php_block").hide();
                $("#smtp_block").show();
                $("#email_from").attr("required", false);
            }
        </script>
    </body>
</html>