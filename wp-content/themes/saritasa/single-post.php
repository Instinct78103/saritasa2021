<?php

/**
 * Template Name: Resources Single Post
 */

get_header(); ?>
    <div class="container-wrap" style="min-height: 490px;">
        <div class="container main-content">
            <div class="row">
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        global $post;

                        $author_avatar = get_avatar($post->post_author);
                        $author_name = get_the_author();
                        $author_bio = get_the_author_meta('description');

                        $feat_image = get_post_thumbnail_id($post->ID);
                        $image_url = wp_get_attachment_image_url($feat_image, 'full');
                        ?>

                        <div class="single-page-bg" <?php
                        if ($image_url) {
                            echo 'style="background-image: url(' . $image_url . ')"';
                        } ?>>
                            <?php
                            if (get_the_tags() && is_array(get_the_tags())) {
                                foreach (get_the_tags() as $tag) {
                                    echo '<span class="tag">' . $tag->name . '</span>';
                                }
                            }
                            ?>
                            <h1 style="text-align: center"><?php the_title(); ?></h1>
                            <div class="single-meta">
                                <?php
                                echo 'By ' . $author_name . ' <span class="delimiter">|</span> ' . get_the_date();

                                if (shortcode_exists('addtoany')) {
                                    if ($a2a = do_shortcode('[addtoany]')) {
                                        echo " <span class=\"delimiter\">|</span> $a2a";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="single-page-container">
                            <h2 class="title"><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                            <div class="post-author">
                                <?php echo $author_avatar; ?>
                                <p class="author-name"><?php echo $author_name; ?></p>
                                <p class="author-bio"><?php echo $author_bio; ?></p>
                            </div>
                        </div>
                        <div class="prev-next">
                            <?php if ($prevPost = get_previous_post()) { ?>
                                <div class="prev"
                                     style="background-image: url(<?php echo get_the_post_thumbnail_url($prevPost->ID) ?>)">
                                    <div class="overlay"></div>
                                    <div class="text">
                                        <p><a href="<?php echo get_permalink(get_previous_post()); ?>">Previous Post</a>
                                        </p>
                                        <h2>
                                            <a href="<?php echo get_permalink(get_previous_post()); ?>"><?php echo $prevPost->post_title; ?></a>
                                        </h2>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($nextPost = get_next_post()) { ?>
                                <div class="next"
                                     style="background-image: url(<?php echo get_the_post_thumbnail_url($nextPost->ID) ?>)">
                                    <div class="overlay"></div>
                                    <div class="text">
                                        <p><a href="<?php echo get_permalink($nextPost->ID); ?>">Next Post</a></p>
                                        <h2>
                                            <a href="<?php echo get_permalink($nextPost->ID); ?>"><?php echo $nextPost->post_title; ?></a>
                                        </h2>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        if (function_exists('theRelatedPosts')) {
                            theRelatedPosts();
                        }

                        /*
                         * See dashboard: Settings -> Discussion -> Allow people to post comments on new articles
                         */

                        if ('open' === get_option('default_comment_status')) {
                            comments_template();
                        }
                    }
                }

                if (shortcode_exists('saritasa_subscription')) {
                    echo do_shortcode('[saritasa_subscription]');
                }

                ?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/container-wrap-->
<?php

get_footer();

