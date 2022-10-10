<?php include VIEWPATH . 'front/header.php'; ?>
<?php
$last = $page - 2;
$category = $this->input->get('category', true);
$vendor = $this->input->get('vendor', true);
$duration = $this->input->get('duration', true);
$location = $this->input->get('location', true);
?>
<style>
    .select-wrapper span.caret {
        top: 18px;
        color: black;
    }
</style>
<div class="container">
    <div class="pt-3">
        <?php $this->load->view('message'); ?>        
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 resp_mb-60">

                <div class="event_list_main_title">
                    <h3><?php echo translate('events') . " " . translate('in'); ?> <?php echo $select_City; ?></h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="list_dropdown">
                            <form class="filter_form" id="service_filter_form" name="service_filter_form" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="kb-select initialized" onchange="this.form.submit()" name="category" id="category">
                                            <option value="0"><?php echo translate('all') . " " . translate('category'); ?></option>
                                            <?php foreach ($event_category as $val): ?>
                                                <option <?php echo ($category == $val['id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['id'] ?>"><?php echo $val['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="kb-select initialized" onchange="this.form.submit()" name="location" id="location">
                                            <option value="0"><?php echo translate('all') . " " . translate('location'); ?></option>
                                            <?php foreach ($app_location as $val): ?>
                                                <option <?php echo ($location == $val['loc_id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['loc_id'] ?>"><?php echo $val['loc_title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="kb-select initialized" onchange="this.form.submit()" name="duration" id="duration">
                                            <option value=""><?php echo translate('all'); ?></option>
                                            <option <?php echo ($duration == 'W') ? "selected='selected'" : ""; ?> value="W">This Week</option>
                                            <option <?php echo ($duration == 'M') ? "selected='selected'" : ""; ?> value="M">This Month</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-info" href="<?php echo base_url('events'); ?>"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </form>
                        </div> 
                    </div>
                </div>


                <?php if (isset($total_Event) && count($total_Event) > 0): ?>
                    <div class="event_list_view"></div>
                    <div class="row">
                        <?php
                        foreach ($total_Event as $row) {
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
                            if (isset($event_img_file) && $event_img_file != "") {
                                if (file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                    $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                                } else {
                                    $img_src = base_url() . UPLOAD_PATH . "event/events.png";
                                }
                            } else {
                                $img_src = base_url() . UPLOAD_PATH . "event/events.png";
                            }


                            /* Vendor Image */
                            $profile_image = base_url() . img_path . "/user.png";
                            if (isset($row['profile_image']) && $row['profile_image'] != "") {
                                $profile_image = check_profile_image(UPLOAD_PATH . "profiles/" . $row['profile_image']);
                            }
                            
                            $e_start_date=date('Y-m-d',strtotime($row['start_date']));
                            $e_start_time=date('H:i',strtotime($row['start_date']));
                            
    
                            $e_end_date=date('Y-m-d',strtotime($row['end_date']));
                            $e_end_time=date('H:i',strtotime($row['end_date']));
    
    
                            if($e_start_date==$e_end_date){
                                $event_date_display=date('d M',strtotime($row['start_date']))." ".$e_start_time."-".$e_end_time;
                            }else{
                                $event_date_display=date('d M',strtotime($row['start_date']))."-".date('d M',strtotime($row['end_date']))." ".$e_start_time."-".$e_end_time;
                            }

                            ?>

                            <div class="col-md-3">
                                <div class="card hoverable position-r home_card event-card">
                                    <div class="view overlay">
                                        <a class="d-block" href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>">
                                            <img class="card-img-top" src="<?php echo $img_src; ?>">
                                        </a>
                                        <div class="prod_btn">
                                            <a class="d-block" href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>"></a>
                                            <a href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                        </div>
                                        <ul class="titlebtn list-inline inline-ul"><li class="product_cat"><a href="<?php echo base_url('events?category=' . $row['category_id']); ?>" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li></ul>
                                    </div>
                                    <div class="card-body product-docs pb-5px">
                                        <a class="d-block" href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>">
                                            <h4 class="card-title"><?php echo $row['title']; ?></h4>
                                            <div class="w-100">
                                                <div class="sell mb-3"> 
                                                    <p>
                                                        <i class="fa fa-map-marker pr-10 text-danger"></i>
                                                        <?php echo $row['city_title']; ?> 
                                                        <span class="location-area"><i><?php echo $row['loc_title']; ?></i></span>
                                                    </p>
                                                    <p>
                                                        <i class="fa fa-calendar pr-0 text-danger"></i>
                                                        <?php echo $event_date_display; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="product-purchase">
                                        <div class="sell">
                                            <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                <img class="auth-img" src="<?php echo $profile_image; ?>" alt="">
                                            </a>
                                            <p>
                                                <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                    <span class="category-title" style="color: #151111"><?php echo trim($row['company_name']); ?></span>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="price_love">
                                            <p><?php echo get_event_price_by_id($row['event_id'], 'D'); ?></p>
                                            <span><a href="<?php echo base_url('event-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>" style="text-decoration: none"><?php echo translate('get') . ' ' . translate('ticket'); ?></a></span>
                                            <span style="width: 100%;margin-top: 15px;" class="event_date_time_class"  data-enddate="<?php echo date("M d, Y H:i:s", strtotime($row['end_date'])); ?>"  data-date="<?php echo date("M d, Y H:i:s", strtotime($row['start_date'])); ?>" data-id="<?php echo $row['event_id']; ?>" id="event<?php echo $row['event_id']; ?>"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    if ($total_pages > 1):
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1 && $page < $total_pages) { ?>
                                        <li class="page-item"><a class="btn btn-primary button_common  btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('events?page=' . ($last) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-left"></i></a></li>
                                        <li class="page-item"><a class="btn btn-primary button_common  btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('events?page=' . ($page) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-right"></i></a></li>
                                    <?php } else if ($page == 1 && $left_rec > 0) { ?>
                                        <li class="page-item"><a class="btn btn-primary  button_common btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('events?page=' . ($page) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-right"></i></a></li> 
                                    <?php } else if ($left_rec < $rec_limit) { ?>
                                        <li class="page-item"><a class="btn btn-primary button_common  btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('events?page=' . ($last) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-left"></i></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="col-md-12 resp_mb-60" style="text-align: center">
                        <img src="<?php echo base_url('assets/images/no-result.png'); ?>" alt="No Image"/>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?php include VIEWPATH . 'front/footer.php'; ?>
<script>
    if ($(".event_date_time_class").length > 0) {
        $(".event_date_time_class").each(function (e) {
            var event_id = $(this).data('id');
            var date = $(this).data('date');
            var enddate = $(this).data('enddate');
            set_time_count(event_id, date, enddate);
        });
    }
</script>