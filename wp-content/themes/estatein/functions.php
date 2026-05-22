<?php

if (! defined('ABSPATH')) {
    exit;
}

define('ESTATEIN_VERSION', wp_get_theme()->get('Version'));
define('ESTATEIN_DIR', get_template_directory());
define('ESTATEIN_URI', get_template_directory_uri());

require_once ESTATEIN_DIR . '/inc/theme-setup.php';
require_once ESTATEIN_DIR . '/inc/custom-post-types.php';
require_once ESTATEIN_DIR . '/inc/acf-fields.php';
