<select name="wcp_location">
    <option value="0" <?php selected(get_option('wcp_location'), '0'); ?>><?php _e('At the beginning of post', 'wcp_domain'); ?></option>
    <option value="1" <?php selected(get_option('wcp_location'), '1'); ?>><?php _e('At the end of post', 'wcp_domain'); ?></option>
</select>