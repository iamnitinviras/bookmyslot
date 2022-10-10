$(document).ready(function () {
    jQuery.validator.setDefaults({ignore: ":hidden:not(#summornote_div_id),.note-editable.panel-body"});
    folder_name = $('#folder_name').val();
    // set default dates
    var start = new Date();
// set end date to max one year period:
    var end = new Date(new Date().setYear(start.getFullYear() + 1));
    $('#from_date').datepicker({
        autoclose: true,
        startDate: start,
        endDate: end,
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
    $("#EventForm").submit(function (e) {
        if ($("#EventForm").valid()) {
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
            url: site_url + folder_name + "/delete-event/" + id,
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
// Steppers                
$(document).ready(function () {
    var navListItems = $('div.setup-panel-2 div a'),
            allWells = $('.setup-content-2'),
            allNextBtn = $('.nextBtn-2'),
            allPrevBtn = $('.prevBtn-2');

    allWells.hide();

    navListItems.click(function (e) {
        if ($(".bootstrap-timepicker-widget").length) {
            $(".bootstrap-timepicker-widget").hide();
        }
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-amber').addClass('btn-blue-grey');
            $item.addClass('btn-amber');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allPrevBtn.click(function () {
        var curStep = $(this).closest(".setup-content-2"),
                curStepBtn = curStep.attr("id"),
                prevStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

        prevStepSteps.removeAttr('disabled').trigger('click');
    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content-2"),
                curStepBtn = curStep.attr("id"),
                nextStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='time'], input[type='number'], input[type='text'],input[type='url'],input[type='email'],input[type='file'], textarea ,select"),
                isValid = true;

        var form = $("#EventForm");
        form.validate({
            ignore: [],
            rules: {
                name: {
                    required: true
                },
                description: {
                    required: true
                },
                category_id: {
                    required: true
                },
                'days[]': {
                    required: true
                },
                start_time: {
                    required: true
                },
                end_time: {
                    required: true
                },
                slot_time: {
                    required: true
                },
                per_allow: {
                    required: true
                },
                city: {
                    required: true
                },
                location: {
                    required: true
                },
                sponsor_website: {
                    url: true
                },
                status: {
                    required: true
                }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                element.parents(".form-group").append(error);
            }
        });
        if (!curInputs.valid()) {
            return false;
        }
        if (isValid)
            nextStepSteps.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel-2 div a.btn-amber').trigger('click');
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
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});
    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}
//function initAutocomplete() {
//    // Create the autocomplete object, restricting the search to geographical
//    // location types.
//    var cityBounds = new google.maps.LatLngBounds(
//            new google.maps.LatLng(21.1702, 72.8311));
//
//    autocomplete = new google.maps.places.Autocomplete(
//            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
//            {types: ['geocode'], componentRestrictions: {country: "us"}});
//    // When the user selects an address from the dropdown, populate the address
//    // fields in the form.
//    autocomplete.addListener('place_changed', fillInAddress);
//}
function fillInAddress() {
    $("#address_selection").val("1");
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();
    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
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

function getlimitType(e) {
    var type = $(e).val();
    if (type == 'L') {
        $("#total_seat_block").show();
        $("#total_seat").attr('required', true);
    } else {
        $("#total_seat_block").hide();
        $("#total_seat").attr('required', false);
    }
}

function check_valid_image(e) {
    var value = $(e).val();
    var sponsor_old_image = $("#sponsor_old_image").val();
    if (value != '' && sponsor_old_image == '') {
        $("#sponsor_image").attr('required', true);
    } else {
        $("#sponsor_image").attr('required', false);
    }
}