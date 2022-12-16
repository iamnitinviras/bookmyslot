$(document).ready(function () {
    folder_name = $('#folder_name').val();


    $("#image").change(function () {
        readURL(this);
    });

    $("#testimonial_form").validate({
        ignore: [],
        rules: {
            name: {
                required: true,
            },
            details: {
                required: true,
            },
        }
    });
    $("#testimonial_form").submit(function () {
        if ($("#testimonial_form").valid()) {
            $('.loadingmessage').show();
        }
    });
    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + "admin/delete-testimonial/" + id,
            type: "post",
            data: {token_id: csrf_token_name},
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
    });
});
function DeleteRecord(element) {
    var id = $(element).attr('data-id');
    var title = $(element).attr('title');
    $("#some_name").html(title);
    $("#record_id").val(id);
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
