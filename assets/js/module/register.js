
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
                email: true
            },
            password: {
                required: true,
                minlength: 8
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
            password: {
                required: "Please enter a password",
                minlength: "Please enter minimum 8 characters"
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