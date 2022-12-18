

function DeleteRecord(element) {
    var id = $(element).attr('data-id');
    var title = $(element).attr('title');
    $("#some_name").html(title);
    $("#record_id").val(id);
}
$(document).ready(function () {
    $("#frmStaff").validate({
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
            }
        },
        messages: {
            first_name: {
                required: "Please enter firstname"
            },
            last_name: {
                required: "Please enter lastname"
            },
            email: {
                required: "Please enter email",
                email: "Please enter a valid email",
                remote: "Email is already existing."
            },
            phone: {
                required: "Please enter phone number",
                remote: "Phone is already existing."
            }
        }
    });
    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        folder_name = $('#folder_name').val();
        $.ajax({
            url: site_url + folder_name + "/staff/delete/" + id,
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
