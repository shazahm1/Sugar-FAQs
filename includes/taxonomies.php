<?php

//Create project taxonomies
add_action( 'init', 'sf_create_taxonomies' );

function sf_create_taxonomies() {
	$topic_labels = array(
		'name'                  => __( 'Topics' ),
		'singular_name'         => __( 'Topic' ),
		'search_items'          => __( 'Search Topics' ),
		'all_items'             => __( 'All Topics' ),
		'parent_item'           => __( 'Parent Topic' ),
		'parent_item_colon'     => __( 'Parent Topic:' ),
		'edit_item'             => __( 'Edit Topic' ),
		'update_item'           => __( 'Update Topic' ),
		'add_new_item'          => __( 'Add New Topic' ),
		'new_item_name'         => __( 'New Topic Name' ),
		'choose_from_most_used' => __( 'Choose from the most used Topics' ),
	);

	register_taxonomy( 'faq_topics', 'faqs', array(
		'hierarchical' => true,
		'labels'       => $topic_labels,
		'query_var'    => true,
		'rewrite'      => array( 'slug' => 'faq-topics' ),
	) );
}
