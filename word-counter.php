<?php

/*
    Plugin Name: Test Plugin
    Plugin URI: https://github.com/isabella-projects/word-counter
    Description: First plugin description
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
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '0'
        ]);

        // Headline text
        add_settings_field('wcp_headline', 'Headline Text', [$this, 'headlineHTML'], 'word-count-settings', 'wcp_section');
        register_setting('wordcount', 'wcp_headline', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Post Statistics'
        ]);

        // Word count
        add_settings_field('wcp_wordcount', 'Word Count', [$this, 'wordCountHTML'], 'word-count-settings', 'wcp_section');
        register_setting('wordcount', 'wcp_wordcount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);

        // Character count
        add_settings_field('wcp_charcount', 'Character Count', [$this, 'charCountHTML'], 'word-count-settings', 'wcp_section');
        register_setting('wordcount', 'wcp_charcount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);

        // Read time
        add_settings_field('wcp_readtime', 'Read Time', [$this, 'readTimeHTML'], 'word-count-settings', 'wcp_section');
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

    public function wordcountHTML()
    {
        require_once('templates/partials/wordcount.template.php');
    }

    public function charCountHTML()
    {
        require_once('templates/partials/charcount.template.php');
    }

    public function readTimeHTML()
    {
        require_once('templates/partials/readtime.template.php');
    }
}

$wordCounter = new WordCounter();
