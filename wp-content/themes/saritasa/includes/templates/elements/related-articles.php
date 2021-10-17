<?php
/**
 * @var WP_POST $posts
 * @var int $posts_per_page
 */
?>
<div class="related-articles">
    <?php foreach ($posts as $post) { ?>
      <div class="related-articles-item" data-post-id="<?php echo $post->ID; ?>">
        <a href="<?php echo get_permalink($post->ID); ?>">
          <div class="post-img" style="background-image: url(<?php echo get_the_post_thumbnail_url($post->ID) ?>);"></div>
        </a>
        <div class="related-articles-content">
          <p class="related-articles-tags">
              <?php
              $tags = get_the_tags($post->ID);
              if ($tags && is_array($tags)) {
                  foreach ($tags as $tag) {
                      echo '<span>' . $tag->name . '</span>';
                  }
              }
              ?>
          </p>
          <a href="<?php echo get_permalink($post->ID); ?>">
            <h2 class="related-articles-heading"><?php echo get_the_title($post->ID); ?></h2>
          </a>
          <p class="related-articles-excerpt">
              <?php echo wp_trim_words(get_post_field('post_content', $post->ID), 20, ''); ?>
          </p>
          <div class="related-articles-meta">
              <?php echo get_avatar(get_the_author_meta('user_email', $post->post_author)); ?>
            <div>
              <h3 class="author-name"><?php echo get_the_author_meta('display_name', $post->post_author); ?></h3>
              <p class="published"><?php echo get_the_date('', $post->ID); ?></p>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
</div>
