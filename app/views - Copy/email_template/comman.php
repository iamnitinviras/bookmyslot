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
                                                        <p style="font-size:15px; color:#171f23de; line-height:24px; margin:8px 0 30px;"><?php echo isset($content_data)?$content_data:""; ?></p>
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