<?php

/******************************
create the FAQ post type
*******************************/

function sf_create_post_type() {

	$labels = array(
		'name'               => _x( 'FAQs', 'post type general name' ),
		'singular_name'      => _x( 'FAQ', 'post type singular name' ),
		'add_new'            => _x( 'Add New FAQ', 'FAQ' ),
		'add_new_item'       => __( 'Add New FAQ' ),
		'edit_item'          => __( 'Edit FAQ' ),
		'new_item'           => __( 'New FAQ' ),
		'view_item'          => __( 'View FAQ' ),
		'search_items'       => __( 'Search FAQs' ),
		'not_found'          => __( 'No FAQs found' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash' ),
		'parent_item_colon'  => '',
	);

	$supports = array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'page-attributes', 'custom-fields' );

	$faq_args = array(
		'labels'          => $labels,
		'singular_label'  => __( 'FAQ' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'has_archive'     => true,
		'hierarchical'    => false,
		'rewrite'         => array( 'slug' => 'faqs' ),
		'supports'        => $supports,
		'menu_position'   => 25,
		'show_in_rest'   => true,
	);

	register_post_type( 'faqs', $faq_args );
}

add_action( 'init', 'sf_create_post_type' );


// modify FAQ columns

function sf_edit_columns($faq_columns){
	$faq_columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Question",
		"answer" => "Answer",
		"topics" => "Topics",
		"author" => "Author",
		"date" => "Date",
	);
	return $faq_columns;
}
function faq_columns_display($faq_columns){
	switch ($faq_columns)
	{
  		case "topics":
  			//echo get_the_term_list( $post->ID, 'faq_topics', '<ul><li>','</li><li>','</li></ul>');
			//echo get_the_term_list( $post->ID, 'faq_topics', '',', ','');

		$_posttype 	= 'faqs';
		$_taxonomy 	= 'faq_topics';
		$terms 		= get_the_terms( $post->ID, $_taxonomy );

		if ( ! empty( $terms ) ) {
			$out = array();
			foreach ( $terms as $c ) {
				$_taxonomy_title = esc_html(sanitize_term_field('name', $c->name, $c->term_id, $_taxonomy, 'display'));
				$_taxonomy_slug = esc_html(sanitize_term_field('slug', $c->name, $c->term_id, $_taxonomy, 'db'));

				$out[] = "<a href='edit.php?post_type=$_posttype&$_taxonomy=$c->term_id'>$_taxonomy_title</a>";
			}
			echo join( ', ', $out );
		}
		else {
			_e('No Topics Assigned');
		}

  			break;
		case "answer":
  			sf_answer_excerpt(140);
  			break;
	}
}
add_filter("manage_edit-faqs_columns", "sf_edit_columns");
add_action("manage_posts_custom_column",  "faq_columns_display");


// changes the "Enter title here" to "Enter question here" for FAQs
add_filter('gettext', 'custom_rewrites', 10, 4);
function custom_rewrites($translation, $text, $domain) {
	global $post;

	if ( ! isset( $post->post_type ) ) {
		return $translation;
	}

	$translations = get_translations_for_domain($domain);
	$translation_array = array();

	switch ($post->post_type) {
		case 'faqs':
			$translation_array = array(
				'Enter title here' => 'Enter question here'
			);
			break;
	}

	if (array_key_exists($text, $translation_array)) {
		return $translations->translate($translation_array[$text]);
	}

	return $translation;
}

//add filter to ensure the text Book, or book, is displayed when user updates a book
add_filter('post_updated_messages', 'faq_updated_messages');
function faq_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['faqs'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('FAQ updated. <a href="%s">View FAQ>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('FAQ updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('FAQ restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('FAQ published. <a href="%s">View FAQ</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('FAQ saved.'),
    8 => sprintf( __('FAQ submitted. <a target="_blank" href="%s">Preview FAQ</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('FAQ scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview FAQ</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('FAQ draft updated. <a target="_blank" href="%s">Preview FAQ</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

/*
 * Add a taxonomy filter
 * Credit to Joost de Valk
 * http://yoast.com/custom-post-type-snippets/
 */
// Filter the request to just give posts for the given taxonomy, if applicable.
function sf_taxonomy_filter_restrict_manage_posts( $post_type, $which ) {
    global $typenow;

    // If you only want this to work for your specific post type,
    // check for that $type here and then return.
    // This function, if unmodified, will add the dropdown for each
    // post type / taxonomy combination.

	if ( 'faqs' !== $post_type ) {
		return;
	}

    $post_types = get_post_types( array( '_builtin' => false ) );

    if ( in_array( $typenow, $post_types ) ) {
    	$filters = get_object_taxonomies( $typenow );

        foreach ( $filters as $tax_slug ) {
            $tax_obj = get_taxonomy( $tax_slug );
            wp_dropdown_categories( array(
                'show_option_all' => __('Show All '.$tax_obj->label ),
                'taxonomy' 	  => $tax_slug,
                'name' 		  => $tax_obj->name,
                'orderby' 	  => 'name',
                'selected' 	  => $_GET[$tax_slug],
                'hierarchical' 	  => $tax_obj->hierarchical,
                'show_count' 	  => false,
                'hide_empty' 	  => true
            ) );
        }
    }
}

add_action( 'restrict_manage_posts', 'sf_taxonomy_filter_restrict_manage_posts', 10, 2 );

function sf_taxonomy_filter_post_type_request( $query ) {
  global $pagenow, $typenow;

  if ( 'edit.php' == $pagenow ) {
    $filters = get_object_taxonomies( $typenow );
    foreach ( $filters as $tax_slug ) {
      $var = &$query->query_vars[$tax_slug];
      if ( isset( $var ) ) {
        $term = get_term_by( 'id', $var, $tax_slug );
        $var = $term->slug;
      }
    }
  }
}

add_filter( 'parse_query', 'sf_taxonomy_filter_post_type_request' );



// flush the permalinks on plugin activation
function sf_rewrite_flush() {
  sf_create_post_type();
  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sf_rewrite_flush');

?>
