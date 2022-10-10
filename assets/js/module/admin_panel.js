function goBack() {
    window.history.back();
}
$(document).ready(function () {
// SideNav Initialization
    $(".button-collapse").sideNav();

    if ($(".sidebar-menu").length > 0) {
        $.sidebarMenu($('.sidebar-menu'));
    }

    var container = document.getElementById('slide-out');
// Data Picker Initialization
    $('.datepicker').pickadate();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
// Material Select Initialization
$(document).ready(function () {
    $('.kb-select').material_select();
});

//Animate 
new WOW().init();

function article_help(element, val) {
    var a_id = $(element).attr("data-id");
    var c_id = $(element).attr("data-c-id");
//    if (c_id == 0) {
//        $("#myModal").find(".modal-header h4").html("Article Helpful");
//        $("#myModal").find("#confirm_msg").html("Please login for submit your vote");
//        $("#myModal").modal("show");
//    } else {
    $.ajax({
        url: base_url + "add-article-helpful",
        type: "post",
        data: {a_id: a_id, value: val, token_id: csrf_token_name},
        beforeSend: function () {
            $("body").preloader({
                percent: 10,
                duration: 15000
            });
        },
        success: function (responseJSON) {
            $(".article-votes").find("button").removeClass("help_active");
            $("body .preloader").hide();
            var response = JSON.parse(responseJSON);
            $(element).addClass("help_active");
            var tot_vote = parseInt(response.up_count) + parseInt(response.down_count);
            $("#vote_count").html(response.up_count + " out of " + tot_vote + " found this helpful");
        }
    });
//    }

}

//Back To Top
$(document).ready(function () {
    $("#back-top").hide();
    /* Back to Top */
    $(window).scroll(function () {
        if ($(this).scrollTop()) {
            $('#toTop').removeClass(' lightSpeedOut');
            $('#toTop').addClass(' lightSpeedIn');
            $('#toTop').fadeIn();
        } else {
            $('#toTop').removeClass(' lightSpeedIn');
            $('#toTop').addClass(' lightSpeedOut');
            $('#toTop').delay(500).fadeOut();
        }
    });
    $("#toTop").click(function () {
        $("html, body").animate({scrollTop: 0}, 900);
    });
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