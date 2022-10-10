<?php
include VIEWPATH . 'front/header.php';
?>
<input type="hidden" name="chat_id" id="chat_id" value="<?php echo isset($chat_id) ? $chat_id : 0; ?>"/>
<link href="<?php echo $this->config->item('css_url'); ?>module/chat-box.css" rel="stylesheet" type="text/css"/>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid container-min-height">
            <section class="form-light">
                <!-- Row -->
                <div class="row mx-0">
                    <?php
                    $this->load->view('message');
                    if (isset($vendor_list) && count($vendor_list) > 0) {
                        ?>
                        <div class="d-block d-sm-none">
                            <div class="open-nav">
                                <i class="la la-bars"></i>
                            </div>
                        </div>


                        <div class="col-md-3 msg-list chat-list-box border border-right-0">
                            <div class="chat-box-wapper">
                                <div class="chatbox-title">
                                    <p><i class="la la-comments-o"></i>
                                        <?php echo translate('Chats'); ?></p>

                                    <div class="d-block d-sm-none close_chat-box">
                                        <div class="close">
                                            <i class="la la-close"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdl-data-table">
                                    <?php
                                    if (isset($vendor_list) && count($vendor_list) > 0) {
                                        foreach ($vendor_list as $vlist_key => $vlist_value) {
                                            $unread = check_unread_msg($vlist_value['id'], $vlist_value['vendor_id'], $vlist_value['customer_id']);
                                            if (isset($msg_vendor_data) && !empty($msg_vendor_data)):
                                                ?>

                                                <div class="chat-box-cust <?php echo isset($msg_vendor_data) && $vlist_value['vendor_id'] == $msg_vendor_data[0]['id'] ? 'active' : ''; ?>"> 
                                                    <a href="<?php echo base_url('message/' . $vlist_value['vendor_id']); ?>" style="display: inline-block;width: 100%;">
                                                        <img class="rounded-circle position-r" src="<?php echo check_admin_image(UPLOAD_PATH . "profiles/" . $vlist_value['profile_image']); ?>">
                                                        <span class="user-list-text"><?php echo ($vlist_value['first_name']) . " " . $vlist_value['last_name']; ?></span>   
                                                        <?php if ($unread > 0) { ?>
                                                            <div class="un-read"><?php echo $unread; ?></div>
                                                        <?php } ?>
                                                    </a>
                                                </div>
                                                <?php
                                            else:
                                                ?>
                                                <h2 class="no-found"><?php echo translate('no_found'); ?></h2>
                                            <?php
                                            endif;
                                        }
                                        ?>
                                    <?php } else { ?>
                                        <h2 class="no-found"><?php echo translate('no_found'); ?></h2>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($msg_vendor_data) && count($msg_vendor_data) > 0) { ?>
                            <div class="col-md-6 chating_list white">
                                <div class="header pt-3">
                                    <h3 class="mb-3 font-bold pl-10"> 
                                        <img class="rounded-circle" src="<?php echo check_admin_image(UPLOAD_PATH . "profiles/" . $msg_vendor_data[0]['profile_image']); ?>">
                                        <span class="user-text"><?php echo ($msg_vendor_data[0]['first_name']) . " " . ($msg_vendor_data[0]['last_name']); ?></span>                               
                                    </h3>
                                </div>
                                <div class="frame">
                                    <form action="<?php echo base_url('message-action'); ?>" name="chat_form" id="chat_form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                        <ul class="list-inline px-40 chating_block" id="scroll-auto">
                                            <?php
                                            if (isset($msg_group_list) && count($msg_group_list) > 0) {
                                                foreach ($msg_group_list as $group_value) {
                                                    $get_message = get_message($group_value['date'], $group_value['chat_id']);
                                                    $last_chat_message_id = $get_message[count($get_message) - 1]['id'];
                                                    $this->session->set_userdata('last_chat_message_id_' . $chat_id, $last_chat_message_id);
                                                    if (isset($get_message) && count($get_message) > 0) {
                                                        ?>
                                                        <li class="text-center title_border"><small class="alert alert-info chat-date"><?php echo date('F d ,Y', strtotime($group_value['date'])); ?></small></li>
                                                        <?php
                                                        foreach ($get_message as $msg_value) {
                                                            if ($msg_vendor_data[0]['id'] == $msg_value['to_id'] && $msg_value['chat_type'] == 'C') {
                                                                $deliver_check_icon = ($msg_value['msg_read'] == 'Y') ? "<i class='fa pl-10 fa-check-circle text-info'></i>" : "<i class='fa pl-10 fa-check'></i>";
                                                                if ($msg_value['profile_image'] != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $msg_value['profile_image'])) {
                                                                    $avatar_img = base_url() . "assets/uploads/profiles/" . $msg_value['profile_image'];
                                                                } else {
                                                                    $avatar_img = base_url() . "assets/images/user.png";
                                                                }
                                                                ?>
                                                                <li class="by_current_user text-right">
                                                                    <p class="message_content">
                                                                        <span class="chat-message">
                                                                            <?php echo $msg_value['message']; ?>
                                                                        </span>
                                                                        <img class="rounded-circle" src="<?php echo $avatar_img; ?>" /> 
                                                                    </p>
                                                                    <span class="chat_message_header"><i><?php echo $msg_value['timestamp']; ?> by <?php echo $msg_value['first_name']; ?></i>
                                                                        <?php echo $deliver_check_icon; ?>
                                                                    </span>
                                                                </li>
                                                                <?php
                                                            } else {
                                                                if ($msg_value['aprofile_image'] != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $msg_value['aprofile_image'])) {
                                                                    $avatar_img = base_url() . "assets/uploads/profiles/" . $msg_value['aprofile_image'];
                                                                } else {
                                                                    $avatar_img = base_url() . "assets/images/user.png";
                                                                }
                                                                ?>
                                                                <li class="get_current_user">
                                                                    <p class="message_content">
                                                                        <img class="rounded-circle" src="<?php echo $avatar_img; ?>" />
                                                                        <span class="chat-message">
                                                                            <?php echo $msg_value['message']; ?>
                                                                        </span>
                                                                    </p>
                                                                    <span class="chat_message_header"><i><?php echo $msg_value['timestamp']; ?> by <?php echo $msg_value['aname']; ?></i>
                                                                    </span>
                                                                </li>

                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </ul>
                                        <div class="input-type-box">
                                            <input name="msg_to_id" value="<?php echo $msg_vendor_data[0]['id']; ?>" id="msg_to_id" type="hidden">
                                            <div class="macro px-3 chat-box">
                                                <div class="form-group mb-0">
                                                    <input class="form-control d-inline-block w-86" id="message" name="message"  placeholder="Type a message.." autofocus/>
                                                    <button type="button" class="btn m-0 btn-sm pull-right" onclick="send_message();"><i class="fa fa-paper-plane-o" ></i></button>                                         
                                                </div>
                                            </div>
                                        </div>
                                    </form>                            
                                </div>
                            </div>
                            <?php
                        }

                        if (isset($admin_details) && !empty($admin_details)) {
                            $admin_img = base_url() . "assets/images/user.png";
                            foreach ($admin_details as $aRow) {
                                if ($aRow['profile_image'] != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $aRow['profile_image'])) {
                                    $admin_img = base_url() . "assets/uploads/profiles/" . $aRow['profile_image'];
                                }
                                $admin_full_name = ($aRow['first_name']) . " " . ($aRow['last_name']);
                                $admin_email = $aRow['email'];
                                $admin_company = ($aRow['company_name']);
                                $admin_address = ($aRow['address']);
                                $admin_phone = ($aRow['phone']);
                                $fb_link = ($aRow['fb_link']);
                                $twitter_link = ($aRow['twitter_link']);
                                $google_link = ($aRow['google_link']);
                                $instagram_link = ($aRow['instagram_link']);
                            }
                            ?>
                            <div class="col-md-3 d-none d-sm-inline-block border border-left-0 white">
                                <div class="card-body px-0">
                                    <div class="customer-profile-details">
                                        <div class="cus-img">
                                            <img src="<?php echo $admin_img; ?>" alt="customer img" class="img-fluid" />
                                        </div>
                                        <p class="cust-name"><?php echo isset($admin_full_name) ? $admin_full_name : '' ?></p>
                                        <p><?php echo isset($admin_company) ? $admin_company : ""; ?></p>

                                        <ul class="list-inline add-icon">
                                            <?php if (isset($admin_address) && $admin_address != '') : ?>
                                                <li>
                                                    <span class="ico-text">
                                                        <i class="la la-map-marker grey-icon rounded-circle"></i>
                                                        <p><?php echo $admin_address; ?></p>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (isset($admin_email) && $admin_email != '') : ?>
                                                <li>
                                                    <span class="ico-text">
                                                        <i class="la la-envelope grey-icon rounded-circle"></i>
                                                        <p> <?php echo $admin_email; ?></p>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (isset($admin_phone) && $admin_phone != '') : ?>
                                                <li>
                                                    <span class="ico-text">
                                                        <i class="la la-phone grey-icon rounded-circle"></i>
                                                        <p><?php echo $admin_phone; ?></p>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                        <ul class="list-inline inline-ul social-msg-icon">
                                            <?php if (isset($fb_link) && $fb_link != '') : ?>
                                                <li>
                                                    <a href="<?php echo $fb_link; ?>" class="ico-text btn-fb">
                                                        <i class="fa fa-facebook grey-icon rounded-circle"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (isset($twitter_link) && $twitter_link != '') : ?>
                                                <li>
                                                    <a href="<?php echo $twitter_link; ?>" class="ico-text btn-tw">
                                                        <i class="la la-twitter grey-icon rounded-circle"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (isset($google_link) && $google_link != '') : ?>
                                                <li>
                                                    <a href="<?php echo $google_link; ?>" class="ico-text btn-gplus">
                                                        <i class="la la-google-plus grey-icon rounded-circle"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (isset($instagram_link) && $instagram_link != '') : ?>
                                                <li>
                                                    <a href="<?php echo $instagram_link; ?>" class="ico-text btn-ins">
                                                        <i class="la la-instagram grey-icon rounded-circle"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }else {
                        ?>

                        <h2 class="alert alert-info col-xl-12 text-center"><?php echo translate('no_chat_available'); ?></h2>

                        <?php
                    }
                    ?>
                </div>
                <!--Row-->
            </section>
        </div>
    </div>   
</div>
<script>
    var interval_time = '<?php echo CHAT_INTERVAL_TIME; ?>';
    var interval_time_ms = interval_time * 1000;
    $(document).ready(function () {
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }

        if (!Notification) {
            alert('Desktop notifications not available in your browser. Try Chromium.');
            return;
        }

        setInterval(function () {
            get_chats_messages();
        }, interval_time_ms);
    });


    $('#chat_form').submit(function () {
        if ($('#chat_form').valid()) {
            $('.loadingmessage').show();
        }
    });
    window.onload = function () {
        get_chats_messages();
        var objDiv = document.getElementById("scroll-auto");
        objDiv.scrollTop = objDiv.scrollHeight;
    }
    function send_message() {
        last_msg_html = '';
        var msg_to_id = $("#msg_to_id").val();
        var message = $("#message").val();
        $("#message").attr("required", true);
        if (message != '') {
            $.ajax({
                url: base_url + "front/send_message",
                type: "post",
                data: {token_id: csrf_token_name, msg_to_id: msg_to_id, message: message},
                beforeSend: function () {
                    //                    $('#loadingmessage').show();
                },
                success: function (ResponseJson) {
                    var data = JSON.parse(ResponseJson);
                    if (data.status == 'ok') {

                        var current_content = $("#scroll-auto").html();

                        $("#scroll-auto").html(current_content + data.content);

                        /* Scroll each time you submit new message */
                        $('#scroll-auto').scrollTop($('#scroll-auto')[0].scrollHeight);
                        $("#message").attr("required", false);
                        $("#message").val("");

                    } else {
                        /* Error here */                     }
                }
            });

        }
    }
    $("#message").keypress(function (e) {
        if (e.which == 13) {
            send_message();
            e.preventDefault();
        }
    });

    function get_chats_messages()
    {
        var chat_id = $("#chat_id").val();
        $.post(base_url + "front/ajax_get_chats_messages", {chat_id: chat_id}, function (data) {
            /* Condition */
            if (data.status == 'ok') {
                var current_content = $("#scroll-auto").html();
                $("#scroll-auto").html(current_content + data.content);
                /* Scroll each time you get new message */
                $('#scroll-auto').scrollTop($('#scroll-auto')[0].scrollHeight);
            } else {
                /* Error here */
            }
        }, "json");

        return false;
    }

    get_chats_messages();
    $('#chat_form').submit(function () {
        if ($('#chat_form').valid()) {
            $('.loadingmessage').show();
        }
    });
    window.onload = function () {
        var objDiv = document.getElementById("scroll-auto");
        objDiv.scrollTop = objDiv.scrollHeight;
    }

    //Responsive Sidebar
    $(document).ready(function () {
        var open = $('.open-nav'),
                close = $('.close'),
                overlay = $('.overlay');

        open.click(function () {
            overlay.show();
            $('.form-light').addClass('toggled');
        });

        close.click(function () {
            overlay.hide();
            $('.form-light').removeClass('toggled');
        });
    });
</script>
<?php include VIEWPATH . 'front/footer.php'; ?>
