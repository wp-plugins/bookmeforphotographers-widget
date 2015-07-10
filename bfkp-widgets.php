<?php 
/*
Plugin Name: Bookmeforphotographers Widgets
Plugin URI: http://www.bookmeforphotographers.com
Description: Shortcode and Widget Creator
Version: 1.0.0
Author: Tiago G
License: GPL2
Copyright 2014 Tiago Gomes

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
 * Shortcode Syntax
 * [ BKFP url='yourbookinglink' ]
 * Attributes
 * url - Your booking url
 * ------------
 * default values
 * url=yourbookinglink
 */

if ( !class_exists('BKFP') )
{
	class BKFP
	{

		function __construct(){
			add_shortcode('BKFP',array(&$this,'shortcode'));
		
		}
		function shortcode($atts)
		{
            		$base_url = "http://schedules.bookmeforphotographers.com/widgets/";
			extract( shortcode_atts( array(
					'url' => '',
				), $atts ) );
			return sprintf('<div id="div-bk"></div><script>(function(d, s, id) {var js, bks = d.getElementsByTagName(s)[0];js = d.createElement(s); js.id = id;js.src = "%s.js";bks.parentNode.insertBefore(js, bks);}(document, "script", "div-bk-js" ));</script>', $base_url.'button/'.base64_encode($url) );
        }
	}

}	
if ( !class_exists('BKFPWidget') )
{

	
	class BKFPWidget {
		function __construct()
		{
			$this->data = array(
                'url' => '',
			);
			if ( !get_option('BKFPWidget') ) add_option('BKFPWidget',$this->data);
			
		}
		function control()
		{
			$data = get_option('BKFPWidget');
			extract($data);
			echo '<div>';
			
			printf('<label><b>Schedule Link</b>:</label><br/><input required type="text" placeholder="url" name="BKFPWidgetUrl" value="%s" /></label>',$url);

			echo '</div>';

			if ( !empty($_POST['BKFPWidgetUrl']) ) $data['url'] = trim($_POST['BKFPWidgetUrl']);

			update_option('BKFPWidget',$data);

  		}
		function widget($args)
		{
			extract(get_option('BKFPWidget'));
    			echo $args['before_widget'];
    			echo do_shortcode(sprintf('[BKFP url=%s ]',base64_encode($url)));
    			echo $args['after_widget'];
  		}
		function register()
		{
		
            wp_register_sidebar_widget(
                'BKFP_widget_1',        // your unique widget id
                'BKFP Widgets',          // widget name
                array('BKFPWidget', 'widget'),  // callback function
                array(                  // options
                    'description' => 'Create BKFP Widgets'
                )
            );

            wp_register_widget_control(
                'BKFP_widget_1',        // your unique widget id
                'BKFP Widgets',          // widget name
                array('BKFPWidget', 'control'),  // callback function
                array(                  // options
                    'description' => 'Create BKFP Widgets'
                )
            );
    		
  		}

    }
	
}
new BKFP();
new BKFPWidget();
add_action("widgets_init", array('BKFPWidget', 'register'));
?>
