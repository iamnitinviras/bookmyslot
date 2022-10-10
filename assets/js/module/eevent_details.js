$(document).ready(function () {
    $("#FrmContact").validate({
        ignore: [],
        rules: {
            fullname: {
                required: true
            },
            emailid: {
                required: true,
                email: true
            },
            phoneno: {
                required: true,
            },
            message: {
                required: true
            }
        },
        messages: {
            fullname: {
                required: "Please enter your fullname",
            },
            emailid: {
                required: "Please enter your email id",
                email: "please enter valid email id"
            },
            phoneno: {
                required: "Please enter your phone"
            },
            message: {
                required: "Please enter your message"
            }
        },
    });
    $("#FrmContact").submit(function () {
        if ($("#FrmContact").valid()) {
            $("#loadingmessage").show();
        }
    });
});
$(document).on('keydown', '.phone', function (e) {
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 107]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
            (e.keyCode == 67 && e.ctrlKey === true) ||
            (e.keyCode == 88 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39) || (e.keyCode == 107)) {
        return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode != 107) {
        e.preventDefault();
    }
});
$(function () {

    if ($("#wizards").length > 0) {
        $("#wizards").steps({
            headerTag: "h4",
            bodyTag: "section",
            transitionEffect: "fade",
            enableFinishButton: false,
            enableAllSteps: true,
            transitionEffectSpeed: 500,
            setStep: 2,
            onStepChanging: function (event, currentIndex, newIndex) {

                var BookFormValid = $("#BookForm").valid();
                if (BookFormValid) {
                    var main_ticket = parseInt($("#main_ticket").val());
                    var main_amount = parseInt($("#main_amount").val());

                    if (newIndex === 1) {
                        return true;
                    } else {
                        //Check payment method to pricess
                        if (main_amount > 0) {
                            $("#hasPayment").css("display", "block");
                            $("#hasFree").css("display", "none");
                        } else {
                            $("#hasPayment").css("display", "none");
                            $("#hasFree").css("display", "block");
                        }

                        //Display total ticket
                        if (main_ticket > 0) {
                            $("#total_book_ticket").text(main_ticket);
                            return true;
                        } else {
                            return false;
                        }
                    }

                } else {
                    return false;
                }


            },
            labels: {
                next: "Process",
                class: 'niceasd'
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
        });
    }

});


$('.btn-number').click(function (e) {
    e.preventDefault();

    fieldName = $(this).attr('data-field');
    type = $(this).attr('data-type');
    var input = $("input[name='" + fieldName + "']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if (type == 'minus') {

            if (currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if (parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if (type == 'plus') {

            if (currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if (parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
function increment($this, type) {
    var CURRENCY = $("#currency").val();
    var minValue = parseInt($($this).attr('min'));
    var maxValue = parseInt($($this).attr('max'));
    var ticket_type_id = parseInt($($this).data('id'));
    var ticket_type_id = parseInt($($this).data('id'));
    var price = parseInt($($this).data('price'));
    var valueCurrent = parseInt($("#ticket_input_id_" + ticket_type_id).val());
    var main_ticket = parseInt($("#main_ticket").val());
    var main_amount = parseInt($("#main_amount").val());

    if (type == 'd') {
        if (valueCurrent != 0) {

            var total_price = main_amount - price;
            var total_ticket = main_ticket - 1;

            $("#ticket_input_id_" + ticket_type_id).val(valueCurrent - 1);
            $("#main_ticket").val(main_ticket - 1)
            $("#total_ticket_display_count").text(total_ticket);
            $(".main_amount_display_total").text(CURRENCY + "" + total_price.toFixed(2));
            $("#main_amount").val(total_price);
            $("#amount").val(total_price);

            var UdatedCurrent = parseInt($("#ticket_input_id_" + ticket_type_id).val());
            if (UdatedCurrent == 0) {
                $("#ticket_type_id_" + ticket_type_id).attr("disabled", true);
                $("#ticket_input_id_" + ticket_type_id).attr("disabled", true);
            } else {
                $("#ticket_type_id_" + ticket_type_id).attr("disabled", false);
                $("#ticket_input_id_" + ticket_type_id).attr("disabled", false);
            }
        } else {
            $("#ticket_type_id_" + ticket_type_id).attr("disabled", true);
            $("#ticket_input_id_" + ticket_type_id).attr("disabled", true);
        }
    } else {
        if (valueCurrent < maxValue) {
            var total_price = main_amount + price;
            var total_ticket = main_ticket + 1;

            $("#ticket_input_id_" + ticket_type_id).val(valueCurrent + 1);
            $("#main_ticket").val(total_ticket);
            $("#total_ticket_display_count").text(total_ticket);
            $(".main_amount_display_total").text(CURRENCY + "" + total_price.toFixed(2));
            $("#main_amount").val(total_price);
            $("#amount").val(total_price);

            $("#ticket_type_id_" + ticket_type_id).attr("disabled", false);
            $("#ticket_input_id_" + ticket_type_id).attr("disabled", false);

        }
    }
}
function valid_paypal() {
    if ($("#BookForm").valid()) {
        $('#loadingmessage').show();
        $("#BookForm").attr("action", base_url + "eevent-booking-paypal");
        $("#BookForm").submit();
    }
}
function valid_free() {
    if ($("#BookForm").valid()) {
        $('#loadingmessage').show();
        $("#BookForm").attr("action", base_url + "eevent-booking-free");
        $("#BookForm").submit();
    }
}
function eventData(e) {
    $("#confirm_model").modal('show');
}

function valid_on_cash() {
    if ($("#BookForm").valid()) {
        $('#loadingmessage').show();
        $("#BookForm").submit();
    }
}
function valid_stripe() {
    if ($("#BookForm").valid()) {
        get_stripe();
    }
}