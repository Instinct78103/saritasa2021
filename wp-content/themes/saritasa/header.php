<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="ocm-effect-wrap">
    <div id="header-space"></div>
    <div id="header-outer">
        <header id="#top">
            <div class="container">
                <div class="row">
                    <div class="col span_3">
                        <a id="logo" href="<?php echo home_url(); ?>">
                            <img src="<?php echo sar_image_url('logo.svg'); ?>"
                                 alt="<?php echo get_bloginfo('name'); ?>" width="220" height="45">
                        </a>
                    </div>
                    <div class="col span_9">
                        <nav>
                            <?php wp_nav_menu([
                                'theme_location' => 'top_nav',
                                'menu_class' => 'top-nav-menu',
                            ]); ?>
                        </nav>
                        <a class="mobile-menu-toggle" href="#">
                            <i class="lines-button">
                                <i class="lines"></i>
                                <i class="lines"></i>
                                <i class="lines"></i>
                            </i>
                        </a>
                    </div><!--/span_9-->
                </div><!--/row-->
            </div><!--/container-->
        </header>
    </div><!--/header-outer-->
    <div id="ajax-content-wrap">
