<?php
$token = trim($this->uri->segment(2));
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo isset($title) ? $title : ""; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>
        <link href="<?php echo $this->config->item('css_url'); ?>font-awesome.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>line-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>bootstrap.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/bookmyslot.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/admin_panel.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('css_url'); ?>module/custom.css" rel="stylesheet">

        <script src="<?php echo $this->config->item('js_url'); ?>jquery-3.2.1.min.js"></script>
        <script src="<?php echo $this->config->item('js_url'); ?>popper.min.js"></script>
        <script src="<?php echo $this->config->item('js_url'); ?>bootstrap.min.js"></script>
<script>
            var base_url = '<?php echo base_url() ?>';
            var display_record_per_page = '<?php echo get_site_setting('display_record_per_page'); ?>';
            var csrf_token_name = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            });
            $(document).ajaxComplete(function () {
                $.ajaxSetup({
                    data: {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    }
                });
            });
        </script>

    </head>
    <body>
        <div class="main_content">
            <div class="container">
                <br/>
                <br/>
                <div class="row">
                    <?php
                    if (isset($total_service) && count($total_service) > 0) {
                        foreach ($total_service as $row) {
                            $event_img_file = '';
                            $event_img_Arr = json_decode($row['image']);
                            if (isset($event_img_Arr) && !empty($event_img_Arr)) {
                                $event_img = isset($event_img_Arr[0]) ? $event_img_Arr[0] : '';
                                if ($event_img != '') {

                                    $original_filename = (pathinfo($event_img, PATHINFO_FILENAME));
                                    $original_extension = (pathinfo($event_img, PATHINFO_EXTENSION));
                                    $event_img_file = $original_filename . "_thumb" . "." . $original_extension;
                                }
                            }
                            if (isset($event_img_file) && $event_img_file != "" && file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                            } else {
                                $img_src = base_url(img_path . '/service.jpg');
                            }

                            $profile_image = base_url() . img_path . "/user.png";
                            if (isset($row['profile_image']) && $row['profile_image'] != "") {
                                $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $row['profile_image']);
                            }
                            ?>
                            <div class="col-md-3">
                                <div class="card hoverable position-r home_card event-card">
                                    <div class="view overlay">
                                        <a class="d-block" href="<?php echo base_url('eservices-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>">
                                            <img class="card-img-top" src="<?php echo $img_src; ?>">
                                        </a>
                                        <div class="prod_btn">
                                            <a class="d-block" href="<?php echo base_url('eservices-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>"></a>
                                            <a href="<?php echo base_url('eservices-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                        </div>
                                        <ul class="titlebtn list-inline inline-ul">
                                            <li class="product_cat"><a href="javascript:void(0)" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body product-docs pb-5px">
                                        <a class="d-block" href="<?php echo base_url('eservices-details/' . ($row['event_slug']) . "/" . $token . '/' . $row['event_id']); ?>">
                                            <h4 class="card-title"><?php echo $row['title']; ?></h4>
                                            <div class="w-100">
                                                <div class="sell mb-3"> 
                                                    <p>
                                                        <i class="fa fa-map-marker pr-10 text-danger"></i>
                                                        <?php echo $row['city_title']; ?> 
                                                        <span class="location-area"><i><?php echo $row['loc_title']; ?></i></span>
                                                    </p>
                                                </div>
                                                <div class="sell mb-3">
                                                    <p><i class="fa fa-clock-o mr-10 text-success"></i><?php echo convertToHoursMins($row['slot_time']); ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="product-purchase">
                                        <div class="sell">
                                            <a href="javascript:void(0)" style="text-decoration: none;">
                                                <img class="auth-img" src="<?php echo $profile_image; ?>" alt="">
                                            </a>
                                            <p>
                                                <a href="javascript:void(0)" style="text-decoration: none;">
                                                    <span class="category-title" style="color: #151111"><?php echo $row['company_name']; ?></span>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="price_love">
                                            <?php if ($row['payment_type'] == "F"): ?>
                                                <span><?php echo translate('free'); ?></span>
                                                <?php
                                            else:
                                                $get_discount_price_by_date = get_discount_price_by_date($row['id'], date('Y-m-d'));
                                                ?>
                                                <?php if ($get_discount_price_by_date == $row['price']): ?>
                                                    <p class="text-right"><?php echo price_format($row['price']); ?></p>
                                                <?php else: ?>
                                                    <p class="text-right"><?php echo price_format($get_discount_price_by_date); ?> <span class="total_price"><?php echo price_format($row['price']); ?></span></p>                                        
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <span><a href="<?php echo base_url('eslots/' . $token . '/' . $row['id']); ?>" style="text-decoration: none"><?php echo translate('book') . " " . translate('now'); ?></a></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }else {
                        ?>
                        <div class="alert alert-danger text-center w-100">No Service Found</div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
