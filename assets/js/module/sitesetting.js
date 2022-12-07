$('#spnCharLeft').css('display', 'none');
var maxLimit = 130;

$(document).ready(function () {
    $('#footer_text').keyup(function () {
        var lengthCount = this.value.length;
        if (lengthCount > maxLimit) {
            this.value = this.value.substring(0, maxLimit);
            var charactersLeft = maxLimit - lengthCount + 1;
        }
        else {
            var charactersLeft = maxLimit - lengthCount;
        }
        $('#spnCharLeft').css('display', 'block');
        $('#spnCharLeft').text(charactersLeft + ' Characters left');
    });
    if ($('.demo').length) {
        $(".demo").minicolors({
            control: $(this).attr('data-control') || 'hue',
            defaultValue: $(this).attr('data-defaultValue') || '',
            format: $(this).attr('data-format') || 'hex',
            keywords: $(this).attr('data-keywords') || '',
            inline: $(this).attr('data-inline') === 'true',
            letterCase: $(this).attr('data-letterCase') || 'lowercase',
            opacity: $(this).attr('data-opacity'),
            position: $(this).attr('data-position') || 'bottom left',
            swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            change: function (value, opacity) {
                if (!value)
                    return;
                if (opacity)
                    value += ', ' + opacity;
                if (typeof console === 'object') {
                    console.log(value);
                }
            },
            theme: 'bootstrap'
        });
    }

    $("#site_form").submit(function (e) {
        if ($("#site_form").valid()) {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        } else {
            e.preventDefault();
        }
    });
    $("#site_business_form").submit(function (e) {
        if ($("#site_business_form").valid()) {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        } else {
            e.preventDefault();
        }
    });
    $("#site_business_form").validate({
        ignore: [],
        rules: {
            minimum_vendor_payout: {
                required: true
            }
        },
    });
    $("#site_email_form").validate({
        ignore: [],
        rules: {
            smtp_host: {
                required: true
            },
            smtp_password: {
                required: true
            },
            smtp_secure: {
                required: true
            },
            smtp_port: {
                required: true,
                number: true
            },
            smtp_username: {
                required: true
            },
        },
    });
    $("#site_email_form").submit(function (e) {
        if ($("#site_email_form").valid()) {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        } else {
            e.preventDefault();
        }
    });
});
// Profile Image On Click Function 
function readURL(input) {
    var id = $(input).attr("id");
    var image = '#' + id;
    //alert(image);
    var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
        var reader = new FileReader();
    reader.onload = function (e) {
        $('img' + image).attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
}
// Profile Image On Click Function 
function readURLIcon(input) {
    var id = $(input).attr("id");
    var image = '#' + id;
    //alert(image);
    var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0] && (ext == "ico"))
        var reader = new FileReader();
    reader.onload = function (e) {
        $('img' + image).attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

// Steppers                
$(document).ready(function () {
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
            navListItems.removeClass('kb-color').addClass('btn-blue-grey');
            $item.addClass('kb-color');
            $item.removeClass('btn-blue-grey');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allPrevBtn.click(function () {
        var curStep = $(this).closest(".setup-content-2"),
                curStepBtn = curStep.attr("id"),
                prevStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

        prevStepSteps.removeAttr('disabled').trigger('click');
    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content-2"),
                curStepBtn = curStep.attr("id"),
                nextStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='number'], input[type='text'],input[type='url'],input[type='email'],input[type='file'], textarea ,select"),
                isValid = true;

        var form = $("#site_form");
        form.validate({
            ignore: [],
            rules: {
                company_name: {
                    required: true
                },
                company_email1: {
                    required: true,
                    email: true
                },
                home_page: {
                    required: true
                },
                Pro_img: {
                    extension: "png|jpeg|jpg",
                },
                banner_img: {
                    extension: "png|jpeg|jpg",
                },
                fevicon_icon: {
                    extension: "png|jpeg|jpg",
                }
            },
            messages: {
                company_name: {
                    required: "Please Enter Company name"
                },
                company_email1: {
                    required: "Please enter email ",
                    email: "Please enter valid email "
                },
                home_page: {
                    required: "Please select home page ",
                },
                Pro_img: {
                    extension: "File must be JPEG or PNG "
                },
                banner_img: {
                    extension: "File must be JPEG or PNG "
                }

            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                element.parents(".form-group").append(error);
            }
        });
        if (!curInputs.valid()) {
            return false;
        }
        if (isValid)
            nextStepSteps.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel-2 div a.kb-color').trigger('click');


});

function update_display_setting(element) {
    var id = $(element).attr("id");


    var updata_arr = ['display_record_per_page', 'google_location_search_key', 'google_map_key', 'footer_color_code', 'header_color_code', 'footer_text']
    if ($.inArray(id, updata_arr) != -1) {
        if ($(element).val() != '') {

            if (id == 'display_record_per_page') {
                up_data = parseInt($(element).val());
                if (up_data == "" || up_data <= 0 || isNaN(up_data)) {
                    up_data = 12;
                }
            } else {
                up_data = $(element).val();
            }
            $.ajax({
                url: site_url + "admin/update-display-setting",
                type: "post",
                data: {token_id: csrf_token_name, up_data: up_data, name: id, action: 'perpage_setting'},
                beforeSend: function () {
                    $("body").preloader({
                        percent: 10,
                        duration: 15000
                    });
                },
                success: function (data) {
                    if (data == true) {
                        window.location.reload();
                    } else {
                        window.location.reload();
                    }
                }
            });
        }
    } else {
        if ($(element).is(":checked")) {
            is_display = 'Y';
        } else {
            is_display = 'N';
        }
        $.ajax({
            url: site_url + "admin/update-display-setting",
            type: "post",
            data: {token_id: csrf_token_name, action: 'display_setting', display: is_display, name: id},
            beforeSend: function () {
                $("body").preloader({
                    percent: 10,
                    duration: 15000
                });
            },
            success: function (data) {
                if (data == true) {
                    window.location.reload();
                } else {
                    window.location.reload();
                }
            }
        });
    }
}

function update_date_time(time_format) {
    $.ajax({
        url: site_url + "admin/update-display-setting",
        type: "post",
        data: {token_id: csrf_token_name, action: 'update_time', time_format: time_format},
        beforeSend: function () {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        },
        success: function (data) {
            if (data == true) {
                window.location.reload();
            } else {
                window.location.reload();
            }
        }
    });
}
function get_webpage(e) {
    var value = $(e).val();
    if (value != '') {
        $("#integration_form").submit();
    }
}
function copy_code(e) {
    var value = $("#webpage").val();
    if (value != '') {
        $("#content").select();
        document.execCommand('copy');
    }else{
         toastr.error("Please first select choice");
    }
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
function check_package_val(e) {
    if (e == 'Y') {
        $('#commission_percentage_div').hide();
        $("#commission_percentage").removeClass("error");
        $('#commission_percentage').attr('required', false);
        $('#commission_percentage').attr('aria-invalid', false);
        $('#commission_percentage').attr('aria-required', false);
    } else {
        $('#commission_percentage_div').show();
        $('#commission_percentage').attr('required', true);
    }
}

function check_stripe_val(e) {
    if (e == 'Y') {
        $('.stripe-html').removeClass('d-none');
        $('#stripe_secret').attr('required', true);
        $('#stripe_publish').attr('required', true);
    } else {
        $('.stripe-html').addClass('d-none');
        $('#stripe_secret').attr('required', false);
        $('#stripe_publish').attr('required', false);
    }
}
function check_paypal_val(e) {
    if (e == 'Y') {
        $('.palpal-html').removeClass('d-none');
        $('#paypal_merchant_email').attr('required', true);
    } else {
        $('.palpal-html').addClass('d-none');
        $('#paypal_merchant_email').attr('required', false);
    }
}

function check_twoCheckout_val(e) {
    if (e == 'Y') {
        $('.twoCheckout-html').removeClass('d-none');
        $('#2checkout_account_no').attr('required', true);
        $('#2checkout_publishable_key').attr('required', true);
        $('#2checkout_private_key').attr('required', true);
    } else {
        $('.twoCheckout-html').addClass('d-none');
        $('#2checkout_account_no').attr('required', false);
        $('#2checkout_publishable_key').attr('required', false);
        $('#2checkout_private_key').attr('required', false);
    }
}