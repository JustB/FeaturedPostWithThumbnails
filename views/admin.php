<?php
/**
 * @var $this Featured_posts
 * @var $instance array Widget options
 */
?>
<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">
        <?php _e('Title:', YIW_TEXT_DOMAIN); ?>
    </label>
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
           value="<?php echo esc_attr($instance['title']); ?>"
           style="width:100%;" class="widefat"/>
</p>
<p>
    <label for="<?php echo $this->get_field_id('showposts') ?>">
        <?php _e('How many posts do you want to display?', YIW_TEXT_DOMAIN); ?>
    </label>
    <select name="<?php echo $this->get_field_name('showposts'); ?>"
            id="<?php echo $this->get_field_id('showposts') ?>">
        <?php for ($i = 0; $i <= 100; $i++) : ?>
        <option class="level-0"
                value="<?php echo $i ?>" <?php selected($instance['showposts'], $i) ?>><?php echo $i ?></option>
        <?php endfor; ?>
    </select>
</p>


<p>
    <label for="<?php $this->get_field_id('order-by') ?>">
        <?php _e('Choose type of order:', YIW_TEXT_DOMAIN) ?>
    </label>
    <select id="<?php echo $this->get_field_id('order-by'); ?>"
            name="<?php echo $this->get_field_name('order-by'); ?>">
        <option class="level-0" value="rand" <?php selected('rand', $instance['order-by'], true); ?>><?php _e('Random', YIW_TEXT_DOMAIN); ?></option>
        <option class="level-0" value="title" <?php selected('title', $instance['order-by'], true); ?>><?php _e('Title', YIW_TEXT_DOMAIN); ?></option>
        <option class="level-0" value="date" <?php selected('date', $instance['order-by'], true) ?>><?php _e('Date', YIW_TEXT_DOMAIN) ?></option>
        <option class="level-0" value="author" <?php selected('author', $instance['order-by'], true) ?>><?php _e('Author', YIW_TEXT_DOMAIN) ?></option>
        <option class="level-0" value="modified" <?php selected('modified', $instance['order-by'], true) ?>><?php _e('Modified', YIW_TEXT_DOMAIN) ?></option>
        <option class="level-0" value="ID" <?php selected('ID', $instance['order-by'], true) ?>><?php _e('ID', YIW_TEXT_DOMAIN) ?></option>
    </select>
</p>

<p>
    <label for="<?php echo $this->get_field_id('width-thumb') ?>">
        <?php _e('Width Thumbnail', YIW_TEXT_DOMAIN); ?>
    </label>
    <input id="<?php echo $this->get_field_id('width-thumb') ?>"
           name="<?php echo $this->get_field_name('width-thumb') ?>"
           value="<?php echo esc_attr($instance['width-thumb']); ?>" style="width:20%;" class="widefat"/>
</p>
<p>
    <label for="<?php echo $this->get_field_id('height-thumb') ?>">
        <?php _e('Height Thumbnail', YIW_TEXT_DOMAIN) ?>
    </label>
    <input id="<?php echo $this->get_field_id('height-thumb') ?>"
           name="<?php echo $this->get_field_name('height-thumb') ?>"
           value="<?php echo esc_attr($instance['height-thumb']) ?>" style="width:20%;" class="widefat"/>
</p>

<p id="yiw_featured_post_show">
    <label for="<?php $this->get_field_id('show') ?>"><?php _e("Featured or category?", YIW_TEXT_DOMAIN) ?></label>
    <select id="<?php echo $this->get_field_id('show') ?>" name="<?php echo $this->get_field_name('show') ?>">
        <option value="featured" <?php selected($instance['show'], 'featured') ?>>
            <?php _e('Featured', YIW_TEXT_DOMAIN) ?>
        </option>
        <option value="category" <?php selected($instance['show'], 'category') ?>>
            <?php _e('Category', YIW_TEXT_DOMAIN) ?>
        </option>
    </select>
</p>
<p id="yiw_featured_post_category">
    <label for="<?php echo $this->get_field_id('category') ?>"><?php _e('Category', YIW_TEXT_DOMAIN) ?></label>
    <select id="<?php $this->get_field_id('category') ?>" name="<?php $this->get_field_name('category') ?>">
        <?php
        $categories = get_categories();
        foreach ($categories as $c): ?>
            <option value="<?php echo $c->cat_ID ?>" <?php selected($instance['category'], $c->cat_ID) ?>>
                <?php echo $c->name ?>
            </option>
            <?php endforeach; ?>
    </select>
</p>
