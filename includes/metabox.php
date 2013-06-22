<?php
add_action('admin_menu', 'my_post_options_box');

function my_post_options_box() {
    add_meta_box('post_info',
        __('Featured', YIW_TEXT_DOMAIN),
        'YIW_post_box',
        'post',
        'side',
        'high');
}

/**
 * Shows featured form in "Write Post" section
 */
function YIW_post_box() {
    global $post;
    $yes = '';
    $no = '';
    $featured = get_post_meta($post->ID, 'featured', 1);
    if ( $featured ) {
        $yes = 'selected="selected"';
    } else {
        $no = 'selected="selected"';
    }
    echo '<label for="insert_featured_post">' .
        __('Featured post?', YIW_TEXT_DOMAIN) . '</label>';
    echo '<select name="insert_featured_post" id="insert_featured_post">';
    echo '<option value="yes" ' . $yes . ' >' .
        __('Yes', YIW_TEXT_DOMAIN) . '</option>';
    echo '<option value="no" ' . $no . ' >' .
        __('No ', YIW_TEXT_DOMAIN) . '</option>';
    echo '</select>';
}

/**
 * Add/remove featured custom field
 *
 * @param integer $post_ID
 */
function YIW_add_featured($post_ID) {
    $articolo = get_post($post_ID);
    if ( isset($_POST['insert_featured_post']) ) {
        if ( $_POST['insert_featured_post'] == 'yes' ) {
            add_post_meta($articolo->ID, 'featured', 1, TRUE) ||
                update_post_meta($articolo->ID, 'featured', 1);
        } elseif ( $_POST['insert_featured_post'] == 'no' ) {
            delete_post_meta($articolo->ID, 'featured');
        }
    }
}

add_action('new_to_publish', 'YIW_add_featured');
add_action('save_post', 'YIW_add_featured');