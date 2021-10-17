<div id="footer-outer">
    <div id="footer-widgets">
        <div class="container">
            <div class="row">
                <div class="col span_4 services">
                    <?php if (!dynamic_sidebar('Footer Area 1')): ?>
                        <div class="widget"></div>
                    <?php endif; ?>
                </div>
                <div class="col span_4 locations">
                    <?php if (!dynamic_sidebar('Footer Area 2')): ?>
                        <div class="widget"></div>
                    <?php endif; ?>
                </div>
                <div class="col span_4 contacts">
                    <?php if (!dynamic_sidebar('Footer Area 3')): ?>
                        <div class="widget"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div id="copyright">
        <div class="container">
            <div class="row">
                <span>&copy; <?php echo date('Y'); ?> <?php echo __('Saritasa, LLC. All Rights Reserved | '); ?></span>
                <span><?php echo get_the_privacy_policy_link(); ?></span>
            </div>
        </div>
    </div>
</div>
</div> <!--ajax-content-wrap-->
</div><!--ocm-effect-wrap-->
<div id="slide-out-widget-area">
    <div class="inner-wrap">
        <div class="inner">
            <div class="off-canvas-menu-container mobile-only">
                <ul class="menu">
                    <?php wp_nav_menu(['theme_location' => 'top_nav', 'container' => '', 'items_wrap' => '%3$s']); ?>
                </ul>
            </div>
        </div> <!--/inner-->
    </div> <!--/inner-wrap-->
</div>
<div id="slide-out-widget-area-bg"></div>
<a class="slide_out_area_close follow-body material-ocm-open" href="#">
    <span class="close-wrap">
        <span class="close-line close-line1"></span>
        <span class="close-line close-line2"></span>
    </span>
</a>
<?php wp_footer(); ?>
</body>
</html>
