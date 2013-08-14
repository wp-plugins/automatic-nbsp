<?php

/*
 Plugin Name: Automatic NBSP
 Description: This plugin automatically adds HTML non-breaking space (&nbsp) in your posts, custom post types and pages. In settings of plugin you can add words. Each added word will not be displayed on the end of line in text, but was moved to beginning of next line.
 Version: 1.0
 Author: Damian Góra 
 License: GPLv2 or later
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( !function_exists( 'add_action' ) ) {
	exit;
}

if ( is_admin() )
	require_once dirname( __FILE__ ) . '/admin.php';

add_filter('the_content', 'dg_automatic_nbsp');


function dg_automatic_nbsp($content) {

    if (is_singular()) {

        $words = array();
        $excluded = array();
        
        // Get users words from DB
        $conjunctions = get_option('dg_automatic_nbsp');
        $conjunctions = explode(',', $conjunctions);
        
        $content = explode(' ', $content);
        
        $number_of_items = count($content);
        for ($i = 0; $i <= $number_of_items - 1; $i++) {

            foreach ($conjunctions as $item):

                $item_lower = strtolower($item);
                $cons_lower = strtolower($content[$i]);
                
                if ($cons_lower == $item_lower) {
                    
                    $next = $i + 1;

                    //Modify the word and save it to new array
                    $content[$i] = $content[$i] . '&nbsp;' . $content[$next];
                    $words[$i] = $content[$i];
                    // Create index of double words
                    array_push($excluded, $next);
                    
                } else {
                    $words[$i] = $content[$i];
                }

            endforeach;
        }

        
        $excluded_count = count($excluded);

        for ($i = 0; $i <= $excluded_count - 1; $i++) {

            // Remove double words from finnaly array
            $excluded_ID = $excluded[$i];
            unset($words[$excluded_ID]);
        }

        $clear_content = implode(' ', $words);
        return $clear_content;
    }else {
        return $content;
    }
    
}


// Settings link
function our_plugin_action_links($links, $file) {
    static $this_plugin;
    
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }
 
    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {
        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=dg_automatic_nbsp_id">Settings</a>';
        // add the link to the list
        array_unshift($links, $settings_link);
    }
 
    return $links;
}

add_filter('plugin_action_links', 'our_plugin_action_links', 10, 2);


?>