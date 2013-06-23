<?php
/**
 * @package Featured Posts
 * @author Nando Pappalardo e Giustino Borzacchiello
 * @version {{VERSIONE}}
 */
?>
<label for="insert_featured_post"><?php _e('Featured post?', YIW_TEXT_DOMAIN) ?></label>
<select name="insert_featured_post" id="insert_featured_post">
    <option value="yes" <?php selected('yes', $is_featured, true) ?>>
       <?php _e('Yes', YIW_TEXT_DOMAIN) ?>
    </option>
    <option value="no" <?php selected('no', $is_featured, true) ?>>
        <?php _e('No ', YIW_TEXT_DOMAIN) ?>
    </option>
</select>