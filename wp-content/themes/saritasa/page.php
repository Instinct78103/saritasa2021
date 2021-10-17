<?php get_header(); ?>
<div class="container-wrap">
    <div class="container main-content">
        <div class="row">
            <?php
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
            }

            global $post;
            preg_match_all('~vc_row block_id="(\d*)"~', $post->post_content, $matches);
            foreach ($matches[1] as $post_ID) {
                get_additional_css_from_db($post_ID);
            }

            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            }
            ?>
        </div><!--/row-->
    </div><!--/container-->
</div><!--/container-wrap-->
<?php get_footer(); ?>
