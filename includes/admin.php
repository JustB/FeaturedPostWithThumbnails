<?php
/*
 * Add Featured column in Admin post list
 */
add_filter('manage_posts_columns', 'fpwt_add_featured_column');

function fpwt_add_featured_column($defaults) {
    $defaults['yiw-featured'] = __('Featured', YIW_TEXT_DOMAIN);
    return $defaults;
}

/*
 * Recupera dal database tutti i post che hanno il custom field featured
 * attivato
 */
add_action('manage_posts_custom_column', 'fpwt_render_featured_column', 10, 2);

function fpwt_render_featured_column($column_name, $id) {
    if ( $column_name == 'yiw-featured' ) {
        if(get_post_meta($id, '_yiw_featured_post', true)) {
            _e("Yes", YIW_TEXT_DOMAIN);
        } else {
            _e("No", YIW_TEXT_DOMAIN);
        }
    }
}
?>