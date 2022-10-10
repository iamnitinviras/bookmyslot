<?php include VIEWPATH . 'front/header.php'; ?>
<div class="container">
    <div class="mt-20">
        <?php $this->load->view('message'); ?>        
    </div>
    <h3 class="text-center mt-20"><?php echo isset($event_data) && count($event_data) > 0 ? $event_data[0]['category_title'] : translate('event_category'); ?></h3>
    <div class="row">
        <div class="col-md-12">
            <?php
            if ($this->session->flashdata('message')) {
                echo $this->session->flashdata('message');
            }
            ?>	
            <div class="mb-4 resp_mb-60">
                <div class="row">
                    <?php
                    if (isset($event_data) && count($event_data) > 0) {
                        foreach ($event_data as $key => $value) {
                            if (isset($value['image']) && $value['image'] != '') {
                                $imageArr = json_decode($value['image']);
                            }
                            ?>
                            <div class="col-md-4">
                                <div class="card hoverable position-r home_card event-card">
                                    <div class="view overlay">
                                        <a href="<?php echo base_url('service-details/' . slugify($value['title']) . '/' . $value['id']); ?>">
                                            <img class="card-img-top" src="<?php echo check_admin_image(isset($imageArr[0]) && $imageArr[0] != '' ? UPLOAD_PATH . "event/$imageArr[0]" : ''); ?>">
                                        </a>
                                        <ul class="titlebtn list-inline inline-ul pb-10">
                                            <li class="product_cat">
                                                <a href="<?php echo base_url('event-category/' . $value['category_id']); ?>" style="text-decoration: none;">
                                                    <?php echo $value['category_title']; ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body product-docs pb-5px">
                                        <h4 class="card-title"><?php echo $value['title']; ?></h4>
                                        <div class="w-100">
                                            <div class="sell pull-left">
                                                <p><i class="fa fa-clock-o mr-10 text-success"></i>
                                                    <?php echo str_replace('{slot_time}', $value['slot_time'], translate('event_time')); ?></p>
                                            </div>
                                            <div class="sell pull-right">
                                                <p><i class="fa fa-map-marker pr-10 text-danger"></i><?php echo $value['city_title']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-purchase">
                                        <div class="sell">
                                            <?php $created_by = get_VendorDetails($value['created_by']); ?>
                                            <a href="<?php echo base_url('profile-details/' . $created_by['user_id']); ?>" style="text-decoration: none;">
                                                <img class="auth-img" src="<?php echo check_admin_image(UPLOAD_PATH . "profiles/" . $created_by['profile_image']); ?>" alt=""/>
                                            </a>
                                            <p>
                                                <a href="<?php echo base_url('profile-details/' . $created_by['user_id']); ?>" style="text-decoration: none;">
                                                    <span class="category-title" style="color: #151111"><?php echo (isset($created_by['company_name']) && $created_by['company_name'] != '' ? $created_by['company_name'] : get_CompanyName()); ?></span>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="price_love">
                                            <span><?php echo isset($value['payment_type']) && $value['payment_type'] == 'F' ? 'Free' : price_format($value['price']); ?></span>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="col-md-12 text-center">
                            <img src="<?php echo base_url() . img_path . "/no-result.png"; ?>" alt="no-result">
                        </div>
                    <?PHP }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include VIEWPATH . 'front/footer.php'; ?>