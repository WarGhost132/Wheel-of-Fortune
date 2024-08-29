<?php

// Test environment for handling the root path problem.
define('TEST_ENVIRONMENT', true);
if (TEST_ENVIRONMENT) {
    /**
     * @var string $ROOT_PATH The root path of the application in the test environment.
     */
    define('ROOT_PATH', '/');
} else {
    /**
     * @var string $ROOT_PATH The root path of the application in the production environment.
     */
    define('ROOT_PATH', '/');
}

define('USE_CLOAKING', true);
if (USE_CLOAKING) {
    define('IP_LIST_PATH', "includes/data/ips/ip_list.txt");
    define('USER_AGENTS_PATH', "includes/data/blocked_user_agents.txt");
}
