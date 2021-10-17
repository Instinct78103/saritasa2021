<?php get_header(); ?>
<div class="container-wrap">
    <div class="container main-content">
        <div class="row">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    ?>
                    <article id="post-<?php the_ID(); ?>">
                        <?php
                        the_post();
                        the_title();
                        the_content();
                        ?>
                    </article>
                    <?php
                }
            }
            ?>
        </div>
    </div><!--/container-->
</div><!--/container-wrap-->
<?php get_footer(); ?>
