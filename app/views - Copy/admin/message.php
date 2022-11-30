<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    $login_id = $this->session->userdata('Vendor_ID');
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    $login_id = $this->session->userdata('ADMIN_ID');
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
?>
<link href="<?php echo $this->config->item('css_url'); ?>module/chat-box.css" rel="stylesheet" type="text/css"/>
<input id="folder_name" name="folder_name" type="hidden" value="<?php echo isset($folder_name) && $folder_name != '' ? $folder_name : ''; ?>"/>
<input type="hidden" name="chat_id" id="chat_id" value="<?php echo isset($chat_id) ? $chat_id : 0; ?>"/>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container">
            <section class="form-light">
                <!-- Row -->
                <div class="row mt-3 mx-0">
                    <div class="d-block d-sm-none">
                        <div class="open-nav">
                            <i class="la la-bars"></i>
                        </div>
                    </div>
                    <?php $this->load->view('message'); ?>
                    <div class="col-md-4 msg-list chat-list-box border border-right-0">
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
                                if (isset($customer_list) && count($customer_list) > 0) {
                                    foreach ($customer_list as $clist_value) {
                                        $unread = get_unread_msg($clist_value['id'], $login_id, $clist_value['customer_id']);
                                        ?>
                                        <div class="chat-box-cust <?php echo isset($msg_customer_data) && $clist_value['vendor_id'] == $msg_customer_data[0]['id'] ? 'active' : ''; ?>"> 
                                            <a href="<?php echo base_url($folder_name . '/message/' . $clist_value['customer_id']); ?>" style="display: inline-block;width: 100%;">
                                                <img class="rounded-circle position-r" src="<?php echo check_admin_image(UPLOAD_PATH . "profiles/" . $clist_value['profile_image']); ?>">
                                                <span class="user-list-text"><?php echo ($clist_value['first_name']) . " " . $clist_value['last_name']; ?></span>   
                                                <?php if ($unread > 0) { ?>
                                                    <div class="un-read"><?php echo $unread; ?></div>
                                                <?php } ?>
                                            </a>
                                        </div>
                                    <?php }
                                    ?>
                                <?php } else { ?>
                                    <h2 class="no-found"><?php echo translate('no_found'); ?></h2>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($msg_customer_data) && count($msg_customer_data) > 0) { ?>
                        <div class="col-md-8 chating_list white">
                            <div class="header pt-3">
                                <h3 class="mb-3 font-bold pl-10"> 
                                    <img class="rounded-circle" style="width:40px; height: 40px;" src="<?php echo check_admin_image(UPLOAD_PATH . "profiles/" . $msg_customer_data[0]['profile_image']); ?>">
                                    <span class="user-text"><?php echo ($msg_customer_data[0]['first_name']) . " " . ($msg_customer_data[0]['last_name']); ?></span>                               
                                </h3>
                            </div>
                            <div class="frame">
                                <form action="<?php echo base_url($folder_name . '/message-action'); ?>" name="chat_form" id="chat_form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                    <ul class="list-inline px-40 chating_block" style="height: 338px;overflow-x: auto;" id="scroll-auto">
                                        <?php
                                        if (isset($msg_group_list) && count($msg_group_list) > 0) {
                                            foreach ($msg_group_list as $group_value) {
                                                $get_message = get_message($group_value['date'], $group_value['chat_id']);
                                                $last_chat_message_id = $get_message[count($get_message) - 1]['id'];
                                                $this->session->set_userdata('last_chat_message_id_' . $chat_id, $last_chat_message_id);
                                                if (isset($get_message) && count($get_message) > 0) {
                                                    ?>
                                                    <li class="text-center title_border"><small class="label label-info chat-date"><?php echo date('F d ,Y', strtotime($group_value['date'])); ?></small></li>
                                                    <?php
                                                    foreach ($get_message as $msg_value) {
                                                        if ($msg_customer_data[0]['id'] == $msg_value['to_id'] && $msg_value['chat_type'] == 'NC') {
                                                            if ($msg_value['aprofile_image'] != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $msg_value['aprofile_image'])) {
                                                                $avatar_img = base_url() . "assets/uploads/profiles/" . $msg_value['aprofile_image'];
                                                            } else {
                                                                $avatar_img = base_url() . "assets/images/user.png";
                                                            }
                                                            $deliver_check_icon = ($msg_value['msg_read'] == 'Y') ? "<i class='fa pl-10 fa-check-circle text-info'></i>" : "<i class='fa pl-10 fa-check'></i>";
                                                            ?>
                                                            <li class="by_current_user text-right">
                                                                <p class="message_content">
                                                                    <span class="chat-message">
                                                                        <?php echo $msg_value['message']; ?>
                                                                    </span>
                                                                    <img class="rounded-circle" src="<?php echo $avatar_img; ?>">
                                                                </p>
                                                                <span class="chat_message_header"><i><?php echo $msg_value['timestamp']; ?> by <?php echo $msg_value['aname']; ?></i>
                                                                    <?php echo $deliver_check_icon; ?>
                                                                </span>
                                                            </li>

                                                            <?php
                                                        } else {
                                                            if ($msg_value['profile_image'] != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $msg_value['profile_image'])) {
                                                                $avatar_img = base_url() . "assets/uploads/profiles/" . $msg_value['profile_image'];
                                                            } else {
                                                                $avatar_img = base_url() . "assets/images/user.png";
                                                            }
                                                            ?>
                                                            <li class="get_current_user">
                                                                <p class="message_content">
                                                                    <img class="rounded-circle" src="<?php echo $avatar_img; ?>">
                                                                    <span class="chat-message">
                                                                        <?php echo $msg_value['message']; ?>
                                                                    </span>
                                                                </p>
                                                                <span class="chat_message_header"><i><?php echo $msg_value['timestamp']; ?> by <?php echo $msg_value['first_name']; ?></i>
                                                                </span>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <div class="input-type-box">
                                        <input name="msg_to_id" value="<?php echo $msg_customer_data[0]['id']; ?>" id="msg_to_id" type="hidden">
                                        <div class="macro px-3 chat-box">                        
                                            <div class="form-group mb-0">
                                                <input class="form-control d-inline-block w-86" name="message" id="message" required="true" placeholder="Type a message.." autofocus/>
                                                <button type="button" class="btn m-0 btn-sm pull-right" onclick="send_message();"><i class="fa fa-paper-plane-o" ></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>                            
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!--Row-->
            </section>
        </div>
    </div>   
</div>
<script>
    var interval_time = '<?php echo CHAT_INTERVAL_TIME; ?>';
    var interval_time_ms = interval_time * 1000;
    var folder_name = $("#folder_name").val();

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
        var objDiv = document.getElementById("scroll-auto");
        objDiv.scrollTop = objDiv.scrollHeight;
    }
    function send_message() {
        last_msg_html = '';
        var folder_name = $("#folder_name").val();
        var msg_to_id = $("#msg_to_id").val();
        var message = $("#message").val();
        $("#message").attr("required", true);
        if (message != '') {
            $.ajax({
                url: site_url + folder_name + "/send-message",
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
                        /* Error here */
                    }

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
        $.post(site_url + folder_name + "/ajax-get-chats-messages", {chat_id: chat_id}, function (data) {

            /* Condition */
            if (data.status == 'ok') {
                var current_content = $("#scroll-auto").html();
                $("#scroll-auto").html(current_content + data.content);
            } else {
                /* Error here */
            }

        }, "json");

        return false;
    }

    get_chats_messages();

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
<?php
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/footer.php';
} else {
    include VIEWPATH . 'admin/footer.php';
}
?>