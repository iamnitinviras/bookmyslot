folder_name = $('#folder_name').val();
$(document).ready(function () {
    $("#LocationForm").validate({
        ignore: [],
        rules: {
            loc_title: {
                required: true,
                remote: {
                    url: site_url + folder_name + "/check-location-title",
                    type: "post",
                    data: {
                        title: function () {
                            return $("#loc_title").val();
                        },
                        id: function () {
                            return $("#id").val();
                        }
                    }
                }
            },
            loc_city_id: {
                required: true
            },
        },
        messages: {
            loc_title: {
                remote: "Title is already existing."
            },
        },
    });

    $("#LocationForm").submit(function () {
        if ($("#LocationForm").valid()) {
            $('.loadingmessage').show();
        }
    });

    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + folder_name + "/delete-location/" + id,
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
    $("#confirm_msg").html("Are you sure you want to delete this record?");
    $("#record_id").val(id);
}