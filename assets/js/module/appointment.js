$(document).ready(function () {
    $('#RecordDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: base_url + "delete-appointment/" + id,
            type: "post",
            data: {token_id: csrf_token_name},
            beforeSend: function () {
                $('#delete-record').modal('hide');
                $("body").preloader({
                    percent: 10,
                    duration: 15000
                });
            },
            success: function (data) {
                window.location.reload();
            }
        });
    });
    $('#example').DataTable({
        columnDefs: [
            {
                targets: [0, 1, 2],
                className: 'mdl-data-table__cell--non-numeric'
            }
        ]
    });
});
function DeleteRecord(element) {
    var id = $(element).attr('data-id');
    var title = $(element).attr('title');
    $("#some_name").html(title);
    $("#confirm_msg").html("Are you sure you want to delete this record?");
    $("#record_id").val(id);
}
function get_details(id) {
    $.ajax({
        url: base_url + "get-appointment-details/" + id,
        type: "post",
        data: {token_id: csrf_token_name},
        beforeSend: function () {
            $('#loadingmessage').show();
        },
        success: function (data) {
            $('#get_view_data').html(data);
            $('#loadingmessage').hide();
            $('#view-record').modal('show');
        }
    });
}
function get_rating(e) {
    $(e).parents('ul').find('li').find('i').css("color", "");
    for (i = 0; i <= $(e).index(); i++) {
        $(e).parents('ul').find('li:eq(' + i + ')').find('i').css("color", "#ffba00");
    }
}
function append_id(id, appointment_id, value, index,vendor_id) {

    $('#review_modal .modal-body').find('input, textarea').val('');

    quality_rating = location_rating = space_rating = service_rating = price_rating = 5;
    if (value == 1) {
        var quality_rating = $("#q_rating_" + index).val();
        var location_rating = $("#l_rating_" + index).val();
        var space_rating = $("#sp_rating_" + index).val();
        var service_rating = $("#se_rating_" + index).val();
        var price_rating = $("#p_rating_" + index).val();
        var comment = $("#comment_" + index).val();
        var review_id = $("#review_id_" + index).val();
        $("#review_id").val(review_id);

        $("#review_comment").val(comment);
        $("#review_id").val(review_id);

    }
    $("#space_rating").val(space_rating);
    $("#quality_rating, #quality_rate").val(quality_rating);
    $("#location_rating, #location_rate").val(location_rating);
    $("#space_rating, #space_rate").val(space_rating);
    $("#service_rating, #service_rate").val(service_rating);
    $("#price_rating, #price_rate").val(price_rating);
    $("#vendor_id").val(vendor_id);
    $("#event_id").val(id);
    $("#appointment_id").val(appointment_id);

    $("#review_modal").modal("show");
    var ratingOptions = {
        selectors: {
            starsSelector: '.rating-stars',
            starSelector: '.rating-star',
            starActiveClass: 'is--active',
            starHoverClass: 'is--hover',
            starNoHoverClass: 'is--no-hover',
            targetFormElementSelector: '.rating-value'
        }
    }
    $(".rating-stars").ratingStars(ratingOptions);
}
function change_filter() {
    $("#appointment_filter").submit();
}