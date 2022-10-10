$(document).ready(function () {
    $("#LanguageForm").validate({
        ignore: [],
        rules: {
            name: {
                required: true
            }
        },
    });
    $("#EventForm").submit(function () {
        if ($("#LanguageForm").valid()) {
            $('.loadingmessage').show();
        }
    });
    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + "/admin/delete-language/" + id,
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

    $('.save_translated_lang').on('click', function () {
        var id = $(this).attr('data-id');

    });
});

function save_translated_lang(element) {
    var id = $(element).attr('data-id');
    var field = $(element).attr('data-field');
    var text_value = $("#db_field_" + id).val();

    $.ajax({
        url: site_url + "admin/save-translated-language/" + id,
        type: "post",
        data: {token_id: csrf_token_name, id: id, field: field, text_value: text_value},
        beforeSend: function () {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        },
        success: function (data) {
            if (data == true) {
                $(".preloader").fadeOut();
                toastr.success('Record has been updated.');
            } else {
                window.location.reload();
            }
        }
    });
}

function DeleteRecord(element) {
    var id = $(element).attr('data-id');
    var title = $(element).attr('title');
    $("#some_name").html(title);
    $("#confirm_msg").html("Are you sure you want to delete this record?");
    $("#record_id").val(id);
}
function get_location(ci) {
    folder_name = $('#folder_name').val();
    if (ci > 0) {
        $.ajax({
            url: site_url + folder_name + "/get-location/" + ci,
            type: "post",
            data: {token_id: csrf_token_name},
            beforeSend: function () {
                $("#loadingmessage").show();
            },
            success: function (data) {
                $('#location').html(data).material_select();
                $("#loadingmessage").hide();
            }
        });
    }
}
function get_more_image(e) {
    h = '<input type="file" name="image[]" class="form-control mt-10">';
    $("#image-data").append(h);
}
function delete_event_image(e) {
    folder_name = $('#folder_name').val();
    if (confirm("Want to delete?")) {
        i = $(e).data('url');
        id = $(e).data('id');
        h = $('#hidden_image').val();
        $.ajax({
            url: site_url + folder_name + "/delete-event-image",
            type: "post",
            data: {id: id, i: i, h: h, token_id: csrf_token_name},
            beforeSend: function () {
                $("#loadingmessage").show();
            },
            success: function (data) {
                if (data != false) {
                    $('#hidden_image').val(data);
                    $(e).parents('li').remove();
                }
                $("#loadingmessage").hide();
            }
        });
    }
}
$("input[name='payment_type']").on('change', function () {
    if (this.value == 'P') {
        $("#price-box").removeClass('d-none');
        $("#price-box").attr('required', true);
    } else {
        $("#price-box").addClass('d-none');
        $("#price-box").attr('required', false);
    }
});