<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">
        <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>
        <!-- SITE META -->
        <title ><?php
            echo get_CompanyName();
            if (!empty($title))
                echo " | " . $title;
            ?></title>
        <!-- Font Awesome -->       
        <link href="<?php echo $this->config->item('css_url'); ?>font-awesome.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap core CSS -->   
        <link href="<?php echo $this->config->item('css_url'); ?>bootstrap.css" rel="stylesheet" type="text/css"/>
        <!--Material Design Bootstrap-->
        <link href="<?php echo $this->config->item('css_url'); ?>module/bookmyslot.css" rel="stylesheet" type="text/css"/>
        <!-- Your custom styles -->   
        <link href="<?php echo $this->config->item('css_url'); ?>module/admin_panel.css" rel="stylesheet" type="text/css"/>
        <!-- Your custom styles -->   
        <link href="<?php echo $this->config->item('css_url'); ?>module/custom.css" rel="stylesheet" type="text/css"/>
        <!-- J-Query -->  
        <script src="<?php echo $this->config->item('js_url'); ?>jquery-3.2.1.min.js" type="text/javascript"></script>
        <!-- Validation JS -->
        <script src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js"></script>
        <!-- Loader -->
        <script src="<?php echo $this->config->item('assets_url'); ?>loader/js/jquery.preloader.min.js"></script>
        <!-- Loader --> 
        <link href ="<?php echo $this->config->item('assets_url'); ?>loader/css/preloader.css" rel="stylesheet">
         <script>
            var base_url = '<?php echo base_url() ?>';
            var display_record_per_page = '<?php echo get_site_setting('display_record_per_page'); ?>';
            var csrf_token_name = '<?php echo $this->security->get_csrf_hash(); ?>';
        </script>
    </head>
    <body class="pb-0">