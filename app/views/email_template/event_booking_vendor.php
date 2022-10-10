<?php
$event_img_file = '';
$event_img_Arr = json_decode($event_data['image']);
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
?>
<!doctype html>
<html lang="en-US">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <style type="text/css">
            a:hover { text-decoration: none !important; }
            :focus { outline:none; border:0;}
        </style>
    </head>
    <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f8f9;" bgcolor="#eaeeef" leftmargin="0">
        <!--100% body table-->
        <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f8f9" style="@import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300); font-family: 'Roboto', sans-serif , Arial, Helvetica, sans-serif;">
            <tr>
                <td>
                    <table style="background-color: #f2f8f9; max-width:670px; margin:0 auto;" width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="height:80px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;">
                                <a href="<?php echo base_url(); ?>" title="<?php echo get_CompanyName(); ?>"><img width="" src="<?php echo get_CompanyLogo(); ?>" title="<?php echo get_CompanyName(); ?>" alt="<?php echo get_CompanyName(); ?>"></a>
                            </td>
                        </tr>
                        <tr>
                            <td height="40px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:600px; background:#fff; border-radius:3px; text-align:left; -webkit-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);-moz-box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12);box-shadow:0 1px 3px 0 rgba(0, 0, 0, 0.16), 0 1px 3px 0 rgba(0, 0, 0, 0.12)">
                                    <tr>
                                        <td style="padding:40px;">
                                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <h1 style="color: #3075BA; font-weight: 400; margin: 0; font-size: 32px;">Hi <?php echo isset($name) ? $name : ""; ?>,</h1>
                                                        <p style="font-size:15px; color:#171f23de; line-height:24px; margin:8px 0 30px;"><?php echo translate('new_event_booking_vendor'); ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                            <tr style="border-bottom:1px solid #ebebeb; margin-bottom:26px; display:block;">
                                                                <td width="84">
                                                                    <a style="height: 64px; width: 64px; text-align:center; display:block;">
                                                                        <img height="50" width="50" src="<?php echo $img_src; ?>" alt="Profile Picture" style="border-radius:50%;">
                                                                    </a>
                                                                </td>
                                                                <td style="vertical-align:top;">
                                                                    <h3 style="color: #4d4d4d; font-size: 20px; font-weight: 400; line-height: 30px; margin-bottom: 3px; margin-top:0;"> <?php echo isset($event_data['title']) ? $event_data['title'] : ""; ?></h3>
                                                                    <span style="color:#737373; font-size:14px;"><?php echo isset($event_data['category_title']) ? $event_data['category_title'] : ""; ?></span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #ededed">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:#171f23de"><?php echo translate('total_booking') . ' ' . translate('ticket'); ?>:</td>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed; color: rgba(23,31,35,.87);" ><?php echo $event_booking_seat; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:#171f23de"><?php echo translate('price'); ?>:</td>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed; color: rgba(23,31,35,.87);" ><?php echo isset($price) ? $price : ""; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:#171f23de"><?php echo translate('date'); ?>:</td>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed; color: rgba(23,31,35,.87);"><?php echo get_formated_date($event_data['start_date']) . " to " . get_formated_date($event_data['end_date']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:#171f23de"><?php echo translate('city') . "/" . translate('location'); ?>:</td>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed; color: rgba(23,31,35,.87);"><?php echo $event_data['city_title'] . " / " . $event_data['loc_title']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding: 10px;  border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%;font-weight:500; color:#171f23de"><?php echo translate('customer'); ?></td>
                                                                    <td style="padding: 10px; border-bottom: 1px solid #ededed; color: rgba(23,31,35,.87);">
                                                                        <p><b><?php echo isset($customer_data['first_name']) ? $customer_data['first_name'] . " " . $customer_data['last_name'] : ""; ?></b></p>
                                                                        <p><b><?php echo translate('email'); ?>:</b><?php echo isset($customer_data['email']) ? $customer_data['email'] : ""; ?></p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:25px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;">
                                <p style="font-size:14px; color:#455056bd; line-height:18px; margin:0 0 0;"><strong>&copy;</strong> <?php echo get_CompanyName() . " " . date("Y"); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:80px;">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table><!--/100% body table-->
    </body>
</html>