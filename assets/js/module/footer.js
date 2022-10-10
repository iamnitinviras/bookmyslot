$(document).ready(function () {
    if ($('#category-slider').length > 0) {
        $('#category-slider').owlCarousel({
            autoplay: false,
            loop: false,
            dots: false,
            nav: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                    slideBy: 3,
                    nav: true
                },
                600: {
                    items: 6,
                    slideBy: 6,
                    nav: true
                },
                980: {
                    items: 8,
                    slideBy: 8,
                    nav: true
                },
                1000: {
                    items: 8,
                    slideBy: 8,
                    nav: true,
                    loop: false
                }
            }

        });
        $(".owl-prev").html('<i class="fa fa-angle-left"></i>');
        $(".owl-next").html('<i class="fa fa-angle-right"></i>');
    }

});
function get_thumb(img) {
    var image_name = img.split(".");
    final_img_name = image_name[0] + "_thumb" + "." + image_name[1];
    return final_img_name;
}
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function login_protected_modal(element) {
    var message = $(element).data("message");
    $("#login_register_modal_body").html(message);
    $("#login_register_modal").modal("show");
}

if ($('#ReviewForm').length > 0) {
    $("#ReviewForm").validate({
        ignore: [],
        rules: {
            review_comment: {
                required: true
            }
        },
        messages: {
            review_comment: {
                required: "Please enter your comment"
            }
        }
    });
}

$("#ReviewForm").submit(function () {
    if ($("#ReviewForm").valid() == true) {
        $("#loadingmessage").show();
    }
});

$(".integers").keydown(function (e) {
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

function set_time_count(event_id, date, enddate) {
    var countDownDate = new Date(date).getTime();
    var countDownEndDate = new Date(enddate).getTime();

// Update the count down every 1 second
    var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;
        var distanceEnd = countDownEndDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"


        // If the count down is finished, write some text 

        if (now > countDownEndDate) {
            //clearInterval(x);
            document.getElementById("event" + event_id).innerHTML = "EXPIRED";
        } else if (now >= countDownDate && now <= countDownEndDate) {
            document.getElementById("event" + event_id).innerHTML = "On Going";
        } else {
            document.getElementById("event" + event_id).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
        }
    }, 1000);
}