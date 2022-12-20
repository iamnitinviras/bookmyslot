$(document).ready(function () {
    jQuery.validator.setDefaults({ignore: ":hidden:not(#summornote_div_id),.note-editable.panel-body"});
    folder_name = $('#folder_name').val();
    $('#RecordAddonsDelete').on('click', function () {
        var id = $("#record_id").val();
        $.ajax({
            url: site_url + folder_name + "/delete-service-addons/" + id,
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

    // set default dates
    var start = new Date();
    // set end date to max one year period:
    var end = new Date(new Date().setYear(start.getFullYear() + 1));
    $('#from_date').datepicker({
        autoclose: true,
        startDate: start,
        endDate: end
                // update "toDate" defaults whenever "fromDate" changes
    }).on('changeDate', function () {
        // set the "toDate" start to not be later than "fromDate" ends:
        $('#to_date').datepicker('setStartDate', new Date($(this).val()));
    });

    $('#to_date').datepicker({
        autoclose: true,
        startDate: start,
        endDate: end
// update "fromDate" defaults whenever "toDate" changes
    }).on('changeDate', function () {
        // set the "fromDate" end to not be later than "toDate" starts:
        $('#from_date').datepicker('setEndDate', new Date($(this).val()));
    });
    $("#ServiceForm").submit(function (e) {
        if ($("#ServiceForm").valid()) {
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
            url: site_url + folder_name + "/delete-service/" + id,
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
    $('#start_time').timepicker({
        showMeridian: false,
        defaultTime: '07:00',
        minuteStep: 01,
    }).on('hide.timepicker', function (e) {
        openingHour = e.time.hours;
        openingMinutes = e.time.minutes;
        document.getElementById('end_time').value = "";
    });


    $('#end_time').timepicker({
        showMeridian: false,
        defaultTime: '08:00',
        minuteStep: 01,
    }).on('hide.timepicker', function (e) {
        closingHour = e.time.hours;
        closingMinutes = e.time.minutes;

        if (typeof openingHour == 'undefined') {
            Old_openingHour = $("#event_starttime").val();
            openingHour_Arr = Old_openingHour.split(":");
            openingHour = openingHour_Arr[0];
            openingMinutes = openingHour_Arr[1];
        }

        if (closingHour < openingHour) {
            document.getElementById('end_time').value = "";
            toastr.error("End Time should be greater than Starting Time");
        }

        if (openingHour == closingHour && openingMinutes > closingMinutes) {
            document.getElementById('end_time').value = "";
            toastr.error("End Time should be greater than Starting Time");
        }
        if (openingHour == closingHour && openingMinutes == closingMinutes) {
            document.getElementById('end_time').value = "";
            toastr.error("End Time should be greater than Starting Time");
        }
    });


});
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
                $('#location').html(data);
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
                var remain_image = $("#images_ul > li").length;
                if (remain_image <= 0) {
                    $("#image").attr("required", true);
                }
                $("#loadingmessage").hide();
            }
        });
    }
}
function delete_event_seo_image(e) {
    folder_name = $('#folder_name').val();
    if (confirm("Want to delete?")) {
        i = $(e).data('url');
        id = $(e).data('id');
        $.ajax({
            url: site_url + folder_name + "/delete-event-seo-image",
            type: "post",
            data: {id: id, i: i, token_id: csrf_token_name},
            beforeSend: function () {
                $("#loadingmessage").show();
            },
            success: function (data) {
                if (data != false) {
                    $(e).parents('li').remove();
                }
                $("#loadingmessage").hide();
            }
        });
    }
}
$("input[name='payment_type']").on('change', function () {
    if (this.value == 'P') {
        $("#price-box,.price-box").removeClass('d-none');
        $("#price").attr('required', true);
        $("#price, #discount").attr('min', '1');
        $("#discount").attr('max', '100');
    } else {
        $("#price-box,.price-box").addClass('d-none');
        $("#price").attr('required', false);
    }
});
$("input[name='is_display_address']").on('change', function () {
    if (this.value == 'Y') {
        $("#map_address").removeClass('d-none');
        $("#autocomplete").attr('required', true);
    } else {
        $("#map_address").addClass('d-none');
        $("#autocomplete").attr('required', false);
    }
});
$("input[name='multiple_slotbooking_allow']").on('change', function () {
    if (this.value == 'Y') {
        $("#book_limit").removeClass('d-none');
        $("#multiple_slotbooking_limit").attr('required', true);
    } else {
        $("#book_limit").addClass('d-none');
        $("#multiple_slotbooking_limit").attr('required', false);
    }
});
// Steppers                
$(document).ready(function () {
    $("#ServiceForm").validate({
        highlight: function (e) {
            $(e).closest('.validate').removeClass('has-success has-error').addClass('has-error');
        }
    });
});
function calc_final_price(element) {
    var discount = parseFloat($("#discount").val());
    var price = parseFloat($("#price").val());
    if (discount != '' && !isNaN(discount) && typeof discount != 'undefined' && discount > 0) {
        $("#from_date, #to_date").attr("required", true);
        cal_discount = parseFloat((price * discount) / 100);
        if (!isNaN(cal_discount)) {
            final_Price = parseFloat(price - cal_discount);
            if (!isNaN(final_Price)) {
                $("#discounted_price").val(final_Price.toFixed(2));
            }
        }

    } else {
        $("#from_date, #to_date").attr("required", false);
    }
}
$("#autocomplete").keyup(function () {
    $("#address_selection").val("0");
});

$("#autocomplete").focusout(function () {
    if ($("#address_selection").val() == 0) {
        $("#autocomplete").val("");
    }
});

var placeSearch, autocomplete;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};
function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    $("#address_selection").val("1");
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();
    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
        }
    }
}

function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            $("#business_latitude").val(position.coords.latitude);
            $("#business_longitude").val(position.coords.longitude);
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}
