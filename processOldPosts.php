<?php

include (dirname(__FILE__) . '/wp-load.php');

$posts = get_posts(['numberposts' => -1, 'post_type' => 'portfolio']);

if (is_array($posts) && count($posts)) {
    foreach ($posts as $post) {
        echo '<pre>' . $post->ID . '</pre>';
        //$post = get_post('39723');
        $content = str_replace('//http', 'http', $post->post_content);

        var_dump(strpos($content, '<div class="title">'));

        if (is_int(strpos($content, '<div class="title">'))) {
            echo "<pre>post_id = {$post->ID} skipped</pre>";
            continue;
        }

        $tc = explode('Their Challenge', $content);
        //var_dump($tc[1]);
        if (!isset($tc[1])) {
            $blocks = parceType2($content);
        } else {
            $blocks = parceType1($content);
        }

        $newContent = '';

        foreach ($blocks as $key => $block) {
            if ($block != '') {
                $newContent .= '<div class="' . $key . '">' . $block . '</div>';
            }
        }

        $post->post_content = $newContent;

        wp_update_post($post);
    }
}


function parceType1($content = '')
{
    if ($content == ''){
        return false;
    }

    $c1 = explode('<h2>', $content);

    $blocks['title'] = $c1[0];
    $blocks['theirChallenge'] = '<h2>' . $c1[1];

    $mainImage = explode('<img', $blocks['theirChallenge'])[1] ?? '';
    $mainImage = !empty($mainImage) ? '<img' . $mainImage : false;

    $blocks['theirChallenge'] = str_replace($mainImage, '' , $blocks['theirChallenge']);

    $blocks['ourSolution'] = '<h2>' . $c1[2];

    $blocks['whatSaritasaDid'] = $blocks['ourSolution'];

    $ourSolution = explode('<p style="text-align: center;"><b>', $blocks['ourSolution']);

    if (!isset($ourSolution[1])) {
        $ourSolution = explode('<p style="text-align: center;"><strong>', $blocks['ourSolution']);
    }

    $blocks['ourSolution'] = $ourSolution[0];

    $whatSaritasaDid = str_replace($blocks['ourSolution'], '' , $blocks['whatSaritasaDid']);

    $blocks['links'] = $whatSaritasaDid;

    $whatSaritasaDid = explode('<a ', $whatSaritasaDid)[0];

    $blocks['whatSaritasaDid'] = '<h3 class="what-did-title">What Saritasa Did</h3><div class="what-did-list">' . $whatSaritasaDid . '</div>';
    $blocks['whatSaritasaDid'] .= '<div class="what-did-img">' . $mainImage . '</div>';
    #var_dump($blocks['ourSolution']);
    #var_dump($blocks['whatSaritasaDid']);
    $blocks['links'] = str_replace($whatSaritasaDid, '', $blocks['links']);
    $chkArray = ['title', 'theirChallenge', 'ourSolution', 'mainImage', 'whatSaritasaDid', 'links'];

    foreach ($chkArray as $el) {
        if (!isset($blocks[$el]) || $blocks[$el] == '') {
            echo ('<pre>' . $el . ' is not exists.</pre>');
        }
    }

    return $blocks;
}

function parceType2($content = '')
{
    if ($content == ''){
        return false;
    }

    $c1 = explode('</h1>', $content);
    //var_dump($c1);
    $blocks['overview'] = isset($c1[1]) && !empty($c1[1]) ? '<div class="overview-content">' . trim(explode('<strong>', $c1[1])[0]) . '</div>' : false;
    $blocks['overview'] = '<h2 class="overview-title">Overview</h2>' . $blocks['overview'];

    $c1 = explode('<h1', $content);

    $blocks['carousel'] = $c1[0];

    if (isset($c1[1]) && strpos($c1[1], '</h1>')) {
        $blocks['title'] = '<h1' . $c1[1];

        $title = explode('</h1>', $blocks['title'])[0] ?? '';
        $title = !empty($title) ? $title . '</h1>' : false;

        $blocks['title'] = $title;
    }

    $keyFeatures = explode('KEY FEATURES', $content)[1] ?? '';
    $keyFeatures = !empty($keyFeatures) ? '<strong class="key-features-title">Key Features' . $keyFeatures : false;

    $blocks['keyFeatures'] = $keyFeatures;

    $chkArray = ['overview', 'carousel', 'title', 'keyFeatures'];

    foreach ($chkArray as $el) {
        if (!isset($blocks[$el]) || $blocks[$el] == '') {
            echo ('<pre>' . $el . ' is not exists.</pre>');
        }
    }

    return $blocks;
}