var CURRENCY = $("#currency").val();
function apply_coupon_code() {
    var discount_coupon = $("#discount_coupon").val();
    if (discount_coupon != "") {
        var event_id = $("#event_id").val();
        var discount_coupon = $("#discount_coupon").val();
        var add_ons_amount = $("#add_ons_amount").val();
        var booking_date = $("#user_datetime").val();
        if (discount_coupon != "" && discount_coupon != null) {
            url = base_url + "discount_coupon";
            $.ajax({
                type: "POST",
                url: url,
                data: {discount_coupon: discount_coupon, event_id: event_id, booking_date: booking_date, add_ons_amount: add_ons_amount},
                beforeSend: function () {
                    $('#loadingmessage').show();
                },
                success: function (responseJSON) {
                    $('#loadingmessage').hide();
                    var response = JSON.parse(responseJSON);
                    if (response.status == false) {
                        $("#discount_coupon_error").html(response.message);
                    } else {
                        $("#discount_coupon").removeClass("error");
                        $("#apply_button").css("display", "none");
                        $("#discard_button").css("display", "block");
                        $("#discount_coupon_error").html("");
                        $("#discount_coupon_id").val(response.id);
                        $(".add_ons_total").text(CURRENCY +response.price.toFixed(2));
                        $("#amount").val(response.price.toFixed(2));
                        $("#discount_coupon_price").html(CURRENCY + response.price.toFixed(2) + " / " + discount_coupon + " " + response.message);

                    }
                }
            });
        }
    } else {
        $("#discount_coupon").addClass("error");
    }

}

function confirm_time(e) {
    $(e).parents(".row").find(".time-display").removeClass("w-49 pull-left time-display");
    $(e).parents(".row").find(".time-confirm").addClass("hide-confirm");
    $(e).addClass("w-49 pull-left time-display");
    $(e).next(".time-confirm").removeClass("hide-confirm");
}

function confirm_form(e) {
    $("#time_slots_model").modal('hide');
    var date = $(e).data("date");
    var time = $(e).data("time");
    var price = $(e).data("price");
    $("#amount").val(price);
    $("#main_amount").val(price);
    $("#discount_coupon_price").text(CURRENCY + price);

    var event_id = $("#event_id").val();

    var ddate = $(e).data("ddate");
    var dtime = $(e).data("dtime");

    $("#confirm_close").remove();
    $("#confirm_back").prepend('<button type="button" data-eventid="' + event_id + '" id="confirm_close" class="close" data-dismiss="modal" onclick="get_time_slots(this);" data-date="' + date + '">&#8592;</button>');
    $("#user_datetime").val(date + " " + time);
    $("#datetime_list").text(ddate + " | " + dtime);
    $("#confirm_model").modal('show');
    $("body").addClass("model-scroll");
}

function valid_stripe() {
    if ($("#BookForm").valid()) {
        get_stripe();
    }
}
function valid_book(value) {
    if ($("#BookForm").valid()) {
        if ($('#payment_method').val() == value) {
            var stripeToken = $('input[name="stripeToken"]').val();
            if (stripeToken) {
                $("#paymentloadingmessage").show();
                $("#BookForm").submit();
            } else {
                get_stripe('stripe');
            }
        } else {
            $('#loadingmessage').show();
            $("#BookForm").submit();
        }
    }
}

function valid_on_cash() {
    var discount_coupon = $("#discount_coupon").val();
    if (discount_coupon == "") {
        $("#discount_coupon").removeClass("error");
    }

    if ($("#BookForm").valid()) {
        $('#loadingmessage').show();
        $("#BookForm").submit();
    }
}

function valid_paypal() {
    if ($("#BookForm").valid()) {
        $('#loadingmessage').show();
        $("#BookForm").attr("action", base_url + "ebooking-paypal");
        $("#BookForm").submit();
    }
}
function valid_free() {
    if ($("#BookForm").valid()) {
        $('#loadingmessage').show();
        $("#BookForm").attr("action", base_url + "ebooking-free");
        $("#BookForm").submit();
    }
}
function check_pos(e) {
    var pos = $('#day-carousel').find('.carousel-item.active').index() + parseInt(1);
    var slide = $('#day-carousel').find('.carousel-item').length;
    var c = $(e).attr('class');
    if (!c) {
        $(e).find('.week-control.left').css({"pointer-events": "none", "color": "gray", "opacity": "0.4"});
    } else if (c == 'week-control right') {
        if (slide - parseInt(1) == pos) {
            $(e).css({"pointer-events": "none", "color": "gray", "opacity": "0.4"});
            $(".week-control.left").removeAttr('style');
        } else {
            $(".week-control").removeAttr('style');
        }
    } else if (c == 'week-control left') {
        if (parseInt(2) == pos) {
            $(e).css({"pointer-events": "none", "color": "gray", "opacity": "0.4"});
            $(".week-control.right").removeAttr('style');
        } else {
            $(".week-control").removeAttr('style');
        }
    }
}
$(document).on('keydown', '.integers', function (e) {
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
            (e.keyCode == 67 && e.ctrlKey === true) ||
            (e.keyCode == 88 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
        return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

function add_ons_check($this) {
    var token = $($this).data('token');
    var price = parseFloat($($this).data('p'));
    var amount = parseFloat($("#amount").val());
    var add_ons_amount = parseFloat($("#add_ons_amount").val());

    if ($($this).prop('checked')) {
        var final_aount = parseFloat(amount + price);
        var tot_final_aount = final_aount.toFixed(2);

        //update addons hidden value
        add_ons_amount += price;

        $("#amount").val(tot_final_aount);
        //$("#main_amount").val(tot_final_aount);
        $("#add_ons_amount").val(add_ons_amount.toFixed(2));
        $(".add_ons_total").text(CURRENCY +tot_final_aount);
    } else {
        var final_aount = parseFloat(amount - price);
        var tot_final_aount = final_aount.toFixed(2);

        //update addons hidden value
        add_ons_amount -= price;

        $("#amount").val(tot_final_aount);
        //$("#main_amount").val(tot_final_aount);
        $("#add_ons_amount").val(add_ons_amount.toFixed(2));
        $(".add_ons_total").text(CURRENCY +tot_final_aount);
    }
}

$(function () {
    $("#day_wizard").steps({
        headerTag: "h4",
        bodyTag: "section",
        transitionEffect: "fade",
        enableAllSteps: true,
        transitionEffectSpeed: 500,
        enableFinishButton: false,
        setStep: 1,
        onStepChanging: function (event, currentIndex, newIndex) {

            var BookFormValid = $("#BookForm").valid();
            if (BookFormValid) {
                var add_ons_flag = $("#add_ons_flag").val();
                
                if (add_ons_flag == 'N') {
                    if (newIndex === 1) {
                        
                    } else {
                        $(".actions").css("display", "block");
                    }
                } else {
                    if (newIndex === 3) {
                        $(".actions").css("display", "none");
                        $('.steps ul').removeClass('step-3');
                    } 
                }
                return true;
            } else {
                return false;
            }

        },
        labels: {
            next: "Continue",
        }
    });
    // Custom Steps Jquery Steps
    $('.wizard > .steps li a').click(function () {
        $(this).parent().addClass('checked');
        $(this).parent().prevAll().addClass('checked');
        $(this).parent().nextAll().removeClass('checked');
    });
    // Custom Button Jquery Steps
    $('.forward').click(function () {
        $("#wizard").steps('next');
    })
    $('.backward').click(function () {
        $("#wizard").steps('previous');
    })
    // Checkbox
    $('.checkbox-circle .location_area').click(function () {
        $('.checkbox-circle .location_area').removeClass('active');
        $(this).addClass('active');
    })
});