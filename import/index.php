<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '250006M');
set_time_limit(30000);

require_once('../wp-load.php');
require_once('vendor/autoload.php');
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

define('IMPORT_DIRNAME_IMAGES', dirname(__FILE__) . '/images/');
const WP_USE_THEMES = false;
const IMPORT_UPLOADS_DIRNAME = ABSPATH . 'wp-content/uploads';
const FILE_TO_IMPORT = './portfolio_2021-10-15.csv';

use Akeneo\Component\SpreadsheetParser\SpreadsheetParser;

class Import
{
    protected $errors = [];
    protected $did = [];
    protected $attachments = [];
    protected $columns = [
        'Portfolio Name',
        'AppStore URL',
        'Video',
        'Website',
        'GooglePlay URL',
        'Categories',
        'Industries',
        'Featured Image',
        'Published',
        'URL',
        'What Saritasa Did',
        'Our Solution',
        'Images',
        'Quote',
        'Overview',
        'Key Features',
        'Their Challenge',
        'Live in Portfolio',
        'Status',
    ];

    public function execute()
    {
        $attachments = get_posts([
            'post_type' => 'attachment',
            'numberposts' => -1,
        ]);

        foreach ($attachments as $attachment) {
            $this->attachments[] = $attachment;
        }

        $this->remove_old();

        foreach ($this->get_data() as $num => $portfolio) {
            pre('#' . ($num + 1));
            $this->create_portfolio($portfolio);
        }
    }

    protected function remove_old()
    {
        /**
         * Remove old portfolio posts
         */
        $old_portfolio_posts = get_posts([
            'post_type' => 'portfolio',
            'numberposts' => -1,
            'post_status' => 'any',
        ]);

        array_map(fn ($post) => wp_delete_post($post->ID, true), $old_portfolio_posts);

        /**
         * Remove old portfolio terms
         */
        global $wpdb;
        $old_portfolio_terms = $wpdb->get_results("
            SELECT distinct t.term_id, t.slug, t_t.taxonomy
                FROM wp_term_taxonomy t_t
            INNER JOIN wp_terms t ON t_t.term_id = t.term_id
            INNER JOIN wp_term_relationships t_r ON t_r.term_taxonomy_id = t.term_id
            INNER JOIN wp_posts p ON t_r.object_id = p.ID
                WHERE p.post_type='portfolio'
                ORDER BY t.name");
        array_map(fn ($term) => wp_delete_term($term->term_id, $term->taxonomy), $old_portfolio_terms);
    }


    protected function get_data(): array
    {
        $spreadsheet = SpreadsheetParser::open(FILE_TO_IMPORT);
        $worksheet = $spreadsheet->createRowIterator(0);

        $worksheetCols = [];
        $worksheetRows = [];
        foreach ($worksheet as $rowNumber => $row) {
            try {
                if ($rowNumber === 1) {
                    $worksheetCols = $row;
                } elseif ($rowNumber > 1) {
                    $worksheetRows[] = $row;
                }
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }


        $main_cols_snake_case = array_map(fn ($item) => str_replace(' ', '_', trim(strtolower($item))), $this->columns);

        $data = array_reduce($worksheetCols, function ($acc, $item) use ($worksheetCols, $worksheetRows, $main_cols_snake_case) {
            $key = array_search($item, $worksheetCols);
            $item = str_replace(' ', '_', trim(strtolower($item)));
            if (in_array($item, $main_cols_snake_case)) {
                foreach ($worksheetRows as $num => $post) {
                    $acc[$num][$item] = $post[$key];
                }
            }
            return $acc;
        }, []);

        // Easy to read the data
        // $data_in_alphabet_order = array_map(function ($item) {
        //     ksort($item);
        //     return $item;
        // }, $data);

        $data_to_import = array_values(array_filter($data, function ($post) {
            return
                $post['status'] === 'Ready to Publish' || // status: draft
                $post['live_in_portfolio'] && $post['live_in_portfolio'] !== 'false'; // status: publish
        }));

        return $this->sanitize_data($data_to_import);
    }

    protected function sanitize_data($posts): array
    {
        return array_map(function ($post) {
            foreach ($post as $key => $value) {
                $value = trim($value);
                switch ($key) {
                    case 'categories':
                    case 'industries':
                        $post[$key] = $value ? array_map(fn ($item) => sanitize_title(trim($item)), explode(';', $value)) : [];
                        break;
                    case 'images':
                    case 'quote':
                    case 'overview':
                    case 'our_solution':
                    case 'their_challenge':
                        $post[$key] = $value ? array_values(array_filter(array_map('trim', explode("\n", $value)))) : [];
                        break;
                    case 'what_saritasa_did':
                        $units = [];
                        $exploded = $value ? array_values(array_filter(array_map('trim', explode("\n", $value)))) : [];
                        if (count($exploded) > 1) {
                            $i = 0;
                            foreach ($exploded as $k => $str) {
                                if ($k % 2) {
                                    $units[$i - 1]['text'] = trim($str);
                                } else {
                                    $units[$i]['header'] = trim($str);
                                }
                                $i++;
                            }
                        }
                        $post[$key] = array_values($units);
                        break;
                    case 'key_features':
                        $units = [];
                        $exploded = $value ? array_values(array_filter(array_map('trim', explode("\n", $value)))) : [];
                        foreach ($exploded as $str) {
                            $exploded_2 = array_values(array_filter(array_map('trim', explode(':', $str))));
                            if (count($exploded_2) < 2) {
                                $exploded_2 = array_values(array_filter(array_map('trim', explode(';', $str))));
                            }
                            $units[] = [
                                'header' => $exploded_2[0],
                                'text' => $exploded_2[1],
                            ];
                        }
                        $post[$key] = $units;
                        break;
                    default:
                        $post[$key] = $value;
                        break;
                }
            }
            return $post;
        }, $posts);
    }

    protected function create_portfolio($portfolio)
    {
        echo '<h2>' . $portfolio['portfolio_name'] . '</h2>';
        pre($portfolio);

        $content = $this->get_content($portfolio);



        if (preg_replace('~\s+~', ' ', trim(strtolower($portfolio['status']))) === 'ready to publish') {
            $post_status = 'draft';
        } else {
            $post_status = 'publish';
        }

        $post_id = wp_insert_post([
            'post_title' => $portfolio['portfolio_name'],
            'post_content' => $content,
            'post_status' => $post_status,
            'post_type' => 'portfolio',
            'post_date' => $portfolio['published'],
        ]);

        if (count($portfolio['categories'])) {
            $this->set_taxonomy($portfolio['categories'], 'project-type', $post_id);
        }
        if (count($portfolio['industries'])) {
            $this->set_taxonomy($portfolio['industries'], 'project-industry', $post_id);
        }

        if ($portfolio['featured_image']) {
            $image_id = $this->get_image_id($portfolio['featured_image']);
            if ($image_id) {
                set_post_thumbnail($post_id, $image_id);
            }
        }

        echo '--------------------------------------------';
    }

    protected function set_taxonomy($terms, $taxonomy, $post_id)
    {
        $terms_ids = array_map(function ($term) use ($taxonomy) {
            $t = term_exists($term, $taxonomy);
            if (!$t) {
                return wp_insert_term(ucwords(str_replace('-', ' ', $term)), $taxonomy)['term_id'];
            }
            return $t['term_id'];
        }, $terms);

        wp_set_post_terms($post_id, $terms_ids, $taxonomy);
    }

    protected function get_shortcode($string)
    {
        if (preg_match_all('/' . get_shortcode_regex() . '/s', $string, $matches) === false || empty($matches[2])) {
            return $string;
        }
        return trim($matches[5][0]);
    }

    protected function get_content($portfolio)
    {
        $image_ids = [];
        if (count($portfolio['images'])) {
            foreach ($portfolio['images'] as $image_url) {
                $image_id = $this->get_image_id($image_url);
                if ($image_id) {
                    $image_ids[] = $image_id;
                }
            }
        }

        $content = '';

        if (count($portfolio['overview'])) {
            $content .= '
[vc_row class="portfolio-item-overview"]
  [vc_column width="1/1"]
    [text-with-icon icon_image="571"]<h2>Overview</h2>[/text-with-icon]
      [vc_column_text]';
            foreach ($portfolio['overview'] as $overview_val) {
                $content .= '<p>' . $overview_val . '</p>';
            }
            $content .= '
      [/vc_column_text]
  [/vc_column]
[/vc_row]';
        }

        $columns_our_solution = count(array_filter([$portfolio['our_solution'], $portfolio['their_challenge']]));
        switch ($columns_our_solution) {
            case 1:
                $columns_our_solution_width = '1/1';
                break;
            case 2:
                $columns_our_solution_width = '1/2';
                break;
        }
        if ($columns_our_solution) {
            $content .= '
[vc_row class="challenge-solution"]
  [vc_column]
    [vc_row_inner]';

            if (count($portfolio['their_challenge'])) {
                $content .= '
        [vc_column_inner width="' . $columns_our_solution_width . '" el_class="their-challenge"]
          [text-with-icon icon_image="571"]<h2>Their Challenge</h2>[/text-with-icon]
          [vc_column_text]';
                foreach ($portfolio['their_challenge'] as $their_challenge_val) {
                    $content .= '<p>' . $their_challenge_val . '</p>';
                }
                $content .= '
          [/vc_column_text]
        [/vc_column_inner]';
            }

            if (count($portfolio['our_solution'])) {
                $content .= '
        [vc_column_inner width="' . $columns_our_solution_width . '" el_class="our-solution"]
          [text-with-icon icon_image="572"]<h2>Our Solution</h2>[/text-with-icon]
          [vc_column_text]';
                foreach ($portfolio['our_solution'] as $our_solution_val) {
                    $content .= '<p>' . $our_solution_val . '</p>';
                }
                $content .= '
         [/vc_column_text]
        [/vc_column_inner]';
            }

            $content .= '
    [/vc_row_inner]
  [/vc_column]
[/vc_row]';
        }

        if (count($portfolio['what_saritasa_did'])) {
            $content .= ' 
[vc_row class="what_saritasa_did"]
  [vc_column el_class="portfolio-item-did" width="1/3"]
    [vc_custom_heading text="What Saritasa Did:" font_container="tag:h2|font_size:36px|text_align:left|color:%231a3340|line_height:49px"]
    [vc_row_inner]
      [vc_column_inner]';
            foreach ($portfolio['what_saritasa_did'] as $item) {
                $what_saritasa_did_title = $this->get_shortcode($item['header']);
                $what_saritasa_did_text = $item['text'];
                $content .= '
                  [vc_column_text el_class="portfolio-item-did-' . sanitize_title($item['header']) . '"]
                    <h3>' . $what_saritasa_did_title . '</h3>
                    <p>' . $what_saritasa_did_text . '</p>
                  [/vc_column_text]';
            }
            $content .= ' 
      [/vc_column_inner]
    [/vc_row_inner]
  [/vc_column]
  [vc_column el_class="portfolio-item-media" width="2/3"]';
            if (count($image_ids)) {
                if (count($image_ids) > 1) {
                    $desktop_columns = 3;
                    $small_desktop_columns = 3;
                    $tablet_columns = 2;
                    switch (count($image_ids)) {
                        case 2:
                            $desktop_columns = 1;
                            $small_desktop_columns = 1;
                            $tablet_columns = 1;
                            break;
                        case 3:
                            $desktop_columns = 2;
                            $small_desktop_columns = 2;
                            $tablet_columns = 1;
                            break;
                    }
                    $content .= '[vc_gallery type="flickity_style" images="' . implode(',', $image_ids) . '" flickity_controls="pagination" flickity_desktop_columns="' . $desktop_columns . '" flickity_small_desktop_columns="' . $small_desktop_columns . '" flickity_tablet_columns="' . $tablet_columns . '" flickity_autoplay="true" flickity_box_shadow="none" onclick="link_no" img_size="full"]';
                } else {
                    $content .= '[image_with_animation image_url="' . array_pop($image_ids) . '" alignment="center" animation="Fade In" border_radius="none" box_shadow="none" max_width="100%"]';
                }
            }
            if ($portfolio['video']) {
                $content .= '[vc_video link="' . $portfolio['video'] . '" align="right"]';
            }
            if ($portfolio['quote']) {
                $content .= '
        [vc_column_text el_class="portfolio-item-quote"]';
                foreach ($portfolio['quote'] as $portfolio_quote) {
                    $content .= '<p><i>' . trim($portfolio_quote) . '</i></p>';
                }
                $content .= '
        [/vc_column_text]';
            }
            $content .= '
  [/vc_column]
[/vc_row]';
        } else if (count($image_ids) || $portfolio['video']) {
            $content .= '
[vc_row class="portfolio-item-media"]
  [vc_column]';
            if (count($image_ids)) {
                if (count($image_ids) > 1) {
                    $desktop_columns = 3;
                    $small_desktop_columns = 3;
                    $tablet_columns = 2;
                    switch (count($image_ids)) {
                        case 2:
                            $desktop_columns = 1;
                            $small_desktop_columns = 1;
                            $tablet_columns = 1;
                            break;
                        case 3:
                            $desktop_columns = 2;
                            $small_desktop_columns = 2;
                            $tablet_columns = 1;
                            break;
                    }
                    $content .= '[vc_gallery type="flickity_style" images="' . implode(',', $image_ids) . '" flickity_controls="pagination" flickity_desktop_columns="' . $desktop_columns . '" flickity_small_desktop_columns="' . $small_desktop_columns . '" flickity_tablet_columns="' . $tablet_columns . '" flickity_autoplay="true" flickity_box_shadow="none" onclick="link_no" img_size="full"]';
                } else {
                    $content .= '[image_with_animation image_url="' . array_pop($image_ids) . '" alignment="center" animation="Fade In" border_radius="none" box_shadow="none" max_width="100%"]';
                }
            }
            if ($portfolio['video']) {
                $content .= '
    [vc_video link="' . $portfolio['video'] . '" align="right"]';
            }
            if ($portfolio['quote']) {
                $content .= '
        [vc_column_text el_class="portfolio-item-quote"]';
                foreach ($portfolio['quote'] as $portfolio_quote) {
                    $content .= '<p><i>' . trim($portfolio_quote) . '</i></p>';
                }
                $content .= '
        [/vc_column_text]';
            }
            $content .= '
  [/vc_column]
[/vc_row]';
        }

        if (count($portfolio['key_features'])) {
            $content .= '
[vc_row class="portfolio-item-features"]
    [vc_column]
        [vc_custom_heading text="Features" font_container="tag:h2|font_size:36px|text_align:left|color:%231a3340|line_height:49px"]
        [nectar_icon_list color="default" direction="vertical" icon_size="small" icon_style="border"]';
            foreach ($portfolio['key_features'] as $feature_key => $feature) {
                $feature_id = uniqid(rand());
                $content .= '
            [nectar_icon_list_item icon_type="icon" id="' . $feature_id . '-' . ($feature_key + 1000) . '" tab_id="' . $feature_id . '-' . ($feature_key + 1) . '" icon_fontawesome="fa fa-circle" header="' . $feature['header'] . '" text="' . $feature['text'] . '"]';
            }
            $content .= '
        [/nectar_icon_list]
    [/vc_column]
[/vc_row]';
        }

        $columns = count(array_filter([$portfolio['website'], $portfolio['appstore_url'], $portfolio['googleplay_url']]));
        switch ($columns) {
            case 1:
                $column_width = '1/1';
                break;
            case 2:
                $column_width = '1/2';
                break;
            case 3:
                $column_width = '1/3';
                break;
        }
        if ($columns) {

            $content .= '
[vc_row type="in_container" class="portfolio-item-links"]';

            if ($portfolio['website']) {
                $content .= '
    [vc_column column_link_target="_self" width="' . $column_width . '" el_class="portfolio-item-links-website"]
        [vc_custom_heading text="Website"]
        [sar-btn text="Visit Website" icon_fontawesome="fa fa-share-square-o" url="' . $portfolio['website'] . '"]
    [/vc_column]';
            }

            if ($portfolio['appstore_url']) {
                $content .= '
    [vc_column column_link_target="_self" width="' . $column_width . '" el_class="portfolio-item-links-appstore"]
        [vc_custom_heading text="Android"]
        [sar-btn text="Google Play Store" icon_fontawesome="fa fa-android" url="' . $portfolio['googleplay_url'] . '"]
    [/vc_column]';
            }

            if ($portfolio['googleplay_url']) {
                $content .= '
    [vc_column column_link_target="_self" width="' . $column_width . '" el_class="portfolio-item-links-googleplay"]
        [vc_custom_heading text="iOS"]
        [sar-btn text="App Store" icon_fontawesome="fa fa-apple" url="' . $portfolio['appstore_url'] . '"]
    [/vc_column]';
            }

            $content .= '
[/vc_row]';
        }

        return trim(implode('', array_values(array_filter(array_map('trim', explode("\n", $content))))));
    }

    protected function get_image_id($imageUrl)
    {
        $imageUrl = trim($imageUrl);

        if (!$imageUrl) {
            $this->log('Image is empty: %s', 'red');
            return false;
        }

        foreach ($this->attachments as $att) {
            $file_name = get_attached_file($att->ID);
            if (file_exists($file_name)) {
                $file = new SplFileInfo($file_name);
                if ($imageUrl === trim($file->getFileName())) {
                    $this->log(sprintf('Exists image: %s', $file->getFilename()));
                    return $att->ID;
                }
            }
        }

        $dir = new RecursiveDirectoryIterator(IMPORT_UPLOADS_DIRNAME, RecursiveDirectoryIterator::SKIP_DOTS);
        $ite = new RecursiveIteratorIterator($dir);
        $images = [];
        foreach ($ite as $file) {
            if ($imageUrl === trim($file->getFileName())) {
                $images[$file->getPathname()] = $file;
            }
        }
        $images = array_values($images);
        if (count($images)) {

            $file_data = [
                'name' => $images[0]->getFilename(),
                'type' => mime_content_type($images[0]->getPathName()),
                'tmp_name' => $images[0]->getPathName(),
                'error' => 0,
                'size' => filesize($images[0]->getPathName()),
            ];

            pre($file_data);

            $attachment_id = media_handle_sideload($file_data, 0);

            if (is_wp_error($attachment_id)) {
                $this->log(sprintf('Upload Error: %s', $attachment_id->get_error_message()), 'red');
                return false;
            }

            $this->log(sprintf('Upload image: %s', $images[0]->getFilename()));

            $attachment = get_post($attachment_id);
            $this->attachments[] = $attachment;
            return $attachment->ID;
        }

        $this->log(sprintf('Image does not exist: %s', $imageUrl), 'red');
        return false;
    }

    protected function log($string, $color = 'green')
    {
        switch ($color) {
            case 'green':
                $format = "\e[0;32m%s\e[0m";
                break;
            case 'blue':
                $format = "\e[1;34m%s\e[0m";
                break;
            case 'red':
                $format = "\e[1;31m%s\e[0m";
                break;
            case 'yellow':
                $format = "\e[0;33m%s\e[0m";
                break;
        }
        if (php_sapi_name() == 'cli') {
            echo "\n" . sprintf($format, $string);
        } else {
            echo '<pre style="white-space: pre-wrap; color: ' . $color . ';">';
            print_r($string);
            echo '</pre>';
        }
    }
}

(new Import)->execute();
