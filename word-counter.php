<?php

/*
    Plugin Name: Word Counter
    Plugin URI: https://github.com/isabella-projects/word-counter
    Description: Small & simple WordPress plugin
    Version: 1.0
    Author: D. Minkov
    Author URI: https://github.com/isabella-projects
*/

class WordCounter
{
    public function __construct()
    {
        add_filter('admin_menu', [$this, 'adminPage']);
        add_action('admin_init', [$this, 'settings']);
    }

    public function adminPage()
    {
        add_options_page('Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings', [$this, 'pluginDashboard']);
    }

    public function pluginDashboard()
    {
        require_once('templates/dashboard.template.php');
    }

    public function settings()
    {
        add_settings_section('wcp_section', null, null, 'word-count-settings');

        // Display location
        add_settings_field('wcp_location', 'Display Location', [$this, 'locationHTML'], 'word-count-settings', 'wcp_section');
        register_setting('wordcount', 'wcp_location', [
            'sanitize_callback' => [
                $this,
                'sanitizeLocation'
            ],
            'default' => '0'
        ]);

        // Headline text
        add_settings_field('wcp_headline', 'Headline Text', [$this, 'headlineHTML'], 'word-count-settings', 'wcp_section');
        register_setting('wordcount', 'wcp_headline', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Post Statistics'
        ]);

        // Word count
        add_settings_field('wcp_wordcount', 'Word Count', [$this, 'checkBoxHTML'], 'word-count-settings', 'wcp_section', [
            'prop' => 'wcp_wordcount'
        ]);
        register_setting('wordcount', 'wcp_wordcount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);

        // Character count
        add_settings_field('wcp_charcount', 'Character Count', [$this, 'checkBoxHTML'], 'word-count-settings', 'wcp_section', [
            'prop' => 'wcp_charcount'
        ]);
        register_setting('wordcount', 'wcp_charcount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);

        // Read time
        add_settings_field('wcp_readtime', 'Read Time', [$this, 'checkBoxHTML'], 'word-count-settings', 'wcp_section', [
            'prop' => 'wcp_readtime'
        ]);
        register_setting('wordcount', 'wcp_readtime', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);
    }

    public function locationHTML()
    {
        require_once('templates/partials/location.template.php');
    }

    public function headlineHTML()
    {
        require_once('templates/partials/headline.template.php');
    }

    public function checkBoxHTML($args)
    {
        require('templates/partials/checkbox.template.php');
    }

    public function sanitizeLocation($input)
    {
        if ($input !== 0 && $input !== 1) {
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either Beginning or End');
            return get_option('wcp_location');
        }
        return $input;
    }
}

$wordCounter = new WordCounter();
