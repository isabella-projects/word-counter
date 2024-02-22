<?php

/*
    Plugin Name: Word Counter
    Plugin URI: https://github.com/isabella-projects/word-counter
    Description: Word counter plugin for WordPress blog posts
    Version: 1.0
    Author: D. Minkov
    Author URI: https://github.com/isabella-projects
    Text Domain: wcp_domain
    Domain Path: /languages
*/

class WordCounter
{
    public function __construct()
    {
        add_filter('admin_menu', [$this, 'adminPage']);
        add_action('admin_init', [$this, 'settings']);
        add_filter('the_content', [$this, 'conditions']);
        add_action('init', [$this, 'languages']);
    }

    public function adminPage()
    {
        add_options_page(__('Word Count Settings', 'wcp_domain'), __('Word Counter', 'wcp_domain'), 'manage_options', 'word-count-settings', [$this, 'pluginDashboard']);
    }

    public function pluginDashboard()
    {
        require_once('templates/dashboard.template.php');
    }

    public function settings()
    {
        add_settings_section('wcp_section', null, null, 'word-count-settings');

        // Display location
        add_settings_field('wcp_location', __('Display Location', 'wcp_domain'), [$this, 'locationHTML'], 'word-count-settings', 'wcp_section');
        register_setting('wordcount', 'wcp_location', [
            'sanitize_callback' => [
                $this,
                'sanitizeLocation'
            ],
            'default' => '0'
        ]);

        // Headline text
        add_settings_field('wcp_headline', __('Headline Text', 'wcp_domain'), [$this, 'headlineHTML'], 'word-count-settings', 'wcp_section');
        register_setting('wordcount', 'wcp_headline', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => __('Post Statistics', 'wcp_domain')
        ]);

        // Word count
        add_settings_field('wcp_wordcount', __('Word Count', 'wcp_domain'), [$this, 'checkBoxHTML'], 'word-count-settings', 'wcp_section', [
            'prop' => 'wcp_wordcount'
        ]);
        register_setting('wordcount', 'wcp_wordcount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);

        // Character count
        add_settings_field('wcp_charcount', __('Character Count', 'wcp_domain'), [$this, 'checkBoxHTML'], 'word-count-settings', 'wcp_section', [
            'prop' => 'wcp_charcount'
        ]);
        register_setting('wordcount', 'wcp_charcount', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);

        // Read time
        add_settings_field('wcp_readtime', __('Read Time', 'wcp_domain'), [$this, 'checkBoxHTML'], 'word-count-settings', 'wcp_section', [
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
        if ($input !== '0' && $input !== '1') {
            add_settings_error('wcp_location', 'wcp_location_error', __('Display location must be either Beginning or End', 'wcp_domain'));
            return get_option('wcp_location');
        }
        return $input;
    }

    public function conditions($content)
    {
        if (
            is_main_query() && is_single() &&
            (
                get_option('wcp_wordcount', '1') ||
                get_option('wcp_charcount', '1') ||
                get_option('wcp_readtime', '1')
            )
        ) {
            return $this->createHTML($content);
        }
        return $content;
    }

    private function createHTML($content)
    {
        $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

        if (get_option('wcp_wordcount', '1') || get_option('wcp_readtime', '1')) {
            $wordCount = str_word_count(strip_tags($content));
        }

        if (get_option('wcp_wordcount', '1')) {
            $html .= __('This post has', 'wcp_domain') . ' ' . $wordCount . ' ' . __('words', 'wcp_domain') . '.<br>';
        }

        if (get_option('wcp_charcount', '1')) {
            $html .= __('This post has', 'wcp_domain') . ' ' . strlen(strip_tags($content)) . ' ' . __('characters', 'wcp_domain') . '.<br>';
        }

        if (get_option('wcp_readtime', '1')) {
            $readTime = $this->calculateReadTime($wordCount);
            $html .= __('This post will take', 'wcp_domain') . ' ' . $readTime . ' ' . __('to read', 'wcp_domain') . '.<br>';
        }

        $html .= '</p>';

        if (get_option('wcp_location', '0') == '0') {
            return $html . $content;
        }
        return $content . $html;
    }

    public function languages()
    {
        load_plugin_textdomain('wcp_domain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    private function calculateReadTime($wordCount)
    {
        $readTimeInMinutes = $wordCount / 225;
        $readTimeMinutes = round($readTimeInMinutes);

        if ($readTimeMinutes < 1) {
            return __('less than', 'wcp_domain') . '<strong>' . ' ' . __('1 minute', 'wcp_domain') . '</strong>';
        } else {
            return __('about', 'wcp_domain') . ' ' . '<strong>' . $readTimeMinutes . ' ' . __('minute', 'wcp_domain') . ($readTimeMinutes < 1 ? __('s', 'wcp_domain') : '') . '</strong>';
        }
    }
}

$wordCounter = new WordCounter();
