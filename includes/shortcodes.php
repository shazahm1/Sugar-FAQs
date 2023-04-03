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
						// $wp_syntax_applied = WP_Syntax::beforeFilter( get_the_content() );
						// $wp_syntax_applied = do_shortcode( wptexturize( wpautop( $wp_syntax_applied ) ) );
						// $wp_syntax_applied = WP_Syntax::afterFilter( $wp_syntax_applied );
						$content .= apply_filters( 'the_content', get_the_content() );
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

		$iconComments  = '<div class="icon-container" style="width:16px"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M18 4H6c-1.1 0-2 .9-2 2v12.9c0 .6.5 1.1 1.1 1.1.3 0 .5-.1.8-.3L8.5 17H18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm.5 11c0 .3-.2.5-.5.5H7.9l-2.4 2.4V6c0-.3.2-.5.5-.5h12c.3 0 .5.2.5.5v9z"></path></svg></div>';
		$iconPermalink = '<div class="icon-container" style="width:16px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"><path d="M19.647,16.706a1.134,1.134,0,0,0-.343-.833l-2.549-2.549a1.134,1.134,0,0,0-.833-.343,1.168,1.168,0,0,0-.883.392l.233.226q.2.189.264.264a2.922,2.922,0,0,1,.184.233.986.986,0,0,1,.159.312,1.242,1.242,0,0,1,.043.337,1.172,1.172,0,0,1-1.176,1.176,1.237,1.237,0,0,1-.337-.043,1,1,0,0,1-.312-.159,2.76,2.76,0,0,1-.233-.184q-.073-.068-.264-.264l-.226-.233a1.19,1.19,0,0,0-.4.895,1.134,1.134,0,0,0,.343.833L15.837,19.3a1.13,1.13,0,0,0,.833.331,1.18,1.18,0,0,0,.833-.318l1.8-1.789a1.12,1.12,0,0,0,.343-.821Zm-8.615-8.64a1.134,1.134,0,0,0-.343-.833L8.163,4.7a1.134,1.134,0,0,0-.833-.343,1.184,1.184,0,0,0-.833.331L4.7,6.473a1.12,1.12,0,0,0-.343.821,1.134,1.134,0,0,0,.343.833l2.549,2.549a1.13,1.13,0,0,0,.833.331,1.184,1.184,0,0,0,.883-.38L8.728,10.4q-.2-.189-.264-.264A2.922,2.922,0,0,1,8.28,9.9a.986.986,0,0,1-.159-.312,1.242,1.242,0,0,1-.043-.337A1.172,1.172,0,0,1,9.254,8.079a1.237,1.237,0,0,1,.337.043,1,1,0,0,1,.312.159,2.761,2.761,0,0,1,.233.184q.073.068.264.264l.226.233a1.19,1.19,0,0,0,.4-.895ZM22,16.706a3.343,3.343,0,0,1-1.042,2.488l-1.8,1.789a3.536,3.536,0,0,1-4.988-.025l-2.525-2.537a3.384,3.384,0,0,1-1.017-2.488,3.448,3.448,0,0,1,1.078-2.561l-1.078-1.078a3.434,3.434,0,0,1-2.549,1.078,3.4,3.4,0,0,1-2.5-1.029L3.029,9.794A3.4,3.4,0,0,1,2,7.294,3.343,3.343,0,0,1,3.042,4.806l1.8-1.789A3.384,3.384,0,0,1,7.331,2a3.357,3.357,0,0,1,2.5,1.042l2.525,2.537a3.384,3.384,0,0,1,1.017,2.488,3.448,3.448,0,0,1-1.078,2.561l1.078,1.078a3.551,3.551,0,0,1,5.049-.049l2.549,2.549A3.4,3.4,0,0,1,22,16.706Z"></path></svg></div>';

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

						$permalinks = array();

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

						$content .= sprintf(
							'</%1$s>',
							in_array( $atts['title_tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ? esc_attr( $atts['title_tag'] ) : 'h3'
						);

							if ( 'true' == $atts['accordion'] ) $content .= '<div class="faq-content">';

							// $wp_syntax_applied = WP_Syntax::beforeFilter( get_the_content() );
							// $wp_syntax_applied = do_shortcode( wptexturize( wpautop( $wp_syntax_applied ) ) );
							// $wp_syntax_applied = WP_Syntax::afterFilter( $wp_syntax_applied );
							$content .= apply_filters( 'the_content', get_the_content() );

							$content .= '<div style="' . ( 'true' == $atts['accordion'] ? 'margin-bottom: 0px; ' : 'margin-bottom: 24px;' ) . 'text-align: right;">';

							$permalinks[] = '<a title="Link to ' . get_the_title() . '" href="' . get_permalink(
								) . '">' . $iconPermalink . '</a>';

							if ( comments_open() ) {

								$num_comments = get_comments_number();

								if ( $num_comments == 0 ) {
									$comments = __( 'No Comments' );
								} elseif ( $num_comments > 1 ) {
									$comments = __( 'Comment' ) . '(' . $num_comments . ')';
								} else {
									$comments = __( 'Comment' ) . '(' . $num_comments . ')';
								}

								$permalinks[] = '<a href="' . get_comments_link() . '" title="' . $comments . '">' . $iconComments . '</a>';

							} else {

								$permalinks[] = "<span title=\"Comments Closed\">{$iconComments}</span>";
							}

							$content .= '<span>' . implode( ' | ', $permalinks ) . '</span>';
							$content .= '</div>';
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
				// $wp_syntax_applied = WP_Syntax::beforeFilter( get_the_content() );
				// $wp_syntax_applied = do_shortcode( wptexturize( wpautop( $wp_syntax_applied ) ) );
				// $wp_syntax_applied = WP_Syntax::afterFilter( $wp_syntax_applied );
				$content .= apply_filters( 'the_content', get_the_content() );
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
