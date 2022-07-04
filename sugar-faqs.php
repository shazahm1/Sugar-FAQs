<?php
/*
Plugin Name: Sugar FAQs for WordPress
Plugin URI: http://pippinsplugins.com/sugar-faqs-wordpress-faqs-management
Description: Provides a robust FAQs management system for WordPress
Version: 1.2.3
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
*/

/************************************
gloabl variables
************************************/

$sf_version = '1.2.3';

$sfBaseDir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));

$sf_options = get_option( 'sf_settings' );

$sf_load_scripts = false;

/************************************
includes
************************************/

include_once('includes/post-types.php');
include_once('includes/taxonomies.php');
include_once('includes/scripts.php');
include_once('includes/shortcodes.php');
include_once('includes/misc-functions.php');
include_once('includes/submission-form.php');

if(is_admin()) {
	include_once('includes/settings.php');
	include_once('includes/metabox.php');
	include_once('includes/help.php');
}

/*************************************
register the topics widget
*************************************/
class sf_topics_widget extends WP_Widget {

	/** constructor */
	function __construct() {
		parent::__construct( '', 'FAQ Topics' );
	}

	/**
	 * @return array{title: string, show_count: string, hierarchical: string}
	 */
	function get_defaults() {

		return array(
			'title'        => '',
			'show_count'   => '0',
			'hierarchical' => '0',
		);
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']);
		?>
			  <?php echo $before_widget; ?>
				  <?php if ( $title )
						echo $before_title . $title . $after_title; ?>
							<ul class="sf-topics">
								<?php
									$args_list = array(
										'taxonomy' => 'faq_topics', // Registered tax name
										'title_li' => '',
										'show_count' => $instance['show_count'],
										'hierarchical' => $instance['hierarchical'] ? true : false,
										'echo' => '0',
										);

									echo wp_list_categories($args_list);
								?>
							</ul>
			  <?php echo $after_widget; ?>
		<?php
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {

		$instance                 = $old_instance;
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['show_count']   = $new_instance['show_count'];
		$instance['hierarchical'] = $new_instance['hierarchical'];

		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {

		$instance = wp_parse_args( $instance, $this->get_defaults() );

		$title        = $instance['title'];
		$show_count   = $instance['show_count'];
		$hierarchical = $instance['hierarchical'];
		?>
		 <p>
		  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		  <input id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>" type="checkbox" value="1" <?php checked( '1', $show_count ); ?>/>
		  <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Display FAQ count?'); ?></label>
		</p>
		<p>
		  <input id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>" type="checkbox" value="1" <?php checked( '1', $hierarchical ); ?>/>
		  <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e('Display Hierarchically'); ?></label>
		</p>
		<?php
	}


} // end sf_topics_widget class

// Register Topics widget.
add_action(
	'widgets_init',
	static function() {
		register_widget( 'sf_topics_widget' );
	}
);
