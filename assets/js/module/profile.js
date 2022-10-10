$(document).ready(function () {
    $("#Profile").validate({
        ignore: [],
        rules: {
            Firstname: {
                required: true,
            },
            Lastname: {
                required: true
            },
            Email: {
                required: true,
                email: true
            },
            Phone: {
                minlength: 10,
                maxlength: 14,
                required: true,
            },
            Pro_img: {
                extension: "jpg|jpeg|png|gif"
            }
        },
        messages: {
            Firstname: {
                required: "Please enter your firstname",
            },
            Lastname: {
                required: "Please enter your lastname",
            },
            Email: {
                required: "Please enter your email",
                email: "Please enter a valid email"
            },
            Phone: {
                required: "Please enter your phone number",
                minlength: "Please enter minimum 10 digit of number",
                maxlength: "Please enter maximum 14 digit of number"
            },
            Pro_img: {
                extension: "File must be JPEG or PNG "
            }

        },
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