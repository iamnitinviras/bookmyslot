
// User Registration Form

$(document).ready(function () {
    $("#Register_user").validate({
        ignore: [],
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: base_url + "check-vendor-email",
                    type: "post",
                    data: {
                        title: function () {
                            return $("#email").val();
                        }, id: function () {
                            return $("#vendor_id").val();
                        }
                    }
                }
            },
            password: {
                required: true,
                minlength: 8
            },
            company: {
                required: true
            },
            address: {
                required: true
            },
            website: {
                url: true
            },
            phone: {
                required: true,
                remote: {
                    url: base_url + "check-vendor-phone",
                    type: "post",
                    data: {
                        title: function () {
                            return $("#phone").val();
                        }, id: function () {
                            return $("#vendor_id").val();
                        }
                    }
                }
            },
        },
        messages: {
            first_name: {
                required: "Please enter your firstname"
            },
            last_name: {
                required: "Please enter your lastname"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email",
                remote: "Email is already existing."
            },
            password: {
                required: "Please enter a password",
                minlength: "Please enter minimum 8 characters"
            },
            company: {
                required: "Please enter your company name"
            },
            website: {
                url: "Please enter valid url"
            },
            phone: {
                required: "Please enter your phone number",
                remote: "Phone is already existing."
            },
            address: {
                required: "Please enter your address",
            }
        },
    });

});

$(document).ready(function () {
    $('[data-toggle="popover"]').popover({
        placement: 'top',
        trigger: 'hover'
    });
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