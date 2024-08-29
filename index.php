<?php

require 'config.php';
require 'includes/functions.php';

/**
 * Starts the session and handles localization and routing for the Simple Website project.
 */

// Start the session
session_start();

/**
 * Get the requested URL or default to 'index' if not set.
 *
 * @return string $PAGE_URL The requested URL.
 */
$PAGE_URL = isset($_GET['url']) ? $_GET['url'] : 'index';

/**
 * Determine the name of the requested page.
 *
 * @param string $PAGE_URL The URL of the requested page.
 * @return string $PAGE_NAME The name of the requested page.
 */
$PAGE_NAME = ($PAGE_URL === '') ? 'index' : $PAGE_URL;

if (USE_CLOAKING) {
    $switcher = determine_switch();
} else {
    $switcher = 'white';
}

/**
 * Get the content of the requested page.
 *
 * @param string $PAGE_NAME The name of the requested page.
 * @return array<string, string> $PAGE_CONTENT The content of the requested page.
 */
$PAGE_CONTENT = get_page_content($PAGE_NAME, $switcher);

// If the page content is not found, load the 404 page content
if (empty($PAGE_CONTENT['body'])) {
    $PAGE_CONTENT = get_page_content('404', $switcher);
}

// Include the template to render the page with the content
require "includes/templates/$switcher-template.php";
