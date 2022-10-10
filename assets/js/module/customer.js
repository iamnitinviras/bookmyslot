

function DeleteRecord(element) {

    var id = $(element).attr('data-id');
    var title = $(element).attr('title');
    $("#some_name").html(title);
    $("#confirm_msg").html("Are you sure you want to delete this record?");
    $("#record_id").val(id);
}

function ChangeStatus(element) {
    var id = $(element).attr('data-id');
    var status = $(element).attr('data-status');
    var title = 'Change Status';
    $("#CustomerTitle").html(title);
    $("#CustomerMsg").html("Are you sure change status this Customer");
    $("#CustomerIDVal").val(id);
    $("#CustomerStatusVal").val(status);
}
$(document).ready(function () {
    $("#frmCustomer").validate({
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
                    url: base_url + "check-customer-email",
                    type: "post",
                    data: {
                        title: function () {
                            return $("#email").val();
                        }, id: function () {
                            return $("#customer_id").val();
                        }
                    }
                }
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
        $.ajax({
            url: site_url + "admin/delete-customer/" + id,
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
    $('#CustomerChange').on('click', function () {
        var id = $("#CustomerIDVal").val();
        var status = $("#CustomerStatusVal").val();
        $.ajax({
            url: site_url + "admin/change-customer-status/" + id,
            type: "post",
            data: {status: status, token_id: csrf_token_name},
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
