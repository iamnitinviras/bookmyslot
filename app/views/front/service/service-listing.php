<?php
include VIEWPATH . 'front/header.php';
$select_City = $this->session->userdata('location');
$last = $page - 2;
$category = $this->input->get('category', true);
$vendor = $this->input->get('vendor', true);
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
            <div class="mb-0 resp_mb-60">
                <div class="event_list_main_title">
                    <h3><?php echo translate('service') . " " . translate('in'); ?> <?php echo $select_City; ?></h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="list_dropdown">
                            <form class="filter_form" id="service_filter_form" name="service_filter_form" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="kb-select initialized" onchange="this.form.submit()" name="category" id="category">
                                            <option value="0"><?php echo translate('all') . " " . translate('category'); ?></option>
                                            <?php foreach ($service_category as $val): ?>
                                                <option <?php echo ($category == $val['id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['id'] ?>"><?php echo $val['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="kb-select initialized" onchange="this.form.submit()" name="vendor" id="vendor">
                                            <option value="0"><?php echo translate('all') . " " . translate('vendor'); ?></option>
                                            <?php foreach ($vendor_data as $val): ?>
                                                <option <?php echo ($vendor == $val['id']) ? "selected='selected'" : ""; ?> value="<?php echo $val['id'] ?>"><?php echo $val['company_name'] ?></option>
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
                                    <div class="col-md-2">
                                        <a class="btn btn-info" href="<?php echo base_url('services'); ?>"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </form>
                        </div> 
                    </div>
                </div>

                <?php if (isset($total_service) && count($total_service) > 0): ?>
                    <div class="event_list_view">

                    </div>
                    <div class="row">
                        <?php
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
                            if (isset($event_img_file) && $event_img_file != "") {
                                if (file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                                    $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                                } else {
                                    $img_src = base_url(img_path . '/service.jpg');
                                }
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
                                        <a class="d-block" href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>">
                                            <img class="card-img-top" src="<?php echo $img_src; ?>">
                                        </a>
                                        <div class="prod_btn">
                                            <a class="d-block" href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>"></a>
                                            <a href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>" class="transparent border"><?php echo translate('more_info') ?></a>
                                        </div>
                                        <ul class="titlebtn list-inline inline-ul"><li class="product_cat"><a href="<?php echo base_url('services?category=' . $row['category_id']); ?>" style="text-decoration: none;"><?php echo $row['category_title']; ?></a></li></ul>
                                    </div>
                                    <div class="card-body product-docs pb-5px">
                                        <a class="d-block" href="<?php echo base_url('service-details/' . slugify($row['title']) . "/" . $row['event_id']); ?>">
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
                                            <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                <img class="auth-img" src="<?php echo $profile_image; ?>" alt="<?php echo trim($row['company_name']); ?>">
                                            </a>
                                            <p>
                                                <a href="<?php echo base_url('v/' . slugify(trim($row['company_name'])) . '/' . $row['created_by']); ?>" style="text-decoration: none;">
                                                    <span class="category-title" style="color: #151111"><?php echo trim($row['company_name']); ?></span>
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

                                            <span><a href="<?php echo base_url('day-slots/' . $row['id']); ?>" style="text-decoration: none"><?php echo translate('book') . " " . translate('now'); ?></a></span>
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
                                        <li class="page-item"><a class="btn btn-primary button_common btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('services?page=' . ($last) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-left"></i></a></li>
                                        <li class="page-item"><a class="btn btn-primary button_common  btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('services?page=' . ($page) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-right"></i></a></li>
                                    <?php } else if ($page == 1) { ?>
                                        <li class="page-item"><a class="btn btn-primary button_common  btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('services?page=' . ($page) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-right"></i></a></li>
                                    <?php } else if ($left_rec < $rec_limit) { ?>
                                        <li class="page-item"><a class="btn btn-primary  button_common btn-sm btn-rounded color-white waves-effect waves-light" href="<?php echo base_url('services?page=' . ($last) . "&category=" . $category . "&vendor=" . $vendor . "&location=" . $location) ?>"><i class="fa fa-arrow-circle-left"></i></a></li>
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