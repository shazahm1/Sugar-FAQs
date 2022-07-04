<?php
/**
 * Topics Widget.
 *
 * @category   WordPress\Plugin
 * @package    Sugar FAQs
 * @license    GPL-2.0+
 */

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
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<ul class="sf-topics">
			<?php
			$args_list = array(
				'taxonomy'     => 'faq_topics', // Registered tax name
				'title_li'     => '',
				'show_count'   => $instance['show_count'],
				'hierarchical' => $instance['hierarchical'] ? true : false,
				'echo'         => '0',
			);

			echo wp_list_categories( $args_list );
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
	function form( $instance ) {

		$instance = wp_parse_args( $instance, $this->get_defaults() );

		$title        = $instance['title'];
		$show_count   = $instance['show_count'];
		$hierarchical = $instance['hierarchical'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'sugar_faqs' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" type="checkbox" value="1" <?php checked( '1', $show_count ); ?>/>
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e( 'Display FAQ count?', 'sugar_faqs' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>" type="checkbox" value="1" <?php checked( '1', $hierarchical ); ?>/>
			<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>"><?php _e( 'Display Hierarchically', 'sugar_faqs' ); ?></label>
		</p>
		<?php
	}
}

/**
 * Register the Topics widget.
 */
add_action(
	'widgets_init',
	static function() {
		register_widget( 'sf_topics_widget' );
	}
);
