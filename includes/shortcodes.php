<?php
function sf_display_faqs( $atts, $content = NULL ) {

	global $sf_options, $sf_load_scripts;

	$sf_load_scripts = TRUE;

	$atts = shortcode_atts(
		array(
			'topicalize'   => 'false',
			'topic'        => '',
			'width'        => '',
			'hierarchical' => 'false',
			'color'        => '',
			'accordion'    => 'true',
			'topic_tag'    => 'h2',
			'title_tag'    => 'h3',
		),
		$atts
	);

	if ( $atts['width'] == '' ) {
		$faqs_width = $sf_options['width'];
	} else {
		$faqs_width = $atts['width'];
	}
	if ( $atts['color'] == '' ) {
		$faqs_color = $sf_options['style'];
	} else {
		$faqs_color = $atts['color'];
	}

	if ( $atts['topic'] && $atts['hierarchical'] == 'false' ) {
		$atts['topicalize'] = 'false';
	}
	if ( ! empty( $faqs_width ) || $sf_options['icon'] == TRUE ) {
		$content .= '<style type="text/css">';
		if ( ! empty( $faqs_width ) ) {
			$content .= '.sugar-faqs-wrap { width: ' . $faqs_width . 'px; }';
		}
		if ( $sf_options['icon'] == TRUE ) {
			$content .= '.sugar-faqs-wrap .trigger { padding-left: 10px; }';
		}
		$content .= '</style>';
	}
	if ( $atts['topicalize'] == 'true' && $atts['hierarchical'] == 'false' ) {

		$content .= '<div class="sugar-faqs-wrap">';
		$terms = get_terms( 'faq_topics', $atts );
		if ( ! empty( $terms ) ):
			foreach ( $terms as $i => $term ):
				$content .= '<h2 class="faq-topic">' . $term->name . '</h2>';

				$faqs = get_posts(
					'posts_per_page=-1&post_type=faqs&faq_topics=' . $term->slug . '&orderby=' . $sf_options['order'] . '&order=' . $sf_options['direction']
				);
				global $post;
				$temp = $post;

				if ( $faqs ) :
					foreach ( $faqs as $post ) : setup_postdata( $post );
						$content .= '<div class="faq-section ' . $faqs_color . '">';
						$content .= '<h3 class="trigger settings">';
						if ( $sf_options['icon'] != TRUE ) {
							$content .= '<span class="icon"><span class="icon-image"></span></span> ';
						}
						$content .= get_the_title() . '<span class="trigger-end"></span></h3>';

						$content .= '<div class="faq-content">';
						$wp_syntax_applied = WP_Syntax::beforeFilter( get_the_content() );
						$wp_syntax_applied = do_shortcode( wptexturize( wpautop( $wp_syntax_applied ) ) );
						$wp_syntax_applied = WP_Syntax::afterFilter( $wp_syntax_applied );
						$content .= $wp_syntax_applied;
						$content .= '<p style="margin-bottom: 0px; text-align: right;"><a title="Permalink" href="' . get_permalink(
							) . '">Permalink</a>';
						$content .= ' | ';

						$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

						if ( comments_open() ) {
							if ( $num_comments == 0 ) {
								$comments = __( 'No Comments' );
							} elseif ( $num_comments > 1 ) {
								$comments = __( 'Comment' ) . '(' . $num_comments . ')';
							} else {
								$comments = __( 'Comment' ) . '(' . $num_comments . ')';
							}
							$content .= '<a href="' . get_comments_link() . '">' . $comments . '</a>';
						} else {
							$content .= __( 'Comments Closed' );
						}

						$content .= '</p>';


						$content .= '</div>';
						$content .= '</div>';
					endforeach;

					wp_reset_postdata();

				endif;
				$post = $temp;
			endforeach;
		endif;
		$content .= '</div>';

	} elseif ( $atts['topicalize'] == 'true' && $atts['hierarchical'] == 'true' ) {

		global $post;

		$temp = $post;

		if ( 'true' == $atts['accordion'] ) $content .= '<div class="sugar-faqs-wrap">';

		$topic_id = get_term_by( 'slug', $atts['topic'], 'faq_topics' );

		$term_query_args = array(
			'taxonomy' => 'faq_topics',
			'child_of' => $topic_id->term_id,
		);

		$taxonomy = new WP_Term_Query( $term_query_args );

		if ( ! empty( $taxonomy->terms ) ) {

			foreach ( $taxonomy->terms as $term ) {

				$content .= sprintf(
					'<%1$s class="faq-topic">%2$s</%1$s>',
					in_array( $atts['topic_tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ? esc_attr( $atts['topic_tag'] ) : 'h2',
					esc_html( $term->name )
				);

				// $faqs = get_posts( 'posts_per_page=-1&post_type=faqs&faq_topics=' . $term->slug . '&orderby=' . $sf_options['order'] . '&order=' . $sf_options['direction'] );

				$faq_query_args = array(
					'post_status'      => 'publish',
					'post_type'        => 'faqs',
					'posts_per_page'   => -1,
					'tax_query'        => array(
						array(
							'taxonomy' => 'faq_topics',
							'terms'    => $term->term_id,
							'field'    => 'term_id',
							'operator' => 'IN',
						),
					),
					'orderby'          => $sf_options['order'],
					'order'            => $sf_options['direction'],
					'suppress_filters' => true,
				);

				$faqs = new WP_Query( $faq_query_args );

				if ( $faqs->posts ) {

					foreach ( $faqs->posts as $post ) {

						setup_postdata( $post );

						if ( 'true' == $atts['accordion'] ) $content .= '<div class="faq-section ' . $faqs_color . '">';

						//$content .= '<h3 class="trigger settings">';
						$content .= sprintf(
							'<%1$s class="trigger settings">',
							in_array( $atts['title_tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ? esc_attr( $atts['title_tag'] ) : 'h3'
						);

						if ( $sf_options['icon'] != TRUE && 'true' == $atts['accordion'] ) {

							if ( 'true' == $atts['accordion'] ) $content .= '<span class="icon"><span class="icon-image"></span></span> ';
						}

						$content .= get_the_title() . '<span class="trigger-end"></span>';

						//$content .= '</h3>';
						$content .= sprintf(
							'</%1$s>',
							in_array( $atts['title_tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ? esc_attr( $atts['title_tag'] ) : 'h3'
						);

							if ( 'true' == $atts['accordion'] ) $content .= '<div class="faq-content">';

							$wp_syntax_applied = WP_Syntax::beforeFilter( get_the_content() );
							$wp_syntax_applied = do_shortcode( wptexturize( wpautop( $wp_syntax_applied ) ) );
							$wp_syntax_applied = WP_Syntax::afterFilter( $wp_syntax_applied );
							$content .= $wp_syntax_applied;

							$content .= '<p style="' . ( 'true' == $atts['accordion'] ? 'margin-bottom: 0px; ' : '' ) . 'text-align: right;"><a title="Permalink" href="' . get_permalink() . '">Permalink</a>';
							$content .= ' | ';

							$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

							if ( comments_open() ) {
								if ( $num_comments == 0 ) {
									$comments = __( 'No Comments' );
								} elseif ( $num_comments > 1 ) {
									$comments = __( 'Comment' ) . '(' . $num_comments . ')';
								} else {
									$comments = __( 'Comment' ) . '(' . $num_comments . ')';
								}
								$content .= '<a href="' . get_comments_link() . '">' . $comments . '</a>';
							} else {
								$content .= __( 'Comments Closed' );
							}

							$content .= '</p>';
							if ( 'true' == $atts['accordion'] ) $content .= '</div>'; /* /.faq-content */
						if ( 'true' == $atts['accordion'] ) $content .= '</div>'; /* /.faq-section */
					}

					wp_reset_postdata();
				}
			}
		}

		if ( 'true' == $atts['accordion'] ) $content .= '</div>'; /* /.sugar-faqs-wrap */

		$post = $temp;

	} else {
		$content .= '<div class="sugar-faqs-wrap">';
		$faqs = get_posts( 'posts_per_page=-1&post_type=faqs&faq_topics=' . $atts['topic'] . '&orderby=' . $sf_options['order'] . '&order=' . $sf_options['direction'] );
		global $post;
		$temp = $post;

		if ( $faqs ) :
			foreach ( $faqs as $post ) : setup_postdata( $post );
				$content .= '<div class="faq-section ' . $faqs_color . '">';
				$content .= '<h3 class="trigger settings">';
				if ( $sf_options['icon'] != TRUE ) {
					$content .= '<span class="icon"><span class="icon-image"></span></span> ';
				}
				$content .= get_the_title() . '<span class="trigger-end"></span></h3>';

				$content .= '<div class="faq-content">';
				$wp_syntax_applied = WP_Syntax::beforeFilter( get_the_content() );
				$wp_syntax_applied = do_shortcode( wptexturize( wpautop( $wp_syntax_applied ) ) );
				$wp_syntax_applied = WP_Syntax::afterFilter( $wp_syntax_applied );
				$content .= $wp_syntax_applied;
				$content .= '<p style="margin-bottom: 0px; text-align: right;"><a title="Permalink" href="' . get_permalink() . '">Permalink</a>';$content .= ' | ';

				$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

				if ( comments_open() ) {
					if ( $num_comments == 0 ) {
						$comments = __( 'No Comments' );
					} elseif ( $num_comments > 1 ) {
						$comments = __( 'Comment' ) . '(' . $num_comments . ')';
					} else {
						$comments = __( 'Comment' ) . '(' . $num_comments . ')';
					}
					$content .= '<a href="' . get_comments_link() . '">' . $comments . '</a>';
				} else {
					$content .= __( 'Comments Closed' );
				}

				$content .= '</p>';
				$content .= '</div>';
				$content .= '</div>';
			endforeach;

			wp_reset_postdata();

		endif;
		$post = $temp;
		$content .= '</div>';
	}

	return $content;
}

add_shortcode( 'faqs', 'sf_display_faqs' );
