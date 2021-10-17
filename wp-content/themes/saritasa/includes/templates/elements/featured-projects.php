<?php
/**
 * @var WP_Post $projects
 */
?>
<div class="featured-projects"><?php
    foreach ($projects as $project) {
        $project_type = wp_get_post_terms($project->ID, \Sar\Portfolio::PORTFOLIO_TAXONOMY_TYPE)[0]->name;
        $category_id = get_term_by('name', $project_type, 'project-type')->term_id;
        $category_image_id = get_term_meta($category_id, 'category_icon', true);

        $icon_url = wp_get_attachment_image_src($category_image_id, 'full')[0];
        $img_url = get_the_post_thumbnail_url($project->ID, 'full');
        ?>
        <div class="featured-projects-item" style="background-image: url('<?php echo $img_url; ?>')">
            <div class="overlay"></div>
            <div class="category-icon" style="background-image: url('<?php echo $icon_url; ?>')"></div>
            <div class="featured-projects-meta">
                <h3><?php echo $project->post_title; ?></h3>
                <p><?php echo $project_type; ?></p>
            </div>
            <a href="<?php echo get_the_permalink($project->ID); ?>"></a>
        </div>
        <?php
    }
    ?>
</div>
