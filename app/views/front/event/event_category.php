<?php
//echo "<pre>";
//print_r($event_data);exit;
$select_City = $this->session->userdata('location');
include VIEWPATH . 'front/header.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"/>

<div class="container">
    <div class="pt-3">
        <?php $this->load->view('message'); ?>        
    </div>


    <div class="row">
        <div class="col-md-9 event-filter">
            <h3 class="mt-20 event-title">UPCOMING <?php echo strtoupper($category_data['title']); ?> EVENTS IN <?php echo strtoupper($select_City); ?></h3>

            <div class="event_list_view">
                <h6 class="label_title">
                    <span>All</span>
                </h6>
            </div>

            <div class="event_list_content">
                <?php
                foreach ($event_data as $val):
                    $event_img_file = '';
                    $event_img_Arr = json_decode($val['image']);
                    if (isset($event_img_Arr) && !empty($event_img_Arr)) {
                        $event_img = isset($event_img_Arr[0]) ? $event_img_Arr[0] : '';
                        if ($event_img != '') {

                            $original_filename = (pathinfo($event_img, PATHINFO_FILENAME));
                            $original_extension = (pathinfo($event_img, PATHINFO_EXTENSION));
                            $event_img_file = $original_filename . "_thumb" . "." . $original_extension;
                        }
                    }
                    if (file_exists(FCPATH . "assets/uploads/event/" . $event_img_file)) {
                        $img_src = base_url() . UPLOAD_PATH . "event/" . $event_img_file;
                    } else {
                        $img_src = base_url() . UPLOAD_PATH . "event/events.png";
                    }
                    ?>
                    <div class="event-item">
                        <div class="event-body row">
                            <div class="col-lg-8">
                                <a href="#" class="event-img">
                                    <img class="img-fluid" src="<?php echo $img_src; ?>" alt="category img">
                                </a>
                                <div class="event-info">
                                    <h3><a href="#"><?php echo $val['title']; ?></a> </h3>
                                    <p><i class="fa fa-map-marker"></i><span> <?php echo $val['city']; ?></span></p>
                                </div>
                            </div>
                            <div class="col-lg-4 pr-0 event-date-time">
                                <span class="time">
                                    <i class="fa fa-clock-o"></i>
                                    Fri Jan 04 2019 at 01:00 am
                                    <br/>to 
                                    Thu Jan 31 2019 at 07:00 pm
                                </span>

                                <span class="badge badge-light black-text">On-going</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- 
                        <div class="event_list_view">
                            <h6 class="label_title">
                                <span>Tomorrow</span>
                            </h6>
                        </div>
            
                       <div class="event_list_content">
                            <div class="event-item">
                                <div class="event-body row">
                                    <div class="col-lg-8">
                                        <a href="#" class="event-img">
                                            <img class="img-fluid" src="<?php echo base_url(img_path . '/default_slider.jpg'); ?>" alt="category img">
                                        </a>
            
                                        <div class="event-info">
                                            <h3><a href="#">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </a> </h3>
                                            <p><i class="fa fa-map-marker"></i><span> Gujarat 395003, India, Surat</span></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 pr-0 event-date-time">
                                        <span class="time">
                                            <i class="fa fa-clock-o"></i>
                                            Fri Jan 04 2019 at 01:00 am
                                            <br />to 
                                            Thu Jan 31 2019 at 07:00 pm
                                        </span>
            
                                        <span class="badge badge-light black-text">On-going</span>
                                    </div>
                                </div>
                            </div>
                            <div class="event-item">
                                <div class="event-body row">
                                    <div class="col-lg-8">
                                        <a href="#" class="event-img">
                                            <img class="img-fluid" src="<?php echo base_url(img_path . '/default_slider.jpg'); ?>" alt="category img">
                                        </a>
            
                                        <div class="event-info">
                                            <h3><a href="#">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </a> </h3>
                                            <p><i class="fa fa-map-marker"></i><span> Gujarat 395003, India, Surat</span></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 pr-0 event-date-time">
                                        <span class="time">
                                            <i class="fa fa-clock-o"></i>
                                            Fri Jan 04 2019 at 01:00 am
                                            <br />to 
                                            Thu Jan 31 2019 at 07:00 pm
                                        </span>
            
                                        <span class="badge badge-light black-text">On-going</span>
                                    </div>
                                </div>
                            </div>
                        </div>-->
        </div>
        <div class="col-md-3">
            <p class="filter-title">Filters :</p>
            <div class="date_filter">
                <h4><i class="fa fa-calendar"></i>Date</h4>
                <ul class="list-inline">
                    <li class="active"><a href="#">All</a></li>
                    <li><a href="#">Tomorrow</a></li>
                    <li><a href="#">Yesterday</a></li>
                    <li><a href="#">This Weekend</a></li>
                    <li><a href="javascript:void(0)" id="datepicker">Specific Date</a></li>
                </ul>
            </div>
            <div class="category_filter">
                <h4><i class="fa fa-list"></i>Category</h4>
                <ul class="list-inline">
                    <?php foreach ($events_category as $val): ?>
                        <li class="<?php echo ($val['id'] == $category_data['id']) ? "active" : ""; ?>"><a href="<?php echo base_url('event-category/' . trim(slugify($val['title'])) . '/' . $val['id']); ?>"><?php echo $val['title']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script>
    $(function () {
        $("#datepicker").datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker('update', new Date());
    });

</script>
<?php include VIEWPATH . 'front/footer.php'; ?>