<div class="post-item">
    <a href="<?php echo get_permalink(); ?>">
        <div class="post-img" style="background-image: url(<?php echo get_the_post_thumbnail_url() ?>)"></div>
    </a>
    <div class="content">
        <p class="tags">
            <?php
            $tags = get_the_tags();
            if ($tags && is_array($tags)) {
                foreach ($tags as $tag) {
                    echo '<span>' . $tag->name . '</span>';
                }
            }
            ?>
        </p>
        <a href="<?php echo get_permalink(); ?>">
            <h2 class="heading"><?php the_title(); ?></h2>
        </a>
        <p class="excerpt">
            <?php echo wp_trim_words(get_the_content(), 20, ''); ?>
        </p>
        <div class="author">
            <?php echo get_avatar(get_the_author_meta('user_email')); ?>
            <div>
                <h3 class="author-heading"><?php echo get_the_author(); ?></h3>
                <p class="author-date"><?php echo get_the_date(''); ?></p>
            </div>
        </div>
    </div>
</div>
