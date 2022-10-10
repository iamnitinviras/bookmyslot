$(document).ready(function () {
    if ($("#description").length) {
        jQuery.validator.addMethod("ckeditor_required", function (value, element) {
            var editorId = $(element).attr('id');
            var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
            if (messageLength > 0) {
                return true;
            } else {
                return false;
            }
        }, "This field is required.");
        
        $("#customer_post").validate({
            ignore: [],
            rules: {
                topic: {
                    required: true,
                },
                description: {
                    ckeditor_required: true,
                },
                title: {
                    required: true,
                    remote: {
                        url: site_url + "post-title-check",
                        type: "post",
                        data: {
                            topic: function () {
                                return $("#topic").val();
                            },
                            title: function () {
                                return $("#title").val();
                            },
                            id: function () {
                                return $("#id").val();
                            }
                        }
                    }
                },
                seo_description: {
                    required: true,
                },
                seo_keyword: {
                    required: true,
                },
            },
            messages: {
                title: {
                    remote: "Title is already existing this topic."
                }
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($("#" + name + "_validate"));
            },
        });
    }

    $("#customer_post").submit(function (e) {
        if ($("#customer_post").valid()) {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        } else {
            e.preventDefault();
        }
    });

    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + "post-delete/" + id,
            type: "post",
            data: {token_id: csrf_token_name},
            beforeSend: function () {
                $("body").preloader({
                    percent: 10,
                    duration: 1500
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