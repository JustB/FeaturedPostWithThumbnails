<?php
echo '<p>';
echo '<label for="' . $this->get_field_id('title') . '">' .
    _e('Title:', YIW_TEXT_DOMAIN) . '</label>';
echo '<input id="' . $this->get_field_id('title') . '" name="' .
    $this->get_field_name('title') . '" value="' . $instance['title'] .
    '" style="width:100%;" class="widefat" />';
echo '</p>';

echo '<p>';
echo '<label for="' . $this->get_field_id('showposts') . '">' .
    _e('How many posts do you want to display?', YIW_TEXT_DOMAIN) . '</label>';
echo '<select name="' . $this->get_field_name('showposts') .
    '" id="' . $this->get_field_id('showposts') . '" >';
for ($i = 0; $i <= 100; $i++) {
    echo '<option class="level-0" value="' . $i . '"' . selected($instance['showposts'], $i) . '>' . $i . '</option>';
}
echo '</select>';
echo '</p>';
echo '<p>';
echo '<label for="' . $this->get_field_id('orderby') . '">' .
    __('Choose type of order:', YIW_TEXT_DOMAIN) . '</label>';
echo '<select name="' . $this->get_field_name('orderby') . '" id="' .
    $this->get_field_id('orderby') . '" >';
echo '<option class="level-0" value="rand" ' . selected($instance['orderby'], 'random') . ' >' .
    __('Random', YIW_TEXT_DOMAIN) . '</option>';
echo '<option class="level-0" value="title" ' . selected($instance['orderby'], 'title') . ' >' .
    __('Title', YIW_TEXT_DOMAIN) . '</option>';
echo '<option class="level-0" value="date" ' . selected($instance['orderby'], 'date') . ' >' .
    __('Date', YIW_TEXT_DOMAIN) . '</option>';
echo '<option class="level-0" value="author" ' . selected($instance['orderby'], 'author') . ' >' .
    __('Author', YIW_TEXT_DOMAIN) . '</option>';
echo '<option class="level-0" value="modified" ' . selected($instance['orderby'], 'modified') . ' >' .
    __('Modified', YIW_TEXT_DOMAIN) . '</option>';
echo '<option class="level-0" value="ID" ' . selected($instance['orderby'], 'ID') . ' >' .
    __('ID', YIW_TEXT_DOMAIN) . '</option>';
echo '</select>';
echo '</p>';
echo '<p>';
echo '<label for="' . $this->get_field_id('width-thumb') . '">' .
    __('Width Thumbnail', YIW_TEXT_DOMAIN) . '</label>';
echo '<input id="' . $this->get_field_id('width-thumb') .
    '" name="' . $this->get_field_name('width-thumb') . '" value="' .
    $instance['width-thumb'] . '" style="width:20%;" class="widefat" />';
echo '</p>';
echo '<p>';
echo '<label for="' . $this->get_field_id('height-thumb') . '">' .
    __('Height Thumbnail', YIW_TEXT_DOMAIN) . '</label>';
echo '<input id="' . $this->get_field_id('height-thumb') .
    '" name="' . $this->get_field_name('height-thumb') . '" value="' .
    $instance['height-thumb'] . '" style="width:20%;" class="widefat" />';
echo '</p>';
echo '<p id="yiw_featured_post_show">';
echo '<label for="' . $this->get_field_id('show') . '">' . _e("Featured or category?", YIW_TEXT_DOMAIN) . '</label>';
echo '<select id="' . $this->get_field_id('show') . '" name="' . $this->get_field_name('show') . '">';
echo '<option value="featured" ' . selected($instance['show'], 'featured') . '>Featured</option>';
echo '<option value="category" ' . selected($instance['show'], 'category') . '>Category</option>';
echo '</select>';
echo '</p>';

echo '<p id="yiw_featured_post_category">';
echo '<label for="' . $this->get_field_id('category') . '">' . _e('Category', YIW_TEXT_DOMAIN) . '</label>';
echo '<select id="' . $this->get_field_id('category') . '" name="' . $this->get_field_name('category') . '">';
$categories = get_categories();
foreach ($categories as $c) {
    echo '<option value="'. $c->cat_ID .'" ' . selected($instance['category'], $c->cat_ID) . '>'. $c->name .'</option>';
}
echo '</select>';
echo '</p>';