<?php
include VIEWPATH . 'front/header.php';
?>
<style>

    .sec-bg-white {
        background: #fff;
    }
    .list-spac {
        margin: 0px 15px 15px 15px;
        padding: 15px 0px;
        margin-bottom: 15px !important;
    }
    .list-spac-1 {
        border: 1px solid #e2e2e2;
    }
    .home-list-pop {
        position: relative;
        overflow: hidden;
        background: #fdfeff;
        padding: 15px 0px;
        margin-bottom: 30px;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.07);
        border: 1px solid #f1f2f3;

    }
    .home-list-pop:hover {
        -moz-transform: scale(1.02);
        -webkit-transform: scale(1.02);
        -o-transform: scale(1.02);
        transform: scale(1.02);
        box-shadow: 0px 11px 9px -10px rgba(0, 0, 0, 0.52);
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }
    .home-list-pop img {
        width: 100%;
        height: 116px;
        -webkit-object-fit: cover;
        -moz-object-fit: cover;
        -o-object-fit: cover;
        object-fit: cover;
    }
    .list-ser-img img{
        width: 100%;
        height: 165px;
        -webkit-object-fit: cover;
        -moz-object-fit: cover;
        -o-object-fit: cover;
        object-fit: cover;
    }
    .home-list-pop.list-spac img {
    }
    .home-list-pop-desc {
    }
    .home-list-pop-desc span {
        text-transform: uppercase;
        font-size: 12px;
    }
    .home-list-pop-desc h3 {
        font-size: 18px;
        color: #000000;
    }
    .home-list-pop-desc h4 {
        font-size: 14px;
        padding-bottom: 8px;
    }
    .home-list-pop-desc p {
        margin-bottom: 0px;
        font-size: 13px;
    }
    .home-list-pop-rat {
        position: absolute;
        background: #96c113;
        padding: 4px;
        font-weight: 600;
        color: #fff;
        right: 15px;
        top: 0px;
        font-size: 14px;
        border-radius: 2px;
    }

    .list-number {
        position: relative;
        overflow: hidden;
        width: 100%;
        padding-top: 10px;
    }
    .list-number ul {
        padding: 0px;
    }
    .list-number ul li {
        display: inline-block;
        float: left;
        width: 50%;
        color: #000000;
        font-size: 12px;
    }
    .list-number ul li img {
        width: 18px;
        height: 18px;
        margin-right: 7px;
    }

    .inn-list-pop-desc h3 {
        font-size: 18px;
    }
    .inn-list-pop-desc p {
        font-size: 14px;
    }
    .inn-list-pop-desc p b {
        color: #333;
    }
    .list-enqu-btn {
        position: relative;
        overflow: hidden;
        width: 100%;
        padding-top: 10px;
    }
    .list-enqu-btn ul {
        padding: 0px;
    }
    .list-enqu-btn ul li {
        display: inline-block;
        float: left;
        margin: 0px;
    }
    .list-enqu-btn ul li a {
        border: 1px solid #eaeaea;
        text-align: center;
        display: block;
        padding: 5px;
        color: #3e4c56;
        box-sizing: border-box;
        margin: 4px;
        border-radius: 2px;
        font-weight: 600;
        font-size: 14px;
    }
    .list-enqu-btn ul li a:hover {
        color: #fff;
        background-color: #172437;
        border: 1px solid #172233;
    }

    .list-enqu-btn ul li a i {
        margin-right: 5px;
    }
    .list-enqu-btn ul li:first-child a {
        background: #F44336;
        border: 1px solid #dc2e21;
        color: #fff;
    } 
    .margin_b_t_5{
        margin-bottom: 5px;
        margin-top: 5px;
    }
</style>
<div class="h-100">
    <div class="container container-min-height">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 col-12">
                    <h4 style="margin-bottom: 0px;" class="pt-10">
                        <?php echo translate('search') . '  ' . translate('result') . '  ' . translate('for'); ?> : <strong>“<?php echo $search_string; ?>”</strong>
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-12">
                <div class="col-md-12 col-12">
                    <div class="search_sidebar">
                        <div class="se_sidebar_ul">
                            <ul class="nav nav-tabs se_side_tabs" id="myTab" role="tablist">
                                <?php if (get_site_setting('enable_event') == 'Y'): ?>
                                    <?php if (isset($event) && count($event) > 0): ?>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link <?php echo (count($event) > 0) ? "active show" : ""; ?>" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="<?php echo (count($event) > 0) ? "true" : "false"; ?>">
                                                <?php echo translate('event'); ?>
                                                <span class="float-right badge indigo ml-20"><?php echo count($event); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if (get_site_setting('enable_service') == 'Y'): ?>
                                    <?php if (isset($service) && count($service) > 0): ?>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link <?php echo (count($event) == 0 && count($service) > 0) ? "active show" : ""; ?>" id="service-tab" data-toggle="tab" href="#service" role="tab" aria-controls="service" aria-selected="<?php echo (count($event) == 0 && count($service) > 0) ? "true" : "false"; ?>">
                                                <?php echo translate('service'); ?>
                                                <span class="float-right badge indigo ml-20"><?php echo count($service); ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="tab-content" id="myTabContent">
                    <?php if (get_site_setting('enable_event') == 'Y'): ?>
                        <?php if (isset($event) && count($event) > 0): ?>
                            <div class="tab-pane  <?php echo (count($event) > 0) ? "active show" : "fade"; ?>" id="event" role="tabpanel" aria-labelledby="event-tab">

                                <div class="card-body  p-0">
                                    <?php
                                    foreach ($event as $row):
                                        $event_img_file = '';
                                        $event_img_Arr = json_decode($row['image']);

                                        if (isset($event_img_Arr) && !empty($event_img_Arr)) {
                                            foreach ($event_img_Arr as $key => $value) {
                                                $all_image[] = check_admin_image(UPLOAD_PATH . "event/" . $value);
                                            }
                                            $event_img = isset($event_img_Arr[0]) ? $event_img_Arr[0] : '';
                                            if ($event_img != '') {
                                                $original_filename = (pathinfo($event_img, PATHINFO_FILENAME));
                                                $original_extension = (pathinfo($event_img, PATHINFO_EXTENSION));
                                                $event_img_file = $original_filename . "_thumb" . "." . $original_extension;
                                            }
                                        }
                                        if ($event_img_file != '' && file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                            $event_img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                                        } else {
                                            $event_img_src = base_url() . UPLOAD_PATH . "event/events.png";
                                        }
                                        ?>
                                        <div class="home-list-pop list-spac row">
                                            <div class="col-md-3 list-ser-img">
                                                <img src="<?php echo $event_img_src; ?>" class="img-responsive" alt="<?php echo $row['title']; ?>"> 
                                            </div>
                                            <div class="col-md-6 home-list-pop-desc inn-list-pop-desc">
                                                <p><?php echo date("F d", strtotime($row['start_date'])); ?></p>
                                                <a href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>"><h3 class="margin_b_t_5"><?php echo $row['title']; ?></h3></a>
                                                <h4><?php echo translate('organizer'); ?> : <?php echo $row['company_name']; ?></h4>
                                                <p>
                                                    <b><i class="fa fa-map-marker"></i></b>  <?php echo $row['loc_title'] . " / " . $row['city_title']; ?>  |
                                                    <b><i class="fa fa-list"></i></b>  <?php echo $row['category_title']; ?>
                                                </p>
                                                <p style="font-size: 18px;">
                                                    <?php echo get_event_price_by_id($row['id'], 'D'); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-3 home-list-pop-desc inn-list-pop-desc">
                                                <div class="list-enqu-btn">
                                                    <ul>
                                                        <li><a href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> <?php echo translate('get') . ' ' . translate('ticket'); ?></a> </li>
                                                        <li><a href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['id']); ?>"><i class="fa fa-star-o" aria-hidden="true"></i> <?php echo translate('more_info') ?></a> </li>
                                                        <li><a href="tel:<?php echo $row['phone']; ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?php echo translate('call_now') ?></a> </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (get_site_setting('enable_service') == 'Y'): ?>
                        <?php if (isset($service) && count($service) > 0) : ?>
                            <div class="tab-pane <?php echo ((count($event) == 0 && count($service) > 0) || get_site_setting('enable_event') == 'N') ? "active show" : "fade"; ?> " id="service" role="tabpanel" aria-labelledby="service-tab">

                                <?php
                                foreach ($service as $row) {
                                    $event_img_file = '';
                                    $event_img_Arr = json_decode($row['image']);

                                    if (isset($event_img_Arr) && !empty($event_img_Arr)) {
                                        foreach ($event_img_Arr as $key => $value) {
                                            $all_image[] = check_admin_image(UPLOAD_PATH . "event/" . $value);
                                        }
                                        $event_img = isset($event_img_Arr[0]) ? $event_img_Arr[0] : '';
                                        if ($event_img != '') {
                                            $original_filename = (pathinfo($event_img, PATHINFO_FILENAME));
                                            $original_extension = (pathinfo($event_img, PATHINFO_EXTENSION));
                                            $event_img_file = $original_filename . "_thumb" . "." . $original_extension;
                                        }
                                    }
                                    if ($event_img_file != '' && file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                        $event_img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                                    } else {
                                        $event_img_src = base_url(img_path . '/service.jpg');
                                    }
                                    ?>
                                    <div class="home-list-pop list-spac row">
                                        <div class="col-md-3 list-ser-img">
                                            <img src="<?php echo $event_img_src; ?>" class="img-responsive" alt="<?php echo $row['title']; ?>"> 
                                        </div>
                                        <div class="col-md-6 home-list-pop-desc inn-list-pop-desc">
                                            <a href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['id']); ?>"><h3><?php echo $row['title']; ?></h3></a>
                                            <h4><?php echo $row['company_name']; ?></h4>
                                            <p>
                                                <b><i class="fa fa-map-marker"></i></b>  <?php echo $row['loc_title'] . " / " . $row['city_title']; ?>  |
                                                <b><i class="fa fa-list"></i></b>  <?php echo $row['category_title']; ?>  |
                                                <b><i class="fa fa-clock-o"></i></b>  <?php echo convertToHoursMins($row['slot_time']); ?>
                                            </p>

                                            <p style="font-size: 18px;">
                                                <?php if (isset($row['payment_type']) && $row['payment_type'] == 'F'): ?>
                                                    <?php echo translate('free'); ?>
                                                    <?php
                                                else:
                                                    $get_discount_price_by_date = get_discount_price_by_date($row['id'], date('Y-m-d'));
                                                    ?>
                                                    <?php if ($get_discount_price_by_date == $row['price']): ?>
                                                        <?php echo price_format($row['price']); ?>
                                                    <?php else: ?>
                                                        <?php echo price_format($get_discount_price_by_date); ?> <span class="total_price"><?php echo price_format($row['price']); ?></span>  
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3 home-list-pop-desc inn-list-pop-desc">
                                            <div class="list-enqu-btn">
                                                <ul>
                                                    <li><a href="<?php echo base_url('day-slots/' . $row['id']); ?>"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> <?php echo translate('book') . ' ' . translate('now'); ?></a> </li>
                                                    <li><a href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['id']); ?>"><i class="fa fa-star-o" aria-hidden="true"></i> <?php echo translate('more_info') ?></a> </li>
                                                    <li><a href="tel:<?php echo $row['phone']; ?>"><i class="fa fa-phone" aria-hidden="true"></i>  <?php echo translate('call_now') ?></a> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (count($event) == 0 && count($service) == 0): ?>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-responsive" src="<?php echo base_url() . img_path . "/no-result.png"; ?>" alt="no-result">
                            </div>
                        </div>
                    <?php endif; ?>
                </div> 


            </div>
        </div>
    </div>
</div>
<?php include VIEWPATH . 'front/footer.php'; ?>