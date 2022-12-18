function DeleteRecord(element) {
    var id = $(element).attr('data-id');
    var title = $(element).attr('title');
    $("#some_name").html(title);
    $("#record_id").val(id);
}

function ChangeStatus(element) {
    var id = $(element).attr('data-id');
    var status = $(element).attr('data-status');
    var title = 'Change Status';
    $("#CustomerTitle").html(title);
    $("#CustomerMsg").html("Are you sure change status this Vendor");
    $("#CustomerIDVal").val(id);
    $("#CustomerStatusVal").val(status);
}
$(document).ready(function () {
    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + "admin/vendor/delete/" + id,
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
    $('#CustomerChange').on('click', function () {
        var id = $("#CustomerIDVal").val();
        var status = $("#get_status").val();
        if ($('#StausForm').valid()) {
            $.ajax({
                url: site_url + "admin/change-vendor-status/" + id,
                type: "post",
                data: {status: status, token_id: csrf_token_name},
                success: function (data) {
                    if (data == true) {
                        window.location.reload();
                    } else {
                        window.location.reload();
                    }
                }
            });
        }
    });
    $('#UpdateCustomerStatus').on('click', function () {
        var id = $("#CustomerIDVal").val();
        var status = $("#get_status").val();
        if ($('#StausForm').valid()) {
            $('#StausForm').submit();
        }
    });
});
