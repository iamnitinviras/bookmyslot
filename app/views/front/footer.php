<?php
$this->db->select('*', false);
$this->db->from('app_site_setting');
$company_data = $this->db->get()->row();
$footer_color_code = get_site_setting('footer_color_code');
$Total_Event_Count = isset($total_Event) && is_array($total_Event) ? count($total_Event) : 0;


//Get app_content
$app_content_res = $this->db->query("SELECT * FROM app_content WHERE status='A'");
$app_content = $app_content_res->result_array();

$block_html_1_start = '<div class="col-md-3"><ul class="list-inline resp-bb-1">';
$block_html_1_end = '</ul></div>';

$block_html_2_start = '<div class="col-md-3"><ul class="list-inline resp-bb-1">';
$block_html_2_end = '</ul></div>';

$html_1 = "";
$html_2 = "";

$page = 0;
foreach ($app_content as $val):
    if ($page % 2 == 0) {
        $html_1 .= '<li><a href=' . base_url('page/' . $val["slug"]) . ' >' . ($val["title"]) . '</a></li>';
    } else {
        $html_2 .= '<li><a href=' . base_url('page/' . $val["slug"]) . ' >' . ($val["title"]) . '</a></li>';
    }
    $page++;
endforeach;

$get_current_currency = get_current_currency();
?>
<input type="hidden" name="currency" id="currency" value="<?php echo $get_current_currency['currency_code']; ?>"/>
<footer class="page-footer mt-0 p-0 lr-page" style="background-color : <?php echo $footer_color_code != '' && $footer_color_code != NULL ? $footer_color_code : '#4b6499' ?>!important">
    <div class="footer-content text-left">
        <div class="container">  
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-logo">
                        <img src="<?php echo get_CompanyLogo(); ?>" class="img-fluid resp_h-35 h-39" alt="">
                    </div>
                    <p class="footer-descr" style="">
                        <?php echo trim(get_site_setting('footer_text')); ?>
                    </p>
                </div>
                <?php echo $block_html_1_start . $html_1 . $block_html_1_end; ?>
                <?php echo $block_html_2_start . $html_2 . $block_html_2_end; ?>
                <div class="col-md-3">
                    <ul class="list-inline">
                        <li>
                            <a href="<?php echo base_url('faqs'); ?>" ><?php echo translate('faqs') ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('contact-us'); ?>" ><?php echo translate('contact-us') ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--Copyright--> 
    <div class="footer-copyright white-text text-left">
        <div class="container">
            <div class="d-inline-block">
                <strong>&copy;</strong> <?php echo get_CompanyName() . " " . date("Y"); ?>
            </div>

            <ul class="ml-auto inline-ul d-inline-block social_footer-icon">
                <?php if (isset($company_data->fb_link) && $company_data->fb_link != '') { ?>
                    <li><a href="<?php echo $company_data->fb_link ?>" target="_blank"><i class="fa fa-facebook white-text"></i></a></li>
                <?php } ?>

                <?php if (isset($company_data->google_link) && $company_data->google_link != '') { ?>
                    <li><a href="<?php echo $company_data->google_link ?>" target="_blank"><i class="fa fa-google-plus white-text"></i></a></li>
                <?php } ?>

                <?php if (isset($company_data->twitter_link) && $company_data->twitter_link != '') { ?>
                    <li><a href="<?php echo $company_data->twitter_link ?>" target="_blank"><i class="fa fa-twitter white-text"></i></a></li>
                <?php } ?>

                <?php if (isset($company_data->linkdin_link) && $company_data->linkdin_link != '') { ?>
                    <li><a href="<?php echo $company_data->linkdin_link ?>" target="_blank"><i class="fa fa-linkedin  white-text"></i></a></li>
                <?php } ?>

                <?php if (isset($company_data->insta_link) && $company_data->insta_link != '') { ?>
                    <li><a href="<?php echo $company_data->insta_link ?>" target="_blank"><i class="fa fa-instagram white-text"></i></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!--Copyright--> 
</footer>

<!-- Back to Top -->
<a id="toTop" class="animated lightSpeedIn" title="<?php echo translate('back_top'); ?>">
    <i class="fa fa-angle-up"></i>
</a>
<!-- /Back to Top -->

<!--Login Register Review Modal--> 
<div id="login_register_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo translate('login') . "/" . translate('register'); ?>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="login_register_modal_body">
                <?php echo translate('login_required'); ?>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="<?php echo base_url('login?next=' . $this->uri->uri_string()); ?>"><?php echo translate('login'); ?></a>
                <a class="btn btn-info" href="<?php echo base_url('register?next=' . $this->uri->uri_string()); ?>"><?php echo translate('register'); ?></a>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->config->item('js_url'); ?>module/bookmyslot.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/admin_panel.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/footer.js"></script>

<?php $select_City = $this->session->userdata('location'); ?>
<script>
    var CURRENCY = $("#currency").val();
    var locationPopup = "<?php echo isset($select_City) ? trim($select_City) : 1; ?>";
    var is_display_location = "<?php echo get_site_setting('is_display_location'); ?>";
    var Total_Event_Count = "<?php echo isset($Total_Event_Count) ? trim($Total_Event_Count) : 0; ?>";
    if (locationPopup == 1 && is_display_location == 'Y' && Total_Event_Count > 0) {
        $("#locationPopup").modal('show');
    }

    function show_dropdown(e) {
        $(e).attr("aria-expanded", "true");
        $(e).next('.dropdown-menu').attr("style", "display: none;");
    }
    $("#day-carousel").carousel({
        interval: false,
        wrap: false
    });
    $("#category-sliders").carousel({
        interval: false,
        wrap: false
    });
    
    $(".left").click(function () {
        $("#day-carousel,#category-sliders").carousel("prev");
    });
    $(".right").click(function () {
        $("#day-carousel,#category-sliders").carousel("next");
    });

    //Search Modal Box
    $(document).ready(function () {
        $('.open_location').on('click', function () {
            $("#locationPopup").addClass("modal-show");
            $("#searchPopup").addClass("modal-hide");
        });
        $(".location_close").click(function () {
            $("#searchPopup").removeClass("modal-hide");
        });
        $("#locationPopup").on('hidden.bs.modal', function () {
            $("body").addClass("modal-open");
        });
        $("#locationPopup").on('shown.bs.modal', function () {
            $("body").addClass("modal-open");
        });
        $("#search").on("keyup", function () {
            var search_txt = $(this).val();
            if (search_txt == '') {
                $(".searchbox_suggestion_wrapper").addClass("d-none");
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + "front/locations",
                    data: {search_txt: search_txt},
                    success: function (responseJSON) {
                        $("#loadingmessage").hide();
                        var response = JSON.parse(responseJSON);
                        $(".searchbox_suggestion").html("");
                        if ($.trim(response.status) == 'success') {
                            var append_html = '';
                            $(response.data).each(function (i, item) {
                                append_html += '<li>';
                                append_html += '<a data-name="' + item.city_title.toString().toLowerCase() + '" onclick="change_current_city(this)" href="javascript:void(0)">';
                                append_html += '<h6 class="searchbox_suggestion_heading">' + item.city_title + '</h6>';
                                append_html += '</a>';
                                append_html += '</li>';
                            });
                            $(".searchbox_suggestion").append(append_html);
                            $(".searchbox_suggestion_wrapper").removeClass("d-none");

                        } else {
                            $(".searchbox_suggestion_wrapper").addClass("d-none");
                        }
                    }
                });
            }

        });
    });
    function change_current_city($this) {
        var city_name = $($this).data("name");

        if (city_name != "") {
            $.ajax({
                url: "<?php echo base_url('change-city'); ?>",
                type: "post",
                data: {token_id: csrf_token_name, city_name: city_name},
                beforeSend: function () {
                    $('#loadingmessage').show();
                },
                success: function (data) {

                    $('#loadingmessage').hide();
                    $("#locationPopup").modal('hide');
                    window.location.reload();
                }
            });
        }
    }
    $('.note-video-clip').each(function () {
        var tmp = $(this).wrap('<p/>').parent().html();
        $(this).parent().html('<div class="embed-responsive embed-responsive-16by9">' + tmp + '</div>');
    });
</script>
</body>
</html>
