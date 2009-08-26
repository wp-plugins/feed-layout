<?php
/*
Plugin Name: Feed Layout
Plugin URI: http://www.satollo.com/english/wordpress/feed-layout
Description: Feed Layout enables controls to modify the feed contents adding headers, footers, copyright notices.
Version: 1.1.0
Author: Satollo
Author URI: http://www.satollo.com
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008  Satollo  (email : satollo@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_filter("plugin_action_links_feed-layout/plugin.php", 'fdl_plugin_action_links');
function fdl_plugin_action_links($links) 
{ 
	$settings_link = '<a href="options-general.php?page=feed-layout/options.php">' . __('Settings') . '</a>'; 
	array_unshift($links, $settings_link); 
 	return $links;
}

add_action('the_content', 'fdl_the_content', 70);
function fdl_the_content(&$content)
{
	if (!is_feed()) return $content;

    $options = get_option('fdl');

	$link = get_permalink();
	$title = htmlspecialchars(get_the_title());
	
    $before = $options['before'];
    $after = $options['after'];
    $more = $options['more'];
	
	if ($options['break']) 
	{
		$x = strpos($content, '<span id="more');	
		if ($x !== false)
        {
			$content = substr($content, 0, $x) . '</p>';
		}
	}

    if (strpos($before, '<?') !== false) 
    {
        ob_start();
        eval('?>' . $before);
        $before = ob_get_contents();
        ob_end_clean();
    }
    
    if (strpos($after, '<?') !== false) 
    {
        ob_start();
        eval('?>' . $after);
        $after = ob_get_contents();
        ob_end_clean();
    }

    $x = strpos($content, '<span id="more');
    if ($x !== false)
    {
        // span end
        $x = strpos($content, '>', $x);
        if ($x !== false)
        {
        
            if (strpos($more, '<?') !== false) 
            {
                ob_start();
                eval('?>' . $more);
                $more = ob_get_contents();
                ob_end_clean();
            }        

            $content = substr($content, 0, $x+1) . $more . substr($content, $x+1);
        }
    }

	$before = str_replace('[title]', $title, $before);
    $before = str_replace('[link]', $link, $before);
	$after = str_replace('[title]', $title, $after);
    $after = str_replace('[link]', $link, $after);

    return $before . $content . $after;
}

add_action('admin_menu', 'fdl_admin_menu');
function fdl_admin_menu()
{
    add_options_page('Feed Layout', 'Feed Layout', 'manage_options', 'feed-layout/options.php');
}

?>
