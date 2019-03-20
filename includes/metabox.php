<?php
$sf_prefix = 'sf_post_meta_';

$sf_meta_box = array(
    'id' => 'sugar-faqs-meta',
    'title' => 'Question Meta Information',
    'page' => 'faqs',
    'context' => 'normal',
    'priority' => 'core',
    'fields' => array(
        array(
            'name' => 'Asker Name',
            'desc' => 'Name of the person who submitted the question',
            'id' => $sf_prefix . 'author_name',
            'type' => 'text',
            'std' => ''
        ),
		array(
            'name' => 'Asker Email',
            'desc' => 'email of the person who submitted the question',
            'id' => $sf_prefix . 'author_email',
            'type' => 'text',
            'std' => ''
        )
    )
);

add_action('admin_menu', 'sf_add_box');

function sf_add_box() {
    global $sf_meta_box;
    
    add_meta_box($sf_meta_box['id'], $sf_meta_box['title'], 'sf_show_box', $sf_meta_box['page'], $sf_meta_box['context'], $sf_meta_box['priority']);
}

// Callback function to show fields in meta box
function sf_show_box() {
    global $sf_meta_box, $post;
    
    // Use nonce for verification
    echo '<input type="hidden" name="sf_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';

    foreach ($sf_meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
		$submitted = get_post_meta($post->ID, 'sf_post_meta_submitted', true);
        
        echo '<tr>',
                '<th style="width:20%">', $field['name'], '</th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
				if($submitted == true) {
					echo '<strong>', $meta ? $meta : $field['std'] . '</strong>';
                } else {
					echo 'This question was added by Admin';
				}
				break;                
        }
        echo     '<td>',
            '</tr>';
    }
    
    echo '</table>';
}
