<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
} else {
    include VIEWPATH . 'admin/header.php';
}

$location_api_key = get_site_setting('google_location_search_key');
$name = (set_value("name")) ? set_value("name") : (!empty($event_data) ? $event_data['title'] : '');
$description = (set_value("description")) ? set_value("description") : (!empty($event_data) ? $event_data['description'] : '');
$start_time = (set_value("start_time")) ? set_value("start_time") : (!empty($event_data) ? $event_data['start_time'] : '');
$end_time = (set_value("end_time")) ? set_value("end_time") : (!empty($event_data) ? $event_data['end_time'] : '');
$slot_time = (set_value("slot_time")) ? set_value("slot_time") : (!empty($event_data) ? $event_data['slot_time'] : '');
$per_allow = (set_value("per_allow")) ? set_value("per_allow") : (!empty($event_data) ? $event_data['monthly_allow'] : '');
$city = (set_value("city")) ? set_value("city") : (!empty($event_data) ? $event_data['city'] : '');
$total_seat = (set_value("total_seat")) ? set_value("total_seat") : (!empty($event_data) ? $event_data['total_seat'] : '');
$location = (set_value("location")) ? set_value("location") : (!empty($event_data) ? $event_data['location'] : '');
$price = (set_value("price")) ? set_value("price") : (!empty($event_data) ? $event_data['price'] : '1');
$discount = (set_value("discount")) ? set_value("discount") : (!empty($event_data) && $event_data['discount'] > 0 ? $event_data['discount'] : '');
$discounted_price = (set_value("discounted_price")) ? set_value("discounted_price") : (!empty($event_data) ? $event_data['discounted_price'] : '');
$from_date = (set_value("from_date")) ? set_value("from_date") : (!empty($event_data) && $event_data['from_date'] != '' && $event_data['from_date'] != '0000-00-00' ? get_formated_date($event_data['from_date'], '') : '');

$to_date = (set_value("to_date")) ? set_value("to_date") : (!empty($event_data) && $event_data['to_date'] != '' && $event_data['to_date'] != '0000-00-00' ? get_formated_date($event_data['to_date'], '') : '');
$start_date = (set_value("event_start_date")) ? set_value("event_start_date") : (!empty($event_data) && $event_data['start_date'] != '' && $event_data['start_date'] != '0000-00-00' ? date('m/d/Y H:i A', strtotime($event_data['start_date'])) : '');
$end_date = (set_value("event_end_date")) ? set_value("event_end_date") : (!empty($event_data) && $event_data['end_date'] != '' && $event_data['end_date'] != '0000-00-00' ? date('m/d/Y H:i A', strtotime($event_data['end_date'])) : '');
$payment_type = (set_value("payment_type")) ? set_value("payment_type") : (!empty($event_data) ? $event_data['payment_type'] : 'F');
$category_id = (set_value("category_id")) ? set_value("category_id") : (!empty($event_data) ? $event_data['category_id'] : '');
$status = (set_value("status")) ? set_value("status") : (!empty($event_data) ? $event_data['status'] : '');
$type = (set_value("event_type")) ? set_value("event_type") : (!empty($event_data) ? $event_data['type'] : '');

$event_limit_type = (set_value("event_limit_type")) ? set_value("event_limit_type") : (!empty($event_data) ? $event_data['event_limit_type'] : 'N');
$address = (set_value("address")) ? set_value("address") : (!empty($event_data) ? $event_data['address'] : '');
$address_map_link = (set_value("address_map_link")) ? set_value("address_map_link") : (!empty($event_data) ? $event_data['address_map_link'] : '');
$longitude = (set_value("longitude")) ? set_value("longitude") : (!empty($event_data) ? $event_data['longitude'] : '');
$latitude = (set_value("latitude")) ? set_value("latitude") : (!empty($event_data) ? $event_data['latitude'] : '');
$image_data = (!empty($event_data) ? $event_data['image'] : '');
$faq = (isset($event_data['faq']) && !empty($event_data['faq'])) ? json_decode($event_data['faq']) : array();
$seo_description = (set_value("seo_description")) ? set_value("seo_description") : (!empty($event_data) ? $event_data['seo_description'] : '');
$seo_keyword = (set_value("seo_keyword")) ? set_value("seo_keyword") : (!empty($event_data) ? $event_data['seo_keyword'] : '');
$seo_og_image = (set_value("seo_og_image")) ? set_value("seo_og_image") : (!empty($event_data) ? $event_data['seo_og_image'] : '');
if (isset($tag_data) && !empty($tag_data)) {
    foreach ($tag_data as $tag_val) {
        $tag[] = $tag_val['tag'];
    }
    $final_tag = implode(",", $tag);
}

if (isset($image_data) && $image_data != '') {
    $imageArr = json_decode($image_data, TRUE);
}
$id = (set_value("id")) ? set_value("id") : (!empty($event_data) ? $event_data['id'] : 0);
$sponser_id = (set_value("sid")) ? set_value("sid") : (!empty($event_data) ? $event_data['sid'] : 0);
?>

<link href="<?php echo $this->config->item('js_url'); ?>datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<input type="hidden" name="address_selection" id="address_selection" value="0">
<input type="hidden" name="event_sponser_id" id="event_sponser_id" value="<?php echo $sponser_id; ?>">
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <section class="form-light px-2 sm-margin-b-20 ">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>

                        <div class="header bg-color-base p-3">
                            <h3 class="black-text font-bold mb-0"><?php echo isset($id) && $id > 0 ? translate('update') : translate('add'); ?> <?php echo translate('event'); ?></h3>
                        </div>

                        <div class="card">
                            <div class="card-body resp_mx-0">
                                <?php
                                if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
                                    $form_url = 'vendor/save-event';
                                    $folder_name = 'vendor';
                                } else {
                                    $form_url = 'admin/save-event';
                                    $folder_name = 'admin';
                                }
                                ?>
                                <div class="steps-form-2">
                                    <div class="steps-row-2 setup-panel-2 d-flex justify-content-between">
                                        <div class="steps-step-2">
                                            <a href="#step-1" type="button" class="btn btn-amber waves-effect ml-0" data-toggle="tooltip" data-placement="top" title=" <?php echo translate('basic'); ?> <?php echo translate('information'); ?>">
                                                <?php echo translate('basic'); ?> <?php echo translate('information'); ?>
                                            </a>
                                        </div>
                                        <div class="steps-step-2">
                                            <a href="#step-description" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title=" <?php echo translate('description'); ?>">
                                                <?php echo translate('description'); ?>
                                            </a>
                                        </div>
                                        <div class="steps-step-2">
                                            <a href="#step-2" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title="<?php echo translate('Price'); ?>">
                                                <?php echo translate('ticket'); ?>
                                            </a>
                                        </div>
                                        <div class="steps-step-2">
                                            <a href="#step-3" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title="<?php echo translate('media'); ?>">
                                                <?php echo translate('media'); ?>
                                            </a>
                                        </div>
                                        <div class="steps-step-2">
                                            <a href="#step-4" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title="<?php echo translate('sponsor'); ?>">
                                                <?php echo translate('sponsor'); ?>
                                            </a>
                                        </div>
                                        <div class="steps-step-2">
                                            <a href="#step-5" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title="<?php echo translate('seo'); ?>">
                                                <?php echo translate('seo'); ?>
                                            </a>
                                        </div>
                                        <div class="steps-step-2">
                                            <a href="#step-6" type="button" class="btn btn-blue-grey waves-effect" data-toggle="tooltip" data-placement="top" title="<?php echo translate('faqs'); ?>">
                                                <?php echo translate('faqs'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                echo form_open_multipart($form_url, array('name' => 'EventForm', 'id' => 'EventForm'));
                                echo form_input(array('type' => 'hidden', 'name' => 'id', 'id' => 'id', 'value' => $id));
                                echo form_input(array('type' => 'hidden', 'name' => 'hidden_image', 'id' => 'hidden_image', 'value' => isset($image_data) && $image_data != '' ? $image_data : ''));
                                echo form_input(array('type' => 'hidden', 'name' => 'folder_name', 'id' => 'folder_name', 'value' => isset($folder_name) && $folder_name != '' ? $folder_name : ''));
                                echo form_input(array('type' => 'hidden', 'name' => 'event_latitude', 'id' => 'business_latitude', 'value' => isset($latitude) && $latitude != '' ? $latitude : ''));
                                echo form_input(array('type' => 'hidden', 'name' => 'event_longitude', 'id' => 'business_longitude', 'value' => isset($longitude) && $longitude != '' ? $longitude : ''));
                                echo form_input(array('type' => 'hidden', 'name' => 'event_starttime', 'id' => 'event_starttime', 'value' => isset($start_time) && $start_time != '' ? $start_time : ''));
                                echo form_input(array('type' => 'hidden', 'name' => 'event_stoptime', 'id' => 'event_stoptime', 'value' => isset($end_time) && $end_time != '' ? $end_time : ''));
                                ?>
                                <input type="hidden" name="event_latitude" id="business_latitude">
                                <input type="hidden" name="event_longitude" id="business_longitude">
                                <div class="row setup-content-2" id="step-1">
                                    <div class="col-md-12">
                                        <h3 class="font-bold pl-0 my-4"><strong><?php echo translate('event'); ?> <?php echo translate('information'); ?></strong></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name"> <?php echo translate('title'); ?><small class="required">*</small></label>
                                                    <input type="text" autocomplete="off" tabindex="1" id="name" name="name" value="<?php echo $name; ?>" class="form-control" placeholder="<?php echo translate('title'); ?>">                                    
                                                    <?php echo form_error('name'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="black-text"><?php echo translate('select'); ?> <?php echo translate('event_category'); ?><small class="required">*</small></label>
                                                    <select tabindex="7" class="kb-select initialized" id="days" name="category_id"> 
                                                        <option value=""><?php echo translate('select') . " " . translate('event_category'); ?></option>
                                                        <?php
                                                        if (isset($category_data) && count($category_data)) {
                                                            foreach ($category_data as $category_key => $category_value) {
                                                                ?>
                                                                <option value="<?php echo $category_value['id']; ?>" <?php echo isset($category_id) && $category_id == $category_value['id'] ? 'selected' : ''; ?>><?php echo $category_value['title']; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('category_id'); ?>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group" style="padding-top: <?php isset($id) && $id > 0 ? "10px" : ""; ?>;">
                                                    <label for="event_start_date"> <?php echo translate('from_date') ?></label>
                                                    <input tabindex="3"  type="text" required placeholder="<?php echo translate('from_date') ?>" id="event_start_date" name="event_start_date" value="<?php echo $start_date; ?>" class="form-control">                    
                                                    <?php echo form_error('event_start_date'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" style="padding-top: <?php isset($id) && $id > 0 ? "10px" : ""; ?>;">
                                                    <label for="event_end_date"> <?php echo translate('to_date') ?></label>
                                                    <input tabindex="4" type="text" required placeholder="<?php echo translate('to_date'); ?>" id="event_end_date" name="event_end_date" value="<?php echo $end_date; ?>" class="form-control">                                                           
                                                    <?php echo form_error('event_end_date'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="black-text"><?php echo translate('select_city'); ?><small class="required">*</small></label>
                                                    <select tabindex="5" class="kb-select initialized" id="city" name="city" onchange="get_location(this.value);"> 
                                                        <option value=""><?php echo translate('select_city'); ?></option>
                                                        <?php
                                                        if (isset($city_data) && count($city_data) > 0) {
                                                            foreach ($city_data as $value) {
                                                                ?>
                                                                <option value="<?php echo $value['city_id']; ?>" <?php echo isset($city) && $city == $value['city_id'] ? 'selected' : ''; ?>><?php echo $value['city_title']; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('city'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="black-text"><?php echo translate('select_location'); ?><small class="required">*</small></label>
                                                    <select tabindex="6" class="kb-select initialized" id="location" name="location"> 
                                                        <?php if (isset($location_data) && count($location_data) > 0) { ?>
                                                            <option value=""><?php echo translate('select_location'); ?></option>
                                                            <?php foreach ($location_data as $value) {
                                                                ?>
                                                                <option value="<?php echo $value['loc_id']; ?>" <?php echo isset($location) && $location == $value['loc_id'] ? 'selected' : ''; ?>><?php echo $value['loc_title']; ?></option>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <option value=""><?php echo translate('select_city_first'); ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                    <?php echo form_error('location'); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address"> <?php echo translate('venue'); ?><small class="required">*</small></label>
                                                    <input tabindex="8" autocomplete="off"  type="text" required="" name="address" class="form-control" placeholder="<?php echo translate('venue'); ?>" value="<?php echo $address; ?>"/>                     
                                                    <?php echo form_error('address'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address"> <?php echo translate('get_direction') . " " . translate('map') . " " . translate('link'); ?></label>
                                                    <input tabindex="8" autocomplete="off"  type="text" id="address_map_link" name="address_map_link" class="form-control" placeholder="<?php echo translate('map') . " " . translate('link'); ?>" value="<?php echo $address_map_link; ?>"/>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#map_modal" >Click Here For Details</a>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label style="color: #757575;" > <?php echo translate('status'); ?> <small class="required">*</small></label>
                                                <div class="form-group form-inline">
                                                    <?php
                                                    $active = $inactive = '';
                                                    if ($status == "I") {
                                                        $inactive = "checked";
                                                    } else {
                                                        $active = "checked";
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <input tabindex="12" name='status' value="A" type='radio' id='e_active'   <?php echo $active; ?>>
                                                        <label for="e_active"><?php echo translate('active'); ?></label>
                                                    </div>
                                                    <div class="form-group">
                                                        <input tabindex="13" name='status' type='radio'  value='I' id='e_inactive'  <?php echo $inactive; ?>>
                                                        <label for='e_inactive'><?php echo translate('inactive'); ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button"><?php echo translate('next'); ?></button>
                                    </div>
                                </div>

                                <div class="row setup-content-2" id="step-description">
                                    <div class="col-md-12">
                                        <h3 class="font-bold pl-0 my-4"><strong><?php echo translate('event'); ?> <?php echo translate('description'); ?></strong></h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description"> <?php echo translate('description'); ?><small class="required">*</small></label>
                                                    <textarea type="text" tabindex="3" id="summornote_div_id" name="description" class="form-control" placeholder="<?php echo translate('description'); ?>"><?php echo $description; ?></textarea>                              
                                                    <?php echo form_error('description'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button"><?php echo translate('previous'); ?></button>
                                        <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button"><?php echo translate('next'); ?></button>
                                    </div>
                                </div>
                                <div class="row setup-content-2" id="step-2">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3 class="font-bold" style="margin-top:0px;"><strong><?php echo translate('ticket') . " " . translate('information'); ?></strong></h3>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="pull-right btn blue-gradient waves-effect success-color float-right" onclick="add_more_ticket_type(this);"><i class="fa fa-plus-square-o mr-10"></i><?php echo translate('more') . " " . translate('ticket') . " " . translate('type'); ?></button>
                                            </div>
                                        </div>
                                        <div class="aler alert-info p-3" role="alert">
                                            You can't update ticket information of on going event or booked ticket. 
                                        </div>

                                        <div id="ticket_section_id">
                                            <?php
                                            if (isset($app_event_ticket_type) && count($app_event_ticket_type)):
                                                $i = 0;
                                                ?>
                                                <?php
                                                foreach ($app_event_ticket_type as $vals):
                                                    $i++;
                                                    ?>
                                                    <div class="row">
                                                        <input type="hidden" name="ticket_type_id[]" value="<?php echo $vals['ticket_type_id']; ?>"/>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="ticket_title"> <?php echo translate('title') ?></label>
                                                                <input autocomplete="off"  type="text" required="" maxlength="100" placeholder="e.g Early Bird, VIP, Child" id="ticket_title" name="ticket_title[]" value="<?php echo $vals['ticket_type_title']; ?>" class="form-control">                                    
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="ticket_amount"> <?php echo translate('number_of_ticket') ?></label>
                                                                <input autocomplete="off"  type="number" required=""  placeholder="<?php echo translate('number_of_ticket'); ?>" id="ticket_amount" name="ticket_amount[]" value="<?php echo $vals['total_seat']; ?>" class="form-control">                                    
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="ticket_price"> <?php echo translate('price') ?></label>
                                                                <input autocomplete="off"  type="number" required=""  placeholder="<?php echo translate('price'); ?>" id="ticket_price" name="ticket_price[]" value="<?php echo $vals['ticket_type_price']; ?>" class="form-control">                                    
                                                            </div>
                                                        </div>
                                                        <?php if ($i > 1): ?>
                                                            <div class="col-md-2">
                                                                <div class="form-group"><br/>
                                                                    <button type="button" class="btn btn-danger btn-sm" data-id="<?php echo $vals['ticket_type_id']; ?>" onclick="delete_ticket_type(this);"><i class="fa fa-trash mr-10"></i></button>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="ticket_title"> <?php echo translate('title') ?></label>
                                                            <input autocomplete="off"  type="text" required="" maxlength="100" placeholder="e.g Early Bird, VIP, Child" id="ticket_title" name="ticket_title[]" value="" class="form-control">                                    
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="ticket_amount"> <?php echo translate('number_of_ticket') ?></label>
                                                            <input  autocomplete="off" type="number" required=""  placeholder="<?php echo translate('number_of_ticket'); ?>" id="ticket_amount" name="ticket_amount[]" value="" class="form-control">                                    
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="ticket_price"> <?php echo translate('price') ?></label>
                                                            <input  autocomplete="off" type="number" required=""  placeholder="<?php echo translate('price'); ?>" id="ticket_price" name="ticket_price[]" value="" class="form-control">                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button"><?php echo translate('previous'); ?></button>
                                        <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button"><?php echo translate('next'); ?></button>
                                    </div>
                                </div>
                                <div class="row setup-content-2" id="step-3">
                                    <div class="col-md-12">
                                        <h3 class="font-bold pl-0 my-4"><strong><?php echo translate('media'); ?> <?php echo translate('information'); ?></strong></h3>
                                        <div class="row">
                                            <?php if (isset($imageArr) && count($imageArr) > 0) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="image"> <?php echo translate('event_image_preview'); ?></label><br>
                                                        <ul class="list-inline inline-ul" id="images_ul">
                                                            <?php
                                                            foreach ($imageArr as $value) {
                                                                ?>
                                                                <li class="hover-btn">
                                                                    <img src = "<?php echo check_admin_image(UPLOAD_PATH . "event/" . $value); ?>" class = "img-thumbnail mr-10 mb-10 height-100" width = "100px"/>
                                                                    <a class="btn-danger btn-floating btn-sm red-gradient waves-effect waves-light remove-btn" onclick="delete_event_image(this);" data-url="<?php echo UPLOAD_PATH . "event/" . $value; ?>" data-id="<?php echo $id; ?>"><i class="fa fa-trash"></i></a>
                                                                </li>
                                                            <?php }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-6">
                                                <div class="form-group" id="image-data">
                                                    <label for="image"> <?php echo translate('event_image'); ?> <small class="required">*</small></label><button type="button" class="btn blue-gradient waves-effect success-color btn-sm float-right" onclick="get_more_image(this);"><i class="fa fa-plus-square-o mr-10"></i><?php echo translate('more'); ?></button>
                                                    <input tabindex="23" type="file" id="image" name="image[]" class="form-control" <?php echo isset($image_data) && $image_data != '' ? '' : 'required'; ?>>                                    
                                                    <?php echo form_error('image'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button"><?php echo translate('previous'); ?></button>
                                        <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button"><?php echo translate('next'); ?></button>

                                    </div>
                                </div>
                                <div class="row setup-content-2" id="step-4">
                                    <div class="col-md-12">
                                        <h3 class="font-bold pl-0 my-4"><strong><?php echo translate('sponsor'); ?> <?php echo translate('information'); ?></strong></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sponsor_company"> <?php echo translate('compnay') . " " . translate('name'); ?> </label>
                                                    <input autocomplete="off"  onblur="check_valid_image(this);" tabindex="25" type="text" id="sponsor_company"  name="sponsor_company" class="form-control" value="<?php echo isset($event_data['sponsor_name']) ? $event_data['sponsor_name'] : ''; ?>">                                    
                                                    <?php echo form_error('sponsor_company'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sponsor_website"> <?php echo translate('website') . " " . translate('link'); ?></label>
                                                    <input tabindex="26" type="text" placeholder="Ex : http:://www.example.com" id="sponsor_website"  name="sponsor_website" class="form-control" value="<?php echo isset($event_data['website_link']) ? $event_data['website_link'] : ''; ?>">                                    
                                                    <?php echo form_error('image'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group" id="image-data">
                                                    <?php
                                                    $sponsor_img_src = base_url() . img_path . '/default_sponsor.jpg';
                                                    if (isset($event_data['sponsor_image']) != '' && file_exists(FCPATH . uploads_path . '/event/' . $event_data['sponsor_image'])) {
                                                        $sponsor_img_src = base_url() . uploads_path . '/event/' . $event_data['sponsor_image'];
                                                    }
                                                    ?>
                                                    <input type="hidden" id="sponsor_old_image" name="sponsor_old_image" value="<?php echo isset($event_data['sponsor_image']) ? $event_data['sponsor_image'] : ''; ?>">
                                                    <label for="sponsor_image"> <?php echo translate('sponsor') . " " . translate('image'); ?> </label>
                                                    <input tabindex="27" type="file" id="sponsor_image" name="sponsor_image" class="form-control" >                                    
                                                    <?php echo form_error('sponsor_image'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?php if (isset($event_data['sponsor_image']) && $event_data['sponsor_image'] != '') { ?>
                                                        <img src="<?php echo $sponsor_img_src; ?>" height="100" width="100" >
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button"><?php echo translate('previous'); ?></button>
                                        <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button"><?php echo translate('next'); ?></button>

                                    </div>
                                </div>
                                <div class="row setup-content-2" id="step-5">
                                    <div class="col-md-12">
                                        <h3 class="font-bold pl-0 my-4"><strong><?php echo translate('seo'); ?> <?php echo translate('information'); ?></strong></h3>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="seo_keyword"> <?php echo translate('seo_keyword') ?></label>
                                                    <input autocomplete="off"  tabindex="28" type="text" placeholder="<?php echo translate('seo_keyword'); ?>" id="seo_keyword" name="seo_keyword" value="<?php echo $seo_keyword; ?>" class="form-control">                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="seo_description"> <?php echo translate('seo_description'); ?></label>
                                                    <textarea tabindex="29" type="text" id="seo_description" name="seo_description" class="form-control" placeholder="<?php echo translate('seo_description'); ?>"><?php echo $seo_description; ?></textarea>                              
                                                    <?php echo form_error('seo_description'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="image-data">
                                                    <label for="image"> <?php echo translate('seo_og_image'); ?></label>
                                                    <input tabindex="30" type="file" id="image" name="seo_og_image" class="form-control">                                    
                                                </div>
                                            </div>
                                            <?php if ($seo_og_image != '') { ?>
                                                <div class="col-md-6">
                                                    <ul class="list-inline inline-ul" id="images_ul">
                                                        <li class="hover-btn">
                                                            <img src = "<?php echo check_admin_image(UPLOAD_PATH . "event/" . $seo_og_image); ?>" class = "img-thumbnail mr-10 mb-10 height-100" width = "100px"/>
                                                            <a class="btn-danger btn-floating btn-sm red-gradient waves-effect waves-light remove-btn" onclick="delete_event_seo_image(this);" data-url="<?php echo UPLOAD_PATH . "event/" . $seo_og_image; ?>" data-id="<?php echo $id; ?>"><i class="fa fa-trash"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button"><?php echo translate('previous'); ?></button>
                                        <button class="btn btn-kb-color btn-rounded nextBtn-2 float-right" type="button"><?php echo translate('next'); ?></button>

                                    </div>
                                </div>
                                <div class="row setup-content-2" id="step-6">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3 class="font-bold" style="margin-top:0px;"><strong><?php echo translate('faqs'); ?></strong></h3>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="pull-right btn blue-gradient waves-effect success-color btn-sm float-right" onclick="add_more_faq(this);"><i class="fa fa-plus-square-o mr-10"></i><?php echo translate('more'); ?></button>
                                            </div>
                                        </div>
                                        <div id="faq_section_id">
                                            <?php
                                            if (count($faq) && !empty($faq)):
                                                $i = 0;
                                                ?>
                                                <?php
                                                foreach ($faq as $val):
                                                    $i++;
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label for="faq_title"> <?php echo translate('title') ?></label>
                                                                <input  autocomplete="off" tabindex="" type="text" placeholder="<?php echo translate('title'); ?>" id="seo_keyword" name="faq_title[]" value="<?php echo trim($val->faq_title); ?>" class="form-control">                                    
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label for="faq_description"> <?php echo translate('description'); ?></label>
                                                                <textarea tabindex="" type="text" id="seo_description" name="faq_description[]" class="form-control" placeholder="<?php echo translate('description'); ?>"><?php echo trim($val->faq_description); ?></textarea>                              
                                                                <?php echo form_error('faq_description'); ?>
                                                            </div>
                                                        </div>
                                                        <?php if ($i > 1) : ?>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="remove">&nbsp;</label>
                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="remove_add_more(this);"><i class="fa fa-trash mr-10"></i></button>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="faq_title"> <?php echo translate('title') ?></label>
                                                            <input autocomplete="off" type="text" placeholder="<?php echo translate('title'); ?>" id="seo_keyword" name="faq_title[]" value="" class="form-control">                                    
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="faq_description"> <?php echo translate('description'); ?></label>
                                                            <textarea  type="text" id="seo_description" name="faq_description[]" class="form-control" placeholder="<?php echo translate('description'); ?>"></textarea>                              
                                                            <?php echo form_error('faq_description'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="btn btn-kb-color btn-rounded prevBtn-2 float-left" type="button"><?php echo translate('previous'); ?></button>
                                        <button class="btn btn-success btn-rounded float-right" type="submit"><?php echo translate('submit'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            <!--/Form with header-->
                        </div>
                        <!--Card-->
                    </div>
                    <!-- End Col -->
                </div>
                <!--Row-->
            </section>
            <!-- End Login-->
        </div>
    </div>
</div>
<div class="modal fade" id="map_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id='some_name' class="modal-title" style="font-size: 18px;">Share a map or location</h4>
            </div>
            <div class="modal-body">
                <ol>
                    <li>On your computer, open <a href="https://www.google.com/maps" target="_blank" rel="noopener">Google Maps</a>.</li>
                    <li>On the top left, click Menu <img src="//lh5.ggpht.com/gnm-ty6mnE6vkedDflD8UzuuSYpoeaGMx1Am3m0zH0OkEAkqv3jGJV3cnjkqH75mrrqn=w18-h18" width="18" height="18" alt="Menu" title="Menu">.</li>
                    <li>Select <strong>Share or embed map</strong></li>
                    <li>Copy and paste the "<strong>Link to share</strong>"</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo translate('close'); ?></button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
    function add_more_faq(e) {
        var html = '';
        html += '<div class="row">';
        html += '<div class="col-md-5">';
        html += '<div class="form-group">';
        html += '<label for="faq_title"> <?php echo translate('title') ?></label>';
        html += '<input  type="text" placeholder="<?php echo translate('title'); ?>" id="seo_keyword" name="faq_title[]" value="" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-5">';
        html += '<div class="form-group">';
        html += '<label for="faq_description"> <?php echo translate('description'); ?></label>';
        html += '<textarea type="text" id="seo_description" name="faq_description[]" class="form-control" placeholder="<?php echo translate('description'); ?>"></textarea>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group"><br/>';
        html += '<button type="button" class="btn btn-danger btn-sm" onclick="remove_add_more(this);"><i class="fa fa-trash mr-10"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $("#faq_section_id").append(html);
    }
    function add_more_ticket_type(e) {
        var html = '';
        html += '<div class="row">';
        html += '<div class="col-md-3">';
        html += '<div class="form-group">';
        html += '<label for="ticket_title"> <?php echo translate('title') ?></label>';
        html += '<input type="text" required="" maxlength="100" placeholder="e.g Early Bird, VIP, Child" id="ticket_title" name="ticket_title[]" value="" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-3">';
        html += '<div class="form-group">';
        html += '<label for="ticket_amount"> <?php echo translate('number_of_ticket') ?></label>';
        html += '<input type="number" required=""  placeholder="<?php echo translate('number_of_ticket'); ?>" id="ticket_amount" name="ticket_amount[]" value="" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-3">';
        html += '<div class="form-group">';
        html += '<label for="ticket_price"> <?php echo translate('price') ?></label>';
        html += '<input type="number" required=""  placeholder="<?php echo translate('price'); ?>" id="ticket_price" name="ticket_price[]" value="" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<div class="form-group"><br/>';
        html += '<button type="button" class="btn btn-danger btn-sm" onclick="remove_add_more(this);"><i class="fa fa-trash mr-10"></i></button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $("#ticket_section_id").append(html);
    }
    function remove_add_more($this) {
        $($this).closest('.row').remove();
    }

    function delete_ticket_type($this) {
        var r = confirm("Are you sure? You want to delete this!");
        if (r == true) {
            var folder_name = $('#folder_name').val();
            var id = $($this).data('id');
            $.ajax({
                url: site_url + folder_name + "/delete-ticket-type/",
                type: "post",
                data: {token_id: csrf_token_name, ticket_type_id: id},
                success: function (data) {
                    if (data == true) {
                        $($this).closest('.row').remove();
                        toastr.success("Ticket type deleted successfully.");
                    } else {
                        toastr.error("You are not allowed to delete this ticket type.");
                    }
                }
            });
        }
    }
</script>

<script src="<?php echo $this->config->item('js_url'); ?>datetimepicker/moment.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item('js_url'); ?>datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/event.js" type="text/javascript"></script>
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>
<?php if ($id > 0): ?>
    <script>
        $(document).ready(function (e) {
    // set validation for event start & end date
            var event_start = new Date();
            var event_end = new Date(new Date().setYear(event_start.getFullYear() + 1));

            if ($('#event_start_date').length > 0) {
                $('#event_start_date').datetimepicker({
                    format: 'MM/DD/YYYY H:mm',
                }).on('dp.change', function (e) {
                    //$('#event_end_date').datetimepicker('minDate', new Date($(this).val()));
                });
            }

            if ($('#event_end_date').length > 0) {
                $('#event_end_date').datetimepicker({
                    format: 'MM/DD/YYYY H:mm',
                }).on('dp.change', function () {
                    //$('#event_start_date').datetimepicker('maxDate', new Date($(this).val()));
                });
            }
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function (e) {
            // set validation for event start & end date
            var event_start = new Date();
            var event_end = new Date(new Date().setYear(event_start.getFullYear() + 1));
            if ($('#event_start_date').length > 0) {
                $('#event_start_date').datetimepicker({
                    minDate: event_start,
                    maxDate: event_end,
                    format: 'MM/DD/YYYY H:mm',
                }).on('dp.change', function () {
                    //$('#event_end_date').datetimepicker('minDate', new Date($(this).val()));
                });
            }

            if ($('#event_end_date').length > 0) {
                $('#event_end_date').datetimepicker({
                    minDate: event_start,
                    maxDate: event_end,
                    format: 'MM/DD/YYYY H:mm',
                }).on('dp.change', function () {
                    // $('#event_start_date').datetimepicker('maxDate', new Date($(this).val()));
                });
            }
        });
    </script>
<?php endif; ?>
