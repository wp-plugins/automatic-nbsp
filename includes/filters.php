<?php

/*
 * Wordpress filters
 */

if (!defined('ABSPATH'))
    exit;

global $dgwt_nbsp_settings;

/*
 * Adds &nbsp; to content, excerpt, comments and widgets
 */
function webtroter_automatic_nbsp($content) {
   
    // Get phrases list
    $phrases = dgwt_nbsp_get_phrases();
    
    // If phrases exists
    if ($phrases) {

        $pattern = array();

        foreach ($phrases as $phrase) {
            
            //Possible beginnings of phrases
            $beginnings = dgwt_nbsp_get_phrases_beginnings();
            
            // Pattern: beginning + word/phrase + whitespace
            foreach ($beginnings as $beginning){
               $pattern[] = '/' . $beginning . '+' . $phrase . '+\\s+/'; 
            }
            
        }

        // Add &nbsp;
        $new_content = preg_replace_callback($pattern, 'dgwt_nbsp_format_matches', $content);

        return $new_content;
    }

    return $content;
}

add_filter('the_content', 'webtroter_automatic_nbsp');

if (isset($dgwt_nbsp_settings['excerpt']) && $dgwt_nbsp_settings['excerpt'] === '1') {
    add_filter('the_excerpt', 'webtroter_automatic_nbsp');
}
if (isset($dgwt_nbsp_settings['comment']) && $dgwt_nbsp_settings['comment'] === '1') {
    add_filter('comment_text', 'webtroter_automatic_nbsp');
}
if (isset($dgwt_nbsp_settings['widget']) && $dgwt_nbsp_settings['widget'] === '1') {
    add_filter('widget_text', 'webtroter_automatic_nbsp');
}

/*
 * Adds &nbsp; to titles
 */
function webtroter_automatic_nbsp_title($title) {
    // Get phrases list
    $phrases = dgwt_nbsp_get_phrases();
    
    // If phrases exists
    if ($phrases) {

        $pattern = array();

        foreach ($phrases as $phrase) {

            // Pattern: whitespace + word/phrase + whitespace
               $pattern[] = '/\\s+' . $phrase . '+\\s+/'; 
        }

        // Add &nbsp;
        $new_title = preg_replace_callback($pattern, 'dgwt_nbsp_format_matches', $title);

        return $new_title;
    }

    return $new_title;
}


if (isset($dgwt_nbsp_settings['title']) && $dgwt_nbsp_settings['title'] === '1') {
add_filter( 'the_title', 'webtroter_automatic_nbsp_title');
}
