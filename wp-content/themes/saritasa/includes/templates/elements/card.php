<?php
extract(shortcode_atts([
    'type'         => '',
    'image_url'    => '',
    'title'        => '',
    'description'  => '',
    'bg_color'     => '',
    'title_color'  => '',
    'desc_color'   => '',
    'custom_class' => '',
], $atts));
if ($type == 'big') { ?>
    <div class="card-row big <?php echo $custom_class ?>"
         style="background-color: <?php echo $bg_color ?>;">
        <div class="bg" style="background-image: url(<?php echo wp_get_attachment_url($image_url) ?>);"></div>
        <div class="title-block">
            <h4 style="color: <?php echo $title_color ?>;"><?php echo $title ?></h4>
        </div>
        <div class="description-block"
             style="color: <?php echo $desc_color ?>;">
            <?php echo ($content ?: $description) ?>
        </div>
    </div>
<?php } else { ?>
    <div class="card-row mini <?php echo $custom_class ?>"
         style="background-color: <?php echo $bg_color ?>;
                 background-image: url(<?php echo wp_get_attachment_url($image_url) ?>);">
        <div class="overlay"></div>
        <div class="card-text-container">
            <h4 class="mini-card-title"
                style="color: <?php echo $title_color ?>;"><?php echo $title ?></h4>
            <p class="mini-card-description"><?php echo ($content ?: $description) ?></p>
        </div>
    </div>
<?php }
