<div class="wrap">
    <h1><?php _e('Word Count Settings', 'wcp_domain'); ?></h1>
    <form action="options.php" method="POST">
        <?php
        settings_fields('wordcount');
        do_settings_sections('word-count-settings');
        submit_button();
        ?>
    </form>
</div>