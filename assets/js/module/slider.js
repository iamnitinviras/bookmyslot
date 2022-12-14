$(document).ready(function () {
    folder_name = $('#folder_name').val();

    $("#SliderForm").validate({
        ignore: [],
        rules: {

        },
    });
    $("#SliderFormForm").submit(function () {
        if ($("#SliderFormForm").valid()) {
            $('.loadingmessage').show();
        }
    });

    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + folder_name + "/slider/delete/" + id,
            type: "post",
            data: {token_id: csrf_token_name},
            success: function (data) {
                if (data == true) {
                    window.location.reload();
                } else {
                    window.location.reload();
                }
            }
        });
    });
});

function DeleteRecord(element) {
    var id = $(element).attr('data-id');
    var title = $(element).attr('title');
    $("#some_name").html(title);
    $("#record_id").val(id);
}

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