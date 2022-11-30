<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = ((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';
$site_url .= '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
$site_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$site_url = str_replace("admin/", "", $site_url);
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Database Error</title>
        <style type="text/css">

            ::selection { background-color: #72AA5F; color: white; }
            ::-moz-selection { background-color: #72AA5F; color: white; }

            body {
                background-color: #fff;
                margin: 40px;
                font: 13px/20px normal Helvetica, Arial, sans-serif;
                background: #6B9F5A;
                text-align: center;
                padding-bottom: 2rem
            }

            a {
                color: #003399;
                background-color: transparent;
                font-weight: normal;
            }        

            h2 {
                font-size: 40px;
                color: #fff;
                margin-top: 3rem;
            }

            .fa-exclamation-triangle {
                content: '\f071';
                font-family: 'FontAwesome';
            }

            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }

            #container {
                margin-top: 7rem;
            }

            p {
                font-size: 22px;
                width: 70%;
                margin: 3rem auto;
                line-height: 30px;
                color: #fff;
            }

            .btn.btn-danger {
                border: 3px solid #FFF;
                color: #FFF;
                padding: 10px 20px;
                font-size: 17px;
                font-weight: bold;
                text-decoration: none;
                border-radius: 4px;
            }

            @media (max-width:767px){
                body{
                    margin: 0;
                }
                #container {
                    margin-top: 4rem;
                }
                h1 {
                    font-size: 40px;
                    padding: 1rem 0;
                    line-height: normal;
                    margin-bottom: 3rem;
                }
                h2 {
                    line-height: normal;
                }

                .btn.btn-danger{
                    margin-bottom: 1rem
                }
            }
        </style>
    </head>
    <body>
        <div class="container" id="container">
            <div class="icon">
                <img src="<?php echo $site_url; ?>assets/images/error.png" alt="error img" />
            </div>
            <h1><?php echo $heading; ?></h1>
            <?php echo $message; ?>
        </div>
    </body>
</html>