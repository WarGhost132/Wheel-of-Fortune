<?php

use GeoIp2\Database\Reader;

/*
    CDN Setup Section
*/

/**
 * Include the top CDNs, located in the head tag.
 *
 * @return void
 */
function include_top_cdns(): void
{
?>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fonts -->
    <link href="https://fonts.cdnfonts.com/css/rubik-2" rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="assets/styles/style.css">
<?php
}

/**
 * Include the bottom CDNs, located at the bottom of the body tag,
 * and dynamically include modal PHP files from the specified directory.
 *
 * @return void
 */
function include_bottom_cdns(): void
{
    // Include modal PHP files from a specified directory
    $modals_directory = __DIR__ . '/components/modals';
    if (is_dir($modals_directory)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($modals_directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
                include $file->getRealPath();
            }
        }
    }
?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Scripts -->
    <script src="assets/scripts/index.js"></script>
<?php
}

/*
    Utility Section
*/

/**
 * Update a parameter in the current URL and return the modified link.
 *
 * @param  string $url   The URL to modify.
 * @param  string $name  The name of the parameter to update.
 * @param  string $value The value of the parameter.
 * @return string The modified URL.
 */
function update_parameter(string $url, string $name, string $value): string
{
    $parsed_url = parse_url($url);
    $query = isset($parsed_url['query']) ? $parsed_url['query'] : '';
    parse_str($query, $params);
    if (!empty($value)) {
        $params[$name] = $value;
    } else {
        unset($params[$name]); // Remove the parameter if value is empty
    }
    $query_string = http_build_query($params);
    // Remove leading '?' if query string is empty
    $query_string = empty($query_string) ? '' : '?' . $query_string;
    $current_url = isset($parsed_url['path']) ? $parsed_url['path'] . $query_string : '';
    return $current_url;
}

/**
 * Change the language of the page.
 *
 * @param  string $lang The language to change the page to.
 * @return string The URL with the updated language parameter.
 */
function update_current_page_with_language(string $lang): string
{
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'
        ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $path = (string) parse_url($current_url, PHP_URL_PATH);

    $segments = explode('/', rtrim($path, '/'));
    $lastSegment = end($segments);

    if ($lastSegment === 'index') {
        array_pop($segments);
    }
    $newPath = implode('/', $segments);
    $newUrl = update_parameter($newPath, 'lang', $lang);
    return $newUrl;
}

/*
    Page Content Section
*/

/**
 * Get the content of a page.
 *
 * @param  string $page_name The name of the page to get content for.
 * @return array<string, string> The page content including title, description, and body.
 */
function get_page_content(string $page_name, string $switcher): array
{
    global $PAGE_CONTENT;
    $filePath = "pages/{$switcher}/{$page_name}.php";
    if (file_exists($filePath)) {
        ob_start();
        include $filePath;
        $PAGE_CONTENT = ob_get_clean();
        return [
            'title' => $PAGE_TITLE ?? 'Default Title',
            'description' => $PAGE_DESCRIPTION ?? 'Default description',
            'body' => $PAGE_CONTENT
        ];
    }

    // Return Not Found page
    return [
        'title' => '404 Not Found',
        'description' => 'Page not found',
        'body' => ''
    ];
}

/**
 * Loads a blocked list of IP addresses and subnets from the predefined file.
 *
 * @return array<int, string> The blocked list of IP addresses and subnets.
 */
function load_blocked_ip_list(): array
{
    $blocked_ip_list = [];

    if (defined('IP_LIST_PATH') && file_exists(IP_LIST_PATH)) {
        $file_content = file(IP_LIST_PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($file_content !== false) {
            foreach ($file_content as $line) {
                $trimmed_line = trim($line);
                if ($trimmed_line !== '' && strpos($trimmed_line, '#') !== 0) {
                    $blocked_ip_list[] = $trimmed_line;
                }
            }
        }
    }

    return $blocked_ip_list;
}

/**
 * Filters a list of IP addresses and subnets, removing invalid entries.
 *
 * @param array<int, string> $blocked_ip_list The list of IP addresses and subnets.
 * @return array<int, string> The filtered list of valid IP addresses and subnets.
 */
function filter_blocked_ip_list(array $blocked_ip_list): array
{
    $filtered_list = [];

    foreach ($blocked_ip_list as $ip) {
        if (
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ||
            preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}\/[0-9]{1,2}$/', $ip) ||
            preg_match('/^[0-9a-fA-F:]+\/[0-9]{1,3}$/', $ip)
        ) {
            $filtered_list[] = $ip;
        }
    }

    return $filtered_list;
}

/**
 * Checks if a given IP address is in the provided list of IP addresses and subnets.
 *
 * @param string $ip The IP address to check.
 * @param array<int, string> $blocked_ip_list The list of IP addresses and subnets.
 * @return bool True if the IP address is in the list, false otherwise.
 */
function ip_in_blocked_list(string $ip, array $blocked_ip_list): bool
{
    foreach ($blocked_ip_list as $range) {
        if ($ip === $range) {
            return true;
        }
    }
    return false;
}

/**
 * Checks if a given User-Agent or Referer indicates a bot.
 *
 * @param string $user_agent The User-Agent string to check.
 * @param string $referer The Referer string to check.
 * @return bool True if the User-Agent or Referer indicates a bot, false otherwise.
 */
function check_user_agent_referer(string $user_agent, string $referer): bool
{
    $bot_agents = [
        'Googlebot',
        'Bingbot',
        'Slurp',
        'DuckDuckBot',
        'Baiduspider',
        'YandexBot',
        'Sogou',
        'Exabot',
        'facebot',
        'ia_archiver'
    ];
    foreach ($bot_agents as $bot_agent) {
        if (stripos($user_agent, $bot_agent) !== false) {
            return true;
        }
    }

    $bot_referers = ['google.com', 'bing.com', 'yahoo.com', 'duckduckgo.com', 'baidu.com', 'yandex.com'];
    foreach ($bot_referers as $bot_referer) {
        if (stripos($referer, $bot_referer) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * Checks if a given User-Agent is in the blocked list.
 *
 * @param string $user_agent The User-Agent string to check.
 * @return bool True if the User-Agent is in the blocked list, false otherwise.
 */
function is_user_agent_blocked(string $user_agent): bool
{
    if (defined('USER_AGENTS_PATH') && file_exists(USER_AGENTS_PATH)) {
        $file_content = file(USER_AGENTS_PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($file_content)) {
            foreach ($file_content as $line) {
                if (stripos($user_agent, $line) !== false) {
                    return true;
                }
            }
        }
    }

    return false;
}

/**
 * Determines the switch value based on various conditions.
 *
 * @return string Returns 'white' if any condition is true, otherwise returns 'black'.
 */
function determine_switch(): string
{
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    $all_ips = load_blocked_ip_list();

    // Filter the IP list
    $filtered_ips = filter_blocked_ip_list($all_ips);

    if (
        ip_in_blocked_list($user_ip, $filtered_ips) ||
        check_user_agent_referer($user_agent, $referer) ||
        is_user_agent_blocked($user_agent)
    ) {
        return 'white';
    }

    return 'black';
}

?>