
// Login Form 
$(document).ready(function () {
    $("#Login").validate({
        ignore: [],
        rules: {
            username: {
                required: true,
                email: true,
            },
            password: {
                required: true,
            }
        },
        messages: {
            username: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },
            password: {
                required: "Please enter your password"
            }
        },
    });
    $("#Login").submit(function (e) {
        if ($("#Login").valid()) {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        } else {
            e.preventDefault();
        }
    });

});


$("#Forgot_password").validate({
    rules: {
        email: {
            required: true,
            email: true
        }
    },
    messages: {
        email: {
            required: "Please enter your email",
            email: "Please enter a valid email"
        }
    },
});
$("#Forgot_password").submit(function (e) {
    if ($("#Forgot_password").valid()) {
        $("body").preloader({
            percent: 10,
            duration: 15000
        });
    } else {
        e.preventDefault();
    }
});
$("#Forgot_password").submit(function () {
    if ($("#Forgot_password").valid()) {
        $('#loadingmessage').show();
    }
});


// Reset Password Form

$("#Reset_password").validate({
    rules: {
        password: {
            required: true,
            minlength: 8
        },
        cpassword: {
            required: true,
            equalTo: "#password"
        }
    },
    messages: {
        password: {
            required: "Please enter a password",
            minlength: "Please enter minimum 8 characters"
        },
        cpassword: {
            required: "Please enter a confirm password",
            equalTo: "Password and confirm password must be same."
        }
    },
    highlight: function (e) {
        $(e).closest('.validate').removeClass('has-success has-error').addClass('has-error');
    }
});
$("#Reset_password").submit(function (e) {
    if ($("#Reset_password").valid()) {
        $("body").preloader({
            percent: 10,
            duration: 15000
        });
    } else {
        e.preventDefault();
    }
});


// Update Password Form
$(document).ready(function () {

    $("#Update_password").validate({
        rules: {
            old_password: {
                required: true
            },
            password: {
                required: true,
                minlength: 8
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: "Please enter old password",
            },
            password: {
                required: "Please enter password",
                minlength: "Please enter minimum 8 characters"
            },
            confirm_password: {
                required: "Please enter confirm password",
                equalTo: "Password and confirm password must be same."
            }
        },
    });
    $("#Update_password").submit(function (e) {
        if ($("#Update_password").valid()) {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        } else {
            e.preventDefault();
        }
    });
    $("#Update_password").submit(function () {
        if ($("#Update_password").valid()) {
            $('#loadingmessage').show();
        }
    });
});

// Profile Form
$(document).ready(function () {
    $("#Profile").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                minlength: 10,
                maxlength: 16,
                required: true
            },
            profile_image: {
                extension: "jpg|jpeg|png|gif"
            },
            profile_cover_image: {
                extension: "jpg|jpeg|png|gif"
            }
        },
        messages: {
            first_name: {
                required: "Please enter your firstname",
            },
            last_name: {
                required: "Please enter your lastname",
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Please enter minimum 10 digit of number",
                maxlength: "Please enter maximum 16 digit of number"
            },
            profile_image: {
                extension: "File must be JPEG or PNG "
            },
            profile_cover_image: {
                extension: "File must be JPEG or PNG "
            }

        },
    });

    $("#UserProfile").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                minlength: 10,
                maxlength: 16,
            },
            profile_text: {
                required: true
            },
            profile_image: {
                extension: "jpg|jpeg|png|gif"
            },
            profile_cover_image: {
                extension: "jpg|jpeg|png|gif"
            }
        },
        messages: {
            first_name: {
                required: "Please enter your firstname",
            },
            last_name: {
                required: "Please enter your lastname",
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Please enter minimum 10 digit of number",
                maxlength: "Please enter maximum 16 digit of number"
            },
            profile_image: {
                extension: "File must be JPEG or PNG "
            },
            profile_cover_image: {
                extension: "File must be JPEG or PNG "
            }

        },
    });

    $("#Profile").submit(function (e) {
        if ($("#Profile").valid()) {
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
        console.log($('img' + image).length + "===" + image);
        $('img' + image).prev("h5").show();
        $('img' + image).show();
        $('img' + image).attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
}

$(document).on('keydown', '.integers', function (e) {
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
            (e.keyCode == 67 && e.ctrlKey === true) ||
            (e.keyCode == 88 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
        return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});
$(document).on('keydown', '.phone_integers', function (e) {
    console.log(e.keyCode);
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 107, 173]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
            (e.keyCode == 67 && e.ctrlKey === true) ||
            (e.keyCode == 88 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39) || (e.keyCode == 107)) {
        return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode != 107) {
        e.preventDefault();
    }
});