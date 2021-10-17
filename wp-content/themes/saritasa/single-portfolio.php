<?php

/**
 * Template Name: Portfolio Single Post
 */

get_header(); ?>
    <div class="container-wrap" style="min-height: 490px;">
        <div class="container main-content">
            <div class="row">
                <?php

                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        ?>

                        <div class="section-title">
                            <div class="col span_12">
                                <h1><?php the_title(); ?></h1>
                            </div>
                        </div>

                        <?php
                        the_content();
                    }
                }

                $currentPostID = get_the_ID();

                $posts = get_posts([
                    'post_type' => 'portfolio',
                    'post_status' => 'publish',
                    'numberposts' => -1,
                    'orderby' => 'id',
                    'order' => 'DESC',
                ]);

                $arr_of_IDs = [];
                foreach ($posts as $post) {
                    $arr_of_IDs[] = $post->ID;
                }

                $num = array_search($currentPostID, $arr_of_IDs);
                $next_postID = $arr_of_IDs[$num - 1];
                $prev_postID = $arr_of_IDs[$num + 1];

                $prevPostUrl = $prev_postID ? get_permalink($prev_postID) : null;
                $nextPostUrl = $next_postID ? get_permalink($next_postID) : null;
                ?>

                <div class="bottom_controls">
                    <div class="col span_12">
                        <ul class="controls">
                            <li id="prev-link">
                                <a href="<?php echo $prevPostUrl; ?>"<?php echo $prevPostUrl ? '' : ' style="pointer-events: none; visibility: hidden"'; ?>>
                                    <i class="icon-angle-left"></i>
                                    <span>Previous Project</span>
                                </a>
                            </li>
                            <li id="all-items">
                                <a href="/projects" title="Back to all projects">
                                    <i class="icon-salient-back-to-all"></i>
                                </a>
                            </li>
                            <li id="next-link">
                                <a href="<?php echo $nextPostUrl; ?>"<?php echo $nextPostUrl ? '' : ' style="pointer-events: none; visibility: hidden"'; ?>>
                                    <span>Next Project</span>
                                    <i class="icon-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <?php
                get_page_block_template_by_slug('services-bottom-section');
                ?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/container-wrap-->
<?php
get_footer();

